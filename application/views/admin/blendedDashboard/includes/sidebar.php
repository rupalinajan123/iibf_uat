
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
      <li class="treeview"> <a href="<?php echo base_url();?>admin/blended/BlendedData"> <i class="fa fa-dashboard"></i> <span>Dashboard</span> </a> </li>
      <li class="treeview">
      <li <?php if(stristr(current_url(),'admin/blended/BlendedData/memberList') || stristr(current_url(),'admin/finquest/Finquest/')) { echo 'class="active"'; } ?>> <a href="<?php echo base_url();?>admin/blended/BlendedData/memberList"><i class="fa fa-circle-o"></i> Blended Registrations</a> </li>
      </li>
    </ul>
  </section>
</aside>
