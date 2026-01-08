<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left">
                <p></p>
            </div>
            <div class="pull-left info">
                <p>JBIMS</p>
            </div>
        </div>
        <br />
        <!-- sidebar menu: : style can be found in sidebar.less -->

        <ul class="sidebar-menu">
        
     
            <li class="header" style="color:#2ea0e2">My Home</li>
            
            <!--<li class="treeview <?php if(stristr(current_url(),'MainController')) { echo "active"; } ?>">-->
            <!--    <a href="<?php echo base_url();?>JBIMS_dashboard/MainController">-->
            <!--        <i class="fa fa-home"></i> <span>Home</span>-->
            <!--    </a>-->
            <!--</li>-->
           
             <!-- slef sponsored listing -->
             <li class="treeview <?php if(stristr(current_url(),'self')) { echo "active"; } ?>">
                <a href="<?php echo base_url();?>JBIMS_dashboard/self/">
                    <i class="fa fa-credit-card"></i> <span>Self Sponsored</span>
                </a>
            </li>
             
            
            <!-- bank sponsored-Unpaid listing -->
             <li class="treeview <?php if(stristr(current_url(),'bank_unpaid')) { echo "active"; } ?>">
                <a href="<?php echo base_url();?>JBIMS_dashboard/bank_unpaid/">
                    <i class="fa fa-credit-card"></i> <span>Bank Sponsored</span>
                </a>
            </li>
            
             <!-- bank sponsored-Unpaid listing -->
             <li class="treeview <?php if(stristr(current_url(),'Report')) { echo "active"; } ?>">
                <a href="<?php echo base_url();?>JBIMS_dashboard/Report/">
                    <i class="fa fa-credit-card"></i> <span>CSV Report</span>
                </a>
            </li>
            
            
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>