<?php $this->load->view('admin/includes/header');?>
<?php $this->load->view('admin/includes/sidebar');?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Member Details Edit page
      </h1>
    
    </section>
	<form class="form-horizontal" name="usersAddForm" id="usersEditForm"  method="post"  enctype="multipart/form-data">
    <input type="hidden" name="regid" id="regid" value="<?php echo $regData['regid'];?>">
	<input type="hidden" name="regnumber" id="regnumber" value="<?php echo $regData['regnumber'];?>">
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Basic Details</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
            <?php //echo validation_errors(); ?>
              <?php if($this->session->flashdata('error')!=''){?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
              <?php } if($this->session->flashdata('success')!=''){ ?>
                <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
                <?php echo $this->session->flashdata('success'); ?>
              </div>
             <?php } 
			 if(validation_errors()!=''){?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                    <?php echo validation_errors(); ?>
                </div>
              <?php } 
			 ?> 
             
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">First Name *</label>
                	<div class="col-sm-2">
                 	<?php //echo $regData['namesub'];?>
                    <select name="sel_namesub" id="sel_namesub" class="form-control" required>
                    <option value="MR." <?php if($regData['namesub']=='MR.'){echo "selected='selected'";} ?>>MR.</option>
                    <option value="MRS." <?php if($regData['namesub']=='MRS.'){echo "selected='selected'";} ?>>MRS.</option>
                    <option value="MS." <?php if($regData['namesub']=='MS.'){echo "selected='selected'";} ?>>MS.</option>
                    <option value="DR." <?php if($regData['namesub']=='DR.'){echo "selected='selected'";} ?>>DR.</option>
                    <option value="PROF." <?php if($regData['namesub']=='PROF.'){echo "selected='selected'";} ?>>PROF.</option>
                    </select>
                    <input type="hidden" name="sel_namesub_hidd" id="sel_namesub_hidd" value="<?php echo $regData['namesub'];?>" /> 
					<input type="hidden" name="excode" id="excode" value="<?php echo $regData['excode']; ?>" /> 
                    </div>
                    
                     <div class="col-sm-3">
						<input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" required value="<?php echo $regData['firstname'];?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" onchange="createfullname()" onkeyup="createfullname()" onblur="createfullname()">
                         <input type="hidden" name="firstname_hidd" id="firstname_hidd" value="<?php echo $regData['firstname'];?>" /> 
                    </div>
                    
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Middle Name</label>
                	<div class="col-sm-5">
                   	<input type="text" class="form-control" id="middlename" name="middlename" placeholder="Middle Name"  value="<?php echo $regData['middlename'];?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" onchange="createfullname()" onkeyup="createfullname()" onblur="createfullname()">
                      <input type="hidden" name="middlename_hidd" id="middlename_hidd" value="<?php echo $regData['middlename'];?>" /> 
                    </div><!--(Max 30 Characters) -->
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Last Name</label>
                	<div class="col-sm-5">
                      <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name"  value="<?php echo $regData['lastname'];?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" onchange="createfullname()" onkeyup="createfullname()" onblur="createfullname()">
                       <input type="hidden" name="lastname_hidd" id="lastname_hidd" value="<?php echo $regData['lastname'];?>" /> 
                    </div><!--(Max 30 Characters) -->
                </div>
               
                
              </div>
                
               </div> <!-- Basic Details box closed-->
                 
        <div class="box box-info">
        	<div class="box-header with-border">
              <h3 class="box-title">Other Details</h3>
            </div>
			
            
            <div class="box-body">
            
                <div class="form-group">
                	<label for="roleid" class="col-sm-3 control-label">Date of Birth *</label>
                	<div class="col-sm-5 example">
                    <?php if($regData['dateofbirth'] != "0000-00-00" && $regData['dateofbirth'] != ""){ ?>
                        <input type="hidden" id="dob1" name="dob" required value="<?php echo date('Y-m-d',strtotime($regData['dateofbirth']));?>">
                        <input type="hidden" name="datepicker_hidd" id="datepicker_hidd" value="<?php echo date('Y-m-d',strtotime($regData['dateofbirth']));?>" /> 
                    <?php }else{ ?>
                    	<input type="hidden" id="dob1" name="dob" required value="">
                        <input type="hidden" name="datepicker_hidd" id="datepicker_hidd" value="" />
                    <?php } ?>
                        <?php 
                            $min_year = date('Y', strtotime("- 18 year"));
                            $max_year = date('Y', strtotime("- 80 year"));
                        ?>
                        <input type="hidden" id="doj1" name="doj1" required value="">
                        <input type='hidden' name='minyear' id='minyear' value="<?php echo $min_year; ?>">
                        <input type='hidden' name='maxyear' id='maxyear' value="<?php echo $max_year; ?>">
                        <span id="dob_error" class="error"></span>
                    </div>
                </div>
                
               

            <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Email *</label>
                	<div class="col-sm-5">
                    <input <?php if($regData['excode']=='997') echo' disabled="disabled"'; ?>  class="form-control" id="email" name="email" placeholder="Email" required data-parsley-type="email" value="<?php echo $regData['email'];?>" data-parsley-maxlength="45" data-parsley-editemailcheckadmin  autocomplete="off" type="text">
                    
                      
                       <input type="hidden" name="" id="email_hidd" value="<?php echo $regData['email'];?>">
                      (Enter valid and correct email ID to receive communication)
                      <span class="error"><?php //echo form_error('email');?></span>
                    </div>
                </div>  
                
                 
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label"><img src="<?php echo base_url();?><?php echo get_img_name($regData['regnumber'],'p');?><?php echo '?'.time(); ?>" height="100" width="100" ></label>
               <label for="roleid" class="col-sm-3 control-label"><img src="<?php echo base_url();?><?php echo get_img_name($regData['regnumber'],'s');?><?php echo '?'.time(); ?>" height="100" width="100"></label>
                <label for="roleid" class="col-sm-3 control-label"><img src="<?php echo base_url();?><?php echo get_img_name($regData['regnumber'],'pr');?><?php echo '?'.time(); ?>"  height="100" width="100"></label>
                </div>
                
            <div class="form-group">
            <label for="roleid" class="col-sm-3 control-label">Uploaded Photo</label>
            <label for="roleid" class="col-sm-3 control-label">Uploaded Signature</label>
            <label for="roleid" class="col-sm-3 control-label">Uploaded ID Proof</label>
            <label for="roleid" class="col-sm-2 control-label"><a href="<?php echo base_url();?>admin/ippb/IppbDashboard/editimages/<?php echo base64_encode($regData['regid']);?>/<?php echo base64_encode($regData['regnumber']);?>">Edit Images</a></label>
        	</div>
         
             	
                        <?php if(count($idtype_master)){
                                foreach($idtype_master as $idrow){ 	?>
                        <input name="idproof" value="<?php echo $idrow['id'];?>" type="radio" class="minimal"  <?php if($regData['idproof']==$idrow['id']){echo  'checked="checked"';}?>><?php echo $idrow['name'];?><br>
                        <?php } } ?>
           
           
           <?php if($regData['optnletter'] == ''){ $optnletter = 'Y';}else{ $optnletter = $regData['optnletter'];}?>
           <input type="hidden" name="optnletter" id="optnletter" value="<?php echo $optnletter;?>">
           <input type="hidden" name="optnletter" id="optnletter_hidd" value="<?php echo $optnletter;?>">
           
            <div class="box-footer">
                  <div class="col-sm-3 col-xs-offset-5">
                    <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Submit" onclick="return checkEdit_nm_new();">
                     <!--<a href="<?php echo base_url();?>admin/Report" class="btn btn-default pull-right">Back</a>-->
                     <a href="<?php echo base_url();?>/admin/ippb/IppbDashboard/registered_member_search_form?regnumber=<?php if(isset($regData['regnumber'])) echo $regData['regnumber'];?>" class="btn btn-default pull-right">Back</a>
                    </div>
            </div>
		</div>
            
            
             
         </div>
        </div>
      </div>
     
      
      
    </section>
    </form>
  </div>
<!--<link href="<?php echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet"> -->
 
<script>var site_url="<?php echo base_url();?>";</script> 
<script src="<?php echo base_url()?>assets/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/datepicker/datepicker3.css">

<script src="<?php echo base_url()?>assets/admin/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/timepicker/bootstrap-timepicker.min.css">

<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script>
<!--<script src="<?php echo base_url()?>js/js-validation.js"></script>-->
<script src="<?php echo base_url();?>js/validation.js?<?php echo time(); ?>"></script>
<script src="<?php echo base_url();?>assets/admin/dist/js/jquery.date-dropdowns.js"></script>
<script type="text/javascript">
  <!--var flag=$('#usersAddForm').parsley('validate');-->

</script>
<script>
  //START : AUTO FETCH FIRST, MIDDLE & LAST NAME FOR DISPLAY UNDER FULL NAME FIELD
  ///ADDED ON 12-07-2023 BY SM
  function createfullname() 
  {
    firstname = $.trim($("#firstname").val()).toUpperCase();
    middlename = ' ' + $.trim($("#middlename").val()).toUpperCase();
    lastname = ' ' + $.trim($("#lastname").val()).toUpperCase();
    if($.trim(firstname) != "" || $.trim(middlename) != "" || $.trim(lastname) != "")
    {
      $("#nameoncard").val(firstname + middlename + lastname);
    }
    else { $("#nameoncard").val("") }
  }//END : AUTO FETCH FIRST, MIDDLE & LAST NAME FOR DISPLAY UNDER FULL NAME FIELD
  
$(document).ready(function() 
{
	//$('#usersAddForm').parsley('validate');
	
	$('#dob1').change();
	
	var edu = '<?php echo $regData['qualification']; ?>';
	var qualification = '<?php echo $regData['specify_qualification']; ?>';
	if(edu == 'U')
	{
		$('#eduqual1').val(qualification);
		$('#eduqual1_hidd').val(qualification);
		/*$('#eduqual1').attr('required','required');
		$('#eduqual2').removeAttr('required');
		$('#eduqual3').removeAttr('required');
		$('#eduqual').removeAttr('required');*/
	}
	else if(edu == 'G')
	{
		$('#eduqual2').val(qualification);
		$('#eduqual2_hidd').val(qualification);
		/*$('#eduqual1').removeAttr('required');
		$('#eduqual2').attr('required','required');
		$('#eduqual3').removeAttr('required');
		$('#eduqual').removeAttr('required');*/
	}
	else if(edu == 'P')
	{
		$('#eduqual3').val(qualification);
		$('#eduqual3_hidd').val(qualification);
		/*$('#eduqual1').removeAttr('required');
		$('#eduqual2').removeAttr('required');
		$('#eduqual3').attr('required','required');
		$('#eduqual').removeAttr('required');*/
	}
		
	
	changedu(edu);
	
	 
	/*$('#datepicker,#doj').datepicker({
       autoclose: true,
	   endDate: '+0d',
	   format: 'yyyy-mm-dd'
     });*/
	 
	 $(function() {
		$("#dob1").dateDropdowns({
			submitFieldName: 'dob1',
			minAge: 0,
			maxAge:79
		});
		// Set all hidden fields to type text for the demo
		//$('input[type="hidden"]').attr('type', 'text').attr('readonly', 'readonly');
	});
});
	
function checkEdit_nm_new()
{
	//if($('#sel_namesub').val() == $('#sel_namesub_hidd').val())
	var flag = true;
	var gender = $('input[name=gender]:checked').val();
	var optedu = $('input[name=optedu]:checked').val();
	var idproof = $('input[name=idproof]:checked').val();
	//var optnletter = $('input[name=optnletter]:checked').val();
	var optnletter = $('#optnletter').val();
	
	var edu = '<?php echo $regData['qualification']; ?>';
	var qualification = '<?php echo $regData['specify_qualification']; ?>';
	qual_query = '';
	if(edu == 'U')
	{
		if($('#eduqual1').val() == $('#eduqual1_hidd').val())
			var qual_query = true;
		else
			var qual_query =  false;
	}
	else if(edu == 'G')
	{
		if($('#eduqual2').val() == $('#eduqual2_hidd').val())
			var qual_query = true;
		else
			var qual_query =  false;
	}
	else if(edu == 'P')
	{
		if($('#eduqual3').val() == $('#eduqual3_hidd').val())
			var qual_query = true;
		else
			var qual_query =  false;
	}
	else
	{
		var qual_query = true;
	}
	
	//       
	
	
	if($('#sel_namesub').val().trim() == $('#sel_namesub_hidd').val().trim() && $('#firstname').val().trim() == $('#firstname_hidd').val().trim() && $('#middlename').val().trim() == $('#middlename_hidd').val().trim() && $('#lastname').val().trim() == $('#lastname_hidd').val().trim() && $('#addressline1').val().trim() == $('#addressline1_hidd').val().trim() && $('#addressline2').val().trim() == $('#addressline2_hidd').val().trim() && $('#addressline3').val().trim() == $('#addressline3_hidd').val().trim() && $('#addressline4').val().trim() == $('#addressline4_hidd').val().trim() && $('#district').val().trim() == $('#district_hidd').val().trim() && $('#city').val().trim() == $('#city_hidd').val().trim()  && $('#state').val().trim() == $('#state_hidd').val().trim() && 
	$('#dob1').val().trim() == $('#datepicker_hidd').val().trim() && $('#pincode').val().trim() == $('#pincode_hidd').val().trim()  && gender == $('#gender_hidd').val().trim()  && optedu == $('#optedu_hidd').val().trim() && $('#email').val().trim() == $('#email_hidd').val().trim() && $('#stdcode').val().trim() == $('#stdcode_hidd').val().trim() && $('#phone').val().trim() == $('#phone_hidd').val().trim() && $('#mobile').val().trim() == $('#mobile_hidd').val().trim() && idproof == $('#idproof_hidd').val().trim() && $('#idNo').val().trim() == $('#idNo_hidd').val().trim() && $('#aadhar_card').val().trim() == $('#aadhar_card_hidd').val().trim() && optnletter == $('#optnletter_hidd').val().trim() && qual_query)
	{
		alert("Please Change atleast One Value");
		return false;
	}
	else
	{
		var flag=$('#usersEditForm').parsley().validate();
		var dob = $('#dob1').val();
		var err_msg = $("#dob_error").html();
		if(dob=='')
		{
			$("#dob_error").html('Please select Date Of Birth');
			$(".day").focus();
			flag = false;	
		}
		if(err_msg!='')
			flag = false;
			
		//alert(flag);return false;
		if(flag){
			return true;	//$('#usersAddForm').submit();
		}
		else{
			return false;
		}
	}	
}
	
function editUser(id,roleid,Name,Username,Email){
	$('#id').val(id);
	$('#roleid').val(roleid);
	$('#name').val(Name);
	$('#username').val(Username);
	$('#emailid').val(Email);
	$('#btnSubmit').val('Update');
	$('#roleid').focus();
	$('#password').removeAttr('required');
	$('#confirmPassword').removeAttr('required');
	
}
	
function changedu(dval)
{
	var UGid = document.getElementById('UG');
	var GRid = document.getElementById('GR');
	var PGid = document.getElementById('PG');
	var EDUid = document.getElementById('edu');

	if(dval == 'U')
	{
		/*$('#eduqual1').attr('required','required');
		$('#eduqual2').removeAttr('required');
		$('#eduqual3').removeAttr('required');
		$('#eduqual').removeAttr('required');*/
		
		if(UGid != null) {
		//	alert('UG');
			document.getElementById('UG').style.display = "block";
		}
		if(GRid != null) {
			document.getElementById('GR').style.display = "none";
		}
		if(PGid != null) {
			document.getElementById('PG').style.display = "none";	
		}
		if(EDUid != null) {
			document.getElementById('edu').style.display = "none";	
		}
	}
	else if(dval == 'G')
	{
		/*$('#eduqual1').removeAttr('required');
		$('#eduqual2').attr('required','required');
		$('#eduqual3').removeAttr('required');
		$('#eduqual').removeAttr('required');*/
			
		if(UGid != null) {
			document.getElementById('UG').style.display = "none";
		}
		if(GRid != null) {
			document.getElementById('GR').style.display = "block";
		}
		if(PGid != null) {
			document.getElementById('PG').style.display = "none";	
		}
		if(EDUid != null) {
			document.getElementById('edu').style.display = "none";	
		}
	
	}
	else if(dval == 'P')
	{
		/*$('#eduqual1').removeAttr('required');
		$('#eduqual2').removeAttr('required');
		$('#eduqual3').attr('required','required');
		$('#eduqual').removeAttr('required');*/
			
		if(UGid != null) {
			document.getElementById('UG').style.display = "none";
		}
		if(GRid != null) {
			document.getElementById('GR').style.display = "none";
		}
		if(PGid != null) {
			document.getElementById('PG').style.display = "block";	
		}
		if(EDUid != null) {
			document.getElementById('edu').style.display = "none";	
		}
	}
	else
	{
		document.getElementById('UG').style.display = "none";
		document.getElementById('GR').style.display = "none";
		document.getElementById('PG').style.display = "none";
		document.getElementById('edu').style.display = "block";
		
		$('#eduqual1').removeAttr('required');
		$('#eduqual2').removeAttr('required');
		$('#eduqual3').removeAttr('required');
		$('#eduqual').removeAttr('required');
	}
}
	
</script> 

<script>
$(function(){
    $('#new_captcha').click(function(event){
        event.preventDefault();
    $.ajax({
 		type: 'POST',
 		url: site_url+'Register/generatecaptchaajax/',
 		success: function(res)
 		{	
 			if(res!='')
 			{$('#captcha_img').html(res);
 			}
 		}
    });
	});

 $("#datepicker,#doj").keypress(function(event) {event.preventDefault();});
});
</script>
<style>
.labelleft {
	text-align:left !important;
	padding-left:0 !important;
	padding-right:0 !important;
	font-weight:normal;
}
.w50 {
	width:50% !important;
}
</style>
<?php $this->load->view('admin/includes/footer');?>