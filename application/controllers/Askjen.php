<?php
/**
 * *
 *  * Created by PhpStorm.
 *  * User: boonkhailim
 *  * Year: 2017
 *
 */

class Askjen extends CI_Controller {
	public function index() {
		$this->home();
	}

	public function login() {
		$this->session->set_userdata('lastPage', $this->input->get('url'));
		redirect('site/login');
	}

	public function home($all = NULL) {
		$this->load->model('model_question');
		$this->load->helper('slug');

		$data['content'] = 'askjen/askjen_home';
		$questions = $this->model_question->select_latest_question(30);
		
		foreach ($questions as $question) {
			$question_texts = explode(".", $question->question_text);
			$question->url = base_url() . 'question/' . $question->question_id . '/' . create_slug($question_texts[0]);
			$category_name = $this->model_question->get_category_name_from_category_id($question->topic_id);
			$question->category_url = base_url() . 'category/' . $question->topic_id . '/' . create_slug($category_name);
			$question->category_name = $category_name;
			$question->comment_count = $this->model_question->get_comment_count($question->question_id);
			$question->vote_count = $this->model_question->get_vote_count($question->question_id);
			$question->view_count = $this->model_question->get_view_count($question->question_id);
		}

		$data['questions'] = $questions;
		$this->load->view('include/master_view', $data);
	}

	public function question($question_id) {
		$this->load->model('model_question');
		$this->load->model('model_users');
		$this->load->helper('slug');

		$post_data = $this->input->post();

		if (isset($post_data) && empty($post_data) === false) {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('question_comment', 'Comments', 'required|trim|max_length[5000]');

			if ($this->form_validation->run()) {
				$user_id =  $this->session->userdata('user_id');
				$comment = $post_data['question_comment'];
				if ($this->model_question->submit_question_comment($question_id, $user_id, $comment)) {
					$data['comment_message'] = "Comment submitted.";
				} else {
					$data['comment_error'] = "Error submitting comment, please try again later";
				}
				
			} else {
				$data['comment_error'] = validation_errors();
			}

		} 

		$data['content'] = 'askjen/askjen_question';
		$question = $this->model_question->get_question_from_id($question_id);
		$category_name = $this->model_question->get_category_name_from_category_id($question->topic_id);
		$question->category_name = $category_name;
		$question->category_url = base_url() . 'category/' . $question->topic_id . '/' . create_slug($category_name);
		$data['question_id'] = $question_id;
		$data['question'] = $question;
		$comments = $this->model_question->get_comments($question_id);

		foreach ($comments as $comment) {
			$comment->username = $this->model_users->get_username_from_id($comment->user_id);
		}

		$data['comments'] = $comments;
		$data['isLoggedIn'] = $this->session->userdata('is_logged_in');
		$this->load->view('include/master_view', $data);
	}

	public function category($category_id, $category_name) {
		$this->load->model('model_question');
		$this->load->helper('slug');

		$data['content'] = 'askjen/askjen_home';
		$questions = $this->model_question->select_latest_question(30, $category_id);
		
		foreach ($questions as $question) {
			$question_texts = explode(".", $question->question_text);
			$question->url = base_url() . 'question/' . $question->question_id . '/' . create_slug($question_texts[0]);
			$category_name = $this->model_question->get_category_name_from_category_id($question->topic_id);
			$question->category_url = base_url() . 'category/' . $question->topic_id . '/' . create_slug($category_name);
			$question->category_name = $category_name;
			$question->comment_count = $this->model_question->get_comment_count($question->question_id);
			$question->vote_count = $this->model_question->get_vote_count($question->question_id);
			$question->view_count = $this->model_question->get_view_count($question->question_id);
		}

		$data['category_name'] = $category_name;
		$data['questions'] = $questions;
		$this->load->view('include/master_view', $data);
	}
}