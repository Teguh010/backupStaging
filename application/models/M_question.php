<?php

    class M_question extends CI_Model {   
        
        private $user_id;
        private $user_role;

        function __construct(){
            parent::__construct();
            $this->user_id = $this->session->userdata('user_id');
            $this->user_role = $this->session->userdata('user_role');            
        }

        function getInformation($id){
            $sql = "SELECT a.difficulty, c.name AS topic_name, d.name AS substrand_name, e.name as strategy_name FROM sj_questions a             
                    JOIN sj_categories c ON a.topic_id = c.id 
                    JOIN sj_substrands d ON c.substrand_id = d.id 
                    LEFT JOIN sj_strategy e ON a.strategy_id = e.id  
                    WHERE a.question_id='$id'
            ";
            $result = $this->db->query($sql)->row_array();
            return $result;
        }        

        public function read_question($start){
            $getData = $this->input->get();
            $filter         = $getData['filter'];
            $search         = $getData['search'];
            // $subject_name   = $getData['subject_name'];
            $subject_type   = $getData['subject_type'];
            // $level_id       = $getData['level_id'];
            $topic_id       = $getData['topic_id'];
            $strategy_id = $getData['strategy_id'];
            $substrategy_id = $getData['substrategy_id'];
            if(isset($getData['marks'])){
                $marks          = $getData['marks'];
            }
            // $has_subquestion= $getData['has_subquestion'];
            // $question_type  = $getData['question_type'];
            // $sql_subquestion = "";
            // if($has_subquestion == 1){
            //     $sql_subquestion = ", COUNT(a.reference_id) total_subquestion ";
            //     $subquestion = "";
            // }else{
            //     $subquestion = " AND a.sub_question='A' ";
            // }

            $sql_filter = '';

            $sql = "SELECT a.question_id, a.question_text, a.level_id, a.subject_type, a.question_type_id, a.difficulty, a.graphical, 
                    a.question_content, a.content_type, a.topic_id, a.substrategy_id, a.strategy_id, b.branch_image_url  
                    FROM sj_questions a 
                    JOIN sj_branch b ON a.branch_name = b.branch_name                     
                    WHERE a.disabled=0 AND a.sub_question='A' 
            ";

            if($filter == 1){
                // if($subject_name !== '' && $subject_type == '' && $level_id == ''){
                //     $query = "SELECT * FROM sj_subject WHERE name LIKE '%$subject_name%'";
                //     $query = $this->db->query($query)->result();
                //     $subject_list = array();
                //     foreach($query as $row) {
                //         $subject_list[] = $row->id;
                //     }
                //     $sql .= " AND a.subject_type IN (".implode(",", $subject_list).") ";
                // }
                
                if($subject_type !== ''){
                    $sql_filter .= " AND a.subject_type = '$subject_type' ";
                }
    
                // if($level_id !== ''){
                //     $sql .= " AND a.level_id = '$level_id' ";
                // }
    
                if($topic_id !== ''){
                    $sql_filter .= " AND a.topic_id = '$topic_id' ";
                }
    
                if($strategy_id !== ''){
                    $sql_filter .= " AND a.strategy_id = '$strategy_id' ";
                }

                if($substrategy_id !== ''){
                    $sql_filter .= " AND a.substrategy_id = '$substrategy_id' ";
                }
                
                if(isset($getData['marks'])){                
                    $sql .= " AND a.difficulty IN (".implode(",", $marks).") ";                
                }            
    
                // if($question_type !== ''){
                //     $sql .= " AND a.question_type_id = '$question_type' ";
                // }
    
                // if($has_subquestion == 1){
                //     $sql .= " GROUP BY a.reference_id HAVING COUNT(a.reference_id) > 1 ";
                // }
            }

            if($search !== ''){ 
                $sql  = "SELECT DISTINCT(a.question_id), a.question_text, a.level_id, a.subject_type, a.question_type_id, a.difficulty, 
                        a.graphical, a.question_content, a.content_type, a.topic_id, a.substrategy_id, a.strategy_id, b.branch_image_url 
                        FROM sj_questions a 
                        JOIN sj_branch b ON a.branch_name = b.branch_name 
                        JOIN sj_categories c ON a.topic_id=c.id 
                        JOIN sj_question_tag d ON a.question_id=d.question_id 
                        JOIN sj_strategy e ON a.strategy_id=e.id 
                        JOIN sj_substrategy f ON a.substrategy_id=f.id                       
                        WHERE a.disabled=0 AND a.sub_question='A' $sql_filter                        
                        AND concat('.', a.question_text, '.', c.name, '.', d.tags, '.', e.name, '.', f.name, '.') LIKE '%$search%' ";                
            }else{
                $sql = $sql.$sql_filter;
            }
            

            $result['total_rows'] = $this->db->query($sql." LIMIT 5000")->num_rows();

            if($result['total_rows'] < 1){
                $sql  = "SELECT DISTINCT(a.question_id), a.question_text, a.level_id, a.subject_type, a.question_type_id, a.difficulty, 
                a.graphical, a.question_content, a.content_type, a.topic_id, a.substrategy_id, a.strategy_id, b.branch_image_url 
                FROM sj_questions a 
                JOIN sj_branch b ON a.branch_name = b.branch_name 
                JOIN sj_categories c ON a.topic_id=c.id 
                JOIN sj_question_tag d ON a.question_id=d.question_id                       
                WHERE a.disabled=0 AND a.sub_question='A' $sql_filter                        
                AND concat('.', a.question_text, '.', c.name, '.', d.tags, '.') LIKE '%$search%' ";

                $result['total_rows'] = $this->db->query($sql." LIMIT 5000")->num_rows();
            }

            $sql .= "ORDER BY a.question_id ASC LIMIT 10 OFFSET $start";
            $result['data'] = $this->db->query($sql)->result();
                
            return $result;

        }


        public function read_question_id($question_id){
            
            $sql = "SELECT a.question_id, a.question_text, a.level_id, a.subject_type, a.question_type_id, a.difficulty, a.graphical, 
                    a.question_content, a.content_type, a.topic_id, a.substrategy_id, a.strategy_id, b.branch_image_url  
                    FROM sj_questions a 
                    JOIN sj_branch b ON a.branch_name = b.branch_name                     
                    WHERE a.disabled=0 AND a.question_id = '$question_id' 
            ";

            $result['data'] = $this->db->query($sql)->result();
                
            return $result;

        }


        function get_subject(){
            $sql = "SELECT * FROM sj_subject WHERE id NOT IN (4,6)";

            $result = $this->db->query($sql)->result();
            
            return $result;
        }


        function get_level_by_id($level_id){
            $sql = "SELECT level_name FROM sj_levels WHERE level_id='$level_id'";

            $result = $this->db->query($sql)->row();
            
            return $result->level_name;
        }


        function get_subject_by_id($subject_id){
            $sql = "SELECT name FROM sj_subject WHERE id='$subject_id'";

            $result = $this->db->query($sql)->row();
            
            return $result->name;
        }


        function get_reference_by_id($question_id){
            $sql = "SELECT COUNT(reference_id) AS total FROM sj_questions WHERE reference_id='$question_id'";

            $result = $this->db->query($sql)->row();
            
            return $result->total;
        }


        public function get_level_by_subject_name($subject_name){
            $sql = "SELECT id FROM sj_subject WHERE name LIKE '%$subject_name%'";
    
            $result = $this->db->query($sql)->result();
            $subject_id = array();
            foreach($result as $row){
                $subject_id[] = $row->id;
            }
            $subject_id = implode(",", $subject_id);
    
            $sql = "SELECT a.* FROM sj_levels a WHERE a.subject_type IN ($subject_id) AND a.level_id NOT IN ('7','8')";
    
            $result = $this->db->query($sql)->result();
    
            return $result;
    
        }


        function get_topic_by_id($topic_id){

            $data['topic_name'] = '';
            $data['substrand_name'] = '';

            $sql = "SELECT substrand_id, name FROM sj_categories WHERE id='$topic_id'";

            $result = $this->db->query($sql)->row();

            if(isset($result)){
                $data['topic_name'] = $result->name;

                $sql = "SELECT name FROM sj_substrands WHERE id='$result->substrand_id'";

                $result = $this->db->query($sql)->row();

                if(isset($result)){
                    $data['substrand_name'] = $result->name;
                }
            }
            
            return $data;
        }


        function get_substrand_by_id($subtrand_id){
            $sql = "SELECT name FROM sj_substrands WHERE id='$subtrand_id'";

            $result = $this->db->query($sql)->row();
            
            if(isset($result)){
                return $result->name;
            }else{
                return '';
            }
        }


        function get_strategy_by_id($strategy_id){
            
            $sql = "SELECT name FROM sj_strategy WHERE id='$strategy_id'";

            $result = $this->db->query($sql)->row();
            
            if(isset($result)){
                return $result->name;
            }else{
                return '';
            }
            
        }


        public function get_answer_type_by_question_type($question_type){
            
            if($question_type == 1 || $question_type == 4 || $question_type == 8){
                $sql = "SELECT * FROM sj_answer_type WHERE answer_type_id IN (2,3,4,5)";
            }else if($question_type == 2){
                $sql = "SELECT * FROM sj_answer_type WHERE answer_type_id IN (2,3,4,5,6,7,8)";
            }else if($question_type == 5 || $question_type == 7){
                $sql = "SELECT * FROM sj_answer_type WHERE answer_type_id IN (4)";
            }else if($question_type == 3 || $question_type == 6){
                $sql = "SELECT * FROM sj_answer_type WHERE answer_type_id IN (1)";
            }

            $result = $this->db->query($sql)->result();

            return $result;

        }


        public function get_strategy_id($substrategy_id = NULL){
            $strategy_sql = "SELECT * FROM `sj_substrategy` WHERE `id` =".$this->db->escape($substrategy_id);
            $strategy_query = $this->db->query($strategy_sql);

            $result = $strategy_query->row();

            if($strategy_query->num_rows() > 0) {
                return $result->strategy_id;
            } else {
                return 0;
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


        private function update_total_difficulty($ref_id) {

            $sql = "SELECT SUM(difficulty) AS total FROM sj_questions WHERE reference_id='$ref_id'";
            $total_difficulty = $this->db->query($sql)->row()->total;

            $sql = "UPDATE `sj_questions` set `total_difficulty` = '$total_difficulty'  WHERE `reference_id` ='$ref_id'";
            $sql = $this->db->query($sql);
                
        }


        public function get_new_question_counter($subject_id, $branch_name){

            $sql = "SELECT `question_counter` FROM `sj_questions` 
                    WHERE `subject_type` = ". $this->db->escape($subject_id) ." 
                    AND `branch_name` = ". $this->db->escape($branch_name) ." 
                    ORDER BY `question_id` DESC LIMIT 1";
            $sqlQuery = $this->db->query($sql);
            $result = $sqlQuery->row();

            if($result !== NULL){
                return $result->question_counter + 1;
            }else{
                return 1;
            }            

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
            } else if ($type == 'instruction'){
                $target_dir = './img/instructionImage/';
                if(!file_exists($target_dir)){
                    mkdir($target_dir, 0777);
                }
                $config['upload_path']          = './img/articleImage/';
            } else if ($type == 'article'){
                $target_dir = './img/articleImage/';
                if(!file_exists($target_dir)){
                    mkdir($target_dir, 0777);
                }
                $config['upload_path']          = './img/articleImage/';
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
                // echo $this->upload->display_errors();die();
                return $data['error_upload']['error'];;
            }
    
        }


        public function create_question(){
            $post = $this->input->post();

            // var_dump($post['mcq_correct_answer_multiple']);

            $mapping = array(
                '0' => 'A',
                '1'  => 'B',
                '2'  => 'C',
                '3'  => 'D',
                '4'  => 'E',
                '5'  => 'F',
                '6'  => 'G',
                '7'  => 'H',
                '8'  => 'I',
                '9'  => 'J', 
                '10'  => 'K',
                '11' => 'L'
            );


            // $this->db->trans_start();
            if ($this->session->userdata('is_logged_in') == 1) {
                $source = $this->session->userdata('user_id');
            } else {
                $source = 0;
            }            

            if($this->session->userdata('admin_username') == 'admindemo'){
                $question_level = 1;
                $branch_name = BRANCH_NAME;
            } else {
                $question_level = 0;
                $branch_name = 'SmartJen';
            }

            $subject_id = $post['subject_id'];
            
            $question_counter = $this->get_new_question_counter($subject_id, 'SmartJen');            

            $sub_question_number = intval($post['subQuestionNumber']);            

            for ($i = 0; $i<=$sub_question_number; $i++) {

                $key_topic = (isset($post['key_topic_'.$i]))?$post['key_topic_'.$i]:0;
                $key_strategy = (isset($post['key_strategy_'.$i]))?$post['key_strategy_'.$i]:0;


                if(isset($post['key_topic_'.$i])){
                    $topic_id  = (isset($post['topic_id_'.$i][0]))?$post['topic_id_'.$i][0]:0;
                    $topic_id2 = (isset($post['topic_id_'.$i][1]))?$post['topic_id_'.$i][1]:0;
                    $topic_id3 = (isset($post['topic_id_'.$i][2]))?$post['topic_id_'.$i][2]:0;
                    $topic_id4 = (isset($post['topic_id_'.$i][3]))?$post['topic_id_'.$i][3]:0;

                    $key_topic_id  = (isset($post['key_topic_id_'.$i]))?$post['key_topic_id_'.$i]:0;
                }else{
                    $topic_id  = 0;
                    $topic_id2 = 0;
                    $topic_id3 = 0;
                    $topic_id4 = 0;

                    $key_topic_id = null;
                }


                if ($post['subject_id'] == '2'){
                    $substrategy_id  = (isset($post['substrategy_id_'.$i][0]))?$post['substrategy_id_'.$i][0]:0;
                    $substrategy_id2 = (isset($post['substrategy_id_'.$i][1]))?$post['substrategy_id_'.$i][1]:0;
                    $substrategy_id3 = (isset($post['substrategy_id_'.$i][2]))?$post['substrategy_id_'.$i][2]:0;
                    $substrategy_id4 = (isset($post['substrategy_id_'.$i][3]))?$post['substrategy_id_'.$i][3]:0;
                    $strategy  = $this->get_strategy_id($substrategy_id);
                    $strategy2 = $this->get_strategy_id($substrategy_id2);
                    $strategy3 = $this->get_strategy_id($substrategy_id3);
                    $strategy4 = $this->get_strategy_id($substrategy_id4);                    
                } else {
                    $substrategy_id  = 0;
                    $substrategy_id2 = 0;
                    $substrategy_id3 = 0;
                    $substrategy_id4 = 0;
                    $strategy = '0';
                    $strategy2 = '0';
                    $strategy3 = '0';
                    $strategy4 = '0';
                }

                $key_substrategy_id  = (isset($post['key_substrategy_id'.$i]))?$post['key_substrategy_id'.$i]:0;
        
                $question_type_id = $post['question_type_id_' . $i];                
                $questionContent = str_replace(",", " ", $post['data_question_content_'.$i]);
                $workingContent = str_replace(",", " ", $post['data_working_content_'.$i]);
    
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
                
                $currentQuestionContent = ($arrIqc[0] == 'text')?$this->applyMathJaxFormat($post['input_question_text_'.$i][0]):$this->do_upload('question', 'input_question_image_'.$i, 0);
                if($arrIqc[0] == 'text'){
                    $countText--;
                }else{
                    $countImage--;
                }
                    
                
                $insert_array = array(
                    'question_counter' => $question_counter,
                    'question_text'    => $currentQuestionContent,
                    'reference_id'     => '1',
                    'level_id'         => $post['level_id'],
                    'topic_id'         => $topic_id,
                    'topic_id2'        => $topic_id2,
                    'topic_id3'        => $topic_id3,
                    'topic_id4'        => $topic_id4,
                    'key_topic'        => $key_topic,
                    'key_strategy'     => $key_strategy,
                    'substrategy_id'   => $substrategy_id,
                    'substrategy_id2'  => $substrategy_id2,
                    'substrategy_id3'  => $substrategy_id3,
                    'substrategy_id4'  => $substrategy_id4,
                    'strategy_id'      => $strategy,
                    'strategy_id2'     => $strategy2,
                    'strategy_id3'     => $strategy3,
                    'strategy_id4'     => $strategy4,
                    'key_topic_id'       => $key_topic_id,
                    'key_substrategy_id' => $key_substrategy_id,
                    'question_level'   => $question_level,
                    'content_type'     => ($arrIqc[0] == 'text')?'text':'image',
                    'question_content' => (count($arrIqc) > 1)?1:0,
                    'school_id'        => $post['school_id'],
                    'year'             => $post['year'],
                    'question_type_id' => $question_type_id,
                    'question_type_reference_id' => $question_type_id,
                    'difficulty_level'       => $post['difficulty_level'],
                    // 'difficulty_level2'       => (isset($post['difficulty_level2']))?$post['difficulty_level2']:0,
                    'level_id2'       => (isset($post['level_id2']))?$post['level_id2']:0,
                    'difficulty_level3'       => (isset($post['difficulty_level3']))?$post['difficulty_level3']:0,
                    'level_id3'       => (isset($post['level_id3']))?$post['level_id3']:0,
                    'difficulty'       => $post['marks_' . $i],
                    'source'           => $source,
                    'sub_question'     => $mapping[$i],
                    'answer_type_id'   => $post['answer_type_id_'. $i],
                    'source'           => $source,
                    'branch_name'      => $branch_name,
                    'subject_type'     => $post['subject_id']
                );
    
                $this->db->insert('sj_questions', $insert_array);
                $ques_id = $this->db->insert_id();
                $ref_id = ($i == 0)?$ques_id:$ref_id;
                $this->update_reference_id($ques_id, $ref_id);


                // insert question instruction
                if($i == 0 && isset($post['question_instruction'])){

                    $instructionContent = str_replace(",", " ", $post['data_instruction_content']);
                    $arrIic = explode(" ", $instructionContent);
                    
                    $countArText = 0;
                    $countArImage = 0;
                    
                    for($x=0 ; $x<count($arrIic) ; $x++){                
                        ($arrIic[$x] == 'text')?$countArText++:$countArImage++;
                    }
        
                    $countSetArText = $countArText;
                    $countSetArImage = $countArImage;

                    for($x=0 ; $x<count($arrIic) ; $x++){

                        if($arrIic[$x] == 'text'){                        
                            $data = [
                                'question_id' => $ques_id,     
                                'header_type' => 'instruction',                           
                                'content_type' => 'text',
                                'header_order' => $x+1, 
                                'header_content' => $this->applyMathJaxFormat($post['input_instruction_text'][$countSetArText-$countArText])
                            ];                        
                            $countArText--;
                        }else{
                            $data = [
                                'question_id' => $ques_id,       
                                'header_type' => 'instruction',                           
                                'content_type' => 'image',
                                'header_order' => $x+1, 
                                'header_content' => $this->do_upload('instruction', 'input_instruction_image', ($countSetArImage-$countArImage))
                            ];                        
                            $countArImage--;
                        }
    
                        $this->db->insert('sj_question_header', $data);

                    }

                }


                // insert question article
                if($i == 0 && isset($post['question_article'])){

                    $articleContent = str_replace(",", " ", $post['data_article_content']);
                    $arrIac = explode(" ", $articleContent);
                    
                    $countArText = 0;
                    $countArImage = 0;
                    
                    for($x=0 ; $x<count($arrIac) ; $x++){                
                        ($arrIac[$x] == 'text')?$countArText++:$countArImage++;
                    }
        
                    $countSetArText = $countArText;
                    $countSetArImage = $countArImage;

                    for($x=0 ; $x<count($arrIac) ; $x++){

                        if($arrIac[$x] == 'text'){                        
                            $data = [
                                'question_id' => $ques_id,  
                                'header_type' => 'article',
                                'content_type' => 'text',
                                'header_order' => $x+1, 
                                'header_content' => $this->applyMathJaxFormat($post['input_article_text'][$countSetArText-$countArText])
                            ];                        
                            $countArText--;
                        }else{
                            $data = [
                                'question_id' => $ques_id, 
                                'header_type' => 'article',                               
                                'content_type' => 'image',
                                'header_order' => $x+1, 
                                'header_content' => $this->do_upload('article', 'input_article_image', ($countSetArImage-$countArImage))
                            ];                        
                            $countArImage--;
                        }
    
                        $this->db->insert('sj_question_header', $data);

                    }

                }
    
    
                // insert question content            
                
                    for($x=1 ; $x<count($arrIqc) ; $x++){                
                        
                        if($arrIqc[$x] == 'text'){                        
                            $data = [
                                'question_id' => $ques_id,
                                'content_section' => 'question',
                                'content_type' => 'text',
                                'content_order' => $x+1, 
                                'content_name' => $this->applyMathJaxFormat($post['input_question_text_'.$i][$countSetText-$countText])
                            ];                        
                            $countText--;
                        }else{
                            $data = [
                                'question_id' => $ques_id,
                                'content_section' => 'question',
                                'content_type' => 'image',
                                'content_order' => $x+1, 
                                'content_name' => $this->do_upload('question', 'input_question_image_'.$i, ($countSetImage-$countImage))
                            ];                        
                            $countImage--;
                        }
    
                        $this->db->insert('sj_question_content', $data);
    
                    }
                
    
                // insert answers and correct answers
                if ($question_type_id == 1 || $question_type_id == 4) {  // mcq single choice
    
                    $mcq_type = $post['answer_type_mcq_' . $i];
    
                    if($mcq_type == 'text'){
    
                        $insert_mcq_answers = array(
                            array(
                                'question_id' => $ques_id,
                                'answer_type' => 'text',
                                'answer_text' => $this->applyMathJaxFormat($post['mcq_answers_' . $i][0])
                            ),
                            array(
                                'question_id' => $ques_id,
                                'answer_type' => 'text',
                                'answer_text' => $this->applyMathJaxFormat($post['mcq_answers_' . $i][1])
                            ),
                            array(
                                'question_id' => $ques_id,
                                'answer_type' => 'text',
                                'answer_text' => $this->applyMathJaxFormat($post['mcq_answers_' . $i][2])
                            ),
                            array(
                                'question_id' => $ques_id,
                                'answer_type' => 'text',
                                'answer_text' => $this->applyMathJaxFormat($post['mcq_answers_' . $i][3])
                            )
                        );
    
                        $this->db->insert_batch('sj_answers', $insert_mcq_answers);
                        $first_answer_id = $this->db->insert_id();
    
                    }else{
    
                        $data = [
                            'question_id' => $ques_id,
                            'answer_type' => 'image',
                            'answer_text' => $this->do_upload('answer', 'mcq_answers_image_'.$i, 0)
                        ];
                        $this->db->insert('sj_answers', $data);
                        $first_answer_id = $this->db->insert_id();
    
    
                        $data = [
                            'question_id' => $ques_id,
                            'answer_type' => 'image',
                            'answer_text' => $this->do_upload('answer', 'mcq_answers_image_'.$i, 1)
                        ];
                        $this->db->insert('sj_answers', $data);
    
    
                        $data = [
                            'question_id' => $ques_id,
                            'answer_type' => 'image',
                            'answer_text' => $this->do_upload('answer', 'mcq_answers_image_'.$i, 2)
                        ];
                        $this->db->insert('sj_answers', $data);
    
    
                        $data = [
                            'question_id' => $ques_id,
                            'answer_type' => 'image',
                            'answer_text' => $this->do_upload('answer', 'mcq_answers_image_'.$i, 3)
                        ];
                        $this->db->insert('sj_answers', $data);                    
    
                    }
    
                    
                    $mcq_correct_answer = $post['mcq_correct_answer_' . $i];
                    $correct_answer_id = $first_answer_id + $mcq_correct_answer - 1;
                    $this->db->insert('sj_correct_answer', array(
                        'question_id' => $ques_id,
                        'answer_id'   => $correct_answer_id
                    ));
                
                } else if ($question_type_id == 2) { // open ended                    
    
                    //insert first working content
                    $countWorkingText = 0;
                    $countWorkingImage = 0;
                    
                    for($x=1 ; $x<count($arrIwc) ; $x++){                
                        ($arrIwc[$x] == 'text')?$countWorkingText++:$countWorkingImage++;
                    }
    
                    $countWorkingSetText = $countWorkingText;
                    $countWorkingSetImage = $countWorkingImage;
                    
                    if($i == 0){
                        if($arrIwc[0] == 'text'){
                            $currentWorkingContent = $this->applyMathJaxFormat($post['input_working_text_'.$i.'_'][0]);
                            $countWorkingText--;
                        } else if($arrIwc[0] == 'image') {
                            $currentWorkingContent = $this->do_upload('working', 'input_working_image_'.$i.'_', 0);
                            $countWorkingImage--;
                        } else {
                            $currentWorkingContent = '';
                        }                       
    
                    }else{
                        if($arrIwc[0] == 'text'){
                            $currentWorkingContent = $this->applyMathJaxFormat($post['input_working_text_'.$i.'_'][0]);
                            $countWorkingText--;
                        } else if($arrIwc[0] == 'image') {
                            $currentWorkingContent = $this->do_upload('working', 'input_working_image_'.$i.'_', 0);
                            $countWorkingImage--;
                        } else {
                            $currentWorkingContent = '';
                        }
                        
                    }                
                    
                    // insert nmcq answer and correct answer
    
                    $nmcq_type = $post['answer_type_mcq_' . $i];
    
                    if($nmcq_type == 'text'){
                        $nmcq_answer_text = ($i == 0)?$this->applyMathJaxFormat($post['open_ended_answer_0']):$this->applyMathJaxFormat($post['open_ended_answer_' . $i]);
                        $nmcq_answer_type = 'text';
                    } else {
                        $nmcq_answer_text = $this->do_upload('answer', ($i == 0)?'nmcq_answers_image_'.$i:'nmcq_answers_image_'.$i, 0);
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
                                'content_name' => ($i == 0)?$this->applyMathJaxFormat($post['input_working_text_'.$i.'_'][$countWorkingSetText-$countWorkingText]):$this->applyMathJaxFormat($post['input_working_text_'.$i.'_'][$countWorkingSetText-$countWorkingText])
                            ];                        
                            $countWorkingText--;
                        }else{
                            $data = [
                                'question_id' => $ques_id,
                                'content_section' => 'working',
                                'content_type' => 'image',
                                'content_order' => $x+1, 
                                'content_name' => ($i == 0)?$this->do_upload('working', 'input_working_image_'.$i.'_', ($countWorkingSetImage-$countWorkingImage)):$this->do_upload('working', 'input_working_image_'.$i.'_', ($countWorkingSetImage-$countWorkingImage))
                            ];                        
                            $countWorkingImage--;
                        }
    
                        $this->db->insert('sj_question_content', $data);
    
                    }
    
                    if(count($arrIwc) > 1){
                        $sql = "UPDATE `sj_questions` SET `question_content` = '1' WHERE `question_id` =" . $ques_id;
                        $query = $this->db->query($sql);
                    }
    
                } else if ($question_type_id == 3) { // true & false

                    if($post['true_false_answer_'.$i] == 1){
                        $answer = 'True';
                    }else{
                        $answer = 'False';
                    }

                    $insert_answer = [
                            'question_id' => $ques_id,
                            'answer_type' => 'text',
                            'answer_text' => $answer
                    ];

                    $this->db->insert('sj_answers', $insert_answer);
                    $first_answer_id = $this->db->insert_id();

                    $this->db->insert('sj_correct_answer', array(
                        'question_id' => $ques_id,
                        'answer_id'   => $first_answer_id
                    ));

                } else if ($question_type_id == 5) { // FITB with option
                    
                    for($ans=0 ; $ans<count($post['input_answer_fb_open_'.$i]) ; $ans++){
                        $data = [
                            'question_id' => $ques_id, 
                            'answer_group' => $ans+1,                               
                            'answer_type' => 'text',
                            'answer_text' => $post['input_answer_fb_open_'.$i][$ans]
                        ];
                        
                        $this->db->insert('sj_answers', $data);
                        $first_answer_id = $this->db->insert_id();

                        $this->db->insert('sj_correct_answer', array(
                            'question_id' => $ques_id,
                            'answer_id'   => $first_answer_id
                        ));
                        
                    }

                    for($ans=0 ; $ans<count($post['input_answer_fb_distractor_'.$i]) ; $ans++){
                        $data = [
                            'question_id' => $ques_id, 
                            'answer_group' => 0,                               
                            'answer_type' => 'text',
                            'answer_text' => $post['input_answer_fb_distractor_'.$i][$ans]
                        ];
                        
                        $this->db->insert('sj_answers', $data);
                        
                    }


                    

                } else if ($question_type_id == 6) { // FITB without option                    

                    $count_answer_fb = intval($post['count_answer_fb_'.$i])-1;                        
                    for($ans=1 ; $ans<=$count_answer_fb ; $ans++){
                        
                        $answer_fb = $post['input_answer_fb_open_'.$i.'_'.$ans];

                        for($option=0 ; $option<count($answer_fb) ; $option++){

                            $data = [
                                'question_id' => $ques_id,
                                'answer_group' => $ans,
                                'answer_type' => 'text',
                                'answer_text' => $post['input_answer_fb_open_'.$i.'_'.$ans][$option]
                            ];
                            
                            $this->db->insert('sj_answers', $data);                                
                            
                            $answer_id = $this->db->insert_id();
                            $this->db->insert('sj_correct_answer', array(
                                'question_id' => $ques_id,
                                'answer_id'   => $answer_id
                            ));                            

                        }                                                    
                        
                    }

                } else if ($question_type_id == 7) { // FITB with unique option
                    $count_answer_fb = intval($post['count_answer_fb_'.$i])-1;                        
                    for($ans=1 ; $ans<=$count_answer_fb ; $ans++){
                        
                        $answer_fb = $post['input_answer_fb_open_'.$i.'_'.$ans];

                        for($option=0 ; $option<count($answer_fb) ; $option++){

                            $data = [
                                'question_id' => $ques_id,
                                'answer_group' => $ans,
                                'answer_type' => 'text',
                                'answer_text' => $post['input_answer_fb_open_'.$i.'_'.$ans][$option]
                            ];
                            
                            $this->db->insert('sj_answers', $data);                                

                            if($option == 0){
                                $first_answer_id = $this->db->insert_id();
                                $this->db->insert('sj_correct_answer', array(
                                    'question_id' => $ques_id,
                                    'answer_id'   => $first_answer_id
                                ));
                            }

                        }                                                    
                        
                    }
                } else if ($question_type_id == 8) { // mcq multiple choice

                    $mcq_type = $post['answer_type_mcq_' . $i];
    
                    if($mcq_type == 'text'){
    
                        $insert_mcq_answers = array(
                            array(
                                'question_id' => $ques_id,
                                'answer_type' => 'text',
                                'answer_text' => $this->applyMathJaxFormat($post['mcq_answers_' . $i][0])
                            ),
                            array(
                                'question_id' => $ques_id,
                                'answer_type' => 'text',
                                'answer_text' => $this->applyMathJaxFormat($post['mcq_answers_' . $i][1])
                            ),
                            array(
                                'question_id' => $ques_id,
                                'answer_type' => 'text',
                                'answer_text' => $this->applyMathJaxFormat($post['mcq_answers_' . $i][2])
                            ),
                            array(
                                'question_id' => $ques_id,
                                'answer_type' => 'text',
                                'answer_text' => $this->applyMathJaxFormat($post['mcq_answers_' . $i][3])
                            )
                        );
    
                        $this->db->insert_batch('sj_answers', $insert_mcq_answers);
                        $first_answer_id = $this->db->insert_id();
    
                    }else{
    
                        $data = [
                            'question_id' => $ques_id,
                            'answer_type' => 'image',
                            'answer_text' => $this->do_upload('answer', 'mcq_answers_image_'.$i, 0)
                        ];
                        $this->db->insert('sj_answers', $data);
                        $first_answer_id = $this->db->insert_id();
    
    
                        $data = [
                            'question_id' => $ques_id,
                            'answer_type' => 'image',
                            'answer_text' => $this->do_upload('answer', 'mcq_answers_image_'.$i, 1)
                        ];
                        $this->db->insert('sj_answers', $data);
    
    
                        $data = [
                            'question_id' => $ques_id,
                            'answer_type' => 'image',
                            'answer_text' => $this->do_upload('answer', 'mcq_answers_image_'.$i, 2)
                        ];
                        $this->db->insert('sj_answers', $data);
    
    
                        $data = [
                            'question_id' => $ques_id,
                            'answer_type' => 'image',
                            'answer_text' => $this->do_upload('answer', 'mcq_answers_image_'.$i, 3)
                        ];
                        $this->db->insert('sj_answers', $data);                    
    
                    }
    
                    
                    $mcq_correct_answer = $post['mcq_correct_answer_multiple_' . $i];

                    for($ans=0 ; $ans<count($mcq_correct_answer) ; $ans++){
                        $correct_answer_id = $first_answer_id + $mcq_correct_answer[$ans] - 1;
                        $this->db->insert('sj_correct_answer', array(
                            'question_id' => $ques_id,
                            'answer_id'   => $correct_answer_id
                        ));
                    }

                }
    
                $this->update_question_view($ques_id);
                $this->update_total_difficulty($ques_id);
                $this->update_question_tag($ques_id, $post['tagsinput_' . $i]);
                
            }


            // $this->db->trans_complete();
            return $this->db->affected_rows();
           

        }

        public function update_question(){
            $post = $this->input->post();

            // var_dump($post['mcq_correct_answer_multiple']);

            $mapping = array(
                '0' => 'A',
                '1'  => 'B',
                '2'  => 'C',
                '3'  => 'D',
                '4'  => 'E',
                '5'  => 'F',
                '6'  => 'G',
                '7'  => 'H',
                '8'  => 'I',
                '9'  => 'J', 
                '10'  => 'K',
                '11' => 'L'
            );


            // $this->db->trans_start();
            if ($this->session->userdata('is_logged_in') == 1) {
                $source = $this->session->userdata('user_id');
            } else {
                $source = 0;
            }            

            if($this->session->userdata('admin_username') == 'admindemo'){
                $question_level = 1;
                $branch_name = BRANCH_NAME;
            } else {
                $question_level = 0;
                $branch_name = 'SmartJen';
            }

            $subject_id = $post['subject_id'];
            
            // $question_counter = $this->get_new_question_counter($subject_id, 'SmartJen');            

            $sub_question_number = intval($post['subQuestionNumber']);            

            for ($i = 0; $i<=$sub_question_number; $i++) {

                $key_topic = (isset($post['key_topic_'.$i]))?$post['key_topic_'.$i]:0;
                $key_strategy = (isset($post['key_strategy_'.$i]))?$post['key_strategy_'.$i]:0;
                $disabled = (isset($post['disable_question_'.$i]))?$post['disable_question_'.$i]:0;

                if(isset($post['key_topic_'.$i])){
                    $topic_id  = (isset($post['topic_id_'.$i][0]))?$post['topic_id_'.$i][0]:0;
                    $topic_id2 = (isset($post['topic_id_'.$i][1]))?$post['topic_id_'.$i][1]:0;
                    $topic_id3 = (isset($post['topic_id_'.$i][2]))?$post['topic_id_'.$i][2]:0;
                    $topic_id4 = (isset($post['topic_id_'.$i][3]))?$post['topic_id_'.$i][3]:0;

                    $key_topic_id  = (isset($post['key_topic_id_'.$i]))?$post['key_topic_id_'.$i]:0;
                }else{
                    $topic_id  = 0;
                    $topic_id2 = 0;
                    $topic_id3 = 0;
                    $topic_id4 = 0;

                    $key_topic_id = null;
                }


                if ($post['subject_id'] == '2'){
                    $substrategy_id  = (isset($post['substrategy_id_'.$i][0]))?$post['substrategy_id_'.$i][0]:0;
                    $substrategy_id2 = (isset($post['substrategy_id_'.$i][1]))?$post['substrategy_id_'.$i][1]:0;
                    $substrategy_id3 = (isset($post['substrategy_id_'.$i][2]))?$post['substrategy_id_'.$i][2]:0;
                    $substrategy_id4 = (isset($post['substrategy_id_'.$i][3]))?$post['substrategy_id_'.$i][3]:0;
                    $strategy  = $this->get_strategy_id($substrategy_id);
                    $strategy2 = $this->get_strategy_id($substrategy_id2);
                    $strategy3 = $this->get_strategy_id($substrategy_id3);
                    $strategy4 = $this->get_strategy_id($substrategy_id4);                    
                } else {
                    $substrategy_id  = 0;
                    $substrategy_id2 = 0;
                    $substrategy_id3 = 0;
                    $substrategy_id4 = 0;
                    $strategy = '0';
                    $strategy2 = '0';
                    $strategy3 = '0';
                    $strategy4 = '0';
                }

                $key_substrategy_id  = (isset($post['key_substrategy_id'.$i]))?$post['key_substrategy_id'.$i]:0;
        
                $question_type_id = $post['question_type_id_' . $i];                
                $questionContent = str_replace(",", " ", $post['data_question_content_'.$i]);
                $workingContent = str_replace(",", " ", $post['data_working_content_'.$i]);
    
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
                
                $currentQuestionContent = ($arrIqc[0] == 'text')?$this->applyMathJaxFormat($post['input_question_text_'.$i][0]):$this->do_upload('question', 'input_question_image_'.$i, 0);
                if($arrIqc[0] == 'text'){
                    $countText--;
                }else{
                    $countImage--;
                }          

                $update_array = array(
                    // 'question_counter' => $question_counter,
                    'disabled'          => $disabled,
                    'question_text'    => trim($currentQuestionContent),
                    // 'reference_id'     => '1',
                    'level_id'         => $post['level_id'],
                    'topic_id'         => $topic_id,
                    'topic_id2'        => $topic_id2,
                    'topic_id3'        => $topic_id3,
                    'topic_id4'        => $topic_id4,
                    'key_topic'        => $key_topic,
                    'key_strategy'     => $key_strategy,
                    'substrategy_id'   => $substrategy_id,
                    'substrategy_id2'  => $substrategy_id2,
                    'substrategy_id3'  => $substrategy_id3,
                    'substrategy_id4'  => $substrategy_id4,
                    'strategy_id'      => $strategy,
                    'strategy_id2'     => $strategy2,
                    'strategy_id3'     => $strategy3,
                    'strategy_id4'     => $strategy4,
                    'key_topic_id'       => $key_topic_id,
                    'key_substrategy_id' => $key_substrategy_id,
                    'question_level'   => $question_level,
                    'content_type'     => ($arrIqc[0] == 'text')?'text':'image',
                    'question_content' => (count($arrIqc) > 1)?1:0,
                    'school_id'        => $post['school_id'],
                    'year'             => $post['year'],
                    'question_type_id' => $question_type_id,
                    'question_type_reference_id' => $question_type_id,
                    'difficulty_level'       => ($post['subject_id'] == 1)?null:$post['difficulty_level'],
                    // 'difficulty_level2'       => (isset($post['difficulty_level2']))?$post['difficulty_level2']:0,
                    'level_id2'       => (isset($post['level_id2']))?$post['level_id2']:0,
                    'difficulty_level3'       => (isset($post['difficulty_level3']))?$post['difficulty_level3']:0,
                    'level_id3'       => (isset($post['level_id3']))?$post['level_id3']:0,
                    'difficulty'       => $post['marks_' . $i],
                    'source'           => $source,
                    'sub_question'     => $mapping[$i],
                    'answer_type_id'   => $post['answer_type_id_'. $i],
                    'source'           => $source,
                    'branch_name'      => $branch_name,
                    'subject_type'     => $post['subject_id']
                );
    
                // $this->db->insert('sj_questions', $update_array);
                // $ques_id = $this->db->insert_id();
                // $ref_id = ($i == 0)?$ques_id:$ref_id;
                // $this->update_reference_id($ques_id, $ref_id);
                $question_id = $this->model_question->get_question_id_from_counter($post['question_counter'],$post['subject_id'],1);

                // $this->db->trans_start();
                $this->db->where('question_id', $question_id);
                $query = $this->db->update('sj_questions', $update_array);

                if($i == 0 && isset($post['question_instruction'])){

                    $instructionContent = str_replace(",", " ", $post['data_instruction_content']);
                    $arrIic = explode(" ", $instructionContent);
                    
                    $countArText = 0;
                    $countArImage = 0;
                    
                    for($x=0 ; $x<count($arrIic) ; $x++){                
                        ($arrIic[$x] == 'text')?$countArText++:$countArImage++;
                    }
        
                    $countSetArText = $countArText;
                    $countSetArImage = $countArImage;

                    // fetch question instruction
                    $question_instruction = $this->model_question->get_question_instruction_from_id($question_id);

                    // delete previous unused question content
                    if(count($question_instruction) > count($arrIic)){
                        $delete_content = count($arrIic) + 1;

                        $delete_sql = "DELETE FROM `sj_question_header` WHERE  `header_order` >= " .$delete_content." AND `question_id` = " .$question_id." AND `header_type` = 'instruction'";
                        $query = $this->db->query($delete_sql);
                    }

                    for($x=0 ; $x<count($arrIic) ; $x++){

                        if(count($question_instruction) > 0 && $x < count($question_instruction)){
                            foreach($question_instruction as $row){
            
                                // update instruction fields
                                if($row->header_order == $x+1){
                                    if($arrIic[$x] == 'text'){
                                        $data = [
                                            'question_id' => $question_id,
                                            'header_type' => 'instruction',
                                            'content_type' => 'text',
                                            'header_order' => $x+1, 
                                            'header_content' => trim($this->applyMathJaxFormat($post['input_instruction_text'][$countSetArText-$countArText]))
                                        ]; 
                                        $this->db->where('header_id', $row->header_id);
                                        $this->db->update('sj_question_header', $data);
            
                                        $countArText--;
                                    } else {
                                        if(is_uploaded_file($_FILES['input_instruction_image']['tmp_name'][$countSetArImage-$countArImage])){
                                            $data = [
                                                'question_id' => $question_id,
                                                'header_type' => 'instruction',
                                                'content_type' => 'image',
                                                'header_order' => $x+1, 
                                                'header_content' => $this->do_upload('question', 'input_instruction_image', ($countSetImage-$countImage))
                                            ];   
                                            $this->db->where('header_id', $row->header_id);
                                            $this->db->update('sj_question_header', $data);   
                                        }
                                                          
                                        $countArImage--;
                                    }
                                } 
                            }
                        } else {
                            if($arrIic[$x] == 'text'){                        
                                $data = [
                                    'question_id' => $question_id,     
                                    'header_type' => 'instruction',                           
                                    'content_type' => 'text',
                                    'header_order' => $x+1, 
                                    'header_content' => trim($this->applyMathJaxFormat($post['input_instruction_text'][$countSetArText-$countArText]))
                                ];                        
                                $countArText--;
                            }else{
                                $data = [
                                    'question_id' => $question_id,       
                                    'header_type' => 'instruction',                           
                                    'content_type' => 'image',
                                    'header_order' => $x+1, 
                                    'header_content' => $this->do_upload('instruction', 'input_instruction_image', ($countSetArImage-$countArImage))
                                ];                        
                                $countArImage--;
                            }
        
                            $this->db->insert('sj_question_header', $data);
                        }
                    }
                }

                // insert question article
                if($i == 0 && isset($post['question_article'])){

                    $articleContent = str_replace(",", " ", $post['data_article_content']);
                    $arrIac = explode(" ", $articleContent);
                    
                    $countArText = 0;
                    $countArImage = 0;
                    
                    for($x=0 ; $x<count($arrIac) ; $x++){                
                        ($arrIac[$x] == 'text')?$countArText++:$countArImage++;
                    }
                    
                    $countSetArText = $countArText;
                    $countSetArImage = $countArImage;

                    // fetch question article
                    $question_article = $this->model_question->get_question_article_from_id($question_id);

                    // delete previous unused question article
                    if(count($question_article) > count($arrIac)){
                        $delete_content = count($arrIac) + 1;

                        $delete_sql = "DELETE FROM `sj_question_header` WHERE  `header_order` >= " .$delete_content." AND `question_id` = " .$question_id." AND `header_type` = 'article'";
                        $query = $this->db->query($delete_sql);
                    }

                    for($x=0 ; $x<count($arrIac) ; $x++){

                        if(count($question_article) > 0 && $x < count($question_article)){
                            foreach($question_article as $row){
            
                                // update question article fields
                                if($row->header_order == $x+1){
                                    if($arrIac[$x] == 'text'){
                                        $data = [
                                            'question_id' => $question_id,
                                            'header_type' => 'article',
                                            'content_type' => 'text',
                                            'header_order' => $x+1, 
                                            'header_content' => trim($this->applyMathJaxFormat($post['input_article_text'][$countSetArText-$countArText]))
                                        ]; 
                                        $this->db->where('header_id', $row->header_id);
                                        $this->db->update('sj_question_header', $data);
            
                                        $countArText--;
                                    } else {
                                        if(is_uploaded_file($_FILES['input_article_image']['tmp_name'][$countSetImage-$countImage])){
                                            $data = [
                                                'question_id' => $question_id,
                                                'header_type' => 'article',
                                                'content_type' => 'image',
                                                'header_order' => $x+1, 
                                                'header_content' => $this->do_upload('article', 'input_article_image', ($countSetArImage-$countArImage))
                                            ];   
                                            $this->db->where('header_id', $row->header_id);
                                            $this->db->update('sj_question_header', $data);   
                                        }
                                                          
                                        $countArImage--;
                                    }
                                } 
                            }
                        } else {

                            if($arrIac[$x] == 'text'){                        
                                $data = [
                                    'question_id' => $question_id,  
                                    'header_type' => 'article',
                                    'content_type' => 'text',
                                    'header_order' => $x+1, 
                                    'header_content' => trim($this->applyMathJaxFormat($post['input_article_text'][$countSetArText-$countArText]))
                                ];                        
                                $countArText--;
                            }else{
                                $data = [
                                    'question_id' => $question_id, 
                                    'header_type' => 'article',                               
                                    'content_type' => 'image',
                                    'header_order' => $x+1, 
                                    'header_content' => $this->do_upload('article', 'input_article_image', ($countSetArImage-$countArImage))
                                ];                        
                                $countArImage--;
                            }
        
                            $this->db->insert('sj_question_header', $data);
                        }
                    }
                }
                
                $question_content = $this->model_question->get_question_content_from_id($question_id);

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
                                        'content_name' => trim($this->applyMathJaxFormat($post['input_question_text_'.$i][$countSetText-$countText]))
                                    ]; 
                                    $this->db->where('content_id', $row->content_id);
                                    $this->db->update('sj_question_content', $data);
        
                                    $countText--;
                                } else {
                                    if(is_uploaded_file($_FILES['input_question_image_'.$i]['tmp_name'][$countSetImage-$countImage])){
                                        $data = [
                                            'question_id' => $question_id,
                                            'content_section' => 'question',
                                            'content_type' => 'image',
                                            'content_order' => $x+1, 
                                            'content_name' => $this->do_upload('question', 'input_question_image_'.$i, ($countSetImage-$countImage))
                                        ];   
                                        $this->db->where('content_id', $row->content_id);
                                        $this->db->update('sj_question_content', $data);   
                                    }
                                                      
                                    $countImage--;
                                }
                            } 
                        }
                    } else {
    
                        // insert question content                                       
                        if($arrIqc[$x] == 'text'){                        
                            $data = [
                                'question_id' => $question_id,
                                'content_section' => 'question',
                                'content_type' => 'text',
                                'content_order' => $x+1, 
                                'content_name' => $this->applyMathJaxFormat($post['input_question_text_'.$i][$countSetText-$countText])
                            ];                        
                            $countText--;
                        }else{
                            $data = [
                                'question_id' => $question_id,
                                'content_section' => 'question',
                                'content_type' => 'image',
                                'content_order' => $x+1, 
                                'content_name' => $this->do_upload('question', 'input_question_image_'.$i, ($countSetImage-$countImage))
                            ];                        
                            $countImage--;
                        }
    
                        $this->db->insert('sj_question_content', $data);
                    } 
                }

                $query = $this->db->query("SELECT answer_id FROM sj_answers WHERE question_id = ?", array($question_id));
                
                // insert answers and correct answers
                if ($question_type_id == 1 || $question_type_id == 4) {  // mcq single choice

                    // insert answer and correct answers if empty row in answer table
                    if($query->num_rows() == 0){
                        $mcq_type = $post['answer_type_mcq_' . $i];
    
                        if($mcq_type == 'text'){
        
                            $insert_mcq_answers = array(
                                array(
                                    'question_id' => $question_id,
                                    'answer_type' => 'text',
                                    'answer_text' => $this->applyMathJaxFormat($post['mcq_answers_' . $i][0])
                                ),
                                array(
                                    'question_id' =>  $question_id,
                                    'answer_type' => 'text',
                                    'answer_text' => $this->applyMathJaxFormat($post['mcq_answers_' . $i][1])
                                ),
                                array(
                                    'question_id' =>  $question_id,
                                    'answer_type' => 'text',
                                    'answer_text' => $this->applyMathJaxFormat($post['mcq_answers_' . $i][2])
                                ),
                                array(
                                    'question_id' =>  $question_id,
                                    'answer_type' => 'text',
                                    'answer_text' => $this->applyMathJaxFormat($post['mcq_answers_' . $i][3])
                                )
                            );
        
                            $this->db->insert_batch('sj_answers', $insert_mcq_answers);
                            $first_answer_id = $this->db->insert_id();
        
                        }else{
        
                            $data = [
                                'question_id' =>  $question_id,
                                'answer_type' => 'image',
                                'answer_text' => $this->do_upload('answer', 'mcq_answers_image_'.$i, 0)
                            ];
                            $this->db->insert('sj_answers', $data);
                            $first_answer_id = $this->db->insert_id();
        
        
                            $data = [
                                'question_id' =>  $question_id,
                                'answer_type' => 'image',
                                'answer_text' => $this->do_upload('answer', 'mcq_answers_image_'.$i, 1)
                            ];
                            $this->db->insert('sj_answers', $data);
        
        
                            $data = [
                                'question_id' =>  $question_id,
                                'answer_type' => 'image',
                                'answer_text' => $this->do_upload('answer', 'mcq_answers_image_'.$i, 2)
                            ];
                            $this->db->insert('sj_answers', $data);
        
        
                            $data = [
                                'question_id' =>  $question_id,
                                'answer_type' => 'image',
                                'answer_text' => $this->do_upload('answer', 'mcq_answers_image_'.$i, 3)
                            ];
                            $this->db->insert('sj_answers', $data);                    
        
                        }
        
                        
                        $mcq_correct_answer = $post['mcq_correct_answer_' . $i];
                        $correct_answer_id = $first_answer_id + $mcq_correct_answer - 1;
                        $this->db->insert('sj_correct_answer', array(
                            'question_id' => $ques_id,
                            'answer_id'   => $correct_answer_id
                        ));
                    } else {
                        $index = 0;
                        $first_answer_id = 0;
                        $answerArray = $query->result();
                        if($query->num_rows() < 4) {

                            foreach ($answerArray as $row) {
                                if ($index == 0) {
                                    $first_answer_id = $row->answer_id;

                                    $this->db->query("UPDATE sj_answers SET answer_text = ? WHERE answer_id = ?", array(
                                        trim($this->applyMathJaxFormat($post['mcq_answers_' . $i][$index])),
                                        $first_answer_id
                                    ));
                                }
                                $index++;
                            }

                            $insert_mcq_answers = array(
                                array(
                                    'question_id' => $question_id,
                                    'answer_text' => $this->applyMathJaxFormat($post['mcq_answers_' . $i][1])
                                ),
                                array(
                                    'question_id' => $question_id,
                                    'answer_text' => $this->applyMathJaxFormat($post['mcq_answers_' . $i][2])
                                ),
                                array(
                                    'question_id' => $question_id,
                                    'answer_text' => $this->applyMathJaxFormat($post['mcq_answers_' . $i][3])
                                )
                            );
                            $this->db->insert_batch('sj_answers', $insert_mcq_answers);

                        } else {
                            //update mcq text / image
                            if($post['answer_type_mcq_0'] == 'text'){
                                
                                foreach ($answerArray as $row) {
                                    if ($index == 0) {
                                        $first_answer_id = $row->answer_id;
                                    }
                
                                    $this->db->query("UPDATE sj_answers SET answer_text = ?, answer_type = ? WHERE answer_id = ?", array(
                                        trim($this->applyMathJaxFormat($post['mcq_answers_' . $i][$index])),
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

                                    if(is_uploaded_file($_FILES['mcq_answers_image_'.$i]['tmp_name'][$index])){
                                        $mcq_answer_text = $this->do_upload('answer', 'mcq_answers_image_'.$i, $index);
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
                        
                        if(array_key_exists("mcq_correct_answer_0",$post) === false) {
                            return false;
                        }

                        $answerArray = $this->db->query("SELECT answer_id FROM sj_answers WHERE question_id = ?", array($question_id));
                        $answerArray = $answerArray->result();

                        $sql = "SELECT * FROM `sj_correct_answer` WHERE `question_id` =" . $question_id;
                        $query = $this->db->query($sql);

                        $correct_answer_id = $answerArray[$post['mcq_correct_answer_0'] - 1]->answer_id;

                        if($query->num_rows() > 0) {
                            $this->db->query("UPDATE sj_correct_answer SET answer_id = ? WHERE question_id = ?", array(
                                $correct_answer_id,
                                $question_id
                            ));
                        } else {
                            $sql = "INSERT INTO `sj_correct_answer` (`question_id`, `answer_id`) VALUES ('" . $question_id . "', '" .  $correct_answer_id . "')";
                            $query = $this->db->query($sql);
                        }
                    }     
                    
                    // update workings

                    //insert first working content
                    $countWorkingText = 0;
                    $countWorkingImage = 0;
                    
                    for($x=1 ; $x<count($arrIwc) ; $x++){                
                        ($arrIwc[$x] == 'text')?$countWorkingText++:$countWorkingImage++;
                    }

                    $countWorkingSetText = $countWorkingText;
                    $countWorkingSetImage = $countWorkingImage;


                    $mcq_answer_query = $this->db->query("SELECT working_text FROM sj_answers WHERE answer_id = ?", array($answerArray[0]->answer_id));

                    // $workingContent = str_replace(",", " ", $post['input_working_content']);
                    // $arrIwc = explode(" ", $workingContent);
                    // $currentWorkingContent = '';

                    $working_countText = 0;
                    $working_countImage = 0;
                        
                    for($x=1 ; $x<count($arrIwc) ; $x++){                
                        ($arrIwc[$x] == 'text')?$working_countText++:$working_countImage++;
                    }
                    
                    $workingCountSetText = $working_countText;
                    $workingCountSetImage = $working_countImage;

                    if($arrIwc[0] == 'text'){
                        $currentWorkingContent = trim($this->applyMathJaxFormat($post['input_working_text_0_'][0]));
                        $working_countText--;
                    } else if($arrIwc[0] == 'image'){
                        if(is_uploaded_file($_FILES['input_working_image_0_']['tmp_name'][0])){
                            $currentWorkingContent = $this->do_upload('working', 'input_working_image_0_', 0);
                            $working_countImage--;
                        } else {
                            $currentWorkingContent = $mcq_answer_query->row()->working_text;
                            $working_countImage--;
                        }
                    } else {
                        $currentWorkingContent = '';
                        $arrIwc[0] = 'text';
                    }

                    if($mcq_answer_query->num_rows() == 1 || $mcq_answer_query->num_rows() == 4){
                        // update workings inside sj_answers
                        $this->db->query("UPDATE sj_answers SET working_type = ?, working_text = ? WHERE answer_id = ?", array(
                            $arrIwc[0],
                            $currentWorkingContent,
                            $answerArray[0]->answer_id
                        ));
                    }

                    // update question_content value

                    if(count($arrIwc) > 1){
                        $question_content_sql = "UPDATE `sj_questions` SET `question_content` = '1' WHERE `question_id` = " .$this->db->escape($question_id);
                        $query = $this->db->query($question_content_sql);
                    }

                    $working_content = $this->model_question->get_working_content_from_id($question_id);

                    // delete previous unused question content
                    if(count($working_content) > count($arrIwc)-1){
                        $delete_content = count($arrIwc) + 1;

                        $delete_sql = "DELETE FROM `sj_question_content` WHERE `content_order` >= " .$delete_content." AND `question_id` = " .$question_id." AND `content_section` = 'working'";
                        $query = $this->db->query($delete_sql);
                    }
                    
                    for($x=1 ; $x<count($arrIwc) ; $x++){                
                        
                        if(count($working_content) > 0 && $x <= count($working_content)){
                            foreach($working_content as $row){

                                // update question fields
                                if($row->content_order == $x+1){
                                    if($arrIwc[$x] == 'text'){
                                        $data = [
                                            'question_id' => $question_id,
                                            'content_section' => 'working',
                                            'content_type' => 'text',
                                            'content_order' => $x+1, 
                                            'content_name' => trim($this->applyMathJaxFormat($post['input_working_text_0_'][$workingCountSetText-$working_countText]))
                                        ]; 
                                        $this->db->where('content_id', $row->content_id);
                                        $this->db->update('sj_question_content', $data);

                                        $working_countText--;
                                    } else {
                                        if(is_uploaded_file($_FILES['input_working_image_0_']['tmp_name'][$workingCountSetImage-$working_countImage])){
                                            $data = [
                                                'question_id' => $question_id,
                                                'content_section' => 'working',
                                                'content_type' => 'image',
                                                'content_order' => $x+1, 
                                                'content_name' => $this->do_upload('working', 'input_working_image_0_', ($workingCountSetImage-$working_countImage))
                                            ];   
                                            $this->db->where('content_id', $row->content_id);
                                            $this->db->update('sj_question_content', $data);   
                                        }
                                                        
                                        $working_countImage--;
                                    }
                                } 
                            }
                        } else {
                        
                            //insert new question content 1st question content
                            if($arrIwc[$x] == 'text'){                        
                                $data = [
                                    'question_id' => $question_id,
                                    'content_section' => 'working',
                                    'content_type' => 'text',
                                    'content_order' => $x+1, 
                                    'content_name' => trim($this->applyMathJaxFormat($post['input_working_text_0_'][$workingCountSetText-$working_countText]))
                                ]; 
                                $this->db->insert('sj_question_content', $data);

                                $working_countText--;
                            }else{
                                if(is_uploaded_file($_FILES['input_working_image_0_']['tmp_name'][$workingCountSetImage-$working_countImage])){
                                    $data = [
                                        'question_id' => $question_id,
                                        'content_section' => 'working',
                                        'content_type' => 'image',
                                        'content_order' => $x+1, 
                                        'content_name' => $this->do_upload('working', 'input_working_image_0_', ($workingCountSetImage-$working_countImage))
                                    ];   

                                    $this->db->insert('sj_question_content', $data);   
                                }
                                                
                                $working_countImage--;
                            } 
                        }
                        
                        // $this->db->insert('sj_question_content', $data);
                    } 
                
                } else if ($question_type_id == 2) { // open ended                    
    
                    //update nmcq text / image
                    if($post['answer_type_nmcq_' . $i] == 'text'){

                        $nmcq_answer_text = ($i == 0)?$this->applyMathJaxFormat($post['open_ended_answer_0']):$this->applyMathJaxFormat($post['open_ended_answer_' . $i]);
                        $nmcq_answer_type = 'text';

                        $this->db->query("UPDATE sj_answers SET answer_text = ?, answer_type = ? WHERE answer_id = ?", array(
                            trim($nmcq_answer_text),
                            $nmcq_answer_type,
                            $query->row()->answer_id
                        ));
                    } else {
                        if(is_uploaded_file($_FILES['nmcq_answers_image']['tmp_name'][0])){
                            $nmcq_answer_text = $this->do_upload('answer', ($i == 0)?'nmcq_answers_image':'nmcq_answers_image_'.$i, 0);
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

                    $nmcq_answer_query = $this->db->query("SELECT answer_id, working_text FROM sj_answers WHERE question_id = ?", array($question_id));

                    // $workingContent = str_replace(",", " ", $post['input_working_content']);
                    // $arrIwc = explode(" ", $workingContent);
                    // $currentWorkingContent = '';

                    $working_countText = 0;
                    $working_countImage = 0;
                        
                    for($x=1 ; $x<count($arrIwc) ; $x++){                
                        ($arrIwc[$x] == 'text')?$working_countText++:$working_countImage++;
                    }

                    $workingCountSetText = $working_countText;
                    $workingCountSetImage = $working_countImage;

                    if($arrIwc[0] == 'text'){
                        $currentWorkingContent = trim($this->applyMathJaxFormat($post['input_working_text_0_'][0]));
                        $working_countText--;
                    } else if($arrIwc[0] == 'image'){
                        if(is_uploaded_file($_FILES['input_working_image_0_']['tmp_name'][0])){
                            $currentWorkingContent = $this->do_upload('working', 'input_working_image_0_', 0);
                            $working_countImage--;
                        } else {
                            $currentWorkingContent = $mcq_answer_query->row()->working_text;
                            $working_countImage--;
                        }
                    } else {
                        $currentWorkingContent = '';
                        $arrIwc[0] = 'text';
                    }

                    // update workings inside sj_answers
                    if($nmcq_answer_query->num_rows() == 1 || $nmcq_answer_query->num_rows() == 4){
                        $this->db->query("UPDATE sj_answers SET working_type = ?, working_text = ? WHERE answer_id = ?", array(
                            $arrIwc[0],
                            $currentWorkingContent,
                            $nmcq_answer_query->row()->answer_id
                        ));
                    }

                    // update question_content value

                    if(count($arrIwc) > 1){
                        $question_content_sql = "UPDATE `sj_questions` SET `question_content` = '1' WHERE `question_id` = " .$this->db->escape($question_id);
                        $query = $this->db->query($question_content_sql);
                    }

                    $working_content = $this->model_question->get_working_content_from_id($question_id);

                    // delete previous unused question content
                    if(count($working_content) > count($arrIwc)-1){
                        $delete_content = count($arrIwc) + 1;

                        $delete_sql = "DELETE FROM `sj_question_content` WHERE `content_order` >= " .$delete_content." AND `question_id` = " .$question_id." AND `content_section` = 'working'";
                        $query = $this->db->query($delete_sql);
                    }
                    
                    for($x=1 ; $x<count($arrIwc) ; $x++){                
                        
                        if(count($working_content) > 0 && $x <= count($working_content)){
                            foreach($working_content as $row){

                                // update question fields
                                if($row->content_order == $x+1){
                                    if($arrIwc[$x] == 'text'){
                                        $data = [
                                            'question_id' => $question_id,
                                            'content_section' => 'working',
                                            'content_type' => 'text',
                                            'content_order' => $x+1, 
                                            'content_name' => trim($this->applyMathJaxFormat($post['input_working_text_0_'][$workingCountSetText-$working_countText]))
                                        ]; 
                                        $this->db->where('content_id', $row->content_id);
                                        $this->db->update('sj_question_content', $data);

                                        $working_countText--;
                                    } else {
                                        if(is_uploaded_file($_FILES['input_working_image']['tmp_name'][$workingCountSetImage-$working_countImage])){
                                            $data = [
                                                'question_id' => $question_id,
                                                'content_section' => 'working',
                                                'content_type' => 'image',
                                                'content_order' => $x+1, 
                                                'content_name' => $this->do_upload('working', 'input_working_image_0_', ($workingCountSetImage-$working_countImage))
                                            ];   
                                            $this->db->where('content_id', $row->content_id);
                                            $this->db->update('sj_question_content', $data);   
                                        }
                                                        
                                        $working_countImage--;
                                    }
                                } 
                            }
                        } else {
                        
                            //insert new question content 1st question content
                            if($arrIwc[$x] == 'text'){                        
                                $data = [
                                    'question_id' => $question_id,
                                    'content_section' => 'working',
                                    'content_type' => 'text',
                                    'content_order' => $x+1, 
                                    'content_name' => trim($this->applyMathJaxFormat($post['input_working_text_0_'][$workingCountSetText-$working_countText]))
                                ]; 
                                $this->db->insert('sj_question_content', $data);

                                $working_countText--;
                            }else{
                                if(is_uploaded_file($_FILES['input_working_image']['tmp_name'][$workingCountSetImage-$working_countImage])){
                                    $data = [
                                        'question_id' => $question_id,
                                        'content_section' => 'working',
                                        'content_type' => 'image',
                                        'content_order' => $x+1, 
                                        'content_name' => $this->do_upload('working', 'input_working_image_0_', ($workingCountSetImage-$working_countImage))
                                    ];   

                                    $this->db->insert('sj_question_content', $data);   
                                }
                                                
                                $working_countImage--;
                            } 
                        }
                        
                        // $this->db->insert('sj_question_content', $data);
                    }
    
                } else if ($question_type_id == 3) { // true & false

                    if($post['true_false_answer_'.$i] == 1){
                        $answer = 'True';
                    }else{
                        $answer = 'False';
                    }

                    $insert_answer = [
                            'question_id' => $ques_id,
                            'answer_type' => 'text',
                            'answer_text' => $answer
                    ];

                    $this->db->insert('sj_answers', $insert_answer);
                    $first_answer_id = $this->db->insert_id();

                    $this->db->insert('sj_correct_answer', array(
                        'question_id' => $ques_id,
                        'answer_id'   => $first_answer_id
                    ));

                } else if ($question_type_id == 5) { // FITB with option
                    
                    for($ans=0 ; $ans<count($post['input_answer_fb_open_'.$i]) ; $ans++){
                        $data = [
                            'question_id' => $ques_id, 
                            'answer_group' => $ans+1,                               
                            'answer_type' => 'text',
                            'answer_text' => $post['input_answer_fb_open_'.$i][$ans]
                        ];
                        
                        $this->db->insert('sj_answers', $data);
                        $first_answer_id = $this->db->insert_id();

                        $this->db->insert('sj_correct_answer', array(
                            'question_id' => $ques_id,
                            'answer_id'   => $first_answer_id
                        ));
                        
                    }

                    for($ans=0 ; $ans<count($post['input_answer_fb_distractor_'.$i]) ; $ans++){
                        $data = [
                            'question_id' => $ques_id, 
                            'answer_group' => 0,                               
                            'answer_type' => 'text',
                            'answer_text' => $post['input_answer_fb_distractor_'.$i][$ans]
                        ];
                        
                        $this->db->insert('sj_answers', $data);
                        
                    }


                } else if ($question_type_id == 6) { // FITB without option                    

                    $count_answer_fb = intval($post['count_answer_fb_'.$i])-1;
                    
                    for($ans=1 ; $ans<=$count_answer_fb ; $ans++){
                        
                        $answer_fb = $post['input_answer_fb_open_'.$i.'_'.$ans];

                        for($option=0 ; $option<count($answer_fb) ; $option++){

                            $answer_group = $this->model_question->get_answer_content_from_answer_group($question_id, $ans);

                            // if(count($answer_group) > count($answer_fb)){
                            //     $delete_content = count($arrIac) + 1;
        
                            //     $delete_sql = "DELETE FROM `sj_question_header` WHERE  `header_order` >= " .$delete_content." AND `question_id` = " .$question_id." AND `header_type` = 'article'";
                            //     $query = $this->db->query($delete_sql);
                            // }

                            if(count($answer_group) > 0 && ($option < count($answer_group))){

                                $data = [
                                    'question_id' => $question_id,
                                    'answer_group' => $ans,
                                    'answer_type' => 'text',
                                    'answer_text' => $post['input_answer_fb_open_'.$i.'_'.$ans][$option]
                                ]; 
                                $this->db->where('answer_id', $answer_group[$option]->answer_id);
                                $this->db->update('sj_answers', $data);
                                
                            } else {
                                $data = [
                                    'question_id' => $question_id,
                                    'answer_group' => $ans,
                                    'answer_type' => 'text',
                                    'answer_text' => $post['input_answer_fb_open_'.$i.'_'.$ans][$option]
                                ];
                                
                                $this->db->insert('sj_answers', $data);                                
                                
                                $answer_id = $this->db->insert_id();
                                $this->db->insert('sj_correct_answer', array(
                                    'question_id' => $question_id,
                                    'answer_id'   => $answer_id
                                )); 
                            }   
                        }  
                    }

                } else if ($question_type_id == 7) { // FITB with unique option
                    $count_answer_fb = intval($post['count_answer_fb_'.$i])-1;                        
                    for($ans=1 ; $ans<=$count_answer_fb ; $ans++){
                        
                        $answer_fb = $post['input_answer_fb_open_'.$i.'_'.$ans];

                        for($option=0 ; $option<count($answer_fb) ; $option++){

                            $data = [
                                'question_id' => $ques_id,
                                'answer_group' => $ans,
                                'answer_type' => 'text',
                                'answer_text' => $post['input_answer_fb_open_'.$i.'_'.$ans][$option]
                            ];
                            
                            $this->db->insert('sj_answers', $data);                                

                            if($option == 0){
                                $first_answer_id = $this->db->insert_id();
                                $this->db->insert('sj_correct_answer', array(
                                    'question_id' => $ques_id,
                                    'answer_id'   => $first_answer_id
                                ));
                            }

                        }                                                    
                        
                    }
                } else if ($question_type_id == 8) { // mcq multiple choice

                    $mcq_type = $post['answer_type_mcq_' . $i];
    
                    if($mcq_type == 'text'){
    
                        $insert_mcq_answers = array(
                            array(
                                'question_id' => $ques_id,
                                'answer_type' => 'text',
                                'answer_text' => $this->applyMathJaxFormat($post['mcq_answers_' . $i][0])
                            ),
                            array(
                                'question_id' => $ques_id,
                                'answer_type' => 'text',
                                'answer_text' => $this->applyMathJaxFormat($post['mcq_answers_' . $i][1])
                            ),
                            array(
                                'question_id' => $ques_id,
                                'answer_type' => 'text',
                                'answer_text' => $this->applyMathJaxFormat($post['mcq_answers_' . $i][2])
                            ),
                            array(
                                'question_id' => $ques_id,
                                'answer_type' => 'text',
                                'answer_text' => $this->applyMathJaxFormat($post['mcq_answers_' . $i][3])
                            )
                        );
    
                        $this->db->insert_batch('sj_answers', $insert_mcq_answers);
                        $first_answer_id = $this->db->insert_id();
    
                    }else{
    
                        $data = [
                            'question_id' => $ques_id,
                            'answer_type' => 'image',
                            'answer_text' => $this->do_upload('answer', 'mcq_answers_image_'.$i, 0)
                        ];
                        $this->db->insert('sj_answers', $data);
                        $first_answer_id = $this->db->insert_id();
    
    
                        $data = [
                            'question_id' => $ques_id,
                            'answer_type' => 'image',
                            'answer_text' => $this->do_upload('answer', 'mcq_answers_image_'.$i, 1)
                        ];
                        $this->db->insert('sj_answers', $data);
    
    
                        $data = [
                            'question_id' => $ques_id,
                            'answer_type' => 'image',
                            'answer_text' => $this->do_upload('answer', 'mcq_answers_image_'.$i, 2)
                        ];
                        $this->db->insert('sj_answers', $data);
    
    
                        $data = [
                            'question_id' => $ques_id,
                            'answer_type' => 'image',
                            'answer_text' => $this->do_upload('answer', 'mcq_answers_image_'.$i, 3)
                        ];
                        $this->db->insert('sj_answers', $data);                    
    
                    }
    
                    
                    $mcq_correct_answer = $post['mcq_correct_answer_multiple_' . $i];

                    for($ans=0 ; $ans<count($mcq_correct_answer) ; $ans++){
                        $correct_answer_id = $first_answer_id + $mcq_correct_answer[$ans] - 1;
                        $this->db->insert('sj_correct_answer', array(
                            'question_id' => $ques_id,
                            'answer_id'   => $correct_answer_id
                        ));
                    }

                }

                $this->update_question_view($question_id);
                $this->update_total_difficulty($question_id);
                $this->update_question_tag($question_id, $post['tagsinput_' . $i]);
                
            }


            // $this->db->trans_complete();
            return $this->db->affected_rows();
           

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
       
        
    }
    
?>