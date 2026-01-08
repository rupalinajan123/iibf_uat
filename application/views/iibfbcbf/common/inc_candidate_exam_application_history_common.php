<?php
  if($enc_training_id_or_regnumber != '')
  {
    if(count($candidate_data) == 0)
    {
      echo '<h3 class="text-center mt-5 mb-4 text-danger">Please enter correct Training ID or Registration Number</h3>';
    }
    else
    { ?>
      <h3 class="text-center mt-4 mb-2 text-success">Candidate Details</h3>
      <div class="hr-line-dashed"></div>

      <div class="table-responsive">
        <table class="table table-bordered custom_inner_tbl" style="width:100%">
          <tbody>
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
              <td class="wrap"><b>Hold / Release Status</b> : <?php echo $candidate_data[0]['Disphold_release_status']; ?></td>
            </tr>

            <tr>
              <td class="wrap"><b>Attempt</b> : <?php echo $candidate_data[0]['re_attempt']; ?></td>
              <td class="wrap"><b>Registration Date</b> : <?php echo $candidate_data[0]['created_on']; ?></td>
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
          </tbody>
        </table>
      </div>

      <h3 class="text-center mt-4 mb-2 text-success">Exam Application History</h3>
      <div class="hr-line-dashed"></div>

      <?php if(count($exam_application_data) == 0)
      {
        echo '<h3 class="text-center mt-4 mb-4 text-danger">The candidate is not applied for the exam</h3>';
      }
      else
      { ?>
        <div class="table-responsive">
          <table class="table table-bordered table-hover dataTables-example">
            <thead>
              <tr> 
                <th class="text-center " style="width:60px;">Sr. No.</th>
                <th class="text-center " style="width:60px;">Exam Code</th>
                <th class="text-center " style="width:60px;">Exam Period</th>
                <th class="text-center " style="width:60px;">Exam Fees</th>
                <th class="text-center " style="width:60px;">Exam Date</th>
                <th class="text-center " style="width:60px;">Exam Time</th>
                <th class="text-center " style="width:60px;">Payment Mode</th>
                <th class="text-center " style="width:60px;">Receipt Number</th>
                <th class="text-center " style="width:60px;">Transaction Number</th>
                <th class="text-center " style="width:60px;">Payment Status</th>
                <th class="text-center " style="width:60px;">Payment Description</th>
                <th class="text-center " style="width:60px;">Admit Card</th>
                <th class="text-center">Application Date</th>
              </tr>
            </thead>
            
            <tbody>
              <?php 
              $sr_no = 1;
              foreach($exam_application_data as $res)
              { ?>
                <tr>
                  <td class="text-center"><?php echo $sr_no; ?></td>
                  <td class="text-center"><?php echo $res['exam_code']; ?></td>
                  <td class="text-center"><?php echo $res['exam_period']; ?></td>
                  <td class="text-center"><?php echo $res['exam_fee']; ?></td>
                  <td class="text-center"><?php echo $res['exam_date']; ?></td>
                  <td class="text-center"><?php if($res['exam_time'] != '' && $res['exam_time'] != '00' && $res['exam_time'] != '00:00' && $res['exam_time'] != '00:0000') { echo $res['exam_time']; }; ?></td>
                  <td class="text-center"><?php echo $res['payment_mode']; ?></td>
                  <td class="text-center"><?php echo $res['receipt_no']; ?></td>
                  <td class="text-center"><?php echo $res['ref_utr_no']; ?></td>
                  <td class="text-center">
                    <?php 
                      if($res['pay_status'] == '0') { echo 'Fail'; }
                      else if($res['pay_status'] == '1') { echo 'Success'; }
                      else if($res['pay_status'] == '2') { echo 'Pending'; }
                      else if($res['pay_status'] == '3') { echo 'Applied'; }
                      else if($res['pay_status'] == '4') { echo 'Cancelled'; }
                      else if($res['pay_status'] == '5') { echo 'Refund'; }
                      ?>
                  </td>
                  <td class=""><?php echo $res['description']; ?></td>
                  <td class="text-center">
                    <?php 
                    if(in_array($res['exam_code'], array(1039,1040,1041,1042,1057)) && $res['admitcard_id'] != "")
                    {
                      $download_link = 'javascript:void(0)';
                      if(isset($_SESSION['IIBF_BCBF_USER_TYPE']) && $_SESSION['IIBF_BCBF_USER_TYPE'] == 'admin')
                      {
                        $download_link = site_url('iibfbcbf/admin/transaction/download_admitcard_pdf/'.url_encode($res['admitcard_id']));
                      }
                      else if(isset($_SESSION['IIBF_BCBF_USER_TYPE']) && $_SESSION['IIBF_BCBF_USER_TYPE'] == 'agency')
                      {
                        $download_link = site_url('iibfbcbf/agency/transaction_details_agency/download_admitcard_pdf/'.url_encode($res['admitcard_id']));
                      }

                      echo ' <a target="_blank" href="'.$download_link.'" class="btn btn-success btn-xs" title="Download"><i class="fa fa-download"></i></a> ';
                    } ?>
                  </td>
                  <td class="text-center"><?php echo date("Y-m-d h:i A", strtotime($res['created_on'])); ?></td>
                </tr>
                <?php $sr_no++;
              } ?>
            </tbody>
          </table>
        </div>
      <?php }
    } 
  } ?>