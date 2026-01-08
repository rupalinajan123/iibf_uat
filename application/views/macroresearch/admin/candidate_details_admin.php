<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($page_title)) { echo $page_title; } else { echo 'IIBF'; } ?></title>
    <?php $this->load->view('supervision/inc_header'); ?>    
  </head>
  
	<body class="fixed-sidebar">
    <?php $this->load->view('supervision/common/inc_loader'); ?>
		
		<div id="wrapper">
      <?php $this->load->view('supervision/admin/inc_sidebar_admin'); ?>		
			<div id="page-wrapper" class="gray-bg">				
        <?php $this->load->view('supervision/admin/inc_topbar_admin'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-10">
						<h2>Observer Details</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('supervision/admin/dashboard_admin'); ?>">Dashboard</a></li>
              <li class="breadcrumb-item">Masters</li>
							<li class="breadcrumb-item"><a href="<?php echo site_url('supervision/admin/candidate'); ?>">Observer Master</a></li>
							<li class="breadcrumb-item active"> <strong>Observer Details</strong></li>
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
										<a href="<?php echo site_url('supervision/admin/candidate'); ?>" class="btn btn-danger custom_right_add_new_btn">Back</a>
                  </div>
                </div>
								<div class="ibox-content">
                  <div class="table-responsive">
                  <form method="post" style="display: inline-block;	vertical-align: top; margin: -5px 0 0 10px; width:99%;">  
										<table class="table table-bordered custom_inner_tbl table-striped" style="width:100%">
											<tbody>
                        <?php 
                          $sub_data['candidate_data'] = $form_data;
                          $this->load->view('supervision/common/inc_candidate_details_common',$sub_data);
                        ?>

                          
                        <tr>
                          <td>
                          <b style="vertical-align:top">Exam</b> : 
                          <select required name="exam_code" class="form-control select_exam">
                            
                          <?php foreach($exams as $exam) {
                            ?>
                              <option <?php if($form_data[0]['exam_code'] == $exam['exam_code']) echo'selected' ?> value="<?php echo $exam['exam_code'] ?>"><?php echo $exam['exam_name'] ?></option>
                            <?php
                          } ?>
                          </select>
                          </td>
                          <td>
                          <b style="vertical-align:top">Center</b> : 
                          <select required name="center_code" class="form-control select_center">
                            <option value="">Select</option>
                          <?php foreach($center_master_data as $center) {
                            ?>
                              <option style="display:none;" exam_code="<?php echo $center['exam_name'] ?>" <?php if($form_data[0]['center_code'] == $center['center_code']) echo'selected' ?> value="<?php echo $center['center_code'] ?>"><?php echo $center['center_name'] ?></option>
                            <?php
                          } ?>
                          </select>
                        </td>
                        </tr>
                        <tr>
                        <td>
                            <b style="vertical-align:top">Role</b> :                                                        
                            
                              <div id="status_err">
                                <input type="hidden" id="current_candidate_status" value="<?php echo $form_data[0]['role_id']; ?>">
                                <label class="css_checkbox_radio radio_only"> Co-ordinator
                                  <input type="radio" value="1" name="role_id" id="" required <?php if($form_data[0]['role_id'] == '1') { echo "checked"; } ?>>
                                  <span class="radiobtn" style=""></span>
                                </label>&nbsp;&nbsp; 
                                <label class="css_checkbox_radio radio_only"> Supervisor
                                  <input type="radio" value="2" name="role_id" id=""  required <?php if($form_data[0]['role_id'] == '2') { echo "checked"; } ?>>
                                  <span class="radiobtn" style=""></span>
                                </label>
                              </div> 
                            
                          </td>
                          <td>
                            <b style="vertical-align:top">Status</b> :                                                        
                            
                              <div id="status_err">
                                <input type="hidden" id="current_candidate_status" value="<?php echo $form_data[0]['is_active']; ?>">
                                <label class="css_checkbox_radio radio_only"> Active
                                  <input type="radio" value="1" name="status" id="active_radio"  required <?php if($form_data[0]['is_active'] == '1') { echo "checked"; } ?>>
                                  <span class="radiobtn" style=""></span>
                                </label>&nbsp;&nbsp; 
                                <label class="css_checkbox_radio radio_only"> Inactive
                                  <input type="radio" value="0" name="status" id="deactive_radio" required <?php if($form_data[0]['is_active'] == '0') { echo "checked"; } ?>>
                                  <span class="radiobtn" style=""></span>
                                </label>
                              </div> 
                            
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    </form>
                    <div class="hr-line-dashed"></div>		
                    <?php if($this->session->userdata('SUPERVISION_ADMIN_TYPE')!=1) { ?>								
										<div class="text-center" id="submit_btn_outer">
                      <button type="submit"  class="btn btn-success submit_candidate_details">Submit</button>	
                    </div>
                    <?php } ?>
                  </div>                  
                </div>
              </div>
              <div id="common_log_outer"></div>
            </div>					
          </div>
        </div>
				<?php $this->load->view('supervision/admin/inc_footerbar_admin'); ?>		
      </div>
    </div>
    <?php $this->load->view('supervision/inc_footer'); ?>    

    <?php  
     // $this->load->view('supervision/common/get_logs_common_ajax_call', array('enc_pk_id'=>$enc_id, 'module_slug'=>'candidate_action,candidate_password_action', 'log_title'=>'Candidate Log'));
    ?>

    <?php $this->load->view('supervision/common/inc_common_validation_all'); ?>

    <script type="text/javascript"> 
    exam_change_func();
    $('select.select_exam').change(function() {
      exam_change_func();
      $(".select_center").val('').trigger('change');
    });
    function exam_change_func() {
      var exam_code = $('select.select_exam option:selected').attr('value');
      $(".select_center option").each(function() {
        if($(this).attr('exam_code')==exam_code)
          $(this).show();
        else
        $(this).hide();

      });
      
    }
    $('.submit_candidate_details').click( function() {
      change_candidate_status("<?php echo url_encode($form_data[0]['id']); ?>");
    });     
      function change_candidate_status(enc_id)
      {
        var exam_code = $("select[name='exam_code']").val();
        var center_code = $("select[name='center_code']").val();
        
        var status_value = $("input[name='status']:checked").val();
        var role_id = $("input[name='role_id']:checked").val();
          
        if(status_value=='' || role_id=='' || exam_code=='' || center_code=='') {  // 
          //alert('Please select Status & assign role as well');
          alert('All fields are mandatory');
          return false;
        }
        if(enc_id != "")
        {
          var status_nm = 'Deactivate';
          if(status_value == '1'){ status_nm = 'Activate'; }

          swal({ title: "Please confirm", text: "Please confirm, do you want to save the Candidate details", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: false }, function () 
          { 
            $("#page_loader").show(); 
            $.ajax(
            {
              type: "POST",
              url: "<?php echo site_url('supervision/admin/candidate/change_candidate_status'); ?>",
              data: {enc_id:enc_id,status_value:status_value,role_id:role_id,exam_code:exam_code,center_code:center_code},
              /*async: false,
              cache : false,*/
              dataType: 'JSON',
              success: function(data)
              {
                $('#current_candidate_status').val(data.is_active);
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
                //$('#current_candidate_status').val(status);
                console.log('AJAX request failed: ' + errorThrown);
                sweet_alert_error("Error occurred. Please try again.");
                $("#page_loader").hide();
              }
            });
          }); 

          $(".cancel").click(function () 
          {  
            var current_candidate_status = $('#current_candidate_status').val();
            if(current_candidate_status == '1')
            {
              $("#active_radio").prop('checked',true);
              $("#deactive_radio").prop('checked',false);
            }
            else if(current_candidate_status == '0')
            {
              $("#deactive_radio").prop('checked',true);
              $("#active_radio").prop('checked',false);
            }
            else
            {
              $("#deactive_radio").prop('checked',false);
              $("#active_radio").prop('checked',false);
            }
          }); 
           
        }

      } 
      //sweet_alert_success("Test");
    </script> 
    <?php $this->load->view('supervision/common/inc_bottom_script'); ?>

    <?php if($this->session->flashdata('candidate_status_success')) { ?><script>sweet_alert_success("<?php echo $this->session->flashdata('candidate_status_success'); ?>"); </script><?php } ?>
    <?php if($this->session->flashdata('candidate_status_error')) { ?><script>sweet_alert_error("<?php echo $this->session->flashdata('candidate_status_error'); ?>"); </script><?php } ?>

  </body>
</html>