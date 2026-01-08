<tr>
  <td colspan="2" class="text-center heading_row"><b>Inspector Details</b></td>
</tr>

<tr>
  <td><b>Inspector Name</b> : <?php echo $inspector_data[0]['inspector_name']; ?></td>
  <td><b>Mobile Number</b> : <?php echo $inspector_data[0]['inspector_mobile']; ?></td>
</tr>

<tr>
  <td><b>Email id</b> : <?php echo $inspector_data[0]['inspector_email']; ?></td>
  <td><b>Inspector Designation</b> : <?php echo $inspector_data[0]['inspector_designation']; ?></td>
</tr>

<tr>
  <td><b>Inspector Username</b> : <?php echo $inspector_data[0]['inspector_username']; ?></td>
  <td><b>Inspector Password</b> : <?php echo $this->Iibf_bcbf_model->password_decryption($inspector_data[0]['inspector_password']); ?></td>
</tr>

<?php if($inspector_data[0]['batch_online_offline_flag'] == '1')//offline
{ ?>
  <tr><td colspan="2"><b>State</b> : <?php echo $inspector_data[0]['AssignedStates']; ?></td></tr>
  <tr><td colspan="2"><b>Assigned Centres(City)</b> : <?php echo $inspector_data[0]['AssignedCities']; ?></td></tr>  
<?php } ?>

<tr>
  <td><b>Type</b> : <?php echo $inspector_data[0]['DispType']; ?></td>
  <td><b>Status</b> : <?php echo '<span class="badge '.show_faculty_status($inspector_data[0]['is_active']).'" style="min-width:90px;">'.$inspector_data[0]['DispStatus'].'</span>'; ?></td>
</tr>