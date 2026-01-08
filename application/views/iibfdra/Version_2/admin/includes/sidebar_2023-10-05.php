<?php $drauserdata = $this->session->userdata('dra_admin');?>
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
                <p><?php if($drauserdata['username']!=''){ echo $drauserdata['username']; } ?></p>
            </div>
        </div>
        <br />
        <!-- sidebar menu: : style can be found in sidebar.less -->

        <ul class="sidebar-menu">
        <?php if($drauserdata['roleid']!='' && $drauserdata['roleid']==3){ ?>
             <li class="header" style="color:#2ea0e2">My Home</li>
            
            <li class="treeview <?php if(stristr(current_url(),'MainController')) { echo "active"; } ?>">
                <a href="<?php echo base_url();?>iibfdra/Version_2/admin/MainController">
                    <i class="fa fa-home"></i> <span>Home</span>
                </a>
            </li>

             <li class="treeview <?php if(stristr(current_url(),'batch')) { echo "active"; } ?>">
                <a href="<?php echo base_url();?>iibfdra/Version_2/batch/">
                    <i class="fa fa-credit-card"></i> <span>Training Batches</span>
                </a>
            </li>
            
            <li class="treeview <?php if(stristr(current_url(),'candidates_list')) { echo "active"; } ?>">
                <a href="<?php echo base_url();?>iibfdra/Version_2/batch/candidates_list/">
                    <i class="fa fa-credit-card"></i> <span>All Candidates</span>
                </a>
            </li>


        <?php }else if($drauserdata['roleid']!='' && $drauserdata['roleid']==4){ ?>

            <li class="header" style="color:#2ea0e2">My Home</li>
            
            <li class="treeview <?php if(stristr(current_url(),'MainController')) { echo "active"; } ?>">
                <a href="<?php echo base_url();?>iibfdra/Version_2/admin/MainController">
                    <i class="fa fa-home"></i> <span>Home</span>
                </a>
            </li>

             <li class="treeview <?php if(stristr(current_url(),'batch')) { echo "active"; } ?>">
                <a href="<?php echo base_url();?>iibfdra/Version_2/batch/">
                    <i class="fa fa-credit-card"></i> <span>Training Batches</span>
                </a>
            </li> 
            
            <li class="treeview <?php if(stristr(current_url(),'candidates_list')) { echo "active"; } ?>">
                <a href="<?php echo base_url();?>iibfdra/batch/candidates_list/">
                    <i class="fa fa-credit-card"></i> <span>All Candidates</span>
                </a>
            </li>

            <li class="treeview <?php if(stristr(current_url(),'InspectorMaster')) { echo "active"; } ?>">
                <a href="<?php echo base_url();?>iibfdra/Version_2/admin/InspectorMaster"><i class="fa fa-circle-o"></i> 
                   <span>Inspector Master</span>
                </a>
            </li>   


        <?php } else{ ?>
     
            <li class="header" style="color:#2ea0e2">My Home</li>
            
            <li class="treeview <?php if(stristr(current_url(),'MainController')) { echo "active"; } ?>">
                <a href="<?php echo base_url();?>iibfdra/Version_2/admin/MainController">
                    <i class="fa fa-home"></i> <span>Home</span>
                </a>
            </li>
            <li class="treeview <?php if(stristr(current_url(),'transaction/transactions')) { echo "active"; } ?>">
                <a href="<?php echo base_url();?>iibfdra/Version_2/admin/transaction/transactions">
                    <i class="fa fa-credit-card"></i> <span>Transactions</span>
                </a>
            </li>

            <!-- Added by Priyanka on 27-10-2022 -->
            <li class="treeview <?php if(stristr(current_url(),'faculty')) { echo "active"; } ?>">
                <a href="<?php echo base_url();?>iibfdra/Version_2/admin/faculty_master">
                    <i class="fa fa-book"></i><span>Faculty Master</span>
                </a> 
            </li>

            <!-- Agency Listing added by Manoj -->
             <li class="treeview <?php if(stristr(current_url(),'agency')) { echo "active"; } ?>">
                <a href="<?php echo base_url();?>iibfdra/Version_2/agency/">
                    <i class="fa fa-credit-card"></i> <span>Agency List</span>
                </a>
            </li>
             <!-- Batch Listing added by Manoj -->
             <li class="treeview <?php if(end($this->uri->segment_array()) == 'batch') { echo "active"; } ?>">
                <a href="<?php echo base_url();?>iibfdra/Version_2/batch/">
                    <i class="fa fa-credit-card"></i> <span>Training Batches</span>
                </a>
            </li>            
            <li class="treeview <?php if(stristr(current_url(),'candidates_list')) { echo "active"; } ?>">
                <a href="<?php echo base_url();?>iibfdra/Version_2/batch/candidates_list/">
                    <i class="fa fa-credit-card"></i> <span>All Candidates</span>
                </a>
            </li>

            <li class="treeview <?php if(stristr(current_url(),'inspection_summary')) { echo "active"; } ?>">
                <a href="<?php echo base_url();?>iibfdra/Version_2/admin/InspectionSummary">
                    <i class="fa fa-credit-card"></i> <span>Inspection Summary</span>
                </a>
            </li>

            <li class="treeview <?php if(end($this->uri->segment_array()) == 'BatchMIS') { echo "active"; } ?>">
                <a href="<?php echo base_url();?>iibfdra/Version_2/admin/BatchMIS">
                    <i class="fa fa-credit-card"></i> <span>Batch MIS</span>
                </a>
            </li>

            
             <?php
				if(stristr(current_url(),'ExamMaster'))
					$is_active = "active";
				else if(stristr(current_url(),'MiscMaster'))
					$is_active = "active";
				else if(stristr(current_url(),'SubjectMaster'))
					$is_active = "active";
				else if(stristr(current_url(),'CenterMaster'))
					$is_active = "active";
				else if(stristr(current_url(),'MediumMaster'))
					$is_active = "active";
				else if(stristr(current_url(),'FeeMaster'))
					$is_active = "active";
				else if(stristr(current_url(),'InstitutionMaster'))
					$is_active = "active";
				else if(stristr(current_url(),'ExamActiveMaster'))
					$is_active = "active";
				else if(stristr(current_url(),'EligibleMaster'))
					$is_active = "active";
                else if(stristr(current_url(),'InspectorMaster'))
                    $is_active = "active";
				else
					$is_active = "";
			?>
			<li class="treeview <?php echo $is_active; ?>">
			  <a href="#">
				<i class="fa fa-database"></i> <span>Masters</span>
				<span class="pull-right-container">
				  <i class="fa fa-angle-left pull-right"></i>
				</span>
			  </a>
			  <ul class="treeview-menu">
				<li <?php if(stristr(current_url(),'ExamMaster')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>iibfdra/Version_2/admin/ExamMaster"><i class="fa fa-circle-o"></i> Exam Master</a></li>
				<li <?php if(stristr(current_url(),'MiscMaster')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>iibfdra/Version_2/admin/MiscMaster"><i class="fa fa-circle-o"></i> Misc Master</a></li>
				<li <?php if(stristr(current_url(),'CenterMaster')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>iibfdra/Version_2/admin/CenterMaster"><i class="fa fa-circle-o"></i> Center Master</a></li>
				<li <?php if(stristr(current_url(),'MediumMaster')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>iibfdra/Version_2/admin/MediumMaster"><i class="fa fa-circle-o"></i> Medium Master</a></li>
				<li <?php if(stristr(current_url(),'FeeMaster')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>iibfdra/Version_2/admin/FeeMaster"><i class="fa fa-circle-o"></i> Exam Fee Master</a></li>
				<li <?php if(stristr(current_url(),'InstitutionMaster')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>iibfdra/Version_2/admin/InstitutionMaster"><i class="fa fa-circle-o"></i> Institution Master</a></li>
				<li <?php if(stristr(current_url(),'ExamActiveMaster')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>iibfdra/Version_2/admin/ExamActiveMaster"><i class="fa fa-circle-o"></i> Exam Activation Master</a></li>
				<li <?php if(stristr(current_url(),'EligibleMaster')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>iibfdra/Version_2/admin/EligibleMaster"><i class="fa fa-circle-o"></i> Eligible Master</a></li>
                 <li <?php if(stristr(current_url(),'InspectorMaster')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>iibfdra/Version_2/admin/InspectorMaster"><i class="fa fa-circle-o"></i> Inspector Master</a></li>
			  </ul>
			</li>
            <?php ?>

            <li class="treeview <?php if(stristr(current_url(),'transaction/neft_transactions')) { echo "active"; } ?>">
                <a href="<?php echo base_url();?>iibfdra/Version_2/admin/transaction/neft_transactions">
                    <i class="fa fa-check-square-o"></i> <span>Approve NEFT Transactions</span>
                </a>
            </li>

			
            <li class="treeview <?php if(stristr(current_url(),'login/changepassword')) { echo "active"; } ?>">
                <a href="<?php echo base_url(); ?>iibfdra/Version_2/admin/login/changepassword">
                    <i class="fa fa-key"></i> <span>Change Password</span>
                </a>
            </li>
            
   
            <li class="header" style="color:#2ea0e2">Reports</li>
              <li class="treeview <?php if(stristr(current_url(),'report/Quarterly_Report')) { echo "active"; } ?>">
                <a href="<?php echo base_url(); ?>iibfdra/Version_2/admin/report/Quarterly_Report">
                    <i class="fa fa-check"></i> <span>Quarterly Report</span>
                </a>
            </li>
            <li class="treeview <?php if(stristr(current_url(),'report/billdesk_success')) { echo "active"; } ?>">
                <a href="<?php echo base_url(); ?>iibfdra/Version_2/admin/report/billdesk_success">
                    <i class="fa fa-check"></i> <span>Success BillDesk</span>
                </a>
            </li>
            <li class="treeview <?php if(stristr(current_url(),'report/billdesk_failure')) { echo "active"; } ?>">
                <a href="<?php echo base_url(); ?>iibfdra/Version_2/admin/report/billdesk_failure">
                    <i class="fa fa-close"></i> <span>Failure BillDesk</span>
                </a>
            </li>
            <li class="treeview <?php if(stristr(current_url(),'report/failure_reason')) { echo "active"; } ?>">
                <a href="<?php echo base_url(); ?>iibfdra/Version_2/admin/report/failure_reason">
                    <i class="fa fa-sort-amount-desc"></i> <span>Failure Reason</span>
                </a>
            </li>
            <li class="treeview <?php if(stristr(current_url(),'report/billdesk_neft_report')) { echo "active"; } ?>">
                <a href="<?php echo base_url(); ?>iibfdra/Version_2/admin/report/billdesk_neft_report">
                    <i class="fa fa-book"></i> <span>NEFT BillDesk Report</span>
                </a>
            </li>
            <li class="treeview <?php if(stristr(current_url(),'dashboard')) { echo "active"; } ?>">
                <a href="<?php echo base_url(); ?>iibfdra/Version_2/admin/dashboard">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
           
              <?php   }  ?>
        

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>