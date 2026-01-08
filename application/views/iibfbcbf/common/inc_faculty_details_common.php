<tr>
  <td colspan="2" class="text-center heading_row"><b>Faculty Details</b></td>
</tr>

<tr>
  <td <?php if($faculty_data[0]['created_by_type'] == '2' && $this->session->userdata('IIBF_BCBF_USER_TYPE') != 'centre') { echo 'colspan="2"'; } ?>>
    <b>Agency Name</b> : 
    <?php echo $faculty_data[0]['agency_name']; 
      $disp_agency_type = $faculty_data[0]['allow_exam_types'];
      if($faculty_data[0]['allow_exam_types'] == "Bulk/Individual") { $disp_agency_type = "Regular"; }
      
      echo " (".$faculty_data[0]['agency_code']." - ".$disp_agency_type.")"; 
    ?>
  </td>

  <?php if($faculty_data[0]['created_by_type'] == '1'){ ?>
    <td><b>Centre Name</b> : <?php echo $faculty_data[0]['centre_name']." (".$faculty_data[0]['centre_username']." - ".$faculty_data[0]['centre_city_name'].")"; ?></td>
  <?php } ?>
</tr>

<tr>
  <td><b>Faculty Number</b> : <?php echo $faculty_data[0]['faculty_number']; ?></td>
  <td><b>Faculty Name</b> : <?php echo $faculty_data[0]['salutation'] . ' ' . $faculty_data[0]['faculty_name']; ?></td>
</tr>

<tr>
  <td><b>Date of Birth</b> : <?php echo $faculty_data[0]['dob']; ?></td>
  <td><b>PAN No.</b> : <?php echo $faculty_data[0]['pan_no']; ?></td>
</tr>

<tr>
  <td>
    <b style="vertical-align:top">Faculty Photo</b> :
    <div id="faculty_photo_preview" class="upload_img_preview">
      <a href="<?php echo base_url($faculty_photo_path . '/' . $faculty_data[0]['faculty_photo']) . "?" . time(); ?>" class="example-image-link" data-lightbox="faculty_photo_<?php echo $faculty_data[0]['faculty_id']; ?>" data-title="<?php echo $faculty_data[0]['faculty_name'] . " (" . $faculty_data[0]['faculty_number'] . ")"; ?>">
        <img src="<?php echo base_url($faculty_photo_path . '/' . $faculty_data[0]['faculty_photo']) . "?" . time(); ?>">
      </a>
    </div>
  </td>

  <td><b style="vertical-align:top">PAN photo</b> :
    <div id="pan_photo_preview" class="upload_img_preview">
      <a href="<?php echo base_url($pan_photo_path . '/' . $faculty_data[0]['pan_photo']) . "?" . time(); ?>" class="example-image-link" data-lightbox="pan_photo_<?php echo $faculty_data[0]['faculty_id']; ?>" data-title="<?php echo $faculty_data[0]['faculty_name'] . " (" . $faculty_data[0]['faculty_number'] . ")"; ?>">
        <img src="<?php echo base_url($pan_photo_path . '/' . $faculty_data[0]['pan_photo']) . "?" . time(); ?>">
      </a>
    </div>
  </td>
</tr>

<tr>
  <td><b>Base Location</b> : <?php echo $faculty_data[0]['base_location']; ?></td>
  <td><b>Academic Qualification(s) with year of passing</b> : <?php echo $faculty_data[0]['academic_qualification']; ?></td>
</tr>

<tr>
  <td colspan="2"><b>Professional Qualification(s) if any, (including from IIBF) with year of passing</b> : <?php echo $faculty_data[0]['professional_qualification']; ?></td>
</tr>

<tr>
  <td colspan="2"><b>Language Known</b> : <?php echo $faculty_data[0]['language_known']; ?></td>
</tr>

<tr>
  <td colspan="2"><b>Work Experience</b> :
    <table class="table table-bordered mt-2" style="width:100%">
      <thead>
        <tr>
          <th class="text-center nowrap">Bank/ FI Name</th>
          <th class="text-center nowrap">Last Position held, Employee Id</th>
          <th class="text-center nowrap">Gross Duration Year</th>
          <th class="text-center nowrap">Gross Duration Month</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($work_exp_data as $work_exp_res)
        { ?>
          <tr>
            <td><?php echo $work_exp_res['bank_name']; ?></td>
            <td><?php echo $work_exp_res['last_position_employee_id']; ?></td>
            <td><?php echo $work_exp_res['experience_year']; ?></td>
            <td><?php echo $work_exp_res['experience_month']; ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </td>
</tr>

<tr>
  <td><b>Work Experience in Training, if any</b> : <?php echo $faculty_data[0]['work_exp_iibf']; ?></td>
  <td><b>Experience as Faculty in BC/BF training, if any</b> : <?php echo $faculty_data[0]['training_faculty_exp']; ?></td>
</tr>

<?php /* if ($faculty_data[0]['training_faculty_exp'] != "")
{ ?>
  <tr>
    <td><b>Period of Association with the agency in providing BCBF training (Year)</b> : <?php echo $faculty_data[0]['training_faculty_exp_year']; ?></td>
    <td><b>Period of Association with the agency in providing BCBF training (Month)</b> : <?php echo $faculty_data[0]['training_faculty_exp_month']; ?></td>
  </tr>
<?php } */ ?>

<tr>
  <td><b>Interested to take sessions on</b> : <?php echo $faculty_data[0]['intrested_session_name']; ?></td>
  <td><b>Qualification / Experience in Soft Skill in BFSI Sector, if any</b> : <?php echo $faculty_data[0]['softskills_banking_exp']; ?></td>
</tr>

<tr>
  <?php /* <td><b>Experience/Comments on training specific activities, if any</b> : <?php echo $faculty_data[0]['training_activities_exp'];  ?></td> */?>
  <td colspan="2"><b>Current Batches</b> : <?php echo $faculty_data[0]['CurrentBatches']; ?></td>
</tr>

<tr>
  <td colspan="2"><b>All Batches</b> : <?php echo $faculty_data[0]['AllBatches']; ?></td>
</tr>
<tr>
  <?php if($this->session->userdata('IIBF_BCBF_USER_TYPE') != 'centre')
  { ?>
    <td><b>Added By</b> : <?php if($faculty_data[0]['created_by_type'] == '1') { echo 'Centre'; } else { echo 'Agency'; } ?></td>
  <?php } ?>

  <td <?php if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'centre') { echo 'colspan="2"'; } ?>><b style="vertical-align:top">Status</b> : <span class="disp_status_details badge <?php echo show_faculty_status($faculty_data[0]['status']); ?>" style="min-width:90px;"><?php echo $faculty_data[0]['DispStatus']; ?></span></td>
</tr>