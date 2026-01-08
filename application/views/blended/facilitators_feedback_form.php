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
</style>
<?php
header('Cache-Control: must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>

<div class="container">
  <section class="content-header box-header with-border" style="height: 45px; background-color: #1287C0; ">
    <h1 class="register">Blended Training  Facilitator Feedback Form </h1>
    <br />
  </section>
  <div> 
    <!-- Start Get Details -->
    <?php
/*if (!empty($row)) {
    if (isset($row['msg']) && $row['msg'] != '') {
        echo '<div class="alert alert-danger alert-dismissible">' . $row['msg'] . '</div>';
    }
}*/
?>
  </div>
  <section class="">
    <div class="row">
      <div class="col-md-12" style="">
        <?php if ($this->session->flashdata('flsh_msg') != '') {?>
        <div class="alert alert-danger"> <?php echo $this->session->flashdata('flsh_msg'); ?> </div>
        <?php }?>
        <?php
if ($this->session->flashdata('error') != '') {?>
        <div class="alert alert-danger alert-dismissible" id="error_id">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <?php echo $this->session->flashdata('error');?> </div>
        <?php } if ($this->session->flashdata('success') != '') {?>
        <div class="alert alert-success alert-dismissible" id="success_id">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <?php echo $this->session->flashdata('success');?> </div>
        <?php } if (validation_errors() != '') { ?>
        <div class="alert alert-danger alert-dismissible" id="error_id">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <?php echo validation_errors(); 
		  $var_errors='';?> </div>
        <?php }
		$var_errors='';
		 if ($var_errors != '') { ?>
        <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <?php ;
		   echo $var_errors; ?>
        </div>
        <?php } ?>
        <form name="fact_form" id="fact_form" method="post" action="<?php echo base_url(); ?>blended_feedback/save_feedback/<?php echo $batch_code;?>">
          <table border=0 cellpadding=10 >
            <!-- <tr><td><br><strong>Traning Name :  </strong><?php //echo $traning_name;?> <br />  </td></tr>

    <tr>   <td>  <strong> Center  :  </strong><?php //echo  $center_name;?><br />   </td></tr>
<tr>   <td>   <strong> Training Programme Dates :  </strong><?php //echo $start_traning_date.'  to  '.$end_traning_date;?><br /><br />   </td></tr>
 <tr>   <td>  --> 
            <!--<strong>Membership No: <input type="text" name="mem_no" class="form-control"/>
Member Name: <input type="text" name="mem_name" class="form-control"/></strong><br />
<br />
<br />-->
            <div class="box-header with-border">
              <h3 class="box-title">Basic Details</h3>
            </div>
            <div class="col-sm-3"> <strong>Membership No:</strong> </div>
            <div class="col-sm-3">
              <input type="text" class="form-control col-sm-4" id="mem_no" name="mem_no" placeholder="Membership No" value="<?php if(isset($this->session->userdata['mem_no'])){echo $this->session->userdata['mem_no'];}?>">
            </div>
            <div style="clear:both">&nbsp;</div>
            <div class="col-sm-3"> <strong> Member Name:</strong><span style="color:#F00">*</span> </div>
            <div class="col-sm-3">
              <input type="text" class="form-control col-sm-4" id="mem_name" name="mem_name" placeholder="Member Name" value="<?php 
				   if(isset($this->session->userdata['mem_name']))
				   {echo $this->session->userdata['mem_name'];
				   }?>">
            </div>
            <br />
            <br />
            <br />
            <br />
            <div class="box-header with-border">
              <h3 class="box-title"> Please provide your feedback below  : </h3>
            </div>
            Note: All the questions mark <span style="color:#F00"> '*' </span> are mandatory.<br />
              </td>
              </tr>
          </table>
          <table border=0>
            <tr style=" font-size:16px">
              <th width="90%" align="center"  bgcolor="#7fd1ea" style="font-size:17px"><center>
                  Questions
                </center></th>
                </center>
              <th width="10%" bgcolor="#7fd1ea" style="font-size:17px"><center>
                  Facilitator
                </center></th>
                </h3>
            </tr>
            <?php $i=1;
	  $total_q=count($feedback_question);
	   foreach($feedback_question as $que)
	  {
		  ?>
            <tr>
              <td valign=top><br>
                <br>
                <strong> Q<?php echo $i;?><span style="color:#F00">*</span></strong> &nbsp;
                <label  value="<?php echo '      '.$que['question'];?>"><?php echo  '      '.$que['question'];//echo wordwrap($que['question'],90,"<br>\n");?></label>
                <br>
                <input type="hidden" name="<?php echo  'cq'.$i;?>" value="<?php echo $que['topic_code'];?>">
                <?php if($que['option1']!='')
		 {?>
                <input type="radio" name="<?php echo  'Q'.$i;?>" value="<?php echo $que['option1'];?>">
                <?php echo  '      '.$que['option1'];?><br>
                <input type="radio" name="<?php echo  'Q'.$i;?>" value="<?php echo  $que['option2'];?>">
                <?php echo    '      '.$que['option2'];?><br>
                <input type="radio" name="<?php echo  'Q'.$i;?>" value="<?php echo $que['option3'];?>">
                <?php echo   '      '.$que['option3'];?><br>
                <input type="radio" name="<?php echo  'Q'.$i;?>" value="<?php echo $que['option4'];?>">
                <?php echo   '      '.$que['option4'];?><br>
                <input type="radio" name="<?php echo  'Q'.$i;?>" value="<?php echo $que['option5'];?>">
                <?php echo   '      '.$que['option5'];?><br>
                <?php }
?>
                </b></td>
              <td><label  value="<?php echo $que['facilitator'];?>"><?php echo $que['facilitator'];?></label>
                <input type="hidden" name="facilitator" id="facilitator" value="<?php echo $que['facilitator']?>"/></td>
            </tr>
            <?php $i++;}?>
          </table>
          <br />
          <br />
          <div> <strong>Q<?php echo 11;?><span style="color:#F00">*</span></strong>&nbsp;
            <label  value="<?php echo "Utilities Used by Candidate";?>"><?php echo "Utilities Used by Candidate";?></label>
            <br />
            <br />
            &nbsp;<strong> Device Used: <br />
            </strong> &nbsp;&nbsp;
            <input type="radio" name="<?php echo  'Q11';?>" value="<?php echo 'PC';?>">
            <?php echo 'PC';?><br>
            &nbsp;&nbsp;
            <input type="radio" name="<?php echo 'Q11';?>" value="<?php echo 'LAPTOP';?>">
            <?php echo 'LAPTOP';?><br>
            &nbsp;&nbsp;
            <input type="radio" name="<?php echo  'Q11'?>" value="<?php echo 'TABLET';?>">
            <?php  echo 'TABLET';?>
            <br>
            &nbsp;&nbsp;
            <input type="radio" name="<?php echo  'Q11';?>" value="<?php echo 'MOBILE';?>">
            <?php echo 'MOBILE';?><br>
            <br />
          </div>
          <div> &nbsp;<strong> Connectivity:<br />
            </strong> &nbsp;&nbsp;
            <input type="radio" name="<?php echo 'Q12';?>" value="<?php echo 'BROADBAND';?>">
            <?php  echo 'BROADBAND';?>
            <br>
            &nbsp;&nbsp;
            <input type="radio" name="<?php echo 'Q12';?>" value="<?php echo 'MOBILE HOTSPOT';?>">
            <?php echo 'MOBILE HOTSPOT';?><br>
            &nbsp;&nbsp;
            <input type="radio" name="<?php echo 'Q12';?>" value="<?php echo 'MOBILE DATA';?>">
            <?php  echo 'MOBILE DATA';?>
            <br>
            <br />
          </div>
          <div> <strong>Q<?php echo 12;?></strong>&nbsp;
            <label  value="<?php echo "Any other comment";?>"><?php echo "Any other comment?";?></label>
            <br />
            <br />
            <textarea rows="4" cols="50" name="comment" id="comment"  maxlength="150"></textarea>
          </div>
          <br />
          <br />
          <div class="col-sm-8 col-sm-offset-5">
            <input type="submit" name="submit_f" class="btn btn-info" id="submit_f" value="Submit">
            <button onclick="Reset()" class="btn btn-default" >Reset</button>
          </div>
        </form>
      </div>
    </div>
  </section>
  <br />
  <!-- Close Get Details--> 
  
</div>
<link href="<?php echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script> 
<script src="<?php echo base_url();?>assets/admin/dist/js/jquery.date-dropdowns.js"></script> 
<script src="<?php echo base_url();?>js/jquery.validate.min.js"></script> 
<script src="<?php echo base_url();?>js/validation_blended.js?<?php echo time();?>"></script> 
<script>
var site_path = '<?php echo site_url();?>';
</script> 
<script>

function Reset() {
    location.reload();
}

$(document).ready(function(){
	try{
		
		var batch_code=$('#batch_code').val();
		jQuery.validator.addMethod("chkpass", function(value, element) {
		  return this.optional(element) || /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/i.test(value);
		}, "Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"); 
		
			jQuery.validator.addMethod("chktext", function(value, element) {
		  return this.optional(element) || /^[^*|\":<>[\]{}`\\()';&$]+$/i.test(value);
		}, "Must contain only alphanumeric characters"); 
		
			jQuery.validator.addMethod("chkmemname", function(value, element) {
		  return this.optional(element) || /^[^*|\":<>[\]{}`\\()';&$]+$/i.test(value);
		}, "Must contain only alphanumeric characters"); 
		
		var validator = $("#fact_form").validate({
			errorElement: 'div',
			rules: {
				Q1:{required: true},
				Q2:{required: true},
				Q3:{required: true},
				Q4:{required: true},
				Q5:{required: true},
				Q6:{required: true},
			    Q7:{required: true},
	            Q8:{required: true},
				Q9:{required: true},
				Q10:{required: true},
				Q11:{required: true},
				Q12:{required: true},
			comment:{maxlength: 150,chktext: true},
			mem_name:{required: true,maxlength: 50,chkmemname: true},
			mem_no:{remote:site_path+'blended_feedback/chk_memno?batch_code='+batch_code}
			},
		messages: {
			mem_no:{remote: "Membership no is invalid ."}
					},
			errorPlacement: function(error, element) {
				error.appendTo( element.parent() );
			},
			submitHandler: function(form) { 
				form.submit();
			}
		});
	}catch(err){
		console.log(err.message);
	}
	
	
	
})




</script> 
