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
         <a href="<?php echo base_url();?>admin/kyc/Kycsuperuser/dashboard">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
        
        <?php 
			$userRole = $this->session->userdata('roleid');
		
			if($userRole == 4 || $userRole == 5 || $userRole == 6){
		?>
         <?php 
			$home_active = '';
						
			if(stristr(current_url(),'Kycsuperuser/allocation_type') || stristr(current_url(),'Kycsuperuser/approver_edited_member') || stristr(current_url(),'Kycsuperuser/approver_edited_list'))
				$home_active = "active";
			else if (stristr(current_url(),'Kycsuperuser/kyccomplete_newlist') || stristr(current_url(),'Kycsuperuser/completed_details'))
				$home_active = "active";
			else if (stristr(current_url(),'Kycsuperuser/recommended_list'))
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
	<li <?php if(stristr(current_url(),'Kycsuperuser/allocation_type') || stristr(current_url(),'Kycsuperuser/approver_edited_member') || stristr(current_url(),'Kycsuperuser/approver_edited_list') ) { echo 'class="active"'; } ?>>
    <?php 
	$new_allocated_member_list= $this->master_model->getRecords("admin_kyc_users ",array('DATE(date)'=>date('Y-m-d'),'user_id'=>$this->session->userdata('kyc_id'),'list_type'=>'New','allotted_member_id'=>'' ));
	//echo $this->db->last_query();exit;
	if(count($new_allocated_member_list) >0 )
	{
		if($new_allocated_member_list[0]['allotted_member_id']=='')
		{?>
        	<a href="<?php echo base_url();?>admin/kyc/Kycsuperuser/allocation_type"><i class="fa fa-circle-o"></i>KYC member list</a>
<?php }
		else
		{?>
				<a href="<?php echo base_url();?>admin/kyc/Kycsuperuser/allocation_type"><i class="fa fa-circle-o"></i>KYC member list</a>
		<?php 
		}
	}
	else
	{?>
		<a href="<?php echo base_url();?>admin/kyc/Kycsuperuser/allocation_type"><i class="fa fa-circle-o"></i>KYC member list</a>
	<?php 
	}
	?> 
    </li>
		<li <?php if(stristr(current_url(),'Kycsuperuser/kyccomplete_newlist') || stristr(current_url(),'Kycsuperuser/completed_details')) { echo 'class="active"'; } ?>>
        
        <a href="<?php echo base_url();?>admin/kyc/Kycsuperuser/kyccomplete_newlist"><i class="fa fa-circle-o"></i> KYC complete member list</a>
        
        </li>
	
    <li <?php if(stristr(current_url(),'Kycsuperuser/recommended_list')) { echo 'class="active"'; } ?>>
	<a href="<?php echo base_url();?>admin/kyc/Kycsuperuser/recommended_list"><i class="fa fa-circle-o"></i>Recommended member list</a></li>
</ul>
</li>
  
        <li><a href="<?php echo base_url();?>admin/kyc/Login/Logout"><i class="fa fa-circle-o"></i>Logout</a></li>
        </li>
       
            
         

       
		<?php } ?>
        
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

