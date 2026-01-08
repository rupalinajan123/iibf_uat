<!-- Left side column. contains the logo and sidebar -->
  
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
     <?php
     if($this->session->userdata('dbregid')!=''){?>
      <div class="user-panel">
        <div class="pull-left">
         <p><?php if($this->session->userdata('dbregid')!=''){ echo $this->session->userdata('dbregid'); } ?></p>
        </div>
        <div class="pull-left info">
          <p>
          <?php if($this->session->userdata('dbregid')!=''){ 
		 	  $username=$this->session->userdata('dbfirstname').' '.$this->session->userdata('dbmiddlename').' '.$this->session->userdata('dblastname');
			  $userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
		 	 echo $userfinalstrname; 
			 } ?>
         </p>
          <!--<a href="#"><i class="fa fa-circle text-success"></i> Online</a>-->
        </div>
      </div>
      <br />
     <!--  sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
   <!--     <li class="header">MAIN NAVIGATION</li>-->
        <!--<li class="treeview">
         <a href="<?php echo base_url();?>admin/MainController">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            <span class="pull-right-container">
            </span>
          </a>
        </li>-->
    
       
                    <li class="treeview">
                     <a href="<?php echo base_url()?>Dbf/examlist">
                        <i class="fa fa-map-marker"></i> <span>Apply for Exams  </span>
                        <!--<span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>-->
                      </a>
                    </li>
       
       <li class="treeview">
                      <a href="<?php echo base_url()?>Dbfexam/history/">
                        <i class="fa fa-book"></i> <span>Exam Application History</span>
                        <!--<span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>-->
                      </a>
                    </li>
                    
                 
                 	      <li class="treeview">
                          <a href="<?php echo base_url()?>admitcard/getadmitdashboard" target="_blank">
                            <i class="fa fa-book"></i> <span>Download<br />ADMIT LETTER </span>
                            <!--<span class="pull-right-container">
                              <i class="fa fa-angle-left pull-right"></i>
                            </span>-->
                          </a>
        </li>
                  
       
       <li class="treeview">
                      <a href="<?php echo base_url()?>DBFTransaction/">
                        <i class="fa fa-book"></i> <span>Transaction Details</span>
                        <!--<span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>-->
                      </a>
                    </li>
                    
        <li class="treeview">
          <a href="<?php echo base_url();?>Dbf/profile/">
            <i class="fa fa-book"></i> <span>Edit Profile</span>
            <!--<span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>-->
          </a>
        </li>
        
        <li class="treeview">
        	  <a href="<?php echo base_url();?>Dbf/changepass/">
            <i class="fa fa-book"></i> <span>Change Password</span>
            <!--<span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>-->
          </a>
        </li>      
       
				
		
       <li class="treeview">
          <a href="<?php echo  base_url()?>Dbf/logout/">
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
