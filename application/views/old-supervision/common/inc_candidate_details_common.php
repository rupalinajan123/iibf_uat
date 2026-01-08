<tr><td class="text-center heading_row" colspan="2" ><b>Observer Details</b></td></tr>

<tr>
  <td>
    <b>Observer Name</b> : 
    <?php echo $candidate_data[0]['candidate_name'];  ?> (<?php echo $candidate_data[0]['candidate_code']; ?>)</td>
  <td><b>Registration Date</b> : <?php echo date("Y-m-d", strtotime($form_data[0]['created_on'])); ?></td>
</tr>

<tr>
  <td><b>Email</b> : <?php echo $candidate_data[0]['email']; ?></td>
  <td><b>Mobile</b> : <?php echo $candidate_data[0]['mobile']; ?></td>
</tr>

<tr>
  <td><b>Bank</b> : <?php echo $candidate_data[0]['bank']; ?></td>
  <td><b>Branch</b> : <?php echo $candidate_data[0]['branch']; ?></td>
</tr>

<tr>
  <td><b>Designation</b> : <?php echo $candidate_data[0]['designation']; ?></td>
  <td><b>Bank ID Card</b> : <a target="_blank" href="<?php echo base_url().'uploads/supervision/'.$candidate_data[0]['bank_id_card']; ?>">Click Here</a></td>
</tr>

<tr>
  <td><b>PDC Zone</b> : <?php echo $candidate_data[0]['pdc_zone_name']; ?></td>
  <td><!--<b>Center</b> : <?php echo $candidate_data[0]['center_name']; ?>--></td>
</tr>

<tr>
  <td><b>Username/Observer Code</b> : <?php echo $candidate_data[0]['candidate_code']; ?></td>
  <td><b>Password</b> : <?php echo $this->supervision_model->password_decryption($candidate_data[0]['password']); ?></td>
</tr>



