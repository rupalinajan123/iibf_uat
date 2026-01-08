<?php
	/********************************************************************
		* Description : Helper for updating the image name when registration is successful.  
		* Created BY  : Sagar Matale on 06-12-2021
		* Updated BY  : Sagar Matale on 07-01-2022
	********************************************************************/
	
	defined('BASEPATH')||exit('No Direct Allowed Here');
	
	
	/********************************************************************
		* $path 						: "./uploads/photograph/"
		* $current_img_name	: 'p_123.jpg';
		* $new_img_name 		: 'p_456.jpg';
	********************************************************************/
	function update_image_name($path='', $current_img_name='', $new_img_name='')
	{
		$final_img_name = '';
		
		if($path!= "" && $current_img_name != "")
		{
			if(file_exists($path.$current_img_name))
			{
				if($new_img_name != "" && $new_img_name != $current_img_name)
				{
					@copy($path.$current_img_name,$path.$new_img_name);
					
					if(file_exists($path.$new_img_name))
					{
						$final_img_name = $new_img_name;
						//@unlink($path.$current_img_name);  
					}
					else
					{
						$final_img_name = $current_img_name;
					}
				}
				else
				{
					$final_img_name = $current_img_name;
				}
			}
		}
		
		return $final_img_name;
	}
	
	
	/********************************************************************
		* $file_name 		: './uploads/photograph/p_456.jpg'
	********************************************************************/
	function check_files_exist($file_name='')
	{
		$result['flag'] = 'error';
		//$result['file_name'] = $file_name;
		if($file_name != "")
		{
			if(file_exists($file_name))
			{
				$extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION)); 
				if(in_array($extension, array('jpg','jpeg')))
				{
					//$result['message'] = 'file exist';
					$result['flag'] = 'success';
				}
			} 
			else
			{
				//$result['message'] = 'file not exist';
			}
		}
		else
		{
			//$result['message'] = 'file name blank';
		}
		
		/* echo '<pre>'; print_r($result);
		exit; */
		return $result;
	}
	
	
	/********************************************************************
		* $file 		: base_url().'uploads/photograph/p_456.jpg'
	********************************************************************/
	function convert_img_into_base64($file) 
	{  
		$mime = 'image/'.strtolower(pathinfo($file, PATHINFO_EXTENSION));
		
		$contents = file_get_contents($file);
		$base64   = base64_encode($contents); 
		return ('data:' . $mime . ';base64,' . $base64);
	}
	
