
<?php

    $this->load->view('include/site_header');
    $this->load->view('include/site_nav');
    $this->load->view($content);
    $this->load->view('include/footer');
    $this->load->view('include/site_footer');

?>