<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
     <?php
	 $user_images=array();
	 
     if($this->session->userdata('memtype')!=''){?>
      <div class="user-panel">
        <!--<div class="pull-left">
         <p><?php //if($this->session->userdata('memtype')!=''){ echo $this->session->userdata('memtype'); } ?></p>
        </div>-->
        <div class="pull-left info">
          <p><?php if($this->session->userdata('regid')!=''){ 
            //echo'<pre>';print_r($this->session->userdata());die;
		 	  $username=$this->session->userdata('firstname').' '.$this->session->userdata('middlename').' '.$this->session->userdata('lastname');
			  $userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
		 	   //echo $userfinalstrname; 
			  } 
			   ?></p>
         <!-- <a href="#"><i class="fa fa-circle text-success"></i> Online</a>-->
        </div>
      </div>
     <!--  sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <!--<li class="header">MAIN NAVIGATION</li>-->
        <!--<li class="treeview">
         <a href="<?php echo base_url();?>admin/MainController">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            <span class="pull-right-container">
            </span>
          </a>
        </li>-->
       		<li class="treeview">
         <a href="<?php echo base_url()?>Home/examlist">
            <i class="fa fa-map-marker"></i> <span>Apply for Exams*******  </span>
            <!--<span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>-->
          </a>
        </li>
     
	
    
    <li class="treeview">
          <a href="<?php echo base_url()?>Exam/history/">
            <i class="fa fa-book"></i> <span>Exam Application History</span>
            <!--<span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>-->
          </a>
        </li>
        <?php
        	//if($this->session->userdata('showlink') == "yes"){
		?>
        <li class="treeview">
          <a href="<?php echo base_url()?>admitcard/getadmitdashboard" target="_blank">
            <i class="fa fa-book"></i> <span>Download<br />ADMIT LETTER </span>
            <!--<span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>-->
          </a>
        </li>
        <?php //}?>
						<li class="treeview">
          <a href="<?php echo base_url()?>Transaction/">
            <i class="fa fa-book"></i> <span>Transaction Details</span>
            <!--<span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>-->
          </a>
        </li>
        <li class="treeview">
          <a href="<?php echo base_url()?>home/change_center/">
            <i class="fa fa-book"></i> <span>Center Change Request</span>
            <!--<span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>-->
          </a>
        </li>
        <li class="treeview">
          <a href="<?php echo base_url();?>Home/profile/">
            <i class="fa fa-book"></i> <span>Edit Profile</span>
            <!--<span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>-->
          </a>
        </li>
        
        
   
              <!--<li class="treeview">
              <a href="<?php //echo base_url()?>Duplicate/card/">
                <i class="fa fa-book"></i> <span>Duplicate I-card</span>
              </a>
            </li>-->
        
          <li class="treeview">
          <a href="<?php echo base_url();?>home/changepass/">
            <i class="fa fa-book"></i> <span>Change Password</span>
            <!--<span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>-->
          </a>
        </li>
        <?php
		//echo $this->session->userdata('memtype')  ;exit;
			if($this->session->userdata('memtype') == 'O' || $this->session->userdata('memtype') == 'F' || $this->session->userdata('memtype') == 'A'){
			$currdate = date("Y-m-d");
        	$registerdate = explode(" ",$this->session->userdata('createdon'));
			
			$where1 = array('regnumber'=> $this->session->userdata('regnumber'));
			$orderby = array("kyc_id"=>"Desc");
			$chkuser = $this->master_model->getRecords('member_kyc',$where1,'kyc_status,user_edited_date',$orderby);
			//if(isset($chkuser[0]['kyc_status'])){
		?>
        <li class="treeview">
          <a href="<?php echo base_url();?>idcard/downloadidcard_new">
            <i class="fa fa-book"></i>
            <span>
            	Membership/<br />Duplicate ID-card
            </span>
          </a>
        </li>       
  		<?php } //}?>
		
		<!--<li class="treeview">
          <a href="<?php //echo  base_url()?>RPEGstRecovery" target="_blank">
            <i class="fa fa-book"></i> <span>RPE Gst Payment</span>
            
          </a>
        </li>-->
		
          <li class="treeview">
          <a href="<?php echo  base_url()?>login/logout/">
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
