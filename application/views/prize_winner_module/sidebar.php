<?php $careeradminuserdata = $this->session->userdata('career_admin');?>
<aside class="main-sidebar">
	<section class="sidebar">
		<ul class="sidebar-menu" style="margin:0;">			
			<li class="treeview <?php if(stristr(current_url(),'admin/GstB2BDashboard')) { echo "active"; } ?>">
				<a href="<?php echo site_url('admin/GstB2BDashboard'); ?>">
					<i class="fa fa-th-large"></i> <span>GST B2B Count</span>
				</a>
			</li>
			
			
			
		</ul>
		
	</section>
</aside>

