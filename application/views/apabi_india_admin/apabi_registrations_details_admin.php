<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($page_title)) { echo $page_title; } else { echo 'IIBF'; } ?></title>
    <?php $this->load->view('apabi_india/inc_header'); ?>    
  </head>
  
	<body class="fixed-sidebar">
    <?php $this->load->view('apabi_india/inc_loader'); ?>
		
		<div id="wrapper">
      <?php $this->load->view('apabi_india_admin/inc_sidebar_admin'); ?>		
			<div id="page-wrapper" class="gray-bg">				
        <?php $this->load->view('apabi_india_admin/inc_topbar_admin'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-10">
						<h2>Registration Details</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('apabi_india_admin/dashboard_admin'); ?>">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="<?php echo site_url('apabi_india_admin/apabi_admin_registrations'); ?>">Registration Data</a></li>
							<li class="breadcrumb-item active"> <strong>Registration Details</strong></li>
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
										<a href="<?php echo site_url('apabi_india_admin/apabi_admin_registrations'); ?>" class="btn btn-danger custom_right_add_new_btn">Back</a>
                  </div>
                </div>
								<div class="ibox-content">
                  <div class="table-responsive">
										<table class="table table-bordered custom_inner_tbl" style="width:100%">
											<tbody>
                        <tr>
                          <td colspan="2" class="text-center heading_row"><b>Registration Details</b></td>
                        </tr>
                        
                        <tr>
                          <td><b>Participant ID</b> : <?php echo $form_data[0]['apabi_india_code']; ?></td>
                          <td><b>Name</b> : <?php echo $form_data[0]['salutation'].' '.$form_data[0]['name']; ?></td>
                        </tr>
                        
                        <tr>
                          <td><b>Organization Name</b> : <?php echo $form_data[0]['org_name']; ?></td>                          
                          <td><b>Designation</b> : <?php echo $form_data[0]['designation']; ?></td>
                        </tr>
                        
                        <tr>
                          <td><b>Mobile Number</b> : <?php echo $form_data[0]['mobile']; ?></td>
                          <td><b>Email id</b> : <?php echo $form_data[0]['email']; ?></td>
                        </tr>

                        <tr>
                          <td><b>Registration Date</b> : <?php echo date("d/m/Y h:iA", strtotime($form_data[0]['created_on'])); ?></td>
                          <td></td>
                        </tr>
                      </tbody>
                    </table>
                    
                    <div class="hr-line-dashed"></div>										
										<div class="text-center" id="submit_btn_outer">
                      <a href="<?php echo site_url('apabi_india_admin/apabi_admin_registrations'); ?>" class="btn btn-danger">Back</a>	
                    </div>
                  </div>                  
                </div>
              </div>
            </div>					
          </div>
        </div>
				<?php $this->load->view('apabi_india_admin/inc_footerbar_admin'); ?>		
      </div>
    </div>
		<?php $this->load->view('apabi_india/inc_footer'); ?>		

   	<?php $this->load->view('apabi_india/inc_common_validation_all'); ?>
    <?php $this->load->view('apabi_india/inc_bottom_script'); ?>
  </body>
</html>