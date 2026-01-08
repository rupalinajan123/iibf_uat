<?php 

/*$this->load->view('itpo_css');*/

$this->load->view('nonmember/front-header-nm');

$this->load->view('nonmember/front-sidebar-nm');

$this->load->view($middle_content);

$this->load->view('nonmember/front-footer-nm');

?>