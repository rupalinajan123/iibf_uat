<?php $donationadminuserdata = $this->session->userdata('donation_admin');?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    
    <div class="user-panel">
      <div class="pull-left"> </div>
      <div class="pull-left info">
        <p>
          <?php if($donationadminuserdata['name']!=''){ echo $donationadminuserdata['name']; } ?>
        </p>
      </div>
    </div>
    <br />
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu">
      <li class="header" style="color:#2ea0e2">List</li>
      <li class="treeview <?php if(stristr(current_url(),'MainController')) { echo "active"; } ?>"> <a href="<?php echo base_url();?>donations_admin/admin/Donation_admin/donation_admin_list"> <i class="fa fa-home"></i> <span>Donation Form</span> </a> </li>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>
