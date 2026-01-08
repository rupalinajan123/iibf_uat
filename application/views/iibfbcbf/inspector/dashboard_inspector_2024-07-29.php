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
			<?php $this->load->view('iibfbcbf/inspector/inc_sidebar_inspector'); ?>			
			<div id="page-wrapper" class="gray-bg">				
				<?php $this->load->view('iibfbcbf/inspector/inc_topbar_inspector'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-10">
						<h2>Dashboard </h2>
          </div>
					<div class="col-lg-2"> </div>
        </div>
				
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox float-e-margins text-centerx">
								<div class="ibox-title"><h2>Welcome To <?php echo ucfirst($this->session->userdata('IIBF_BCBF_USER_TYPE')); ?> Dashboard</h2></div>
								<div class="ibox-content">
									<h4>
										<?php echo date("d F, Y. h:i A"); ?>	
                  </h4>
                </div> 
              </div>
            </div>
          </div>
          
          <div class="row justify-content-md-centerx" style="display:nonex;" >
            <div class="col-lg-4">
              <div class="ibox ">
                <div class="ibox-title">                    
                  <h5>Total Inspection : <?php echo $total_inspection_cnt; ?></h5>
                  <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                </div>
                <div class="ibox-content">
                  <table class="table table-bordered mb-0">
                    <tbody>
                      <tr>
                        <td><b>Total Inspection</b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"><b><?php echo $total_inspection_cnt; ?></b></a></td>
                      </tr>
                      <tr>
                        <td><b>Total Completed Inspection</b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"><b><?php echo $total_completed_inspection_cnt; ?></b></a></td>
                      </tr>
                      <tr>
                        <td><b>Total Upcoming Inspection</b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"><b><?php echo $total_upcoming_inspection_cnt; ?></b></a></td>
                      </tr>
                      <tr>
                        <td><b>Total Ongoing</b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"><b><?php echo $total_ongoing_inspection_cnt; ?></b></a></td>
                      </tr>
                      <tr>
                        <td><b>Total Missed Inspection</b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"><b><?php echo $total_missed_inspection_cnt; ?></b></a></td>
                      </tr>
                      <tr>
                        <td><b>Total Re-Inspection</b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"><b><?php echo $total_re_inspection_cnt; ?></b></a></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            
            <div class="col-lg-8">
              <?php /*  PIE CHART CODE */?> 
              <div class="ibox">
                <div class="ibox-title">
                  <h5>Statistics</h5>                  
                </div>
                <div class="ibox-content">                  
                  <div>
                    <div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                    <canvas id="doughnutChart" style="display: block; width: 100px; height: 100px;" class="chartjs-render-monitor"></canvas>
                  </div>
                </div>
              </div> 
              
            </div>
          </div>
        </div>				
				
				<?php $this->load->view('iibfbcbf/inspector/inc_footerbar_inspector'); ?>		
        
        
      </div>
    </div>
		<?php $this->load->view('iibfbcbf/inc_footer'); ?>			
        
    <script src="<?php echo auto_version(base_url('assets/iibfbcbf/js/plugins/chartJs/Chart.min.js')); ?>"></script>
    <script>
      $(function () 
      {
        var doughnutData = 
        {
          labels: ["Total Completed Inspection", "Total Upcoming Inspection", "Total Ongoing", "Total Missed Inspection", "Total Re-Inspection"],
          datasets: [
          {
            data: ['<?php echo $total_completed_inspection_cnt; ?>', '<?php echo $total_upcoming_inspection_cnt; ?>', '<?php echo $total_ongoing_inspection_cnt; ?>', '<?php echo $total_missed_inspection_cnt; ?>', '<?php echo $total_re_inspection_cnt; ?>'],
            backgroundColor: ["#28b463", "#f1c40f", "#3498db", "#ec7063", "#bb8fce"]
          }]
        } ;
        
        var doughnutOptions =
        {
          responsive: true,
        };
        
        var ctx4 = document.getElementById("doughnutChart").getContext("2d");
        new Chart(ctx4, {type: 'doughnut', data: doughnutData, options:doughnutOptions}); 
      });
    </script>  
    
    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>	
  </body>
</html>