<?php
defined('BASEPATH') or exit('No direct script access allowed');
class File_upload_demo extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('upload');
        $this->load->helper('upload_helper');
    }
    
    public function index()
		{   
			$data = array();
      $data['upload_file_ext'] = $upload_file_ext = '.jpg,.jpeg,.pdf';
      $data['upload_file_max_size'] = $upload_file_max_size = '500KB';
      
      $upload_file_ext_arr = array();
      $upload_file_ext_str = '';
      if($upload_file_ext != "")
      {
        $upload_file_ext_arr = explode(",",str_replace(".","",$upload_file_ext));
        $upload_file_ext_str = str_replace(",","|",str_replace(".","",$upload_file_ext));
      }
     			
			if(isset($_POST) && count($_POST) > 0)
			{        
				$this->form_validation->set_rules('upload_form_hidden', '', 'trim|required|xss_clean');		
				if(empty($_FILES['upload_file']['name']))
        {
          $this->form_validation->set_rules('upload_file', 'File', 'required');
        }
				
				if($this->form_validation->run())		
				{		
          /*echo '<pre>';	
          print_r($_POST);
          print_r($_FILES);
          echo '</pre>'; */
          
          $flag = 0;
          $path_img = $_FILES['upload_file']['name']; 
                    
          $ext_img = strtolower(pathinfo($path_img, PATHINFO_EXTENSION));
          $valid_ext_arr = $upload_file_ext_arr;
          $allowed_types = $upload_file_ext_str;
          
          if(!in_array(strtolower($ext_img),$valid_ext_arr))
          {
            $flag=1;
          }
          
          if($flag == 0)
          {
            $upload_path = 'uploads/file_upload_demo';            
            $this->create_directories($upload_path);
            
            $file=$_FILES;	
            $_FILES['file_upload']['name'] = $file['upload_file']['name'];
            
            $path = $_FILES['file_upload']['name'];
                        
            $raw_filename = str_replace(".".$ext_img,"",strtolower($path));
            $raw_filename = $this->remove_special_character_from_string($raw_filename, '50')."_".rand(100,999).date("YmdHis"); 
            $filename = $raw_filename.".".$ext_img;            
            $final_img = $filename;
            
            $config['file_name']     = $final_img;
            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = $allowed_types;
            
            $this->upload->initialize($config);					
            
            $_FILES['file_upload']['type']=$file['upload_file']['type'];
            $_FILES['file_upload']['tmp_name']=$file['upload_file']['tmp_name'];
            $_FILES['file_upload']['error']=$file['upload_file']['error'];
            $_FILES['file_upload']['size']=$file['upload_file']['size'];            
            
            if($this->upload->do_upload('file_upload'))
            {
              $data=$this->upload->data();
              $this->session->set_flashdata('success','File successfully uploaded. <strong><a href="'.base_url($upload_path.'/'.$final_img).'" target="_blank">Click Here</a></strong> to download the file');
            }
            else
            {
              $this->session->set_flashdata('error','Selected file is corrupted. Please upload valid file.');
            }
          }
          else
          {
            $this->session->set_flashdata('error',"Please upload valid ".str_replace('|',' | ',$allowed_types)." extension image.");
          }

          redirect(site_url('file_upload_demo'));
				}
			}			
			
      $this->load->view('file_upload_demo', $data);
    }

    function create_directories($directory_path='')
    {
      $directory_path = str_replace("./","",$directory_path);
      $directory_path_arr = explode("/",$directory_path);        
      $chk_dir_path = './';
      if(count($directory_path_arr) > 0)
      {
        $i = 0;
        foreach($directory_path_arr as $res)
        {
          if($i > 0) { $chk_dir_path .= "/"; }
          $chk_dir_path .= $res;
          
          if(!is_dir($chk_dir_path))
          { 
            $dir = mkdir($chk_dir_path,0755);					
            $myfile = fopen($chk_dir_path."/index.php", "w") or die("Unable to open file!");
            $txt = ""; fwrite($myfile, $txt); fclose($myfile);
          }
          $i++;
        }
      }
      return $chk_dir_path;
    }

    //START : REMOVE SPECIAL CHARACTER FROM STRING
		function remove_special_character_from_string($old_string='', $char_limit='50')
		{
			$find_arr = array('`', '~', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '-', '=', '+', '[', '{', ']', '}', '|', ';', ':', '"', '<', ',', '.', '>', '/', '?', "'", '/\/', ' ');
			$new_string = substr($this->check_multiple_underscore(str_replace($find_arr,'_',$old_string)), 0, $char_limit);
			
			/* echo "<br>Old Name : ".$old_string;
			echo "<br>New Name : ".$new_string; */
			return strtolower($new_string);
		}
		//END : REMOVE SPECIAL CHARACTER FROM STRING

    //START : REMOVE MULTIPLE UNDERSCORE FROM STRING
		function check_multiple_underscore($new_name='')
		{
			if (strpos($new_name, '__') !== false)
			{
				$new_name = str_replace('__','_',$new_name);
				return $this->check_multiple_underscore($new_name);
			}
			else { return $new_name; }
		}
		//END : REMOVE MULTIPLE UNDERSCORE FROM STRING

}
