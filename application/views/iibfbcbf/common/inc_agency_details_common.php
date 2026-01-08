<tr><td class="text-center heading_row" colspan="2" ><b>Agency Details</b></td></tr>

<tr>
  <td>
    <b>Agency Name</b> : 
    <?php echo $agency_data[0]['agency_name']; 
    $disp_agency_type = $agency_data[0]['allow_exam_types'];
    if($agency_data[0]['allow_exam_types'] == "Bulk/Individual") { $disp_agency_type = "Regular"; }
    
    echo " (".$agency_data[0]['agency_code']." - ".$disp_agency_type.")"; ?></td>
  <td><b>Establishment Year</b> : <?php echo $agency_data[0]['estb_year']; ?></td>
</tr>

<tr>
  <td><b>Address Line-1</b> : <?php echo $agency_data[0]['agency_address1']; ?></td>
  <td><b>Address Line-2</b> : <?php echo $agency_data[0]['agency_address2']; ?></td>
</tr>

<tr>
  <td><b>Address Line-3</b> : <?php echo $agency_data[0]['agency_address3']; ?></td>
  <td><b>Address Line-4</b> : <?php echo $agency_data[0]['agency_address4']; ?></td>
</tr>

<tr>
  <td><b>State</b> : <?php echo $agency_data[0]['state_name']; ?></td>
  <td><b>City</b> : <?php echo $agency_data[0]['city_name']; ?></td>
</tr>

<tr>
  <td><b>District</b> : <?php echo $agency_data[0]['agency_district']; ?></td>
  <td><b>Pincode</b> : <?php echo $agency_data[0]['agency_pincode']; ?></td>
</tr>

<tr>
  <td><b>Contact Person Name</b> : <?php echo $agency_data[0]['contact_person_name']; ?></td>
  <td><b>Contact Person Designation</b> : <?php echo $agency_data[0]['contact_person_designation']; ?></td>
</tr>

<tr>
  <td><b>Contact Person Mobile Number</b> : <?php echo $agency_data[0]['contact_person_mobile']; ?></td>
  <td><b>Contact Person Email id</b> : <?php echo $agency_data[0]['contact_person_email']; ?></td>
</tr>

<tr>
  <td><b>Agency Code</b> : <?php echo $agency_data[0]['agency_code']; ?></td>
  <td>
    <?php if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'admin') { ?>
      <b>Password</b> : <?php echo $this->Iibf_bcbf_model->password_decryption($agency_data[0]['agency_password']); ?>
    <?php }
    else { ?>
      <b>Registration Date</b> : <?php echo date("Y-m-d", strtotime($agency_data[0]['created_on'])); ?>
    <?php } ?>
  </td>
</tr>

<tr>
  <td colspan="2" ><b>Centre GST No</b> : <?php echo $agency_data[0]['gst_no']; ?></td>
</tr>