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
        <li class="treeview <?php if(current_url() == base_url().'admin/ippb/IppbDashboard') { echo "active"; } ?>">
         <a href="<?php echo base_url();?>admin/ippb/IppbDashboard">
            <i class="fa fa-dashboard"></i> <span>Employee/Agent Master</span>
          </a>
        </li>
        
       <li class="treeview">
         <a href="<?php echo base_url();?>admin/ippb/IppbDashboard/examReg">
            <i class="fa fa-list"></i> <span>Exam Registration Details</span>
          </a>
        </li>
        <li class="treeview">
         <a href="<?php echo base_url();?>admin/ippb/IppbDashboard/registered_member_search_form">
            <i class="fa fa-list"></i> <span>Edit member Details</span>
          </a>
        </li>
      <li class="treeview">
        <a href="<?php echo base_url();?>ippb_login/admin/login/Logout">
          <i class="glyphicon glyphicon-log-out"></i><span>Logout</span>
        </a>
      </li>

    
        
        <?php 
			$userRole = $this->session->userdata('roleid'); ?> 
       
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
