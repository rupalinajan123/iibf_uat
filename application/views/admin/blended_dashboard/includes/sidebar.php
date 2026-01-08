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
        <li class="treeview <?php if(current_url() == base_url().'admin/blended/BlendedDashboard') { echo "active"; } ?>">
         <a href="<?php echo base_url();?>admin/blended/BlendedDashboard">
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
        <li class="treeview <?php echo $home_active; ?>">
          <a href="#">
             <i class="glyphicon glyphicon-tasks"></i> <span>Blended Traning</span>
            
          </a>
          <ul class="treeview-menu">
            <li <?php if(stristr(current_url(),'admin/blended/BlendedDashboard/counts')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/blended/BlendedDashboard/counts"><i class="fa fa-circle-o"></i> Batch  Registration  Counts </a></li>
     
                 <li <?php if(stristr(current_url(),'admin/blended/BlendedDashboard/')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/blended/BlendedDashboard/"><i class="fa fa-circle-o"></i>Member Registration  Counts</a></li>
            
   
           
          
          </ul>
        </li>
        
        <li class="treeview <?php echo $home_active; ?>">
          <a href="#">
            <i class="glyphicon glyphicon-tasks"></i> <span>Contact Classes Traning</span>
         
          </a>
          <ul class="treeview-menu">
            <li <?php if(stristr(current_url(),'admin/blended/BlendedDashboard/cc_subject_list')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/blended/BlendedDashboard/cc_subject_list"><i class="fa fa-circle-o"></i> Subject  Registration  Counts </a></li>
     
                 <li <?php if(stristr(current_url(),'admin/blended/BlendedDashboard/cc_member_list')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/blended/BlendedDashboard/cc_member_list"><i class="fa fa-circle-o"></i>Member Registration  Counts</a></li>
            
   
           
          
          </ul>
        </li>
        
       
       <?php } ?> 
       
  <li><a href="<?php echo base_url();?>blended_login/admin/login/Logout"><i class="glyphicon glyphicon-log-out"></i>Logout</a></li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
