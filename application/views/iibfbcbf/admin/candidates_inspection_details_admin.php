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
						<h2>Candidate Inspection Details</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/admin/dashboard_admin'); ?>">Dashboard</a></li>
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/admin/inspection_summary_admin/index/'.$enc_batch_id); ?>">Inspection Summary (<?php echo $batch_data[0]['batch_code']; ?>)</a></li>
							<li class="breadcrumb-item active"> <strong>Candidate Inspection Details</strong></li>
            </ol>
          </div>
					<div class="col-lg-2"> </div>
        </div>			
				
				<div class="wrapper wrapper-content animated fadeInRight">
          <div class="row">
						<div class="col-lg-12">
              <?php if(isset($candidate_inspection_data) && count($candidate_inspection_data) > 0) { ?>
                <div class="ibox mb-2">
                  <div class="ibox-title" style="background:#effdff;">
                    <h5>
                      Candidate Inspection Details : 
                      <?php 
                        echo $candidate_data[0]['salutation'].' '.$candidate_data[0]['first_name']; 
                        if($candidate_data[0]['middle_name'] != "") { echo " ".$candidate_data[0]['middle_name']; } 
                        if($candidate_data[0]['last_name'] != "") { echo " ".$candidate_data[0]['last_name']; } 
                        echo " (".$candidate_data[0]['training_id'].")";
                      ?>
                    </h5>
                    <div class="ibox-tools">
                      <a href="<?php echo site_url('iibfbcbf/admin/inspection_summary_admin/index/'.$enc_batch_id); ?>" class="btn btn-danger custom_right_add_new_btn">Back</a> 
                    </div>
                  </div>

                  <div class="ibox-content">
                    <div class="table-responsive">
                      <table class="table table-bordered table-hover table-striped batch_inspection_form_tbl dataTables-example" style="width:100%">
                        <thead>
                          <tr> 
                            <th class="no-sort text-center nowrap" style="width:60px;">Sr. No.</th>
                            <th class="text-center nowrap">Inspection No</th>
                            <th class="text-center nowrap">Inspector Name</th>
                            <th class="text-center nowrap">Attendance</th>
                            <th class="text-center nowrap">Date</th>                        
                            <th class="text-center nowrap" style="min-width:100px;">Remark</th>              
                          </tr>
                        </thead>
                        
                        <tbody>
                          <?php $sr_no = 1;
                          foreach($candidate_inspection_data as $candidate_inspection_res)
                          { ?>
                            <tr>
                              <td class="text-center"><?php echo $sr_no; ?></td>
                              <td class="text-center"><?php echo $candidate_inspection_res['inspection_no']; ?></td>
                              <td class=""><?php echo $candidate_inspection_res['inspector_name']; ?></td>
                              <td class="text-center"><?php echo $candidate_inspection_res['attendance']; ?></td>
                              <td class=""><?php echo $candidate_inspection_res['created_on']; ?></td>
                              <td class=""><?php echo $candidate_inspection_res['remark']; ?></td>
                            </tr>
                          <?php $sr_no++;
                          } ?>
                        </tbody>
                      </table>
                  </div>
                </div>
              <?php } ?>
            </div>					
          </div>
        </div>
				<?php $this->load->view('iibfbcbf/admin/inc_footerbar_admin'); ?>			
      </div>
    </div>
		<?php $this->load->view('iibfbcbf/inc_footer'); ?>		
    <?php $this->load->view('iibfbcbf/common/inc_common_validation_all'); ?>

    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>
  </body>
</html>