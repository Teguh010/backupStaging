<?php
/**
 * *
 *  * Created by PhpStorm.
 *  * User: boonkhailim
 *  * Year: 2017
 *
 */

class Model_question extends CI_Model{

    function __construct(){
        parent::__construct();
        $this->load->model('Model_users', 'm_user');
    }

    // WS TID
    public function get_topiclevel_list($level=NULL) {
        $this->db->select('*');
        if(isset($level) && empty($level) == false) {
            $this->db->where("level like '".$level."'"); 
        }
        $this->db->where("branch_id like '".BRANCH_ID."'"); 
        $this->db->from('sj_topics_tid');
        $this->db->order_by('topic_id', 'ASC');
        return $this->db->get()->result_array();
    }

    public function get_ability_list() {
        $this->db->select('*');
        $this->db->where("branch_id like '".BRANCH_ID."'"); 
        $this->db->from('sj_ability_tid');
        $this->db->order_by('ability_id', 'ASC');
        return $this->db->get()->result_array();
    }

     public function get_level_tid_by_id($level_code = NULL)
    {
        if(isset($level_code) && empty($level_code) == false) {
            $sql = "SELECT * FROM `sj_levels_tid` WHERE `level_code` ='".$level_code."' and `branch_id`=".BRANCH_ID;
        } else {
            $sql = "SELECT * FROM `sj_levels_tid` WHERE `branch_id`=".BRANCH_ID;
        }
        
        $query = $this->db->query($sql);

        $result = $query->row();

        if($query->num_rows() > 0) {
            return $result->level_name;
        } else {
            return '-';
        }
    }

    function getMoreQuestions($start){
        // $getData = array(
        //    'id' => '18795',
        //     'level'=> 'junior',
        //     'queType'=>  '1',
        //     'generate'=>  'true',
        //     'topic'=> 'all',
        //     'ability'=> 'all',
        //     'que_type' => '1',
        //     'difficulties'=> '2',
        //     'search'=> 'false',
        //     'searchKeyword'=> ''
        // );
        $getData = $this->input->get();            
        $generate   = $getData['generate'];
        $search     = $getData['search'];
        if($generate === 'true'){
            $result = $this->generateEditQuestion($start, $getData);
            return $result;
        }else if($search === 'true'){
            $result = $this->searchEditQuestion($start, $getData);
            return $result;
        }else{
            $id     = $getData['id'];
            $queType= $getData['queType'];
            $this->db->select('a.*, b.tid_level, b.tid_topic_id, b.ability, b.difficulty');
            $this->db->from('sj_generate_questions a');
            $this->db->join('sj_questions b', 'a.question_id=b.question_id');
            $this->db->where('a.id', $id);
            $row = $this->db->get()->row_array();
            $questionID = $row['question_id'];
            $levelID = $row['tid_level'];            
            $topicID = $row['tid_topic_id'];
            $difficulty = $row['difficulty'];
            $abilityID = $row['ability_req'];
            $reqTopic = $row['topic_req'];
            $reqOperator = $row['operator_req'];
            $reqDifficulty = $row['difficulty_req'];
            $date_created = date('Y-m-d H:i:s');
            $list_question = $this->db->get_where('sj_generate_questions', ['requirement_id' => $this->session->userdata('requirementId')])->result_array();
            $question_id = array();
            foreach($list_question as $row) {
                $question_id[] = $row['question_id'];
            }
            $opt = ($reqOperator=="" || $reqOperator=="1") ? "AND" : "OR";
            $ability = ($abilityID=="all") ? " a.ability>=0 " : "a.ability='".$abilityID."'";
            $sql  = "SELECT a.question_id, a.question_text, a.level_id, a.subject_type, a.difficulty, a.graphical, a.tid_topic_id, a.ability, b.branch_image_url FROM sj_questions a 
                JOIN sj_branch b ON a.branch_name = b.branch_name 
                WHERE a.tid_level='$levelID' AND a.disabled=0 AND a.sub_question='A' AND a.is_tid=1
                AND ($ability $opt a.difficulty IN ($reqDifficulty) ) AND a.question_id NOT IN (".implode(",", $question_id).") ";

            if($reqTopic !== 'all' && $reqTopic !== ''){
                $sql .= "AND a.tid_topic_id='$reqTopic' ";
                $row = $this->db->get_where('sj_topics_tid', ['topic_id' => $reqTopic])->row_array();
                $result['reqTopic'] = ($row) ? $row['topic_name'] : 'Any Topic';
            } else {
                $result['reqTopic'] = 'Any Topic';
            }

            if($queType == 1){
                $sql .= "AND a.question_type_id IN (1,4) ";
            }else if($queType == 2){
                $sql .= "AND a.question_type_id IN (1,2) ";
            }
            
            $result['total_rows'] = $this->db->query($sql." LIMIT 1000")->num_rows();

            $sql .= "LIMIT 10 OFFSET $start";
            $result['data'] = $this->db->query($sql)->result();
            
            return $result;
        }
    } 

    function searchEditQuestion($start, $getData){
        $levelID        = $getData['level'];
        $subjectID      = $getData['subject'];
        $searchKeyword  = $getData['searchKeyword'];

        //$starttime = microtime(true);
        $list_question = $this->db->get_where('sj_generate_questions', ['requirement_id' => $this->session->userdata('requirementId')])->result_array();
        $question_id = array();
        foreach($list_question as $row) {
            $question_id[] = $row['question_id'];
        }
        $question_id = implode(",", $question_id);


        $query = $this->db->query("SELECT question_id FROM sj_questions WHERE question_text LIKE '%$searchKeyword%' LIMIT 5000");
        $query_question_id = array();
        if($query->num_rows() > 0){
            foreach($query->result_array() as $row) {
                $query_question_id[] = $row['question_id'];
            }
            $query_question_id = implode(",", $query_question_id);
            $queryQuestion = " OR a.question_id IN ($query_question_id)";
        }else{
            $queryQuestion = '';
        }   

        $query = $this->db->query("SELECT * FROM sj_topics_tid WHERE topic_name LIKE '%$searchKeyword%' ");
        $topic_id = array();
        if($query->num_rows() > 0){
            foreach($query->result_array() as $row) {
                $topic_id[] = $row['topic_id'];
            }
            $topic_id = implode(",", $topic_id);
            $queryTopic = " OR a.tid_topic_id IN ($topic_id)";
        }else{
            $queryTopic = '';
        }         

        $query = $this->db->query("SELECT * FROM sj_ability_tid WHERE ability_name LIKE '%$searchKeyword%' ");
        $strategy_id = array();
        if($query->num_rows() > 0){
            foreach($query->result_array() as $row) {
                $strategy_id[] = $row['ability_id'];
            }
            $strategy_id = implode(",", $strategy_id);
            $queryStrategy = " OR a.ability IN ($strategy_id)";
        }else{
            $queryStrategy = '';
        }            
        
        $sql  = "SELECT DISTINCT(a.question_id), a.question_text, a.level_id, a.subject_type, a.difficulty, a.graphical, 
                    b.branch_image_url, c.topic_name AS topic_name, d.ability_name AS ability_name FROM sj_questions a 
                    JOIN sj_branch b ON a.branch_name = b.branch_name 
                    JOIN sj_topics_tid c ON a.tid_topic_id=c.topic_id 
                    JOIN sj_ability_tid d ON a.ability=d.ability_id                       
                    WHERE a.tid_level='$levelID' AND a.disabled=0 AND a.sub_question='A' 
                    AND question_id NOT IN ($question_id) AND question_type_id IN (1,2,4) 
                    AND concat('.', a.question_text, '.', c.topic_name, '.', d.ability_name, '.') LIKE '%$searchKeyword%' ";

        if($queryQuestion != '' || $queryTopic != '' || $queryStrategy != ''){                
            $result['total_rows'] = $this->db->query($sql." LIMIT 5000")->num_rows();
            $sql .= "LIMIT 10 OFFSET $start";
            $result['data'] = $this->db->query($sql)->result();   

            return $result;
        }else{                
            $result['total_rows'] = 0;
            $result['data'] = '';
            return $result;
        }
                    
    }

    function generateEditQuestion($start, $getData){
        $id     = $getData['id'];
        $queType= $getData['queType'];
        $this->db->select('a.*, b.tid_level, b.tid_topic_id, b.ability, b.difficulty');
        $this->db->from('sj_generate_questions a');
        $this->db->join('sj_questions b', 'a.question_id=b.question_id');
        $this->db->where('a.id', $id);
        $row = $this->db->get()->row_array();
        $questionID = $row['question_id'];
        $levelID = $row['tid_level'];            
        $topicID = $row['tid_topic_id'];
        $difficulty = $row['difficulty'];
        $abilityID = $row['ability_req'];
        $reqTopic = $row['topic_req'];
        $reqOperator = $row['operator_req'];
        $reqDifficulty = $row['difficulty_req'];
        $date_created = date('Y-m-d H:i:s');
        $list_question = $this->db->get_where('sj_generate_questions', ['requirement_id' => $this->session->userdata('requirementId')])->result_array();
        $question_id = array();
        foreach($list_question as $row) {
            $question_id[] = $row['question_id'];
        }
        $opt = ($reqOperator=="" || $reqOperator=="1") ? "AND" : "OR";
        $ability = ($abilityID=="all") ? " a.ability>=0 " : "a.ability='".$abilityID."'";
        $sql  = "SELECT a.question_id, a.question_text, a.level_id, a.subject_type, a.difficulty, a.graphical, a.tid_topic_id, a.ability, b.branch_image_url FROM sj_questions a 
            JOIN sj_branch b ON a.branch_name = b.branch_name 
            WHERE a.tid_level='$levelID' AND a.disabled=0 AND a.sub_question='A' AND a.is_tid=1
            AND ($ability $opt a.difficulty IN ($reqDifficulty) ) AND a.question_id NOT IN (".implode(",", $question_id).") ";

        if($reqTopic !== 'all' && $reqTopic !== ''){
            $sql .= "AND a.tid_topic_id='$reqTopic' ";
            $row = $this->db->get_where('sj_topics_tid', ['topic_id' => $reqTopic])->row_array();
            $result['reqTopic'] = ($row) ? $row['topic_name'] : 'Any Topic';
        } else {
            $result['reqTopic'] = 'Any Topic';
        }

        if($queType == 1){
            $sql .= "AND a.question_type_id IN (1,4) ";
        }else if($queType == 2){
            $sql .= "AND a.question_type_id IN (1,2) ";
        }
        
        $result['total_rows'] = $this->db->query($sql." LIMIT 1000")->num_rows();

        $sql .= "LIMIT 10 OFFSET $start";
        $result['data'] = $this->db->query($sql)->result();
        
        return $result;
    }

    // Worksheet Generator Design - START
    public function get_subjectlevel_list(){                        
        $this->db->select('b.id, a.id as subject_id, a.name as subject_name, b.level_id, b.level_name');
        $this->db->from('sj_subject a');
        $this->db->join('sj_levels b', 'b.subject_type=a.id');
        $this->db->where('b.level_id NOT IN (7,8)');  
        $this->db->where('a.id NOT IN (4,6)');  
        // if($this->session->userdata('is_logged_in') == 1){
        //     $subject_id = $this->m_user->getTutorStatusTag($this->session->userdata('user_id'));
        //     $subject_id = implode(',', $subject_id);
        //     $this->db->where('a.id IN ('.$subject_id.')'); 
        // }else{
            
        // }
        $this->db->order_by('a.name', 'ASC');
        $this->db->order_by('b.level_name', 'ASC');
        return $this->db->get()->result_array();
    }

    public function get_heuristics_list($subject_id = NULL){
        if(empty($subject_id) === false){
            $this->db->select('*')->from('sj_heuristics a')->where('a.subject_type='.$this->db->escape($subject_id))->order_by('a.heuristic_id', 'ASC');
        }else {
            $this->db->select('*')->from('sj_heuristics a')->order_by('a.heuristic_id', 'ASC');
        }           
        return $this->db->get()->result_array();
    }

    public function get_strategy_list($subject_id = NULL){   
        if(empty($subject_id) === false){
            $this->db->select('*')->from('sj_strategy a')->where('a.subject_type='.$this->db->escape($subject_id))->order_by('a.id', 'ASC');
        }else {
            $this->db->select('*')->from('sj_strategy a')->order_by('a.id', 'ASC');
        }           
        return $this->db->get()->result_array();
    }

    public function get_worksheet_substr($subject_id, $level_name){
        if($level_name == 'Primary 1' || $level_name == 'Secondary 1'){
            $this->db->select('a.id as substrand_id, a.name as substrand_name, a.primary_level_1 as level');
        }else if($level_name == 'Primary 2' || $level_name == 'Secondary 2'){
            $this->db->select('a.id as substrand_id, a.name as substrand_name, a.primary_level_2 as level');
        }else if($level_name == 'Primary 3' || $level_name == 'Secondary 3'){
            $this->db->select('a.id as substrand_id, a.name as substrand_name, a.primary_level_3 as level');
        }else if($level_name == 'Primary 4' || $level_name == 'Secondary 4'){
            $this->db->select('a.id as substrand_id, a.name as substrand_name, a.primary_level_4 as level');
        }else if($level_name == 'Primary 5' || $level_name == 'Secondary 5'){
            $this->db->select('a.id as substrand_id, a.name as substrand_name, a.primary_level_5 as level');
        }else if($level_name == 'Primary 6' || $level_name == 'Secondary 6'){
            $this->db->select('a.id as substrand_id, a.name as substrand_name, a.primary_level_6 as level');
        }else{
            $this->db->select('a.id as substrand_id, a.name as substrand_name');
        }
        
        $this->db->from('sj_substrands a');
        $this->db->where('a.subject_type='.$this->db->escape($subject_id));
        $this->db->order_by('a.id', 'ASC');
        return $this->db->get()->result_array();
    }

    public function get_worksheet_topic($substrand_id, $level_name){
        if($level_name == 'Primary 1' || $level_name == 'Secondary 1'){
            $this->db->select('a.id as topic_id, a.name as topic_name, a.primary_level_1 as level');
        }else if($level_name == 'Primary 2' || $level_name == 'Secondary 2'){
            $this->db->select('a.id as topic_id, a.name as topic_name, a.primary_level_2 as level');
        }else if($level_name == 'Primary 3' || $level_name == 'Secondary 3'){
            $this->db->select('a.id as topic_id, a.name as topic_name, a.primary_level_3 as level');
        }else if($level_name == 'Primary 4' || $level_name == 'Secondary 4'){
            $this->db->select('a.id as topic_id, a.name as topic_name, a.primary_level_4 as level');
        }else if($level_name == 'Primary 5' || $level_name == 'Secondary 5'){
            $this->db->select('a.id as topic_id, a.name as topic_name, a.primary_level_5 as level');
        }else if($level_name == 'Primary 6' || $level_name == 'Secondary 6'){
            $this->db->select('a.id as topic_id, a.name as topic_name, a.primary_level_6 as level');
        }else{
            $this->db->select('a.id as topic_id, a.name as topic_name');
        }
        
        $this->db->from('sj_categories a');
        $this->db->where('a.substrand_id='.$this->db->escape($substrand_id));
        $this->db->order_by('a.id', 'ASC');
        return $this->db->get()->result_array();
    }

    public function get_worksheet_heuristic($subject_id, $substrand_id, $topic_id, $level_name){    
        // USE ANY_VALUE(a.primary_level_1) on Server
        if(empty($subject_id) === false){
            $this->db->select('*')->from('sj_heuristics a')->where('a.subject_type='.$this->db->escape($subject_id))->order_by('a.heuristic_id', 'ASC');
                    
            return $this->db->get()->result_array();
        }else{
            if($level_name == 'Primary 1'){
                $this->db->select('DISTINCT(a.heuristic_id), a.primary_level_1 as level');
                // $this->db->select('a.heuristic_id, ANY_VALUE(a.primary_level_1) as level');
                $this->db->where('a.primary_level_1=1');
            }else if($level_name == 'Primary 2'){
                $this->db->select('DISTINCT(a.heuristic_id), a.primary_level_2 as level');
                // $this->db->select('a.heuristic_id, ANY_VALUE(a.primary_level_2) as level');
                $this->db->where('a.primary_level_2=1');
            }else if($level_name == 'Primary 3'){
                $this->db->select('DISTINCT(a.heuristic_id), a.primary_level_3 as level');
                // $this->db->select('a.heuristic_id, ANY_VALUE(a.primary_level_3) as level');
                $this->db->where('a.primary_level_3=1');
            }else if($level_name == 'Primary 4'){
                $this->db->select('DISTINCT(a.heuristic_id), a.primary_level_4 as level');
                // $this->db->select('a.heuristic_id, ANY_VALUE(a.primary_level_4) as level');
                $this->db->where('a.primary_level_4=1');
            }else if($level_name == 'Primary 5'){
                $this->db->select('DISTINCT(a.heuristic_id), a.primary_level_5 as level');
                // $this->db->select('a.heuristic_id, ANY_VALUE(a.primary_level_5) as level');
                $this->db->where('a.primary_level_5=1');
            }else if($level_name == 'Primary 6'){
                $this->db->select('DISTINCT(a.heuristic_id), a.primary_level_6 as level');
                // $this->db->select('a.heuristic_id, ANY_VALUE(a.primary_level_6) as level');
                $this->db->where('a.primary_level_6=1');
            }
            
            $this->db->from('sj_heuristics_relation a');
            if($substrand_id !== ''){
                $this->db->join('sj_categories b', 'a.topic_id=b.id');
                $this->db->where('b.substrand_id='.$this->db->escape($substrand_id));
                // $this->db->group_by('a.heuristic_id');
            }else{
                $this->db->where('a.topic_id='.$this->db->escape($topic_id));
            }
            $this->db->order_by('a.heuristic_id', 'ASC');
            return $this->db->get()->result_array();
        }
    }
    
    public function get_worksheet_strategy($subject_id, $substrand_id, $topic_id, $heuristic_id, $level_name){              
        if(empty($subject_id) === false){
            $this->db->select('*')->from('sj_strategy a')->where('a.subject_type='.$this->db->escape($subject_id))->order_by('a.id', 'ASC');
                    
            return $this->db->get()->result_array();
        }else{ 
            if($level_name == 'Primary 1'){
                $this->db->select('DISTINCT(a.strategy_id), a.primary_level_1 as level');
                // $this->db->select('a.strategy_id, ANY_VALUE(a.primary_level_1) as level'); 
                $this->db->where('a.primary_level_1=1');
            }else if($level_name == 'Primary 2'){
                $this->db->select('DISTINCT(a.strategy_id), a.primary_level_2 as level');
                // $this->db->select('a.strategy_id, ANY_VALUE(a.primary_level_2) as level');
                $this->db->where('a.primary_level_2=1');
            }else if($level_name == 'Primary 3'){
                $this->db->select('DISTINCT(a.strategy_id), a.primary_level_3 as level');
                // $this->db->select('a.strategy_id, ANY_VALUE(a.primary_level_3) as level');
                $this->db->where('a.primary_level_3=1');
            }else if($level_name == 'Primary 4'){
                $this->db->select('DISTINCT(a.strategy_id), a.primary_level_4 as level');
                // $this->db->select('a.strategy_id, ANY_VALUE(a.primary_level_4) as level');
                $this->db->where('a.primary_level_4=1');
            }else if($level_name == 'Primary 5'){
                $this->db->select('DISTINCT(a.strategy_id), a.primary_level_5 as level');
                // $this->db->select('a.strategy_id, ANY_VALUE(a.primary_level_5) as level');
                $this->db->where('a.primary_level_5=1');
            }else if($level_name == 'Primary 6'){
                $this->db->select('DISTINCT(a.strategy_id), a.primary_level_6 as level');
                // $this->db->select('a.strategy_id, ANY_VALUE(a.primary_level_6) as level');
                $this->db->where('a.primary_level_6=1');
            }      

            $this->db->from('sj_heuristics_relation a');

            if($heuristic_id !== '' && $heuristic_id !== 'all'){
                $this->db->where('a.heuristic_id='.$this->db->escape($heuristic_id));
            }else{
                if($topic_id !== '' && $topic_id !== 'all'){
                    $this->db->where('a.topic_id='.$this->db->escape($topic_id));
                }else{
                    if($substrand_id !== ''){
                        $this->db->join('sj_categories b', 'a.topic_id=b.id');
                        $this->db->where('b.substrand_id='.$this->db->escape($substrand_id));                        
                    }else{

                    }
                }
            }
            
            $this->db->order_by('a.strategy_id', 'ASC');
            return $this->db->get()->result_array();
        }
    }
    
    // Worksheet Generator Design - END



    public function get_level_list($subject = NULL){
        if(isset($subject) && empty($subject) === false){
            $level_sql = "SELECT * FROM `sj_levels` WHERE `subject_type`=".$this->db->escape($subject)." AND `level_id` NOT IN ('7','8')";
        } else {
            $level_sql = "SELECT * FROM `sj_levels` WHERE `id` NOT IN (7, 8, 14, 15, 16, 17)";
        }
        $level_sql_query = $this->db->query($level_sql);
        $level_array = array();

        foreach ($level_sql_query->result() as $row) {
            $level_array[] = $row;
        }
        return $level_array;
    }

    public function get_level_list_tid()
    {
        $level_sql = "SELECT * FROM `sj_levels_tid` WHERE 1 ";
        $level_sql_query = $this->db->query($level_sql);
        $level_array = array();
        foreach ($level_sql_query->result() as $row) {
            $level_array[] = $row;
        }
        return $level_array;
    }
    
    public function get_mock_exam_level_list() {
        $level_sql = "SELECT * FROM `sj_levels` WHERE `level_name` = 'Primary 5' OR `level_name` = 'Primary 6'";
        $level_sql_query = $this->db->query($level_sql);

        $level_array = array();
        foreach ($level_sql_query->result() as $row) {
            $level_array[] = $row;
        }

        return $level_array;
    }

    public function get_school_name($postData){
        $sql = "SELECT * FROM `sj_levels` WHERE `level_id` = " . $this->db->escape($postData['level']); 
        $sqlQuery = $this->db->query($sql);

        $row = $sqlQuery->row();
        // set school level base on subject type inside table levels
        if ($row->subject_type == '2') {
            $school_level = 'Primary';
        } else {
            $school_level = 'Secondary';
        }

       // Get City departments
        $response = array();
        $this->db->select('school_id,school_name');
        $this->db->where('school_level', $school_level);
        $this->db->order_by('school_name','ASC');
        $query = $this->db->get('sj_schools');
        $response = $query->result_array();

        return $response;
    }
    

    public function get_school_list(){
        $school_sql = "SELECT * FROM `sj_schools` ORDER BY `school_id`";
        $school_sql_query = $this->db->query($school_sql);

        $school_array = array();
        foreach ($school_sql_query->result() as $row) {
            $school_array[] = $row;
        }
        return $school_array;
    }
    

    public function get_topic_list($substrand_id = NULL){
        if (isset($substrand_id) && empty($substrand_id) === false) {
            $topic_sql = "SELECT * FROM `sj_categories` WHERE `substrand_id` =".$this->db->escape($substrand_id)." ORDER BY `id` ";
            $topic_sql_query = $this->db->query($topic_sql);
        } else {
            $topic_sql = "SELECT * FROM `sj_categories` ORDER BY `id`";
            $topic_sql_query = $this->db->query($topic_sql);
        }

        $topic_array = array();
        foreach ($topic_sql_query->result() as $row) {
            $topic_array[] = $row;
        }

        return $topic_array;
    }
    
    public function get_substrategy_list(){
        $substrategy_sql = "SELECT * FROM `sj_substrategy` ORDER BY `id` ";
        $substrategy_sql_query = $this->db->query($substrategy_sql);

        $substrategy_array = array();
        foreach ($substrategy_sql_query->result() as $row) {
            $substrategy_array[] = $row;
        }

        return $substrategy_array;
    }   


    public function get_substrand_list($stand_id = NULL, $subject = NULL){
        if (isset($subject) && empty($subject) === false) {
            $substrand_sql = "SELECT * FROM `sj_substrands` WHERE `subject_type` = ".$this->db->escape($subject)." ORDER BY `id`";
            $substrand_sql_query = $this->db->query($substrand_sql);
        } else {
            $substrand_sql = "SELECT * FROM `sj_substrands` WHERE `strand_id` = ".$this->db->escape($strand_id)." ORDER BY `id`";
            $substrand_sql_query = $this->db->query($substrand_sql);
        }
        
        $substrand_array = array();
        foreach ($substrand_sql_query->result() as $row) {
            $substrand_array[] = $row;
        }

        return $substrand_array;
    }   
    
    public function get_subject_list($data = NULL){
        if(isset($data) && empty($data) === false) {
            $sql = "SELECT * FROM `sj_subject`WHERE `id` IN (" . implode(",", $data) . ") ORDER BY `id`";
        } else {
            $sql = "SELECT * FROM `sj_subject` ORDER BY `id`";
        }
        
        $query = $this->db->query($sql);
        
        $subject_array = array();
        foreach($query->result() as $row){
            $subject_array[] = $row;
        }
        
        return $subject_array;
    }



    public function get_question_list($post, $userId, $exclude = array(), $start = 0 ){
        $this->session->unset_userdata('is_admin'); 
        $questions = array();
        $numGeneratedQuestion = 0;
        if($post['gen_que_type'] == 1){
            $tempData = $this->get_questions_id_from_requirements($post, $userId, $exclude, $start);
            
            if(isset($exclude) && empty($exclude) == true){
                $tempTable = $tempData;
            }
        } else {
            $tempData = $this->get_questions_id_from_requirement($post, $userId, $exclude, $start);
            if(isset($exclude) && empty($exclude) == true){
            $tempTable = $tempData;
            }
        }

        if(isset($exclude) && !empty($exclude)){
            return $tempData;
        }
        
        $questionArray = $this->get_random_question($tempTable, $post['gen_num_of_question']);

        $questionArrayID = array();
        foreach ($questionArray AS $question) {
            $questionArrayID[] = $question->question_id;
        }

        $sessionArray = array(
            'questionArray' => $questionArrayID
        );

        $this->session->set_userdata($sessionArray); //save the question ID in session for easier ajax regenerating

        return $questionArray;
    }

    public function get_tid_list(){ 
        $this->db->select('a.*, b.*, c.*');
        $this->db->from('sj_generate_questions a');
        $this->db->join('sj_questions b', 'a.question_id=b.question_id');
        $this->db->join('sj_branch c', 'b.branch_name=c.branch_name');
        $this->db->where('a.requirement_id', $this->session->userdata('requirementId'));
        $this->db->order_by('a.question_number', 'ASC');
        $questionArray = $this->db->get()->result();
        
        $questionArrayID = array();
        foreach ($questionArray AS $question) {
            $questionArrayID[] = $question->question_id;
        }

        $sessionArray = array(
            'questionArray' => $questionArrayID
        );

        $this->session->set_userdata($sessionArray);
        return $questionArray;
    }

    public function get_exam_list(){ 
        $this->db->select('a.*, b.*, c.*');
        $this->db->from('sj_generate_questions a');
        $this->db->join('sj_questions b', 'a.question_id=b.question_id');
        $this->db->join('sj_branch c', 'b.branch_name=c.branch_name');
        $this->db->where('a.requirement_id', $this->session->userdata('requirementId'));
        $this->db->order_by('a.question_number', 'ASC');
        $questionArray = $this->db->get()->result();

        $questionArrayID = array();
        foreach ($questionArray AS $question) {
            $questionArrayID[] = $question->question_id;
        }

        $sessionArray = array(
            'questionArray' => $questionArrayID
        );

        $this->session->set_userdata($sessionArray);
        return $questionArray;
    }

    public function get_exam_list_old($post, $userId, $exclude = array(), $start = 0){
        
        $questions = array();

        $numGeneratedQuestion = 0;
        
        $tempData = $this->get_questions_id_from_exam_requirement($post, $userId, $exclude, $start);
            
        if(isset($exclude) && empty($exclude) == true){
            $tempTable = $tempData;
        }

        if(isset($exclude) && !empty($exclude)){
            return $tempData;
        }
        
        $questionArray = $this->get_random_question($tempTable, $post['gen_num_of_question']);
        

        $questionArrayID = array();

        foreach ($questionArray AS $question) {

            $questionArrayID[] = $question->question_id;
        
        }

        $sessionArray = array(

            'questionArray' => $questionArrayID

        );

        $this->session->set_userdata($sessionArray); //save the question ID in session for easier ajax regenerating

        
        return $questionArray;

    }
    

    public function get_mock_exam_question_list($post) {
        $questions = array();
        $numGeneratedQuestion = 0;
        $questionArray = $this->get_mock_questions_id_from_requirement($post);
        return $questionArray;
    }


    public function get_answer_list($questionList){
        $this->load->helper('shuffleassoc');
        $answerList = array();
        foreach ($questionList as $question) {
            # code...
            $questionId = $question->question_id;
            $ans = array();
            $answerOptions = $this->get_answer_option_from_question_id($questionId);
            $correctAnswer = $this->get_answer_text_from_answer_id($this->get_correct_answer_from_question_id($questionId));
            $correctAnswerId = $this->get_correct_answer_from_question_id($questionId);
            //shuffling the ordering of answer options
            $answerOptions = shuffleAssoc($answerOptions);
            $ans['answerOption'] = $answerOptions;
            $ans['correctAnswer'] = $correctAnswer;

            $i = 1;
            foreach ($answerOptions as $answer) {
                if (strcmp($answer->answer_id, $correctAnswerId) == 0) {
                    $ans['correctAnswerOptionNum'] = $i;
                    break;
                }
                $i++;
            }

            $answerList[] = $ans;

        }

        return $answerList;
    }


    public function get_answer_list_by_id($question_id){
        $this->load->helper('shuffleassoc');
        $answerList = array();

        $answerOptions = $this->db->get_where('sj_answers', ['question_id' => $question_id])->result();
        $correctAnswer = $this->db->get_where('sj_correct_answer', ['question_id' => $question_id])->result();
        
        $ans['answerOption'] = $answerOptions;
        $ans['correctAnswer'] = $correctAnswer;
            
        $answerList[] = $ans;

        return $answerList;
    }


    public function get_question_content_list($questionList){

        $questionContentList = array();
        foreach ($questionList as $question) {

            $questionId = $question->question_id;
            $content = array();
            $questionContents = $this->get_question_contents_from_question_id($questionId);

            $content['question_content'] = $questionContents;
            
            $questionContentList[] = $content;

            
        }

        return $questionContentList;
    }


    public function get_question_header_list($questionList, $headerType){

        $questionHeaderList = array();
        foreach ($questionList as $question) {

            $questionId = $question->question_id;
            $content = array();
            $questionHeaders = $this->get_question_header_from_question_id($questionId, $headerType);

            if($headerType == 'instruction'){
                $content['question_instruction'] = $questionHeaders;
            }else{
                $content['question_article'] = $questionHeaders;
            }
            
            $questionHeaderList[] = $content;
            
        }

        return $questionHeaderList;
    }



    public function get_mock_answer_list($questionList) {

        $this->load->helper('shuffleassoc');

        $answerList = array();

        foreach ($questionList as $question) {

            $answerSubList = array();

            foreach ($question as $subquestion) {

                # code...

                $questionId = $subquestion->question_id;

                $questionType = $subquestion->question_type_id;



                $ans = array();

                if ($questionType == 1) {  // mcq

                    

                    $answerOptions = $this->get_answer_option_from_question_id($questionId);

                    $correctAnswer = $this->get_answer_text_from_answer_id($this->get_correct_answer_from_question_id($questionId));

                    //shuffling the ordering of answer options

                    $answerOptions = shuffleAssoc($answerOptions);



                    $ans['answerOption'] = $answerOptions;

                    $ans['correctAnswer'] = $correctAnswer;



                    $i = 1;

                    foreach ($answerOptions as $answer) {

                        if (strcmp($answer->answer_text, $correctAnswer) == 0) {

                            $ans['correctAnswerOptionNum'] = $i;

                            break;

                        }

                        $i++;

                    }

                } else {

                    $correctAnswer = $this->get_answer_text_from_answer_id($this->get_correct_answer_from_question_id($questionId));

                    $ans['correctAnswer'] = $correctAnswer;

                }



                $answerSubList[] = $ans;

                

            }



            $answerList[] = $answerSubList;

        }



        return $answerList;

    }



    private function get_total_question_number()

    {

        $totalNumOfQuestion_sql = "SELECT count(`question_id`) AS num FROM `sj_questions`";

        $totalNumOfQuestion = $this->db->query($totalNumOfQuestion_sql)->row()->num;

        return $totalNumOfQuestion;

    }



    public function get_questions_id_from_requirement($post, $userId, $exclude = array(), $start = 0){
        $exclude_condition = "";
        $pagination = "";
        if(sizeof($exclude) > 0){
            $exclude_array = array();
            foreach($exclude as $ex_item){
                $exclude_array[] = $this->db->escape($ex_item);
            }
            
            $exclude_condition = " AND question_id NOT IN(".implode(", ",$exclude_array).")";
            $pagination = " LIMIT 10 OFFSET ".$start;
        }

        
        if($userId == 0){
            $level = $post["gen_level"];

            $substrand = $post['gen_substrand'];

            $topic = $post["gen_topic"];

            // $percDifficulty = $post["gen_difficulties"];
            if($post['gen_difficulties'] >= 0 && $post['gen_difficulties'] < 30) {
                $percDifficulty = array(1,2);
            } else if($post['gen_difficulties'] >= 30 && $post['gen_difficulties'] < 60) {
                $percDifficulty = array(1,2,3,4,5);
            } else {
                $percDifficulty = array(4,5);
            }

            if(!isset($post['gen_strategy']) && empty($post['gen_strategy']) === true){
                $post['gen_strategy'] = "all";
            }
            $strategy = $post["gen_strategy"];
            
            $que_type = $post["gen_que_type"];
            

            $sql = "SELECT * FROM `sj_questions` LEFT JOIN `sj_branch` ON `sj_branch`.`branch_name` = `sj_questions`.`branch_name` WHERE `level_id` = ? ".$exclude_condition;
            $sql_bind_array = array();

            $sql_bind_array[] = $level;

            

            $whereClauseArray = array();

            for ($i = 0, $count = count($substrand); $i < $count; $i++) {
                $whereClause = "";
                
                if(strcmp($strategy, "all") !== 0){
                    $whereClause .= " `strategy_id` =". $this->db->escape($strategy);
                }else {
                    $strategy_list = $this->get_strategy_list();
                    $strategy_id = array();
                    foreach($strategy_list as $strategy_item) {
                        $strategy_id[] = $strategy_item['id']; 
                    }
                    $whereClause .= "`strategy_id` IN ( 0," . implode(",", $strategy_id) . " ) ";
                    
                }
                
                if($substrand[$i] == 'all') {

                    $arraySubstrand = $this->get_worksheet_substr($post['gen_subject'], $post['gen_level']);
                    $newSubstrandIndex = array_rand($arraySubstrand);
                    $newSubstrand = $arraySubstrand[$newSubstrandIndex]['substrand_id'];

                } else {

                    $newSubstrand = $substrand[$i];

                }
                
                
                if (strcmp($topic[$i], "all") !== 0) {
                    $whereClause .= " AND `topic_id` = ?";
                    $sql_bind_array[] = $topic[$i];
                } else {
                    $topic_list = $this->get_topic_list($newSubstrand);
                    $topic_id = array();
                    foreach ($topic_list as $topic_item) {
                        $topic_id[] = $topic_item->id;
                    }
                    $whereClause .= " AND `topic_id` IN (" . implode(",", $topic_id) . ")";
                }
                
                if(is_array($percDifficulty) == 1) {

                    $difficulty = array();
                    foreach($percDifficulty as $diff_key=>$diff_item) {
                        $difficulty[] = $diff_item;
                    }
                    $whereClause .= " AND `difficulty` IN (" . implode(",", $difficulty) . ")";
                }

                if($que_type == 2) {

                    $quesType = array(1, 2);

                    $whereClause .= " AND `question_type_id` IN (" . implode(",", $quesType) . ")";
                }

                $whereClause = "( " . $whereClause . " )";
                $whereClauseArray[] = $whereClause;
            }

            

            $whereClause = "(" . implode(" OR ", $whereClauseArray) . ")";

            

            $sql .= " AND `disabled` = 0 AND `sub_question` = 'A' AND " . $whereClause . " LIMIT 10 OFFSET ".$start;

            

            $sql_result = $this->db->query($sql, $sql_bind_array);

            $total_row = $this->get_total_row($sql,$sql_bind_array);
            

            $tempTableId = array();

            if(isset($exclude) && empty($exclude) == true){
                foreach ($sql_result->result() AS $row) {
                    $tempTableId[] = $row->question_id;
                }
                
                $sessionArray = array(
                    'tempTableId' => $tempTableId
                );
                
                $this->session->set_userdata($sessionArray);
                
            }else{
                $data = array();
                $data['result'] = $sql_result->result();
                $data['total_rows'] = $total_row;
                return $data;
            }

            

            return $sql_result;

        } else {
            if($post['gen_subject'] == '7') {
                $level = '1';
                $substrand = array(1);
                $topic = '1';
                $percDifficulty = 50;
            } else {
                $level = $post["gen_level"];
                $substrand = $post['gen_substrand'];
                $topic = $post["gen_topic"];
                // $percDifficulty = $post["gen_difficulties"];
                if($post['gen_difficulties'] >= 0 && $post['gen_difficulties'] < 30) {
                    $percDifficulty = array(1,2);
                } else if($post['gen_difficulties'] >= 30 && $post['gen_difficulties'] < 60) {
                    $percDifficulty = array(1,2,3,4,5);
                } else {
                    $percDifficulty = array(4,5);
                }
            }
            
            $que_type = $post["gen_que_type"];
            
            // $percDifficulty = $post["gen_difficulties"];
            if(!isset($post['gen_strategy']) && empty($post['gen_strategy']) === true){
                $post['gen_strategy'] = "all";
            }
            $strategy = $post["gen_strategy"];
            
            $sql = "SELECT * FROM `sj_questions` LEFT JOIN `sj_branch` ON `sj_branch`.`branch_name` = `sj_questions`.`branch_name` WHERE `level_id` = ?".$exclude_condition;
            $sql_bind_array = array();
            $sql_bind_array[] = $level;
            
            $whereClauseArray = array();
            for ($i = 0, $count = count($substrand); $i < $count; $i++) {
                $whereClause = "";
                
                if(strcmp($strategy, "all") !== 0){
                    $whereClause .= " `strategy_id` =". $this->db->escape($strategy);
                }else {
                    $strategy_list = $this->get_strategy_list();
                    $strategy_id = array();
                    foreach($strategy_list as $strategy_item) {
                        $strategy_id[] = $strategy_item['id']; 
                    }
                    $whereClause .= "`strategy_id` IN ( 0," . implode(",", $strategy_id) . " ) ";
                    
                }
                
                if($substrand[$i] == 'all') {

                    $arraySubstrand = $this->get_worksheet_substr($post['gen_subject'], $post['gen_level']);
                    $newSubstrandIndex = array_rand($arraySubstrand);
                    $newSubstrand = $arraySubstrand[$newSubstrandIndex]['substrand_id'];

                } else {

                    $newSubstrand = $substrand[$i];

                }
                
                
                if (strcmp($topic[$i], "all") !== 0) {
                    $whereClause .= " AND `topic_id` = ?";
                    $sql_bind_array[] = $topic[$i];
                } else {
                    $topic_list = $this->get_topic_list($newSubstrand);
                    $topic_id = array();
                    foreach ($topic_list as $topic_item) {
                        $topic_id[] = $topic_item->id;
                    }
                    $whereClause .= " AND `topic_id` IN (" . implode(",", $topic_id) . ")";
                }
                
                if(is_array($percDifficulty) == 1) {

                    $difficulty = array();
                    foreach($percDifficulty as $diff_key=>$diff_item) {
                        $difficulty[] = $diff_item;
                    }
                    $whereClause .= " AND `difficulty` IN (" . implode(",", $difficulty) . ")";
                }

                if($que_type == 2) {

                    $quesType = array(1, 2);

                    $whereClause .= " AND `question_type_id` IN (" . implode(",", $quesType) . ")";
                }

                $whereClause = "( " . $whereClause . " )";
                $whereClauseArray[] = $whereClause;
            }
            
            $whereClause = "(" . implode(" OR ", $whereClauseArray) . ")";
            
            $sql .= " AND `disabled` = 0 AND `sub_question` = 'A' AND " . $whereClause . $pagination;
            if($post['gen_subject'] == '7') {
                $sql = "SELECT * FROM `sj_questions` WHERE `question_id` BETWEEN 23441 AND 23460 ORDER BY RAND() LIMIT " . $post['gen_num_of_question'];
            }
            $sql_result = $this->db->query($sql, $sql_bind_array);
            $total_row = $this->get_total_row($sql,$sql_bind_array);
            
            $tempTableId = array();
            if(isset($exclude) && empty($exclude) == true){
                foreach ($sql_result->result() AS $row) {
                    $tempTableId[] = $row->question_id;
                }
                
                $sessionArray = array(
                    'tempTableId' => $tempTableId
                );
                
                $this->session->set_userdata($sessionArray);
            }else{
                $data = array();
                $data['result'] = $sql_result->result();
                $data['total_rows'] = $total_row;
                return $data;
            }
            
            return $sql_result;
        }
        
    }
    
    public function get_questions_id_from_requirements($post, $userId, $exclude = array(), $start = 0){

        $exclude_condition = "";
        $pagination = "";
        if(sizeof($exclude) > 0){
            $exclude_array = array();
            
            foreach($exclude as $ex_item){
                $exclude_array[] = $this->db->escape($ex_item);
            }
            
            $exclude_condition = " AND question_id NOT IN(".implode(", ",$exclude_array).")";
            
            $pagination = " LIMIT 10 OFFSET ".$start;
        }
        
        if ($userId == 0){
            $level = $post["gen_level"];
            $que_type = $post["gen_que_type"];
            // $substrand = $post['gen_substrand'];
            if(!isset($post['gen_substrand']) && empty($post['gen_substrand']) === true){                
                $substrand = "all";
            }else{
                $substrand = $post['gen_substrand'];
            }
            // $topic = $post["gen_topic"];
            if(!isset($post['gen_topic']) && empty($post['gen_topic']) === true){                
                $topic = "all";
            }else{
                $topic = $post['gen_topic'];
            }
            // $percDifficulty = $post["gen_difficulties"];
            if($post['gen_difficulties'] >= 0 && $post['gen_difficulties'] < 30) {
                $percDifficulty = array(1,2);
            } else if($post['gen_difficulties'] >= 30 && $post['gen_difficulties'] < 60) {
                $percDifficulty = array(1,2,3,4,5);
            } else {
                $percDifficulty = array(4,5);
            }
            if(!isset($post['gen_strategy']) && empty($post['gen_strategy']) === true){
                $post['gen_strategy'] = "all";
            }
            $strategy = $post["gen_strategy"];
            
            $sql = "SELECT * FROM `sj_questions` LEFT JOIN `sj_branch` ON `sj_branch`.`branch_name` = `sj_questions`.`branch_name` WHERE `level_id` = ? ".$exclude_condition;
            $sql_bind_array = array();
            $sql_bind_array[] = $level;
            
            $whereClauseArray = array();
            
            for ($i = 0, $count = count($substrand); $i < $count; $i++) {
                $whereClause = "";
                
                if(strcmp($strategy, "all") !== 0){
                    $whereClause .= " `strategy_id` =". $this->db->escape($strategy);
                }else {
                    $strategy_list = $this->get_strategy_list();
                    $strategy_id = array();
                    foreach($strategy_list as $strategy_item) {
                        $strategy_id[] = $strategy_item['id']; 
                    }
                    $whereClause .= "`strategy_id` IN (" . implode(",", $strategy_id) . " ) ";
                    
                }
                
                if($substrand[$i] == 'all') {

                    $arraySubstrand = $this->get_worksheet_substr($post['gen_subject'], $post['gen_level']);
                    $newSubstrandIndex = array_rand($arraySubstrand);
                    $newSubstrand = $arraySubstrand[$newSubstrandIndex]['substrand_id'];

                } else {

                    $newSubstrand = $substrand[$i];

                }
            
                if (strcmp($topic[$i], "all") !== 0) {
                    $whereClause .= " AND `topic_id` = ?";
                    $sql_bind_array[] = $topic[$i];
                } else {
                    if($substrand[$i] == 'all' || $substrand[$i] == ''){
                        $topic_list = $this->get_topic_list();
                    }else{
                        $topic_list = $this->get_topic_list($newSubstrand);
                    }                   
                    $topic_id = array();
                    foreach ($topic_list as $topic_item) {
                        $topic_id[] = $topic_item->id;
                    }
                    $whereClause .= " AND `topic_id` IN (" . implode(",", $topic_id) . ")";
                }
                
                if(is_array($percDifficulty) == 1) {

                    $difficulty = array();
                    foreach($percDifficulty as $diff_key=>$diff_item) {
                        $difficulty[] = $diff_item;
                    }
                    $whereClause .= " AND `difficulty` IN (" . implode(",", $difficulty) . ")";
                }

                if($que_type == 1) {
                    $quesType = array(1, 4);

                    $whereClause .= " AND `question_type_id` IN (" . implode(",", $quesType) . ")";
                }
                
                $whereClause = "( " . $whereClause . " )";
                $whereClauseArray[] = $whereClause;
            }
            $whereClause = "(" . implode(" OR ", $whereClauseArray) . ")";
            $sql .= " AND `disabled` = 0 AND `sub_question` = 'A' AND " . $whereClause . " LIMIT 10 OFFSET ".$start;
            $sql_result = $this->db->query($sql, $sql_bind_array);
            $total_row = $this->get_total_row($sql,$sql_bind_array);
            $tempTableId = array();
            
            if(isset($exclude) && empty($exclude) == true){
                foreach ($sql_result->result() AS $row) {
                    $tempTableId[] = $row->question_id;
                }
                $sessionArray = array(
                    'tempTableId' => $tempTableId
                );
                $this->session->set_userdata($sessionArray);
            }else{
                $data = array();
                $data['result'] = $sql_result->result();
                $data['total_rows'] = $total_row;

                return $data;
            }
            return $sql_result;
        }else {
            if($post['gen_subject'] == '7') {
                $level = '1';
                $substrand = array(1);
                $topic = '1';
                $percDifficulty = 50;
            } else {
                $level = $post["gen_level"];
                $substrand = $post['gen_substrand'];
                $topic = $post["gen_topic"];
                // $percDifficulty = $post["gen_difficulties"];
                if($post['gen_difficulties'] >= 0 && $post['gen_difficulties'] < 30) {
                    $percDifficulty = array(1,2);
                } else if($post['gen_difficulties'] >= 30 && $post['gen_difficulties'] < 60) {
                    $percDifficulty = array(1,2,3,4,5);
                } else {
                    $percDifficulty = array(4,5);
                }
            }
            
            $que_type = $post["gen_que_type"];
            
            if(!isset($post['gen_strategy']) && empty($post['gen_strategy']) === true){
                $post['gen_strategy'] = "all";
            }
            $strategy = $post["gen_strategy"];
            $sql = "SELECT * FROM `sj_questions` LEFT JOIN `sj_branch` ON `sj_branch`.`branch_name` = `sj_questions`.`branch_name` WHERE `level_id` = ? ".$exclude_condition;
            $sql_bind_array = array();
            $sql_bind_array[] = $level;
            
            $whereClauseArray = array();
            
            for ($i = 0, $count = count($substrand); $i < $count; $i++) {
                $whereClause = "";
                
                if(strcmp($strategy, "all") !== 0){
                    $whereClause .= " `strategy_id` =". $this->db->escape($strategy);
                }else {
                    $strategy_list = $this->get_strategy_list();
                    $strategy_id = array();
                    foreach($strategy_list as $strategy_item) {
                        $strategy_id[] = $strategy_item['id']; 
                    }
                    $whereClause .= "`strategy_id` IN ( 0," . implode(",", $strategy_id) . " ) ";
                    
                }
                
                if($substrand[$i] == 'all') {

                    $arraySubstrand = $this->get_worksheet_substr($post['gen_subject'], $post['gen_level']);
                    $newSubstrandIndex = array_rand($arraySubstrand);
                    $newSubstrand = $arraySubstrand[$newSubstrandIndex]['substrand_id'];

                } else {

                    $newSubstrand = $substrand[$i];

                }
                
                
                if (strcmp($topic[$i], "all") !== 0) {
                    $whereClause .= " AND `topic_id` = ?";
                    $sql_bind_array[] = $topic[$i];
                } else {
                    $topic_list = $this->get_topic_list($newSubstrand);
                    $topic_id = array();
                    foreach ($topic_list as $topic_item) {
                        $topic_id[] = $topic_item->id;
                    }
                    $whereClause .= " AND `topic_id` IN (" . implode(",", $topic_id) . ")";
                }
                
                if(is_array($percDifficulty) == 1) {

                    $difficulty = array();
                    foreach($percDifficulty as $diff_key=>$diff_item) {
                        $difficulty[] = $diff_item;
                    }
                    $whereClause .= " AND `difficulty` IN (" . implode(",", $difficulty) . ")";
                }

                if($que_type == 1) {
                    $quesType = array(1, 4);

                    $whereClause .= " AND `question_type_id` IN (" . implode(",", $quesType) . ")";
                }

                $whereClause = "( " . $whereClause . " )";
                $whereClauseArray[] = $whereClause;
            }
            $whereClause = "(" . implode(" OR ", $whereClauseArray) . ")";
            
            $sql .= " AND `disabled` = 0 AND `sub_question` = 'A' AND " . $whereClause. $pagination;
            if($post['gen_subject'] == '7') {
                $sql = "SELECT * FROM `sj_questions` WHERE `question_id` BETWEEN 23441 AND 23460 ORDER BY RAND() LIMIT " . $post['gen_num_of_question'];
            }
            
            $sql_result = $this->db->query($sql, $sql_bind_array);
            $total_row = $this->get_total_row($sql,$sql_bind_array);
            $tempTableId = array();
            if(isset($exclude) && empty($exclude) == true){
                foreach ($sql_result->result() AS $row) {
                    $tempTableId[] = $row->question_id;
                }
                $sessionArray = array(
                    'tempTableId' => $tempTableId
                );
                
                $this->session->set_userdata($sessionArray);
            }else{
                $data = array();
                $data['result'] = $sql_result->result();
                $data['total_rows'] = $total_row;

                return $data;
            }
            return $sql_result;
        }
    }
    
    public function get_questions_id_from_exam_requirement($post, $userId, $exclude = array(), $start = 0){


    }

    public function get_questions_id_from_exam_requirement2($post, $userId, $exclude = array(), $start = 0){

        $exclude_condition = "";
        $pagination = "";
        if(sizeof($exclude) > 0){
            $exclude_array = array();
            
            foreach($exclude as $ex_item){
                $exclude_array[] = $this->db->escape($ex_item);
            }
            
            $exclude_condition = " AND question_id NOT IN(".implode(", ",$exclude_array).")";
            
            $pagination = " LIMIT 10 OFFSET ".$start;
        }
        
        if ($userId == 0){
            $level = $post["gen_level"];
            $substrand = $post['gen_substrand'];
            $topic = $post["gen_topic"];
            $percDifficulty = $post["gen_difficulties"];
            $startQuestion = $post["gen_start_of_question"];
            $endQuestion = $post["gen_end_of_question"];
            $noOfQuestions = $post['gen_num_of_question'];
            $sqlArray = array();
            $sql = "";
            
            for ($i = 0, $count = count($substrand); $i < $count; $i++) {


                if($i == 0) {
                    $sql = "(SELECT * FROM `sj_questions` LEFT JOIN `sj_branch` ON `sj_branch`.`branch_name` = `sj_questions`.`branch_name` WHERE `level_id` = " . $level. " ".$exclude_condition;
                } else {
                    $sql = "SELECT * FROM `sj_questions` LEFT JOIN `sj_branch` ON `sj_branch`.`branch_name` = `sj_questions`.`branch_name` WHERE `level_id` = " . $level. " ".$exclude_condition; 
                }
                // $sql = "SELECT * FROM `sj_questions` LEFT JOIN `sj_branch` ON `sj_branch`.`branch_name` = `sj_questions`.`branch_name` WHERE `level_id` = " . $level. " ".$exclude_condition;
                
                
                if (strcmp($topic[$i], "all") !== 0) {
                    $sql_topic = " `topic_id` =" . $topic[$i];
                } else {
                    $topic_list = $this->get_topic_list($substrand[$i]);
                    $topic_id = array();
                    foreach ($topic_list as $topic_item) {
                        $topic_id[] = $topic_item->id;
                    }
                    $sql_topic = " `topic_id` IN (" . implode(",", $topic_id) . ")";
                }
                
                if(is_array($percDifficulty) == 1) {

                    $difficulty = array();
                    foreach($percDifficulty as $diff_key=>$diff_item) {
                        $difficulty[] = $diff_item;
                    }
                    $sql_difficulty = " AND `difficulty` IN (" . implode(",", $difficulty) . ")";
                }

                $limit = " LIMIT " . ($endQuestion[$i]-$startQuestion[$i]+1) . ")";
                
                
                $sql .= " AND `disabled` = 0 AND `sub_question` = 'A' AND " . $sql_topic. $sql_difficulty. $pagination. $limit;

                $sqlArary[] = $sql;
            }

            $sql = implode(" UNION ALL (", $sqlArary);
            $sql_result = $this->db->query($sql);
            foreach ($sql_result->result() AS $row) {
                $tempTableId[] = $row->question_id;
            }
            $sessionArray = array(
                'tempTableId' => $tempTableId
            );
            
            $this->session->set_userdata($sessionArray);
            // $total_row = $this->get_total_row($sql);
            // $tempTableId = array();
            // if(isset($exclude) && empty($exclude) == true){
            //  foreach ($sql_result->result() AS $row) {
            //      $tempTableId[] = $row->question_id;
            //  }
            //  $sessionArray = array(
            //      'tempTableId' => $tempTableId
            //  );
                
            //  $this->session->set_userdata($sessionArray);
            // }else{
            //  $data = array();
            //  $data['result'] = $sql_result->result();
            //  $data['total_rows'] = $total_row;

            //  return $data;
            // }
            return $sql_result;
        }else {
            $level = $post["gen_level"];
            $substrand = $post['gen_substrand'];
            $topic = $post["gen_topic"];
            $percDifficulty = $post["gen_difficulties"];
            $startQuestion = $post["gen_start_of_question"];
            $endQuestion = $post["gen_end_of_question"];
            $noOfQuestions = $post['gen_num_of_question'];
            $sqlArray = array();
            $sql = "";
            for ($i = 0, $count = count($substrand); $i < $count; $i++) {


                if($i == 0) {
                    $sql = "(SELECT * FROM `sj_questions` LEFT JOIN `sj_branch` ON `sj_branch`.`branch_name` = `sj_questions`.`branch_name` WHERE `level_id` = " . $level. " ".$exclude_condition;
                } else {
                    $sql = "SELECT * FROM `sj_questions` LEFT JOIN `sj_branch` ON `sj_branch`.`branch_name` = `sj_questions`.`branch_name` WHERE `level_id` = " . $level. " ".$exclude_condition; 
                }
                // $sql = "SELECT * FROM `sj_questions` LEFT JOIN `sj_branch` ON `sj_branch`.`branch_name` = `sj_questions`.`branch_name` WHERE `level_id` = " . $level. " ".$exclude_condition;
                
                
                if (strcmp($topic[$i], "all") !== 0) {
                    $sql_topic = " `topic_id` =" . $topic[$i];
                } else {
                    $topic_list = $this->get_topic_list($substrand[$i]);
                    $topic_id = array();
                    foreach ($topic_list as $topic_item) {
                        $topic_id[] = $topic_item->id;
                    }
                    $sql_topic = " `topic_id` IN (" . implode(",", $topic_id) . ")";
                }
                
                if(is_array($percDifficulty) == 1) {

                    $difficulty = array();
                    foreach($percDifficulty as $diff_key=>$diff_item) {
                        $difficulty[] = $diff_item;
                    }
                    $sql_difficulty = " AND `difficulty` IN (" . implode(",", $difficulty) . ")";
                }

                $limit = " LIMIT " . ($endQuestion[$i]-$startQuestion[$i]+1) . ")";
                
                
                $sql .= " AND `disabled` = 0 AND `sub_question` = 'A' AND " . $sql_topic. $sql_difficulty. $pagination. $limit;

                $sqlArary[] = $sql;
            }
            $sql = implode(" UNION ALL (", $sqlArary);
            $sql_result = $this->db->query($sql);
            foreach ($sql_result->result() AS $row) {
                $tempTableId[] = $row->question_id;
            }
            $sessionArray = array(
                'tempTableId' => $tempTableId
            );
            
            $this->session->set_userdata($sessionArray);
            // $total_row = $this->get_total_row($sql);
            // $tempTableId = array();
            // if(isset($exclude) && empty($exclude) == true){
            //  foreach ($sql_result->result() AS $row) {
            //      $tempTableId[] = $row->question_id;
            //  }
            //  $sessionArray = array(
            //      'tempTableId' => $tempTableId
            //  );
                
            //  $this->session->set_userdata($sessionArray);
            // }else{
            //  $data = array();
            //  $data['result'] = $sql_result->result();
            //  $data['total_rows'] = $total_row;

            //  return $data;
            // }
            return $sql_result;
        }
    }


    public function get_mock_questions_id_from_requirement($post)

    {

        $tutorid = $post["gen_tutor"];

        $me = $post['gen_me'];

        $year = $post["gen_year"];

        $level = $post["gen_level"];

        $randomize = isset($post["gen_randomize"])?$post["gen_randomize"]:'';

        $sql = "SELECT * FROM `sj_questions`
                LEFT JOIN `sj_question_tag`
                ON `sj_question_tag`.`question_id` = `sj_questions`.`question_id`
                LEFT JOIN `sj_branch`
                ON `sj_branch`.`branch_name` = `sj_questions`.`branch_name`
                WHERE `sj_questions`.`source` = ? AND `sj_questions`.`level_id` = ? AND `sj_questions`.`year` = ? AND `sj_question_tag`.`tags` LIKE ?
        ";

        $questions = array();

        $sql_result = $this->db->query($sql, array($tutorid, $level, $year, $me));



        // rearrange the sub question with its parent here

        foreach ($sql_result->result() as $row) {

            if (strcmp($row->sub_question, 'A') != 0) {

                $questions[count($questions) - 1][] = $row;

            } else {  // it is a parent question

                $questions[] = array($row);

            }

        }

        

        if ($randomize == 1) {

            shuffle($questions);

        }

        

        return $questions;

    }



    /*

    To generate a random question from database

    1. Get total number of question from temp table

    2. In a loop, use php to generate a random number that max with step 1.

    3. select the question based on id generated in step 2

    */



    private function get_random_question($tempTable, $numOfQuestion)

    {

        $randomNumArray = array();

        $totalNumOfQuestion = $tempTable->num_rows();



        //don't have so many questions at the moment

        if ($totalNumOfQuestion < $numOfQuestion) {

            $numOfQuestion = $totalNumOfQuestion;

        }



        //generate an array of different position for random question

        for ($i = 0; $i < $numOfQuestion; $i++) {

            $randomNum = mt_rand(0, $totalNumOfQuestion - 1);

            while (in_array($randomNum, $randomNumArray) === true) {

                $randomNum = mt_rand(0, $totalNumOfQuestion - 1);

            }

            $randomNumArray[] = $randomNum;

        }



        $questionArray = array();

        foreach ($randomNumArray AS $randomNum) {

            $questionArray[] = $tempTable->row($randomNum);

        }



        return $questionArray;

    }



    public function get_question_from_id($id)

    {

        if(is_array($id)){
            $id = implode(",", $id);
        }
        $sql = "SELECT * FROM `sj_questions` LEFT JOIN `sj_branch` ON `sj_branch`.`branch_name` = `sj_questions`.`branch_name` WHERE `question_id` IN (" . $this->db->escape($id) . ")";

        $sqlQuery = $this->db->query($sql);

        return $sqlQuery->row();

    }


    public function get_question_content_from_id($id){

        $sql = "SELECT * FROM `sj_question_content` WHERE `content_section` = 'question' AND `question_id`='$id' ORDER BY `content_order` ";

        $sqlQuery = $this->db->query($sql)->result();

        return $sqlQuery;

    }

    public function get_answer_content_from_answer_group($question_id, $answer_group){

        $sql = "SELECT * FROM `sj_answers` WHERE `answer_group` = ".$this->db->escape($answer_group)." AND `question_id`= ".$this->db->escape($question_id);

        $sqlQuery = $this->db->query($sql)->result();

        return $sqlQuery;

    }

    public function get_question_instruction_from_id($question_id){

        $sql = "SELECT * FROM `sj_question_header` WHERE `header_type` = 'instruction' AND `question_id`='$question_id' ORDER BY `header_order` ";

        $sqlQuery = $this->db->query($sql)->result();

        return $sqlQuery;

    }

    public function get_question_article_from_id($question_id){

        $sql = "SELECT * FROM `sj_question_header` WHERE `header_type` = 'article' AND `question_id`='$question_id' ORDER BY `header_order` ";

        $sqlQuery = $this->db->query($sql)->result();

        return $sqlQuery;

    }

    public function get_question_parent_id($id)
    {   
        $status = false;
        $id = $id - 1;

        while($status == false) {
            $sql = "SELECT * FROM `sj_questions` LEFT JOIN `sj_branch` ON `sj_branch`.`branch_name` = `sj_questions`.`branch_name` WHERE `question_id` IN (" . $this->db->escape($id) . ")";

            $sqlQuery = $this->db->query($sql);
            $row = $sqlQuery->row();

            if(strlen($row->sub_question) == 1) {
                $status = true;
            }
            else {
                $id = $id - 1;
            }
        }     

        return $sqlQuery->row();
    }

    public function get_question_status($id)
    {   
        $status = false;
        $id = $id + 1;

        $sql = "SELECT * FROM `sj_questions` LEFT JOIN `sj_branch` ON `sj_branch`.`branch_name` = `sj_questions`.`branch_name` WHERE `question_id` IN (" . $this->db->escape($id) . ")";

        $sqlQuery = $this->db->query($sql);

        if($sqlQuery->num_rows() > 0) {
            
            $row = $sqlQuery->row();

            if($row->sub_question == 'A') {
                $status = true;
            } else {
                $status = false;
            }
        } else {
            
            $status = true;
        }
        
        return $status;
    }



    public function get_new_unique_question($currentQuestionNumber)

    {

        $questionArrayId = $this->session->userdata('questionArray');

        $tempTableId = $this->session->userdata('tempTableId');



        $totalNumOfQuestion = count($tempTableId);

        $randomNum = mt_rand(0, $totalNumOfQuestion - 1);

        while (in_array($tempTableId[$randomNum], $questionArrayId) === true) {

            $randomNum = mt_rand(0, $totalNumOfQuestion - 1);

        }



        $questionArrayId[$currentQuestionNumber - 1] = $tempTableId[$randomNum];

        $this->session->set_userdata('questionArray', $questionArrayId);

        return $this->get_question_from_id($tempTableId[$randomNum]);

    }
    
    public function get_answer_option_from_question_id($questionId)
    {
        $this->db->where('question_id', $questionId);

        $this->db->order_by('rand()');

        $query = $this->db->get('sj_answers');

        $answerOptions = array();

        foreach ($query->result() AS $row) {

            $answerOptions[] = $row;

        }

        return $answerOptions;

        // $this->load->model('model_automarking');
        // $this->db->where('question_id', $questionId);

        // $query = $this->db->get('sj_answers');

       
        // foreach ($query->result() AS $row) {
            

        //     $hasSIUNIT = false;
        //     $hasNONSIUNIT = false;
        //     $hasNumber = false;
        //     $hasChracter = false;
        //     $nonSIUNITTxt = "";

        //     $siUnit = new Model_automarking();
        //     $UNIT =  $siUnit->UNIT_TYPES;
        //     $NOT_CONSIDER_UNIT = array (
        //         'st','nd','rd',"th"
        //     );


        //     //added by KL
        //     $answerText = $row->answer_text;
        //     $answerText = str_replace(" :",":", $answerText);
        //     $answerText = str_replace(": ",":", $answerText);
        //     $answerText = str_replace(":"," : ", $answerText);
        //     $answerText = preg_replace('/(?<=[a-z|%|$])(?=\d)|(?<=\d)(?=[a-z|%])/i',' ', $answerText);


        //     //to split from SI UNIT
        //     for($i = 1 ; $i <= strlen($answerText); $i++){
        //         if(preg_match('/[a-z|A-Z|0-9]/',substr($answerText,-$i,1))){
        //             //is a number or alphabet
        //             //check if it is SI UNIT;
        //             foreach($UNIT as $unitindex => $unitarray) {
        //                 foreach($unitarray as $unit2index => $singleUnit){
        //                     $strLen = strlen($answerText) - $i;
        //                     $unitLen = strlen($singleUnit);

        //                     if($strLen > $unitLen && 
        //                         $singleUnit == substr($answerText, -$unitLen-$i+1,$unitLen) &&
        //                         !preg_match('/[a-z|A-Z]/',substr($answerText,-$unitLen-$i, 1))
        //                         ){
        //                         //unit match
        //                         //split string & unit
        //                         $hasSIUNIT = true;
        //                     }
        //                 }
        //             }

        //             break;
        //         }
        //     }
        //     //check has number
        //     if(!$hasSIUNIT){
        //         $shouldStop = false;
        //         for($i = 1 ; $i <= strlen($answerText); $i++){
        //             if(preg_match('/[0-9]/',substr($answerText,-$i,1))){
        //                 $hasNumber = true;
        //                 break;
        //             }else if(preg_match('/[a-z|A-Z]/',substr($answerText,-$i,1)) && !$shouldStop){
        //                 $nonSIUNITTxt = substr($answerText,-$i,1).$nonSIUNITTxt;
        //             }else if(preg_match('/\S/',substr($answerText,-$i,1))){
        //                 $shouldStop = true;
        //             }
        //         }
        //         if(strlen($nonSIUNITTxt) > 0 && $hasNumber){
        //             $hasNONSIUNIT = true;
        //             //filter nonSIUNIT
        //             if(!in_array($nonSIUNITTxt,$NOT_CONSIDER_UNIT)){
        //                 $answerText = preg_replace('/'.$nonSIUNITTxt.'/',"",$answerText);
        //                 $answerText = trim($answerText);
        //             }
        //         }
        //     }
            

        //     $row->answer_text =  $answerText;
        //     $answerOptions[] = $row;
        // }


        // return $answerOptions;

    }

    public function get_answer_option_from_question_id_no_randomize($questionId)
    {
        $this->db->where('question_id', $questionId);

        $query = $this->db->get('sj_answers');

        $answerOptions = array();

        foreach ($query->result() AS $row) {

            $answerOptions[] = $row;

        }
        
        return $answerOptions;

    }

    public function get_max_answer_group_from_question_id($question_id)
    {
        $Q = "SELECT MAX(answer_group) AS `max_group_answer` FROM `sj_answers` WHERE question_id = ". $this->db->escape($question_id);
        $query = $this->db->query($Q);
        $result = $query->row();
        
        return $result->max_group_answer;
    }

    public function get_answer_type_option_from_question_id($questionId)
    {
        $this->db->where('question_id', $questionId);

        $this->db->order_by('rand()');

        $query = $this->db->get('sj_answers');

        foreach ($query->result() AS $row) {
            if($row->answer_type == 'image'){
                $answer_isImage = true;
            } else {
                $answer_isImage = false;
                break;
            }
        }

        return $answer_isImage;
    }



    public function get_correct_answer_from_question_id($questionId)

    {

        $this->db->where('question_id', $questionId);

        $query = $this->db->get('sj_correct_answer');



        $result = $query->row();

        return isset($result) ?$result->answer_id:NULL;

    }

    
    public function get_correct_answer_text_from_correct_id($questionId)

    {
        $this->db->where('answer_id', $questionId);
        $query = $this->db->get('sj_answers');
        $result = $query->row();
        return $result->answer_text;
    }


    public function get_total_question_id($questionId){        
        $result = $this->db->query('SELECT COUNT(*) AS total FROM sj_correct_answer WHERE question_id='.$questionId)->row();
        return $result->total;
    }


    public function get_total_marks_from_question_id($questionId)

    {

        $this->db->select('difficulty');

        $this->db->from('sj_questions');

        $this->db->where('question_id', $questionId);

        $query = $this->db->get();



        $result = $query->row();

        return $result->difficulty;

    }

    public function get_total_marks_from_worksheet_id($worksheetId)

    {
        $Q = "SELECT sum(q.difficulty) as total FROM `sj_worksheet_questions` a, sj_questions q WHERE a.`question_id`=q.question_id and worksheet_id='".$worksheetId."' ";
        $query = $this->db->query($Q);
        $result = $query->row();
        return $result->total;
    }

    public function get_question_type_id_from_question_id($questionId)

    {

        // [WM]: value of question_type might be:

        // "1" >> MCQ with 4 answer options

        // "2" >> Open ended with text based answer

        // "3" >> True/False answer



        $this->db->select('question_type_id');

        $this->db->from('sj_questions');

        $this->db->where('question_id', $questionId);

        $query = $this->db->get();



        $result = $query->row();

        return $result->question_type_id;

    }

    function get_all_question_type($question_type_id = NULL) {

        if(isset($question_type_id) && !empty($question_type_id) == TRUE) {

            $sql = "SELECT * FROM `sj_question_type` WHERE `question_type_id` = " . $this->db->escape($question_type_id);

        } else {

            $sql = "SELECT * FROM `sj_question_type`";

        }

        $query = $this->db->query($sql);

        if($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_all_answer_type($answer_type_id = NULL) {

        if(isset($answer_type_id) && !empty($answer_type_id) == TRUE) {

            $sql = "SELECT * FROM `sj_answer_type` WHERE `answer_type_id` = " . $this->db->escape($answer_type_id) . " AND `answer_type_id` NOT IN (1)";

        } else {

            $sql = "SELECT * FROM `sj_answer_type` wHERE `answer_type_id` NOT IN (1) ORDER BY `answer_type_id`";

        }

        $query = $this->db->query($sql);

        if($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    public function get_question_type_from_requirement_id($requirementId)

    {
        $this->db->select('question_type');
        $this->db->from('sj_worksheet_requirement');
        $this->db->where('requirement_id', $requirementId);
        $query = $this->db->get();
        
        $result = $query->row();
        return $result->question_type;
    }

    public function get_question_type_from_worksheet_questions($questionId, $worksheetId)
    {
        $this->db->select('question_type');
        $this->db->from('sj_worksheet_questions');
        $this->db->where('question_id', $questionId);
        $this->db->where('worksheet_id', $worksheetId);
        $query = $this->db->get();
        
        $result = $query->row();
        return $result->question_type;
    }

    public function get_answer_text_from_answer_id($answerId)
    {
        $this->db->select('answer_text');

        $this->db->from('sj_answers');

        $this->db->where('answer_id', $answerId);

        $query = $this->db->get();

        if($query->num_rows() > 0){
            $result = $query->row();

            return $result->answer_text;
        } else {
            return false;
        }
    }

    public function get_nmcq_answer_type_from_answer_id($answerId)
    {
        $this->db->select('answer_type');

        $this->db->from('sj_answers');

        $this->db->where('answer_id', $answerId);

        $query = $this->db->get();

        if($query->num_rows() > 0){
            $result = $query->row();

            return $result->answer_type;
        } else {
            return false;
        }
    }

    public function get_working_text_from_answer_id($answerId)
    {

        $sql = "SELECT * FROM `sj_answers` WHERE `answer_id` = ". $this->db->escape($answerId);

        $sqlQuery = $this->db->query($sql);

        return $sqlQuery->row();

    }

    function get_first_answer_id ($questionId) {

        $sql = "SELECT `answer_id` FROM `sj_answers` WHERE `question_id` = " . $this->db->escape($questionId) . " ORDER BY `answer_id` ASC LIMIT 1";

        $query = $this->db->query($sql);

        if($query->num_rows() > 0) {

            return $query->row()->answer_id;

        }
    }

    public function get_working_content_from_question_id($question_id)
    {
        $sql = "SELECT * FROM `sj_question_content` WHERE `question_id` = ". $this->db->escape($question_id) . " AND `content_section` = 'working' ORDER BY `content_order`";

        $query = $this->db->query($sql);

        $working_content = $query->result();

        return $working_content;

    }

    public function get_count_working_index($question_id)
    {
        $sql = "SELECT COUNT(content_id) AS `counter` FROM `sj_question_content` WHERE `question_id` = ". $this->db->escape($question_id) . " AND `content_section` = 'working' ORDER BY `content_order`";

        $query = $this->db->query($sql);

        return $query->row()->counter + 1;

    }

    public function get_answer_type_id_from_question_id($questionId)

    {

        $this->db->select('answer_type_id');

        $this->db->from('sj_questions');

        $this->db->where('question_id', $questionId);

        $query = $this->db->get();



        $result = $query->row();

        return $result->answer_type_id;

    }



    public function get_category_from_question_id($questionId, $check = NULL, $requirementId = NULL)
    {   
        if(isset($check) && $check == TRUE) {
            if(empty($requirementId) == TRUE) {
                $select = "SELECT * FROM `sj_generate_questions` WHERE `question_id` = " . $this->db->escape($questionId) . " AND `requirement_id` = " . $this->session->userdata('requirementId');
            } else {
                if($check == TRUE) {
                    $select = "SELECT * FROM `sj_generate_questions` WHERE `question_id` = " . $this->db->escape($questionId) . " AND `requirement_id` = " . $requirementId;
                } else {
                    $select = "SELECT * FROM `sj_generate_questions` WHERE `question_id` = " . $this->db->escape($questionId) . " AND `requirement_id` = " . $this->session->userdata('requirementId');
                }
            }

            $query = $this->db->query($select);
            
            if($query->row()->topic_req != 'all') {
                $categoryId = $query->row()->topic_req;
            } else {
                if(is_array($questionId)){
                    $questionId = implode(",", $this->db->escape($questionId));
                }
                $sql = "SELECT `topic_id` FROM `sj_questions` WHERE `question_id` IN (".$questionId.")";
        
                $query = $this->db->query($sql);
        
        
                $categoryId = $query->row()->topic_id;
            }
        } else {
            if(is_array($questionId)){
                $questionId = implode(",", $this->db->escape($questionId));
            }
            $sql = "SELECT `topic_id` FROM `sj_questions` WHERE `question_id` IN (".$questionId.")";
    
            $query = $this->db->query($sql);
    
    
            $categoryId = $query->row()->topic_id;
        }

        if (isset($categoryId) && empty($categoryId) === false) {

            $this->db->select('name');

            $this->db->from('sj_categories');

            $this->db->where('id', $categoryId);

            $query = $this->db->get();



            return $query->row()->name;

        } else {

            return "";

        }

    }

    
    public function get_strategy_from_question_id($questionId)
    {
        if(is_array($questionId)){
            $questionId = implode(",", $this->db->escape($questionId));
        }
        $sql = "SELECT `strategy_id` FROM `sj_questions` WHERE `question_id` IN (".$questionId.")";
        $query = $this->db->query($sql);

        $strategyId = $query->row()->strategy_id;

        if (isset($strategyId) && empty($strategyId) === false) {
            $this->db->select('name');
            $this->db->from('sj_strategy');
            $this->db->where('id', $strategyId);
            $query = $this->db->get();

            return $query->row()->name;
        } else {
            return "";
        }
    }


    public function get_substrand_from_question_id($questionId)

    {
        if(is_array($questionId)){
            $questionId = implode(",", $this->db->escape($questionId));
        }
        $sql = "SELECT `topic_id` FROM `sj_questions` WHERE `question_id` IN (".$questionId.")";
        $query = $this->db->query($sql);
        

//         $this->db->select('topic_id');

//         $this->db->from('sj_questions');

//         $this->db->where('question_id', $questionId);

//         $query = $this->db->get();



        $categoryId = $query->row()->topic_id;



        if (isset($categoryId) && empty($categoryId) === false) {

            $this->db->select('substrand_id');

            $this->db->from('sj_categories');

            $this->db->where('id', $categoryId);

            $query = $this->db->get();

            $substrandId = $query->row()->substrand_id;



            if (isset($substrandId) && empty($substrandId) === false) {

                $this->db->select('name');

                $this->db->from('sj_substrands');

                $this->db->where('id', $substrandId);

                $query = $this->db->get();

                return $query->row()->name;



            } else {

                return "";

            }

        } else {

            return "";

        }

    }



    public function get_category_name_from_category_id($category_id)

    {

        $this->db->select('name');

        $this->db->from('sj_categories');

        $this->db->where('id', $category_id);

        $query = $this->db->get();



        return $query->row()->name;

    }

    public function get_category_id_from_category_name($cat_name) {
        $sql = "SELECT * FROM `sj_categories` WHERE `name` =" . $this->db->escape($cat_name);
        $query = $this->db->query($sql);

        if($query->num_rows() > 0) {
            return $query->row()->id;
        } else {
            return null;
        }

    }



    public function get_strand_structure($subject = NULL)
    {
        $strands = array();

        if(isset($subject) && empty($subject) == FALSE) {
            $strand_sql = "SELECT * FROM sj_strands WHERE `subject_type` = ". $this->db->escape($subject)." AND `id` != 18 ORDER BY `id` ASC";
        } else {
            $strand_sql = "SELECT * FROM sj_strands WHERE `subject_type` = 2 AND `id` != 18 ORDER BY `id` ASC";
        }
        
        /*$strand_sql = "SELECT * FROM sj_strands WHERE `type` = 'Primary' ORDER BY `id` ASC";
        --- Secondary Math Prototype */
        $strand_sql_query = $this->db->query($strand_sql);



        foreach ($strand_sql_query->result() as $row) {

            $strand_object = array();

            $strand_name = $row->name;

            $strand_id = $row->id;

            $strand_object['name'] = $strand_name;



            

            if(isset($subject) && empty($subject) == FALSE) {
                $substrand_sql = "SELECT `id`, `name` FROM `sj_substrands` WHERE `subject_type` = ". $this->db->escape($subject)." AND `strand_id` = ?";
            } else {
                $substrand_sql = "SELECT `id`, `name` FROM `sj_substrands` WHERE `strand_id` = ?";
            }

            $substrand_sql_query = $this->db->query($substrand_sql, array($strand_id));

            $strand_object['substrand'] = array();

            foreach ($substrand_sql_query->result() as $substrand_row) {

                $substrand_object = array();

                $substrand_name = $substrand_row->name;

                $substrand_id = $substrand_row->id;

                $substrand_object['name'] = $substrand_name;



                

                if(isset($subject) && empty($subject) == FALSE) {
                    if($subject == 5) {
                        $categories_sql = "SELECT `id`, `name` FROM `sj_sec_categories` WHERE `subject_type` = ". $this->db->escape($subject)." AND `substrand_id` = ?";
                    } else {
                        $categories_sql = "SELECT `id`, `name` FROM `sj_categories` WHERE `subject_type` = ". $this->db->escape($subject)." AND `substrand_id` = ?";
                    }
                } else {
                    $categories_sql = "SELECT `id`, `name` FROM `sj_categories` WHERE `substrand_id` = ?";
                }

                $categories_sql_query = $this->db->query($categories_sql, array($substrand_id));



                $substrand_object['category'] = array();

                foreach ($categories_sql_query->result() as $category_row) {

                    $category_object = array();

                    $category_name = $category_row->name;

                    $category_id = $category_row->id;

                    $sql="
                        SELECT DISTINCT hr.strategy_id, hr.topic_id, str.name FROM `sj_heuristic_relationship` hr
                        JOIN 
                        (SELECT `id`,`name` FROM `sj_strategy`) str
                        WHERE hr.strategy_id = str.id
                        AND hr.topic_id = " . $this->db->escape($category_id) . "
                        ORDER BY hr.strategy_id ASC
                    ";

                    $query = $this->db->query($sql);

                    $heuristic = array();
                    $heu = array();
                    foreach($query->result() as $value){
                        $heu['strategy_id'] = $value->strategy_id;
                        $heu['strategy_name'] = $value->name;
                        $heuristic[$value->strategy_id] = $heu;
                        
                    }

                    $category_object['id'] = $category_id;

                    $category_object['name'] = $category_name;

                    $category_object['heuristic'] = $heuristic;

                    $substrand_object['category'][] = $category_object;

                }


                $strand_object['substrand'][] = $substrand_object;

            }

            $strands[] = $strand_object;

        }



        return $strands;

    }

    public function get_strategy_structure(){
        $sql = "SELECT * FROM `sj_categories` ORDER BY `id` ASC";

        $query = $this->db->query($sql);

        if($query->num_rows() > 0)
        {
            $cat_str = array();

            foreach($query->result() as $row)
            {
                $cat_id = $row->id;
                
                $sql = "
                    SELECT DISTINCT hr.strategy_id, hr.topic_id, str.name FROM `sj_heuristic_relationship` hr
                    JOIN 
                    (SELECT `id`,`name` FROM `sj_strategy`) str
                    WHERE hr.strategy_id = str.id
                    AND hr.topic_id = " . $this->db->escape($cat_id) . "
                    ORDER BY hr.strategy_id ASC
                ";
    
                $query = $this->db->query($sql);

                if($query->num_rows() >0)
                {
                    $str = array();
                    foreach($query->result() as $value)
                    {
                        $str[$value->strategy_id] = $value->name;
                    }
                    $cat_str[$value->topic_id] = $str;
                }   
            }
        }
        return $cat_str;
    }

    


    public function get_question_tags($question_id) {

        $query = $this->db->query("SELECT `tags` FROM `sj_question_tag` WHERE `question_id` = ?", array($question_id));

        return ($query->row())?$query->row()->tags:'';

    }



    public function flag_question($user_id, $question_id, $error_type, $error_comment)

    {

        $insert_array = array(

            'user_id' => $user_id,

            'question_id' => $question_id,

            'error_type' => $error_type,

            'error_comment' => $error_comment

        );



        $query = $this->db->insert('sj_question_issue', $insert_array);



        return ($query) ? true : false;



    }



    public function update_question_category($question_id, $update_category)

    {

        $data_ar = array(

            'topic_id' => $update_category

        );



        $this->db->where('question_id', $question_id);

        $query = $this->db->update('sj_questions', $data_ar);



        return $query ? true : false;

    }



    public function select_latest_question($number = 1, $category_id = NULL)

    {

        if (isset($category_id) && empty($category_id) === false) {

            $sql = "SELECT * FROM `sj_questions` WHERE `topic_id` = ?  AND `disabled` = 0 AND `source` = 0 ORDER BY `question_id` DESC LIMIT ?";

            $query = $this->db->query($sql, array($category_id, $number));

        } else {

            $sql = "SELECT * FROM `sj_questions` WHERE `disabled` = 0  AND `source` = 0  ORDER BY `question_id` DESC LIMIT ?";

            $query = $this->db->query($sql, array($number));

        }



        $questions = array();

        foreach ($query->result() as $row) {

            $questions[] = $row;

        }



        return $questions;

    }



    public function submit_question_comment($question_id, $user_id, $comment)

    {

        $insert_array = array(

            'question_id' => $question_id,

            'user_id' => $user_id,

            'comment' => $comment

        );



        $query = $this->db->insert('sj_askjen_comments', $insert_array);



        return $query;

    }



    public function get_comments($question_id)

    {

        $sql = "SELECT * FROM `sj_askjen_comments` WHERE `question_id` = ? ORDER BY `id` DESC";

        $query = $this->db->query($sql, array($question_id));



        $comments = array();

        foreach ($query->result() as $row) {

            $comments[] = $row;

        }



        return $comments;

    }



    public function get_comment_count($question_id)

    {

        $sql = "SELECT COUNT(`id`) as comment_count FROM `sj_askjen_comments` WHERE `question_id` = ?";

        $query = $this->db->query($sql, array($question_id));



        return $query->row()->comment_count;

    }



    public function get_vote_count($question_id)

    {

        $sql = "SELECT COUNT(`id`) as vote_count FROM `sj_askjen_vote` WHERE `question_id` = ?";

        $query = $this->db->query($sql, array($question_id));



        return $query->row()->vote_count;

    }



    public function get_view_count($question_id)

    {

        $sql = "SELECT `view_count` FROM `sj_askjen_view` WHERE `question_id` = ?";

        $query = $this->db->query($sql, array($question_id));



        return $query->row()->view_count;

    }



    public function get_question_type($question_id) {

        $sql = "SELECT `question_type_id` FROM `sj_questions` WHERE `question_id` = ?";

        $query = $this->db->query($sql, array($question_id));
        return $query->row();

    }



    function do_upload($type, $imageName, $index){

        $file_name = microtime(true);
        $file_name = str_replace('.', '', $file_name);
        $file_name = date('Y-m-d').'-'.$file_name;
        $_FILES['file']['name']       = $_FILES[$imageName]['name'][$index];
        $_FILES['file']['type']       = $_FILES[$imageName]['type'][$index];
        $_FILES['file']['tmp_name']   = $_FILES[$imageName]['tmp_name'][$index];
        $_FILES['file']['error']      = $_FILES[$imageName]['error'][$index];
        $_FILES['file']['size']       = $_FILES[$imageName]['size'][$index];

        if($type == 'question'){
            $config['upload_path']          = './img/questionImage/';
        }else if ($type == 'working'){
            $config['upload_path']          = './img/workingImage/';
        } else {
            $config['upload_path']          = './img/answerImage/';
        }

        $config['allowed_types']        = 'gif|png|jpg|jpeg';

        $allowedTypes = array('gif', 'png', 'jpeg', 'bmp');

        $path = $_FILES[$imageName]['name'][$index];
        $ext = pathinfo($path, PATHINFO_EXTENSION);

        if (in_array($ext, $allowedTypes)) {
            $config['allowed_types']        = '*';
        }

        $config['max_size']             = 1000;
        $config['file_name']            = $file_name;
        $config['file_ext_tolower']     = TRUE;
        $config['overwrite']            = TRUE;            

        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if ($this->upload->do_upload('file')) {
            return $this->upload->data('file_name');
        }else{    
            $this->session->set_flashdata('update_error', $this->upload->display_errors());
            
            redirect(base_url($this->uri->uri_string()));

            // return $data['error_upload']['error'];;
        }

    }



    public function create_new_question($post, $source=0) {                 
       
        $mapping = array(
            '-1' => 'A',
            '0'  => 'B',
            '1'  => 'C',
            '2'  => 'D',
            '3'  => 'E',
            '4'  => 'F',
            '5'  => 'G',
            '6'  => 'H',
            '7'  => 'I',
            '8'  => 'J', 
            '9'  => 'K',
            '10' => 'L'
        );

        $this->db->trans_start();
        if ($this->session->userdata('is_logged_in') == 1) {
            $source = $this->session->userdata('user_id');
        } else {
            $source = 0;
        }

        $sub_question_number = intval($post['subQuestionNumber']);

        // fetch latest question counter by subject and branch name for new question insertion
        $subject_id = $post['subject_id'];
        $question_counter = $this->get_new_question_counter($subject_id,'SmartJen');

        for ($i = -1; $i < $sub_question_number; $i++) {
            
            if ($post['subject_id'] == '2'){
                $strategy = $this->get_strategy_id($post['substrategy_id']);
                $strategy2 = $this->get_strategy_id($post['substrategy_id2']);
                $strategy3 = $this->get_strategy_id($post['substrategy_id3']);
                $strategy4 = $this->get_strategy_id($post['substrategy_id4']);
            } else {
                $strategy = '0';
                $strategy2 = '0';
                $strategy3 = '0';
                $strategy4 = '0';
            }

            if($this->session->userdata('admin_username') == 'admindemo'){
                $question_level = 1;
                $branch_name = BRANCH_NAME;
            } else {
                $question_level = 0;
                $branch_name = 'SmartJen';
            }
    
            $question_type_id = ($i == -1)?$post['question_type_id']:$post['question_type_id_' . $i];
            //$question_text = ($i == -1)?$this->applyMathJaxFormat($post['question_text']):$this->applyMathJaxFormat($post['question_text_' . $i]);
            $questionContent = ($i == -1)?str_replace(",", " ", $post['input_question_content']):str_replace(",", " ", $post['input_question_content'.$i]);
            $workingContent = ($i == -1)?str_replace(",", " ", $post['input_working_content']):str_replace(",", " ", $post['input_working_content'.$i]);

            $arrIqc = explode(" ", $questionContent);
            $arrIwc = explode(" ", $workingContent);

            $currentQuestionContent = '';
            $currentWorkingContent = '';

            $countText = 0;
            $countImage = 0;
            
            for($x=1 ; $x<count($arrIqc) ; $x++){                
                ($arrIqc[$x] == 'text')?$countText++:$countImage++;
            }

            $countSetText = $countText;
            $countSetImage = $countImage;
            
            if($i == -1){
                $currentQuestionContent = ($arrIqc[0] == 'text')?$this->applyMathJaxFormat($post['input_question_text'][0]):$this->do_upload('question', 'input_question_image', 0);
                if($arrIqc[0] == 'text'){
                    $countText--;
                }else{
                    $countImage--;
                }

            }else{
                $currentQuestionContent = ($arrIqc[0] == 'text')?$this->applyMathJaxFormat($post['input_subquestion_text_'.$i.'_'][0]):$this->do_upload('question', 'input_subquestion_image_'.$i.'_', 0);
                if($arrIqc[0] == 'text'){
                    $countText--;
                }else{
                    $countImage--;
                }
            }            

            $insert_array = array(
                'question_text'    => $currentQuestionContent,
                'question_counter' => $question_counter,
                'reference_id'     => '1',
                'level_id'         => $post['level_id'],
                'topic_id'         => $post['topic_id'],
                'topic_id2'        => $post['topic_id2'],
                'topic_id3'        => $post['topic_id3'],
                'topic_id4'        => $post['topic_id4'],
                'key_topic'        => $post['key_topic'],
                'key_strategy'     => $post['key_strategy'],
                'substrategy_id'   => $post['substrategy_id'],
                'substrategy_id2'  => $post['substrategy_id2'],
                'substrategy_id3'  => $post['substrategy_id3'],
                'substrategy_id4'  => $post['substrategy_id4'],
                'strategy_id'      => $strategy,
                'strategy_id2'     => $strategy2,
                'strategy_id3'     => $strategy3,
                'strategy_id4'     => $strategy4,
                'question_level'   => $question_level,
                'content_type'     => ($arrIqc[0] == 'text')?'text':'image',
                'question_content' => (count($arrIqc) > 1)?1:0,
                'school_id'        => $post['school_id'],
                'year'             => $post['year'],
                'question_type_id' => $question_type_id,
                'question_type_reference_id' => $question_type_id,
                'difficulty'       => ($i == -1)?$post['difficulty']:$post['difficulty_' . $i],
                'source'           => $source,
                'sub_question'     => $mapping[$i],
                'answer_type_id'   => ($i == -1)?$post['answer_type_id']:$post['answer_type_id_'. $i],
                'source'           => $source,
                'branch_name'      => $branch_name,
                'subject_type'     => $post['subject_id']
            );

            $this->db->insert('sj_questions', $insert_array);
            $ques_id = $this->db->insert_id();
            $ref_id = ($i == -1)?$ques_id:$ref_id;
            $this->update_reference_id($ques_id, $ref_id);


            // insert question content            
            
                for($x=1 ; $x<count($arrIqc) ; $x++){                
                    
                    if($arrIqc[$x] == 'text'){                        
                        $data = [
                            'question_id' => $ques_id,
                            'content_section' => 'question',
                            'content_type' => 'text',
                            'content_order' => $x+1, 
                            'content_name' => ($i == -1)?$this->applyMathJaxFormat($post['input_question_text'][$countSetText-$countText]):$this->applyMathJaxFormat($post['input_subquestion_text_'.$i.'_'][$countSetText-$countText])
                        ];                        
                        $countText--;
                    }else{
                        $data = [
                            'question_id' => $ques_id,
                            'content_section' => 'question',
                            'content_type' => 'image',
                            'content_order' => $x+1, 
                            'content_name' => ($i == -1)?$this->do_upload('question', 'input_question_image', ($countSetImage-$countImage)):$this->do_upload('question', 'input_subquestion_image_'.$i.'_', ($countSetImage-$countImage))
                        ];                        
                        $countImage--;
                    }

                    $this->db->insert('sj_question_content', $data);

                }
            


            // insert answers and correct answers
            if ($question_type_id == 1) {  // mcq

                $mcq_type = ($i == -1)?$post['answer_type_mcq']:$post['answer_type_mcq_' . $i];

                if($mcq_type == 'text'){

                    $insert_mcq_answers = array(
                        array(
                            'question_id' => $ques_id,
                            'answer_type' => 'text',
                            'answer_text' => ($i == -1)?$this->applyMathJaxFormat($post['mcq_answers'][0]):$this->applyMathJaxFormat($post['mcq_answers_' . $i][0])
                        ),
                        array(
                            'question_id' => $ques_id,
                            'answer_type' => 'text',
                            'answer_text' => ($i == -1)?$this->applyMathJaxFormat($post['mcq_answers'][1]):$this->applyMathJaxFormat($post['mcq_answers_' . $i][1])
                        ),
                        array(
                            'question_id' => $ques_id,
                            'answer_type' => 'text',
                            'answer_text' => ($i == -1)?$this->applyMathJaxFormat($post['mcq_answers'][2]):$this->applyMathJaxFormat($post['mcq_answers_' . $i][2])
                        ),
                        array(
                            'question_id' => $ques_id,
                            'answer_type' => 'text',
                            'answer_text' => ($i == -1)?$this->applyMathJaxFormat($post['mcq_answers'][3]):$this->applyMathJaxFormat($post['mcq_answers_' . $i][3])
                        )
                    );

                    $this->db->insert_batch('sj_answers', $insert_mcq_answers);
                    $first_answer_id = $this->db->insert_id();

                }else{

                    $data = [
                        'question_id' => $ques_id,
                        'answer_type' => 'image',
                        'answer_text' => $this->do_upload('answer', ($i == -1)?'mcq_answers_image':'mcq_answers_image_'.$i, 0)
                    ];
                    $this->db->insert('sj_answers', $data);
                    $first_answer_id = $this->db->insert_id();


                    $data = [
                        'question_id' => $ques_id,
                        'answer_type' => 'image',
                        'answer_text' => $this->do_upload('answer', ($i == -1)?'mcq_answers_image':'mcq_answers_image_'.$i, 1)
                    ];
                    $this->db->insert('sj_answers', $data);


                    $data = [
                        'question_id' => $ques_id,
                        'answer_type' => 'image',
                        'answer_text' => $this->do_upload('answer', ($i == -1)?'mcq_answers_image':'mcq_answers_image_'.$i, 2)
                    ];
                    $this->db->insert('sj_answers', $data);


                    $data = [
                        'question_id' => $ques_id,
                        'answer_type' => 'image',
                        'answer_text' => $this->do_upload('answer', ($i == -1)?'mcq_answers_image':'mcq_answers_image_'.$i, 3)
                    ];
                    $this->db->insert('sj_answers', $data);                    

                }

                
                $mcq_correct_answer = ($i == -1)?$post['mcq_correct_answer']:$post['mcq_correct_answer_' . $i];
                $correct_answer_id = $first_answer_id + $mcq_correct_answer - 1;
                $this->db->insert('sj_correct_answer', array(
                    'question_id' => $ques_id,
                    'answer_id'   => $correct_answer_id
                ));
            
            } else if ($question_type_id == 2) {  // open ended

                // $count_working_content = 0;
                
                // if($i == -1){

                //     if(isset($_FILES['input_working_content']['name'][0])){
                //         $working_text = $this->do_upload('working', 'input_working_content',0);
                //         $working_type = 'image';

                //     } else if(isset($post['input_working_content'][0])) {
                //         $working_type = 'text';
                //         $working_text = $this->applyMathJaxFormat($post['input_working_content'][0]);
                //     } else {
                //         $working_text = '';
                //         $working_type = 'text';
                //     }
    
                // } else {

                //     if(isset($_FILES['input_subworking_content_'.$i.'_']['name'][0])){
                //         $working_text = $this->do_upload('working', 'input_subworking_content_'.$i.'_',0);
                //         $working_type = 'image';

                //     } else if(isset($post['input_subworking_content_'.$i.'_'][0])) {
                //         $working_type = 'text';
                //         $working_text = $this->applyMathJaxFormat($post['input_subworking_content_'.$i.'_'][0]);
                //     } else {
                //         $working_text = '';
                //         $working_type = 'text';
                //     }
                // }

                //insert first working content
                $countWorkingText = 0;
                $countWorkingImage = 0;
                
                for($x=1 ; $x<count($arrIwc) ; $x++){                
                    ($arrIwc[$x] == 'text')?$countWorkingText++:$countWorkingImage++;
                }

                $countWorkingSetText = $countWorkingText;
                $countWorkingSetImage = $countWorkingImage;
                
                if($i == -1){
                    if($arrIwc[0] == 'text'){
                        $currentWorkingContent = $this->applyMathJaxFormat($post['input_working_text'][0]);
                        $countWorkingText--;
                    } else if($arrIwc[0] == 'image') {
                        $currentWorkingContent = $this->do_upload('working', 'input_working_image', 0);
                        $countWorkingImage--;
                    } else {
                        $currentWorkingContent = '';
                    }
                    // $currentWorkingContent = $arrIwc[0] == 'text'?$this->applyMathJaxFormat($post['input_working_text'][0]):$this->do_upload('working', 'input_working_image', 0);
                    // if($arrIwc[0] == 'text'){
                    //     $countWorkingText--;
                    // }else{
                    //     $countWorkingImage--;
                    // }

                }else{
                    if($arrIwc[0] == 'text'){
                        $currentWorkingContent = $this->applyMathJaxFormat($post['input_subworking_text_'.$i.'_'][0]);
                        $countWorkingText--;
                    } else if($arrIwc[0] == 'image') {
                        $currentWorkingContent = $this->do_upload('working', 'input_subworking_image_'.$i.'_', 0);
                        $countWorkingImage--;
                    } else {
                        $currentWorkingContent = '';
                    }
                    // $currentWorkingContent = ($arrIwc[0] == 'text')?$this->applyMathJaxFormat($post['input_subworking_text_'.$i.'_'][0]):$this->do_upload('working', 'input_subworking_image_'.$i.'_', 0);
                    // if($arrIwc[0] == 'text'){
                    //     $countWorkingText--;
                    // }else{
                    //     $countWorkingImage--;
                    // }
                }                
                
                // insert nmcq answer and correct answer

                $nmcq_type = ($i == -1)?$post['answer_type_nmcq']:$post['answer_type_nmcq_' . $i];

                if($nmcq_type == 'text'){
                    $nmcq_answer_text = ($i == -1)?$this->applyMathJaxFormat($post['open_ended_answer']):$this->applyMathJaxFormat($post['open_ended_answer_' . $i]);
                    $nmcq_answer_type = 'text';
                } else {
                    $nmcq_answer_text = $this->do_upload('answer', ($i == -1)?'nmcq_answers_image':'nmcq_answers_image_'.$i, 0);
                    $nmcq_answer_type = 'image';
                }

                $this->db->insert('sj_answers', array(
                    'question_id' => $ques_id,
                    'answer_text' => $nmcq_answer_text,
                    'answer_type' => $nmcq_answer_type,
                    'working_type' => ($arrIwc[0] == 'image')?'image':'text',
                    'working_text' => $currentWorkingContent
                ));

                $correct_answer_id = $this->db->insert_id();
                $this->db->insert('sj_correct_answer', array(
                    'question_id' => $ques_id,
                    'answer_id'   => $correct_answer_id
                ));

                // insert rest workings content            
            
                for($x=1 ; $x<count($arrIwc) ; $x++){                
                    
                    if($arrIwc[$x] == 'text'){                        
                        $data = [
                            'question_id' => $ques_id,
                            'content_section' => 'working',
                            'content_type' => 'text',
                            'content_order' => $x+1, 
                            'content_name' => ($i == -1)?$this->applyMathJaxFormat($post['input_working_text'][$countWorkingSetText-$countWorkingText]):$this->applyMathJaxFormat($post['input_subworking_text_'.$i.'_'][$countWorkingSetText-$countWorkingText])
                        ];                        
                        $countWorkingText--;
                    }else{
                        $data = [
                            'question_id' => $ques_id,
                            'content_section' => 'working',
                            'content_type' => 'image',
                            'content_order' => $x+1, 
                            'content_name' => ($i == -1)?$this->do_upload('working', 'input_working_image', ($countWorkingSetImage-$countWorkingImage)):$this->do_upload('working', 'input_subworking_image_'.$i.'_', ($countWorkingSetImage-$countWorkingImage))
                        ];                        
                        $countWorkingImage--;
                    }

                    $this->db->insert('sj_question_content', $data);

                }

                if(count($arrIwc) > 1){
                    $sql = "UPDATE `sj_questions` SET `question_content` = '1' WHERE `question_id` =" . $ques_id;
                    $query = $this->db->query($sql);
                }

                // // Store another 5 working sections - shuq
                // $count = 0;
                // $content_order = 1;

                // if($i == -1){
                //     // main question workings
                //     while($count <6){
                //         if(isset($_FILES['input_working_content']['name'][$content_order])){

                //             $working_text = $this->do_upload('working', 'input_working_content',$content_order);

                //             $insert_array = array(
                //                 'question_id'    => $ques_id,
                //                 'content_section'     => 'working',
                //                 'content_type'         => 'image',
                //                 'content_order'         => $content_order + 1,
                //                 'content_name'        => $working_text
                //             );
                //             $this->db->insert('sj_question_content', $insert_array);

                //             $content_order++;
                //             $count++;
    
                //         } else if(isset($post['input_working_content'][$content_order])) {

                //             $working_text = $this->applyMathJaxFormat($post['input_working_content'][$content_order]);

                //             //insert new workings
                //             $insert_array = array(
                //                 'question_id'    => $ques_id,
                //                 'content_section'     => 'working',
                //                 'content_type'         => 'text',
                //                 'content_order'         => $content_order + 1,
                //                 'content_name'        => $working_text
                //             );
                //             $this->db->insert('sj_question_content', $insert_array);

                //             $content_order++;
                //             $count++;
                //         } else {
                //             //break while loop
                //             break;
                //         }
                //     }

                //     if($content_order >= 1){
                //         $sql = "UPDATE `sj_questions` SET `question_content` = '1' WHERE `question_id` =" . $ques_id;
                //         $query = $this->db->query($sql);
                //     }
                // } else {
                //     // subquestion workings
                //     while($count <6){
                //         if(isset($_FILES['input_subworking_content_'.$i.'_'][$content_order])){

                //             $working_text = $this->do_upload('working', 'input_subworking_content_'.$i.'_',$content_order);

                //             $insert_array = array(
                //                 'question_id'    => $ques_id,
                //                 'content_section'     => 'working',
                //                 'content_type'         => 'image',
                //                 'content_order'         => $content_order + 1,
                //                 'content_name'        => $working_text
                //             );
                //             $this->db->insert('sj_question_content', $insert_array);

                //             $content_order++;
                //             $count++;
    
                //         } else if(isset($post['input_subworking_content_'.$i.'_'][$content_order])) {

                //             $working_text = $this->applyMathJaxFormat($post['input_subworking_content_'.$i.'_'][$content_order]);

                //             //insert new workings
                //             $insert_array = array(
                //                 'question_id'    => $ques_id,
                //                 'content_section'     => 'working',
                //                 'content_type'         => 'text',
                //                 'content_order'         => $content_order + 1,
                //                 'content_name'        => $working_text
                //             );
                //             $this->db->insert('sj_question_content', $insert_array);

                //             $content_order++;
                //             $count++;
                //         } else {
                //             //break while loop
                //             break;
                //         }
                //     }

                //     if($content_order >= 1){
                //         $sql = "UPDATE `sj_questions` SET `question_content` = '1' WHERE `question_id` =" . $ques_id;
                //         $query = $this->db->query($sql);
                //     }
                // }
                
                //end store 5 sections
            }

            $this->update_question_view($ques_id);
            $this->update_question_tag($ques_id, ($i == -1)?$post['tagsinput']:$post['tagsinput_' . $i]);

            $question_counter++;
        }

        $this->db->trans_complete();

        return array(
            'status'      => $this->db->trans_status(),
            'question_id' => isset($ques_id)?$ques_id:''
        );

    }

    private function applyMathJaxFormat($text) {
        if (is_numeric($text)) {  // is answer ID

            return $text;

        } else {

            // [WM] 4Mar18: Fix //frac{}{} problem Part 1: if no space is inserted before //frac, add space before //frac

            // [Shuq] 19Mar20 : Fix spacing issue for \\frac, still under testing
            // $text = str_replace('\\frac{', '\\ \\frac{', $text);
            
            $text = str_replace('<br>','\\ br\\ ',$text);

            $text = str_replace('S\\$','S$',$text);

            $text = str_replace('US\\$','US$',$text);

            $text = str_replace('A\\$','A$',$text);

            $explodedString = explode('\\ ', $text);

            $appliedMathJaxFormatToken = array();

            foreach ($explodedString as $str) {
                // if the token starts with backslash, it's a math symbol!

                // or if the string contains ^ , we also need to add the \( and \)

                if (substr($str, 0, 1) == '\\' || strpos($str, '^') !== false || strpos($str, '_') !== false || strpos($str, '\\pi') !== false) {
                    //remove spacing for underline without combine text
                    if ((strpos($str, '\\underline') !== false)) {
                        $str = str_replace(' ', '', $str);
                        $appliedMathJaxFormatToken[] = '\\(' . $str . '\\)';
                    //format angle issue
                    } else if (strpos($str, '\\angle ') !== false){
                        // $appliedMathJaxFormatToken[] = '\\(' . $str . '\\)';
                        $str = explode(' ', $str);
                        $appliedMathJaxFormatToken[] = '\\(' . $str[0] . '\\) ' . $str[1];
                    } else {
                        $appliedMathJaxFormatToken[] = '\\(' . $str . '\\)';
                    }
                    
                } else if ($str == 'br') {
                    $appliedMathJaxFormatToken[] = '<br>';

                } else {
                    if ((strpos($str, '\\underline') !== false)) {
                        //remove spacing for underline if underline combine with other text
                        $str = str_replace('\\underline', ' \\underline', $str);
                        $str = str_replace(' ', '', $str);
                        $appliedMathJaxFormatToken[] = '\\(' . $str . '\\)';
                    } else {
                        $appliedMathJaxFormatToken[] = $str;
                    }                     
                }
                
            }

            // [WM] 4Mar18: Fix //frac{}{} problem Part 2: if there is a numeric before //frac, remove the space between for better look

            $outputStr = '';

            foreach($appliedMathJaxFormatToken as $idxToken => $outputStrToken) {
                if($idxToken > 0 && is_numeric($appliedMathJaxFormatToken[$idxToken-1]) && strpos($outputStrToken,'\\frac{') !== false) {

                    $outputStr = $outputStr . $outputStrToken; // no space if numeric number followed by fraction

                } else if($outputStrToken == '<br>' || ($idxToken > 0 && $appliedMathJaxFormatToken[$idxToken-1] == '<br>')) {
                    $outputStr = $outputStr . $outputStrToken;
                } else if($idxToken > 0 && $appliedMathJaxFormatToken[$idxToken-1] == '(') {
                    $outputStr = $outputStr . $outputStrToken;
                } else {
                    $outputStr = $outputStr . ' ' . $outputStrToken;

                }
            }



            return $outputStr;

        }
        
    }



    private function update_question_view($ques_id) {

        $this->db->insert('sj_askjen_view', array(

            'question_id' => $ques_id

        ));

    }



    private function update_question_tag($ques_id, $tags) {

        $this->db->insert('sj_question_tag', array(

            'question_id' => $ques_id,

            'tags'        => $tags

        ));

    }



    private function update_reference_id($ques_id, $ref_id) {

        $query = "UPDATE `sj_questions` set `reference_id` = ? WHERE `question_id` = ?";

        $sql = $this->db->query($query, array($ref_id, $ques_id));



        return $sql;

    }



    public function update_question_image($image_name, $question_id) {

        $query = "UPDATE `sj_questions` set `graphical` = ? WHERE `question_id` = ?";

        $sql = $this->db->query($query, array($image_name, $question_id));



        return $sql;

    }

    public function update_working_image($image_name, $question_id, $content_order) {

        if($content_order == 1){
            $query = "UPDATE `sj_answers` set working_type = 'image', working_text = ? WHERE `question_id` = ?";
            $sql = $this->db->query($query, array($image_name, $question_id));

            return $sql;
        } else {

            $working_query = $this->db->query("SELECT * FROM sj_question_content WHERE `content_section` = 'working' AND question_id = ?", array($question_id));

            if($working_query->num_rows() == 0){
                $insert_array = array(
                    'question_id'    => $question_id,
                    'content_section'     => 'working',
                    'content_type'         => 'image',
                    'content_order'         => $content_order,
                    'content_name'        => $image_name
                );
                $this->db->insert('sj_question_content', $insert_array);

                $sql = "UPDATE `sj_questions` SET `question_content` = '1' WHERE `question_id` = " . $question_id;
                $query = $this->db->query($sql);

                return $query;

            } else{
                //update existing workings
                $count = $content_order - 1;

                if($working_query->num_rows() >= $count){
                
                    $this->db->query("UPDATE sj_question_content SET content_type = 'image', content_name = ? WHERE content_order = ? and question_id = ?", array(
                        $image_name,
                        $content_order,
                        $question_id
                    ));

                    $sql = "UPDATE `sj_questions` SET `question_content` = '1' WHERE `question_id` = " . $question_id;
                    $query = $this->db->query($sql);

                    return $query;

                } else {

                    //insert new workings
                    $insert_array = array(
                        'question_id'    => $question_id,
                        'content_section'     => 'working',
                        'content_type'         => 'image',
                        'content_order'         => $content_order,
                        'content_name'        => $image_name
                    );
                    $this->db->insert('sj_question_content', $insert_array);

                    $sql = "UPDATE `sj_questions` SET `question_content` = '1' WHERE `question_id` = " . $question_id;
                    $query = $this->db->query($sql);

                    return $query;
                }
            }
        }
    }


    public function update_question($post, $question_id) {        

        //check disabled checkbox
        if ($post['disabled'] == 'on'){
            $disabled = 1;
        } else {
            $disabled = 0;
        }

        if ($post['subject_id'] == '2'){
            $strategy = $this->get_strategy_id($post['substrategy_id']);
            $strategy2 = $this->get_strategy_id($post['substrategy_id2']);
            $strategy3 = $this->get_strategy_id($post['substrategy_id3']);
            $strategy4 = $this->get_strategy_id($post['substrategy_id4']);
        } else {
            $strategy = '0';
            $strategy2 = '0';
            $strategy3 = '0';
            $strategy4 = '0';
        }

        // update answer type id 
        if($post['question_type_id'] == 4) {

            $answer_type_id = 1;

        } else {

            $answer_type_id = $post['answer_type_id'];

        }


        $questionContent = str_replace(",", " ", $post['input_question_content']);
        $arrIqc = explode(" ", $questionContent);
        $currentQuestionContent = '';

        $countText = 0;
        $countImage = 0;
            
        for($x=1 ; $x<count($arrIqc) ; $x++){                
            ($arrIqc[$x] == 'text')?$countText++:$countImage++;
        }

        $countSetText = $countText;
        $countSetImage = $countImage;

        // get current question detail
        $question_detail = $this->get_question_from_id($question_id);

        if($arrIqc[0] == 'text'){
            $currentQuestionContent = trim($this->applyMathJaxFormat($post['input_question_text'][0]));
            $countText--;
        } else { 
            if(is_uploaded_file($_FILES['input_question_image']['tmp_name'][0])){
                $currentQuestionContent = $this->do_upload('question', 'input_question_image', 0);
                $countImage--;
            } else {
                $currentQuestionContent = $question_detail->question_text;
                $countImage--;
            }
        }

        // $currentQuestionContent = ($arrIqc[0] == 'text')?trim($this->applyMathJaxFormat($post['input_question_text'][0])):$this->do_upload('question', 'input_question_image', 0);
        // if($arrIqc[0] == 'text'){
        //     $countText--;
        // }else{
        //     $countImage--;
        // }     
        

        // insert to sj_questions table first, to get the question ID
        $update_array = array(
            'question_text' => $currentQuestionContent,
            'level_id' => $post['level_id'],
            'topic_id' => $post['topic_id'],
            'topic_id2' => $post['topic_id2'],
            'topic_id3' => $post['topic_id3'],
            'topic_id4' => $post['topic_id4'],
            'key_topic' => $post['key_topic'],
            'key_strategy' => $post['key_strategy'],
            'substrategy_id' => $post['substrategy_id'],
            'substrategy_id2' => $post['substrategy_id2'],
            'substrategy_id3' => $post['substrategy_id3'],
            'substrategy_id4' => $post['substrategy_id4'],
            'school_id' => $post['school_id'],
            'year' => $post['year'],
            'question_type_id' => $post['question_type_id'],
            'difficulty' => $post['difficulty'],
            'disabled' => $disabled,
            'strategy_id' => $strategy,
            'strategy_id2' => $strategy2,
            'strategy_id3' => $strategy3,
            'strategy_id4' => $strategy4,
            'subject_type' => $post['subject_id'],
            'content_type'     => ($arrIqc[0] == 'text')?'text':'image',
            'question_content' => (count($arrIqc) > 1)?1:0
        );
        
        $this->db->trans_start();
        $this->db->where('question_id', $question_id);
        $query = $this->db->update('sj_questions', $update_array);

        // $this->db->where('question_id', $question_id);
        // $this->db->delete('sj_question_content');

        $question_content = $this->get_question_content_from_id($question_id);

        // delete previous unused question content
        if(count($question_content) > count($arrIqc)-1){
            $delete_content = count($arrIqc) + 1;

            $delete_sql = "DELETE FROM `sj_question_content` WHERE `content_order` >= " .$delete_content." AND `question_id` = " .$question_id." AND `content_section` = 'question'";
            $query = $this->db->query($delete_sql);
        }

        for($x=1 ; $x<count($arrIqc) ; $x++){                
            
            if(count($question_content) > 0 && $x <= count($question_content)){
                foreach($question_content as $row){

                    // update question fields
                    if($row->content_order == $x+1){
                        if($arrIqc[$x] == 'text'){
                            $data = [
                                'question_id' => $question_id,
                                'content_section' => 'question',
                                'content_type' => 'text',
                                'content_order' => $x+1, 
                                'content_name' => trim($this->applyMathJaxFormat($post['input_question_text'][$countSetText-$countText]))
                            ]; 
                            $this->db->where('content_id', $row->content_id);
                            $this->db->update('sj_question_content', $data);

                            $countText--;
                        } else {
                            if(is_uploaded_file($_FILES['input_question_image']['tmp_name'][$countSetImage-$countImage])){
                                $data = [
                                    'question_id' => $question_id,
                                    'content_section' => 'question',
                                    'content_type' => 'image',
                                    'content_order' => $x+1, 
                                    'content_name' => $this->do_upload('question', 'input_question_image', ($countSetImage-$countImage))
                                ];   
                                $this->db->where('content_id', $row->content_id);
                                $this->db->update('sj_question_content', $data);   
                            }
                                              
                            $countImage--;
                        }
                    } 
                }
            } else {

                //insert new question content
                if($arrIqc[$x] == 'text'){                        
                    $data = [
                        'question_id' => $question_id,
                        'content_section' => 'question',
                        'content_type' => 'text',
                        'content_order' => $x+1, 
                        'content_name' => trim($this->applyMathJaxFormat($post['input_question_text'][$countSetText-$countText]))
                    ]; 
                    $this->db->insert('sj_question_content', $data);
    
                    $countText--;
                }else{
                    if(is_uploaded_file($_FILES['input_question_image']['tmp_name'][$countSetImage-$countImage])){
                        $data = [
                            'question_id' => $question_id,
                            'content_section' => 'question',
                            'content_type' => 'image',
                            'content_order' => $x+1, 
                            'content_name' => $this->do_upload('question', 'input_question_image', ($countSetImage-$countImage))
                        ];   
    
                        $this->db->insert('sj_question_content', $data);   
                    }
                                      
                    $countImage--;
                }
            }
            
            // $this->db->insert('sj_question_content', $data);
        }

        // update existing answers
        $query = $this->db->query("SELECT answer_id FROM sj_answers WHERE question_id = ?", array($question_id));

        // insert answer and correct answers if empty row in answer table
        if($query->num_rows() == 0){
            if ($post['question_type_id'] == 1 || $post['question_type_id'] == 4) {  // mcq

                if(array_key_exists("mcq_correct_answer",$post) === false) {
                    return false;
                }

                $mcq_type = $post['answer_type_mcq'];
                if($mcq_type == 'text'){

                    $insert_mcq_answers = array(
                        array(
                            'question_id' => $question_id,
                            'answer_type' => 'text',
                            'answer_text' => $this->applyMathJaxFormat($post['mcq_answers'][0])
                        ),
                        array(
                            'question_id' => $question_id,
                            'answer_type' => 'text',
                            'answer_text' => $this->applyMathJaxFormat($post['mcq_answers'][1])
                        ),
                        array(
                            'question_id' => $question_id,
                            'answer_type' => 'text',
                            'answer_text' => $this->applyMathJaxFormat($post['mcq_answers'][2])
                        ),
                        array(
                            'question_id' => $question_id,
                            'answer_type' => 'text',
                            'answer_text' => $this->applyMathJaxFormat($post['mcq_answers'][3])
                        )
                    );

                    $this->db->insert_batch('sj_answers', $insert_mcq_answers);
                    $first_answer_id = $this->db->insert_id();

                }else{

                    $data = [
                        'question_id' => $question_id,
                        'answer_type' => 'image',
                        'answer_text' => $this->do_upload('answer', 'mcq_answers_image', 0)
                    ];                    

                    $this->db->insert('sj_answers', $data);
                    $first_answer_id = $this->db->insert_id();

                    $data = [
                        'question_id' => $question_id,
                        'answer_type' => 'image',
                        'answer_text' => $this->do_upload('answer', 'mcq_answers_image', 1)
                    ];

                    $this->db->insert('sj_answers', $data);

                    $data = [
                        'question_id' => $question_id,
                        'answer_type' => 'image',
                        'answer_text' => $this->do_upload('answer', 'mcq_answers_image', 2)
                    ];

                    $this->db->insert('sj_answers', $data);

                    $data = [
                        'question_id' => $question_id,
                        'answer_type' => 'image',
                        'answer_text' => $this->do_upload('answer', 'mcq_answers_image', 3)
                    ];

                    $this->db->insert('sj_answers', $data);

                }
                

                $mcq_correct_answer = $post['mcq_correct_answer'];
                $correct_answer_id = $first_answer_id + $mcq_correct_answer - 1;

                $sql = "INSERT INTO `sj_correct_answer` (`question_id`, `answer_id`) VALUES ('".$question_id."', '".$correct_answer_id."')";
                $query = $this->db->query($sql);

                // $this->db->insert('sj_correct_answer', array(
                //     'question_id' => $question_id,
                //     'answer_id'   => $correct_answer_id
                // ));
            
            } else if ($post['question_type_id'] == 2) {  // open ended
                $this->db->insert('sj_answers', array(
                    'question_id' => $question_id,
                    'answer_text' => $this->applyMathJaxFormat($post['open_ended_answer'])
                ));
                $correct_answer_id = $this->db->insert_id();
                $this->db->insert('sj_correct_answer', array(
                    'question_id' => $question_id,
                    'answer_id'   => $correct_answer_id
                ));
            }
        } else {

            // update answers and correct answers
            if ($post['question_type_id'] == 1 || $post['question_type_id'] == 4) {  // mcq
                $index = 0;
                $first_answer_id = 0;
                $answerArray = $query->result();
                if($query->num_rows() < 4) {

                    foreach ($answerArray as $row) {
                        if ($index == 0) {
                            $first_answer_id = $row->answer_id;

                            $this->db->query("UPDATE sj_answers SET answer_text = ? WHERE answer_id = ?", array(
                                trim($this->applyMathJaxFormat($post['mcq_answers'][$index])),
                                $first_answer_id
                            ));
                        }
                        $index++;
                    }

                    $insert_mcq_answers = array(
                        array(
                            'question_id' => $question_id,
                            'answer_text' => $this->applyMathJaxFormat($post['mcq_answers'][1])
                        ),
                        array(
                            'question_id' => $question_id,
                            'answer_text' => $this->applyMathJaxFormat($post['mcq_answers'][2])
                        ),
                        array(
                            'question_id' => $question_id,
                            'answer_text' => $this->applyMathJaxFormat($post['mcq_answers'][3])
                        )
                    );
                    $this->db->insert_batch('sj_answers', $insert_mcq_answers);

                } else {

                    //update mcq text / image
                    if($post['answer_type_mcq'] == 'text'){
                        foreach ($answerArray as $row) {
                            if ($index == 0) {
                                $first_answer_id = $row->answer_id;
                            }
        
                            $this->db->query("UPDATE sj_answers SET answer_text = ?, answer_type = ? WHERE answer_id = ?", array(
                                trim($this->applyMathJaxFormat($post['mcq_answers'][$index])),
                                'text',
                                $row->answer_id
                            ));
                            $index++;
                        }
                    } else {

                        foreach ($answerArray as $row) {
                            if ($index == 0) {
                                $first_answer_id = $row->answer_id;
                            }

                            if(is_uploaded_file($_FILES['mcq_answers_image']['tmp_name'][$index])){
                                $mcq_answer_text = $this->do_upload('answer', 'mcq_answers_image', $index);
                                $mcq_answer_type = 'image';
    
                                $this->db->query("UPDATE sj_answers SET answer_text = ?, answer_type = ? WHERE answer_id = ?", array(
                                    $mcq_answer_text,
                                    $mcq_answer_type,
                                    $row->answer_id
                                ));
                            }
                            $index++;
                        }
                        
                    }

                }
                
                if(array_key_exists("mcq_correct_answer",$post) === false) {
                    return false;
                }

                $answerArray = $this->db->query("SELECT answer_id FROM sj_answers WHERE question_id = ?", array($question_id));
                $answerArray = $answerArray->result();

                $sql = "SELECT * FROM `sj_correct_answer` WHERE `question_id` =" . $question_id;
                $query = $this->db->query($sql);

                $correct_answer_id = $answerArray[$post['mcq_correct_answer'] - 1]->answer_id;

                if($query->num_rows() > 0) {
                    $this->db->query("UPDATE sj_correct_answer SET answer_id = ? WHERE question_id = ?", array(
                        $correct_answer_id,
                        $question_id
                    ));
                } else {
                    $sql = "INSERT INTO `sj_correct_answer` (`question_id`, `answer_id`) VALUES ('" . $question_id . "', '" .  $correct_answer_id . "')";
                    $query = $this->db->query($sql);
                }   
            // } else if ($post['question_type_id'] == 2) {  // open ended
            } else {

                //update nmcq text / image
                if($post['answer_type_nmcq'] == 'text'){
                    // if(isset($post['open_ended_answer'])){
                    //     echo 'detected'; echo $post['open_ended_answer']; echo $this->applyMathJaxFormat($post['open_ended_answer']); die();
                    // }
                    $nmcq_answer_text = $this->applyMathJaxFormat($post['open_ended_answer']);
                    $nmcq_answer_type = 'text';

                    $this->db->query("UPDATE sj_answers SET answer_text = ?, answer_type = ? WHERE answer_id = ?", array(
                        $nmcq_answer_text,
                        $nmcq_answer_type,
                        $query->row()->answer_id
                    ));
                } else {
                    if(is_uploaded_file($_FILES['nmcq_answers_image']['tmp_name'][0])){
                        $nmcq_answer_text = $this->do_upload('answer', 'nmcq_answers_image', 0);
                        $nmcq_answer_type = 'image';

                        $this->db->query("UPDATE sj_answers SET answer_text = ?, answer_type = ? WHERE answer_id = ?", array(
                            $nmcq_answer_text,
                            $nmcq_answer_type,
                            $query->row()->answer_id
                        ));
                    }
                }

                $this->db->query("UPDATE `sj_correct_answer` SET `answer_id` = ? WHERE `question_id` = ?", array(
                    $query->row()->answer_id,
                    $question_id
                ));
                

                // Update working fields

                $working_query = $this->db->query("SELECT * FROM sj_question_content WHERE `content_section` = 'working' AND question_id = ?", array($question_id));

                $nmcq_answer_query = $this->db->query("SELECT answer_id FROM sj_answers WHERE question_id = ?", array($question_id));

                //delete remove workind field
                foreach ($working_query->result() as $working_row) {

                    $delete_index = $working_row->content_order - 1;
                    $delete_working_index = 'working_field_'.$delete_index; 

                    if(!isset($_FILES[$delete_working_index]['name']) && !isset($post[$delete_working_index])){
                        $sql = "DELETE FROM `sj_question_content` WHERE `content_id` = ". $working_row->content_id;
                        $query = $this->db->query($sql);
                    }                    
                }

                // insert answer and correct answers if empty row in answer table
                $count = 0;
                $content_order = 1;

                while($count <6){

                    $working_index = 'working_field_'.$count;                 

                    //is an image
                    if(isset($_FILES[$working_index]['name'])){
                        if($_FILES[$working_index]['size'] != 0) {

                            $config = array();
                            $config['upload_path'] = 'img/workingImage';
                            $config['allowed_types'] = '*';
                            $config['max_size']    = '300';
                            $config['overwrite'] = 'true';
                            $config['file_name'] = $post['year'] . '-' . $content_order . '-' . $post['level_id'] . '-' . $question_id;
                            $this->load->library('upload');
                            $this->upload->initialize($config);
    
                            $allowed_types = array('jpg', 'png', 'gif', 'jpeg');
                            $file_name = $_FILES[$working_index]['name'];
                            $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                            
                            if (in_array($ext, $allowed_types)) {
                                if (!$this->upload->do_upload($working_index)){
                                    $data['message'] = $this->upload->display_errors();
                                    $data['message_status'] = 'alert-danger';
                                } else {
                                    
                                    $upload_data = $this->upload->data();
                                    if ($this->update_working_image($upload_data['file_name'], $question_id, $content_order)) {
                                        $data['message'] = "Question created successfully and image uploaded successfully";
                                        $data['message_status'] = 'alert-success';
                                        // $data['content'] = 'administrator/administrator_question';
                                    } else {
                                        $data['message'] = "Error in uploading question image. Please try again later or contact administrator at admin@smartjen.sg";
                                        $data['message_status'] = 'alert-danger';
                                    }
                                }
                            } else {
                                $data['message'] = 'Please upload only jpg, png, gif or jpeg file';
                                $data['message_status'] = 'alert-danger';
                            }
                        }
                    }                    

                    if($count == 0){

                        //is a text
                        if(isset($post[$working_index])) {
                            if($nmcq_answer_query->num_rows() == 1){
                                $this->db->query("UPDATE sj_answers SET working_type = 'text', working_text = ? WHERE answer_id = ?", array(
                                    trim($this->applyMathJaxFormat($post[$working_index])),
                                    $nmcq_answer_query->row()->answer_id
                                ));
                            }
                        }

                    } else {
                        if(isset($post[$working_index])) {
                            if($working_query->num_rows() == 0){
                                $insert_array = array(
                                    'question_id'    => $question_id,
                                    'content_section'     => 'working',
                                    'content_type'         => 'text',
                                    'content_order'         => $content_order,
                                    'content_name'        => trim($this->applyMathJaxFormat($post[$working_index]))
                                );
                                $this->db->insert('sj_question_content', $insert_array);

                                $sql = "UPDATE `sj_questions` SET `question_content` = '1' WHERE `question_id` =" . $question_id;
                                $query = $this->db->query($sql);

                            } else{
                                //update existing workings
                                if($working_query->num_rows() >= $count){
                                
                                    $this->db->query("UPDATE sj_question_content SET content_type = 'text', content_name = ? WHERE content_order = ? and question_id = ?", array(
                                        trim($this->applyMathJaxFormat($post[$working_index])),
                                        $content_order,
                                        $question_id
                                    ));

                                    $sql = "UPDATE `sj_questions` SET `question_content` = '1' WHERE `question_id` =" . $question_id;
                                    $query = $this->db->query($sql);

                                } else {

                                    //insert new workings
                                    $insert_array = array(
                                        'question_id'    => $question_id,
                                        'content_section'     => 'working',
                                        'content_type'         => 'text',
                                        'content_order'         => $content_order,
                                        'content_name'        => trim($this->applyMathJaxFormat($post[$working_index]))
                                    );
                                    $this->db->insert('sj_question_content', $insert_array);

                                    $sql = "UPDATE `sj_questions` SET `question_content` = '1' WHERE `question_id` =" . $question_id;
                                    $query = $this->db->query($sql);
                                }
                            }

                        }
                    }
                    
                    $count++;
                    $content_order++;
                }
                
            }

        }

        // delete existing tags
        $this->db->query("DELETE FROM sj_question_tag WHERE question_id = ?", array($question_id));

        // insert question tags
        $this->db->insert('sj_question_tag', array(
            'question_id' => $question_id,
            'tags'        => $post['tagsinput']
        ));

        $this->db->trans_complete();
        return array(
            'status'    => $this->db->trans_status()
        );

    }

    public function can_edit_question($question_id) {
        $question = $this->get_question_from_id($question_id);
        return $this->session->userdata('user_id') == $question->source;
    }



    public function get_random_question_by_level($level) {
        $this->load->helper('shuffleassoc');
        $sql = "SELECT `question_id`, `question_text`, `graphical`, `topic_id` 
                FROM `sj_questions` 
                WHERE `level_id` = ? AND `disabled` = 0 AND `question_type_id` = 1
                ORDER BY RAND() 
                LIMIT 1";

        $query = $this->db->query($sql, array($level));
        $question = $query->row();
        $questionId = $question->question_id;
        $ans = array();
        $answerOptions = $this->get_answer_option_from_question_id($questionId);
        $correctAnswer = $this->get_answer_text_from_answer_id($this->get_correct_answer_from_question_id($questionId));

        //shuffling the ordering of answer options
        $answerOptions = shuffleAssoc($answerOptions);
        $ans['answerOption'] = $answerOptions;
        $ans['correctAnswer'] = $correctAnswer;
        $i = 1;

        foreach ($answerOptions as $answer) {
            if (strcmp($answer->answer_text, $correctAnswer) == 0) {
                $ans['correctAnswerOptionNum'] = $i;
                break;
            }
            $i++;
        }

        $question->answer = $ans;
        return $question;

    }
    
    public function sub_question($ref_id) {
        if(is_array($ref_id)){
            $ref_id = implode(",", $ref_id);
        }
        $sql = "SELECT * FROM `sj_questions` WHERE `reference_id` IN (" . $ref_id . ")";
        $query = $this->db->query($sql);
        $result = $query->result();
        if(!isset($result[1])){
            return null;
        }
        return $result[1]->sub_question;
    }
    
    
    public function list_sub_question($parent_id, $sub_array = NULL) {
        if($sub_array){
            $sql_subQue = "
                SELECT * FROM `sj_questions` 
                LEFT JOIN `sj_branch` ON `sj_questions`.`branch_name` = `sj_branch`.`branch_name` 
                WHERE `reference_id` = " . $this->db->escape($parent_id) . " 
                AND `sub_question` != 'A' 
                AND `question_id` NOT IN (".implode(",",$this->db->escape($sub_array)).")
            ";
        }else{
            $sql_subQue = "
                SELECT * FROM `sj_questions` 
                LEFT JOIN `sj_branch` ON `sj_questions`.`branch_name` = `sj_branch`.`branch_name`
                WHERE `reference_id` = " . $this->db->escape($parent_id) . "
                AND `sub_question` != 'A'
            ";
        }
        $query_subQue = $this->db->query($sql_subQue);
        return $query_subQue->result();
    }
  
    public function insert_student_log($data){
        
        if(isset($data['answer']) && !empty($data['answer'])){
            $answer = $data['answer'];
        }else {
            $answer = 'NULL';
        }
    
        
        if($answer == 'NULL'){
            
            $data_ar = array(
                'id' => uniqid(),
                'question_id' => $data['question_id'],
                'quiz_id' => $data['quiz_id'],
                'student_id' => $data['user_id'],
                'time_taken' => '00:00:00',
                'answer_switch' => isset($data['answer']) && !empty($data['answer'])?$data['answer']:'NULL',
                'created_by' => $data['tutor_id'],
                'timestamp' => date('Y-m-d H:i:s'),
                'branch_tag' => BRANCH_TAG
            );
            
            $insert_key = array();
            $insert_data = array();
            foreach($data_ar as $key=>$value){
                $insert_key[] = $key;
                $insert_data[] = $this->db->escape($value); 
            }
            
            $sql = "INSERT INTO `sj_student_log` (".implode(',', $insert_key).") VALUES (".implode(',', $insert_data).")";
            
            $query = $this->db->query($sql);
            
        } else {
            
            $select = "SELECT `id`, `timestamp` FROM `sj_student_log` ORDER BY `timestamp` DESC LIMIT 1";
            
            $select_query = $this->db->query($select);
            
            $endTime = $select_query->row()->timestamp;
            
            $rowId = $select_query->row()->id;
            
            $startTime = date('Y-m-d H:i:s');
            
            $dteStart = new DateTime($startTime);
            
            $dteEnd   = new DateTime($endTime);
            
            $time_taken = date_diff($dteStart, $dteEnd);
            
            $hour = str_pad($time_taken->h, 2, '0', STR_PAD_LEFT);
            
            $minute = str_pad($time_taken->i, 2, '0', STR_PAD_LEFT);
            
            $second = str_pad($time_taken->s, 2, '0', STR_PAD_LEFT);
            
            $data_ar = array(
                'id' => uniqid(),
                'question_id' => $data['question_id'],
                'quiz_id' => $data['quiz_id'],
                'student_id' => $data['user_id'],
                'time_taken' => $hour.':'.$minute.':'.$second,
                'answer_switch' => isset($data['answer']) && !empty($data['answer'])?$data['answer']:'NULL',
                'created_by' => $data['tutor_id'],
                'timestamp' => date('Y-m-d H:i:s'),
                'branch_tag' => BRANCH_TAG
            );
            
            $insert_key = array();
            $insert_data = array();
            foreach($data_ar as $key=>$value){
                $insert_key[] = $key;
                $insert_data[] = $this->db->escape($value); 
            }
            
            $sql = "INSERT INTO `sj_student_log` (".implode(',', $insert_key).") VALUES (".implode(',', $insert_data).")";
            
            $query = $this->db->query($sql);
            
        }
        
        if($query){
            return TRUE;
        }else {
            return FALSE;
        }
        
    }
    
    
    /**Secondary Math Prototype**/
    
    public function get_sc_substrand_list($strand_id = NULL)
    {
        if (isset($strand_id) && empty($strand_id) === false) {
            $substrand_sql = "SELECT * FROM `sj_strands` WHERE `strand_id` = '".$strand_id."' `type` = 'Primary_Science'";
            $substrand_sql_query = $this->db->query($substrand_sql);
        } else {
            $substrand_sql = "SELECT * FROM `sj_strands` WHERE `type` = 'Primary_Science'";
            $substrand_sql_query = $this->db->query($substrand_sql);
        }
        $substrand_array = array();
        foreach ($substrand_sql_query->result() as $row) {
            $substrand_array[] = $row;
        }
        return $substrand_array;
    }
    
    public function get_sc_level_list()
    {
        $level_sql = "SELECT * FROM `sj_levels`";
        $level_sql_query = $this->db->query($level_sql);
        $level_array = array();
        foreach ($level_sql_query->result() as $row) {
            $level_array[] = $row;
        }
        return $level_array;
    }
    
    public function get_sc_topic_list($strand_id = NULL)
    {
        if (isset($strand_id) && empty($strand_id) === false) {
            $substrand_sql = "SELECT * FROM `sj_substrands` WHERE `strand_id` = ? AND `type` = 'Primary_Science'";
            $substrand_sql_query = $this->db->query($substrand_sql, array($strand_id));
        } else {
            $substrand_sql = "SELECT * FROM `sj_substrands` WHERE `type` = 'Primary_Science'";
            $substrand_sql_query = $this->db->query($substrand_sql);
        }
        $substrand_array = array();
        foreach ($substrand_sql_query->result() as $row) {
            $substrand_array[] = $row;
        }
        return $substrand_array;
    }
    
    public function get_sc_question_list($post, $userId)
    {
        $questions = array();
        $numGeneratedQuestion = 0;
        $tempTable = $this->get_sc_questions_id_from_requirement($post, $userId);
        $questionArray = $this->get_random_question($tempTable, $post['gen_num_of_question']);
        $questionArrayID = array();
        foreach ($questionArray AS $question) {
            $questionArrayID[] = $question->question_id;   
        }
        $sessionArray = array(
            'questionArray' => $questionArrayID
        );
        $this->session->set_userdata($sessionArray); //save the question ID in session for easier ajax regenerating
        return $questionArray;
    }
    
    public function get_sc_questions_id_from_requirement($post, $userId){
        if($userId == 0){
            $level = $post["gen_level"];
            $substrand = $post['gen_substrand'];
            $topic = $post["gen_topic"];
            $percDifficulty = $post["gen_difficulties"];
            $sql = "SELECT * FROM `sj_questions` LEFT JOIN `sj_branch` ON `sj_branch`.`branch_name` = `sj_questions`.`branch_name` WHERE `level_id` = ?";
            $sql_bind_array = array();
            $sql_bind_array[] = $level;
            $whereClauseArray = array();
            for ($i = 0, $count = count($substrand); $i < $count; $i++) {
            $whereClause = "";
            if (strcmp($topic[$i], "all") !== 0) {
            $whereClause .= " `topic_id` = ?";
            $sql_bind_array[] = $topic[$i];
            } else {
            $topic_list = $this->get_topic_list($substrand[$i]);
            $topic_id = array();
            foreach ($topic_list as $topic_item) {
            $topic_id[] = $topic_item->id;
            }
            $whereClause .= " `topic_id` IN (68)";
            }
            $whereClause = "( " . $whereClause . " )";
            $whereClauseArray[] = $whereClause;
            }
            $whereClause = "(" . implode(" OR ", $whereClauseArray) . ")";
            $sql .= " AND `disabled` = 0 AND `sub_question` = 'A' AND " . $whereClause . " LIMIT 10";
            $sql_result = $this->db->query($sql, $sql_bind_array);
            $tempTableId = array();
            foreach ($sql_result->result() AS $row) {
            $tempTableId[] = $row->question_id;
            }
            $sessionArray = array(
            'tempTableId' => $tempTableId
            );
            $this->session->set_userdata($sessionArray);
            
            return $sql_result;
        } else {
            $level = $post["gen_level"];
            $substrand = $post['gen_substrand'];
            $topic = $post["gen_topic"];
            $percDifficulty = $post["gen_difficulties"];
            
            $sql = "SELECT * FROM `sj_questions` LEFT JOIN `sj_branch` ON `sj_branch`.`branch_name` = `sj_questions`.`branch_name` WHERE `level_id` = ?";
            $sql_bind_array = array();
            $sql_bind_array[] = $level;
            
            $whereClauseArray = array();
            for ($i = 0, $count = count($substrand); $i < $count; $i++) {
            $whereClause = "";
            
            if (strcmp($topic[$i], "all") !== 0) {
            $whereClause .= " `topic_id` = ?";
            $sql_bind_array[] = $topic[$i];
            } else {
            $topic_list = $this->get_topic_list($substrand[$i]);
            $topic_id = array();
            foreach ($topic_list as $topic_item) {
            $topic_id[] = $topic_item->id;
            }
            $whereClause .= " `topic_id` IN (68)";
            }
            
            $whereClause = "( " . $whereClause . " )";
            $whereClauseArray[] = $whereClause;
            }
            
            $whereClause = "(" . implode(" OR ", $whereClauseArray) . ")";
            
            $sql .= " AND `disabled` = 0 AND `sub_question` = 'A' AND " . $whereClause . " AND `type` = 'Primary_Science'";
            
            $sql_result = $this->db->query($sql, $sql_bind_array);
            
            $tempTableId = array();
            foreach ($sql_result->result() AS $row) {
            $tempTableId[] = $row->question_id;
            }
            $sessionArray = array(
            'tempTableId' => $tempTableId
            );
            $this->session->set_userdata($sessionArray);
            
            return $sql_result;
        }
    }

    public function get_sc_strand_structure()
    {
        $strands = array();
        $strand_sql = "SELECT * FROM sj_strands WHERE `type` = 'Primary_Science' ORDER BY `id` ASC";
        $strand_sql_query = $this->db->query($strand_sql);
        foreach ($strand_sql_query->result() as $row) {
            $strand_object = array();
            $strand_name = $row->name;
            $strand_id = $row->id;
            $strand_object['name'] = $strand_name;
            $substrand_sql = "SELECT `id`, `name` FROM `sj_substrands` WHERE `strand_id` = ? AND `type` = 'Primary_Science'";
            $substrand_sql_query = $this->db->query($substrand_sql, array($strand_id));
            $strand_object['substrand'] = array();
            foreach ($substrand_sql_query->result() as $substrand_row) {
                $substrand_object = array();
                $substrand_name = $substrand_row->name;
                $substrand_id = $substrand_row->id;
                $substrand_object['name'] = $substrand_name;
                $categories_sql = "SELECT `id`, `name` FROM `sj_categories` WHERE `substrand_id` = ? AND `type` = 'Primary_Science'";
                $categories_sql_query = $this->db->query($categories_sql, array($substrand_id));
                $substrand_object['category'] = array();
                foreach ($categories_sql_query->result() as $category_row) {
                    $category_object = array();
                    $category_name = $category_row->name;
                    $category_object['name'] = $category_name;
                    $substrand_object['category'][] = $category_object;
                }
                $strand_object['substrand'][] = $substrand_object;
            }
            $strands[] = $strand_object;
        }
        return $strands;
    }
    
    /***Secondary Math Prototype***/
    
    function get_total_row($sql = "",$sql_bind_array = array()){
        if(!empty($sql)){
            if(strpos($sql, "LIMIT") !== false){
                $pos = strpos($sql,"LIMIT");
                $sql = substr($sql,0,$pos);
            }
            
            
            $sql_result = $this->db->query($sql, $sql_bind_array);
            return $sql_result->num_rows();
        }
    }
    
    public function upload_student_photo($data){
        //update question
        if(isset($data['img_path']) && !empty($data['img_path'])){
            $img_path = $data['img_path'];
        }else {
            $img_path = 'NULL';
        }
        //to determine which area to store
    }

    public function get_strategy_id($substrategy_id = NULL)
    {
        $strategy_sql = "SELECT * FROM `sj_substrategy` WHERE `id` =".$this->db->escape($substrategy_id);
        $strategy_query = $this->db->query($strategy_sql);

        $result = $strategy_query->row();

        if($strategy_query->num_rows() > 0) {
            return $result->strategy_id;
        } else {
            return 0;
        }
    }


    function get_worksheetName_from_admin($tutorId = NULL) {

        // get the admin_worksheetId from sj_admin_quiz in order to get the admin_worksheet_name
        $sql = "SELECT `admin_worksheetId` FROM `sj_admin_quiz` WHERE `admin_assignedTo` =  " . $this->db->escape($tutorId) . " AND `admin_branch_code` = ".BRANCH_ID;

        $query = $this->db->query($sql);

        if($query->num_rows() > 0) {

            // multiple of admin_worksheet_id will be fetch from sj_admin_quiz
            foreach($query->result() as $key=>$value) {
                
                $sql = "SELECT * FROM `sj_admin_worksheets` WHERE `admin_worksheet_id` = " . $this->db->escape($value->admin_worksheetId);

                $query = $this->db->query($sql);

                $arrValue= $query->result();

            }

            return $arrValue;

        } else {

            return false;
        }
    }

    public function get_question_id_from_counter($question_counter, $subject_id, $branch_id)
    {     
        if($branch_id == 1){
            $branch_name = 'SmartJen';
        } else {
            $branch_name = BRANCH_ID;
        }

        $sql = "SELECT * FROM `sj_questions` LEFT JOIN `sj_branch` ON `sj_branch`.`branch_name` = `sj_questions`.`branch_name` WHERE `sj_questions`.`branch_name` = ". $this->db->escape($branch_name) ." AND `question_counter` = " . $this->db->escape($question_counter) ." AND `subject_type` = ".$this->db->escape($subject_id);
        $sqlQuery = $this->db->query($sql);
        $row = $sqlQuery->row();

        if($sqlQuery->num_rows() > 0) {
            return $row->question_id;
        }        
    }

    public function get_working_content_from_id($id){

        $sql = "SELECT * FROM `sj_question_content` WHERE `content_section` = 'working' AND `question_id`='$id' ORDER BY `content_order` ";

        $sqlQuery = $this->db->query($sql)->result();

        return $sqlQuery;

    }

    public function get_question_detail_from_counter($question_counter)
    {
        if(is_array($question_counter)){
            $question_counter = implode(",", $question_counter);
        }
        $sql = "SELECT * FROM `sj_questions` LEFT JOIN `sj_branch` ON `sj_branch`.`branch_name` = `sj_questions`.`branch_name` WHERE `sj_questions`.`branch_name` = 'Prototype' AND `sj_questions`.`question_level` = '1' AND `question_counter` IN (" . $this->db->escape($question_counter) . ")";

        $sqlQuery = $this->db->query($sql);

        return $sqlQuery->row();

    }

    public function get_solution_answer_text_from_correct_id($answerId)

    {
        $this->db->where('answer_id', $answerId);
        $query = $this->db->get('sj_answers');
        $result = $query->row();
        return $result->working_text;
    }

    public function get_solution_answer_type_from_correct_id($answerId)

    {
        $this->db->where('answer_id', $answerId);
        $query = $this->db->get('sj_answers');
        $result = $query->row();
        return $result->working_type;
    }
    
    public function get_working_contents_from_question_id($questionId)
    {

        $this->db->where('question_id', $questionId);

        $this->db->where('content_section', 'working');

        $this->db->order_by('content_order');

        $query = $this->db->get('sj_question_content');

        $workingContents = array();

        foreach ($query->result() AS $row) {

            $workingContents[] = $row;

        }

        return $workingContents;

    }

    public function get_question_contents_from_question_id($questionId)
    {

        $this->db->where('question_id', $questionId);

        $this->db->where('content_section', 'question');

        $this->db->order_by('content_order');

        $query = $this->db->get('sj_question_content');

        $questionContents = array();

        foreach ($query->result() AS $row) {

            $questionContents[] = $row;

        }

        return $questionContents;

    }


    public function get_question_header_from_question_id($questionId, $headerType){

        $row = $this->db->query("SELECT reference_id FROM sj_questions WHERE question_id ='$questionId'")->row();

        $this->db->where('question_id', $row->reference_id);

        $this->db->where('header_type', $headerType);

        $this->db->order_by('header_order');

        $query = $this->db->get('sj_question_header');

        $questionHeaders = array();

        foreach ($query->result() AS $row) {
            
            $questionHeaders[] = $row;

        }

        return $questionHeaders;

    }


    public function get_new_question_counter($subject_id,$branch_name)
    {
        $sql = "SELECT `question_counter` FROM `sj_questions` WHERE `subject_type` = ". $this->db->escape($subject_id) ." AND `branch_name` = ". $this->db->escape($branch_name) ." ORDER BY `question_id` DESC LIMIT 1";

        $sqlQuery = $this->db->query($sql);

        $result = $sqlQuery->row();

        return $result->question_counter + 1;

    }

    function regenerateQuestionTID($id, $questionType){              
        $this->db->select('a.*, b.level_id, b.tid_topic_id, b.ability, b.difficulty,b.tid_level,b.question_level');
        $this->db->from('sj_generate_questions a');
        $this->db->join('sj_questions b', 'a.question_id=b.question_id');
        $this->db->where('a.id', $id);
        $row = $this->db->get()->row_array();            
        $questionID = $row['question_id'];
        $levelID = $row['tid_level'];
        $level_name = $row['tid_level'];
        $topicID = $row['tid_topic_id'];           
        $difficulty = $row['difficulty'];           
        $ability = $row['ability'];
        $reqOperator = $row['operator_req'];
        $reqTopic = $row['topic_req'];
        $reqDifficulty = $row['difficulty_req'];
        $genQueBank = $row['question_level'];
        if(strlen($row['difficulty_req']) > 1){
            $reqDifficulty = str_replace(',', '', $row['difficulty_req']);
            $reqDifficulty = str_replace($difficulty, '', $reqDifficulty);
            $reqDifficulty = implode(',', str_split($reqDifficulty));
        }            
        $date_created = date('Y-m-d H:i:s');
        $list_question = $this->db->get_where('sj_generate_questions', ['requirement_id' => $this->session->userdata('requirementId')])->result_array();
        $question_id = array();
        foreach($list_question as $row) {
            $question_id[] = $row['question_id'];
        }
        $sql  = "SELECT question_id FROM sj_questions WHERE tid_level='$levelID' AND `disabled`=0 AND sub_question='A' ";            
        $sql .= "AND question_id NOT IN (".implode(",", $question_id).") ";

        if($reqTopic !== 'all' && $reqTopic !== ''){
            $sql .= "AND tid_topic_id='$reqTopic' ";                            
        }else{
            $sql .= "AND tid_topic_id<>'' ";                                              
        }
        $sql .= " AND `is_tid` = 1 AND `branch_name` = '" .BRANCH_TITLE . "' ";
            

        if($questionType == 1){
            $sql .= "AND question_type_id IN (1,4) ";
        }else if($questionType == 2){
            $sql .= "AND question_type_id IN (1,2) ";
        }
        if($ability != 'all') {
            $sql .= "AND (`ability` = '$ability' ";
        } else $sql .= "AND (`ability` >= 0 ";
        $operator_ab = ($reqOperator==1 || $reqOperator=='') ? "AND" : "OR";
        $sql_difficulty = $operator_ab." difficulty IN ($reqDifficulty)) ";
        $sql_limit = " ORDER BY RAND() LIMIT 1";
        $row = $this->db->query($sql.$sql_difficulty.$sql_limit)->row();
        $quest_id = '';
        if(isset($row)){
            $quest_id = $row->question_id;
            $data = [
                    'question_id' => $quest_id
                ];
            $this->db->where('id', $id);
            $this->db->update('sj_generate_questions', $data);            
        }else{
            $sql_difficulty = $operator_ab." difficulty IN ($difficulty) )";
            if(isset($row)){
                $quest_id = $row->question_id;
                $data = [
                        'question_id' => $quest_id
                    ];
                $this->db->where('id', $id);
                $this->db->update('sj_generate_questions', $data);            
            }
        }
        
        return $quest_id;
    }

    function getDetailQuestion($question_id){
        $sql  = "SELECT DISTINCT(a.question_id), a.question_text, a.sub_question, a.level_id, a.topic_id, a.strategy_id, a.graphical, a.difficulty, ";
        // $sql .= "ANY_VALUE(b.branch_image_url) as branch_image_url, ANY_VALUE(d.name) as substrand_name, ANY_VALUE(c.name) as category_name, ANY_VALUE(e.name) as strategy_name FROM sj_questions a ";
        $sql .= "b.branch_image_url as branch_image_url, d.name as substrand_name, c.name as category_name, e.name as strategy_name FROM sj_questions a ";
        $sql .= "JOIN sj_branch b ON a.branch_name=b.branch_name AND a.question_id='$question_id' ";
        $sql .= "JOIN sj_categories c ON a.topic_id=c.id ";
        $sql .= "JOIN sj_substrands d ON c.substrand_id=d.id ";
        $sql .= "LEFT JOIN sj_strategy e ON a.strategy_id=e.id ";
        $row = $this->db->query($sql)->row();
        if(isset($row)){
            $result['question_id'] = $row->question_id;
            $result['question_text'] = $row->question_text;
            $result['sub_question'] = $row->sub_question;
            $result['level_id'] = $row->level_id;
            $result['topic_id'] = $row->topic_id;
            $result['strategy_id'] = $row->strategy_id;
            $result['graphical'] = $row->graphical;
            $result['difficulty'] = $row->difficulty;
            if($row->strategy_id == 0){
                $result['strategy_name'] = '';
            }else{
                $result['strategy_name'] = $row->strategy_name;
            }                
            $result['category_name'] = $row->category_name;
            $result['substrand_name'] = $row->substrand_name;
            $result['branch_image_url'] = $row->branch_image_url;
        }

        return $result;
    }

    function getAnswers($question_id){
        $sql  = "SELECT a.*, b.answer_id as correct_answer FROM sj_answers a, sj_correct_answer b WHERE a.question_id=b.question_id ";
        $sql .= "AND a.question_id='$question_id' ORDER BY RAND()";
        $query = $this->db->query($sql);
        foreach($query->result() as $row){
            $result[] = $row;
        }

        return $result;
    }
    
    function getSubquestion($quest_id){
        $sql = "SELECT question_id FROM sj_questions WHERE reference_id='$quest_id'";
        $result = $this->db->query($sql)->result();

        return $result;
    }
}