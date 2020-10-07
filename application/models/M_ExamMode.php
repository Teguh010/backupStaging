<?php

    class M_ExamMode extends CI_Model {        
    
        function __construct(){
            parent::__construct();
            $this->load->model('Model_question', 'm_question');
        }

        public function get_strategy_list($subject_id = NULL){   
            if(empty($subject_id) === false){
                $this->db->select('*')->from('sj_strategy a')->where('a.subject_type='.$this->db->escape($subject_id))->order_by('RAND ()');
            }else {
                $this->db->select('*')->from('sj_strategy a')->order_by('a.id', 'ASC');
            }           
            return $this->db->get()->result_array();
        }

        function getLevel($level_id = NULL){
            if(empty($level_id) === false){
                return $this->db->get_where('sj_levels', ['level_id' => $level_id])->row_array();
            }else {
                return $this->db->get('sj_levels')->result();
            }
        }

        function readQuestion($id, $requirement_id, $level, $subject, $queType){
            if($id !== ''){
                return $this->db->get_where('sj_generate_questions', ['id' => $id])->row_array();
            }else if($requirement_id !== ''){
                return $this->db->get_where('sj_generate_questions', ['requirement_id' => $requirement_id])->result();
            }else{
                $sql = "SELECT a.question_id, LEFT(a.question_text, 160) as question_text, a.level_id, a.subject_type FROM sj_questions a ";
                $sql .= "WHERE a.level_id='$level' AND a.subject_type='$subject' ";
                if($queType == 1){
                    $sql .= "AND a.question_type_id IN (1,4) ";
                }else if($queType == 1){
                    $sql .= "AND a.question_type_id IN (1,2) ";
                }
                $sql .= "AND a.disabled=0 AND a.sub_question='A' ORDER BY RAND() LIMIT 500";
                $query = $this->db->query($sql);
                return $query->result();
            }
        }

        function getHeaderEdit($id){
            $row = $this->db->get_where('sj_generate_questions', ['id' => $id])->row_array();
            $question_id = $row['question_id'];
            $reqSubstrand = $row['substrand_req'];
            $reqTopic = $row['topic_req'];
            $reqStrategy = $row['strategy_req'];
            $sql = "SELECT a.subject_type, b.name FROM sj_questions a, sj_subject b WHERE a.subject_type=b.id AND a.question_id='$question_id' ";
            $row = $this->db->query($sql)->row();
            if(isset($row)){
                $result['reqSubject'] = $row->name;
                $result['reqSubject'] = str_replace('Primary', '', $result['reqSubject']);
                $result['reqSubject'] = str_replace('Secondary', '', $result['reqSubject']);
            }            
            if($reqSubstrand !== 'all' && $reqSubstrand !== ''){
                $row = $this->db->get_where('sj_substrands', ['id' => $reqSubstrand])->row_array();
                $result['reqSubstrand'] = $row['name'];
            }else{
                $result['reqSubstrand'] = 'Any Strand';
            }

            if($reqTopic !== 'all' && $reqTopic !== ''){
                $row = $this->db->get_where('sj_categories', ['id' => $reqTopic])->row_array();
                $result['reqTopic'] = $row['name'];
            }else{                
                $result['reqTopic'] = 'Any Topic';
            }

            if($reqStrategy !== 'all' && $reqStrategy !== ''){
                $row = $this->db->get_where('sj_strategy', ['id' => $reqStrategy])->row_array();
                $result['reqStrategy'] = $row['name'];
            }else{               
                $result['reqStrategy'] = 'Any Strategy';
            }

            $row = $this->db->get_where('sj_questions', ['question_id' => $question_id])->row_array();            
            if($row['difficulty'] == 1) $result['reqDifficulty'] = '1 Mark, Easy';
            if($row['difficulty'] == 2) $result['reqDifficulty'] = '2 Marks, Normal';
            if($row['difficulty'] == 3) $result['reqDifficulty'] = '3 Marks, Hard';
            if($row['difficulty'] == 4 || $row['difficulty'] == 5) $result['reqDifficulty'] = '4 Marks, Genius';            

            return $result;
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

        function generateEditQuestion($start, $getData){
            $levelID        = $getData['level'];
            $subjectID      = $getData['subject'];
            $query = $this->db->get_where('sj_levels', ['level_id' => $levelID, 'subject_type' => $subjectID])->row_array();
			$level_name = $query['level_name'];
            $reqSubstrand   = $getData['substrand'];
            $reqTopic       = $getData['topic'];
            $reqHeuristic   = $getData['heuristic'];
            $reqStrategy    = $getData['strategy'];            
            $queType        = $getData['que_type'];
            $reqDifficulty  = $getData['difficulties'];
            if($reqDifficulty >= 0 && $reqDifficulty < 30) {
                $difficulties = array(1,2);
            } else if($reqDifficulty >= 30 && $reqDifficulty < 60) {
                $difficulties = array(1,2,3,4,5);
            } else {
                $difficulties = array(4,5);
            }
            $reqDifficulty  = implode(",", $difficulties);
            $list_question = $this->db->get_where('sj_generate_questions', ['requirement_id' => $this->session->userdata('requirementId')])->result_array();
            $question_id = array();
            foreach($list_question as $row) {
                $question_id[] = $row['question_id'];
            }
            $question_id = implode(",", $question_id);
            $sql  = "SELECT a.question_id, a.question_text, a.level_id, a.subject_type, a.difficulty, a.graphical, 
                        b.branch_image_url, c.name AS topic_name, d.name AS substrand_name, e.name as strategy_name FROM sj_questions a 
                        JOIN sj_branch b ON a.branch_name = b.branch_name 
                        JOIN sj_categories c ON a.topic_id = c.id 
                        JOIN sj_substrands d ON c.substrand_id = d.id 
                        LEFT JOIN sj_strategy e ON a.strategy_id = e.id  
                        WHERE a.level_id='$levelID' AND a.subject_type='$subjectID' AND a.disabled=0 AND a.sub_question='A' 
                        AND a.difficulty IN ($reqDifficulty) AND a.question_id NOT IN ($question_id) ";

            if($reqSubstrand !== 'all' && $reqSubstrand !== ''){
                $row = $this->db->get_where('sj_substrands', ['id' => $reqSubstrand])->row_array();
                $result['reqSubstrand'] = $row['name'];
            }else{
                $result['reqSubstrand'] = 'Any Strand';
            }

            if($reqTopic !== 'all' && $reqTopic !== ''){
                $sql .= "AND topic_id='$reqTopic' ";
                $row = $this->db->get_where('sj_categories', ['id' => $reqTopic])->row_array();
                $result['reqTopic'] = $row['name'];
            }else{
                if($reqSubstrand !== 'all' && $reqSubstrand !== ''){
                    $sql .= "AND topic_id IN (SELECT b.id FROM sj_categories b WHERE b.substrand_id='$reqSubstrand' ORDER BY RAND()) ";
                }else{
                    $sql .= "AND topic_id IN (SELECT b.id FROM sj_categories b WHERE b.subject_type='$subjectID' ORDER BY RAND()) ";
                }
                $result['reqTopic'] = 'Any Topic';
            }            

            if($subjectID == 2){
                if($reqStrategy !== 'all' && $reqStrategy !== ''){							
                    $sql .= "AND strategy_id='$reqStrategy' ";
                }else{           
                    if($reqHeuristic !== 'all' && $reqHeuristic !== ''){								
                        $strategy_list = $this->m_question->get_worksheet_strategy('', '', $reqTopic, $reqHeuristic, $level_name);
                    }else{
                        $this->db->select('a.id as strategy_id')->from('sj_strategy a')->where('a.subject_type='.$this->db->escape($subjectID))->order_by('RAND()');
                        $strategy_list = $this->db->get()->result_array();
                    }
                    $strategy_id = array();
                    foreach($strategy_list as $strategy_item) {
                        $strategy_id[] = $strategy_item['strategy_id'];
                    }
                    $sql .= "AND strategy_id IN (0,".implode(",", $strategy_id).") ";                    
                }
            }

            if($queType == 1){
                $sql .= "AND question_type_id IN (1,4) ";
            }else if($queType == 2){
                $sql .= "AND question_type_id IN (1,2) ";
            }
                
            $result['total_rows'] = $this->db->query($sql." LIMIT 5000")->num_rows();            
            $sql .= "LIMIT 10 OFFSET $start";
            $result['data'] = $this->db->query($sql)->result();            
                
            return $result;
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

            if($subjectID == 1) {

                $sql  = "SELECT DISTINCT(a.question_id), a.question_text, a.level_id, a.subject_type, a.difficulty, a.graphical, 
                        b.branch_image_url, c.name AS topic_name, d.name AS substrand_name FROM sj_questions a 
                        JOIN sj_branch b ON a.branch_name = b.branch_name 
                        JOIN sj_categories c ON a.topic_id=c.id 
                        JOIN sj_substrands d ON c.substrand_id=d.id 
                        WHERE a.level_id='$levelID' AND a.subject_type='$subjectID' AND a.disabled=0 AND a.sub_question='A' 
                        AND question_id NOT IN ($question_id) AND question_type_id IN (1,2,4) 
                        AND concat('.', a.question_text, '.', c.name, '.', d.name, '.') LIKE '%$searchKeyword%' ";

            } else {

                $sql  = "SELECT DISTINCT(a.question_id), a.question_text, a.level_id, a.subject_type, a.difficulty, a.graphical, 
                        b.branch_image_url, c.name AS topic_name, e.name AS substrand_name FROM sj_questions a 
                        JOIN sj_branch b ON a.branch_name = b.branch_name 
                        JOIN sj_categories c ON a.topic_id=c.id 
                        JOIN sj_strategy d ON a.strategy_id=d.id 
                        JOIN sj_substrands e ON c.substrand_id=e.id 
                        JOIN sj_heuristics_relation f ON a.strategy_id=f.strategy_id 
                        JOIN sj_heuristics g ON f.heuristic_id=g.heuristic_id 
                        JOIN sj_substrategy h ON a.strategy_id=h.strategy_id 
                        WHERE a.level_id='$levelID' AND a.subject_type='$subjectID' AND a.disabled=0 AND a.sub_question='A' 
                        AND question_id NOT IN ($question_id) AND question_type_id IN (1,2,4) 
                        AND concat('.', a.question_text, '.', c.name, '.', d.name, '.', e.name, '.', g.heuristic_name, '.', h.name, '.') LIKE '%$searchKeyword%' ";

            }
            
            $result['total_rows'] = $this->db->query($sql." LIMIT 5000")->num_rows();
            $sql .= "LIMIT 10 OFFSET $start";
            $result['data'] = $this->db->query($sql)->result();            
            // array_debug($result['data']);exit;
            // $endtime = microtime(true);
            // $result['duration'] = $endtime - $starttime;
            return $result;            
        }

        function getMoreQuestions($start){
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
                $this->db->select('a.*, b.level_id, b.topic_id, b.subject_type, b.strategy_id, b.difficulty');
                $this->db->from('sj_generate_questions a');
                $this->db->join('sj_questions b', 'a.question_id=b.question_id');
                $this->db->where('a.id', $id);
                $row = $this->db->get()->row_array();
                $questionID = $row['question_id'];
                $levelID = $row['level_id'];            
                $topicID = $row['topic_id'];
                $strategyID = $row['strategy_id'];
                $subjectID = $row['subject_type'];
                $difficulty = $row['difficulty'];
                $reqSubstrand = $row['substrand_req'];
                $reqTopic = $row['topic_req'];
                $reqStrategy = $row['strategy_req'];
                $reqDifficulty = $row['difficulty_req'];
                $date_created = date('Y-m-d H:i:s');
                $list_question = $this->db->get_where('sj_generate_questions', ['requirement_id' => $this->session->userdata('requirementId')])->result_array();
                $question_id = array();
                foreach($list_question as $row) {
                    $question_id[] = $row['question_id'];
                }
                $sql  = "SELECT a.question_id, a.question_text, a.level_id, a.subject_type, a.difficulty, a.graphical, 
                            b.branch_image_url, c.name AS topic_name, d.name AS substrand_name, e.name as strategy_name FROM sj_questions a 
                            JOIN sj_branch b ON a.branch_name = b.branch_name 
                            JOIN sj_categories c ON a.topic_id = c.id 
                            JOIN sj_substrands d ON c.substrand_id = d.id 
                            LEFT JOIN sj_strategy e ON a.strategy_id = e.id  
                            WHERE a.level_id='$levelID' AND a.subject_type='$subjectID' AND a.disabled=0 AND a.sub_question='A' 
                            AND a.difficulty IN ($reqDifficulty) AND a.question_id NOT IN (".implode(",", $question_id).") ";

                if($reqSubstrand !== 'all' && $reqSubstrand !== ''){
                    $row = $this->db->get_where('sj_substrands', ['id' => $reqSubstrand])->row_array();
                    $result['reqSubstrand'] = $row['name'];
                }else{
                    $result['reqSubstrand'] = 'Any Strand';
                }

                if($reqTopic !== 'all' && $reqTopic !== ''){
                    $sql .= "AND topic_id='$reqTopic' ";
                    $row = $this->db->get_where('sj_categories', ['id' => $reqTopic])->row_array();
                    $result['reqTopic'] = $row['name'];
                }else{
                    if($reqSubstrand !== 'all' && $reqSubstrand !== ''){
                        $sql .= "AND topic_id IN (SELECT b.id FROM sj_categories b WHERE b.substrand_id='$reqSubstrand' ORDER BY RAND()) ";
                    }else{
                        $sql .= "AND topic_id IN (SELECT b.id FROM sj_categories b WHERE b.subject_type='$subjectID' ORDER BY RAND()) ";
                    }
                    $result['reqTopic'] = 'Any Topic';
                }

                if($reqStrategy !== 'all' && $reqStrategy !== ''){                          
                    $sql .= "AND strategy_id='$reqStrategy' ";
                    $row = $this->db->get_where('sj_strategy', ['id' => $reqStrategy])->row_array();
                    $result['reqStrategy'] = $row['name'];
                }else{
                    $strategy_list = $this->get_strategy_list($subjectID);
                    $strategy_id = array();
                    foreach($strategy_list as $strategy_item) {
                        $strategy_id[] = $strategy_item['id']; 
                    }
                    if(isset($strategy_id) && !empty($strategy_id) == TRUE) {
                        $sql .= "AND strategy_id IN (0,".implode(",", $strategy_id).") ";                
                    }

                    $result['reqStrategy'] = 'Any Strategy';
                }

                if($queType == 1){
                    $sql .= "AND question_type_id IN (1,4) ";
                }else if($queType == 2){
                    $sql .= "AND question_type_id IN (1,2) ";
                }
                
                $result['total_rows'] = $this->db->query($sql." LIMIT 1000")->num_rows();

                $sql .= "LIMIT 10 OFFSET $start";
                $result['data'] = $this->db->query($sql)->result();
                
                return $result;
            }
        }        

        function updateQuestion(){         
            $data = [
                'question_id' => $this->input->post('question_id'),
                'question_type' => $this->input->post('question_type')
            ];
            $this->db->where('id', $this->input->post('id'));
            $this->db->update('sj_generate_questions', $data);
            return $this->db->affected_rows();
        }

        function updateNumberOfQuestion(){
            $quesNumber = $this->input->post('question_number');
            $quesNumber2 = $this->input->post('old_number');
            $sql  = "SELECT * FROM sj_generate_questions WHERE requirement_id=".$this->session->userdata('requirementId')." ";
            if($quesNumber > $quesNumber2){
                $sql .= "AND question_number BETWEEN '$quesNumber2' AND '$quesNumber' ORDER BY question_number ASC";
            }else{
                $sql .= "AND question_number BETWEEN '$quesNumber' AND '$quesNumber2' ORDER BY question_number ASC";
            }
            
            $query = $this->db->query($sql);
            foreach($query->result() as $row){
                if($row->question_number == $quesNumber2){
                    $data = [
                        'question_number' => $quesNumber,
                        'date_created' => date('Y-m-d H:i:s')
                    ];                    
                }else if($quesNumber2 > $row->question_number){
                    $data = [
                        'question_number' => $row->question_number+1,
                        'date_created' => date('Y-m-d H:i:s')
                    ];
                }else if($quesNumber2 < $row->question_number){
                    $data = [
                        'question_number' => $row->question_number-1,
                        'date_created' => date('Y-m-d H:i:s')
                    ];
                }               
                $this->db->where('id', $row->id);
                $this->db->update('sj_generate_questions', $data);
            }
            
            return $this->db->affected_rows();
        }

        function deleteQuestion(){            
            $this->db->where('id', $this->input->post('id'));
            $this->db->delete('sj_generate_questions');
            return $this->db->affected_rows();
        }

        function regenerateAllQuestion($requirement_id){
            $row = $this->db->get_where('sj_worksheet_requirement', ['requirement_id' => $requirement_id])->row_array();
            $levelID = $row['level_ids'];
            $subjectID = $row['subject_type'];
            $query = $this->db->get_where('sj_levels', ['level_id' => $levelID, 'subject_type' => $subjectID])->row_array();
			$level_name = $query['level_name'];
            $substrandID = explode(",", $row['substrand_ids']);
            $topicID = explode(",", $row['topic_ids']);
            $heuristicID = explode(",", $row['heuristic_ids']);
            $strategyID = explode(",", $row['strategy_ids']);            
            $difficulties = explode(",", $row['difficulty']);            
            $quizTime = $row['quiz_time'];
            $numOfQuestion = $row['num_question'];
            $startQuestion = explode(",", $row['start_question']);
            $endQuestion = explode(",", $row['end_question']);
            $questionType = explode(",", $row['question_type']);
            $date_created = date('Y-m-d H:i:s');
            $i=1;
            for($j = 0 ; $j<count($substrandID) ; $j++){
                $sql  = "SELECT question_id FROM sj_questions ";
                $sql .= "WHERE level_id='$levelID' ";
                if($topicID[$j] !== 'all' && $topicID[$j] !== ''){
                    $sql .= "AND topic_id='$topicID[$j]' ";
                }else{
                    if($substrandID[$j] !== 'all' && $substrandID[$j] !== ''){
                        $sql .= "AND topic_id IN (SELECT b.id FROM sj_categories b WHERE b.substrand_id='$substrandID[$j]' ORDER BY RAND()) ";
                    }else{
                        $sql .= "AND topic_id IN (SELECT b.id FROM sj_categories b WHERE b.subject_type='$subjectID' ORDER BY RAND()) ";
                    }
				}
				if($strategyID[$j] !== 'all' && $strategyID[$j] !== ''){							
					$sql .= "AND strategy_id='$strategyID[$j]' ";
				}else{
                    if($heuristicID[$j] !== 'all' && $heuristicID[$j] !== ''){       
                        $this->db->select('DISTINCT(a.strategy_id) as id')->from('sj_heuristics_relation a')->where('a.heuristic_id='.$this->db->escape($heuristicID[$j]))->order_by('RAND()');
						$strategy_list = $this->db->get()->result_array();
                    }else{
                        $strategy_list = $this->get_strategy_list($subjectID);
                    }					
					$strategy_id = array();
					foreach($strategy_list as $strategy_item) {
					    $strategy_id[] = $strategy_item['id']; 
                    }
                    if(isset($strategy_id) && !empty($strategy_id) == TRUE) {
                        $sql .= "AND strategy_id IN (0,".implode(",", $strategy_id).") ";
                    }
                }                                               
                $sql .= "AND `disabled`=0 AND `sub_question`='A' ";
                if($questionType[$j] == 1){
                    $sql .= "AND question_type_id IN (1,4) ";
                }else if($questionType[$j] == 2){
                    $sql .= "AND question_type_id IN (1,2) ";
                }
                if($subjectID == 1) {

                    $difficulties = array(1,2,3,4,5);

                } else {

                    if($this->session->userdata('gen_difficulties_'.$j) >= 0 && $this->session->userdata('gen_difficulties_'.$j) < 30) {
                        $difficulties = array(1,2);
                    } else if($this->session->userdata('gen_difficulties_'.$j) >= 30 && $this->session->userdata('gen_difficulties_'.$j) < 60) {
                        $difficulties = array(1,2,3,4,5);
                    } else {
                        $difficulties = array(4,5);
                    }

                }
                $sql .= "AND difficulty IN (" . implode(",", $difficulties) . ") ORDER BY RAND() LIMIT ".($endQuestion[$j]-$startQuestion[$j]+1);
                $query = $this->db->query($sql);
                foreach($query->result() as $row){
                    $data = [
                        'question_id' => $row->question_id
                    ];
                    $this->db->where('requirement_id', $requirement_id);
                    $this->db->where('question_number', $i++);
                    $this->db->update('sj_generate_questions', $data);
                }
            }
            
            return $this->db->affected_rows();

        }

        function regenerateQuestionID($id, $questionType){              
            $this->db->select('a.*, b.level_id, b.topic_id, b.subject_type, b.strategy_id, b.difficulty');
            $this->db->from('sj_generate_questions a');
            $this->db->join('sj_questions b', 'a.question_id=b.question_id');
            $this->db->where('a.id', $id);
            $row = $this->db->get()->row_array();            
            $questionID = $row['question_id'];
            $levelID = $row['level_id'];
            $subjectID = $row['subject_type'];
            $query = $this->db->get_where('sj_levels', ['level_id' => $levelID, 'subject_type' => $subjectID])->row_array();
			$level_name = $query['level_name'];
            $topicID = $row['topic_id'];
            $strategyID = $row['strategy_id'];            
            $difficulty = $row['difficulty'];
            $reqSubstrand = $row['substrand_req'];
            $reqTopic = $row['topic_req'];
            $reqHeuristic = $row['heuristic_req'];
            $reqStrategy = $row['strategy_req'];
            $reqDifficulty = $row['difficulty_req'];
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
            $sql  = "SELECT question_id FROM sj_questions WHERE level_id='$levelID' AND subject_type='$subjectID' AND `disabled`=0 AND sub_question='A' ";            
            $sql .= "AND question_id NOT IN (".implode(",", $question_id).") ";

            if($reqTopic !== 'all' && $reqTopic !== ''){
                $sql .= "AND topic_id='$reqTopic' ";                            
            }else{
                if($reqSubstrand !== 'all' && $reqSubstrand !== ''){
                    $sql .= "AND topic_id IN (SELECT b.id FROM sj_categories b WHERE b.substrand_id='$reqSubstrand' ORDER BY RAND()) ";
                }else{
                    $sql .= "AND topic_id IN (SELECT b.id FROM sj_categories b WHERE b.subject_type='$subjectID' ORDER BY RAND()) ";
                }                                               
            }

            if($subjectID == 2){
                if($reqStrategy !== 'all' && $reqStrategy !== ''){							
                    $sql .= "AND strategy_id='$reqStrategy' ";
                }else{           
                    if($reqHeuristic !== 'all' && $reqHeuristic !== ''){								
                        $strategy_list = $this->m_question->get_worksheet_strategy('', '', $reqTopic, $reqHeuristic, $level_name);
                    }else{
                        $this->db->select('a.id as strategy_id')->from('sj_strategy a')->where('a.subject_type='.$this->db->escape($subjectID))->order_by('RAND()');
                        $strategy_list = $this->db->get()->result_array();
                    }
                    $strategy_id = array();
                    foreach($strategy_list as $strategy_item) {
                        $strategy_id[] = $strategy_item['strategy_id'];
                    }
                    $sql .= "AND strategy_id IN (0,".implode(",", $strategy_id).") ";                    
                }
            }

            if($questionType == 1){
                $sql .= "AND question_type_id IN (1,4) ";
            }else if($questionType == 2){
                $sql .= "AND question_type_id IN (1,2) ";
            }
            $sql_difficulty = "AND difficulty IN ($reqDifficulty) ";
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
                $sql_difficulty = "AND difficulty IN ($difficulty) ";
                $row = $this->db->query($sql.$sql_difficulty.$sql_limit)->row();
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

        
    }
?>