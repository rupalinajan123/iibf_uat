<?php $drainspdata = $this->session->userdata('dra_inspector');
if($drainspdata ) { ?>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <p><span style="color: #b8c7ce;"><?php echo $drainspdata['inspector_name'];?></span></p>
                <p></p>
            </div>
            <!--  sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
               <!--  <li class="treeview">
                    <a href="<?php //echo base_url();?>iibfdra/Version_2/InspectorHome/editprofile">
                        <i class="fa fa-user"></i><span>View Profile</span>
                    </a> 
                </li> -->
                 
               
                <li class="treeview">
                    <a href="<?php echo base_url();?>iibfdra/Version_2/InspectorHome/batches">
                        <i class="fa fa-book"></i><span>Batch List</span>
                    </a> 
                </li>

                <li class="treeview">
                    <a href="<?php echo base_url();?>iibfdra/Version_2/InspectorHome/inspection_report">
                        <i class="fa fa-book"></i><span>Add Inspection Report</span>
                    </a> 
                </li>

                <li class="treeview">
                    <a href="<?php echo base_url();?>iibfdra/Version_2/InspectorHome/batch_inspection_report">
                        <i class="fa fa-book"></i><span>Inspection Report</span>
                    </a> 
                </li>
                <li class="treeview">
                    <a href="<?php echo base_url();?>iibfdra/Version_2/InspectorHome/change_password">
                        <i class="fa fa-book"></i><span>Change Password</span>
                    </a> 
                </li>

                <li class="treeview">
                    <a href="<?php echo  base_url()?>iibfdra/Version_2/InspectorLogin/logout">
                        <i class="fa fa-book"></i> <span>Logout</span>
                    </a>
                </li>
            </ul>
        </section>
    <!-- /.sidebar -->
    </aside>
<?php }
?>