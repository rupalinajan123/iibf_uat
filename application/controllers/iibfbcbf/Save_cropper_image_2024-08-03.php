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
          $minFileSizeKB = '14';//IN KB
          $maxFileSizeKB = '20';//IN KB
          $resize_width = '';
          $resize_height = '';

          if($db_tbl_name == 'iibfbcbf_batch_candidates')
          {
            $id_proof_file_path = 'uploads/iibfbcbf/id_proof';
            $qualification_certificate_file_path = 'uploads/iibfbcbf/qualification_certificate';
            $candidate_photo_path = 'uploads/iibfbcbf/photo';
            $candidate_sign_path = 'uploads/iibfbcbf/sign';
          
            if($current_image_id == 'candidate_photo')
            {
              $upload_path = $candidate_photo_path."_temp";
              $new_file_name = "photo_".$file_name_str;

              $resize_width = '600';
              $resize_height = '600';
            }
            else if($current_image_id == 'candidate_sign')
            {
              $upload_path = $candidate_sign_path."_temp";
              $new_file_name = "sign_".$file_name_str;

              $resize_width = '600';
              $resize_height = '600';
            }
            else if($current_image_id == 'id_proof_file')
            {
              $upload_path = $id_proof_file_path."_temp";
              $new_file_name = "id_proof_".$file_name_str;

              $resize_width = '1000';
              $resize_height = '1000';
              $minFileSizeKB = '75';//IN KB
              $maxFileSizeKB = '100';//IN KB
            }
            else if($current_image_id == 'qualification_certificate_file')
            {
              $upload_path = $qualification_certificate_file_path."_temp";
              $new_file_name = "quali_cert_".$file_name_str;

              $resize_width = '1000';
              $resize_height = '1000';
              $minFileSizeKB = '75';//IN KB
              $maxFileSizeKB = '100';//IN KB
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

              $sourceImagePath = $final_image;  // Replace with your image path
              $destinationImagePath = $final_image;  // Replace with your desired output path
              
              if($maxFileSizeKB > 0)
              {
                $compressImageRes = $this->compressImage($sourceImagePath, $destinationImagePath, $minFileSizeKB, $maxFileSizeKB);
                if ($compressImageRes['flag'] == true) 
                {
                  $flag = 'success';
                  //echo "Image successfully compressed to under {$maxFileSizeKB}KB";
                  $response_msg = base_url($upload_path.'/'.date('Ymd').'/'.$upload_data['message']);
                } 
                else 
                {
                  //echo "Could not compress image to under {$maxFileSizeKB}KB";
                  //$response_msg =  "Could not compress image to under {$maxFileSizeKB}KB";
                  //$response_msg = 'The crop image size is too large.';
                  $response_msg = $compressImageRes['message'];
                }
              }
              else
              {
                $flag = 'success';
                //echo "Image successfully compressed to under {$maxFileSizeKB}KB";
                $response_msg = base_url($upload_path.'/'.date('Ymd').'/'.$upload_data['message']);
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

    function compressImage_new($source, $destination, $targetMinSizeKB, $targetMaxSizeKB) 
    {
      $flag = false;
      $message = '';      
      
      $info = getimagesize($source);// Get image info
      $mime = $info['mime'];      
      
      switch ($mime) // Create a new image from file
      {
        case 'image/jpeg': $image = imagecreatefromjpeg($source); break;
        case 'image/png': $image = imagecreatefrompng($source); break;
        case 'image/gif': $image = imagecreatefromgif($source); break;
        default: throw new Exception('Unsupported image type.');
      }
      
      // Initialize compression parameters
      $quality = 90; // Start quality
      $minQuality = 0;
      $maxQuality = 100;
      $filesize = 0;
      
      do 
      {
        // Compress the image to a variable
        ob_start();
        imagejpeg($image, null, $quality);
        $content = ob_get_contents();
        ob_end_clean();
        $filesize = strlen($content) / 1024; // Size in KB
        
        if ($filesize > $targetMaxSizeKB) 
        {
          $maxQuality = $quality;
          $quality = (int)(($minQuality + $quality) / 2);
        } 
        else if ($filesize < $targetMinSizeKB) 
        {
          $minQuality = $quality;
          $quality = (int)(($maxQuality + $quality) / 2);
        }
        
      } while (($filesize > $targetMaxSizeKB || $filesize < $targetMinSizeKB) && $minQuality <= $maxQuality);      
      
      if (file_put_contents($destination, $content) === false) // Save the compressed image
      {
        $message = 'Failed to save the image.';
        imagedestroy($image);
        return ['flag' => $flag, 'message' => $message];
      }      
      
      imagedestroy($image);// Free up memory
      
      $flag = $filesize >= $targetMinSizeKB && $filesize <= $targetMaxSizeKB;
      
      if (!$flag) 
      {
        $message = $filesize > $targetMaxSizeKB ? 'The image size is too large.' : 'The image size is too small.';
      }
      
      return ['flag' => $flag, 'message' => $message];
    }


    function compressImage($source, $destination, $targetMinSizeKB, $targetMaxSizeKB, $maxWidth = null, $maxHeight = null) 
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

    function test()
    {
      // Specify the source image path
      $sourceImagePath = 'uploads/iibfbcbf/photo_temp/photo_20240522112615_5583.jpg';  // Replace with your image path
      $destinationImagePath = 'uploads/iibfbcbf/photo_temp/compress_photo_20240522112615_5583.jpg';  // Replace with your desired output path
      $targetMinSizeKB = '14';
      $targetMaxSizeKB = '20';
      if ($this->compressImage($sourceImagePath, $destinationImagePath, $targetMinSizeKB, $targetMaxSizeKB)) 
      {
        echo "Image successfully compressed to under {$targetMaxSizeKB}KB";
      } else {
          echo "Could not compress image to under {$targetMaxSizeKB}KB";
      }
    }

    function delete_old_folders()
    {
      //START: GET ALL FOLDER LIST FROM BELOW FOLDERS AND DELETE ALL DATA EXCEPT TODAY & YESTERDAY   
      $folder_arr = array('uploads/iibfbcbf/photo_temp', 'uploads/iibfbcbf/sign_temp', 'uploads/iibfbcbf/id_proof_temp', 'uploads/iibfbcbf/qualification_certificate_temp');   
      $not_delete_folder_arr = array(date('Ymd'), date('Ymd', strtotime("-1days")));
      
      foreach($folder_arr as $dir_name)
      {
        //$baseDir = '/path/to/your/base/directory';
        $baseDir = rtrim($dir_name, '/') . '/';// Ensure the base directory ends with a slash      
        $directories = glob($baseDir . '*', GLOB_ONLYDIR);// Get all directories in the base directory
        
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
      //END: GET ALL FOLDER LIST FROM BELOW FOLDERS AND DELETE ALL DATA EXCEPT TODAY & YESTERDAY
    }
  }