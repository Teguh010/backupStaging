<?php
/**
 * *
 *  * Created by PhpStorm.
 *  * User: boonkhailim
 *  * Year: 2017
 *
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('model_users');
        $this->load->helper('slug');
    }

    public function index()
    {
        redirect('404');
    }

    public function public_profile($username) {
        $this->load->model('model_question');
        $user_id = $this->model_users->get_user_id_from_email_or_username($username);

        if (isset($user_id) && empty($user_id) === false) {
            $user_info = $this->model_users->get_user_info($user_id);

            $user_content = array();
            $user_content['profile_pic'] = $user_info->profile_pic;
            $user_content['fullname'] = $user_info->fullname;
            $user_content['profession'] = $this->model_users->get_profession_name_from_id($user_info->profession);


            if ($this->model_users->get_user_role_from_user_id($user_id) == 1) {  // is tutor
                $data['is_tutor'] = true;
                $tutor_specialization = $this->model_users->get_tutor_specialization($user_id);
                $specialization = array();
                foreach ($tutor_specialization as $subject_id) {
                    $specialization[] = $this->model_users->get_specialization_name_from_id($subject_id);
                }
                $user_content['specialization'] = $specialization;
            } else {
                $data['is_tutor'] = false;
            }

            $comments = $this->model_users->get_comments_from_user_id($user_id);
            $user_content['no_of_comments'] = count($comments);

            $user_content['comments'] = array();
            foreach ($comments as $comment) {
                $question = $this->model_question->get_question_from_id($comment['question_id']);
                $question_texts = explode(".", $question->question_text);
                $comment['url'] = base_url() . 'question/' . $question->question_id . '/' . create_slug($question_texts[0]);
                $comment['question_text'] = $question->question_text;
                $user_content['comments'][] = $comment;
            }
            $data['user_content'] = $user_content;

        } else {
            redirect('404');
        }

        $data['content'] = 'user/user_home';
        $this->load->view('include/master_view', $data);

    }
}