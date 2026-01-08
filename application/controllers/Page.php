<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Page extends CI_Controller {
	public function __construct()
	{
		 parent::__construct();
		 $this->load->model('master_model');	 
	}
	public function details()
	{
		if($this->uri->segment(3)=="")
		{
		 	redirect(base_url());
		}	
		$data=array();	
		$page = $this->uri->segment(3);
		$this->db->where('status','Active');
		$this->db->where('url_word',$page);
		$detail_data = $this->master_model->getRecords('page_master',array('url_word'=>$page));
		if(count($detail_data) > 0 ) {
			//echo "if "; die("this is test message");
			$meta_key = $detail_data[0]['meta_keyword'];
			$meta_desc = $detail_data[0]['meta_desc'];
			$page_title = $detail_data[0]['title'];
			$page_desc = $detail_data[0]['description'];
			$data = array("middle_content"=>'cms_detail_page',
				"page_title" => $page_title,
				"page_desc" => $page_desc,
				"meta_key" => $meta_key,
				"meta_desc" => $meta_desc
			) ;
		} else {
			//echo "else  "; die("this is test message");
			$data=array("middle_content"=>'page_not_found');
		}
		$this->load->view('common_view_fullwidth',$data);	
		//$this->load->view('common_view',$data);	
	}
	public function instructions()
	{
		if($this->uri->segment(3)=="")
		{
		 	redirect(base_url());
		}	
		$data=array();	
		$examcode = $this->uri->segment(3);
		$this->db->where('status','Active');
		$this->db->where('url_word',$page);
		$detail_data = $this->master_model->getRecords('page_master',array('url_word'=>$page));
		if(count($detail_data) > 0 ) {
			//echo "if "; die("this is test message");
			$meta_key = $detail_data[0]['meta_keyword'];
			$meta_desc = $detail_data[0]['meta_desc'];
			$page_title = $detail_data[0]['title'];
			$page_desc = $detail_data[0]['description'];
			$data = array("middle_content"=>'cms_detail_page',
				"page_title" => $page_title,
				"page_desc" => $page_desc,
				"meta_key" => $meta_key,
				"meta_desc" => $meta_desc
			) ;
		} else {
			//echo "else  "; die("this is test message");
			$data=array("middle_content"=>'page_not_found');
		}
		$this->load->view('common_view',$data);	
	}
}