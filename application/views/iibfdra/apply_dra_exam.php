<style>
  .example { width: 33%; min-width: 370px; display: inline-block; box-sizing: border-box; }
  .example select { padding: 10px; background: #ffffff; border: 1px solid #CCCCCC; border-radius: 3px; margin: 0 3px; }
  .example select.invalid { color: #E9403C; }
  .mandatory-field { color:#F00; }

  .box-header { padding: 10px 10px 10px 10px; margin:0 0 15px 0; }
  .box.custom_sub_header { border-radius: 0; border-left: none; border-right: none; margin: 0; border-bottom: none; }

  .note { color: blue; font-size: 12px; line-height: 15px; display: inline-block; margin: 5px 0 0 0; }
  .note-error { color: rgb(185, 74, 72); font-size: 12px; line-height: 15px; display: inline-block; margin: 5px 0 0 0; vertical-align:top; }
  .parsley-errors-list > li { display: inline-block !important; font-size: 12px; line-height: 14px; margin: 2px 0 0 0 !important; padding: 0 !important; }
  .datepicker table tr td.disabled, .datepicker table tr td.disabled:hover, .datepicker table tr td span.disabled, .datepicker table tr td span.disabled:hover { cursor: not-allowed; }
  #loading { display: none;	position: fixed; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999; }
  #loading > p { margin: 0 auto; width: 100%; height: 100%; position: absolute; top: 20%; }
  #loading > p > img { max-height: 250px; margin:0 auto; display: block; }
  .form-group ul.parsley-errors-list li::before { content: ""; }
  .radio_btn_label { margin-bottom:0; }
  .radio_btn_label > input[type="radio"] { margin: 6px 3px 2px 2px; vertical-align: top; }
  #basic_details_static_div .form-group { margin-bottom:0; }

  .box-header { padding: 10px 10px 10px 10px; margin:0 0 15px 0; }
  .img_preview { width: 100px; max-width: 80px; padding: 5px 5px 5px 0px; }
  .tbl_outer_div { padding:10px 30px 20px 30px; }
  .tbl_outer_div .table, .tbl_outer_div .table td { border:1px solid #ccc; }
  .tbl_outer_div .table tr.tbl_header td { padding:8px 10px 8px 10px; }
  .tbl_outer_div .table tr.empty_td td { padding: 25px 0 0 0; border-left: 1px solid #fff; border-right: 1px solid #fff; }

  #listitems_logs th { border: 1px solid #ccc !important;  text-align: center; background: #eee; }
  #listitems_logs td { border: 1px solid #ccc !important;   }
</style>

<div id="loading" class="divLoading" style="display: none;">
  <p><img src="<?php echo base_url(); ?>assets/images/loading-4.gif"/></p>
</div>

<div class="content-wrapper">  
  <section class="content-header"><h1> DRA examination application form </h1></section>
  
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <?php if($this->session->flashdata('error')!='')
        { ?>
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo $this->session->flashdata('error'); ?>
          </div>
        <?php } 
    
        if($this->session->flashdata('success')!=''){ ?>
          <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <?php echo $this->session->flashdata('success'); ?>
          </div>
        <?php } 

        if(validation_errors()!=''){?>
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <?php echo validation_errors(); ?>
          </div>
        <?php } 

        if (isset($img_error_msg) && $img_error_msg != '') { ?>
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo $img_error_msg; ?>
          </div>
        <?php } ?>
        
        <form class="form-horizontal" autocomplete="off" name="dra_apply_exam_form" id="dra_apply_exam_form"  method="post"  enctype="multipart/form-data" data-parsley-validate>
          <input type="hidden" name="registrationtype" value="<?php echo $candidate_details['registrationtype']; ?>">
          <input type="hidden" autocomplete="false" name="membertype" value="<?php echo ( set_value('membertype') ) ? set_value('membertype') : 'normal_member';?>" id="membertype" />
          <input type="hidden" class="form-control" id="reg_no" name="reg_no" value="<?php echo $candidate_details['entered_regnumber'];?>" autocomplete="off" />
          <input type="hidden" autocomplete="false" name="memtype" value="<?php echo set_value('memtype');?>" id="memtype" />
          
          <div class="box box-info" id="basic_details_static_div">
            <div class="box-header with-border">
              <h3 class="box-title">Candidate Details</h3>
              <div class="pull-right">
                <a href="<?php echo base_url('iibfdra/TrainingBatches/allapplicants/'.base64_encode($_SESSION['excode'])); ?>" class="btn btn-warning">Back</a>
              </div>
            </div>
            <?php //echo '<pre>'; print_r($examRes); echo '</pre>'; ?>
            <div class="box-body tbl_outer_div">
              <div class="table-responsive">
                <table class="table table-bordered table-hover table-condensed">
                  <tbody>
                    <tr class="info text-center tbl_header"><td colspan="2"><strong>Basic Details</strong></td></tr>
                    
                    <tr>
                      <td style="width:200px;"><strong>Training Id</strong> : <?php echo $examRes["training_id"];?></td>
                      <td style="width:200px;"><strong>Registration No</strong> : <?php if($examRes['entered_regnumber'] != "") { echo $examRes["entered_regnumber"]; } ?></td>
                    </tr>                 

                    <tr>
                      <td><strong>Name</strong> : <?php echo $examRes["namesub"]." ".$examRes["firstname"]; if($examRes["middlename"] != "") { echo " ".$examRes["middlename"]; } if($examRes["lastname"] != "") { echo " ".$examRes["lastname"]; } ?></td>
                      <td><strong>Date of Birth</strong> : <?php echo $examRes["dateofbirth"];?></td>
                    </tr>

                    <tr>
                      <td><strong>Gender</strong> : <?php echo ucfirst($examRes["gender"]);?></td>
                      <td><strong>Mobile No</strong> : <?php echo $examRes["mobile_no"];?></td>
                    </tr>

                    <tr>
                      <td><strong>Alternate Mobile No</strong> : <?php echo $examRes["alt_mobile_no"];?></td>
                      <td><strong>Email Id</strong> : <?php echo $examRes["email_id"];?></td>
                    </tr>

                    <tr>
                      <td><strong>Alternate Email Id</strong> : <?php echo $examRes["alt_email_id"];?></td>
                      <td><strong>Qualification</strong> : <?php echo str_replace("_"," ",$examRes["qualification_type"]);?></td>
                    </tr>
                    
                    <tr class="empty_td"><td colspan="2"></td></tr>
                    <tr class="info text-center tbl_header"><td colspan="2"><strong>Other Details</strong></td></tr>

                    <tr>
                      <td><strong>Address Line 1</strong> : <?php echo $examRes["address1"];?></td>                    
                      <td><strong>Address Line 2</strong> : <?php echo $examRes["address2"];?></td>
                    </tr>

                    <tr>
                      <td><strong>Address Line 3</strong> : <?php echo $examRes["address3"];?></td>
                      <td><strong>Address Line 4</strong> : <?php echo $examRes["address4"];?></td>
                    </tr>

                    <tr>
                      <td><strong>State</strong> : <?php echo $examRes["Candidate_state_name"];?></td>
                      <td><strong>District</strong> : <?php echo $examRes["district"];?></td>
                    </tr>

                    <tr>
                      <td><strong>City</strong> : <?php echo $examRes["city"];?></td>
                      <td><strong>Pincode</strong> : <?php echo $examRes["pincode"];?></td>
                    </tr>
                    
                    <tr class="empty_td"><td colspan="2"></td></tr>
                    <tr class="info text-center tbl_header"><td colspan="2"><strong>Agency Details</strong></td></tr>
                    <tr>
                      <td colspan="2"><strong>Name Of Training Institute</strong> : 
                        <?php
                        $drainstdata = $this->session->userdata('dra_institute');
                        if( $drainstdata ) 
                        {
                          echo $drainstdata['institute_name'];   
                        } ?>
                      </td>                      
                    </tr>

                    <tr>
                      <td><strong>Centre Name</strong> : <?php echo $batch_details[0]["city_name"];?></td>
                      <td><strong>State</strong> : <?php echo $batch_details[0]["state_name"];?></td>
                    </tr>
                    
                    <tr>
                      <td><strong>District</strong> : <?php echo $batch_details[0]["district"];?></td>
                      <td><strong>City</strong> : <?php echo $batch_details[0]["city_name"];?></td>
                    </tr>

                    <tr>
                      <td><strong>Training Period From</strong> : <?php echo $batch_details[0]["batch_from_date"];?></td>
                      <td><strong>Training Period To</strong> : <?php echo $batch_details[0]["batch_to_date"];?></td>
                    </tr>
                      
                    <tr class="empty_td"><td colspan="2"></td></tr>
                    <tr class="info text-center tbl_header"><td colspan="2"><strong> Photograph, Signature and Copies of Documents of the Candidate</strong></td></tr>                    
                    <tr>
                      <td><strong>Id Proof</strong> : 
                        <?php 
                          $idtype_res = $this->master_model->getRecords('dra_idtype_master',array('id' => $examRes['idproof']));
                          echo $idtype_res[0]['name'];
                        ?>
                      </td>                      
                      <td><strong>Id Proof Number</strong> : <?php echo $examRes["idproof_no"];?></td>
                    </tr>

                    <tr>
                      <td><strong>Proof of Identity</strong> <br>
                        <?php if(!empty($examRes['idproofphoto'])){?> 
                          <img class="img_preview" src="<?php echo base_url(); ?>uploads/iibfdra/<?php echo $examRes['idproofphoto']; ?>">
                        <?php } ?>
                      </td>
                    
                      <td><strong>Qualification</strong> : 
                        <?php 
                          if($examRes['qualification'] == 'tenth') { echo '10th Pass'; }
                          else if($examRes['qualification'] == 'twelth') { echo '12th Pass'; }
                          else if($examRes['qualification'] == 'graduate') { echo 'Graduation'; }
                          else if($examRes['qualification'] == 'post_graduate') { echo 'Post Graduation'; } 
                        ?>
                      </td>
                    </tr>

                    <tr>
                      <td><strong>Qualification Certificate</strong><br> 
                        <?php if(!empty($examRes['quali_certificate'])){?>
                          <img class="img_preview" src="<?php echo base_url(); ?>uploads/iibfdra/<?php echo $examRes['quali_certificate']; ?>" />
                        <?php } ?>
                      </td>
                    
                      <td><strong>Passport Photograph of the Candidate</strong><br>
                        <?php if(!empty($examRes['scannedphoto'])){?>
                          <img class="img_preview" src="<?php echo base_url(); ?>uploads/iibfdra/<?php echo $examRes['scannedphoto']; ?>" />
                        <?php } ?>
                      </td>
                    </tr>

                    <tr>
                      <td><strong>Full Signature of the Candidate</strong><br>
                        <?php if(!empty($examRes['scannedsignaturephoto'])){?> 
                          <img class="img_preview" src="<?php echo base_url(); ?>uploads/iibfdra/<?php echo $examRes['scannedsignaturephoto']; ?>" />
                        <?php } ?>
                      </td>
                    
                      <td><strong>Aadhar Card No</strong> : <?php echo $examRes['aadhar_no'];?></td>
                    </tr> 
                    
                    <tr class="empty_td"><td colspan="2"></td></tr>
                    <tr class="info text-center tbl_header"><td colspan="2"><strong>Exam Details</strong></td></tr>
                    
                    <?php if(count($subject_master_data) > 0) 
                    { ?>
                      <tr>
                        <td><strong>Examination Date</strong></td>
                        <td><?php echo date("d-M-Y", strtotime($subject_master_data[0]['exam_date'])); ?></td>
                      </tr>
                    <?php } ?> 

                    <?php /********* START : FOR PHYSICAL MODE *************************/
                    if($chk_exam_mode == 'PHYSICAL')
                    { ?>
                      <tr>
                        <td><strong>Exam Center Name</strong><span class="mandatory-field">*</span></td>
                        <td>
                          <select required class="form-control" name="exam_center">
                            <option value="">Select</option>
                            <?php 
                            if(count($center_master) > 0)
                            {      
                              foreach($center_master as $centers)
                              { ?>   
                                <option value="<?php echo $centers['center_code'];?>" <?php if($centers['center_code']==$examRes['exam_center_code']){  echo  'selected="selected"';}?>><?php echo $centers['center_name'];?>
                                </option>
                              <?php } 
                            } ?>
                          </select>
                        </td>
                      </tr>
                    <?php } /********* END : FOR PHYSICAL MODE *************************/ ?>

                    <tr>
                      <td><strong>Exam medium</strong><span class="mandatory-field">*</span></td>
                      <td>
                        <select required class="form-control" name="exam_medium">
                          <option value="">Select</option>
                          <?php if(count($medium_master) > 0){
                            foreach($medium_master as $mediums){     ?>
                              <option value="<?php echo $mediums['medium_code'];?>" <?php if($mediums['medium_code']==$examRes['exam_medium']){echo  'selected="selected"';}?>><?php echo $mediums['medium_description'];?>
                                </option>
                            <?php } } ?>
                        </select>
                      </td>
                    </tr>
                    
                    <tr>
                      <td></td>
                      <td>
                        <input type="button" class="btn btn-info btn_submit" name="btnSubmit" id="btnSubmit" value="Submit">
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>  
          
          <?php if(count($DraCandidateLogs) > 0)
          { ?>
            <div class="box" style="margin:15px 0 0 0">
              <div class="box-header with-border">
                <h3 class="box-title">Candidate Logs</h3>
                <div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"> <i class="fa fa-minus"></i> </button></div>
              </div>
              
              <div class="box-body ">
                <div class="table-responsive">
                  <table id="listitems_logs" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Sr.No.</th>
                        <th>Action</th>
                        <th>Action Date </th>
                      </tr>
                    </thead>

                    <tbody class="no-bd-y" id="list222">
                      <?php $i=1;
                      foreach($DraCandidateLogs as $res_log)
                      { ?>   
                        <tr>
                          <td class="text-center"><?php echo $i; ?></td>
                          <td><?php echo $res_log['log_title']; ?></td>
                          <td><?php echo date_format(date_create($res_log['created_on']),"d-M-Y h:i:s"); ?></td>
                        </tr>                              
                        <?php $i++; 
                      } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          <?php }  ?>
        </form>
      </div>
    </div>
  </section>
</div>
  
<script src="<?php echo base_url();?>assets/admin/dist/js/jquery.date-dropdowns.js"></script>
<link href="<?php echo base_url()?>assets/css/parsley.css" rel="stylesheet">
<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script>

<script type="text/javascript">
  var base_url = '<?php echo base_url(); ?>';  

  //START : USED TO REMOVE ERROR MESSAGE ON VALUE CHANGE
  function remove_err_msg(input_id)
  {
    if($("#"+input_id).val() != "")
    {
      //$("#dra_apply_exam_form").parsley().validate()
      $("#"+input_id).removeClass('parsley-error');
      $("#"+input_id+"_error").html('');
    }
  }
  //END : USED TO REMOVE ERROR MESSAGE ON VALUE CHANGE

  $(document).ready(function() 
  {
    //START : ON CLICK ON FORM SUBMIT BUTTON, VALIDATE FORM FIELDS
    $('#btnSubmit').click(function () 
    {
      $('#dra_apply_exam_form').parsley().validate();
      $("#dra_apply_exam_form").submit();           
    });
    //END : ON CLICK ON FORM SUBMIT BUTTON, VALIDATE FORM FIELDS
  });
</script>