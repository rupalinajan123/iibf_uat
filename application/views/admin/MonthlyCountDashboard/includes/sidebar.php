
<aside class="main-sidebar">
  <section class="sidebar">
    <div class="user-panel">
      <div class="pull-left"> </div>
      <div class="pull-left info">
        <p>
          <?php if($this->session->userdata('username')!=''){ echo "Welcome ".$this->session->userdata('username'); } ?>
        </p>
        <a href="javascript:void(0);">
        <?php if($this->session->userdata('role')!=''){ echo '('.$this->session->userdata('role').')'; } ?>
        </a> </div>
    </div>
    <br />
    <ul class="sidebar-menu">
      <li class="treeview"> <a href="<?php echo base_url();?>admin/MonthlyCount/monthlycount"> <i class="fa fa-dashboard"></i> <span>Dashboard</span> </a> </li>
      
    </ul>
  </section>
</aside>
