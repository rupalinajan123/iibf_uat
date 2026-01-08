<tr><td class="text-center heading_row" colspan="2" ><b>Basic Details</b></td></tr>

<tr>
  <td class="wrap" width="50%"><b>Training ID</b> : <?php echo $candidate_data[0]['training_id']; ?></td>
  <td class="wrap"><b>Registration No.</b> : <?php echo $candidate_data[0]['regnumber']; ?></td>
</tr>

<?php 
  $chk_salutation = $candidate_data[0]['salutation'];
?>  

<tr>
  <td class="wrap">
    <b>Candidate Full Name</b> : 
    <?php echo $chk_salutation . " " . $candidate_data[0]['first_name']; 
    if($candidate_data[0]['middle_name'] != "") { echo " ".$candidate_data[0]['middle_name']; } 
    if($candidate_data[0]['last_name'] != "") { echo " ".$candidate_data[0]['last_name']; } ?>
  </td>
  <td class="wrap"><b>Gender</b> : <?php echo $candidate_data[0]['DispGender']; ?></td>
</tr>

<?php 
  $chk_guardian_salutation = $candidate_data[0]['guardian_salutation'];
?>

<tr>
  <td class="wrap" colspan="2">
    <b>Guardian Full Name</b> : 
    <?php echo $chk_guardian_salutation . " " . $candidate_data[0]['guardian_name']; ?>
  </td>
  <!-- <td class="wrap"></td> -->
</tr>

<tr>
  <td class="wrap"><b>Email id</b> : <?php echo $candidate_data[0]['email_id']; ?></td>
  <td class="wrap"><b>Mobile Number</b> : <?php echo $candidate_data[0]['mobile_no']; ?></td>
</tr>
<?php 
  if($candidate_data[0]['dob'] != '0000-00-00') { 
     $dob = new DateTime($candidate_data[0]['dob']); 
     $today = new DateTime(); 
     $age = $today->diff($dob)->y; 
  } 
?>
<tr>
  <td class="wrap"><b>Date of Birth</b> : <?php echo $candidate_data[0]['dob']; ?></td>
  <td class="wrap"><b>Age</b> : <?php echo $age; ?></td>
</tr>

<tr>  
  <td class="wrap"><b>Person with Benchmark Disability</b> : <?php echo $candidate_data[0]['benchmark_disability'] == 'Y' ? 'Yes':'No'; ?></td>
  <?php if($candidate_data[0]['benchmark_disability'] == 'Y') { ?>
    <td class="wrap"><b>Visually impaired</b> : <?php echo $candidate_data[0]['visually_impaired'] == 'Y' ? 'Yes':'No'; ?></td>
  <?php } else { ?>  
    <td class="wrap"><b> Status</b> : <?php echo $candidate_data[0]['is_active']==1 ? 'Active' : 'Deactive'; ?></td>
  <?php } ?>
</tr>    

<?php if($candidate_data[0]['benchmark_disability'] == 'Y') { ?>
  <tr>
    <td class="wrap"><b>Orthopedically Handicapped</b> : <?php echo $candidate_data[0]['orthopedically_handicapped'] == 'Y' ? 'Yes':'No'; ?></td>
    <td class="wrap"><b>Cerebral Palsy</b> : <?php echo $candidate_data[0]['cerebral_palsy'] == 'Y' ? 'Yes':'No'; ?></td>
  </tr>
<?php } ?>

<?php
  $benchmark_kyc_status = 'Not Applicable'; 
  switch ($candidate_data[0]['benchmark_kyc_status']) {
    case '0':
      $benchmark_kyc_status = 'Pending';
      break;
    case '1':
      $benchmark_kyc_status = 'In Progress';
      break;
    case '2':
      $benchmark_kyc_status = 'Approved';
      break;
    case '3':
      $benchmark_kyc_status = 'Rejected';
      break;      
    default:
      $benchmark_kyc_status = 'Not Applicable';
      break;
  }

  $kyc_status = 'Pending'; 
  switch ($candidate_data[0]['kyc_status']) {
    case '0':
      $kyc_status = 'Pending';
      break;
    case '1':
      $kyc_status = 'In Progress';
      break;
    case '2':
      $kyc_status = 'Approved';
      break;
    case '3':
      $kyc_status = 'Rejected';
      break;      
    default:
      $kyc_status = 'Pending';
      break;
  }
?>

<tr>
  <td class="wrap"><b>KYC Status</b> : <?php echo $kyc_status; ?></td>
  <td class="wrap"><b>Benchmark KYC Status</b> : <?php echo $benchmark_kyc_status; ?></td>
</tr>

<?php if($candidate_data[0]['benchmark_disability'] == 'Y') { ?>
  <tr>
    <td class="wrap"><b> Status</b> : <?php echo $candidate_data[0]['is_active']==1 ? 'Active' : 'Inactive'; ?></td>
    <td class="wrap"></td>
  </tr>
<?php } ?>

<tr><td class="empty_row" colspan="2"></td></tr>

<tr><td class="text-center heading_row" colspan="2"><b>Contact Details</b></td></tr>
<tr>
  <td class="wrap"> <b style="font-size: 20px;">Communication Address</b> </td>
  <td class="wrap"> <b style="font-size: 20px;">Permanant Address</b> </td>
</tr>
<tr>
  <td class="wrap"><b>Address Line-1</b> : <?php echo $candidate_data[0]['address1']; ?></td>
  <td class="wrap"><b>Address Line-1</b> : <?php echo $candidate_data[0]['address1_pr']; ?></td>
</tr>

<tr>
  <td class="wrap"><b>Address Line-2</b> : <?php echo $candidate_data[0]['address2']; ?></td>
  <td class="wrap"><b>Address Line-2</b> : <?php echo $candidate_data[0]['address2_pr']; ?></td>
</tr>

<tr>
  <td class="wrap"><b>Address Line-2</b> : <?php echo $candidate_data[0]['address2']; ?></td>
  <td class="wrap"><b>Address Line-2</b> : <?php echo $candidate_data[0]['address2_pr']; ?></td>
</tr>

<tr>
  <td class="wrap"><b>Address Line-2</b> : <?php echo $candidate_data[0]['address3']; ?></td>
  <td class="wrap"><b>Address Line-2</b> : <?php echo $candidate_data[0]['address3_pr']; ?></td>
</tr>

<tr>
  <td class="wrap"><b>State</b> : <?php echo $state_master_data[0]['state_name']; ?></td>
  <td class="wrap"><b>State</b> : <?php echo $pr_state_master_data[0]['state_name']; ?></td>
</tr>

<?php 
  $selected_state_val = $candidate_data[0]['state'];                              
  if($selected_state_val != "")
  {
    $city_data = $this->master_model->getRecords('city_master', array('state_code' => $selected_state_val, 'city_delete' => '0','id'=>$candidate_data[0]['city']), 'id, city_name', array('city_name'=>'ASC'));
  }
  
  $selected_pr_state_val = $form_data[0]['state_pr'];
                                
  if($selected_pr_state_val != "")
  {
    $city_pr_data = $this->master_model->getRecords('city_master', array('state_code' => $selected_pr_state_val, 'city_delete' => '0','id'=>$form_data[0]['city_pr']), 'id, city_name', array('city_name'=>'ASC'));
  }  
?>  

<tr>
  <td class="wrap"><b>City</b> : <?php echo isset($city_data[0]['city_name']) ? $city_data[0]['city_name']:''; ?></td>
  <td class="wrap"><b>City</b> : <?php echo isset($city_pr_data[0]['city_name']) ? $city_pr_data[0]['city_name']:''; ?></td>
</tr>
<tr>
  <td class="wrap"><b>District</b> : <?php echo $candidate_data[0]['district']; ?></td>
  <td class="wrap"><b>District</b> : <?php echo $candidate_data[0]['district_pr']; ?></td>
</tr>
<tr>
  <td class="wrap"><b>Pincode</b> : <?php echo $candidate_data[0]['pincode']; ?></td>
  <td class="wrap"><b>Pincode</b> : <?php echo $candidate_data[0]['pincode_pr']; ?></td>
</tr>

<tr><td class="empty_row" colspan="2"></td></tr>

<tr><td class="text-center heading_row" colspan="2"><b>Other Details</b></td></tr>

<tr>
  <td class="wrap" style="word-break: break-word;"><b>Eligibility</b> : <?php echo $qualification_arr[$candidate_data[0]['qualification']]; ?></td>
  <?php if($candidate_data[0]['qualification'] == 1) { ?>
  <td class="wrap"><b>Experience More than 1.5 Year in BFSI</b> : <?php echo $candidate_data[0]['experience'] == 'Y' ? 'Yes':'No'; ?></td>
  <?php } else { ?>
    <td class="wrap"><b>Aadhar Card Number</b> : <?php echo $candidate_data[0]['aadhar_no']; ?></td>
  <?php } ?> 
</tr>

<?php if ($candidate_data[0]['qualification'] == 3 || $candidate_data[0]['qualification'] == 4) { ?>  
  <tr>
    <td colspan="2" class="wrap"><b>Semester</b> : <?php echo $candidate_data[0]['semester']; ?></td>
  </tr>
  <tr>  
    <td colspan="2" class="wrap"><b>Name of the College/Academic Institution</b> : <?php echo $candidate_data[0]['collage']; ?></td>
  </tr>
  <tr>
    <td colspan="2" class="wrap"><b>Name of the University</b> : <?php echo $candidate_data[0]['university']; ?></td>
  </tr>  
<?php } ?>

<tr>
  <?php if($candidate_data[0]['qualification'] == 1) { ?>
    <td class="wrap"><b>Aadhar Card Number</b> : <?php echo $candidate_data[0]['aadhar_no']; ?></td>
    <td class="wrap"><b>APAAR ID/ABC ID</b> : <?php echo $candidate_data[0]['id_proof_number']; ?></td>
  <?php } else { ?>
    <td class="wrap"><b>APAAR ID/ABC ID</b> : <?php echo $candidate_data[0]['id_proof_number']; ?></td>
    <td class="wrap"></td>
  <?php } ?>   
</tr>

<?php 
  $qualification_el = $candidate_data[0]['qualification']; 
  
  $qualification_state_lable = 'State';
  switch ($qualification_el) {
    case '1':
      $qualification_state_lable = 'State of Working';
      break;
    case '2':
      $qualification_state_lable = 'State of Degree College';
      break;
    case '3':
      $qualification_state_lable = 'State of College / Academic Institution';
      break;
    case '4':
      $qualification_state_lable = 'State of College / Academic Institution';
      break;    
    default:
      // code...
      break;
  }
?>  

<tr>
  <td colspan="2" class="wrap"><b><?php echo $qualification_state_lable; ?></b> : <?php echo $state_college_data[0]['state_name']; ?></td>
</tr>

<tr><td class="empty_row" colspan="2"></td></tr>

<tr><td class="text-center heading_row" colspan="2"><b>Document Details</b></td></tr>
<?php 
  $qualification_cert_lable = '';
  if($candidate_data[0]['qualification'] == '1')
  {
    $qualification_cert_lable = '12th Pass Certificate';
  }

  if($candidate_data[0]['qualification'] == '2')
  {
    $qualification_cert_lable = 'Degree Certificate/ Provisional degree certificate';
  }

  $preview_candidate_id = $candidate_data[0]['candidate_id']; 
  $preview_first_name   = $candidate_data[0]['first_name']; 
  $preview_training_id  = $candidate_data[0]['training_id'];

?>


<tr>
  <td class="wrap"><b>Passport Photograph of the Candidate</b> : 
      <div id="candidate_photo_preview" class="upload_img_preview">
      <?php if ($candidate_data[0]['candidate_photo'] != "")
      { ?>
        <a href="<?php echo base_url($candidate_photo_path . '/' . $candidate_data[0]['candidate_photo']) . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Passport Photograph of the Candidate - '.$candidate_data[0]['first_name'].' ('.$candidate_data[0]['training_id'].')'; ?>">
          <img src="<?php echo base_url($candidate_photo_path . '/' . $candidate_data[0]['candidate_photo']) . "?" . time(); ?>">
        </a>
      <?php 
      } 
      else
      {
        echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
      }  ?>
      </div>
  </td>
  <td class="wrap"><b>Signature of the Candidate</b> : 
    <div id="candidate_sign_preview" class="upload_img_preview">
      <?php if ($candidate_data[0]['candidate_sign'] != "")
      { ?>
        <a href="<?php echo base_url($candidate_sign_path . '/' . $candidate_data[0]['candidate_sign']) . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Signature of the Candidate - '.$candidate_data[0]['first_name'].' ('.$candidate_data[0]['training_id'].')'; ?>">
          <img src="<?php echo base_url($candidate_sign_path . '/' . $candidate_data[0]['candidate_sign']) . "?" . time(); ?>">
        </a>
    <?php }
      else
      {
        echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
      }  ?>
    </div>
  </td>
</tr>

<tr>
  <td class="wrap"><b>APAAR ID/ABC ID</b> : 
      <div id="id_proof_file_preview" class="upload_img_preview">
      <?php 
        $preview_id_proof_file = '';
        if($candidate_data[0]['id_proof_file'] != "") 
        { 
          $preview_id_proof_file = $candidate_data[0]['id_proof_file'];
          $preview_id_proof_file = base_url($id_proof_file_path.'/'.$preview_id_proof_file); 
        }

      if($preview_id_proof_file != "" && strtolower(pathinfo($candidate_data[0]['id_proof_file'], PATHINFO_EXTENSION)) !== "pdf" && strtolower(pathinfo($candidate_data[0]['id_proof_file'], PATHINFO_EXTENSION)) !== "")
      { ?>
        <a href="<?php echo $preview_id_proof_file."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'APAAR ID/ABC ID - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
            <img src="<?php echo $preview_id_proof_file."?".time(); ?>">
        </a>
      <?php 
      }
      else if($preview_id_proof_file != "" && strtolower(pathinfo($candidate_data[0]['id_proof_file'], PATHINFO_EXTENSION)) === "pdf" && strtolower(pathinfo($candidate_data[0]['id_proof_file'], PATHINFO_EXTENSION)) !== "")
      { ?>   
        <a data-caption="<?php echo 'APAAR ID/ABC ID - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>" data-fancybox data-type="iframe" data-src="https://mozilla.github.io/pdf.js/web/viewer.html?file=<?php echo $preview_id_proof_file."?".time(); ?>" href="javascript:;" title="<?php echo 'Aadhar File - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
          <img src="<?php echo base_url('assets/images/pdf.png'); ?>" width="60" height="60" alt="PDF"> 
        </a>
      <?php 
      } 
      else
      {
        echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
      }  
      ?>
      </div>
  </td>
  
  <td class="wrap"><b>Aadhar Card</b> : 
    <div id="aadhar_file_preview" class="upload_img_preview">
    <?php 
      $preview_aadhar_file = '';
      if($candidate_data[0]['aadhar_file'] != "") 
      { 
        $preview_aadhar_file = $candidate_data[0]['aadhar_file'];
        $preview_aadhar_file = base_url($aadhar_file_path.'/'.$preview_aadhar_file); 
      }

    if($preview_aadhar_file != "" && strtolower(pathinfo($candidate_data[0]['aadhar_file'], PATHINFO_EXTENSION)) !== "pdf" && strtolower(pathinfo($candidate_data[0]['aadhar_file'], PATHINFO_EXTENSION)) !== "")
    { ?>
        <a href="<?php echo $preview_aadhar_file."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Aadhar File - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
            <img src="<?php echo $preview_aadhar_file."?".time(); ?>">
        </a>
    <?php 
    }
    else if($preview_aadhar_file != "" && strtolower(pathinfo($candidate_data[0]['aadhar_file'], PATHINFO_EXTENSION)) === "pdf" && strtolower(pathinfo($candidate_data[0]['aadhar_file'], PATHINFO_EXTENSION)) !== "")
    { ?> 
        <a data-caption="<?php echo 'Aadhar File - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>" data-fancybox data-type="iframe" data-src="https://mozilla.github.io/pdf.js/web/viewer.html?file=<?php echo $preview_aadhar_file."?".time(); ?>" href="javascript:;" title="<?php echo 'Aadhar File - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
          <img src="<?php echo base_url('assets/images/pdf.png'); ?>" width="60" height="60" alt="PDF"> 
        </a>
    <?php 
    }
    else
    {
      echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
    }  ?>
    </div>
  </td>
</tr>

<tr>
  <?php if($candidate_data[0]['qualification'] == '1' || $candidate_data[0]['qualification'] == '2') { ?>
    <td class="wrap">
      <b><?php echo $qualification_cert_lable; ?></b> : 
      <div id="qualification_certificate_file_preview" class="upload_img_preview">
      <?php if ($candidate_data[0]['qualification_certificate_file'] != "")
      { ?>
          <?php 
              $preview_qualification_certificate_file = '';
              if($candidate_data[0]['qualification_certificate_file'] != "") 
              { 
                $preview_qualification_certificate_file = $candidate_data[0]['qualification_certificate_file'];
                $preview_qualification_certificate_file = base_url($qualification_certificate_file_path.'/'.$preview_qualification_certificate_file); 
              }
              if($preview_qualification_certificate_file != "" && strtolower(pathinfo($candidate_data[0]['qualification_certificate_file'], PATHINFO_EXTENSION)) !== "pdf" && strtolower(pathinfo($candidate_data[0]['qualification_certificate_file'], PATHINFO_EXTENSION)) !== "")
              { ?>
                <a href="<?php echo $preview_qualification_certificate_file."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo $qualification_cert_lable.' - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                  <img src="<?php echo $preview_qualification_certificate_file."?".time(); ?>">
              </a>
              <?php 
              }
              else if($preview_qualification_certificate_file != "" && strtolower(pathinfo($candidate_data[0]['qualification_certificate_file'], PATHINFO_EXTENSION)) === "pdf" && strtolower(pathinfo($candidate_data[0]['qualification_certificate_file'], PATHINFO_EXTENSION)) !== "")
              { ?>

                <a data-caption="<?php echo $qualification_cert_lable.' - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>" data-fancybox data-type="iframe" data-src="https://mozilla.github.io/pdf.js/web/viewer.html?file=<?php echo $preview_qualification_certificate_file."?".time(); ?>" href="javascript:;" title="<?php echo $qualification_cert_lable.' - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                  <img src="<?php echo base_url('assets/images/pdf.png'); ?>" width="60" height="60" alt="PDF"> 
                </a>

        <?php }  
              else
              {
                echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
              } ?>
      <?php }   
            else
            {
              echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
            }  ?>
        </div>    
    </td>
  <?php } ?>

  <?php if($candidate_data[0]['qualification'] == '1') { ?>  
  <td class="wrap">
    <b>Upload Experience Certificate</b> : 
    
    <div id="id_proof_file_preview" class="upload_img_preview">
    <?php if ($candidate_data[0]['exp_certificate'] != "")
    { ?>    
        <?php 
            $preview_exp_certificate = '';
            if($candidate_data[0]['exp_certificate'] != "") 
            { 
              $preview_exp_certificate = $candidate_data[0]['exp_certificate'];
              $preview_exp_certificate = base_url($exp_certificate_path.'/'.$preview_exp_certificate); 
            }
            
            if($preview_exp_certificate != "" && strtolower(pathinfo($candidate_data[0]['exp_certificate'], PATHINFO_EXTENSION)) === "pdf" && strtolower(pathinfo($candidate_data[0]['exp_certificate'], PATHINFO_EXTENSION)) !== "")
            { ?>

              <a data-caption="<?php echo 'Experience Certificate - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>" data-fancybox data-type="iframe" data-src="https://mozilla.github.io/pdf.js/web/viewer.html?file=<?php echo $preview_exp_certificate."?".time(); ?>" href="javascript:;" title="<?php echo 'Experience Certificate - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                <img src="<?php echo base_url('assets/images/pdf.png'); ?>" width="60" height="60" alt="PDF"> 
              </a>

      <?php }
            if($preview_exp_certificate != "" && strtolower(pathinfo($candidate_data[0]['exp_certificate'], PATHINFO_EXTENSION)) !== "pdf" && strtolower(pathinfo($form_data[0]['exp_certificate'], PATHINFO_EXTENSION)) !== "")
            { ?>

              <a href="<?php echo $preview_exp_certificate."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Experience Certificate - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                <img src="<?php echo $preview_exp_certificate."?".time(); ?>">
              </a>
      <?php }  
            else
            {
              echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
            } ?>
    <?php }   
          else
          {
            echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
          } ?>
      </div>    
    </td>
  <?php } ?>

  <?php if($candidate_data[0]['qualification'] == '3' || $candidate_data[0]['qualification'] == '4') { ?>
    <td class="wrap">
      <b>Institutional ID</b> : 
      <div id="institute_idproof_preview" class="upload_img_preview">
        <?php if ($candidate_data[0]['institute_idproof'] != "")
        { ?>
          <?php 
              $preview_institute_idproof = '';
              if($candidate_data[0]['institute_idproof'] != "") 
              { 
                $preview_institute_idproof = $candidate_data[0]['institute_idproof'];
                $preview_institute_idproof = base_url($institute_idproof_path.'/'.$preview_institute_idproof); 
              }
              if($preview_institute_idproof != "" && strtolower(pathinfo($candidate_data[0]['institute_idproof'], PATHINFO_EXTENSION)) !== "pdf" && strtolower(pathinfo($candidate_data[0]['institute_idproof'], PATHINFO_EXTENSION)) !== "")
              { ?>
                <a href="<?php echo $preview_institute_idproof."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Institutional ID - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                  <img src="<?php echo $preview_institute_idproof."?".time(); ?>">
              </a>
              <?php 
              }
              else if($preview_institute_idproof != "" && strtolower(pathinfo($candidate_data[0]['institute_idproof'], PATHINFO_EXTENSION)) === "pdf" && strtolower(pathinfo($candidate_data[0]['institute_idproof'], PATHINFO_EXTENSION)) !== "")
              { ?>

                <a data-caption="<?php echo 'Institutional ID - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>" data-fancybox data-type="iframe" data-src="https://mozilla.github.io/pdf.js/web/viewer.html?file=<?php echo $preview_institute_idproof."?".time(); ?>" href="javascript:;" title="<?php echo $qualification_cert_lable.' - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                  <img src="<?php echo base_url('assets/images/pdf.png'); ?>" width="60" height="60" alt="PDF"> 
                </a>

        <?php }  
              else
              {
                echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
              } ?>
      <?php }   
            else
            {
              echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
            }  ?>
        </div>    
    </td>
    
    <td class="wrap">
      <b>Declaration</b> : 
      <div id="declarationform_preview" class="upload_img_preview">
        <?php if ($candidate_data[0]['declarationform'] != "")
        { ?>
          <?php 
              $preview_declarationform = '';
              if($candidate_data[0]['declarationform'] != "") 
              { 
                $preview_declarationform = $candidate_data[0]['declarationform'];
                $preview_declarationform = base_url($declarationform_path.'/'.$preview_declarationform); 
              }
              if($preview_declarationform != "" && strtolower(pathinfo($candidate_data[0]['declarationform'], PATHINFO_EXTENSION)) !== "pdf" && strtolower(pathinfo($candidate_data[0]['declarationform'], PATHINFO_EXTENSION)) !== "")
              { ?>
                <a href="<?php echo $preview_declarationform."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Declaration - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                  <img src="<?php echo $preview_declarationform."?".time(); ?>">
              </a>
              <?php 
              }
              else if($preview_declarationform != "" && strtolower(pathinfo($candidate_data[0]['declarationform'], PATHINFO_EXTENSION)) === "pdf" && strtolower(pathinfo($candidate_data[0]['declarationform'], PATHINFO_EXTENSION)) !== "")
              { ?>

                <a data-caption="<?php echo 'Declaration - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>" data-fancybox data-type="iframe" data-src="https://mozilla.github.io/pdf.js/web/viewer.html?file=<?php echo $preview_declarationform."?".time(); ?>" href="javascript:;" title="<?php echo $qualification_cert_lable.' - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                  <img src="<?php echo base_url('assets/images/pdf.png'); ?>" width="60" height="60" alt="PDF"> 
                </a>

        <?php }  
              else
              {
                echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
              } ?>
      <?php }   
            else
            {
              echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
            }  ?>
        </div>    
      </td>
  <?php } ?>
</tr>


<?php $disabilityCount = 0; if($candidate_data[0]['benchmark_disability'] == 'Y') { ?>  
  <tr>
    <?php if($candidate_data[0]['visually_impaired'] == 'Y') { $disabilityCount++; ?>
    <td class="wrap">
      <b>Visually Impaired Certificate</b> : 
      <div id="vis_imp_cert_img_preview" class="upload_img_preview">
      <?php if ($candidate_data[0]['vis_imp_cert_img'] != "")
      { ?>
          <?php 
              $preview_vis_imp_cert_img = '';
              if($candidate_data[0]['vis_imp_cert_img'] != "") 
              { 
                $preview_vis_imp_cert_img = $candidate_data[0]['vis_imp_cert_img'];
                $preview_vis_imp_cert_img = base_url($disability_cert_img_path.'/'.$preview_vis_imp_cert_img); 
              }
              if($preview_vis_imp_cert_img != "" && strtolower(pathinfo($candidate_data[0]['vis_imp_cert_img'], PATHINFO_EXTENSION)) !== "pdf" && strtolower(pathinfo($candidate_data[0]['vis_imp_cert_img'], PATHINFO_EXTENSION)) !== "")
              { ?>
                <a href="<?php echo $preview_vis_imp_cert_img."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo ' Visually Impaired Certificate - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                  <img src="<?php echo $preview_vis_imp_cert_img."?".time(); ?>">
              </a>
              <?php 
              }
              else if($preview_vis_imp_cert_img != "" && strtolower(pathinfo($candidate_data[0]['vis_imp_cert_img'], PATHINFO_EXTENSION)) === "pdf" && strtolower(pathinfo($candidate_data[0]['vis_imp_cert_img'], PATHINFO_EXTENSION)) !== "")
              { ?>

                <a data-caption="<?php echo $qualification_cert_lable.' - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>" data-fancybox data-type="iframe" data-src="https://mozilla.github.io/pdf.js/web/viewer.html?file=<?php echo $preview_vis_imp_cert_img."?".time(); ?>" href="javascript:;" title="<?php echo ' Visually Impaired Certificate  - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                  <img src="<?php echo base_url('assets/images/pdf.png'); ?>" width="60" height="60" alt="PDF"> 
                </a>
        <?php }  
              else
              {
                echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
              } ?>
      <?php }   
            else
            {
              echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
            }  ?>
        </div>    
    </td>
    <?php } ?>

    <?php if($candidate_data[0]['orthopedically_handicapped'] == 'Y') { $disabilityCount++; ?>
    <td class="wrap">
      <b>Orthopedically Handicapped Certificate</b> : 
      <div id="orth_han_cert_img_preview" class="upload_img_preview">
      <?php if ($candidate_data[0]['orth_han_cert_img'] != "")
      { ?>
          <?php 
              $preview_orth_han_cert_img = '';
              if($candidate_data[0]['orth_han_cert_img'] != "") 
              { 
                $preview_orth_han_cert_img = $candidate_data[0]['orth_han_cert_img'];
                $preview_orth_han_cert_img = base_url($disability_cert_img_path.'/'.$preview_orth_han_cert_img); 
              }
              if($preview_orth_han_cert_img != "" && strtolower(pathinfo($candidate_data[0]['orth_han_cert_img'], PATHINFO_EXTENSION)) !== "pdf" && strtolower(pathinfo($candidate_data[0]['orth_han_cert_img'], PATHINFO_EXTENSION)) !== "")
              { ?>
                <a href="<?php echo $preview_orth_han_cert_img."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo ' Orthopedically Handicapped Certificate - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                  <img src="<?php echo $preview_orth_han_cert_img."?".time(); ?>">
              </a>
              <?php 
              }
              else if($preview_orth_han_cert_img != "" && strtolower(pathinfo($candidate_data[0]['orth_han_cert_img'], PATHINFO_EXTENSION)) === "pdf" && strtolower(pathinfo($candidate_data[0]['orth_han_cert_img'], PATHINFO_EXTENSION)) !== "")
              { ?>

                <a data-caption="<?php echo $qualification_cert_lable.' - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>" data-fancybox data-type="iframe" data-src="https://mozilla.github.io/pdf.js/web/viewer.html?file=<?php echo $preview_orth_han_cert_img."?".time(); ?>" href="javascript:;" title="<?php echo ' Orthopedically Handicapped Certificate - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                  <img src="<?php echo base_url('assets/images/pdf.png'); ?>" width="60" height="60" alt="PDF"> 
                </a>
        <?php }  
              else
              {
                echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
              } ?>
      <?php }   
            else
            {
              echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
            }  ?>
        </div>    
    </td>
    <?php } ?>
    <?php if($disabilityCount == 2) { ?>
     </tr> 
    <?php } ?>
    <?php if($candidate_data[0]['orthopedically_handicapped'] == 'Y') { ?>
      <?php if($disabilityCount == 2) { ?>
      <tr> 
      <?php } ?>
      <td class="wrap">
        <b>Cerebral Palsy Certificate</b> : 
        <div id="cer_palsy_cert_img_preview" class="upload_img_preview">
        <?php if ($candidate_data[0]['cer_palsy_cert_img'] != "")
        { ?>
            <?php 
                $preview_cer_palsy_cert_img = '';
                if($candidate_data[0]['cer_palsy_cert_img'] != "") 
                { 
                  $preview_cer_palsy_cert_img = $candidate_data[0]['cer_palsy_cert_img'];
                  $preview_cer_palsy_cert_img = base_url($disability_cert_img_path.'/'.$preview_cer_palsy_cert_img); 
                }
                if($preview_cer_palsy_cert_img != "" && strtolower(pathinfo($candidate_data[0]['cer_palsy_cert_img'], PATHINFO_EXTENSION)) !== "pdf" && strtolower(pathinfo($candidate_data[0]['cer_palsy_cert_img'], PATHINFO_EXTENSION)) !== "")
                { ?>
                  <a href="<?php echo $preview_cer_palsy_cert_img."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo ' Cerebral Palsy Certificate - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                    <img src="<?php echo $preview_cer_palsy_cert_img."?".time(); ?>">
                  </a>
                <?php 
                }
                else if($preview_cer_palsy_cert_img != "" && strtolower(pathinfo($candidate_data[0]['cer_palsy_cert_img'], PATHINFO_EXTENSION)) === "pdf" && strtolower(pathinfo($candidate_data[0]['cer_palsy_cert_img'], PATHINFO_EXTENSION)) !== "")
                { ?>

                  <a data-caption="<?php echo $qualification_cert_lable.' - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>" data-fancybox data-type="iframe" data-src="https://mozilla.github.io/pdf.js/web/viewer.html?file=<?php echo $preview_cer_palsy_cert_img."?".time(); ?>" href="javascript:;" title="<?php echo ' Cerebral Palsy Certificate - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                    <img src="<?php echo base_url('assets/images/pdf.png'); ?>" width="60" height="60" alt="PDF"> 
                  </a>
          <?php }  
                else
                {
                  echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
                } ?>
        <?php }   
              else
              {
                echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
              }  ?>
          </div>    
      </td>
      <?php } ?>
      <?php if($disabilityCount == 2) { ?>
        <td class="wrap"></td>
      </tr> 
      <?php } ?>
<?php } ?>
