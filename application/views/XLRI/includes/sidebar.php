<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left">
         <p><?php /*if($this->session->userdata('username')!=''){ echo $this->session->userdata('username'); }*/ ?></p>
        </div>
       
      </div>
      <br/>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
       <!-- <li class="header">MAIN NAVIGATION</li>-->
        <li class="treeview <?php if(current_url() == base_url().'amp_dashboard') { echo "active"; } ?>">
         <a href="<?php echo base_url();?>amp_self">
            <i class="fa fa-dashboard"></i> <span>self</span>
          </a>
        </li>
        
      
       
        
        <li class="treeview <?php if(current_url() == base_url().'amp_dashboard/bank') { echo "active"; } ?>">
         <a href="<?php echo base_url();?>amp_bank">
            <i class="fa fa-dashboard"></i> <span>bank</span>
          </a>
        </li>
        
       
        
        
        
         
        
        
           
       
      
      
        
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
