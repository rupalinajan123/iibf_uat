<?php $careeradminuserdata = $this->session->userdata('career_admin');
	  $data = $this->session->userdata('sessionData'); ?>

<aside class="main-sidebar">
	<section class="sidebar">
		<ul class="sidebar-menu" style="margin:0;">
		<?php if ($data['user_type'] =="vendor") {?>
				
			<li class="treeview <?php if(stristr(current_url(),'scribe_list')) { echo "active"; } ?>">
				<a href="<?php echo site_url('scribe_dashboard/scribe_list/SearchQry'); ?>">
					<i class="fa fa-search"></i> <span>Search</span>
				</a>
			</li>			
			<?php }else{?>
			<li class="treeview <?php if(stristr(current_url(),'scribe_list')) { echo "active"; } ?>">
				<a href="<?php echo site_url('scribe_dashboard/scribe_list'); ?>">
					<i class="fa fa-dashboard"></i><span>Home</span>
				</a>
			</li>

			<li class="treeview <?php if(stristr(current_url(),'scribe_list')) { echo "active"; } ?>">
				<a href="<?php echo site_url('scribe_dashboard/scribe_list/SearchQry'); ?>">
					<i class="fa fa-search"></i> <span>Search</span>
				</a>
			</li>

			<li class="treeview <?php if(stristr(current_url(),'scribe_list')) { echo "active"; } ?>">
				<a href="<?php echo site_url('scribe_dashboard/scribe_list/scribe'); ?>">
					<i class="fa fa-th"></i> <span>Scribe list</span>
				</a>
			</li>
			<li class="treeview <?php if(stristr(current_url(),'scribe_list')) { echo "active"; } ?>">
				<a href="<?php echo site_url('scribe_dashboard/scribe_list/approved_list'); ?>">
					<i class="fa fa-th-large"></i> <span>Scribe Approved list</span>
				</a>
			</li>
			<li class="treeview <?php if(stristr(current_url(),'scribe_list')) { echo "active"; } ?>">
				<a href="<?php echo site_url('scribe_dashboard/scribe_list/rejected_list'); ?>">
					<i class="fa fa-th-large"></i> <span>Scribe Rejected list</span>
				</a>
			</li>
			<li class="treeview <?php if(stristr(current_url(),'scribe_list')) { echo "active"; } ?>">
				<a href="<?php echo site_url('scribe_dashboard/scribe_list/special'); ?>">
					<i class="fa fa-th"></i> <span>Special Assistance List</span>
				</a>
			</li>
			<li class="treeview <?php if(stristr(current_url(),'scribe_list')) { echo "active"; } ?>">
				<a href="<?php echo site_url('scribe_dashboard/scribe_list/special_approved_list'); ?>">
					<i class="fa fa-th-large"></i> <span>Special Assistance Approved list</span>
				</a>
			</li>
			<li class="treeview <?php if(stristr(current_url(),'scribe_list')) { echo "active"; } ?>">
				<a href="<?php echo site_url('scribe_dashboard/scribe_list/special_rejected_list'); ?>">
					<i class="fa fa-th-large"></i> <span>Special Assistance Rejected list</span>
				</a>
			</li>
			
			<?php }?>
			
			<br>
		</ul>
		
	</section>
</aside>

