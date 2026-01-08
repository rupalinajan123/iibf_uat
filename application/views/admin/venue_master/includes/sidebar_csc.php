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
       
        <!--
            <li class="treeview <?php if(current_url() == base_url().'admin/venue_master/CSCVenueDashboard') { echo "active"; } ?>">
         <a href="<?php echo base_url();?>admin/venue_master/CSCVenueDashboard">
            <i class="fa fa-calendar"></i> <span>CSC count as per Venue</span>
          </a>
        </li>-->
		
		<!-- <li class="treeview <?php if(current_url() == base_url().'admin/venue_master/CSCVenueDashboard/edit_venue') { echo "active"; } ?>">
         <a href="<?php echo base_url();?>admin/venue_master/CSCVenueDashboard/upload_csv">
            <i class="fa fa-calendar"></i> <span>CSC Venue Master Update</span>
          </a>
        </li>-->
        <li class="treeview <?php if(current_url() == base_url().'admin/venue_master/CSCVenueDashboard/exam_registered_data') { echo "active"; } ?>">
         <a href="<?php echo base_url();?>admin/venue_master/CSCVenueDashboard/exam_registered_data">
            <i class="fa fa-calendar"></i> <span>Send Candidate Data</span>
          </a>
        </li>
       <?php //} ?> 
       
      
      
      
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
