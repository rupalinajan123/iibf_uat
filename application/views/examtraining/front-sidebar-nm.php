<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
     <?php
     if($this->session->userdata('nmregid')!=''){?>
      <div class="user-panel">
        <!--<div class="pull-left">
         <p><?php //if($this->session->userdata('nmregid')!=''){ echo $this->session->userdata('nmregid'); } ?></p>
        </div>-->
        <div class="pull-left info">
          <p>
          <?php if($this->session->userdata('nmregid')!=''){ 
		 	  $username=$this->session->userdata('nmfirstname').' '.$this->session->userdata('nmmiddlename').' '.$this->session->userdata('nmlastname');
			  $userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
		 	 echo $userfinalstrname; 
			 } ?>
         </p>
          <!--<a href="#"><i class="fa fa-circle text-success"></i> Online</a>-->
        </div>
      </div>
       <!--  sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
       <!-- <li class="header">MAIN NAVIGATION</li>-->
        <!--<li class="treeview">
         <a href="<?php echo base_url();?>admin/MainController">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            <span class="pull-right-container">
            </span>
          </a>
        </li>-->
    
       
                    <li class="treeview">
                     <a href="<?php echo base_url()?>NonMember/examlist">
                        <i class="fa fa-map-marker"></i> <span>Apply for Exams  </span>
                        <!--<span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>-->
                      </a>
                    </li>
       
       <li class="treeview">
                      <a href="<?php echo base_url()?>NMExam/history/">
                        <i class="fa fa-book"></i> <span>Exam Application History</span>
                        <!--<span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>-->
                      </a>
                    </li>
                    
       
        <li class="treeview">
          <a href="<?php echo base_url()?>admitcard/getadmitdashboard" target="_blank">
            <i class="fa fa-book"></i> <span>Download ADMIT LETTER  </span>
            <!--<span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>-->
          </a>
        </li>
       
       
       <li class="treeview">
                      <a href="<?php echo base_url()?>NMTransaction/">
                        <i class="fa fa-book"></i> <span>Transaction Details</span>
                        <!--<span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>-->
                      </a>
                    </li>
                    
        <li class="treeview">
          <a href="<?php echo base_url();?>NonMember/profile/">
            <i class="fa fa-book"></i> <span>Edit Profile</span>
            <!--<span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>-->
          </a>
        </li>
        
        <li class="treeview">
        	  <a href="<?php echo base_url();?>NonMember/changepass/">
            <i class="fa fa-book"></i> <span>Change Password</span>
            <!--<span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>-->
          </a>
        </li>      
       
				
		
       <li class="treeview">
          <a href="<?php echo  base_url()?>Nonreg/logout/">
            <i class="fa fa-book"></i> <span>Logout</span>
            <!--<span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>-->
          </a>
        </li>
      </ul>
      <?php }?>
    </section>
    <!-- /.sidebar -->
  </aside>
