<?php $drauserdata = $this->session->userdata('vendor_admin');?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left">
                <p><?php //if($drauserdata['username']!=''){ echo $drauserdata['username']; } ?></p>
            </div>
            <div class="pull-left info">
                <p><?php if($drauserdata['username']!=''){ echo $drauserdata['name']; } ?></p>
            </div>
        </div>
        <br />
        <!-- sidebar menu: : style can be found in sidebar.less -->

        <ul class="sidebar-menu">
        <?php //if($drauserdata['roleid'] == '1'){ ?>
             <li class="header" style="color:#2ea0e2">Dashboard</li>
            
            <li class="treeview <?php if(stristr(current_url(),'vendors/Dashboard')) { echo "active"; } ?>">
                <a href="<?php echo base_url();?>vendors/Dashboard">
                    <i class="fa fa-home"></i> <span>Vendor Registration</span>
                </a>
            </li> 

        <?php //}
          ?>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>