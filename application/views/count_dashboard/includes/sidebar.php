<?php
// echo "Hii";
// exit;
$careeradminuserdata = $this->session->userdata('career_admin');
$data = $this->session->userdata('sessionData'); ?>

<aside class="main-sidebar">
	<section class="sidebar">
		<ul class="sidebar-menu" style="margin:0;">
			<?php if ($data['user_type'] == "vendor") { ?>

				<li class="treeview <?php if (stristr(current_url(), 'count_list')) {
										echo "active";
									} ?>">
					<a href="<?php echo site_url('count_dashboard/Count_list/SearchQry'); ?>">
						<i class="fa fa-search"></i> <span>Search</span>
					</a>
				</li>
			<?php } else { ?>
				<li class="treeview <?php if (stristr(current_url(), 'count_list')) {
										echo "active";
									} ?>">
					<a href="<?php echo site_url('count_dashboard/Count_list'); ?>">
						<i class="fa fa-dashboard"></i><span>Home</span>
					</a>
				</li>

				<li class="treeview <?php if (stristr(current_url(), 'count_list')) {
										echo "active";
									} ?>">
					<a href="<?php echo site_url('count_dashboard/Count_list/count'); ?>">
						<i class="fa fa-th"></i> <span>User Count</span>
					</a>
				</li>

			<?php } ?>

			<br>
		</ul>

	</section>
</aside>