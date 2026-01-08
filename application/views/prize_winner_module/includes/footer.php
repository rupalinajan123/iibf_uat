<footer class="main-footer">
	<div class="pull-right hidden-xs">Powered By <b>ESDS</b></div>
	<strong>Copyright &copy; &nbsp;<?php echo date("Y"); ?> <a href="javascript:void(0);">ESDS</a>.</strong> All rights
	reserved.
</footer>

<script src="<?php echo base_url('assets/admin/bootstrap/js/bootstrap.min.js'); ?>"></script><!-- Bootstrap 3.3.6 -->
<script src="<?php echo base_url('assets/chosen/chosen.jquery.js');?>"></script><!----- FOR SELECT DROPDOWN ----->
<script src="<?php echo base_url('assets/admin/plugins/slimScroll/jquery.slimscroll.min.js'); ?>"></script><!-- SlimScroll -->
<script src="<?php echo base_url('assets/admin/plugins/fastclick/fastclick.js'); ?>"></script><!-- FastClick -->
<script src="<?php echo base_url('assets/admin/dist/js/app.min.js'); ?>"></script><!-- AdminLTE App -->
<script src="<?php echo base_url('assets/admin/dist/js/demo.js'); ?>"></script><!-- AdminLTE for demo purposes -->
<script>
	$('.chosen-select').chosen({width: "100%"});
	/* $(document).ready(function(){
		$(".DTTT_button_print, .DTTT_button_copy, .DTTT_button_csv, .DTTT_button_xls, .DTTT_button_pdf").hide();
	}); */
</script>

<!----- FOR DATEPICKER ----->
<script src="<?php echo base_url('assets/admin/plugins/datepicker/bootstrap-datepicker.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo base_url('assets/admin/plugins/datepicker/datepicker3.css'); ?>">

<script> 	
	$(document).ready(function() 
	{			
		$("#from_date").attr('autocomplete', 'off');
		$("#to_date").attr('autocomplete', 'off');
				
		$('#from_date').datepicker({ format: 'yyyy-mm-dd', endDate: '-2d', autoclose: true, forceParse: true }).on('changeDate', function()
		{
			$('#to_date').datepicker('setStartDate', new Date($(this).val()));
		}); 
		
		$('#to_date').datepicker({ format: 'yyyy-mm-dd', endDate: '-2d', autoclose: true, forceParse: true }).on('changeDate', function()
		{
			$('#from_date').datepicker('setEndDate', new Date($(this).val()));
		});
	});
	</script>

<?php if(isset($from_date) && $from_date != "") {	?> 
	<script>$('#to_date').datepicker({ format: 'yyyy-mm-dd', startDate:'<?php echo $from_date; ?>', endDate: '+0d', autoclose: true });</script>	
<?php }

if(isset($to_date) && $to_date != "") {	?> 
	<script>$('#from_date').datepicker({ format: 'yyyy-mm-dd', endDate: '<?php echo $to_date; ?>', autoclose: true });</script>	
<?php } ?>

<script src="<?php echo base_url('assets/js/jquery.validate.js'); ?>"></script><!----- FOR JQUERY VALIDATION ----->
<script>  
	$(document).ajaxStart(function() { $("#page_loader").css("display", "block"); });
	$(document).ajaxComplete(function() { $("#page_loader").css("display", "none"); });
	
	$(".allowd_only_numbers").keydown(function (e) 
	{
		// Allow: backspace, delete, tab, escape, enter
		if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
		// Allow: Ctrl+A
		(e.keyCode == 65 && e.ctrlKey === true) || 
		// Allow: home, end, left, right
		(e.keyCode >= 35 && e.keyCode <= 39)) 
		{
			// let it happen, don't do anything
			return;
		}
		
		// Ensure that it is a number and stop the keypress
		if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
			e.preventDefault();
		}
	});
</script>	