<tr>
  <td colspan="2" class="text-center heading_row"><b>Centre Details</b></td>
</tr>

<tr>
  <td>
    <b>Agency Name</b> : 
    <?php echo $centre_data[0]['agency_name']; 
      $disp_agency_type = $centre_data[0]['allow_exam_types'];
      if($centre_data[0]['allow_exam_types'] == "Bulk/Individual") { $disp_agency_type = "Regular"; }
      
      echo " (".$centre_data[0]['agency_code']." - ".$disp_agency_type.")"; ?>
    </td>
  <td><b>Centre Name</b> : <?php echo $centre_data[0]['centre_name']." (".$centre_data[0]['centre_username'].")"; ?></td>
</tr>

<tr>
  <td><b>Centre Address Line-1</b> : <?php echo $centre_data[0]['centre_address1']; ?></td>
  <td><b>Centre Address Line-2</b> : <?php echo $centre_data[0]['centre_address2']; ?></td>
</tr>

<tr>
  <td><b>Centre Address Line-3</b> : <?php echo $centre_data[0]['centre_address3']; ?></td>
  <td><b>Centre Address Line-4</b> : <?php echo $centre_data[0]['centre_address4']; ?></td>
</tr>

<tr>
  <td><b>Centre State</b> : <?php echo $centre_data[0]['state_name']; ?></td>
  <td><b>Centre City</b> : <?php echo $centre_data[0]['city_name']; ?></td>
  
</tr>

<tr>
  <td><b>Centre District</b> : <?php echo $centre_data[0]['centre_district']; ?></td>
  <td><b>Centre Pincode</b> : <?php echo $centre_data[0]['centre_pincode']; ?></td>
</tr>

<tr>
  <td><b>Centre Contact Number</b> : <?php echo $centre_data[0]['centre_mobile']; ?></td>
  <td><b>Name of contact Person</b> : <?php echo $centre_data[0]['centre_contact_person_name']; ?></td>
</tr>

<tr>
  <td><b>Contact Person Mobile Number</b> : <?php echo $centre_data[0]['centre_contact_person_mobile']; ?></td>
  <td><b>Contact Person Email id</b> : <?php echo $centre_data[0]['centre_contact_person_email']; ?></td>
</tr>

<tr>
  <td><b>Centre ID</b> : <?php echo $centre_data[0]['centre_username']; ?></td>
  <td>
    <?php if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'admin' || $this->session->userdata('IIBF_BCBF_USER_TYPE') == 'agency') { ?>
      <b>Centre Password</b> : <?php echo $this->Iibf_bcbf_model->password_decryption($centre_data[0]['centre_password']); ?></td>
    <?php } ?>
</tr>

<tr>
  <?php /* <td><b>Centre Type</b> : <?php echo $centre_data[0]['DispCentreType']; ?></td> */ ?>
  <td><b>Centre GST No</b> : <?php echo $centre_data[0]['gst_no']; ?></td>
  <td><b>Centre Remarks</b> : <?php echo $centre_data[0]['centre_remarks']; ?></td>
</tr>

<tr>
  <td><b>Address On Invoice</b> : <?php if($centre_data[0]['invoice_address'] == '1') { echo "Institute Address and GST No."; } else if($centre_data[0]['invoice_address'] == '2') { echo "Centre Address and GST No."; } ?></td>
  <td><b style="vertical-align:top">Date of Addition</b> : <?php echo date("Y-m-d", strtotime($centre_data[0]['created_on'])); ?></td>
</tr>

<tr>
  <td><b style="vertical-align:top">Status</b> : <span class="disp_status_details badge <?php echo show_faculty_status($centre_data[0]['status']); ?>" style="min-width:90px;"><?php echo $centre_data[0]['DispStatus']; ?></span></td>
  <td></td>
</tr>