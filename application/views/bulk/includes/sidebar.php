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
      <li class="header">MAIN NAVIGATION</li>
      
      <li class="treeview <?php echo (current_url() == base_url().'bulk/Bankdashboard/view_profile') ? 'active' : ''; ?>"> 
        <a href="<?php echo base_url();?>bulk/Bankdashboard/view_profile">
          <i class="fa fa-user"></i> View Profile 
        </a> 
      </li>
     <!-- ### added mou flag condition for mou exam list by pooja - 2024-10-11 ### -->
      <?php if($this->session->userdata('mou_flg') == '1'){ ?>
        <li class="treeview <?php echo (stristr(current_url(),'bulk/BulkApply/mouexamlist')) ? 'active' : ''; ?>"> 
          <a href="<?php echo base_url();?>bulk/BulkApply/mouexamlist">
            <i class="fa fa-tasks"></i> 
            <?php 
              $menu_text = "Apply For Exam";
              if ($this->session->userdata('is_admin') == 'yes') {
                $menu_text = "Make Payment";
              } 
              echo $menu_text; 
            ?> 
          </a> 
        </li>
      <?php }  ### ----- added mou flag condition for mou exam list by pooja - 2024-10-11 -----### 
      else { ?>

        <?php if($this->session->userdata('institute_id') != '17171'){ ?>
        <li class="treeview <?php echo (current_url() == base_url().'bulk/Bankdashboard') ? 'active' : ''; ?>"> 
         <a href="<?php echo base_url();?>bulk/Bankdashboard"> 
          <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a> 
        </li>
      <?php }else if($this->session->userdata('institute_id') == '17171' && $this->session->userdata('is_admin') == 'yes'){ ?>
        <li class="treeview <?php echo (current_url() == base_url().'bulk/Bankdashboard') ? 'active' : ''; ?>"> 
         <a href="<?php echo base_url();?>bulk/Bankdashboard"> 
          <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a> 
        </li>
      <?php } ?>

        <li class="treeview <?php echo (stristr(current_url(),'bulk/BulkApply/examlist') || stristr(current_url(),'bulk/BulkApply/examdetails') || stristr(current_url(),'bulk/BulkApply/mouexamlist') || stristr(current_url(),'bulk/BulkApply/exam_applicantlst') || stristr(current_url(),'bulk/BulkApplyNM/add_member')) ? 'active' : ''; ?>"> 
          <a href="<?php echo base_url();?>bulk/BulkApply/examlist">
            <i class="fa fa-tasks"></i> 
            <?php 
              $menu_text = "Apply For Exam";
              if ($this->session->userdata('is_admin') == 'yes') {
                $menu_text = "Make Payment";
              } 
              echo $menu_text; 
            ?> 
          </a> 
        </li>
      <?php } 

      if($this->session->userdata('institute_id') != '17171'){
      ?> 
      <li class="treeview <?php echo (stristr(current_url(),'bulk/BulkTransaction/transactions')) ? 'active' : ''; ?>"> 
        <a href="<?php echo base_url();?>bulk/BulkTransaction/transactions">
          <i class="fa fa-list"></i> Transaction Details 
        </a> 
      </li>
      <?php 
          if($this->session->userdata('mou_flg')==1 && $this->session->userdata('is_admin') != 'yes') {  //Priyanka D >>  DBFMOUPAYMENTCHANGE >> 10-july-25
            ?>
            <li class="treeview <?php echo (stristr(current_url(),'bulk/BulkTransaction/proforma_invoice_payment')) ? 'active' : ''; ?>">
              <a href="<?php echo base_url();?>bulk/BulkTransaction/proforma_invoice_payment">
                <i class="fa fa-check-square-o"></i> <span>Proforma Invoice Payment</span>
              </a>
            </li>
            <?php 
          } else { //Priyanka D >>  DBFMOUPAYMENTCHANGE >> 10-july-25
            ?>
      <li class="treeview <?php echo (stristr(current_url(),'bulk/BulkTransaction/neft_transactions')) ? 'active' : ''; ?>">
        <a href="<?php echo base_url();?>bulk/BulkTransaction/neft_transactions">
          <i class="fa fa-check-square-o"></i> <span>NEFT Details</span>
        </a>
      </li>
      <?php } ?>
      
    <?php }else if($this->session->userdata('institute_id') == '17171' && $this->session->userdata('is_admin') == 'yes'){ ?>
      <li class="treeview <?php echo (stristr(current_url(),'bulk/BulkTransaction/transactions')) ? 'active' : ''; ?>"> 
        <a href="<?php echo base_url();?>bulk/BulkTransaction/transactions">
          <i class="fa fa-list"></i> Transaction Details 
        </a> 
      </li>
      <li class="treeview <?php echo (stristr(current_url(),'bulk/BulkTransaction/neft_transactions')) ? 'active' : ''; ?>">
        <a href="<?php echo base_url();?>bulk/BulkTransaction/neft_transactions">
          <i class="fa fa-check-square-o"></i> <span>NEFT Details</span>
        </a>
      </li>
    <?php } ?>
      <li class="treeview"> 
        <a href="<?php echo base_url();?>bulk/Banklogin/Logout">
          <i class="fa fa-sign-out"></i>Logout
        </a> 
      </li>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>
