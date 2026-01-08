<footer class="main-footer">
  <div class="pull-right hidden-xs"> Powered By <b>ESDS</b> </div>
  <strong>Copyright &copy;&nbsp;<?php echo date("Y"); ?> <a href="javascript:void(0);">ESDS</a>.</strong> All rights reserved. </footer>
</div>

<script>
	<?php /* FUNCTION ADDED BY SAGAR ON 01-10-2021 TO REDIRECT THE USER TO EDIT PROFILE PAGE AFTER LOGIN */ ?>
	function garp_redirect_edit_profile()
	{
		$.ajax(
		{
			type: "POST",
			url: "<?php echo site_url('Garp_exam/set_session_garp_redirect_edit_profile_ajax'); ?>",
			cache: false,
			dataType: 'JSON',
			success:function(data)
			{
				if(data.flag == "success")
				{ 
					window.location.href = "<?php echo site_url(); ?>";					
				}
			}
		});
	}
</script>

<!-- ./wrapper --> 
<!-- Bootstrap 3.3.6 --> 
<script src="<?php echo base_url()?>assets/admin/bootstrap/js/bootstrap.min.js"></script> 
<!-- FastClick --> 
<script src="<?php echo base_url()?>assets/admin/plugins/fastclick/fastclick.js"></script> 
<!-- AdminLTE App --> 
<script src="<?php echo base_url()?>assets/admin/dist/js/app.min.js"></script> 
<!-- AdminLTE for demo purposes --> 
<script src="<?php echo base_url()?>assets/admin/dist/js/demo.js"></script>
</body>
</html>