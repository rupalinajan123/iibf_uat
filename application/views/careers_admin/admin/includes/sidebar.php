<?php $careeradminuserdata = $this->session->userdata('career_admin');?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left">
                <p><?php //if($careeradminuserdata['username']!=''){ echo $careeradminuserdata['username']; } ?></p>
            </div>
            <div class="pull-left info">
                <p><?php if($careeradminuserdata['name']!=''){ echo $careeradminuserdata['name']; } ?></p>
            </div>
        </div>
        <br />
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
        
            <li class="header" style="color:#2ea0e2">List</li>
            
            <li class="treeview <?php if(stristr(current_url(),'MainController')) { echo "active"; } ?>">
                <a href="<?php echo base_url();?>careers_admin/admin/Career_admin/career_admin_list">
                    <i class="fa fa-home"></i> <span>Candidate List</span>
                </a>
            </li>

            <li class="treeview <?php if(stristr(current_url(),'MainController')) { echo "active"; } ?>">
                <a href="<?php echo base_url();?>careers_admin/admin/Careers_position/career_position_list">
                    <i class="fa fa-file"></i> <span>Summary Report</span>
                </a>
            </li>
            <!-- <?php if($careeradminuserdata['admin_user_type'] == 'Maker'){ ?>

                 <li class="treeview <?php if(stristr(current_url(),'refundrequest/rerundRequest')) { echo "active"; } ?>">
                   <a href="<?php echo base_url();?>creditnote/refundrequest/refundRequest">
                      <i class="fa fa-credit-card"></i> <span>Refund Requests</span>
                   </a>
                 </li> -->

                <!--  <li class="treeview <?php if(stristr(current_url(),'Maker/refundrequest_list')) { echo "active"; } ?>">
                   <a href="<?php echo base_url();?>creditnote/admin/Maker/refundrequest_list">
                      <i class="fa fa-credit-card"></i> <span>Request List</span>
                   </a>
                 </li> -->

                 <!-- <li class="treeview <?php if(stristr(current_url(),'Maker/report')) { echo "active"; } ?>">
                   <a href="<?php echo base_url();?>creditnote/admin/Maker/report">
                      <i class="fa fa-credit-card"></i> <span>Report</span>
                   </a>
                 </li> -->

            <!-- <?php }elseif($creditnoteuserdata['admin_user_type'] == 'Checker'){ ?>
                  <li class="treeview <?php if(stristr(current_url(),'Checker/refundrequest_list')) { echo "active"; } ?>">
                   <a href="<?php echo base_url();?>creditnote/admin/Checker/refundrequest_list">
                      <i class="fa fa-credit-card"></i> <span>Request List</span>
                   </a>
                 </li> -->

                <!--  <li class="treeview <?php if(stristr(current_url(),'Checker/report')) { echo "active"; } ?>">
                   <a href="<?php echo base_url();?>creditnote/admin/Checker/report">
                      <i class="fa fa-credit-card"></i> <span>Report</span>
                   </a>
                 </li> -->
                
               
            <!-- <?php }elseif($creditnoteuserdata['admin_user_type'] == 'ESDSMaker'){ ?>
               <li class="treeview <?php if(stristr(current_url(),'refundrequest/rerundRequest')) { echo "active"; } ?>">
                   <a href="<?php echo base_url();?>creditnote/refundrequest/refundRequest">
                      <i class="fa fa-credit-card"></i> <span>Refund Requests</span>
                   </a>
                 </li> -->

                 <!-- <li class="treeview <?php if(stristr(current_url(),'Maker/refundrequest_list')) { echo "active"; } ?>">
                   <a href="<?php echo base_url();?>creditnote/admin/Maker/refundrequest_list">
                      <i class="fa fa-credit-card"></i> <span>Request List</span>
                   </a>
                 </li> -->

                  <!-- <li class="treeview <?php if(stristr(current_url(),'Maker/cancellation_list')) { echo "active"; } ?>">
                   <a href="<?php echo base_url();?>creditnote/admin/Maker/cancellation_list">
                      <i class="fa fa-credit-card"></i> <span>Cancellation List</span>
                   </a>
                 </li>
 -->
                  <!-- <li class="treeview <?php if(stristr(current_url(),'Maker/report')) { echo "active"; } ?>">
                   <a href="<?php echo base_url();?>creditnote/admin/Maker/report">
                      <i class="fa fa-credit-card"></i> <span>Report</span>
                   </a>
                 </li>
                 
            <?php } elseif($creditnoteuserdata['admin_user_type'] == 'Superadmin')   { ?>
 -->
                  

                 <!-- <li class="treeview <?php if(stristr(current_url(),'creditnote/refundrequest_list')) { echo "active"; } ?>">
                   <a href="<?php echo base_url();?>creditnote/admin/creditnote/refundrequest_list">
                      <i class="fa fa-credit-card"></i> <span>Request List</span>
                   </a>
                 </li> 

              <?php }  ?>-->

           
            <!-- <li class="treeview <?php if(stristr(current_url(),'login/changepassword')) { echo "active"; } ?>">
                   <a href="<?php echo base_url();?>careers_admin/admin/Login/changepassword">
                      <i class="fa fa-credit-card"></i> <span>Change Password</span>
                   </a>
                 </li> -->

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>