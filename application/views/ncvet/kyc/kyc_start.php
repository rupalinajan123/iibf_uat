<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($page_title)) { echo $page_title; } else { echo 'IIBF'; } ?></title>
    <?php $this->load->view('ncvet/kyc/inc_header'); ?>    
  </head>
	<body class="fixed-sidebar">
  <?php $this->load->view('ncvet/common/inc_loader'); ?>
		
		<div id="wrapper">
    <?php $this->load->view('ncvet/kyc/inc_sidebar_admin'); ?>		
			<div id="page-wrapper" class="gray-bg">				
      <?php $this->load->view('ncvet/kyc/inc_topbar_admin'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-10">
						<h2><?php echo $disp_title; ?></h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item active"> <strong><?php echo $disp_title; ?></strong></li>
						</ol>
					</div>
					<div class="col-lg-2"> </div>
				</div>

        <?php 
        function get_kyc_radio_btn_details($member_data=array(),$input_name='')
        {
          $login_user_type = $_SESSION['KYC_ADMIN_TYPE'];
          $approve_check = $reject_check = $approve_cls = $reject_cls = '';
          $disabled_flg ='';
          
          if($member_data['img_ediited_on'] != '' && $member_data['img_ediited_on'] != '0000-00-00 00:00:00')//EDITED MEMBER
          {
            if(isset($member_data[$input_name]) && $member_data[$input_name] == 'Y' && $login_user_type == '1') { $disabled_flg = 'disabled'; }
          }
          
          if($member_data[$input_name] == 'Y') 
          { 
            $approve_check = 'checked'; 
            if($disabled_flg != '') { $reject_cls = 'hide'; }                                
          } 
          else if($member_data[$input_name] == 'N') 
          { 
            //$reject_check = 'checked'; 
            //if($disabled_flg != '') { $approve_cls = 'hide'; }
          }

          $data = array();
          $data['approve_check'] = $approve_check;
          $data['reject_check'] = $reject_check;
          $data['approve_cls'] = $approve_cls;
          $data['reject_cls'] = $reject_cls;
          $data['disabled_flg'] = $disabled_flg;
          return $data;
        } ?>
				
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox">
								<div class="ibox-content"> 
                  <?php if($this->session->flashdata('success_kyc')){ ?>
                  <div class="alert alert-success "><?php echo $this->session->flashdata('success_kyc'); ?><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
                <?php } ?>

                  <h4>Pending count : <?php echo count($kyc_pending_candidate_data); ?></h4>

                  <?php $mem_data = $kyc_inprogress_candidate_data; /* _pa($mem_data); */  ?>
                  
                  <form method="post" action="<?php echo site_url('ncvet/kyc/kyc_all/process_kyc'); ?>" id="kyc_form" enctype="multipart/form-data" autocomplete="off">
                    <input type="hidden" name="module_name" id="module_name" value="<?php echo $module_name; ?>">
                    <input type="hidden" name="membership_type" id="membership_type" value="<?php echo $membership_type; ?>">
                    <input type="hidden" name="member_type" id="member_type" value="<?php echo $member_type; ?>">
                    <input type="hidden" name="exam_code" id="exam_code" value="<?php echo $exam_code; ?>">
                    <input type="hidden" name="form_action" id="form_action" value="">
                    <div class="table-responsive">
                      <table class="table table-bordered" style="width:100%">
                        <thead>
                          <tr> 
                            <th class="text-center nowrap">Name</th>
                            <th class="text-center nowrap">Enrollement No.</th>
                            <!-- <th class="text-center nowrap">Training ID</th> -->
                            <th class="text-center nowrap">Birth Date</th>
                            <th class="text-center nowrap">Photo</th>
                            <th class="text-center nowrap">Signature</th>
                            <th class="text-center nowrap">Id Proof</th>
                            <th class="text-center nowrap">Declaration</th>
                          </tr>
                        </thead>
                        
                        <tbody>
                          <?php 
                          $empty_photo_flag = $empty_sign_flag = $empty_id_proof_flag = $empty_declaration_flag = '';
                          if(count($mem_data) > 0)
                          { ?>
                            <tr>
                              <td><?php /* Name */ ?>
                                <?php 
                                  $disp_name = $mem_data[0]['salutation']; 
                                  $disp_name .= $mem_data[0]['first_name'] !=''? ' '.$mem_data[0]['first_name']:'';
                                  $disp_name .= $mem_data[0]['middle_name'] !=''? ' '.$mem_data[0]['middle_name']:'';
                                  $disp_name .= $mem_data[0]['last_name'] !=''? ' '.$mem_data[0]['last_name']:'';
                                  echo $disp_name;
                                ?>
                                <input type="hidden" name="candidate_id" id="candidate_id" value="<?php echo $mem_data[0]['candidate_id']; ?>">
                              </td>
                              <td><?php echo $mem_data[0]['regnumber']; ?></td><?php /* Regnumber */ ?>
                              <!-- <td><?php //echo $mem_data[0]['training_id']; ?></td> --><?php /* Training ID */ ?>
                              <td><?php echo $mem_data[0]['dob']; ?></td><?php /* Birth Date */ ?>
                              
                              <td class="text-center"><?php /* Photo */ ?>
                                <?php if ($mem_data[0]['photo_file'] != "" && file_exists($photo_path . '/' . $mem_data[0]['photo_file']))
                                { ?>
                                  <div id="photo_file_preview" class="upload_img_preview" style="margin:0 auto;">
                                    <a href="<?php echo base_url($photo_path . '/' . $mem_data[0]['photo_file']) . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Photo - '.$disp_name.' ('.$mem_data[0]['training_id'].')'; ?>">
                                      <img src="<?php echo base_url($photo_path . '/' . $mem_data[0]['photo_file']) . "?" . time(); ?>">
                                    </a>
                                  </div>
                                <?php } else { $empty_photo_flag = '1'; } ?>
                              </td>

                              <td class="text-center"><?php /* Signature */ ?>
                                <?php if ($mem_data[0]['sign_file'] != "" && file_exists($sign_path . '/' . $mem_data[0]['sign_file']))
                                { ?>
                                  <div id="sign_file_preview" class="upload_img_preview" style="margin:0 auto;">
                                    <a href="<?php echo base_url($sign_path . '/' . $mem_data[0]['sign_file']) . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Signature - '.$disp_name.' ('.$mem_data[0]['training_id'].')'; ?>">
                                      <img src="<?php echo base_url($sign_path . '/' . $mem_data[0]['sign_file']) . "?" . time(); ?>">
                                    </a>
                                  </div>
                                <?php } else { $empty_sign_flag = '1'; } ?>
                              </td>

                              <td class="text-center"><?php /* Id Proof */ ?>
                                <?php if ($mem_data[0]['id_proof_file'] != "" && file_exists($id_proof_path . '/' . $mem_data[0]['id_proof_file']))
                                { ?>
                                  <div id="id_proof_file_preview" class="upload_img_preview" style="margin:0 auto;">
                                    <a href="<?php echo base_url($id_proof_path . '/' . $mem_data[0]['id_proof_file']) . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'ID proof - '.$disp_name.' ('.$mem_data[0]['training_id'].')'; ?>">
                                      <img src="<?php echo base_url($id_proof_path . '/' . $mem_data[0]['id_proof_file']) . "?" . time(); ?>">
                                    </a>
                                  </div>
                                <?php }else { $empty_id_proof_flag = '1'; } ?>
                              </td>

                              <td class="text-center"><?php /* Declaration */ ?>
                                <?php if ($mem_data[0]['declarationform'] != "" && file_exists($declaration_path . '/' . $mem_data[0]['declarationform']))
                                { ?>
                                  <div id="declaration_preview" class="upload_img_preview" style="margin:0 auto;">
                                    <a href="<?php echo base_url($declaration_path . '/' . $mem_data[0]['declarationform']) . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Declaration - '.$disp_name.' ('.$mem_data[0]['training_id'].')'; ?>">
                                      <img src="<?php echo base_url($declaration_path . '/' . $mem_data[0]['declarationform']) . "?" . time(); ?>">
                                    </a>
                                  </div>
                                <?php } else { $empty_declaration_flag = '1'; } ?>
                              </td>

                            </tr>
                            
                            <tr>
                              <td></td><?php /* Name */ ?>
                              <td></td><?php /* Regnumber */ ?>
                              <!-- <td></td> --><?php /* Training ID */ ?>
                              <td></td><?php /* Birth Date */ ?>

                              <td><?php /* Photo */ ?>
                                <?php 
                                $kyc_radio_btn_details_photo = get_kyc_radio_btn_details($mem_data[0],'kyc_photo_flag');
                                $approve_check = $kyc_radio_btn_details_photo['approve_check'];
                                $reject_check = $kyc_radio_btn_details_photo['reject_check'];
                                $approve_cls = $kyc_radio_btn_details_photo['approve_cls'];
                                $reject_cls = $kyc_radio_btn_details_photo['reject_cls'];
                                $disabled_flg = $kyc_radio_btn_details_photo['disabled_flg']; ?>
        
                                <div id="photo_file_kyc_err" class="kyc_radio_outer"> 
                                  <?php if($empty_photo_flag == '') 
                                  { ?>                               
                                    <label class="css_checkbox_radio radio_only kyc_appprove_radio <?php echo $approve_cls; ?> <?php echo $disabled_flg; ?>"> <?php if($disabled_flg == '') { echo 'Approve'; } else { echo 'Approved'; } ?>
                                      <input type="radio" value="Y" name="photo_file_kyc" required <?php echo $approve_check; ?>><span class="radiobtn"></span>
                                    </label>

                                    <label class="css_checkbox_radio radio_only kyc_reject_radio <?php echo $reject_cls; ?> <?php echo $disabled_flg; ?>"> Reject
                                      <input type="radio" value="N" name="photo_file_kyc" required <?php echo $reject_check; ?>><span class="radiobtn"></span>
                                    </label>
                                  <?php }
                                  else
                                  { ?>
                                    <label class="css_checkbox_radio radio_only kyc_reject_radio"> Reject
                                      <input type="radio" value="N" name="photo_file_kyc" required checked><span class="radiobtn"></span>
                                    </label>
                                  <?php } ?>
                                </div>
                                <?php if (form_error('photo_file_kyc') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('photo_file_kyc'); ?></label> <?php } ?>
                              </td>

                              <td><?php /* Signature */ ?>
                                <?php 
                                $kyc_radio_btn_details_sign = get_kyc_radio_btn_details($mem_data[0],'kyc_sign_flag');
                                $approve_check = $kyc_radio_btn_details_sign['approve_check'];
                                $reject_check = $kyc_radio_btn_details_sign['reject_check'];
                                $approve_cls = $kyc_radio_btn_details_sign['approve_cls'];
                                $reject_cls = $kyc_radio_btn_details_sign['reject_cls'];
                                $disabled_flg = $kyc_radio_btn_details_sign['disabled_flg']; ?>
                                
                                <div id="sign_file_kyc_err" class="kyc_radio_outer">   
                                  <?php if($empty_sign_flag == '') 
                                  { ?>                                                           
                                    <label class="css_checkbox_radio radio_only kyc_appprove_radio <?php echo $approve_cls; ?> <?php echo $disabled_flg; ?>"> <?php if($disabled_flg == '') { echo 'Approve'; } else { echo 'Approved'; } ?>
                                      <input type="radio" value="Y" name="sign_file_kyc" required <?php echo $approve_check; ?>><span class="radiobtn"></span>
                                    </label>
                                  
                                    <label class="css_checkbox_radio radio_only kyc_reject_radio <?php echo $reject_cls; ?> <?php echo $disabled_flg; ?>"> Reject
                                      <input type="radio" value="N" name="sign_file_kyc" required <?php echo $reject_check; ?>><span class="radiobtn"></span>
                                    </label>
                                  <?php }
                                  else
                                  { ?>
                                    <label class="css_checkbox_radio radio_only kyc_reject_radio"> Reject
                                      <input type="radio" value="N" name="sign_file_kyc" required checked><span class="radiobtn"></span>
                                    </label>
                                  <?php } ?>
                                </div>
                                <?php if (form_error('sign_file_kyc') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('sign_file_kyc'); ?></label> <?php } ?>
                              </td>
                            
                              <td><?php /* Id Proof */ ?>
                                <?php 
                                $kyc_radio_btn_details_id_proof = get_kyc_radio_btn_details($mem_data[0],'kyc_id_card_flag');
                                $approve_check = $kyc_radio_btn_details_id_proof['approve_check'];
                                $reject_check = $kyc_radio_btn_details_id_proof['reject_check'];
                                $approve_cls = $kyc_radio_btn_details_id_proof['approve_cls'];
                                $reject_cls = $kyc_radio_btn_details_id_proof['reject_cls'];
                                $disabled_flg = $kyc_radio_btn_details_id_proof['disabled_flg']; ?>
                                
                                <div id="id_proof_file_kyc_err" class="kyc_radio_outer">
                                  <?php if($empty_id_proof_flag == '') 
                                  { ?>                                
                                    <label class="css_checkbox_radio radio_only kyc_appprove_radio <?php echo $approve_cls; ?> <?php echo $disabled_flg; ?>"> <?php if($disabled_flg == '') { echo 'Approve'; } else { echo 'Approved'; } ?>
                                      <input type="radio" value="Y" name="id_proof_file_kyc" required <?php echo $approve_check; ?>><span class="radiobtn"></span>
                                    </label>
                                  
                                    <label class="css_checkbox_radio radio_only kyc_reject_radio <?php echo $reject_cls; ?> <?php echo $disabled_flg; ?>"> Reject
                                      <input type="radio" value="N" name="id_proof_file_kyc" required <?php echo $reject_check; ?>><span class="radiobtn"></span>
                                    </label>
                                  <?php }
                                  else
                                  { ?>
                                    <label class="css_checkbox_radio radio_only kyc_reject_radio"> Reject
                                      <input type="radio" value="N" name="id_proof_file_kyc" required checked><span class="radiobtn"></span>
                                    </label>
                                  <?php } ?>
                                </div>
                                <?php if (form_error('id_proof_file_kyc') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('id_proof_file_kyc'); ?></label> <?php } ?>
                              </td>

                              <td><?php /* Declaration */ ?>
                                <?php 
                                $kyc_radio_btn_details_declaration = get_kyc_radio_btn_details($mem_data[0],'kyc_declaration_flag');
                                $approve_check = $kyc_radio_btn_details_declaration['approve_check'];
                                $reject_check = $kyc_radio_btn_details_declaration['reject_check'];
                                $approve_cls = $kyc_radio_btn_details_declaration['approve_cls'];
                                $reject_cls = $kyc_radio_btn_details_declaration['reject_cls'];
                                $disabled_flg = $kyc_radio_btn_details_declaration['disabled_flg']; ?>
                                
                                <div id="declaration_file_kyc_err" class="kyc_radio_outer">
                                  <?php if($empty_declaration_flag == '') 
                                  { ?>                                
                                    <label class="css_checkbox_radio radio_only kyc_appprove_radio <?php echo $approve_cls; ?> <?php echo $disabled_flg; ?>"> <?php if($disabled_flg == '') { echo 'Approve'; } else { echo 'Approved'; } ?>
                                      <input type="radio" value="Y" name="declaration_file_kyc" required <?php echo $approve_check; ?>><span class="radiobtn"></span>
                                    </label>
                                  
                                    <label class="css_checkbox_radio radio_only kyc_reject_radio <?php echo $reject_cls; ?> <?php echo $disabled_flg; ?>"> Reject
                                      <input type="radio" value="N" name="declaration_file_kyc" required <?php echo $reject_check; ?>><span class="radiobtn"></span>
                                    </label>
                                  <?php }
                                  else
                                  { ?>
                                    <label class="css_checkbox_radio radio_only kyc_reject_radio"> Reject
                                      <input type="radio" value="N" name="declaration_file_kyc" required checked><span class="radiobtn"></span>
                                    </label>
                                  <?php } ?>
                                </div>
                                <?php if (form_error('declaration_file_kyc') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('declaration_file_kyc'); ?></label> <?php } ?>
                              </td>


                            </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                    </div>
                  
                    <?php if(count($mem_data) > 0)
                    { ?>
                      <div class="hr-line-dashed"></div>										
                      <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right" id="submit_btn_outer">
                          <?php if(count($kyc_pending_candidate_data) > 0) { ?>
                            <button class="btn btn-primary" type="submit" onclick="fun_form_action('submit_and_next')">Submit & Next</button>
                          <?php } ?>
                          <button class="btn btn-primary" type="submit" onclick="fun_form_action('submit_and_exit')">Submit & Exit</button>
                        </div>
                      </div>
                    <?php } ?>
                  </form>

								</div>               
							</div>
              <div id="common_log_outer"></div>                  
						</div>
					</div>
				</div>				
				
				<?php $this->load->view('ncvet/kyc/inc_footerbar_admin'); ?>	
			</div>
		</div>
		<?php $this->load->view('ncvet/kyc/inc_footer'); ?>

    <?php 
    $enc_pk_id = '';
    if($module_name == 'bcbf')
    {
      $enc_pk_id = url_encode($mem_data[0]['candidate_id']);
    }
    else if($module_name == 'ncvet')
    {
      $enc_pk_id = url_encode($mem_data[0]['candidate_id']);
    }
    else if($module_name == 'dra')
    {
      $enc_pk_id = url_encode($mem_data[0]['candidate_id']);
    }

    $module_slug = 'kyc_recommender_Approved,kyc_recommender_Rejected,kyc_approver_Approved,kyc_approver_Rejected';
    $this->load->view('ncvet/kyc/common/get_logs_common_ajax_call', array('enc_pk_id'=>$enc_pk_id, 'module_slug'=>$module_slug, 'log_title'=>'KYC Logs', 'module_name'=>$module_name));
  ?>
		
    <?php $this->load->view('ncvet/common/inc_common_validation_all'); ?>    
    <script type="text/javascript">  
      function fun_form_action(form_action)
      {
        $("#form_action").val(form_action);
      } 

      //START : JQUERY VALIDATION SCRIPT 
      function validate_input(input_id) { $("#"+input_id).valid(); }
      $(document ).ready( function() 
      {
        var form = $("#kyc_form").validate( 
        {
          onkeyup: function(element) { $(element).valid(); },          
          rules:
          {
            photo_file_kyc:{ required: true, },
            sign_file_kyc:{ required: true, },
            id_proof_file_kyc:{ required: true, },                       
            declaration_file_kyc:{ required: true, },                       
          },
          messages:
          {
            photo_file_kyc: { required: "Please select the option" },
            sign_file_kyc: { required: "Please select the option" },
            id_proof_file_kyc: { required: "Please select the option" },
            declaration_file_kyc: { required: "Please select the option" },
          }, 
          errorPlacement: function(error, element) // For replace error 
          {
            if (element.attr("name") == "photo_file_kyc") { error.insertAfter("#photo_file_kyc_err"); }
            else if (element.attr("name") == "sign_file_kyc") { error.insertAfter("#sign_file_kyc_err"); }
            else if (element.attr("name") == "id_proof_file_kyc") { error.insertAfter("#id_proof_file_kyc_err"); }
            else if (element.attr("name") == "declaration_file_kyc") { error.insertAfter("#declaration_file_kyc_err"); }
            else { error.insertAfter(element); }
          },          
          submitHandler: function(form) 
          {          
            $("#page_loader").hide();
            var photo_file_kyc = $('input[name="photo_file_kyc"]:checked').val();
            var sign_file_kyc = $('input[name="sign_file_kyc"]:checked').val();
            var id_proof_file_kyc = $('input[name="id_proof_file_kyc"]:checked').val();
            var declaration_file_kyc = $('input[name="declaration_file_kyc"]:checked').val();

            var swal_message = "Please confirm to ";
            if (photo_file_kyc === "Y") { swal_message += "Approve Photo"; } 
            else if (photo_file_kyc === "N") { swal_message += "Reject Photo"; } 

            if (sign_file_kyc === "Y") { swal_message += ", Approve Signature"; } 
            else if (sign_file_kyc === "N") { swal_message += ", Reject Signature"; }

            if (id_proof_file_kyc === "Y") { swal_message += ", Approve ID Proof"; } 
            else if (id_proof_file_kyc === "N") { swal_message += ", Reject ID Proof"; }

            if (declaration_file_kyc === "Y") { swal_message += ", Approve Declaration"; } 
            else if (declaration_file_kyc === "N") { swal_message += ", Reject Declaration"; }
            
            swal({ title: "Please confirm", text: swal_message+"?", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Confirm", closeOnConfirm: true }, function () 
            { 
              $("#page_loader").show();
              form.submit();
            }); 
          }
        });
      });
    </script>

		<?php $this->load->view('ncvet/kyc/common/inc_bottom_script'); ?>
	</body>
</html>