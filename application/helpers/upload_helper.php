<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * AdminCMS
 * @package   AdminCMS
 * @author    Yunus Shaikh {contributed}
 * @since     Version 1.0
 */
/**
 * Files upload helper functions.
 *
 * Includes additional file upload functions
 *
 */
if ( ! function_exists('upload_file'))
{
	function upload_file($file, $path, $filename=NULL,$max_width=NULL,$max_height=NULL,$overwrite=NULL)
	{
		$flag=1;
		$CI = & get_instance(); 
		$config['upload_path']  = $path;
		$config['allowed_types']  = '*';
		$config['max_size']    = '2048';
		
		if($overwrite)
		{
			$config['overwrite'] = $overwrite;   
		} 
		if($max_width && $max_height)
		{
			$image_info = getimagesize($_FILES[$file]["tmp_name"]);
			if($image_info[0]!=$max_width && $image_info[1]!=$max_height)
			{
				$flag=0;	
			}
			
		}
		if($filename)
		{	$config['file_name']  = $filename;	}
		
		//$myfile = fopen("./uploads/Text/logs.txt", "a+");
		//fwrite($myfile, '<br/><br/> ********************* <br/>'.date('Y-m-d H:i:s').'<br/>');
		//fwrite($myfile, 'In upload_file Function <br/>');
			
		
		$CI->load->library('session');
		$CI->load->library('upload');
		$CI->upload->initialize($config); // Important
		
		if ($CI->upload->do_upload($file) && $flag)
		{
			//$data = array('upload_data' => $CI->upload->data());
			$data = $CI->upload->data();
			
			/* fwrite($myfile, 'Upload Success : '.$file.'<br/>');
			fwrite($myfile, 'Uploaded File : '.$data['file_name'].'');
			fwrite($myfile, '<br/>******************************************************************');
			fclose($myfile); */
						
			return $data;
		}
		else
		{
			$error = array('error' => $CI->upload->display_errors());
			$last = $CI->uri->total_segments();
			$post = $CI->uri->segment($last);
			$CI->session->set_flashdata('error',$CI->upload->display_errors());
			
			/* fwrite($myfile, '<br/> ****** Upload Error '.$file.'****** <br/>');
			fwrite($myfile, '<br/> Filename : '.$filename.' <br/>');
			fwrite($myfile, 'Error'.$CI->upload->display_errors().'');
			fwrite($myfile, '<br/>******************************************************************');
			fclose($myfile); */
			//redirect(base_url().$post);
			return false;
		}
	}
 }
 
if ( ! function_exists('upload_file1'))
{
	function upload_file1($file, $path, $filename=NULL)
	{
		$CI = & get_instance(); 
		$config['upload_path']  = $path;
		$config['allowed_types']  = '*';
		if($filename)
		{	$config['file_name']  = $filename;	}
		
		
		$CI->load->library('session');
		$CI->load->library('upload');
		$CI->upload->initialize($config); // Important
		
		if ($CI->upload->do_upload($file))
		{
			//$data = array('upload_data' => $CI->upload->data());
			$data = $CI->upload->data();
			
			$error = array('error' => $CI->upload->display_errors());
			$last = $CI->uri->total_segments();
			$post = $CI->uri->segment($last);
			$CI->session->set_flashdata('error','uploaderror');
			redirect(base_url().$post);			
			//return $data;
		}
		else
		{
			$error = array('error' => $CI->upload->display_errors());
			$last = $CI->uri->total_segments();
			$post = $CI->uri->segment($last);
			$CI->session->set_flashdata('error',$CI->upload->display_errors());
			redirect(base_url().$post);
			//return false;
		}
	}
 }


