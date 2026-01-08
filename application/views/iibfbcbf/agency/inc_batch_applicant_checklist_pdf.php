<style type="text/css">
  table, th, td { border: 1px solid #303030; border-collapse: collapse;}
  th, td { padding:4px 4px; font-size:12px;  }
</style>

<div>
  <div style="text-align: centre; font-size:14px; margin-bottom:5px;"> Batch Checklist Report</div>

  <table width="100%">
    <thead>
      <tr>
        <th>Sr. No.</th>
        <th>Training  Id</th>
        <th>Candidate Name</th>
        <th>Gender</th>
        <th>DOB</th>
        <th>Mobile</th>
        <th>Email</th>
        <th>Qualification</th>
        <th>Address</th>
        <th>ID Proof</th>
        <th>ID Proof No.</th>
        <th>Photo</th>
        <th>Signature</th>
        <th>ID Proof Photo</th>
        <th>Qualification Certificate</th>        
      </tr>
    </thead>
    
    <tbody>
      <?php $sr_no = 1;
      foreach ($candidate_data as $res) 
      { ?>
        <tr>
          <td style="text-align:centre;"><?php echo $sr_no; ?></td>
          <td><?php echo $res['training_id']; ?></td>
          <td><?php echo $res['DispName']; ?></td>
          <td style="text-align:centre;"><?php echo $res['DispGender']; ?></td>
          <td><?php echo $res['dob']; ?></td>
          <td><?php echo $res['mobile_no']; ?></td>
          <td><?php echo $res['email_id']; ?></td>
          <td style="text-align:centre;"><?php echo $res['DispQualification']; ?></td>
          <td style="word-wrap: anywhere;"><?php echo $res['DispAddress']; ?></td>
          <td><?php echo $res['DispIdProofType']; ?></td>
          <td><?php echo $res['id_proof_number']; ?></td>
          
          <td style="text-align:centre;">
            <?php if($res['candidate_photo'] != "")
            { ?>
              <img src="<?php echo base_url($candidate_photo_path.'/'.$res['candidate_photo'])."?".time(); ?>" style="max-width:50px; max-height:50px;">                
            <?php }  ?>
          </td>
          
          <td style="text-align:centre;">
            <?php  if($res['candidate_sign'] != "")
            { ?>
              <img src="<?php echo base_url($candidate_sign_path.'/'.$res['candidate_sign'])."?".time(); ?>" style="max-width:50px; max-height:50px;">
            <?php }  ?>
          </td>

          <td style="text-align:centre;">
            <?php if($res['id_proof_file'] != "")
            { ?>
              <img src="<?php echo base_url($id_proof_file_path.'/'.$res['id_proof_file'])."?".time(); ?>" style="max-width:50px; max-height:50px;">
            <?php } ?>
          </td>
          
          <td style="text-align:centre;">
            <?php if($res['qualification_certificate_file'] != "")
            { ?>
              <img src="<?php echo base_url($qualification_certificate_file_path.'/'.$res['qualification_certificate_file'])."?".time(); ?>" style="max-width:50px; max-height:50px;">
            <?php } ?>
          </td>
        </tr>
      <?php $sr_no++; } ?>
    </tbody>
  </table>
</div>
