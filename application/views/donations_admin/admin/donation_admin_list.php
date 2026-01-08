<?php $this->load->view('donations_admin/admin/includes/header');?>
<?php $this->load->view('donations_admin/admin/includes/sidebar');?>
<?php $donationadminuserdata = $this->session->userdata('donation_admin');?>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Donation Form </h1>
    <?php //echo $breadcrumb; ?>
  </section>
  <div class="col-md-12"> <br />
    <?php 
	 if($this->session->flashdata('success')!=''){ ?>
    <div class="alert alert-success alert-dismissible" id="success_id">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <?php echo $this->session->flashdata('success'); ?> </div>
    <?php }?>
  </div>
  <!-- Main content -->
  <section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-body">
        <br />
        <p style="color:#038acd; font-size:16px;"> "I have read the contents of the appeal from Chief Executive Officer requesting all the staff member of IIBF to contribute to the PM CARES Fund for COVID-19 relief. I hereby authorise the Institute to encase leave from my PL balance / deduct from my remuneration for the month of April 2020 as per the details given below and contribute to the Prime Minister's Assistance and Relief in Emergency Situations (PM CARES) Fund." </p>
        <hr/>
		
		<?php if($member_data['isactive']!='5'){ ?>
		
        <form class="form-horizontal" name="donationrequestForm" id="donationrequestForm"  method="post" enctype="multipart/form-data"  action="<?php echo base_url();?>donations_admin/admin/Donation_admin/update_details">
          <input type="hidden" name="id" id="id" value="<?php if($donationadminuserdata['id']!=''){ echo $donationadminuserdata['id']; } ?>"/>
          <!--<section class="content">-->
          <div class="row">
            <div class="col-xs-12">
              <div class="form-group">
                <label for="roleid" class="col-xs-4 control-label"></label>
                <div class="col-sm-5" style="color:#009933;"> <?php echo $sms;?> </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-xs-4 control-label"><strong>Do you wish to donate your salary to PM Cares Fund?&nbsp;:</strong></label>
                <div class="col-sm-5">
                  <input type="radio" id="donate_salary_yes" name="donate_salary" value="Yes" <?php if($member_data['donate_salary']=='Yes'){ ?> checked="checked" <?php }  ?> checked="checked">
                  <label for="Yes">Yes</label>
                  <input type="radio" id="donate_salary_no" name="donate_salary" value="No" <?php if($member_data['donate_salary']=='No'){ ?> checked="checked" <?php }  ?>>
                  <label for="No">No</label>
                </div>
              </div>
              <script>
			$(document).ready(function() 
			{
				$(document).on("click","#donate_salary_no", function(){
				
					$(".donation_type_div").hide();
				});
				
				$(document).on("click","#donate_salary_yes", function(){
				
					$(".donation_type_div").show();
				});
				
				
				$(document).on("click","#number_of_days_type", function(){
					$("#number_of_days_div").show();
					$("#amount").val("0");
					$("#amount_div").hide();
					$("#amount").removeAttr("required");
					$("#no_of_days").prop('required',true);
					
				});
				
				$(document).on("click","#amount_type", function(){
					$("#number_of_days_div").hide();
					
					$("#no_of_days").val("0");
					$("#amount_div").show();
					$("#no_of_days").removeAttr("required");
					$("#amount").prop('required',true);
				});
			});
			</script>
              <div class="form-group">
                <label for="roleid" class="col-xs-4 control-label"><strong>Employee Name&nbsp;:</strong></label>
                <div class="col-sm-5">
                  <?php if($member_data['name']!=''){ echo $member_data['name']; } ?>
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-xs-4 control-label"><strong>Employee Email Id&nbsp;:</strong></label>
                <div class="col-sm-5">
                  <?php if($member_data['username']!=''){ echo $member_data['username']; } ?>
                </div>
              </div>
              <div class="donation_type_div" <?php if($member_data['donate_salary']=='No'){ ?> style="display:none;" <?php } ?>>
              <div class="form-group">
                <label for="roleid" class="col-xs-4 control-label"><strong>Select Donation Type&nbsp;:</strong></label>
                <div class="col-sm-5">
                  <input type="radio" id="number_of_days_type" name="donation_type" value="number_of_days_type" <?php if($member_data['donation_type']=='number_of_days_type'){ ?> checked="checked" <?php }  ?>>
                  <label for="Yes">Number of PL days to be encashed</label>
                  <input type="radio" id="amount_type" name="donation_type" value="amount_type" <?php if($member_data['donation_type']=='amount_type'){ ?> checked="checked" <?php }  ?> >
                  <label for="No">Amount to be donated</label>
                </div>
              </div>
              <div class="form-group" id="number_of_days_div" <?php if($member_data['donation_type']!='number_of_days_type'){ ?>style="display:none;" <?php  }  ?> >
                <label for="roleid" class="col-xs-4 control-label"><strong>Number of days/ PL to be encashed&nbsp;:</strong></label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="no_of_days" name="no_of_days" placeholder="Number of Days (Enter Digit Only)" value="<?php if($member_data['no_of_days']!=''){ echo $member_data['no_of_days']; } ?>" required />
                  <span class="error" id="err_title"></span><span style="color:#FF0000"><?php echo $err_sms;?></span></div>
              </div>
              <div class="form-group" id="amount_div" <?php if($member_data['donation_type']!='amount_type'){ ?>style="display:none;" <?php } ?>>
                <label for="roleid" class="col-xs-4 control-label"><strong>Amount to be donated&nbsp;:</strong></label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="amount" name="amount" placeholder="Amount to be donated (Enter Digit Only)" value="<?php if($member_data['amount']!=''){ echo $member_data['amount']; } ?>"  required />
                  <span style="color:#FF0000"><?php echo $err_sms;?></span></div>
              </div>
            </div>
            <div class="box-footer">
              <div class="col-sm-6 col-sm-offset-3">
                <input type="submit" name="btnSubmit" class="btn btn-info" id="btnSubmit" value="Submit">
                <a href="<?php echo base_url();?>donations_admin/admin/Donation_admin/donation_admin_list" class="btn btn-default" >Reset</a> </div>
            </div>
          </div>
          </div>
        </form>
      
	  
	  <?php } else { ?>
	  <div align="center" style="color:#0000FF;"><h1>You have already Submitted the Donation Form</h1></div>
	  <?php } ?>
	  
	  </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>
</section>
</div>
<!-- Data Tables -->
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">
<style>
.active_batch{
color:#00a65a;	
font-weight:600;
}

.deactive_batch{
color:#930;	
font-weight:600;
}
.input_search_data{
 width:100%;	
}
tfoot {
    display: table-header-group;
}
.vbtn{
padding: 3px 4px;
font-weight: 600;
}
</style>
<!-- Data Tables -->
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
<script src="<?php echo base_url()?>js/js-paginate.js"></script>
<script>
$(function () {
	$('#listitems2').DataTable();
	$("#listitems_filter").show();
});
function donationCheckForm()
	{ 
		$('#error_id').html(''); 
		$('#success_id').html(''); 
		$('#error_id').removeClass("alert alert-danger alert-dismissible");
		$('#success_id').removeClass("alert alert-danger alert-dismissible");
		$('#tiitle_error').html(''); 
		var rflag = 1;
		var form_flag=$('#donationrequestForm').parsley().validate();
		alert(form_flag);
		
		if(form_flag)
		{
			return true;
		}
	}



</script>
<?php $this->load->view('donations_admin/admin/includes/footer');?>
