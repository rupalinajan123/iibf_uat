<tr><td class="text-center heading_row" colspan="2" ><b>Basic Details</b></td></tr>

<tr>
  <td>
    <b>Agency Name</b> : 
    <?php echo $candidate_data[0]['agency_name']; 
      $disp_agency_type = $candidate_data[0]['allow_exam_types'];
      if($candidate_data[0]['allow_exam_types'] == "Bulk/Individual") { $disp_agency_type = "Regular"; }
      
      echo " (".$candidate_data[0]['agency_code']." - ".$disp_agency_type.")"; 
    ?>
  </td>
  <td><b>Centre Name</b> : <?php echo $candidate_data[0]['centre_name']." (".$candidate_data[0]['centre_username']." - ".$candidate_data[0]['centre_city_name'].")"; ?></td>
</tr>

<tr>
  <td class="wrap"><b>Training ID</b> : <?php echo $candidate_data[0]['training_id']; ?></td>
  <td class="wrap"><b>Registration No.</b> : <?php echo $candidate_data[0]['regnumber']; ?></td>
</tr>

<tr>
  <td class="wrap">
    <b>Candidate Full Name</b> : 
    <?php echo $candidate_data[0]['salutation'] . " " . $candidate_data[0]['first_name']; 
    if($candidate_data[0]['middle_name'] != "") { echo " ".$candidate_data[0]['middle_name']; } 
    if($candidate_data[0]['last_name'] != "") { echo " ".$candidate_data[0]['last_name']; } ?>
  </td>
  <td class="wrap"><b>Date of Birth</b> : <?php echo $candidate_data[0]['dob']; ?></td>
</tr>

<tr>
  <td class="wrap"><b>Gender</b> : <?php echo $candidate_data[0]['DispGender']; ?></td>
  <td class="wrap"><b>Mobile Number</b> : <?php echo $candidate_data[0]['mobile_no']; ?></td>
</tr>

<tr>
  <td class="wrap"><b>Alternate Mobile Number</b> : <?php echo $candidate_data[0]['alt_mobile_no']; ?></td>
  <td class="wrap"><b>Email id</b> : <?php echo $candidate_data[0]['email_id']; ?></td>
</tr>

<tr>
  <td class="wrap"><b>Alternate Email id</b> : <?php echo $candidate_data[0]['alt_email_id']; ?></td>
  <td class="wrap"><b>Qualification</b> : <?php echo $candidate_data[0]['DispQualification']; ?></td>
</tr>
<tr><td class="empty_row" colspan="2"></td></tr>

<tr><td class="text-center heading_row" colspan="2"><b>Other Details</b></td></tr>

<tr>
  <td class="wrap"><b>Address Line-1</b> : <?php echo $candidate_data[0]['address1']; ?></td>
  <td class="wrap"><b>Address Line-2</b> : <?php echo $candidate_data[0]['address2']; ?></td>
</tr>

<tr>
  <td class="wrap"><b>Address Line-3</b> : <?php echo $candidate_data[0]['address3']; ?></td>
  <td class="wrap"><b>Address Line-4</b> : <?php echo $candidate_data[0]['address4']; ?></td>
</tr>

<tr>
  <td class="wrap"><b>State</b> : <?php echo $candidate_data[0]['state_name']; ?></td>
  <td class="wrap"><b>City</b> : <?php echo $candidate_data[0]['city_name']; ?></td>
</tr>
<tr>
  <td class="wrap"><b>District</b> : <?php echo $candidate_data[0]['district']; ?></td>
  <td class="wrap"><b>Pincode</b> : <?php echo $candidate_data[0]['pincode']; ?></td>
</tr>
<tr>
  <td class="wrap" colspan="2"><b>Bank Employee Id</b> : <?php echo $candidate_data[0]['bank_emp_id']; ?></td> 
</tr>
<tr>
  <td class="wrap"><b>Affiliated with the Bank as a BC</b> : <?php echo $candidate_data[0]['associated_with_any_bank']=="1"?"Yes":"No"; ?></td>
  <td class="wrap">
    <b>Bank associated with</b> : 
    <?php 
    if($candidate_data[0]['bank_associated'] != 'Other')
    {
      $BankAssociatedData = $this->master_model->getRecords('iibfbcbf_bank_associated_master', array('bank_code' => $candidate_data[0]['bank_associated'], 'is_active' => '1', 'is_deleted' => '0'), "bank_name");
      if(count($BankAssociatedData) > 0)
      {
        echo $BankAssociatedData[0]['bank_name'];
      }
    } 
    else
    {
      echo $candidate_data[0]['bank_associated'];
    }
    
    if($candidate_data[0]['bank_associated'] == 'Other') { echo " - ".$candidate_data[0]['bank_associated_other']; } ?>
  </td>
</tr>

<?php 
if($candidate_data[0]['are_you_corporate_bc'] == "No")
{
?>
<tr>
  <td class="wrap" colspan="2"><b>Are you associated with a Corporate BC?</b> : <?php echo $candidate_data[0]['are_you_corporate_bc']; ?></td>
</tr>
<?php 
}else{
  if($candidate_data[0]['corporate_bc_option'] == "CSC")
  { 
?>
  <tr>
    <td class="wrap"><b>Are you associated with a Corporate BC?</b> : <?php echo $candidate_data[0]['are_you_corporate_bc']; ?></td>
    <td class="wrap"><b>Corporate BC</b> : <?php echo $candidate_data[0]['corporate_bc_option']; ?></td> 
  </tr>
<?php
  }
  else if($candidate_data[0]['corporate_bc_option'] == "Other")
  {
    ?>
  <tr>
    <td class="wrap"><b>Are you associated with a Corporate BC?</b> : <?php echo $candidate_data[0]['are_you_corporate_bc']; ?></td>
    <td class="wrap"><b>Corporate BC</b> : <?php echo $candidate_data[0]['corporate_bc_option']; ?></td> 
  </tr>
  <?php
  }
}

if($candidate_data[0]['corporate_bc_option'] == "Other"){
?> 
<tr>
  <td class="wrap" colspan="2"><b>Corporate BC associated with</b> : <?php echo $candidate_data[0]['corporate_bc_associated']; ?></td>
</tr>
<?php } ?>

<tr><td class="empty_row" colspan="2"></td></tr>

<tr><td class="text-center heading_row" colspan="2"><b>Document Details</b></td></tr>
<tr>
  <td class="wrap"><b>ID Proof Type</b> : <?php echo $candidate_data[0]['DispIdProofType']; ?></td>
  <td class="wrap"><b>Id Proof Number</b> : <?php echo $candidate_data[0]['id_proof_number']; ?></td>
</tr>
<tr>
  <td class="wrap" colspan="2"><b>Qualification Certificate Type</b> : <?php echo $candidate_data[0]['DispQualificationCertificateType']; ?></td>  
</tr>

<tr>
  <td class="wrap">
    <b>Proof of Identity</b> : 
    <?php if ($candidate_data[0]['id_proof_file'] != "")
    { ?>
      <div id="id_proof_file_preview" class="upload_img_preview">
        <a href="<?php echo base_url($id_proof_file_path . '/' . $candidate_data[0]['id_proof_file']) . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Proof of Identity - '.$candidate_data[0]['first_name'].' ('.$candidate_data[0]['training_id'].')'; ?>">
          <img src="<?php echo base_url($id_proof_file_path . '/' . $candidate_data[0]['id_proof_file']) . "?" . time(); ?>">
        </a>
      </div>
    <?php } ?>
  </td>
  <td class="wrap">
    <b>Qualification Certificate</b> : 
    <?php if ($candidate_data[0]['qualification_certificate_file'] != "")
    { ?>
      <div id="qualification_certificate_file_preview" class="upload_img_preview">
        <a href="<?php echo base_url($qualification_certificate_file_path . '/' . $candidate_data[0]['qualification_certificate_file']) . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Qualification Certificate - '.$candidate_data[0]['first_name'].' ('.$candidate_data[0]['training_id'].')'; ?>">
          <img src="<?php echo base_url($qualification_certificate_file_path . '/' . $candidate_data[0]['qualification_certificate_file']) . "?" . time(); ?>">
        </a>
      </div>
    <?php } ?>
  </td>
</tr>

<tr>
  <td class="wrap"><b>Passport Photograph of the Candidate</b> : 
    <?php if ($candidate_data[0]['candidate_photo'] != "")
    { ?>
      <div id="candidate_photo_preview" class="upload_img_preview">
        <a href="<?php echo base_url($candidate_photo_path . '/' . $candidate_data[0]['candidate_photo']) . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Passport Photograph of the Candidate - '.$candidate_data[0]['first_name'].' ('.$candidate_data[0]['training_id'].')'; ?>">
          <img src="<?php echo base_url($candidate_photo_path . '/' . $candidate_data[0]['candidate_photo']) . "?" . time(); ?>">
        </a>
      </div>
    <?php } ?>
  </td>
  <td class="wrap"><b>Signature of the Candidate</b> : 
    <?php if ($candidate_data[0]['candidate_sign'] != "")
    { ?>
      <div id="candidate_sign_preview" class="upload_img_preview">
        <a href="<?php echo base_url($candidate_sign_path . '/' . $candidate_data[0]['candidate_sign']) . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Signature of the Candidate - '.$candidate_data[0]['first_name'].' ('.$candidate_data[0]['training_id'].')'; ?>">
          <img src="<?php echo base_url($candidate_sign_path . '/' . $candidate_data[0]['candidate_sign']) . "?" . time(); ?>">
        </a>
      </div>
    <?php } ?>
  </td>
</tr>

<tr>
  <td class="wrap"><b>Aadhar Number</b> : <?php echo $candidate_data[0]['aadhar_no']; ?></td>
  <td class="wrap"><b>Hold / Release Status</b> : <?php echo $candidate_data[0]['Disphold_release_status']; ?></td>
</tr>