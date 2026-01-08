<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
  <?php $id_admin = $this->session->userdata('id');?>
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left">
         <p><?php /*if($this->session->userdata('username')!=''){ echo $this->session->userdata('username'); }*/ ?></p>
        </div>
        <div class="pull-left info">
          <p><?php if($this->session->userdata('username')!=''){ echo "Welcome ".$this->session->userdata('username'); } ?></p>
          <a href="javascript:void(0);"><?php if($this->session->userdata('role')!=''){ echo '('.$this->session->userdata('role').')'; } ?></a>
          <!--<a href="#"><i class="fa fa-circle text-success"></i> Online</a>-->
        </div>
      </div>
      <br/>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
       <!-- <li class="header">MAIN NAVIGATION</li>-->
        <li class="treeview <?php if(current_url() == base_url().'admin/MainController') { echo "active"; } ?>">
         <a href="<?php echo base_url();?>admin/MainController">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
         <?php if($id_admin == '96' || $id_admin == '99'){ ?>
					 <li class="treeview <?php if(current_url() == base_url().'admin/Kycme mber') { echo "active"; } ?>">
				 <a href="<?php echo base_url();?>admin/Kycmember">
					<i class="fa fa-dashboard"></i> <span>KYC List</span>
				  </a>
				</li>
				 <li class="treeview <?php if(current_url() == base_url().'admin/Kycmember/statistic') { echo "active"; } ?>">
				 <a href="<?php echo base_url();?>admin/Kycmember/statistic">
					<i class="fa fa-dashboard"></i> <span>User wise kyc report</span>
				  </a>
				</li>
				
				<li class="treeview <?php if(current_url() == base_url().'admin/Kycmember/asondate') { echo "active"; } ?>">
				 <a href="<?php echo base_url();?>admin/Kycmember/asondate">
					<i class="fa fa-dashboard"></i> <span>As on date Report</span>
				  </a>
				</li>
			<?php } else { ?>
       <!-- <li class="treeview">
         <a href="<?php //echo base_url();?>admin/MainController/Menu">
            <i class="fa fa-dashboard"></i> <span>Menus</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-right pull-right"></i>
            </span>
          </a>
        </li>-->
    
        <!--<li class="treeview">
         <a href="<?php echo base_url();?>admin/MainController/Page/AccessPermissions">
            <i class="fa fa-map-marker"></i> <span>Access Permissions</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
        </li>-->
        
        <?php 
			$userRole = $this->session->userdata('roleid');
			
			if($userRole != 3){
		?>
        <?php 
			$home_active = '';
						
			if(stristr(current_url(),'Report/datewise'))
				$home_active = "active";
			else if(stristr(current_url(),'Page/Users'))
				$home_active = "active";
			else if(stristr(current_url(),'admin/Admitcard'))
				$home_active = "active";
	    ?>
        <li class="treeview <?php echo $home_active; ?>">
          <a href="#">
            <i class="fa fa-home"></i> <span>My Home</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?php if(stristr(current_url(),'Report/datewise')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/Report/datewise"><i class="fa fa-circle-o"></i> Date Wise </a></li>
            
            <?php if($userRole == 1){?>
            	<li <?php if(stristr(current_url(),'Page/Users')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/MainController/Page/Users"><i class="fa fa-circle-o"></i> User Mgnt</a></li>
                <li <?php if(stristr(current_url(),'admin/Admitcard')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/Admitcard"><i class="fa fa-circle-o"></i> Admit Card Settings</a></li>
            <?php } ?> 
           
            <li><a href="<?php echo base_url();?>admin/login/Logout"><i class="fa fa-circle-o"></i>Logout</a></li>
          </ul>
        </li>
        
        <?php 
			$report_active = '';
			if(stristr(current_url(),'Report/BD_success'))
				$report_active = "active";
			else if(stristr(current_url(),'Report/BD_failure'))
				$report_active = "active";
			else if(stristr(current_url(),'dup_icard_success'))
				$report_active = "active";
			else if(stristr(current_url(),'Report/dup_icard_failure'))
				$report_active = "active";
			else if(stristr(current_url(),'Report/examReg'))
				$report_active = "active";
	    ?>
         <li class="treeview <?php echo $report_active; ?>">
          <a href="#">
            <i class="fa fa-file"></i> <span>Reports</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?php if(stristr(current_url(),'Report/BD_success')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/Report/BD_success"><i class="fa fa-circle-o"></i>Success Bill Desk</a></li>
            <li <?php if(stristr(current_url(),'Report/BD_failure') && !stristr(current_url(),'Report/BD_failure/reason')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/Report/BD_failure"><i class="fa fa-circle-o"></i>Failure Bill Desk </a></li>
            <li <?php if(stristr(current_url(),'Report/BD_failure/reason')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/Report/BD_failure/reason"><i class="fa fa-circle-o"></i>Failure Reason</a></li>
            <!--<li><a href="javascript:void(0);"><i class="fa fa-circle-o"></i>Payment Options</a></li>-->
            <li <?php if(stristr(current_url(),'Report/dup_icard_success')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/Report/dup_icard_success"><i class="fa fa-circle-o"></i>Dup i-card Success</a></li>
            <li <?php if(stristr(current_url(),'Report/dup_icard_failure') && !stristr(current_url(),'Report/dup_icard_failure/reason')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/Report/dup_icard_failure"><i class="fa fa-circle-o"></i>Dup i-card Failure</a></li>
            <li <?php if(stristr(current_url(),'dup_icard_failure/reason')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/Report/dup_icard_failure/reason"><i class="fa fa-circle-o"></i>Dup i-card Failure Reason</a></li>
            <?php if($userRole == 1){?>
            	<li <?php if(stristr(current_url(),'Report/examReg')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/Report/examReg"><i class="fa fa-circle-o"></i>Exam Registration Details</a></li>
            <?php } ?>
			<li <?php if(stristr(current_url(),'Report/dupCert')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/Report/dupCert"><i class="fa fa-circle-o"></i>Duplicate Certificate Transaction</a></li>
          </ul>
        </li>
        
      <?php 
	  	$search_active = '';
	  	if(stristr(current_url(),'Search/success'))
			$search_active = "active";
		else if(stristr(current_url(),'Search/failure'))
			$search_active = "active";
		else if(stristr(current_url(),'failCandReport'))
			$search_active = "active";
		else if(stristr(current_url(),'deactivate'))
			$search_active = "active";
		else if(stristr(current_url(),'Refund'))
			$search_active = "active";
	  ?>
        <li class="treeview <?php echo $search_active ?>">
          <a href="#">
            <i class="fa fa-search"></i> <span>Search</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?php if(stristr(current_url(),'Search/success')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/Search/search_success"><i class="fa fa-circle-o"></i>Success Transaction<br />(Only Registration)</a></li>
            <li <?php if(stristr(current_url(),'Search/failure')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/Search/search_failure"><i class="fa fa-circle-o"></i> Failure Transaction</a></li>
            <li <?php if(stristr(current_url(),'Report/failCandReport')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/Report/failCandReport"><i class="fa fa-circle-o"></i>View Failure candidates</a></li>
           <?php if($userRole == 1){?>
            <li <?php if(stristr(current_url(),'deactivate')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/Member/de_active"><i class="fa fa-circle-o"></i>Deactivation - New Membership</a></li>
            <li <?php if(stristr(current_url(),'Refund')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/Refund"><i class="fa fa-circle-o"></i>Billdesk Transaction Refund</a></li>
           <?php } ?>
          </ul>
        </li>
        
        
        <?php
		if(stristr(current_url(),'ExamMaster'))
			$is_active = "active";
		else if(stristr(current_url(),'MiscMaster'))
			$is_active = "active";
		else if(stristr(current_url(),'SubjectMaster'))
			$is_active = "active";
		else if(stristr(current_url(),'CenterMaster'))
			$is_active = "active";
		else if(stristr(current_url(),'MediumMaster'))
			$is_active = "active";
		else if(stristr(current_url(),'FeeMaster'))
			$is_active = "active";
		else if(stristr(current_url(),'InstitutionMaster'))
			$is_active = "active";
		else if(stristr(current_url(),'ExamActiveMaster'))
			$is_active = "active";
		else if(stristr(current_url(),'EligibleMaster'))
			$is_active = "active";
		else if(stristr(current_url(),'StateMaster'))
			$is_active = "active";
		else if(stristr(current_url(),'DesignationMaster'))
			$is_active = "active";
    else if(stristr(current_url(),'InspectorMaster'))
      $is_active = "active";
		else
			$is_active = "";
			
		if(stristr(current_url(),'Downloads'))
			$download_active = "active";
		else
			$download_active = "";	
		
		?>
        
        <?php if($userRole == 1){?>
        <li class="treeview <?php echo $is_active ?>">
          <a href="#">
            <i class="fa fa-book"></i> <span>Masters</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?php if(stristr(current_url(),'ExamMaster')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/ExamMaster"><i class="fa fa-circle-o"></i> Exam Master</a></li>
            <li <?php if(stristr(current_url(),'MiscMaster')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/MiscMaster"><i class="fa fa-circle-o"></i> Misc Master</a></li>
            <li <?php if(stristr(current_url(),'SubjectMaster')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/SubjectMaster"><i class="fa fa-circle-o"></i> Subject Master</a></li>
            <li <?php if(stristr(current_url(),'CenterMaster')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/CenterMaster"><i class="fa fa-circle-o"></i> Center Master</a></li>
            <li <?php if(stristr(current_url(),'MediumMaster')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/MediumMaster"><i class="fa fa-circle-o"></i> Medium Master</a></li>
            <li <?php if(stristr(current_url(),'FeeMaster')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/FeeMaster"><i class="fa fa-circle-o"></i> Exam Fee Master</a></li>
            <li <?php if(stristr(current_url(),'InstitutionMaster')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/InstitutionMaster"><i class="fa fa-circle-o"></i> Institution Master</a></li>
            <li <?php if(stristr(current_url(),'ExamActiveMaster')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/ExamActiveMaster"><i class="fa fa-circle-o"></i> Exam Activation Master</a></li>
            <li <?php if(stristr(current_url(),'EligibleMaster')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/EligibleMaster"><i class="fa fa-circle-o"></i> Eligible Master</a></li>
            <li <?php if(stristr(current_url(),'StateMaster')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/StateMaster"><i class="fa fa-circle-o"></i> State Master</a></li>
            <li <?php if(stristr(current_url(),'DesignationMaster')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/DesignationMaster"><i class="fa fa-circle-o"></i> Designation Master</a></li>

            <li <?php if(stristr(current_url(),'InspectorMaster')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/InspectorMaster"><i class="fa fa-circle-o"></i> Inspector Master</a></li>
          </ul>
        </li>
        <?php } ?>
        <!--<li class="treeview">
          <a href="#">
            <i class="fa fa-book"></i> <span>Admin Menu</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
          	<li><a href="<?php echo base_url();?>admin/MainController/Page/Roles"><i class="fa fa-circle-o"></i> Role Master</a><li>
            <li><a href="<?php echo base_url();?>admin/MainController/Page/Users"><i class="fa fa-circle-o"></i> User Master</a><li>
          </ul>
        </li>-->
        
        <?php if($userRole == 1){?>
        <li class="treeview <?php echo $download_active; ?>">
          <a href="#">
            <i class="fa fa-download"></i> <span>Downloads</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
          	<li <?php if(stristr(current_url(),'Downloads/data/1')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/Downloads/data/1"><i class="fa fa-circle-o"></i>Data</a><li>
            <li <?php if(stristr(current_url(),'Downloads/data/2')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/Downloads/data/2"><i class="fa fa-circle-o"></i>Edited Data</a><li>
            <!--<li><a href="javascript:void(0);"><i class="fa fa-circle-o"></i>Audit Log</a><li>
            <li><a href="javascript:void(0);"><i class="fa fa-circle-o"></i>Image Audit Log</a><li>-->
          </ul>
        </li>
        
        <li class="treeview <?php if(stristr(current_url(),'Pages')) { echo 'active'; } ?>">
         <a href="<?php echo base_url();?>admin/Pages">
            <i class="fa fa-laptop"></i> <span>Pages</span>
          </a>
        </li>
        <?php } ?>
         <li class="treeview <?php if(current_url() == base_url().'admin/Kycmember') { echo "active"; } ?>">
         <a href="<?php echo base_url();?>admin/Kycmember">
            <i class="fa fa-dashboard"></i> <span>KYC List</span>
          </a>
        </li>
        
        <li class="treeview <?php if(current_url() == base_url().'admin/Kycmember/statistic') { echo "active"; } ?>">
         <a href="<?php echo base_url();?>admin/Kycmember/statistic">
            <i class="fa fa-dashboard"></i> <span>User wise kyc report</span>
          </a>
        </li>
        
        <li class="treeview <?php if(current_url() == base_url().'admin/Kycmember/asondate') { echo "active"; } ?>">
         <a href="<?php echo base_url();?>admin/Kycmember/asondate">
            <i class="fa fa-dashboard"></i> <span>As on date Report</span>
          </a>
        </li>
        
        <!-- 
        - SAGAR WALZADE : Code start here (this 2 menus again cmmented out)
        - Changes : Menu hide : statistics (admin/CountController), Exam count as per venue (admin/ExamVenueCount/download)
        - date : 10-5-2022
        -->
         <li class="treeview <?php if(current_url() == base_url().'admin/CountController') { echo "active"; } ?>">
         <a href="<?php echo base_url();?>admin/CountController">
            <i class="fa fa-calendar"></i> <span>Statistics</span>
          </a>
        </li> 
        
        <li class="treeview <?php if(current_url() == base_url().'admin/ExamVenueCount/dowanload') { echo "active"; } ?>">
          <a href="<?php echo base_url();?>admin/ExamVenueCount/dowanload">
            <i class="fa fa-calendar"></i> <span>Exam count as per Venue</span>
          </a>
        </li>
        <!-- SAGAR WALZADE : Code end here -->
        
         
         <!--<li class="treeview <?php if(current_url() == base_url().'admin/CountController') { echo "active"; } ?>">
         <a href="<?php echo base_url();?>admin/CountController">
            <i class="fa fa-calendar"></i> <span>Statistics</span>
          </a>
        </li>-->
       <?php } ?> 
       
      
      
        <?php if($userRole != 4 && $userRole != 5){ ?>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-home"></i> <span> Query Report</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?php if(stristr(current_url(),'Report/query')) { echo 'class="active"'; } ?>>
			<a href="<?php echo base_url();?>admin/Report/query"><i class="fa fa-circle-o"></i> Query Report</a></li>
			<li><a href="<?php echo base_url();?>Refund_details/member"><i class="fa fa-circle-o"></i> Membership Details</a></li>
           	<li><a href="<?php echo base_url();?>admin/login/Logout"><i class="fa fa-circle-o"></i>Logout</a></li>
          </ul>
        </li>
        
			<?php } }?>
      
			<?php if($userRole == 1){
		?>
    <li class="treeview <?php if(current_url() == base_url().'admin/Garp/examReg') { echo "active"; } ?>">
         <a href="<?php echo base_url();?>admin/Garp/examReg">
            <i class="fa fa-dashboard"></i> <span>Garp Registrations</span>
          </a>
        </li>
        <?php } ?>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
