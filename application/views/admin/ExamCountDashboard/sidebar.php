<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
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
        <li class="treeview <?php if(current_url() == base_url().'admin/ExamCountDashboard/ExamCount/index') { echo "active"; } ?>">
         <a href="<?php echo base_url();?>admin/ExamCountDashboard/ExamCount/index">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
        
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
		else
			$is_active = "";
			
		if(stristr(current_url(),'Downloads'))
			$download_active = "active";
		else
			$download_active = "";	
		
		?>
        
        <?php if($userRole == 1){?>
        
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
      
        
            <li class="treeview <?php if(current_url() == base_url().'admin/ExamCountDashboard/ExamCount/dowanload') { echo "active"; } ?>">
         <a href="<?php echo base_url();?>admin/ExamCountDashboard/ExamCount/dowanload">
            <i class="fa fa-calendar"></i> <span>Exam count as per Venue</span>
          </a>
        </li>
       <?php } ?> 
       
      
      
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
