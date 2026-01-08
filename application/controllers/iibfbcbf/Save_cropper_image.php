<?php 
  /********************************************************************************************************************
  ** Description: Controller for UPLOAD & SAVE THE CROPPER IMAGE INTO DATABASE TABLE
  ** Created BY: Sagar Matale On 28-06-2024
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Save_cropper_image extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct();
      
      $this->load->model('master_model');
      $this->load->model('iibfbcbf/Iibf_bcbf_model');
      $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 
      $this->load->helper('file');
      $this->delete_old_folders();//GET ALL FOLDER LIST FROM SPECIFIC FOLDERS AND DELETE ALL DATA EXCEPT TODAY & YESTERDAY DATE
		}

    function save_image()
    {
      $flag = "error";
      $response_msg = "";
      $form_data = array();
      if(isset($_POST) && count($_POST) > 0)
      { /* _pa($_POST); _pa($_FILES); */
        $current_image_id = trim($this->security->xss_clean($this->input->post('current_image_id')));
        $db_tbl_name = trim($this->security->xss_clean($this->input->post('db_tbl_name')));
                
        if($db_tbl_name == 'iibfbcbf_batch_candidates')
        {
          $enc_candidate_id = trim($this->security->xss_clean($this->input->post('enc_candidate_id')));
          if($enc_candidate_id == '0') { }
          else
          {
            $enc_batch_id = trim($this->security->xss_clean($this->input->post('enc_batch_id')));
            $batch_id = url_decode($enc_batch_id);
            $candidate_id = url_decode($enc_candidate_id);
          
            //$form_data = $this->master_model->getRecords('iibfbcbf_batch_candidates bc', array('bc.candidate_id' => $candidate_id, 'bc.batch_id' => $batch_id, 'bc.is_deleted' => '0'), "bc.regnumber");
          }
        }



        /* _pa($_POST);
        _pa($_FILES); */

        if($_FILES['selected_image']['name'] != "")
        {
          $file_name_str = date("YmdHis").'_'.rand(1000,9999);
          /* if(isset($form_data) && count($form_data) > 0) 
          { 
            if(isset($form_data[0]['regnumber']) && $form_data[0]['regnumber'] != "") 
            {
              $file_name_str = $form_data[0]['regnumber'];
            }
          } */ 

          $upload_path = $new_file_name = '';
          $min_size_in_kb = '8';//IN KB
          $max_size_in_kb = '20';//IN KB
          $resize_width = $resize_height = '';
          $min_height= $min_width = '0';//IN PIXEL

          if($db_tbl_name == 'iibfbcbf_batch_candidates')
          {
            $id_proof_file_path = 'uploads/iibfbcbf/id_proof';
            $qualification_certificate_file_path = 'uploads/iibfbcbf/qualification_certificate';
            $candidate_photo_path = 'uploads/iibfbcbf/photo';
            $candidate_sign_path = 'uploads/iibfbcbf/sign';
          
            if($current_image_id == 'candidate_photo')
            {
              $upload_path = $candidate_photo_path."_cropper_temp";
              $new_file_name = "photo_".$file_name_str;

              $resize_width = '600';
              $resize_height = '600';

              $min_height = $min_width = '100';
            }
            else if($current_image_id == 'candidate_sign')
            {
              $upload_path = $candidate_sign_path."_cropper_temp";
              $new_file_name = "sign_".$file_name_str;

              $resize_width = '600';
              $resize_height = '600';

              $min_height = '100';
              $min_width = '100';
            }
            else if($current_image_id == 'id_proof_file')
            {
              $upload_path = $id_proof_file_path."_cropper_temp";
              $new_file_name = "id_proof_".$file_name_str;

              $resize_width = '1000';
              $resize_height = '1000';
              $min_size_in_kb = '50';//IN KB
              $max_size_in_kb = '100';//IN KB

              $min_height = $min_width = '300';
            }
            else if($current_image_id == 'qualification_certificate_file')
            {
              $upload_path = $qualification_certificate_file_path."_cropper_temp";
              $new_file_name = "quali_cert_".$file_name_str;

              $resize_width = '1000';
              $resize_height = '1000';
              $min_size_in_kb = '50';//IN KB
              $max_size_in_kb = '100';//IN KB

              $min_height = $min_width = '400';
            }
          }
          else if($db_tbl_name == 'member_registration')
          { 
            $scannedphoto_path = 'uploads/photograph';
            $scannedsignaturephoto_path = 'uploads/scansignature';
            $idproofphoto_path = 'uploads/idproof';
            $declaration_path = 'uploads/declaration';
            $empidproofphoto_path = 'uploads/empidproof';
            $bank_bc_id_card_path = 'uploads/empidproof';
          
            if($current_image_id == 'scannedphoto')
            {
              $upload_path = $scannedphoto_path."_cropper_temp";
              $new_file_name = "p_".$file_name_str;

              $resize_width = '600';
              $resize_height = '600';
              $min_size_in_kb = '10';//IN KB
              $max_size_in_kb = '50';//IN KB

              $min_width = '200';
              $min_height = '230';
            }
            else if($current_image_id == 'scannedsignaturephoto')
            {
              $upload_path = $scannedsignaturephoto_path."_cropper_temp";
              $new_file_name = "s_".$file_name_str;

              $resize_width = '600';
              $resize_height = '600';
              $min_size_in_kb = '10';//IN KB
              $max_size_in_kb = '50';//IN KB

              $min_width = '140';
              $min_height = '60';
            }
            else if($current_image_id == 'idproofphoto')
            {
              $upload_path = $idproofphoto_path."_cropper_temp";
              $new_file_name = "pr_".$file_name_str;

              $resize_width = '1000';
              $resize_height = '1000';
              $min_size_in_kb = '50';//IN KB
              $max_size_in_kb = '300';//IN KB

              $min_height = $min_width = '300';
            }
            else if($current_image_id == 'declaration' || $current_image_id == 'declarationform')
            {
              $upload_path = $declaration_path."_cropper_temp";
              $new_file_name = "declaration_".$file_name_str;

              $resize_width = '1000';
              $resize_height = '1000';
              $min_size_in_kb = '50';//IN KB
              $max_size_in_kb = '300';//IN KB

              $min_height = $min_width = '300';
            }
            else if($current_image_id == 'empidproofphoto')
            {
              $upload_path = $empidproofphoto_path."_cropper_temp";
              $new_file_name = "empr_".$file_name_str;

              $resize_width = '1000';
              $resize_height = '1000';
              $min_size_in_kb = '90';//IN KB
              $max_size_in_kb = '300';//IN KB

              $min_height = $min_width = '300';
            }
            else if($current_image_id == 'bank_bc_id_card')
            {
              $upload_path = $bank_bc_id_card_path."_cropper_temp";
              $new_file_name = "bank_bc_id_card_".$file_name_str;

              $resize_width = '1000';
              $resize_height = '1000';
              $min_size_in_kb = '90';//IN KB
              $max_size_in_kb = '300';//IN KB

              $min_height = $min_width = '300';
            }
          }
          else if($db_tbl_name == 'dra_members')
          {
            $id_proof_file_path = 'uploads/iibfdra';
            $qualification_certificate_file_path = 'uploads/iibfdra';
            $candidate_photo_path = 'uploads/iibfdra';
            $candidate_sign_path = 'uploads/iibfdra';
          
            if($current_image_id == 'candidate_photo')
            {
              $upload_path = $candidate_photo_path."_cropper_temp";
              $new_file_name = "photo_".$file_name_str;

              $resize_width = '600';
              $resize_height = '600';

              $min_height = $min_width = '100';
            }
            else if($current_image_id == 'candidate_sign')
            {
              $upload_path = $candidate_sign_path."_cropper_temp";
              $new_file_name = "sign_".$file_name_str;

              $resize_width = '600';
              $resize_height = '600';

              $min_height = '100';
              $min_width = '100';
            }
            else if($current_image_id == 'id_proof_file')
            {
              $upload_path = $id_proof_file_path."_cropper_temp";
              $new_file_name = "id_proof_".$file_name_str;

              $resize_width = '1000';
              $resize_height = '1000';
              $min_size_in_kb = '50';//IN KB
              $max_size_in_kb = '100';//IN KB

              $min_height = $min_width = '300';
            }
          }
          else if($db_tbl_name == 'image_process_global')
          {
            $id_proof_file_path = $qualification_certificate_file_path = $candidate_photo_path = $candidate_sign_path = 'uploads/tmp_processed_images';
          
            if($current_image_id == 'candidate_photo')
            {
              $upload_path = $candidate_photo_path."_cropper_temp";
              $new_file_name = "photo_".$file_name_str;

              $resize_width = '600';
              $resize_height = '600';

              $min_height = $min_width = '100';
            }
            else if($current_image_id == 'candidate_sign')
            {
              $upload_path = $candidate_sign_path."_cropper_temp";
              $new_file_name = "sign_".$file_name_str;

              $resize_width = '600';
              $resize_height = '600';

              $min_height = '100';
              $min_width = '100';
            }
            else if($current_image_id == 'id_proof_file')
            {
              $upload_path = $id_proof_file_path."_cropper_temp";
              $new_file_name = "id_proof_".$file_name_str;

              $resize_width = '1000';
              $resize_height = '1000';
              $min_size_in_kb = '50';//IN KB
              $max_size_in_kb = '100';//IN KB

              $min_height = $min_width = '300';
            }
            else if($current_image_id == 'qualification_certificate_file')
            {
              $upload_path = $qualification_certificate_file_path."_cropper_temp";
              $new_file_name = "quali_cert_".$file_name_str;

              $resize_width = '1000';
              $resize_height = '1000';
              $min_size_in_kb = '50';//IN KB
              $max_size_in_kb = '100';//IN KB

              $min_height = $min_width = '400';
            }
          }

          if($upload_path != "" && $new_file_name != "")
          {
            //$input_name, $valid_arr, $new_file_name, $upload_path, $allowed_types, $is_multiple=0, $cnt='', $height=0, $width=0, $size=0,$resize_width=0,$resize_height=0,$resize_file_name
            $upload_data = $this->Iibf_bcbf_model->upload_file("selected_image", array('jpg','jpeg','png'), $new_file_name, "./".$upload_path.'/'.date('Ymd'), "jpg|jpeg|png", '', '', '', '', '',$resize_width,$resize_height,$new_file_name);
            if($upload_data['response'] == 'error')
            {
              $response_msg = str_replace("</p>","",str_replace("<p>","",$upload_data['message']));
            }
            else if($upload_data['response'] == 'success')
            {
              $final_image = $upload_path.'/'.date('Ymd').'/'.$upload_data['message'];

              $source_img = $final_image;  // Replace with your image path
              $destination_img = $final_image;  // Replace with your desired output path

              $compressImageRes = $this->compressImage($source_img, $destination_img, $min_height, $min_width, $max_size_in_kb, $min_size_in_kb);
              ////_pa($compressImageRes);
              if ($compressImageRes['flag'] == true) 
              {
                $flag = 'success';
                //echo "Image successfully compressed to under {$max_size_in_kb}KB";
                $response_msg = base_url($upload_path.'/'.date('Ymd').'/'.$upload_data['message']);
              } 
              else 
              {
                //echo "Could not compress image to under {$max_size_in_kb}KB";
                //$response_msg =  "Could not compress image to under {$max_size_in_kb}KB";
                //$response_msg = 'The crop image size is too large.';
                $response_msg = $compressImageRes['message'];
              }
            }
          }
          else
          {
            $response_msg = 'Error occurred. Please try again.';
          }
        }
        else { $flag = "error2"; }
      }

      $result['flag'] = $flag;
      $result['response_msg'] = $response_msg;
      echo json_encode($result);
    }

    function compressImage_bk($source, $destination, $targetMinSizeKB, $targetMaxSizeKB, $maxWidth = null, $maxHeight = null) 
    {
      $flag = 'false';
      $message = '';

      // Get image info
      $info = getimagesize($source);
      $mime = $info['mime'];
  
      // Create a new image from file
      switch ($mime) 
      {
        case 'image/jpeg': $image = imagecreatefromjpeg($source); break;
        case 'image/png': $image = imagecreatefrompng($source); break;
        case 'image/gif': $image = imagecreatefromgif($source); break;
        default: throw new Exception('Unknown image type.');
      }
  
      // Resize image if max dimensions are set
      if ($maxWidth || $maxHeight) 
      {
        list($width, $height) = getimagesize($source);
        $newWidth = $width;
        $newHeight = $height;
  
        if ($maxWidth && $newWidth > $maxWidth) 
        {
          $newHeight = $newHeight * ($maxWidth / $newWidth);
          $newWidth = $maxWidth;
        }

        if ($maxHeight && $newHeight > $maxHeight) 
        {
          $newWidth = $newWidth * ($maxHeight / $newHeight);
          $newHeight = $maxHeight;
        }
  
        $tmp = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($tmp, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        $image = $tmp;
      }
  
      // Compress the image to the target size
      $quality = 100;
      $minQuality = 0;
      $maxQuality = 100;
      $filesize = 0;
      
      do 
      {
        ob_start();
        imagejpeg($image, null, $quality);
        $content = ob_get_contents();
        ob_end_clean();
        $filesize = strlen($content) / 1024;
        
        if ($filesize > $targetMaxSizeKB) 
        {
          $maxQuality = $quality - 1;
          $quality = (int)(($minQuality + $quality) / 2);
        } 
        else if ($filesize < $targetMinSizeKB) 
        {
          $minQuality = $quality + 1;
          $quality = (int)(($maxQuality + $quality) / 2);
        }

      } while (($filesize > $targetMaxSizeKB || $filesize < $targetMinSizeKB) && $minQuality < $maxQuality);
  
      // Save the compressed image
      file_put_contents($destination, $content);
  
      // Free up memory
      imagedestroy($image);
  
      $flag = $filesize >= $targetMinSizeKB && $filesize <= $targetMaxSizeKB;

      if($flag == false)
      {
        if($filesize > $targetMaxSizeKB) { $message = 'The crop image size is too large.'; }
        else if($filesize < $targetMinSizeKB) { $message = 'The crop image size is too small.'; }        
      }

      $result = array();
      $result['flag'] = $flag;
      $result['message'] = $message;
      return $result;
    }

    function compressImage($source_img = '', $destination_img = '', $min_height = 0, $min_width = 0, $max_size_in_kb = 0, $min_size_in_kb = 0)
    {
      /* echo '<br>source_img : '.$source_img;  // Replace with your image path
      echo '<br>destination_img : '.$destination_img;  // Replace with your desired output path
      echo '<br>min_height : '.$min_height;
      echo '<br>min_width : '.$min_width;
      echo '<br>max_size_in_kb : '.$max_size_in_kb;
      echo '<br>min_size_in_kb : '.$min_size_in_kb;
      echo '<br><br>';////exit; */

      $result = ['flag' => false, 'message' => ''];

      if (empty($source_img)) /// Validate source image
      {
        $result['message'] = 'Source image cannot be blank.';
        return $result;
      }

      if (!file_exists($source_img)) 
      {
        $result['message'] = 'The source image does not exist.';
        return $result;
      }
      
      if (empty($destination_img)) { $destination_img = $source_img; } // Default destination to source if not provided
      
      $source_info = getimagesize($source_img);// Get source image information
      if ($source_info === false) 
      {
        $result['message'] = 'The source image is not valid.';
        return $result;
      }

      // Check for valid image types
      $validTypes = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF];
      if (!in_array($source_info[2], $validTypes)) 
      {
        $result['message'] = 'The source image is not a valid type.';
        return $result;
      }

      // Create a new image from file based on its mime type
      switch ($source_info['mime']) 
      {
        case 'image/jpeg': $image = imagecreatefromjpeg($source_img); break;
        case 'image/png': $image = imagecreatefrompng($source_img); break;
        case 'image/gif': $image = imagecreatefromgif($source_img); break;
        default: $result['message'] = 'Unsupported image type.'; return $result;
      }
      
      list($width, $height) = $source_info;// Get image dimensions

      '<br>source_width : '.$source_width = $width;
      '<br>source_height : '.$source_height = $height; ////exit; 

      '<br>min_height : '.$min_height;
      '<br>min_width : '.$min_width; ////exit; 
      
      
      if ($min_height > 0 && $source_height < $min_height) // Check dimensions
      {
        $result['message'] = 'The selected image height is too small. Please select an image with a height more than '.$min_height.'px';
        imagedestroy($image);
        return $result;
      }

      if ($min_width > 0 && $source_width < $min_width) 
      {
        $result['message'] = 'The selected image width is too small. Please select an image with a width more than '.$min_width.'px';
        imagedestroy($image);
        return $result;
      }

      // Check size constraints
      $source_size_in_kb = filesize($source_img) / 1024;
      if ($max_size_in_kb > 0 && $min_size_in_kb > 0 && $max_size_in_kb <= $min_size_in_kb) 
      {
        $result['message'] = 'The maximum size must be less than the minimum size.';
        imagedestroy($image);
        return $result;
      }

      '<br>max_size_in_kb : '.$max_size_in_kb; ////exit;
      '<br>min_size_in_kb : '.$min_size_in_kb; ////exit;
      '<br>source_size_in_kb : '.$source_size_in_kb = filesize($source_img) / 1024; ////exit;
      if($max_size_in_kb > 0 && $source_size_in_kb > $max_size_in_kb)
      {
        $quality = 100;
        do 
        {
          ob_start();
          imagejpeg($image, null, $quality);
          $content = ob_get_clean();
          $filesize = strlen($content) / 1024;

          if ($filesize > $max_size_in_kb) 
          {
            $quality -= 2;
          }
        } while ($filesize > $max_size_in_kb && $quality > 0);

        file_put_contents($destination_img, $content);
        imagedestroy($image);

        if ($filesize > $max_size_in_kb) 
        {
          $result['message'] = 'The crop image size is too large.';
        } 
        else 
        {
          $result['flag'] = true;
        }
      }
      else if($min_size_in_kb > 0 && $source_size_in_kb < $min_size_in_kb)
      {
        $quality = 0;
        do 
        {
          ob_start();
          imagejpeg($image, null, $quality);
          $content = ob_get_clean();
          $filesize = strlen($content) / 1024;

          '<br>filesize : '.$filesize; ////exit;

          if ($filesize < $min_size_in_kb) 
          {
            $quality += 2;
          }

          '<br>'.$filesize.' << '.$quality.' >> '.$min_size_in_kb;
        } while ($filesize < $min_size_in_kb && $quality <= 100);

        file_put_contents($destination_img, $content);
        imagedestroy($image);

        if ($filesize < $min_size_in_kb) 
        {
          $result['message'] = 'The crop image size is too small.';
        } 
        else 
        {
          $result['flag'] = true;
        }
      }
      else 
      {        
        imagejpeg($image, $destination_img);// No size constraints
        imagedestroy($image);
        $result['flag'] = true;
      }

      return $result;
    }

    function test()
    {
      // Specify the source image path
      echo '<br>source_img : '.$source_img = 'uploads/vertical_img1.jpg';  // Replace with your image path
      echo '<br>destination_img : '.$destination_img = 'uploads/vertical_img2.jpg';;  // Replace with your desired output path
      echo '<br>min_height : '.$min_height = '2000';
      echo '<br>min_width : '.$min_width = '1000';
      echo '<br>max_size_in_kb : '.$max_size_in_kb = '1200';
      echo '<br>min_size_in_kb : '.$min_size_in_kb = '1100';
      echo '<br><br>';

      $result = $this->compressImage2($source_img, $destination_img, $min_height, $min_width, $max_size_in_kb, $min_size_in_kb);
      _pa($result);
      /* if () 
      {
        echo "Image successfully compressed to under {$max_size_in_kb}KB";
      } else {
          echo "Could not compress image to under {$min_size_in_kb}KB";
      } */
    }

    function delete_old_folders()
    {
      //START: GET ALL FOLDER LIST FROM BELOW FOLDERS AND DELETE ALL DATA EXCEPT TODAY & YESTERDAY   
      $folder_arr = array('uploads/iibfbcbf/photo_cropper_temp', 'uploads/iibfbcbf/sign_cropper_temp', 'uploads/iibfbcbf/id_proof_cropper_temp', 'uploads/iibfbcbf/qualification_certificate_cropper_temp', 'uploads/photograph_cropper_temp', 'uploads/scansignature_cropper_temp', 'uploads/idproof_cropper_temp', 'uploads/declaration_cropper_temp', 'uploads/empidproof_cropper_temp','uploads/iibfdra_cropper_temp', 'uploads/tmp_processed_images_cropper_temp');
      $not_delete_folder_arr = array(date('Ymd'), date('Ymd', strtotime("-1days")));
       

      foreach($folder_arr as $dir_name)
      {
        if (strpos($dir_name, '_cropper_temp') !== false) { } else { $dir_name = $dir_name.'_cropper_temp'; }
        
        //$baseDir = '/path/to/your/base/directory';
        $baseDir = rtrim($dir_name, '/') . '/';// Ensure the base directory ends with a slash      
        $directories = glob($baseDir . '*', GLOB_ONLYDIR);// Get all directories in the base directory
        
        //print_r($directories);
        if(count($directories) > 0)
        {
          foreach ($directories as $dir) 
          {
            $dirName = basename($dir);// Get the base name of the directory
            if (!in_array($dirName, $not_delete_folder_arr)) // Check if the directory is not "temp"
            {
              // Recursively delete the directory
              delete_files($dir, TRUE); // Delete all files inside
              rmdir($dir); // Remove the directory itself
            }
          }
        }
      }
      //END: GET ALL FOLDER LIST FROM BELOW FOLDERS AND DELETE ALL DATA EXCEPT TODAY & YESTERDAY
    }
  }