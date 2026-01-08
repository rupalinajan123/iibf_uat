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
        <li class="treeview <?php if(current_url() == base_url().'admin/Center_change/examReg') { echo "active"; } ?>">
         <a href="<?php echo base_url();?>admin/Center_change/examReg">
            <i class="fa fa-dashboard"></i> <span>Center Change Request</span>
          </a>
        </li>
         
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
