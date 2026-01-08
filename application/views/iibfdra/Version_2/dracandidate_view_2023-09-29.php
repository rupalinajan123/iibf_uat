<style>
.control-label {
	font-weight: bold !important;
}
label {      
      border-color: #80808059;
}
.types {
    color: green;
    font-weight: 800;
}
.status_div{
 font-weight: 800 !important;
}

.status {
  color: #223fcc;
  font-weight: 800;
}
.myview .form-group{
	clear:both;
}

.box-header { padding: 10px 10px 10px 10px; margin:0 0 15px 0; }
.img_preview { width: 100px; max-width: 80px; padding: 5px 5px 5px 0px; }
.tbl_outer_div { padding:10px 30px 20px 30px; }
.tbl_outer_div .table, .tbl_outer_div .table td { border:1px solid #ccc; }
.tbl_outer_div .table tr.tbl_header td { padding:8px 10px 8px 10px; }
.tbl_outer_div .table tr.empty_td td { padding: 25px 0 0 0; border-left: 1px solid #fff; border-right: 1px solid #fff; }
</style>
<div class="content-wrapper">
  <section class="content-header">
    <h1> Candidate Preview </h1>
  </section>
   
  </section>
    <section class="content">
      <div class="row myview">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Candidate Details</h3>
              <div class="pull-right"> <a href="<?php echo base_url('iibfdra/Version_2/TrainingBatches/candidate_list/'.$batchId);?>" class="btn btn-warning"> Back </a> </div>
            </div>
            <div class="box-body tbl_outer_div">
              <?php //echo '<pre>'; print_r($batch_details); echo '</pre>'; ?>
              <div class="table-responsive">
                <table class="table table-bordered table-hover table-condensed">
                  <tbody>
                    <tr class="info text-center tbl_header"><td colspan="2"><strong>Basic Details</strong></td></tr>
                    
                    <tr>
                      <td style="width:200px;"><strong>Training Id</strong></td>
                      <td><?php echo $examRes["training_id"];?></td>
                    </tr>

                    <?php if($examRes['entered_regnumber'] != "") { ?>
                      <tr>
                        <td><strong>Registration No</strong></td>
                        <td><?php echo $examRes["entered_regnumber"];?></td>
                      </tr>
                    <?php } ?>

                    <tr>
                      <td><strong>Name</strong></td>
                      <td><?php echo $examRes["namesub"]." ".$examRes["firstname"]; if($examRes["middlename"] != "") { echo " ".$examRes["middlename"]; } if($examRes["lastname"] != "") { echo " ".$examRes["lastname"]; } ?></td>
                    </tr>

                    <tr>
                      <td><strong>Date of Birth</strong></td>
                      <td><?php echo $examRes["dateofbirth"];?></td>
                    </tr>

                    <tr>
                      <td><strong>Gender</strong></td>
                      <td><?php echo ucfirst($examRes["gender"]);?></td>
                    </tr>

                    <tr>
                      <td><strong>Mobile No</strong></td>
                      <td><?php echo $examRes["mobile_no"];?></td>
                    </tr>

                    <tr>
                      <td><strong>Alternate Mobile No</strong></td>
                      <td><?php echo $examRes["alt_mobile_no"];?></td>
                    </tr>

                    <tr>
                      <td><strong>Email Id</strong></td>
                      <td><?php echo $examRes["email_id"];?></td>
                    </tr>

                    <tr>
                      <td><strong>Alternate Email Id</strong></td>
                      <td><?php echo $examRes["alt_email_id"];?></td>
                    </tr>

                    <tr>
                      <td><strong>Qualification</strong></td>
                      <td><?php echo str_replace("_"," ",$examRes["qualification_type"]);?></td>
                    </tr>
                    
                    <tr class="empty_td"><td colspan="2"></td></tr>
                    <tr class="info text-center tbl_header"><td colspan="2"><strong>Other Details</strong></td></tr>

                    <tr>
                      <td><strong>Address Line 1</strong></td>
                      <td><?php echo $examRes["address1"];?></td>
                    </tr>
                    <tr>
                      <td><strong>Address Line 2</strong></td>
                      <td><?php echo $examRes["address2"];?></td>
                    </tr>
                    <tr>
                      <td><strong>Address Line 3</strong></td>
                      <td><?php echo $examRes["address3"];?></td>
                    </tr>
                    <tr>
                      <td><strong>Address Line 4</strong></td>
                      <td><?php echo $examRes["address4"];?></td>
                    </tr>
                    <tr>
                      <td><strong>State</strong></td>
                      <td><?php echo $examRes["state_name"];?></td>
                    </tr>
                    <tr>
                      <td><strong>District</strong></td>
                      <td><?php echo $examRes["district"];?></td>
                    </tr>
                    <tr>
                      <td><strong>City</strong></td>
                      <td><?php echo $examRes["city"];?></td>
                    </tr>
                    <tr>
                      <td><strong>Pincode</strong></td>
                      <td><?php echo $examRes["pincode"];?></td>
                    </tr>
                    
                    <tr class="empty_td"><td colspan="2"></td></tr>
                    <tr class="info text-center tbl_header"><td colspan="2"><strong>Agency Details</strong></td></tr>
                    <tr>
                      <td><strong>Name Of Training Institute</strong></td>
                      <td>
                        <?php
                        $drainstdata = $this->session->userdata('dra_institute');
                        if( $drainstdata ) 
                        {
                          echo $drainstdata['institute_name'];   
                        } ?>
                      </td>
                    </tr>
                    <tr>
                      <td><strong>Centre Name</strong></td>
                      <td><?php echo $batch_details[0]["city_name"];?></td>
                    </tr>
                    <tr>
                      <td><strong>State</strong></td>
                      <td><?php echo $batch_details[0]["state_name"];?></td>
                    </tr>
                    <tr>
                      <td><strong>District</strong></td>
                      <td><?php echo $batch_details[0]["district"];?></td>
                    </tr>
                    <tr>
                      <td><strong>City</strong></td>
                      <td><?php echo $batch_details[0]["city_name"];?></td>
                    </tr>
                    <tr>
                      <td><strong>Training Period From</strong></td>
                      <td><?php echo $batch_details[0]["batch_from_date"];?></td>
                    </tr>
                    <tr>
                      <td><strong>Training Period To</strong></td>
                      <td><?php echo $batch_details[0]["batch_to_date"];?></td>
                    </tr>
                      
                    <tr class="empty_td"><td colspan="2"></td></tr>
                    <tr class="info text-center tbl_header"><td colspan="2"><strong> Photograph, Signature and Copies of Documents of the Candidate</strong></td></tr>
                    
                    <tr>
                      <td><strong>Id Proof</strong></td>
                      <td>
                        <?php 
                          $idtype_res = $this->master_model->getRecords('dra_idtype_master',array('id' => $examRes['idproof']));
                          echo $idtype_res[0]['name'];
                        ?>
                      </td>
                    </tr>
                    
                    <tr>
                      <td><strong>Id Proof Number</strong></td>
                      <td><?php echo $examRes["idproof_no"];?></td>
                    </tr>
                    <tr>
                      <td><strong>Proof of Identity</strong></td>
                      <td>
                        <?php if(!empty($examRes['idproofphoto'])){?> 
                          <img class="img_preview" src="<?php echo base_url(); ?>uploads/iibfdra/<?php echo $examRes['idproofphoto']; ?>">
                        <?php } ?>
                      </td>
                    </tr>
                    <tr>
                      <td><strong>Qualification</strong></td>
                      <td>
                        <?php 
                          if($examRes['qualification'] == 'tenth') { echo '10th Pass'; }
                          else if($examRes['qualification'] == 'twelth') { echo '12th Pass'; }
                          else if($examRes['qualification'] == 'graduate') { echo 'Graduation'; }
                          else if($examRes['qualification'] == 'post_graduate') { echo 'Post Graduation'; } 
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td><strong>Qualification Certificate</strong></td>
                      <td>
                        <?php if(!empty($examRes['quali_certificate'])){?>
                          <img class="img_preview" src="<?php echo base_url(); ?>uploads/iibfdra/<?php echo $examRes['quali_certificate']; ?>" />
                        <?php } ?>
                      </td>
                    </tr>
                    <tr>
                      <td><strong>Passport Photograph of the Candidate</strong></td>
                      <td>
                        <?php if(!empty($examRes['scannedphoto'])){?>
                          <img class="img_preview" src="<?php echo base_url(); ?>uploads/iibfdra/<?php echo $examRes['scannedphoto']; ?>" />
                        <?php } ?></td>
                    </tr>
                    <tr>
                      <td><strong>Full Signature of the Candidate</strong></td>
                      <td>
                        <?php if(!empty($examRes['scannedsignaturephoto'])){?> 
                          <img class="img_preview" src="<?php echo base_url(); ?>uploads/iibfdra/<?php echo $examRes['scannedsignaturephoto']; ?>" />
                        <?php } ?></td>
                    </tr>
                    <tr>
                      <td><strong>Aadhar Card No</strong></td>
                      <td><?php echo $examRes['aadhar_no'];?></td>
                    </tr>                    
                  </tbody>
                </table>

                <div class="text-center"> <a href="<?php echo base_url('iibfdra/Version_2/TrainingBatches/candidate_list/'.$batchId);?>" class="btn btn-warning"> Back </a> </div>
              </div>                      
            </div>
          </div>
        </div>
      </div>
    </section>
</div>