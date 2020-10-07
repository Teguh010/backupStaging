<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TestUI extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Model_quiz');
    }


    public function pe_layout(){

        $question_open = $this->db->get_where('sj_questions', ['question_id' => 33626])->result();

        foreach($question_open as $qo){
            $q_direction = $this->db->get_where('sj_question_article', ['question_id' => $qo->question_id])->row_array();
            $qo->question_direction = $q_direction['article_content'];
        }


        $question_option = $this->db->get_where('sj_questions', ['question_id' => 33629])->result();

        foreach($question_option as $qopt){
            $q_direction = $this->db->get_where('sj_question_article', ['question_id' => $qopt->question_id])->row_array();
            $qopt->question_direction = $q_direction['article_content'];
        }        


        $question_list = $this->db->get_where('sj_questions', ['question_id' => 33593])->result();

        foreach($question_list as $ql){
            // $q_direction = $this->db->get_where('sj_question_article', ['question_id' => $ql->question_id])->row_array();
            // $ql->question_direction = $q_direction['article_content'];
        }

        $data['question_open'] = $question_open;
        $data['question_option'] = $question_option;
        $data['question_list'] = $question_list;

        $data['answers_option'] = $this->Model_quiz->getAnswerOption(33629);
        $data['answers_list'] = '';

        $this->load->view('quiz/test_ui', $data);
    }


}

?>