<?php $careeradminuserdata = $this->session->userdata('career_admin');?>
<aside class="main-sidebar">
	<section class="sidebar">
		<ul class="sidebar-menu" style="margin:0;">			
			<li class="treeview <?php if(stristr(current_url(),'examdashboard')) { echo "active"; } ?>">
				<a href="<?php echo site_url('webmanager/examdashboard'); ?>">
					<i class="fa fa-th-large"></i> <span>Exam Count</span>
				</a>
			</li>
			
			<li class="treeview <?php if(stristr(current_url(),'contact_class')) { echo "active"; } ?>">
				<a href="<?php echo site_url('webmanager/contact_class'); ?>">
					<i class="fa fa-th-large"></i> <span>Contact Classes Training Count</span>
				</a>
			</li>
			
			<li class="treeview <?php if(stristr(current_url(),'duplicate_certificate')) { echo "active"; } ?>">
				<a href="<?php echo site_url('webmanager/duplicate_certificate'); ?>">
					<i class="fa fa-th-large"></i> <span>Duplicate Certificate</span>
				</a>
			</li>
			
			<li class="treeview <?php if(stristr(current_url(),'duplicate_icard')) { echo "active"; } ?>">
				<a href="<?php echo site_url('webmanager/duplicate_icard'); ?>">
					<i class="fa fa-th-large"></i> <span>Duplicate Icard</span>
				</a>
			</li>
			
			<li class="treeview <?php if(stristr(current_url(),'member_registration')) { echo "active"; } ?>">
				<a href="<?php echo site_url('webmanager/member_registration'); ?>">
					<i class="fa fa-th-large"></i> <span>Member Registration</span>
				</a>
			</li>
      
      <li class="treeview <?php if(stristr(current_url(),'member_renewal')) { echo "active"; } ?>">
				<a href="<?php echo site_url('webmanager/member_renewal'); ?>">
					<i class="fa fa-th-large"></i> <span>Member Renewal</span>
				</a>
			</li>
			
			<li class="treeview <?php if(stristr(current_url(),'vision')) { echo "active"; } ?>">
				<a href="<?php echo site_url('webmanager/vision'); ?>">
					<i class="fa fa-th-large"></i> <span>Vision Count</span>
				</a>
			</li>
			
			<li class="treeview <?php if(stristr(current_url(),'bankquest')) { echo "active"; } ?>">
				<a href="<?php echo site_url('webmanager/bankquest'); ?>">
					<i class="fa fa-th-large"></i> <span>Bankquest Count</span>
				</a>
			</li>
			
			<li class="treeview <?php if(stristr(current_url(),'finquest')) { echo "active"; } ?>">
				<a href="<?php echo site_url('webmanager/finquest'); ?>">
					<i class="fa fa-th-large"></i> <span>Finquest Count</span>
				</a>
			</li>
			
			<li class="treeview <?php if(stristr(current_url(),'cpd')) { echo "active"; } ?>">
				<a href="<?php echo site_url('webmanager/cpd'); ?>">
					<i class="fa fa-th-large"></i> <span>CPD</span>
				</a>
			</li>
			
			<li class="treeview <?php if(stristr(current_url(),'blended')) { echo "active"; } ?>">
				<a href="<?php echo site_url('webmanager/blended'); ?>">
					<i class="fa fa-th-large"></i> <span>Blended</span>
				</a>
			</li>
			
			<li class="treeview <?php if(stristr(current_url(),'dra')) { echo "active"; } ?>">
				<a href="<?php echo site_url('webmanager/dra'); ?>">
					<i class="fa fa-th-large"></i> <span>DRA</span>
				</a>
			</li>
			
			<li class="treeview <?php if(stristr(current_url(),'elearning_spm')) { echo "active"; } ?>">
				<a href="<?php echo site_url('webmanager/elearning_spm'); ?>">
					<i class="fa fa-th-large"></i> <span>Separate E-learning Exam</span>
				</a>
			</li>
			<br>
		</ul>
		
	</section>
</aside>

