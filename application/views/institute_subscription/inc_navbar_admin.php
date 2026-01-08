<header class="main-header">
	<a href="<?php echo site_url('institute_subscription/admin_dashboard');?>" class="logo">IIBF</a>
	
	<nav class="navbar navbar-static-top">
		<a href="javascript:void(0)" class="sidebar-toggle" data-toggle="offcanvas" role="button">
			<span class="sr-only">IIBF</span> 
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</a>
		
		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav navlogo">
				<li><img src="<?php echo base_url();?>assets/images/iibf_logo_black.png"></li>
				
				<li class="dropdown user user-menu">
					<a href="<?php echo site_url('institute_subscription/admin_logout'); ?>" class="dropdown-toggle" >
						<span class="hidden-xs">Logout</span>
						<i class="fa fa-sign-out"></i>
					</a>
				</li>							
			</ul>
		</div>					
	</nav>
	<script>
		$(function(){
			$("body").on("contextmenu",function(e){
				return false;
			});
		});
	</script>
</header>

<aside class="main-sidebar">
	<section class="sidebar">
		<ul class="sidebar-menu" style="margin:0;">
			<li class="treeview <?php if($act_id == 'dashboard') { echo 'active'; } ?>">
				<a href="<?php echo site_url('institute_subscription/admin_dashboard');?>">
					<i class="fa fa-book"></i> <span>Dashboard</span>
				</a>
			</li>
			<li class="treeview">
				<a href="<?php echo site_url('institute_subscription/admin_logout'); ?>">
					<i class="fa fa-book"></i> <span>Logout</span>
				</a>
			</li>   
		</ul>
	</section>
</aside>
