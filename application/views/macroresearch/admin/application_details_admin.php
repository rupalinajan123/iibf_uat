<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($page_title)) { echo $page_title; } else { echo 'IIBF'; } ?></title>
    <?php $this->load->view('macroresearch/inc_header'); ?>    
  </head>
  
	<body class="fixed-sidebar">
    <?php $this->load->view('macroresearch/common/inc_loader'); ?>
		
		<div id="wrapper">
      <?php $this->load->view('macroresearch/admin/inc_sidebar_admin'); ?>		
			<div id="page-wrapper" class="gray-bg">				
        <?php $this->load->view('macroresearch/admin/inc_topbar_admin'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-10">
						<h2>Application Details</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('macroresearch/admin/dashboard_admin'); ?>">Dashboard</a></li>
              <li class="breadcrumb-item">Masters</li>
							<li class="breadcrumb-item"><a href="<?php echo site_url('macroresearch/admin/application'); ?>">Application Master</a></li>
							<li class="breadcrumb-item active"> <strong>Application Details</strong></li>
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
										<!--<a href="<?php echo site_url('macroresearch/admin/application'); ?>" class="btn btn-danger custom_right_add_new_btn">Back</a> -->
                  </div>
                </div>
								<div class="ibox-content">
                  <div class="table-responsive">
                  <form method="post" style="display: inline-block;	vertical-align: top; margin: -5px 0 0 10px; width:99%;">
                  
										<table class="table table-bordered custom_inner_tbl table-striped" style="width:100%">
											<tbody>
                       
                      <tr>
                          <td>
                          <b style="vertical-align:top">Application Code </b> : 
                          <?php echo $app_rows[0]['application_code'] ?>
                          </td>
                          <td>
                          <b style="vertical-align:top">Application Date</b> : 
                          <?php echo date('d M, Y',strtotime($app_rows[0]['created_on'])) ?>
                        </td>
                        </tr>
                        <tr>
                          <td>
                          <b style="vertical-align:top">Title Of Research Proposal</b> : 
                          <?php echo $app_rows[0]['title_research_proposal'] ?>
                          </td>
                          <td>
                          <b style="vertical-align:top">Major Objectives of Research</b> : 
                          <?php echo $app_rows[0]['objectives'] ?>
                        </td>
                        </tr>
                        <tr>
                          <td>
                          <b style="vertical-align:top">Theme </b> : 
                          <?php echo $app_rows[0]['theme'] ?>
                          </td>
                          <td>
                          <b style="vertical-align:top">Proposal</b> : 
                          <i> <a target="_blank" href="<?php echo base_url(); ?>/uploads/macroresearch/<?php echo $app_rows[0]['proposal'] ?>">View file</a></i>
                        </td>
                        </tr>
                        
                      </tbody>
                    </table>
                    <h3>Candidate Details</h3>  
                    <div class="table-responsive">
										<table class="table table-bordered table-hover dataTables-example" style="width:100%">
											<thead>
												<tr> 
                          
													<th class="text-center nowrap">Name</th> 
													<th class="text-center nowrap">DOB</th> 
													<th class="text-center">Email</th>
													<th class="text-center nowrap">Mobile</th> 
													<th class="text-center nowrap">Nature Of Job</th> 
													<th class="text-center">Employer </th>
                          <th class="text-center">Designation </th>
                          <th class="text-center">Address </th>
                          <th class="text-center">Forwarding Letter </th>
                          <th class="text-center">CV </th>
												</tr>
											</thead>
											
											<tbody>
                      <?php foreach($app_rows as $app_row) {
                       // echo'<pre>';print_r($app_row);exit;
                      ?>
                      <tr>
                      <td><?php echo $app_row['candidate_name'] ?></td>
                      <td><?php echo date('d M, Y',strtotime($app_row['dob'])) ?></td>
                      <td><?php echo $app_row['email'] ?></td>
                      <td><?php echo $app_row['mobile'] ?></td>
                      <td><?php echo $app_row['nature_of_job'] ?></td>
                      <td><?php echo $app_row['employer'] ?></td>
                      <td><?php echo $app_row['designation'] ?></td>
                      <td><?php echo $app_row['address'] ?></td>
                      <td><a target="_blank" href="<?php echo base_url(); ?>/uploads/macroresearch/<?php echo $app_row['forwarding_letter'] ?>">View file</a></i></td>
                      <td><a target="_blank" href="<?php echo base_url(); ?>/uploads/macroresearch/<?php echo $app_row['resume'] ?>">View file</a></i></td>
                      </tr>
                      <?php 
                      } ?>
                      </tbody>
										</table>
									</div>		
                    </form>
                    
                  </div>                  
                </div>
              </div>
              <div id="common_log_outer"></div>
            </div>					
          </div>
        </div>
				<?php $this->load->view('macroresearch/admin/inc_footerbar_admin'); ?>		
      </div>
    </div>
    <?php $this->load->view('macroresearch/inc_footer'); ?>    

    <?php  
     // $this->load->view('macroresearch/common/get_logs_common_ajax_call', array('enc_pk_id'=>$enc_id, 'module_slug'=>'application_action,application_password_action', 'log_title'=>'Application Log'));
    ?>

    <?php $this->load->view('macroresearch/common/inc_common_validation_all'); ?>

    
    <?php $this->load->view('macroresearch/common/inc_bottom_script'); ?>

    <?php if($this->session->flashdata('application_status_success')) { ?><script>sweet_alert_success("<?php echo $this->session->flashdata('application_status_success'); ?>"); </script><?php } ?>
    <?php if($this->session->flashdata('application_status_error')) { ?><script>sweet_alert_error("<?php echo $this->session->flashdata('application_status_error'); ?>"); </script><?php } ?>

  </body>
  <script>
    $(document).ready(function()
			{
				var table = $('.dataTables-example').DataTable(
				{
          searching: true,
					"processing": false,
					
					"lengthMenu": [[10, 25, 50, 100, 500], [10, 25, 50, 100, 500]],
          "language": 
          {
						"lengthMenu": "_MENU_",
          },
          //"dom": '<"top"lf><"clear"><i>rt<"bottom row"<"col-sm-12 col-md-5" and i><"col-sm-12 col-md-7" and p>><"clear">',
					pageLength: 10,
					responsive: true,
                    			
				});
      }); 
      </script>
</html>