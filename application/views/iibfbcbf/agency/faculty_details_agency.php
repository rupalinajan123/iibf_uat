<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($page_title)) { echo $page_title; } else { echo 'IIBF'; } ?></title>
    <?php $this->load->view('iibfbcbf/inc_header'); ?>    
  </head>
  
	<body class="fixed-sidebar">
    <?php $this->load->view('iibfbcbf/common/inc_loader'); ?>
		
		<div id="wrapper">
      <?php $this->load->view('iibfbcbf/agency/inc_sidebar_agency'); ?>		
			<div id="page-wrapper" class="gray-bg">				
        <?php $this->load->view('iibfbcbf/agency/inc_topbar_agency'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-10">
						<h2>Faculty Details</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/agency/dashboard_agency'); ?>">Dashboard</a></li>
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/agency/faculty_master_agency'); ?>">Faculty Master</a></li>
							<li class="breadcrumb-item active"> <strong>Faculty Details</strong></li>
            </ol>
          </div>
					<div class="col-lg-2"> </div>
        </div>			
				
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox">
								<div class="ibox-title">
									<div class="ibox-tools">
										<a href="<?php echo site_url('iibfbcbf/agency/faculty_master_agency'); ?>" class="btn btn-danger custom_right_add_new_btn">Back</a>
                  </div>
                </div>
								<div class="ibox-content">
                  <div class="table-responsive">
										<table class="table table-bordered custom_inner_tbl" style="width:100%">
											<tbody>
                        <?php 
                          $sub_data['faculty_data'] = $form_data;
                          $sub_data['faculty_photo_path'] = $faculty_photo_path;
                          $sub_data['pan_photo_path'] = $pan_photo_path;
                          $this->load->view('iibfbcbf/common/inc_faculty_details_common',$sub_data);
                        ?>

                        <?php if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'agency' && $form_data[0]['created_by_type'] == '1') 
                        { ?>
                          <tr>
                            <td>
                              <b style="vertical-align:top">Status Change</b> :
                              <form method="post" style="display: inline-block;vertical-align: top;margin: -5px 0 0 10px;">  
                                <div id="status_err">
                                  <input type="hidden" id="current_faculty_status" value="<?php echo $form_data[0]['status']; ?>">
                                  <label class="css_checkbox_radio radio_only"> Active
                                    <input type="radio" value="1" name="status" id="active_radio" onchange="change_faculty_status('<?php echo url_encode($form_data[0]['faculty_id']); ?>','activate');" required <?php if($form_data[0]['status'] == '1') { echo "checked"; } ?>>
                                    <span class="radiobtn"></span>
                                  </label>&nbsp;&nbsp; 
                                  <label class="css_checkbox_radio radio_only"> Inactive
                                    <input type="radio" value="0" name="status" id="deactive_radio" onchange="reason_for_faculty_deactivate('<?php echo url_encode($form_data[0]['faculty_id']); ?>','deactivate');" required <?php if($form_data[0]['status'] == '0') { echo "checked"; } ?>>
                                    <span class="radiobtn"></span>
                                  </label>
                                </div> 
                              </form> 
                            </td>                             
                            <td></td>
                          </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                    
                    <div class="hr-line-dashed"></div>										
										<div class="text-center" id="submit_btn_outer">
                      <a href="<?php echo site_url('iibfbcbf/agency/faculty_master_agency'); ?>" class="btn btn-danger">Back</a>	
                    </div>
                  </div>                  
                </div>
              </div>
              
              <div id="common_log_outer"></div>
            </div>					
          </div>
        </div>
				<?php $this->load->view('iibfbcbf/agency/inc_footerbar_agency'); ?>		
      </div>
    </div>

    <div class="modal inmodal fade" id="faculty_deactivate_reason_modal" tabindex="-1" role="dialog"  aria-hidden="true">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Reason for Deactivate Faculty</h4>
              </div>
              <?php echo validation_errors(); ?>
              <form method="post" class="form-horizontal" id="reasonForm" autocomplete="off" enctype="multipart/form-data">
                 
                 <input type="hidden" name="faculty_id" id="faculty_id" /> 

                <div class="modal-body">
                  <div class="modal_form_outer">

                    <div class="form-group row">
                      <!-- <label class="col-lg-4 text-right"><b>Enter the Reason for Deactivate Faculty<sup class="text-danger">*</sup> :</b></label> -->
                      <div class="col-lg-12"> 
                        <textarea required maxlength="350" placeholder="Enter the Reason for Deactivate Faculty" name="reason_for_deactivate" id="reason_for_deactivate" class="form-control custom_input blockCharacters"></textarea>  
                        <span id="modal_error_msg" style="color:red;"></span>

                        <note class="form_note" id="reason_for_deactivate_err">Note: Please enter only 350 characters</note>
                          
                          <?php if(form_error('reason_for_deactivate')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('reason_for_deactivate'); ?></label> <?php } ?>

                      </div>
                    </div>  

                  </div>
                </div>

                <div class="modal-footer" id="submit_btn_outer"> 
                  <button type="submit" class="btn btn-success" id="btnSubmit">Submit</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>  
                </div>
              </form>
          </div>
      </div>
    </div>

		<?php $this->load->view('iibfbcbf/inc_footer'); ?>
    <?php $this->load->view('iibfbcbf/common/inc_common_validation_all'); ?>
    
    <?php  
      $this->load->view('iibfbcbf/common/get_logs_common_ajax_call', array('enc_pk_id'=>$enc_faculty_id, 'module_slug'=>'faculty_action', 'log_title'=>'Faculty Log'))
    ?>

    <script type="text/javascript"> 
      <?php if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'agency' && $form_data[0]['created_by_type'] == '1') 
      { ?>     

        $('#faculty_deactivate_reason_modal').on('hidden.bs.modal', function () {
          var status = $('#current_faculty_status').val();
          if(status == '1'){
            $("#active_radio").prop('checked',true);
            $("#deactive_radio").prop('checked',false);
          }else if(status == '0'){
            $("#deactive_radio").prop('checked',true);
            $("#active_radio").prop('checked',false);
          }else{
            $("#deactive_radio").prop('checked',false);
            $("#active_radio").prop('checked',false);
          }
          $("#modal_error_msg").text("");
          $("#reason_for_deactivate").val("");
        });

        function reason_for_faculty_deactivate(enc_id, exist_status){
            $("#faculty_id").val(enc_id);
            var modal = $("#faculty_deactivate_reason_modal");
            modal.modal({backdrop: 'static', keyboard: false}, 'show');
        }

        function change_faculty_status(enc_id, exist_status,reason_flag='')
        {
          var reason_for_deactivate = '';
          if(reason_flag == 'reason_required'){ reason_for_deactivate = $('#reason_for_deactivate').val(); }
          var status = $('#current_faculty_status').val();
          if(enc_id != "")
          {
            var status_nm = '';
            if(exist_status == 'deactivate') { status_nm = 'Deactivate'; }
            else if(exist_status == 'activate') { status_nm = 'Activate'; } 

            swal({ title: "Please confirm", text: "Please confirm, do you want to "+status_nm+" the Faculty", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: false }, function () 
            { 
              $("#page_loader").show(); 
              $.ajax(
              {
                type: "POST",
                url: "<?php echo site_url('iibfbcbf/agency/faculty_master_agency/change_faculty_status/'); ?>",
                data: { enc_id:enc_id, status:status_nm, status_num_val : $('#current_faculty_status').val(), reason_for_deactivate:reason_for_deactivate},
                //async: false,
                //cache : false,
                dataType: 'JSON',
                success: function(data)
                {
                  $('#current_faculty_status').val(data.status);
                  if($.trim(data.flag) == 'success')
                  { 
                    window.location.reload();
                    //sweet_alert_success(data.response);
                    //sweet_alert_success("Hiiii");
                    //$("#page_loader").hide(); 
                  }
                  else
                  { 
                    window.location.reload();
                    //sweet_alert_error("Error occurred. Please try again.");
                    //$("#page_loader").hide(); 
                  } 
                  // data.response;
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                  //$('#current_faculty_status').val(status);
                  console.log('AJAX request failed: ' + errorThrown);
                  sweet_alert_error("Error occurred. Please try again.");
                  $("#page_loader").hide();
                }
              });
            }); 

            $(".cancel").click(function () 
            {  
              $("#page_loader").hide(); 
              var status = $('#current_faculty_status').val();
              if(status == '1'){
                $("#active_radio").prop('checked',true);
                $("#deactive_radio").prop('checked',false);
              }else if(status == '0'){
                $("#deactive_radio").prop('checked',true);
                $("#active_radio").prop('checked',false);
              }else{
                $("#deactive_radio").prop('checked',false);
                $("#active_radio").prop('checked',false);
              }
            });            
          }
        } 
        //sweet_alert_success("Test");

        function validate_input(input_id) { $("#"+input_id).valid(); }
        $(document ).ready( function() 
        {
          $("#reasonForm").validate( 
          {
            //onfocusout: true,
            onkeyup: function(element) { $(element).valid(); },          
            rules:
            {
              reason_for_deactivate:{ required: true,  blockCharacters:true, maxlength:350 },
            },
            messages:
            {
              reason_for_deactivate: { required: "Please enter the reason for deactivating the faculty" },              
            }, 
            errorPlacement: function(error, element) // For replace error 
            {
              if (element.attr("name") == "reason_for_deactivate") { error.insertAfter("#reason_for_deactivate_err"); }
              else { error.insertAfter(element); }
            },          
            submitHandler: function(form) 
            {
              $("#page_loader").show();
              var faculty_id = $("#faculty_id").val();
              change_faculty_status(faculty_id, 'deactivate', 'reason_required')
            }
          });
        });
      <?php } ?>
    </script>

    <?php if($this->session->flashdata('faculty_status_success')) { ?><script>sweet_alert_success("<?php echo $this->session->flashdata('faculty_status_success'); ?>"); </script><?php } ?>
    <?php if($this->session->flashdata('faculty_status_error')) { ?><script>sweet_alert_error("<?php echo $this->session->flashdata('faculty_status_error'); ?>"); </script><?php } ?>

    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>
  </body>
</html>