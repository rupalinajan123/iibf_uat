<?php $this->load->view('admin/finquest_dashboard/includes/header');?>
<?php $this->load->view('admin/finquest_dashboard/includes/sidebar');?>

  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        As on date report
      </h1>
      </section>
          <section class="content">
              <div class="box">
              <div class="box-header">
              
                   </div>
       <div class="form-group">
             <div class="box-body">
              <form class="form-horizontal" action="<?php echo base_url();?>admin/finquest/Finquest/asondate" method="post">
            <center> 	
              <b> Counts  From <?php echo $this->config->item('kyc_start_date');?> To <?php  echo   date('Y-m-d');?></b>
                       <div class="col-sm-12">
                        <div class="col-sm-6">
                        <div class="panel panel-default">
                          <div class="panel-body">
                          <b> Total Members Applied For Duplicate Icard</b>
                          </div>
                          <div class="panel-footer"><?php  echo $dup_card_count;?></div>
                        </div>
                         </div>
                         
                         <div class="col-sm-6">
                        <div class="panel panel-default">
                          <div class="panel-body">
                          <b> Total  Membership Icard Download Count</b>
                          </div>
                          <div class="panel-footer"><?php  echo $dwn_mem_icard_count;?></div>
                        </div>
                         </div>
                    </div>
                     
                      </center>
               
                     
                  
                   </form> 
                   
             
                    </div>
              
		    </div>
           
              <div class="box-body" style="display: block;">
              <div class="table-responsive">
                                     <table class="table table-bordered">
							<tr style="background-color:#7FD1EA;">
                          
							  <td width="55%"><strong>Title</strong></td>
							  <td width="15%" class="text-center" nowrap="nowrap"><strong>Odinary  Member (O)</strong></td> 
                                <td width="15%" class="text-center" nowrap="nowrap"><strong>Non member (NM & DB) </strong></td> 
                             
							</tr>
							<tr>
							  <td> Total New Registration</td>
							 <td class="text-center"><?php echo  $new_registration_count_M;?></td>
                                <td class="text-center"><?php echo $new_registration_count_NM;?></td>
                             
							 </tr>
                             <tr>
							  <td>Total Edited Profile</td>
							 <td class="text-center"><?php echo  $edit_registration_count_M;?></td>
                                <td class="text-center"><?php echo   $edit_registration_count_NM;?></td>
                             
							 </tr>
           
                  </table>    
                  <table class="table table-bordered">
							<tr style="background-color:#7FD1EA;">
                          
							  <td width="55%"><strong>Title</strong></td>
							  <td width="15%" class="text-center" nowrap="nowrap"><strong>Odinary  Member (O)</strong></td> 
                                <td width="15%" class="text-center" nowrap="nowrap"><strong>Non member (NM & DB) </strong></td> 
                             
							</tr>
							<tr>
							  <td> KYC approved for New member </td>
							 <td class="text-center"><?php echo $approve_new_member ?></td>
                                <td class="text-center"><?php echo $approve_new_nonmember ?></td>
                             
							 </tr>
                             <tr>
							  <td>KYC approved for Edit member</td>
							 <td class="text-center"><?php echo $approve_edit_member;?></td>
                                <td class="text-center"><?php echo  $approve_edit_nonmember;?></td>
                             
							 </tr>
           
                  </table>
                              <table class="table table-bordered">
							<tr style="background-color:#7FD1EA;">
                       
							  <td width="55%"><strong>Pending for recommender</strong></td>
							  <td width="15%" class="text-center" nowrap="nowrap"><strong>Odinary  Member (O)</strong></td> 
                                <td width="15%" class="text-center" nowrap="nowrap"><strong>Non member (NM & DB) </strong></td> 
                                 <td width="15%" class="text-center" nowrap="nowrap"><strong>Download</strong></td> 
							</tr>
							<tr>
							  <td> New member </td>
							 <td class="text-center"><?php echo    $pending_new_list_member; ?></td>
                                <td class="text-center"><?php echo $pending_new_list_nonmembers ?></td>
                                  <td class="text-center"><a  data-toggle="tooltip" class="btn btn-warning" href="<?php echo base_url('admin/Kycmember/recommender_new_download_CSV'); ?>"> Download CSV </a></td>
                             
							 </tr>
                             <tr>
							  <td>Edit member </td>
							 <td class="text-center"><?php echo $pending_edit_member;?></td>
                                <td class="text-center"><?php echo  $pending_edit_nonmember;?></td>
                               <td class="text-center"><a  data-toggle="tooltip" class="btn btn-warning" href="<?php echo base_url('admin/Kycmember/recommender_edit_download_CSV'); ?>"> Download CSV </a></td>
							 </tr>
           
                  </table>  
                  
                  <table class="table table-bordered">
							<tr style="background-color:#7FD1EA;">
                       
							  <td width="55%"><strong>Pending for approver</strong></td>
							  <td width="15%" class="text-center" nowrap="nowrap"><strong>Odinary  Member (O)</strong></td> 
                                <td width="15%" class="text-center" nowrap="nowrap"><strong>Non member (NM & DB) </strong></td> 
                                 <td width="15%" class="text-center" nowrap="nowrap"><strong>Download</strong></td> 
							</tr>
							<tr>
							  <td> New member </td>
							 <td class="text-center"><?php echo    $approver_new_pending; ?></td>
                                <td class="text-center"><?php echo $approver_new_pending_non ?></td>
                                  <td class="text-center"><a  data-toggle="tooltip" class="btn btn-warning" href="<?php echo base_url('admin/Kycmember/approver_new_download_CSV'); ?>"> Download CSV </a></td>
                             
							 </tr>
                             <tr>
							  <td>Edit member </td>
							 <td class="text-center"><?php echo $approver_edit_pending;?></td>
                                <td class="text-center"><?php echo  $approver_edit_pending_non;?></td>
                               <td class="text-center"><a  data-toggle="tooltip" class="btn btn-warning" href="<?php echo base_url('admin/Kycmember/approver_edit_download_CSV'); ?>"> Download CSV </a></td>
							 </tr>
           
                  </table> 
                    </div>
              
		    </div>
          
              
                  </div>
                       </section>       
	</div>
    
<?php $this->load->view('admin/includes/footer');?>