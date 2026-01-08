<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <p><span style="color: #b8c7ce;"><strong><?php echo $this->session->userdata('institute_name'); ?></strong></span></p>
    </div>
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu">
      <li class="header">MAIN NAVIGATION</li><?php //echo current_url(); ?>
      <li class="treeview <?php if(current_url() == base_url().'bulk/Bankdashboard') { echo 'active'; } ?>"> <a href="<?php echo base_url();?>bulk/Bankdashboard"> <i class="fa fa-dashboard"></i> <span>Dashboard</span> </a> </li>
      <li class="treeview <?php if(current_url() == base_url().'bulk/Bankdashboard/view_profile') { echo 'active'; } ?>"> <a href="<?php echo base_url();?>bulk/Bankdashboard/view_profile"><i class="fa fa-user"></i> View Profile </a> </li>
     
      <li class="treeview <?php if(stristr(current_url(),'bulk/BulkApply/examlist') || stristr(current_url(),'bulk/BulkApply/examdetails') || stristr(current_url(),'bulk/BulkApply/exam_applicantlst') || stristr(current_url(),'bulk/BulkApplyNM/add_member')) { echo 'active'; } ?>"> <a href="<?php echo base_url();?>bulk/BulkApply/examlist"><i class="fa fa-tasks"></i> 
       
       <?php 
        $menu_text = "Apply For Exam";
        if ($this->session->userdata('is_admin')=='yes') {
           $menu_text = "Make Payment";
         } 
        echo $menu_text; 
       ?> 
       

    </a> </li>

  <!--    <li class="treeview <?php// if(stristr(current_url(),'bulk/BulkApply/all_exam_applicantlst') || stristr(current_url(),'bulk/BulkApply/all_exam_applicantlst/')) { echo 'active'; } ?>"> <a href="<?php //echo base_url();?>bulk/BulkApply/all_exam_applicantlst"><i class="fa fa-credit-card"></i> Make Payment </a> </li>-->
      <!--<li class="treeview"> <a href="#"><i class="fa fa-circle-o"></i> View Admit Card </a> </li>-->
      <li class="treeview <?php if(stristr(current_url(),'bulk/BulkTransaction/transactions')) { echo 'active'; } ?>"> <a href="<?php echo base_url();?>bulk/BulkTransaction/transactions"><i class="fa fa-list"></i> Transaction Details </a> </li>
      <li class="treeview <?php if(stristr(current_url(),'bulk/BulkTransaction/neft_transactions')) { echo "active"; } ?>">
          <a href="<?php echo base_url();?>bulk/BulkTransaction/neft_transactions">
          	<i class="fa fa-check-square-o"></i> <span>NEFT Details</span>
          </a>
      </li>
      <li class="treeview"> <a href="<?php echo base_url();?>bulk/Banklogin/Logout"><i class="fa fa-sign-out"></i>Logout</a> </li>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>