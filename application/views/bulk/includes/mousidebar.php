<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">  
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->  
    <div class="user-panel">
      <p><span style="color: #b8c7ce;"><strong><?php echo $this->session->userdata('institute_name'); ?></strong></span></p>
    </div> 
    <ul class="sidebar-menu">
    
    <li class="treeview <?php if(stristr(current_url(),'bulk/BulkApply/mouexamlist')) { echo 'active'; } ?>"> <a href="<?php echo base_url();?>bulk/BulkApply/mouexamlist"><i class="fa fa-dashboard"></i> Dashboard </a> </li>

    <li class="treeview <?php if(current_url() == base_url().'bulk/MouBulkTransaction/view_profile') { echo 'active'; } ?>"> <a href="<?php echo base_url();?>bulk/MouBulkTransaction/view_profile"><i class="fa fa-user"></i> View Profile </a> </li>

    
    <li class="treeview <?php if(stristr(current_url(),'bulk/MouBulkTransaction/transactions')) { echo 'active'; } ?>"> <a href="<?php echo base_url();?>bulk/MouBulkTransaction/transactions"><i class="fa fa-list"></i> Transaction Details </a> </li>
    
      <li class="treeview <?php if(stristr(current_url(),'bulk/MouBulkTransaction/neft_transactions')) { echo "active"; } ?>">
          <a href="<?php echo base_url();?>bulk/MouBulkTransaction/neft_transactions">
            <i class="fa fa-check-square-o"></i> <span>NEFT Details</span>
          </a>
      </li>
      
      <li class="treeview"> <a href="<?php echo base_url();?>bulk/Moulogin/Logout"><i class="fa fa-sign-out"></i>Logout</a> </li>
      
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>