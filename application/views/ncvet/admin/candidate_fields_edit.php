<form method="post" action="<?php echo site_url('ncvet/admin/candidate/editable_field'); ?>" id="editable_fields_form" class="admin_form_all" enctype="multipart/form-data" autocomplete="off">
  <input type="hidden" name="candidate_id" value="<?php echo $enc_pk_id; ?>">
  <table class="table table-bordered custom_inner_tbl" style="width:100%">
    <tbody>
      <tr><td class="text-center heading_row" colspan="4" ><b>Editable Fields</b></td></tr>

      <tr>
        <td class="wrap" style="width:47%"><b>Candidate Name</b></td>
        <td class="wrap"><input type="checkbox" name="editable_fields[]" id="editable_fields" value="Candidate Name" <?php echo checkEditableField('Candidate Name',$candidate_data[0]['candidate_id']) ? 'checked' : ''; ?>></td>
        <td class="wrap"><b>Guardian Name</b></td>
        <td class="wrap"><input type="checkbox" name="editable_fields[]" id="editable_fields" value="Guardian Name" <?php echo checkEditableField('Guardian Name',$candidate_data[0]['candidate_id']) ? 'checked' : ''; ?>></td>
      </tr>

      <tr>
        <td class="wrap"><b>Email Id</b></td>
        <td class="wrap"><input type="checkbox" name="editable_fields[]" id="editable_fields" value="Email Id" <?php echo checkEditableField('Email Id',$candidate_data[0]['candidate_id']) ? 'checked' : ''; ?>></td>
        <td class="wrap"><b>Mobile Number</b></td>
        <td class="wrap"><input type="checkbox" name="editable_fields[]" id="editable_fields" value="Mobile Number" <?php echo checkEditableField('Mobile Number',$candidate_data[0]['candidate_id']) ? 'checked' : ''; ?>></td>
      </tr>

      <tr>
        <td class="wrap"><b>Communication Address</b></td>
        <td class="wrap"><input type="checkbox" name="editable_fields[]" id="editable_fields" value="Communication Address" <?php echo checkEditableField('Communication Address',$candidate_data[0]['candidate_id']) ? 'checked' : ''; ?>></td>
        <td class="wrap"><b>Permanant Address</b></td>
        <td class="wrap"><input type="checkbox" name="editable_fields[]" id="editable_fields" value="Permanant Address" <?php echo checkEditableField('Permanant Address',$candidate_data[0]['candidate_id']) ? 'checked' : ''; ?>></td>
      </tr>

      <!-- <tr>
        <td class="wrap"><b>APAAR ID/ABC ID Number</b></td>
        <td class="wrap"><input type="checkbox" name="editable_fields[]" id="editable_fields" value="APAAR ID/ABC ID Number" <?php echo checkEditableField('APAAR ID/ABC ID Number',$candidate_data[0]['candidate_id']) ? 'checked' : ''; ?>></td>
        <td class="wrap"><b>Aadhar Card Number</b></td>
        <td class="wrap"><input type="checkbox" name="editable_fields[]" id="editable_fields" value="Aadhar Card Number" <?php echo checkEditableField('Aadhar Card Number',$candidate_data[0]['candidate_id']) ? 'checked' : ''; ?>></td>
      </tr> -->

      <tr>
          <td class="wrap"><b>Eligibility</b></td>
          <td class="wrap"><input type="checkbox" name="editable_fields[]" id="editable_fields" value="Eligibility" <?php echo checkEditableField('Eligibility',$candidate_data[0]['candidate_id']) ? 'checked' : ''; ?>></td>
          <td class="wrap"><b>Benchmark Disability</b></td>
          <td class="wrap"><input type="checkbox" name="editable_fields[]" id="editable_fields" value="Benchmark Disability" <?php echo checkEditableField('Benchmark Disability',$candidate_data[0]['candidate_id']) ? 'checked' : ''; ?>></td> 
      </tr>

      <tr>
        <td class="wrap"><b>Candidate Photo</b></td>
        <td class="wrap"><input type="checkbox" name="editable_fields[]" id="editable_fields" value="Candidate Photo" <?php echo checkEditableField('Candidate Photo',$candidate_data[0]['candidate_id']) ? 'checked' : ''; ?>></td>
        <td class="wrap"><b>Candidate Signature</b></td>
        <td class="wrap"><input type="checkbox" name="editable_fields[]" id="editable_fields" value="Candidate Signature" <?php echo checkEditableField('Candidate Signature',$candidate_data[0]['candidate_id']) ? 'checked' : ''; ?>></td>
      </tr>

      <tr>
        <td class="wrap"><b>APAAR ID/ABC ID</b></td>
        <td class="wrap"><input type="checkbox" name="editable_fields[]" id="editable_fields" value="APAAR ID/ABC ID" <?php echo checkEditableField('APAAR ID/ABC ID',$candidate_data[0]['candidate_id']) ? 'checked' : ''; ?>></td>
        <td class="wrap"><b>Aadhar Card</b></td>
        <td class="wrap"><input type="checkbox" name="editable_fields[]" id="editable_fields" value="Aadhar Card" <?php echo checkEditableField('Aadhar Card',$candidate_data[0]['candidate_id']) ? 'checked' : ''; ?>></td>
      </tr>

      <tr>
        <td class="wrap"><b>Date of Birth</b></td>
        <td class="wrap"><input type="checkbox" name="editable_fields[]" id="editable_fields" value="Date of Birth" <?php echo checkEditableField('Date of Birth',$candidate_data[0]['candidate_id']) ? 'checked' : ''; ?>></td>
        <td class="wrap"></td>
        <td class="wrap"></td>
      </tr>

      <?php if($candidate_data[0]['qualification'] == 3 || $candidate_data[0]['qualification'] == 4) { ?>
      <!-- <tr>
          <td class="wrap"><b>Institute ID</b></td>
          <td class="wrap"><input type="checkbox" name="editable_fields[]" id="editable_fields" value="Institute ID" <?php echo checkEditableField('Institute ID',$candidate_data[0]['candidate_id']) ? 'checked' : ''; ?>></td>
        
          <td class="wrap"><b>Declaration</b></td>
          <td class="wrap"><input type="checkbox" name="editable_fields[]" id="editable_fields" value="Declaration" <?php echo checkEditableField('Declaration',$candidate_data[0]['candidate_id']) ? 'checked' : ''; ?>></td>
      </tr> -->
      <?php } ?>  

      <?php 
        if($candidate_data[0]['qualification'] == '1')
        {
          $qualification_cert_lable = '12th Pass Certificate';
        }

        if($candidate_data[0]['qualification'] == '2')
        {
          $qualification_cert_lable = 'Degree Certificate/ Provisional degree certificate';
        }
      ?>   

      <?php if($candidate_data[0]['qualification'] == 3 || $candidate_data[0]['qualification'] == 4 ) { ?>
          <!-- <tr>
              <td class="wrap"><b>Eligibility</b></td>
              <td class="wrap"><input type="checkbox" name="editable_fields[]" id="editable_fields" value="Eligibility" <?php echo checkEditableField('Eligibility',$candidate_data[0]['candidate_id']) ? 'checked' : ''; ?>></td>
              <td class="wrap"><b>Semester</b></td>
              <td class="wrap"><input type="checkbox" name="editable_fields[]" id="editable_fields" value="Semester" <?php echo checkEditableField('Semester',$candidate_data[0]['candidate_id']) ? 'checked' : ''; ?>></td>  
          </tr>

          <tr>
              <td class="wrap"><b>Name of the College / Academic Institution</b></td>
              <td class="wrap"><input type="checkbox" name="editable_fields[]" id="editable_fields" value="Name of the College / Academic Institution" <?php echo checkEditableField('Name of the College / Academic Institution',$candidate_data[0]['candidate_id']) ? 'checked' : ''; ?>></td>
              <td class="wrap"><b>Name of the University</b></td>
              <td class="wrap"><input type="checkbox" name="editable_fields[]" id="editable_fields" value="Name of the University" <?php echo checkEditableField('Name of the University',$candidate_data[0]['candidate_id']) ? 'checked' : ''; ?>></td>  
          </tr> -->  
      <?php } ?>

      <?php if($candidate_data[0]['qualification'] == 1) { ?>
      <!-- <tr>
          <td class="wrap"><b><?php echo $qualification_cert_lable; ?></b></td>
          <td class="wrap"><input type="checkbox" name="editable_fields[]" id="editable_fields" value="<?php echo $qualification_cert_lable; ?>" <?php echo checkEditableField($qualification_cert_lable,$candidate_data[0]['candidate_id']); ?>></td>
          <td class="wrap"><b>Experience Certificate</b></td>
          <td class="wrap"><input type="checkbox" name="editable_fields[]" id="editable_fields" value="Experience Certificate" <?php echo checkEditableField('Experience Certificate',$candidate_data[0]['candidate_id']) ? 'checked' : ''; ?>></td>  
      </tr> --> 
      <?php } ?>

      <?php if($candidate_data[0]['qualification'] == 2 ) { ?>
      <!-- <tr>
          <td class="wrap"><b><?php echo $qualification_cert_lable; ?></b></td>
          <td class="wrap"><input type="checkbox" name="editable_fields[]" id="editable_fields" value="<?php echo $qualification_cert_lable; ?>" <?php echo checkEditableField($qualification_cert_lable,$candidate_data[0]['candidate_id']); ?>></td>
          <td class="wrap"><b>Benchmark Disability</b></td>
          <td class="wrap"><input type="checkbox" name="editable_fields[]" id="editable_fields" value="Benchmark Disability" <?php echo checkEditableField('Benchmark Disability',$candidate_data[0]['candidate_id']) ? 'checked' : ''; ?>></td>  
      </tr> -->
      <?php } ?>

      <tr>
        <?php if($candidate_data[0]['qualification'] != 2 ) { ?>
          <!-- <td class="wrap"><b>Benchmark Disability</b></td>
          <td class="wrap"><input type="checkbox" name="editable_fields[]" id="editable_fields" value="Benchmark Disability" <?php echo checkEditableField('Benchmark Disability',$candidate_data[0]['candidate_id']) ? 'checked' : ''; ?>></td> -->  
        <?php } ?>  
        <?php if($candidate_data[0]['qualification'] != 3 && $candidate_data[0]['qualification'] != 4 ) { ?>
          <!-- <td class="wrap"><b>Eligibility</b></td>
          <td class="wrap"><input type="checkbox" name="editable_fields[]" id="editable_fields" value="Eligibility" <?php echo checkEditableField('Eligibility',$candidate_data[0]['candidate_id']) ? 'checked' : ''; ?>></td> -->
        <?php } ?>  
        <?php if($candidate_data[0]['qualification'] != 1 ) { ?>
          <!-- <td class="wrap"></td>
          <td class="wrap"></td> -->
        <?php } ?>
      </tr>
    </tbody>
  </table>
  <button type="submit" name="editable_btn" class="btn btn-primary" value="true">Update</button> &nbsp
  <?php if($candidate_data[0]['updated_fields'] !== '' && $candidate_data[0]['updated_fields'] !== null ) { ?> 
    <button type="button" id="cancel_edit_btn" class="btn btn-danger">Reset</button>
  <?php } ?>
</form> 

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('editable_fields_form');
        const cancelButton = document.getElementById('cancel_edit_btn');
        const candidateId = document.querySelector('input[name="candidate_id"]').value;

        // Get the relevant checkboxes
        const candidateNameCheckbox = document.querySelector('input[value="Candidate Name"]');
        const apaarIdCheckbox = document.querySelector('input[value="APAAR ID/ABC ID"]');
        const aadharCardCheckbox = document.querySelector('input[value="Aadhar Card"]');

        // Add a change event listener to the "Candidate Name" checkbox
        candidateNameCheckbox.addEventListener('change', function() {
            if (this.checked) {
                // If "Candidate Name" is checked, check and disable the other two
                if (apaarIdCheckbox) {
                    apaarIdCheckbox.checked = true;
                    apaarIdCheckbox.disabled = true;
                }
                if (aadharCardCheckbox) {
                    aadharCardCheckbox.checked = true;
                    aadharCardCheckbox.disabled = true;
                }
            } else {
                // If "Candidate Name" is unchecked, uncheck and enable the other two
                if (apaarIdCheckbox) {
                    apaarIdCheckbox.checked = false;
                    apaarIdCheckbox.disabled = false;
                }
                if (aadharCardCheckbox) {
                    aadharCardCheckbox.checked = false;
                    aadharCardCheckbox.disabled = false;
                }
            }
        });
        
        // Handle form submission logic
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            if (candidateNameCheckbox.checked ) 
            {
              if ( (apaarIdCheckbox.checked == false) || (aadharCardCheckbox.checked == false) )
              {
                swal({
                  title: "Error",
                  text: "Please select at Aadhar card and Aapar ID field.",
                  type: "error",
                  confirmButtonText: "OK"
                });
                return false;
              }    
            }

            // Re-enable disabled checkboxes temporarily to ensure they are submitted
            if (apaarIdCheckbox && apaarIdCheckbox.disabled) {
                apaarIdCheckbox.disabled = false;
            }
            if (aadharCardCheckbox && aadharCardCheckbox.disabled) {
                aadharCardCheckbox.disabled = false;
            }

            const checkboxes = document.querySelectorAll('input[name="editable_fields[]"]');
            let isChecked = false;

            for (let i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    isChecked = true;
                    break;
                }
            }

            if (isChecked) {
                swal({
                    title: "Are you sure?",
                    text: "You want to save the selected fields as editable for this candidate.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes",
                    closeOnConfirm: true
                }, function(isConfirmed) {
                    if (isConfirmed) {
                        form.submit();
                    } else {
                        // Re-disable if the user cancels
                        if (candidateNameCheckbox.checked) {
                            if (apaarIdCheckbox) apaarIdCheckbox.disabled = true;
                            if (aadharCardCheckbox) aadharCardCheckbox.disabled = true;
                        }
                    }
                });
            } else {
                swal({
                    title: "Error",
                    text: "Please select at least one field to edit.",
                    type: "error",
                    confirmButtonText: "OK"
                });
            }
        });

        // Event listener for the Cancel button
        cancelButton.addEventListener('click', function() {
            swal({
                title: "Are you sure?",
                text: "You want to reset all editable fields for this candidate.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Reset",
                closeOnConfirm: false
            }, function(isConfirmed) {
                if (isConfirmed) {
                    window.location.href = `<?php echo site_url('ncvet/admin/candidate/clear_editable_fields'); ?>/${candidateId}`;
                }
            });
        });
    });
</script>