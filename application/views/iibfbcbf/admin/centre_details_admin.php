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
      <?php $this->load->view('iibfbcbf/admin/inc_sidebar_admin'); ?>		
			<div id="page-wrapper" class="gray-bg">				
        <?php $this->load->view('iibfbcbf/admin/inc_topbar_admin'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-10">
						<h2>Centre Details</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/admin/dashboard_admin'); ?>">Dashboard</a></li>
              <li class="breadcrumb-item">Masters</li>
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/admin/centre'); ?>">Centre Master</a></li>
							<li class="breadcrumb-item active"> <strong>Centre Details</strong></li>
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
										<a href="<?php echo site_url('iibfbcbf/admin/centre'); ?>" class="btn btn-danger custom_right_add_new_btn">Back</a>
                  </div>
                </div>
								<div class="ibox-content">
                  <div class="table-responsive">
										<table class="table table-bordered custom_inner_tbl" style="width:100%">
											<tbody>
                        <?php 
                          $sub_data['centre_data'] = $form_data;
                          $this->load->view('iibfbcbf/common/inc_centre_details_common',$sub_data);
                        ?>

                        <tr>
                          <td colspan="2">
                            <b style="vertical-align:top">Status</b> :                             
                            <form method="post" style="display: inline-block;vertical-align: top;margin: -5px 0 0 10px;">  
                              <div id="status_err">
                                <input type="hidden" id="current_centre_status" value="<?php echo $form_data[0]['status']; ?>">
                                <label class="css_checkbox_radio radio_only"> Active
                                  <input type="radio" value="1" name="status" id="active_radio" onchange="change_centre_status('<?php echo url_encode($form_data[0]['centre_id']); ?>','activate');" required <?php if($form_data[0]['status'] == '1') { echo "checked"; } ?>>
                                  <span class="radiobtn" style=""></span>
                                </label>&nbsp;&nbsp; 
                                <label class="css_checkbox_radio radio_only"> Inactive
                                  <input type="radio" value="0" name="status" id="deactive_radio" onchange="change_centre_status('<?php echo url_encode($form_data[0]['centre_id']); ?>','deactivate');" required <?php if($form_data[0]['status'] == '0') { echo "checked"; } ?>>
                                  <span class="radiobtn" style=""></span>
                                </label>
                              </div> 
                            </form> 
                          </td>
                        </tr>

                      </tbody>
                    </table>
                    
                    <div class="hr-line-dashed"></div>										
										<div class="text-center" id="submit_btn_outer">
                      <a href="<?php echo site_url('iibfbcbf/admin/centre'); ?>" class="btn btn-danger">Back</a>	
                    </div>
                  </div>                  
                </div>
              </div>
               

              <div id="common_log_outer"></div>

            </div>					
          </div>
        </div>
				<?php $this->load->view('iibfbcbf/admin/inc_footerbar_admin'); ?>		
      </div>
    </div>
		<?php $this->load->view('iibfbcbf/inc_footer'); ?>		

    <?php  
      $this->load->view('iibfbcbf/common/get_logs_common_ajax_call', array('enc_pk_id'=>$enc_centre_id, 'module_slug'=>'centre_action,centre_password_action', 'log_title'=>'Centre Log'));
    ?>

		<?php $this->load->view('iibfbcbf/common/inc_common_validation_all'); ?>

    <script type="text/javascript">
      
      function change_centre_status(enc_id,exist_status){
        var status = $('#current_centre_status').val();
        if(enc_id != ""){
          var status_nm = '';
          if(exist_status == 'deactivate'){
            status_nm = 'Deactivate';
          }else if(exist_status == 'activate'){
            status_nm = 'Activate';
          } 

          swal({ title: "Please confirm", text: "Please confirm, do you want to "+status_nm+" the Centre", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: false }, function () 
            { 
              $("#page_loader").show(); 
              $.ajax(
              {
                type: "POST",
                url: "<?php echo site_url('iibfbcbf/admin/centre/change_centre_status/'); ?>",
                data: {enc_id:enc_id,status:status_nm, status_num_val : $('#current_centre_status').val()},
                /*async: false,
                cache : false,*/
                dataType: 'JSON',
                success: function(data)
                {
                  $('#current_centre_status').val(data.status);
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
                  //$('#current_centre_status').val(status);
                  console.log('AJAX request failed: ' + errorThrown);
                  sweet_alert_error("Error occurred. Please try again.");
                  $("#page_loader").hide();
                }
              });
            }); 

            $(".cancel").click(function () {  
                var status = $('#current_centre_status').val();
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
    </script>

    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>

    <?php if($this->session->flashdata('centre_status_success')) { ?><script>sweet_alert_success("<?php echo $this->session->flashdata('centre_status_success'); ?>"); </script><?php } ?>
    <?php if($this->session->flashdata('centre_status_error')) { ?><script>sweet_alert_error("<?php echo $this->session->flashdata('centre_status_error'); ?>"); </script><?php } ?>

  </body>
</html>