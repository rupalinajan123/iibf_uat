

<script>
	/* function validateForm(form)
	{
	//var 
	var member_no = document.getElementById('member_no').value;
	//alert(member_no);
	if(member_no=="")
	{
	alert('First get details of member and then submit');
	document.getElementById("member_no").focus();
	return false;
	}
	else
	{
	
	var is_dra_mem = '<?php echo @$result[0]['is_dra_mem']; ?>';
	
	if(is_dra_mem != '2')
	{
	
	var firstname=form.firstname.value;
	var email=form.email.value;
	var mobile=form.mobile.value;
	var addressline1=form.addressline1.value;
	var district=form.district.value;
	var city=form.city.value;
	var state=form.state.value;
	var pincode=form.pincode.value;
	//alert();
	if( firstname=="" ||email=="" || mobile=="" || addressline1=="" || district=="" || city=="" || state == "" || pincode =="")
	{
	$('#confirm').modal('show');
	
	}
	}
	}
	} */
	
	
</script>


<script src="<?php echo base_url()?>assets/admin/bootstrap/js/bootstrap.min.js"></script> 
<script src="<?php echo base_url()?>assets/admin/plugins/fastclick/fastclick.js"></script> 
<script src="<?php echo base_url()?>assets/admin/dist/js/app.min.js"></script> 
<script src="<?php echo base_url()?>assets/admin/dist/js/demo.js"></script>
<script src="<?php echo base_url()?>js/jquery.validate.min.js"></script>
<script src="<?php echo base_url();?>js/disability.js?<?php echo time(); ?>"></script>
<?php $this->load->view('scribe_form/common_validation_all'); ?>

	