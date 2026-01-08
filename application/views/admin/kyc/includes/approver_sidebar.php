<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left">
        <p><?php /*if($this->session->userdata('username')!=''){ echo $this->session->userdata('username'); }*/ ?></p>
      </div>
      <div class="pull-left info">
        <p><?php if($this->session->userdata('username')!=''){ echo "Welcome ".$this->session->userdata('username'); } ?></p>
        <a href="javascript:void(0);"><?php if($this->session->userdata('role')!=''){ echo '('.$this->session->userdata('role').')'; } ?></a>
        <!--<a href="#"><i class="fa fa-circle text-success"></i> Online</a>-->
      </div>
    </div>
    <br />
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu">
      <!-- <li class="header">MAIN NAVIGATION</li>-->
      <li class="treeview">
            <a href="
               <?php echo base_url();?>admin/kyc/Approver/dashboard">
            <i class="fa fa-dashboard"></i>
            <span>Dashboard</span>
            </a>
         </li>
         <li class="treeview">
            <a href="<?php echo base_url();?>admin/kyc/Approver/pending_member_list">
            <i class="fa fa-list-alt"></i> <span>Pending Member List</span>
            </a>
         </li>
         <?php
               if(strpos(current_url(),base_url().'admin/kyc/Approver/pending_allocation_type') !== false || 
                  strpos(current_url(),base_url().'admin/kyc/Approver/pending_member') !== false  || 
                  strpos(current_url(),base_url().'admin/kyc/Approver/pending_allocated_list')  !== false || 
                  strpos(current_url(),base_url().'admin/kyc/Approver/next_pending_allocated_list') !== false || 
                  strpos(current_url(),base_url().'admin/kyc/Approver/next_pending_allocation_type') !== false)
               $home_active = "active";
         ?>
         <li class="treeview">
            <a href="<?php echo base_url();?>admin/kyc/Approver/pending_allocation_type">
            <i class="fa fa-circle-o"></i> <span>Allocate Pending members</span>
        </a>
      </li>
      
      <?php 
        $userRole = $this->session->userdata('roleid');
        
        if($userRole == 4 || $userRole == 5){
            ?> <?php 
          $home_active = '';
          
            if(stristr(current_url(),'Approver/allocation_type') || stristr(current_url(),'Approver/approver_allocated_member')|| stristr(current_url(),'Approver/approver_allocated_list'))
            	$home_active = "active";
            else if(stristr(current_url(),'Approver/edited_allocation_type') || stristr(current_url(),'Approver/approver_edited_member') ||  stristr(current_url(),'Approver/approver_edited_list'))
          $home_active = "active";
          else if (stristr(current_url(),'Approver/kyccomplete_newlist') || stristr(current_url(),'Approver/completed_details'))
          $home_active = "active";
          else if (stristr(current_url(),'Approver/recommended_list'))
          $home_active = "active";
               else if (stristr(current_url(),'Approver/next_edited_allocation_type'))
            	$home_active = "active";
        ?>
         <li class="treeview 
            <?php echo $home_active; ?>">
          <a href="#">
            <i class="fa fa-home"></i>
            <span>Members</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
            <ul class="treeview-menu">
               <li <?php if(stristr(current_url(),'Approver/allocation_type') || 
                           stristr(current_url(),'Approver/approver_allocated_member') ||
                           stristr(current_url(),'Approver/approver_next_allocated_list') ||  
                           stristr(current_url(),'Approver/approver_allocated_list') ) 
                        { echo 'class="active"'; } ?>> 
          
              <?php 
                $new_allocated_member_list= $this->master_model->getRecords("admin_kyc_users ",array('DATE(date)'=>date('Y-m-d'),'user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'New','allotted_member_id'=>'' ));
                //echo $this->db->last_query();exit;
                if(count($new_allocated_member_list) >0 )
                {
                  if($new_allocated_member_list[0]['allotted_member_id']=='')
                  {?>
                              <a href="<?php echo base_url();?>admin/kyc/Approver/next_allocation_type">
                              <i class="fa fa-circle-o"></i>KYC member list </a> <?php 
                           }
                           else
                           {?> <a href="
                              <?php echo base_url();?>admin/kyc/Approver/approver_allocated_list">
                              <i class="fa fa-circle-o"></i>KYC member list </a> <?php 
                           }
                        }
                  else
                  {?>
                           <a href="<?php echo base_url();?>admin/kyc/Approver/allocation_type">
                           <i class="fa fa-circle-o"></i>KYC member list </a> <?php 
                        }
                        ?> 
               </li>
              <!-- edited member tab added by pooja mane : 10-10-2023 -->
              <li <?php if(stristr(current_url(),'Approver/edited_allocation_type') || 
                          stristr(current_url(),'Approver/approver_edited_member')|| 
                          stristr(current_url(),'Approver/next_edited_allocation_type')|| 
                          stristr(current_url(),'Approver/approver_edited_list')) 
                          { echo 'class="active"'; } ?>>
                        <?php 
                        $edit_allocated_member_list= $this->master_model->getRecords("admin_kyc_users ",array('DATE(date)'=>date('Y-m-d'),'user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'Edit','allotted_member_id'=>'' ));				
                        if(count($edit_allocated_member_list) > 0)
                        {
                           if($edit_allocated_member_list[0]['allotted_member_id']=='')
                           {?>
                              <a href="<?php echo base_url();?>admin/kyc/Approver/next_edited_allocation_type"><i class="fa fa-circle-o"></i> Edited member list</a>
                  <?php 
                  }
                else
                {?>
                              <a href="<?php echo base_url();?>admin/kyc/Approver/approver_edited_list"><i class="fa fa-circle-o"></i> Edited member list</a>	
                <?php 
                }
                        }else
                        {?>
                           <a href="<?php echo base_url();?>admin/kyc/Approver/edited_allocation_type"><i class="fa fa-circle-o"></i> Edited member list</a>	
                           <?php 
                        }?>
            </li>
              <!-- edited member tab added by pooja mane : 10-10-2023 -->
            <li <?php if(stristr(current_url(),'Approver/kyccomplete_newlist') || stristr(current_url(),'Approver/completed_details')) { echo 'class="active"'; } ?>>
                  <a href="
                     <?php echo base_url();?>admin/kyc/Approver/kyccomplete_newlist">
                  <i class="fa fa-circle-o"></i> KYC complete member list </a>
            </li>
            
            <li <?php if(stristr(current_url(),'Approver/recommended_list')) { echo 'class="active"'; } ?>>
                  <a href="
                     <?php echo base_url();?>admin/kyc/Approver/recommended_list">
                  <i class="fa fa-circle-o"></i>Approver recommended <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;member list </a>
               </li>
          </ul>
        </li>
         <li>
            <a href="<?php echo base_url();?>admin/kyc/Approver/benchmark_allocation_type">
            <i class="fa fa-circle-o"></i>Benchmark Members </a>
         </li>
         <li>
            <a href="
               <?php echo base_url();?>admin/kyc/Approver/professional_banker_kyc">
            <i class="fa fa-circle-o"></i>Professional Banker KYC </a>
         </li>
         <!-- pooja mane:12-12-2022 : added scribe member list -->
         <li>
            <a href="
               <?php echo base_url();?>admin/kyc/Approver/scribe_allocation_type">
            <i class="fa fa-circle-o"></i>Scribe Members </a>
         </li>
        <!-- Pooja Mane : scribe list End  -->
         <li>
            <a href="
               <?php echo base_url();?>admin/kyc/Login/Logout">
            <i class="fa fa-circle-o"></i>Logout </a>
      </li>
         </li> <?php } ?>
  </ul>
</section>
<!-- /.sidebar -->
</aside>