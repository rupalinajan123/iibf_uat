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
         <a href="<?php echo base_url();?>admin/finquest/">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            
          </a>
          </li>
          
            <li class="treeview">
       
                <li <?php if(stristr(current_url(),'admin/finquest/Finquest/asondate') || stristr(current_url(),'admin/finquest/Finquest/')) { echo 'class="active"'; } ?>>
               			<a href="<?php echo base_url();?>admin/finquest/Finquest/asondate"><i class="fa fa-circle-o"></i> KYC  Counts </a>
                 	
                  </li>
       </li>
       <li class="treeview">
          
                <li <?php if(stristr(current_url(),'admin/finquest/Finquest/dupid_member_list') || stristr(current_url(),'admin/finquest/Finquest/')) { echo 'class="active"'; } ?>>
               			<a href="<?php echo base_url();?>admin/finquest/Finquest/dupid_member_list"><i class="fa fa-circle-o"></i> Duplicate Id-card member list</a>
                 	
                  </li>
       </li> 
          <li class="treeview">
          
                <li <?php if(stristr(current_url(),'admin/finquest/Finquest/member_list') || stristr(current_url(),'admin/finquest/Finquest/')) { echo 'class="active"'; } ?>>
               			<a href="<?php echo base_url();?>admin/finquest/Finquest/member_list"><i class="fa fa-circle-o"></i> Fin@quest member list</a>
                 	
                  </li>
       </li>  
         <li class="treeview">
          
                <li <?php if(stristr(current_url(),'admin/finquest/Finquest/cc_member_list') || stristr(current_url(),'admin/finquest/Finquest/')) { echo 'class="active"'; } ?>>
               			<a href="<?php echo base_url();?>admin/finquest/Finquest/cc_member_list"><i class="fa fa-circle-o"></i> Contact classes member list</a>
                 	
                  </li>
       </li>   
       <li class="treeview">
          
                <li <?php if(stristr(current_url(),'admin/finquest/Finquest/cc_subject_list') || stristr(current_url(),'admin/finquest/Finquest/')) { echo 'class="active"'; } ?>>
               			<a href="<?php echo base_url();?>admin/finquest/Finquest/cc_subject_list"><i class="fa fa-circle-o"></i> CC subject wise counts</a>
                 	
                  </li>
       </li>  
          <li class="treeview">
          
                <li <?php if(stristr(current_url(),'admin/finquest/Finquest/prize_winner_list') || stristr(current_url(),'admin/finquest/Finquest/')) { echo 'class="active"'; } ?>>
               			<a href="<?php echo base_url();?>admin/finquest/Finquest/prize_winner_list"><i class="fa fa-circle-o"></i> Examination Prize winners 2017-18</a>
                 	
                  </li>
       </li>    
         
      
        <?php 
			$userRole = $this->session->userdata('roleid');
		
			if($userRole == 4 || $userRole == 5){
		?>
            <?php 
			
			
			$home_active = '';
			/*if(stristr(current_url(),'Kyc/recommended_list'))
				$home_active = "active";
			else if(stristr(current_url(),'Kyc/allocated_list'))
				$home_active = "active";
			else if(stristr(current_url(),'Kyc/edited_list'))
				$home_active = "active";*/
				
			if(strpos(current_url(),base_url().'admin/kyc/Kyc/recommender_recommend_list') !== false || strpos(current_url(),base_url().'admin/kyc/Kyc/recommended_list') !== false)
				$home_active = "active";
				else	if(strpos(current_url(),base_url().'admin/kyc/Kyc/allocation_type') !== false || strpos(current_url(),base_url().'admin/kyc/Kyc/member') !== false  || strpos(current_url(),base_url().'admin/kyc/Kyc/allocated_list')  !== false || strpos(current_url(),base_url().'admin/kyc/Kyc/next_allocated_list') !== false || strpos(current_url(),base_url().'admin/kyc/Kyc/next_allocation_type') !== false)
				$home_active = "active";
				else	if(strpos(current_url(),base_url().'admin/kyc/Kyc/edited_allocation_type') !== false || strpos(current_url(),base_url().'admin/kyc/Kyc/edited_member') !== false  || strpos(current_url(),base_url().'admin/kyc/Kyc/next_edited_allocation_type') !== false || strpos(current_url(),base_url().'admin/kyc/Kyc/edited_list') !== false || strpos(current_url(),base_url().'admin/kyc/Kyc/next_edited_list') !== false)
				$home_active = "active";
	    ?>
        
 <li class="treeview <?php echo $home_active; ?>">
      <a href="#">
            <i class="fa fa-home"></i> <span>Members</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          
     <ul class="treeview-menu">
            <li <?php if(stristr(current_url(),'Kyc/allocation_type') || stristr(current_url(),'Kyc/member') || stristr(current_url(),'Kyc/allocated_list'  || stristr(current_url(),'Kyc/next_allocated_list') || stristr(current_url(),'Kyc/next_allocation_type'))) { echo 'class="active"'; } ?>>
            <?php
			$new_allocated_member_list= $this->master_model->getRecords("admin_kyc_users ",array('DATE(date)'=>date('Y-m-d'),'user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'New','allotted_member_id'=>'' ));
			
			if(count($new_allocated_member_list) >0 )
			{
				if($new_allocated_member_list[0]['allotted_member_id']=='')
				{?>
						<a href="<?php echo base_url();?>admin/kyc/Kyc/next_allocation_type"><i class="fa fa-circle-o"></i>New member list</a>
				<?php 
				}
				else
				{?>
						<a href="<?php echo base_url();?>admin/kyc/Kyc/allocation_type"><i class="fa fa-circle-o"></i>New member list</a>
				<?php 
				}
			}
			else
			{?>
				<a href="<?php echo base_url();?>admin/kyc/Kyc/allocation_type"><i class="fa fa-circle-o"></i>New member list</a>
			<?php 
			}
            ?>
            
            
            </li>
                  <li <?php if(stristr(current_url(),'Kyc/edited_allocation_type') || stristr(current_url(),'Kyc/edited_member')|| stristr(current_url(),'Kyc/edited_list') || stristr(current_url(),'Kyc/next_edited_list') || stristr(current_url(),'Kyc/next_edited_allocation_type')) { echo 'class="active"'; } ?>>
                 <?php 
				  $edit_allocated_member_list= $this->master_model->getRecords("admin_kyc_users ",array('DATE(date)'=>date('Y-m-d'),'user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'Edit','allotted_member_id'=>'' ));				
				  if(count($edit_allocated_member_list) > 0)
				  {
					  if($edit_allocated_member_list[0]['allotted_member_id']=='')
					  {?>
                  			<a href="<?php echo base_url();?>admin/kyc/Kyc/next_edited_allocation_type"><i class="fa fa-circle-o"></i> Edited member list</a>
                 	 <?php
					  }
						else
						{?>
								<a href="<?php echo base_url();?>admin/kyc/Kyc/edited_allocation_type"><i class="fa fa-circle-o"></i> Edited member list</a>	
						<?php 
                       }  
				  }else
				  {?>
					 		<a href="<?php echo base_url();?>admin/kyc/Kyc/edited_allocation_type"><i class="fa fa-circle-o"></i> Edited member list</a>	
					<?php 
					}?>
                  </li>
                  
                   <li <?php if(stristr(current_url(),'Kyc/recommender_recommend_list') || stristr(current_url(),'Kyc/recommended_list')) { echo 'class="active"'; } ?>>
                   
                   <a href="<?php echo base_url();?>admin/kyc/Kyc/recommended_list"><i class="fa fa-circle-o"></i>Recommended member list</a></li>

      
        </ul>
        </li>
       
       
        <?php 
			$dra_home_active = '';
			/*if(stristr(current_url(),'Dra_kyc/recommended_list'))
				$dra_home_active = "active";
			else if(stristr(current_url(),'Dra_kyc/allocated_list'))
				$dra_home_active = "active";
			else if(stristr(current_url(),'Dra_kyc/edited_list'))
				$dra_home_active = "active";*/
				
				
				if(strpos(current_url(),base_url().'admin/kyc/Dra_kyc/recommender_recommend_list') !== false)
				$dra_home_active = "active";
				else	if(strpos(current_url(),base_url().'admin/kyc/Dra_kyc/allocation_type') !== false)
				$dra_home_active = "active";
				else	if(strpos(current_url(),base_url().'admin/kyc/Dra_kyc/edited_allocation_type') !== false)
				$dra_home_active = "active";
				
	    ?>
        
 <!--<li class="treeview <?php echo $dra_home_active; ?>">
      <a href="#">
            <i class="fa fa-home"></i> <span>DRA Members</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          
     <ul class="treeview-menu">
            <li <?php if(stristr(current_url(),'Dra_kyc/allocated_list')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/kyc/Dra_kyc/allocated_list"><i class="fa fa-circle-o"></i>New Members List</a></li>
                  <li <?php if(stristr(current_url(),'Dra_kyc/edited_list')) { echo 'class="active"'; } ?>><a href="<?php echo base_url();?>admin/kyc/Dra_kyc/edited_list"><i class="fa fa-circle-o"></i> Edited Members List</a></li>
                   <li <?php if(stristr(current_url(),'Dra_kyc/recommended_list')) { echo 'class="active"'; } ?>>
                   
                   <a href="<?php echo base_url();?>admin/kyc/Dra_kyc/recommended_list"><i class="fa fa-circle-o"></i>Recommended Members List</a></li>

      
        </ul>
        </li>-->
       
		<?php } ?>
       
       
       
        <li><a href="<?php echo base_url();?>admin/kyc/Login/Logout"><i class="fa fa-circle-o"></i>Logout</a></li>  
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
