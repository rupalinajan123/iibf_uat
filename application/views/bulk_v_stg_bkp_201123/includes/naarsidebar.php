<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">  
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->  
    <div class="user-panel">
      <p><span style="color: #b8c7ce;"><strong><?php echo $this->session->userdata('institute_name'); ?></strong></span></p>
    </div> 
    <ul class="sidebar-menu">
    
    <li class="treeview <?php if(stristr(current_url(),'bulk/Naardashboard')) { echo 'active'; } ?>"> <a href="<?php echo base_url();?>bulk/Naardashboard"><i class="fa fa-dashboard"></i> Dashboard </a> </li>
    
    <li class="treeview <?php if(stristr(current_url(),'bulk/NaarBulkTransaction/transactions')) { echo 'active'; } ?>"> <a href="<?php echo base_url();?>bulk/NaarBulkTransaction/transactions"><i class="fa fa-list"></i> Transaction Details </a> </li>
    
      <li class="treeview <?php if(stristr(current_url(),'bulk/NaarBulkTransaction/neft_transactions')) { echo "active"; } ?>">
          <a href="<?php echo base_url();?>bulk/NaarBulkTransaction/neft_transactions">
          	<i class="fa fa-check-square-o"></i> <span>NEFT Details</span>
          </a>
      </li>
      
      <li class="treeview"> <a href="<?php echo base_url();?>bulk/Naarlogin/Logout"><i class="fa fa-sign-out"></i>Logout</a> </li>
      
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>