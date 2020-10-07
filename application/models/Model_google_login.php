<?php
class Model_google_login extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        include_once APPPATH . "vendor/vendor/autoload.php";
        
    }

    function Is_already_register($id)
    {
        $this->db->where('login_oauth_uid', $id);
        $query = $this->db->get('sj_google_user');
        if($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function Update_user_data($data, $id)
    {
        $this->db->where('login_oauth_uid', $id);
        $this->db->update('sj_google_user', $data);
    }

    function Insert_user_data($data)
    {
        $this->db->insert('sj_google_user', $data);
    }
}
?>