<?php
header('Cache-Control: must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
//echo '***************'; die();
?>

<div class="container">
  <section class="content-header box-header with-border" style="height: 45px; background-color: #1287C0; ">
    <h1 class="register">AMP Exam Recovery Form</h1>
    <br />
  </section>
  <section class="">
    <div class="row">
      <div class="col-md-12 col-sm-12" style="">
        <?php if ($this->session->flashdata('flsh_msg') != '') {?>
        <div class="alert alert-danger"> <?php echo $this->session->flashdata('flsh_msg'); ?> </div>
        <?php }?>
        <form name="getDetailsForm" id="getDetailsForm" method="post" action="<?php echo base_url(); ?>AmpExamRecovery">
          <br />
          <div class="">
            <div for="roleid" class="col-sm-12 control-label" style="text-align: right; width:35%;">Membership No.&nbsp;<span style="color:#F00">*</span>&nbsp;:</div>
            <div class="col-sm-12 col-md-3">
              <input type="text" class="form-control" id="regnumber" name="regnumber" placeholder="Membership No." required value="<?php if (isset($row['regnumber'])) { echo $row['regnumber'];} else { echo set_value('regnumber'); }
?>" <?php if (isset($row['regnumber'])) { echo "readonly='readonly'";} elseif (set_value('regnumber')) { echo "readonly='readonly'"; } ?> style="border-color:#000;" title="Membership No.">
            </div>
            <div class="col-sm-12 col-md-3">
              <?php 
			  	if (isset($row['regnumber']) || set_value('regnumber')) {
				?>
              <a href="<?php echo base_url();?>AmpExamRecovery" class="btn btn-warning" id="modify" style="">Reset</a>
              <input type="submit" class="btn btn-info" name="btnGetDetails" id="btnGet" value="Search" style="display:none;">
              <?php
				} 
				else
				{
				?>
              <input type="submit" class="btn btn-info" name="btnGetDetails" id="btnGetDetails" value="Search">
              <?php 
				 } 
				  ?>
            </div>
            <div> 
              <!-- <div class="col-sm-12" align="center"> <span style="color:#F00; font-size:14px;">Please insert your 'Membership No.' and click on 'Get Details' button. All below details will get filled automatically.</span> </div>--> 
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
  <br />
  <!-- Close Get Details-->
  <?php if(!empty($MemberArray)) { ?>
  <form class="form-horizontal" name="GstRecoveryAddForm" id="GstRecoveryAddForm"  method="post"  enctype="multipart/form-data">
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"><strong>Candidates Details</strong></h3>
            </div>
            <br/>
            <div class="table-responsive">
              <table width="100%"  border="1" cellspacing="5" cellpadding="5">
                <tr>
                  <th>Sr.No.</th>
                  <th>Membership No.</th>
                  <th>Name</th>
                  <th>Email Id</th>
                  <th>Mobile No.</th>
                  <th>Service Name</th>
                  <th>Amount Payable</th>
                  <th>Payment</th>
                </tr>
                <?php 
			  $sr = 1;
			  foreach($MemberArray as $value)
			  {
				$payStatusArr = array('1'=>'Membership Registration','2'=>'Exam','3'=>'Duplicate ID Card','4'=>'Duplicate Certificate','5'=>'Membership Renewal');
			  ?>
                <tr>
                  <td><?php echo $sr; ?></td>
                  <td><?php echo $value['regnumber']; ?></td>
                  <td><?php echo $value['name']; ?></td>
                  <td><?php echo $value['email_id']; ?></td>
                  <td><?php echo $value['mobile_no']; ?></td>
                  <td><?php 
					
						echo 'AMP Exam Registration'; 
					?></td>
                  <td><span>&#8377;</span>&nbsp;<?php echo $value['fee_amt']; ?></td>
                  <td><?php  
					  if($value['pay_status'] != '2'){?>
                    <a class="btn btn-success" style="width: 75px;">Paid</a>
                    <?php
                        }else{
                        ?>
                    <a href="<?php echo base_url();?>AmpExamRecovery/stored_details/<?php echo base64_encode($value['invoice_id']); ?>" class="btn btn-info" style="width: 75px;">Pay Now</a>
                    <?php }?></td>
                </tr>
                <?php 
			  $sr++;
			  }
			  ?>
                <tr>
                  <th colspan="8">&nbsp;</th>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
  </form>
  <?php } ?>
</div>
<link href="<?php echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script> 
<script src="<?php echo base_url();?>assets/admin/dist/js/jquery.date-dropdowns.js"></script> 
<!--<script src="<?php //echo base_url();?>js/gstrecovery.js?<?php //echo time();?>"></script>--> 
<script>
$(document).ready(function() {
    $("#regnumber").focus();
	var flag = $("#flag").val();
	if(flag == 1){
		$("#regnumber").val('');
		$("#regnumber").prop("readonly", false);
		$("#modify").hide();
		$("#btnGet").show();
	}
});

history.pushState(null, null, '<?php echo $_SERVER["REQUEST_URI"]; ?>');
window.addEventListener('popstate', function(event) {
    window.location.assign(site_url+"GstRecovery/");
});

$(function(){
	 /*$(document).keydown(function(event) {
        if (event.ctrlKey==true && (event.which == '67' || event.which == '86')) {
            if(event.which == '67')
			{
				alert('Key combination CTRL + C has been disabled.');
			}
			if(event.which == '86')
			{
				alert('Key combination CTRL + V has been disabled.');
			}
			event.preventDefault();
         }
    });
	
	$("body").on("contextmenu",function(e){
        return false;
    });
    $(this).scrollTop(0);*/

});

</script>
<style>
.modal-dialog {
	position: relative;
	display: table;
	overflow-y: auto;
	overflow-x: auto;
	width: 920px;
	min-width: 300px;
}
#confirm .modal-dialog {
	position: relative;
	display: table;
	overflow-y: auto;
	overflow-x: auto;
	width: 420px;
	min-width: 400px;
}
.skin-blue .main-header .navbar {
	background-color: #fff;
}
body.layout-top-nav .main-header h1 {
	color: #0699dd;
	margin-bottom: 0;
	margin-top: 30px;
}
.container {
	position: relative;
}
.box-header.with-border {
	background-color: #7fd1ea;
	border-top-left-radius: 0;
	border-top-right-radius: 0;
	margin-bottom: 10px;
}
.header_blue {
	background-color: #2ea0e2 !important;
	color: #fff !important;
	margin-bottom: 0 !important;
}
.box {
	border: none;
	box-shadow: none;
	border-radius: 0;
	margin-bottom: 0;
}
.nobg {
	background: none !important;
	border: none !important;
}
.box-title-hd {
	color: #3c8dbc;
	font-size: 16px;
	margin: 0;
}
.blue_bg {
	background-color: #e7f3ff;
}
.m_t_15 {
	margin-top: 15px;
}
.main-footer {
	padding-left: 160px;
	padding-right: 160px;
}
.content-header > h1 {
	font-size: 22px;
	font-weight: 600;
}
h4 {
	margin-top: 5px;
	margin-bottom: 10px !important;
	font-size: 14px;
	line-height: 18px;
	padding: 0 5px;
	font-weight: 600;
	text-align: justify;
}
.form-horizontal .control-label {
	padding-top: 4px;
}
.pad_top_2 {
	padding-top: 2px !important;
}
.pad_top_0 {
	padding-top: 0px !important;
}
div.form-group:nth-child(odd) {
	background-color: #dcf1fc;
	padding: 5px 0;
}
#confirmBox {
	display: none;
	background-color: #eee;
	border-radius: 5px;
	border: 1px solid #aaa;
	position: fixed;
	width: 300px;
	left: 50%;
	margin-left: -150px;
	padding: 6px 8px 8px;
	box-sizing: border-box;
	text-align: center;
	z-index: 1;
	box-shadow: 0 1px 3px #000;
}
#confirmBox .button {
	background-color: #ccc;
	display: inline-block;
	border-radius: 3px;
	border: 1px solid #aaa;
	padding: 2px;
	text-align: center;
	width: 80px;
	cursor: pointer;
}
#confirmBox .button:hover {
	background-color: #ddd;
}
#confirmBox .message {
	text-align: left;
	margin-bottom: 8px;
}
.form-group {
	margin-bottom: 10px;
}
.form-horizontal .form-group {
	margin-left: 0;
	margin-right: 0;
}
.form-control {
	border-color: #888;
}
.form-horizontal .control-label {
	font-weight: normal;
}
a.forget {
	color: #9d0000;
}
a.forget:hover {
	color: #9d0000;
	text-decoration: underline;
}
ol li {
	line-height: 18px;
}
.example {
	text-align: left !important;
	padding: 0 10px;
}
td, th {
	text-align:center !important;
	padding: 5px !important;
}
.container {
    width: 75% !important;
  
	margin-bottom: 500px !important;
}
.main-header {

	width: 75% !important;
}
th {
	background-color: aliceblue;
	text-align:center;
}

</style>
