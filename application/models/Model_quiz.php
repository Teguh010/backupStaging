<?php

/**

 * *

 *  * Created by PhpStorm.

 *  * User: boonkhailim

 *  * Year: 2017

 *

 */



class Model_quiz extends CI_Model

{

    public function assign_student($worksheet_id, $assign_students) {

        $this->db->trans_start();

        $assigned_students = $this->get_assigned_list($worksheet_id);

        $assigned_students_ids = array();

        foreach ($assigned_students as $assigned) {

            $assigned_students_ids[] = $assigned->id;

        }

        $to_be_removed = array_diff($assigned_students_ids, $assign_students);

        $to_be_insert = array_diff($assign_students, $assigned_students_ids);

        // $this->db->query('DELETE FROM `sj_quiz` WHERE `worksheetId` = ?', array($worksheet_id));
        
        $subject_id = $this->model_worksheet->get_worksheet_subject_id($worksheet_id);
        
        $assigned_date = date('Y-m-d H:i:s');

        foreach ($to_be_removed AS $assigned_student_id) {
            $query = "UPDATE `sj_quiz` set `status` = 0, `assignedDate` = ? 
                      WHERE `worksheetId` = ? AND `assignedTo` = ? AND `status` = 1";
            $sql = $this->db->query($query, array($assigned_date, $worksheet_id, $assigned_student_id));
        }
        

        $data = array();

        foreach ($to_be_insert AS $assign_student_id) {

            $data_row = array();
            $data_row['worksheetId']  = $worksheet_id;
            $data_row['assignedTo']   = $assign_student_id;
            $data_row['assignedDate'] = $assigned_date;
            $data_row['status'] = '1';
            $data_row['subject_type'] = $subject_id;
            $data_row['branch_tag'] = BRANCH_TAG;
            $data_row['branch_code'] = BRANCH_ID;
            $data[] = $data_row;

        }



        if (count($data) > 0) {

            $this->db->insert_batch('sj_quiz', $data);

        }



        $this->db->trans_complete();



        return $this->db->trans_status();

    }

    public function assign_tutors($worksheet_id, $assign_tutors) {

        $this->db->trans_start();

        $assigned_tutors = $this->get_assigned_list($worksheet_id);

        $assigned_tutors_ids = array();

        foreach ($assigned_tutors as $assigned) {

            $assigned_tutors_ids[] = $assigned->id;

        }

        $to_be_removed = array_diff($assigned_tutors_ids, $assign_tutors);

        $to_be_insert = array_diff($assign_tutors, $assigned_tutors_ids);

        // $this->db->query('DELETE FROM `sj_quiz` WHERE `worksheetId` = ?', array($worksheet_id));
        
        $subject_id = $this->model_worksheet->get_worksheet_subject_id($worksheet_id);
        
        $assigned_date = date('Y-m-d H:i:s');

        foreach ($to_be_removed AS $assigned_tutor_id) {
            $query = "UPDATE `sj_admin_quiz` set `admin_status` = 0, `admin_assignedDate` = ? 
                      WHERE `admin_worksheetId` = ? AND `admin_assignedTo` = ? AND `admin_status` = 1";
            $sql = $this->db->query($query, array($assigned_date, $worksheet_id, $assigned_tutor_id));
        }
        

        $data = array();

        foreach ($to_be_insert AS $assign_tutor_id) {

            $data_row = array();
            $data_row['admin_worksheetId']  = $worksheet_id;
            $data_row['admin_assignedTo']   = $assign_tutor_id;
            $data_row['admin_assignedDate'] = $assigned_date;
            $data_row['admin_status'] = '1';
            $data_row['admin_subject_type'] = $subject_id;
            $data_row['admin_branch_tag'] = BRANCH_TAG;
            $data_row['admin_branch_code'] = BRANCH_ID;
            $data[] = $data_row;
        }

        if (count($data) > 0) {

            $this->db->insert_batch('sj_admin_quiz', $data);

        }

        $this->db->trans_complete();

        return $this->db->trans_status();

    }



    public function get_assigned_list($worksheetId) {

        if ($this->session->userdata('is_admin_logged_in') == 1) {
            
            $sql = "
                SELECT `id`, `username`, `fullname`
                FROM `sj_users` users
                WHERE `id` in 
                (SELECT `admin_assignedTo`
                FROM `sj_admin_quiz`
                WHERE `admin_worksheetId` = '".$worksheetId."' AND `admin_assignedTo` != 1 AND `admin_branch_code` = ".BRANCH_ID." AND `admin_status` = 1)
            ";

            $query = $this->db->query($sql);
            $returnArray = array();
            if ($query) {
                foreach ($query->result() As $tutor) {
                    $returnArray[] = $tutor;
                }
            }
            
            return $returnArray;

        } else {
            $userId = $this->session->userdata('user_id');
            $sql = "
                SELECT `users`.`id`, `username`, `level_name` 
                FROM `sj_users` users
                LEFT JOIN (SELECT `student_id`, `level_id` FROM `sj_student`) students
                ON `students`.`student_id` = `users`.`id`
                LEFT JOIN (SELECT `id`, `level_id`, `level_name` FROM `sj_levels`) levels
                ON `levels`.`id` = `students`.`level_id` 
                WHERE `users`.`id` in 
                (SELECT `assignedTo`
                FROM `sj_quiz`
                WHERE `worksheetId` = '".$worksheetId."' AND assignedTo != 1 AND `branch_code` = ".BRANCH_ID." AND `status` = 1)
                AND `users`.`id` in 
                (SELECT `student_id` FROM `sj_relationship` 
                WHERE `adult_id` = '".$userId."' AND `branch_tag` = '".BRANCH_TAG."')
            ";
            
            $query = $this->db->query($sql);
            $returnArray = array();
            if ($query) {
                foreach ($query->result() As $student) {
                    $returnArray[] = $student;
                }
            }
            
            return $returnArray;
        }

    }




    public function is_authorized_to_take_quiz($studentId, $quizId)

    {

        $sql = "SELECT count(`id`) AS count 

                FROM `sj_quiz`

                WHERE `assignedTo` = ? AND `id` = ?

        ";



        $query = $this->db->query($sql, array($studentId, $quizId));



        return ($query->row()->count == 1) ? true : false;

    }



    public function is_authorized_to_view_result($attemptId, $userId = NULL)

    {

        // if user ID is not passed, get it from PHP session

        if (!(isset($userId) && empty($userId) === false)) {

            $userId = $this->session->userdata('user_id');

        }

        $sql = "SELECT `quizId`  

                FROM `sj_quiz_attempt`

                WHERE `id` = ?

        ";



        $query = $this->db->query($sql, array($attemptId));

        if ($query->num_rows() == 1) {

            $quizId = $query->row()->quizId;

        } else {

            return false;

        }



        //check if the current user is the student attempting the quiz

        $sql = "SELECT count(`id`) AS count 

                FROM `sj_quiz`

                WHERE `assignedTo` = ? AND `id` = ?

        ";



        $query = $this->db->query($sql, array($userId, $quizId));

        if ($query->row()->count == 1) {

            return true;

        }



        //check if the current user is the tutor assigning the quiz to student

        $sql = "SELECT `worksheetId` 

                FROM `sj_quiz`

                WHERE `id` = ?

        ";



        $query = $this->db->query($sql, array($quizId));

        $worksheetId = $query->row()->worksheetId;



        $dbWhere = array(

            'worksheet_id' => $worksheetId,

            'created_by' => $userId

        );

        $this->db->where($dbWhere);

        $query = $this->db->get('sj_worksheet');



        return ($query->num_rows() == 1) ? true : false;



    }



    public function get_quiz_id_from_studentId_worksheetId($studentId, $worksheetId)

    {

        $sql = "SELECT `id`

                FROM `sj_quiz` 

                WHERE `assignedTo` = ? AND `worksheetId` = ?

        ";



        $query = $this->db->query($sql, array($studentId, $worksheetId));



        return $query->row()->id;

    }



    public function get_quiz_id_from_attempt_id($attemptId)

    {

        $sql = "SELECT `quizId`

                FROM `sj_quiz_attempt` 

                WHERE `id` = ?

        ";



        $query = $this->db->query($sql, array($attemptId));



        return $query->row()->quizId;

    }



    public function count_number_of_attempt($quizId)

    {

        $sql = "SELECT count(`id`) AS count

                FROM `sj_quiz_attempt` 

                WHERE `quizId` = ?

        ";



        $query = $this->db->query($sql, array($quizId));



        return $query->row()->count;

    }
    
    public function get_worksheet_question($worksheetId){
        $sql = "SELECT * FROM `sj_worksheet_questions` WHERE `worksheet_id` = ".$worksheetId." AND `branch_code` = ".BRANCH_ID."";
        $query = $this->db->query($sql);
        return $query;
    }




    public function get_attempt_ids($quizId, $date_range = array())

    {
        $date_compare = '';

        if(!empty($date_range) && sizeof($date_range) > 0 ){
            $date_compare = " AND  CONVERT(CONCAT(YEAR(completedDateTime), LPAD(MONTH(completedDateTime), 2, '0'),LPAD(DAY(completedDateTime), 2, '0')),UNSIGNED INTEGER) >=".$this->db->escape($date_range[0]).
                            " AND  CONVERT(CONCAT(YEAR(completedDateTime), LPAD(MONTH(completedDateTime), 2, '0'),LPAD(DAY(completedDateTime), 2, '0')),UNSIGNED INTEGER) <".$this->db->escape($date_range[1]);
        }

        $sql = "SELECT `id`

                FROM `sj_quiz_attempt`

                WHERE `quizId` = ? 
                AND `branch_code` = ".BRANCH_ID."
                ".$date_compare."
                ORDER BY `attemptDateTime` DESC";

        $query = $this->db->query($sql, array($quizId));

        $returnArray = array();

        foreach ($query->result() as $row) {

            $returnArray[] = $row->id;

        }

        return $returnArray;

    }

    public function get_attempt_id_from_quiz($quizId,$limit="1",$sort_by="DESC")
    {
        $sql = "SELECT `id` 
                FROM `sj_quiz_attempt`
                WHERE `quizId` = ?
                ORDER BY `attemptDateTime` ".$sort_by."
                LIMIT ".$limit;

        $query = $this->db->query($sql, $quizId);

        return $query->num_rows()>0?$query->row()->id:0;
    }

    public function get_last_attempt_date($quizId)

    {

        $sql = "SELECT `attemptDateTime` 

                FROM `sj_quiz_attempt`

                WHERE `quizId` = ?

                ORDER BY `attemptDateTime` DESC

                LIMIT 1

        ";



        $query = $this->db->query($sql, array($quizId));



        return $query->num_rows()>0?$query->row()->attemptDateTime:FALSE;

    }


    public function upload_student_photo($data){
        //user upload picture
    }
    
    public function save_student_attempt($quizId, $userAnswer, $attemptDateTime, $userOcr = NULL, $userImg = NULL, $userOCRDIGIT = NULL, $multiLine = 0, $userSvg = NULL, $userOcrQuestion = NULL, $userSvgQuestion = NULL)
    {

        //insert a quiz attempt record , to obtain ID

        $completedDateTime = date('Y-m-d H:i:s');

        $insertArray = array(

            'quizId' => $quizId,

            'attemptDateTime' => $attemptDateTime,

            'branch_code' => BRANCH_ID,

            'completedDateTime' => $completedDateTime

        );

        if(isset($userOcr) && !empty($userOcr) == false){
            $userOcr = 'NULL';
        }

        if(isset($userImg) && !empty($userImg) == false){
            $userImg = 'NULL';
        }

        if(isset($userOCRDIGIT) && !empty($userOCRDIGIT) == false){
            $userOCRDIGIT = 'NULL';
        }

        if(isset($multiLine) && !empty($multiLine) == false){
            $userOCRDIGIT = 0;
        }

        
        $query = $this->db->insert('sj_quiz_attempt', $insertArray);
        

        if ($query) {

            $attemptId = $this->db->insert_id();


            //insert user attempt answer using above ID

            $attemptIDs = array();

            $attemptIDs['quizAttemptID'] = $attemptId;

            $attemptIDs['questionAttemptIDs'] = array();

            $quesNum = 1;
            
            foreach ($userAnswer AS $index => $answer) {

                $insertArray2 = array(

                    'attemptId' => $attemptId,

                    'questionNo' => $quesNum,

                    'answerId' => $answer,

                    'remark' => '',

                    'branch_code' => BRANCH_ID,

                    'ocr' => $userOcr[$index],

                    'img' => $userImg[$index],

                    'ocrDigitize' => $userOCRDIGIT[$index],

                    'multiLine' => (is_numeric($multiLine[$index]) ? $multiLine[$index] :  0),

                    'svg' => $userSvg[$index],

                    'ocr_question_image' => $userOcrQuestion[$index],

                    'svg_question_image' => $userSvgQuestion[$index],

                );

                $quesNum++;

                $query2 = $this->db->insert('sj_quiz_attempt_answer', $insertArray2);

                $attemptIDs['questionAttemptIDs'][] = $this->db->insert_id();

            }

        } else {

            return false;

        }



        return ($query && $query2) ? $attemptId : false;



    }



    public function save_student_working_image($attemptID, $imagePath, $workingImage)

    {

        //if existed, dont need insert, the reference will be the same

        if ($this->student_working_image_exist($attemptID, $imagePath)) {

            return true;

        } else {

            $insert_array = array(

                'attemptID' => $attemptID,

                'image' => $imagePath,

                'type' => $workingImage

            );

            $query = $this->db->insert('sj_quiz_attempt_working', $insert_array);

            return ($query) ? true : false;

        }

    }



    private function student_working_image_exist($attemptID, $imagePath)

    {

        $sql = "SELECT count(`id`) AS IDCount

                FROM `sj_quiz_attempt_working`

                WHERE `attemptID` = ? AND `image` = ?";

        $query = $this->db->query($sql, array($attemptID, $imagePath));

        return ($query->row()->IDCount > 0) ? true : false;

    }


    public function get_attempt_ocr_img($attemptId){
        $sql = "SELECT `ocr`, `img`,`questionNo`, `multiLine` , `svg`, `svg_tutor` , `svg_tutor_bg` , `svg_tutor_img`, `ocr_svg_tutor` , `ocr_svg_tutor_bg` , `ocr_svg_tutor_img`

                FROM `sj_quiz_attempt_answer`

                WHERE `attemptId` = ? AND `branch_code` = ".BRANCH_ID."
        ";

        $query = $this->db->query($sql, array($attemptId));

        $userData = array();

        foreach ($query->result() AS $row) {

            $userData[$row->questionNo - 1]["ocr"] = $row->ocr;
            $userData[$row->questionNo - 1]["img"] = $row->img;
            $userData[$row->questionNo - 1]["multiLine"] = $row->multiLine;
            $userData[$row->questionNo - 1]["svg"] = $row->svg;
            $userData[$row->questionNo - 1]["svg_tutor"] = $row->svg_tutor;
            $userData[$row->questionNo - 1]["svg_tutor_bg"] = $row->svg_tutor_bg;
            $userData[$row->questionNo - 1]["svg_tutor_img"] = $row->svg_tutor_img;
            $userData[$row->questionNo - 1]["ocr_svg_tutor"] = $row->ocr_svg_tutor;
            $userData[$row->questionNo - 1]["ocr_svg_tutor_bg"] = $row->ocr_svg_tutor_bg;
            $userData[$row->questionNo - 1]["ocr_svg_tutor_img"] = $row->ocr_svg_tutor_img;
        }

        return $userData;
    }

    public function get_attempt_ocr_svg($attemptId,$questionNo,$column){
        $sql = "SELECT `svg` , `img`, `svg_tutor` , `svg_tutor_img`, `svg_question_image`
                FROM `sj_quiz_attempt_answer`
                WHERE `attemptId` = ? AND questionNo = ? AND `branch_code` = ".BRANCH_ID."
        ";

        $query = $this->db->query($sql, array($attemptId , $questionNo));

        $userData = "";

        foreach ($query->result() AS $row) {

            return $row->$column;
        }

        return $userData;
    }

    public function save_working($attemptId, $questionNo, $svg_tutor, $ocr_tutor, $save_to) {

        if($save_to=="svg_tutor_bg_group") {
            $attemptId = $attemptId;
            $quizId = $this->get_quiz_id_from_attempt_id($attemptId);
            $worksheetId = $this->get_worksheetid_from_quiz_id($quizId);
            $allQuiz = $this->get_quiz_from_worksheetid($worksheetId);
            foreach ($allQuiz as $quiz) {
                $allAttempts = $this->get_attempt_ids($quiz->id);
                foreach ($allAttempts as $value) {
                    $data = [
                        'uploaded_video_explanation_group' => $post['uploaded_video']
                    ];
                    $this->db->where('attemptId', $value);
                    $this->db->where('questionNo', $post['questionNo']);
                    $this->db->update('sj_quiz_attempt_answer', $data);

                    $sql = "UPDATE `sj_quiz_attempt_answer`
                    SET `".$save_to."` = '".$svg_tutor."', `ocr_".$save_to."` = '".$ocr_tutor."'
                    WHERE `attemptId` = '".$value."' AND `questionNo` = '".$questionNo."'
                     ";
                    $this->db->query($sql);
                }
            }
        } else {
            $sql = "UPDATE `sj_quiz_attempt_answer`
                    SET `".$save_to."` = '".$svg_tutor."', `ocr_".$save_to."` = '".$ocr_tutor."'
                    WHERE `attemptId` = '".$attemptId."' AND `questionNo` = '".$questionNo."'
            ";
            $this->db->query($sql);
        }
        $response['msg'] = 'success';
        return $response;
    }
    
    public function get_attempt_answer($attemptId)

    {

        $sql = "SELECT `answerId`, `questionNo`

                FROM `sj_quiz_attempt_answer`

                WHERE `attemptId` = ? AND `branch_code` = ".BRANCH_ID."
        ";



        $query = $this->db->query($sql, array($attemptId));



        $userAnswer = array();

        foreach ($query->result() AS $row) {

            $userAnswer[$row->questionNo - 1] = $row->answerId;

        }



        return $userAnswer;

    }



    public function get_attempt_score_array($attemptId)

    {

        $sql = "SELECT `score`, `questionNo`

                FROM `sj_quiz_attempt_answer`

                WHERE `attemptId` = ?

        ";



        $query = $this->db->query($sql, array($attemptId));

        $userScore = array();

        foreach ($query->result() AS $row) {

            $userScore[$row->questionNo - 1] = $row->score;

        }



        return $userScore;

    }



    public function save_score_for_answer($attemptId, $questionNo, $score) {

        $sql = "UPDATE `sj_quiz_attempt_answer`

                SET `score` = $score

                WHERE `attemptId` = $attemptId AND `questionNo` = $questionNo

        ";



        if($this->db->query($sql) === TRUE) {

            return TRUE;

        } else {

            return FALSE;

        }

    }



    // [WM] 8Aug17: THe following is obsolete, commenting

    // ===================== GET SUBJECTIVE ANSWER ====================

//    public function get_attempt_answer_textId($attemptId)

//    {

//        $sql = "SELECT `answerId`, `questionNo`

//              FROM `sj_quiz_attempt_answer_subjective`

//              WHERE `attemptId` = ?

//      ";

//

//        $query = $this->db->query($sql, array($attemptId));

//

//        $userAnswerTextId = array();

//        foreach ($query->result() AS $row) {

//            $userAnswerTextId[$row->questionNo - 1] = $row->answerId;

//        }

//

//        return $userAnswerTextId;

//    }

    // ================================================================



    public function get_num_correct_answer($attemptId)

    {



    }



    public function get_student_attempt($quizId)

    {

        $sql = "SELECT * 

                FROM `sj_quiz_attempt`

                WHERE `quizId` = ?

        ";



        $query = $this->db->query($sql, array($quizId));



        $returnArray = array();

        foreach ($query->result() as $row) {

            $returnArray[] = $row;

        }



        return $returnArray;

    }



    public function get_attempt_time_taken($attemptId)

    {

        $sql = "SELECT `attemptDateTime`, `completedDateTime`

                FROM `sj_quiz_attempt`

                WHERE `id` = ?";

        $query = $this->db->query($sql, array($attemptId));

        $startTime = strtotime($query->row()->attemptDateTime);

        $endTime = strtotime($query->row()->completedDateTime);

        $minutesTaken = floor(($endTime - $startTime) / 60);

        $secondsTaken = ($endTime - $startTime) % 60;

        return $minutesTaken . " minutes, " . $secondsTaken . " seconds";

    }



    public function get_attempt_has_marked($attemptId)

    {

        $sql = "SELECT `marked`

                FROM `sj_quiz_attempt`

                WHERE `id` = ?";

        $query = $this->db->query($sql, array($attemptId));



        return $query->row()->marked == 1;

    }
    
    public function get_total_no_attempt_question($attemptId)
    {
        $sql = "SELECT COUNT(*) as TOTAL FROM `sj_quiz_attempt_answer` WHERE `attemptId` = ".$attemptId." AND `branch_code` = ".BRANCH_ID."";
        $query = $this->db->query($sql);
        return $query->row()->TOTAL;

    }
    
    public function get_no_attempt_question($attemptId)
    {
        $sql = "SELECT COUNT(*) as TOTAL FROM `sj_quiz_attempt_answer` WHERE `attemptId` = ".$attemptId." AND `score` > 0 AND `branch_code` = ".BRANCH_ID."";
        $query = $this->db->query($sql);
        return $query->row()->TOTAL;
    }




    public function get_attempt_score($attemptId)

    {

        $sql = "SELECT `attemptScore`

                FROM `sj_quiz_attempt`

                WHERE `id` = ?";

        $query = $this->db->query($sql, array($attemptId));



        return $query->row()->attemptScore;

    }



    public function save_attempt_score($attemptId, $score) {

        $sql = "UPDATE `sj_quiz_attempt`

                SET `attemptScore` = $score, `marked` = 1

                WHERE `id` = $attemptId

        ";



        if($this->db->query($sql) === TRUE) {

            return TRUE;

        } else {

            return FALSE;

        }

    }
    
    public function update_remark($attemptId, $questionNo, $remark) {

        $sql = "UPDATE `sj_quiz_attempt_answer`
                SET `remark` = '".$remark."'
                WHERE `attemptId` = '".$attemptId."' AND `questionNo` = '".$questionNo."'
        ";

        if($this->db->query($sql) === TRUE) {
            return $remark;
        } else {
            return $remark;
        }
    }
    
    public function get_remarks_from_attempt_id($attemptId, $questionNo) {
        $sql = "SELECT `remark` FROM `sj_quiz_attempt_answer` WHERE `attemptId` = ".$attemptId." AND `questionNo` = ".$questionNo."";
    
        $query = $this->db->query($sql);
        
        if($query->num_rows() > 0){
            $remark = $query->row()->remark;
        } else {
            $remark = ' ';
        }
        return $remark;

        
    }
    
    public function get_tutor_marked_from_attempt_id($attemptId, $questionNo) {
		$sql = "SELECT `tutor_marked` FROM `sj_quiz_attempt_answer` WHERE `attemptId` = ".$attemptId." AND `questionNo` = ".$questionNo."";
	
		$query = $this->db->query($sql);
		
		return $query->row()->tutor_marked;

		
    }
    

    function getAnswerList($question_id, $answer_group){
        $sql = "SELECT * FROM sj_answers WHERE question_id='$question_id' AND answer_group='$answer_group' ORDER BY RAND()";
        $result = $this->db->query($sql)->result();

        return $result;
    }


    function getAnswerOption($question_id){
        $sql = "SELECT * FROM sj_answers WHERE question_id='$question_id' ORDER BY RAND()";
        $result = $this->db->query($sql)->result();

        return $result;
    }

    public function get_video_explanation_from_attempt_id($attemptId, $questionNo) {
		$sql = "SELECT `uploaded_video_explanation` FROM `sj_quiz_attempt_answer` WHERE `attemptId` = ".$attemptId." AND `questionNo` = ".$questionNo;
	
		$query = $this->db->query($sql);
		
		return $query->row()->uploaded_video_explanation;

		
    }
    

    public function get_working_from_attempt_id($attemptId, $questionNo) {
		$sql = "SELECT `ocr_styles` as working FROM `sj_quiz_attempt_answer` WHERE `attemptId` = ".$attemptId." AND `questionNo` = ".$questionNo;
	
		$query = $this->db->query($sql);
		
		return $query->row()->working;

		
    }
    
    public function saveVideoExplanation(){
        $post = $this->input->post();

        $data = [
            'uploaded_video_explanation' => $post['uploaded_video']
        ];

        $this->db->where('attemptId', $post['attemptId']);
        $this->db->where('questionNo', $post['questionNo']);
        $this->db->update('sj_quiz_attempt_answer', $data);
    
        return $this->db->affected_rows();

    }

    public function saveVideoExplanationGroup(){
        $post = $this->input->post();
        $attemptId = $post['attemptId'];
        $quizId = $this->get_quiz_id_from_attempt_id($attemptId);
        $worksheetId = $this->get_worksheetid_from_quiz_id($quizId);
        $allQuiz = $this->get_quiz_from_worksheetid($worksheetId);
        foreach ($allQuiz as $quiz) {
            $allAttempts = $this->get_attempt_ids($quiz->id);
            foreach ($allAttempts as $value) {
                $data = [
                    'uploaded_video_explanation_group' => $post['uploaded_video']
                ];
                $this->db->where('attemptId', $value);
                $this->db->where('questionNo', $post['questionNo']);
                $this->db->update('sj_quiz_attempt_answer', $data);
            }
        }
        return $this->db->affected_rows();
    }

    public function get_worksheetid_from_quiz_id($quizId) {
        $sql = "SELECT `worksheetId` FROM `sj_quiz` WHERE `id` = ".$quizId;
        $query = $this->db->query($sql);
        return $query->row()->worksheetId; 
    }

    public function get_quiz_from_worksheetid($worksheetId) {
        $sql = "SELECT `id` FROM `sj_quiz` WHERE `worksheetId` = ".$worksheetId;
        $query = $this->db->query($sql);
        return $query->result(); 
    }

}