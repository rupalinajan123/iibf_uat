<!DOCTYPE html>

<html>

<head>
<?php $this->load->view('google_analytics_script_common'); ?>
<script>var site_url="<?php echo base_url();?>";</script>
  <meta charset="utf-8">

  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>IIBF - Member Login</title>

  <!-- Tell the browser to be responsive to screen width -->

  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <!-- Bootstrap 3.3.6 -->

  <link rel="stylesheet" href="<?php echo  base_url()?>assets/admin/bootstrap/css/bootstrap.min.css">

  <!-- Font Awesome -->

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">

  <!-- Ionicons -->

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

  <!-- Theme style -->

  <link rel="stylesheet" href="<?php echo  base_url()?>assets/admin/dist/css/AdminLTE.min.css">

  <!-- iCheck -->

  <link rel="stylesheet" href="<?php echo  base_url()?>assets/admin/plugins/iCheck/square/blue.css">

  <script src="<?php echo base_url()?>assets/admin/plugins/jQuery/jQuery-2.2.0.min.js"></script>





  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->

  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

  <!--[if lt IE 9]>

  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>

  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

  <![endif]-->
  <style>
	ul {
	  margin-top: -1px;
	  padding: 5px 10px 5px 30px;
	  border: 1px solid #1287c0;
	  background-color: #dcf1fc;
	}
	ul li {
		padding: 2px 0;
	}
	.login-box-body a {
		line-height: 20px;
	}
	.short_logo {
		display: inline-block;
		float: left;
		margin: 0 0 0 20px;
	}
	.login-logo a {
		color: #619fda;
		font-weight: 600;
		text-align: center;
		font-size: 28px;
		line-height: 24px;
		display: inline-block;
	}
	.login-logo a small {
		font-size: 14px;
		color: #1d1d1d;
	}
	.form-control {
		width: 50%;
	}
	label {
		line-height: 18px;
		font-weight: normal;
	}
	form {
		padding: 10px;
		border: 1px solid #1287c0;
		background-color: #dcf1fc;
	}
	.form-group {
		margin-bottom: 10px;
	}
	a.forget {
		color: #9d0000;
		line-height: 24px;
	}
	a.forget:hover {
		color: #9d0000;
		text-decoration: underline;
	}
	.btn.btn-flat {
		min-height: 34px;
		background-color: #015171;
	}
	.red {
		color: #f00;
	}

  </style>

</head>

<body class="hold-transition login-page">

<div class="login-box">

  <div class="login-logo">
    <div class="short_logo"><img src="<?php echo base_url();?>assets/images/iibf_logo_short.png"></div>
    <div><a>INDIAN INSTITUTE OF BANKING &amp; FINANCE<br><small>(An ISO 21001:2018 Certified)</small></a></div>

  </div>

  

  <?php //echo $this->session->userdata('adminlogincaptcha');?>

  

  <!-- /.login-logo -->

  <div class="login-box-body">

    <?php if(validation_errors()){?>

    <div class="callout callout-danger"><?php echo validation_errors();?></div>

    <?php }?>    
    
     <?php if($error){?>

    <div class="callout callout-danger" style="color:#FFF !important"><?php echo $error;?></div>

    <?php }?>    
    
     <?php if($this->session->flashdata('error_message')){?>

    <div class="callout callout-danger"><?php echo $this->session->flashdata('error_message')?></div>

    <?php }?>   

   <?php //echo form_open()?>

   <form action="" method="post" name="loginFrm" id="loginFrm">

      <div class="form-group has-feedback clearfix">
<label for="text" class="col-md-6">Membership No. <span class="red">*</span> :</label>
        <input type="text" class="form-control" placeholder="User name" name="Username" value='<?php echo set_value('Username'); ?>' autocomplete="off" required>

        <!--<span class="glyphicon glyphicon-envelope form-control-feedback"></span>-->

      </div>

   <!--   <div class="form-group has-feedback">
        <input type="password" class="form-control" name="Password" value='<?php echo set_value('Password'); ?>'  placeholder="Password" autocomplete="off" required>
	   </div>-->
<div class="form-group has-feedback clearfix">
<label for="text" class="col-md-6">Type the exact characters you see in the picture <span class="red">*</span>.</label>
        <input type="text" class="form-control" name="code" autocomplete="off"  style="padding-right:10px !important" required>
       <label>     <div id="captcha_img"><?php echo $image;?></div></label>
        <label for="text" class="col-md-7"></label>
        <div class="form-group has-feedback clearfix">
            <a href="javascript:void(0);" id="new_captcha" class="forget" >Change Image</a>
        </div>
        </div>


        <div class="row">

        <div class="col-xs-4">
		  <input id="Submit" class="btn btn-primary btn-block btn-flat" name="btnLogin" value="Submit" type="submit">
      </div>
      
      <div class="col-xs-4">
	      <input class="btn btn-primary btn-block btn-flat" onClick="reloadpage()" name="Reset" value="Reset" type="reset">
      </div>
      
      <div class="col-xs-4">
		<a onClick="window.history.go(-1); return false;" class="btn btn-primary btn-block btn-flat">Back</a>
      </div>
      
        <span style="color:#F00;"></span> </div>
        <span style="color:#F00;"><?php //echo @$error." ".validation_errors(); ?></span> 
     <!-- /.col -->
  </form>
  
  
  
    <ul>
	    
        <li><a class="disability forget" href="<?php echo base_url()?>login/forgotpassword/">Forgot Password/Get password Click Here</a></li>
	<?php 
		$obj = new OS_BR();
		if($obj->showInfo('browser')=='Internet Explorer')
		{?>
		    <div  style="color:#F00">
              Portal best viewed in recommended browsers (Mozilla Firefox or Google chrome latest versions) for error free performance. It is suggested Internet explorer not to be used to avoid issues.</div>
      <?php
        }?>	
        </ul>	
        
        <?php
        	$exarr = array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB'));
			 if(in_array(base64_decode($this->input->get('ExId')),$exarr)){ 
		?>	
        

		<!--<ul>

     <p style="text-align:center"><strong>Indian Institute of Banking & Finance</strong></p>
<p>Due to non-availability of appropriate venues at Centres, candidates are requested to select nearest centre as indicated below:</p>
<p>&nbsp;</p>
<table width="100%" cellspacing="0" cellpadding="0" border="1">
  <tbody>
    
    <tr>
      <td valign="top"><p align="center">ANANTAPUR</p></td>
       <td valign="top"><p align="center">ANDHRA PRADESH</p></td>
    </tr>
    
    <tr>
      <td valign="top"><p align="center">PALAKOL</p></td>
       <td valign="top"><p align="center">ANDHRA PRADESH</p></td>
    </tr>
    
    <tr>
      <td valign="top"><p align="center">SRIKAKULAM</p></td>
       <td valign="top"><p align="center">ANDHRA PRADESH</p></td>
    </tr>
    
    <tr>
      <td valign="top"><p align="center">GUNTUR</p></td>
       <td valign="top"><p align="center">ANDHRA PRADESH</p></td>
    </tr>
    
    <tr>
      <td valign="top"><p align="center">SAMASTIPUR</p></td>
       <td valign="top"><p align="center">BIHAR</p></td>
    </tr>
    
    <tr>
      <td valign="top"><p align="center">DEOGHAR</p></td>
       <td valign="top"><p align="center">JHARKHAND</p></td>
    </tr>
    
    
    
  </tbody>
</table>
</ul>-->

<ul>

     <p style="text-align:center"><strong>Indian Institute of Banking & Finance</strong></p>
<p>Due to non-availability of appropriate venues at Centres, candidates are requested to select nearest centre as indicated below:</p>
<p>&nbsp;</p>
<table width="100%" cellspacing="0" cellpadding="0" border="1">
  <tbody>
    <tr>
      <td valign="top"><p align="center"><strong>Centre</strong></p></td>
       <td valign="top"><p align="center"><strong>Nearest Centre</strong></p></td>
    </tr>
    <tr>
      <td valign="top"><p align="center">MIDNAPORE</p></td>
       <td valign="top"><p align="center">KOLKATTA</p></td>
    </tr>
    <tr>
      <td valign="top"><p align="center">NAHARLAGUN(ITANAGAR)</p></td>
       <td valign="top"><p align="center">North Lakhimpur </p></td>
    </tr>
    <tr>
      <td valign="top"><p align="center">KALYAN</p></td>
       <td valign="top"><p align="center">THANE</p></td>
    </tr>
  </tbody>
</table>
</ul>



        <?php }?>	
        
        <?php 
			$dipexarr = array(17700,1750,16000,810,590,5800,3400,200);
			 if(in_array(base64_decode($this->input->get('ExId')),$dipexarr)){
		?>
        <!--<ul>

     <p style="text-align:center"><strong>Indian Institute of Banking & Finance</strong></p>
<p>Due to non-availability of venues, the below mentioned centres have not yet commenced but is expected to start shortly. Concerned candidates are requested to kindly retry in some time</p>
<p>&nbsp;</p>
<table width="100%" cellspacing="0" cellpadding="0" border="1">
  <tbody>
    
    <tr>
      <td valign="top"><p align="center">Sl. No.</p></td>
      <td valign="top"><p align="center">IIBF Centre Code</p></td>
       <td valign="top"><p align="center">Centre Name</p></td>
    </tr>
    <tr>
      <td valign="top"><p align="center">1</p></td>
      <td valign="top"><p align="center">319</p></td>
       <td valign="top"><p align="center">NASHIK</p></td>
    </tr>
    <tr>
      <td valign="top"><p align="center">2</p></td>
      <td valign="top"><p align="center">733</p></td>
       <td valign="top"><p align="center">DAHISAR</p></td>
    </tr>
    <tr>
      <td valign="top"><p align="center">3</p></td>
      <td valign="top"><p align="center">322</p></td>
       <td valign="top"><p align="center">PUNE</p></td>
    </tr>
    <tr>
      <td valign="top"><p align="center">4</p></td>
      <td valign="top"><p align="center">300</p></td>
       <td valign="top"><p align="center">AMRAVATI</p></td>
    </tr>
    <tr>
      <td valign="top"><p align="center">5</p></td>
      <td valign="top"><p align="center">119</p></td>
       <td valign="top"><p align="center">RAJKOT</p></td>
    </tr>
    <tr>
      <td valign="top"><p align="center">6</p></td>
      <td valign="top"><p align="center">120</p></td>
       <td valign="top"><p align="center">SURAT</p></td>
    </tr>
  </tbody>
</table>
</ul>-->
        
        <?php }?>
        
        <?php
        	$exarr = array($this->config->item('examCodeCaiib'),62,$this->config->item('examCodeCaiibElective63'),64,65,66,67,$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'),72);
			 if(in_array(base64_decode($this->input->get('ExId')),$exarr)){
		?>	
        <ul>

     <p style="text-align:center"><strong>Indian Institute of Banking & Finance</strong></p>
<p>Due to non-availability of appropriate venues at Centres, candidates are requested to select nearest centre</p>
<p>&nbsp;</p>
<table width="100%" cellspacing="0" cellpadding="0" border="1">
  <tbody>
    
    <tr>
      <td valign="top"><p align="center">ANANTNAG</p></td>
      <td valign="top"><p align="center">NORTH</p></td>
       <td valign="top"><p align="center">JAMMU & KASHMIR</p></td>
    </tr>
    <tr>
      <td valign="top"><p align="center">LEH</p></td>
      <td valign="top"><p align="center">NORTH</p></td>
       <td valign="top"><p align="center">JAMMU & KASHMIR</p></td>
    </tr>
    <tr>
      <td valign="top"><p align="center">BHAWANIPATNA</p></td>
      <td valign="top"><p align="center">EAST</p></td>
       <td valign="top"><p align="center">ORISSA</p></td>
    </tr>
    
    
  </tbody>
</table>
</ul>
        <?php }?>
		
    <!-- /.social-auth-links -->

    <!--<a href="#">I forgot my password</a><br>-->

  </div>

  <!-- /.login-box-body -->

</div>

<!-- /.login-box -->

<?php 
if(base64_decode($this->input->get('ExId'))==$this->config->item('examCodeJaiib') || base64_decode($this->input->get('ExId'))==$this->config->item('examCodeDBF'))
	  {?>
<!--<div id="myModal" class="modal fade">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

<h3 class="modal-title" align="center">INDIAN INSTITUTE OF BANKING & FINANCE</h3>
<br>

                <h4 class="modal-title">Re-schedule of JAIIB/DB&F examinations (May-2019) due to Lok Sabha Election.</h4>

            </div>

            <div class="modal-body">

                <p style="font-size:16px">JAIIB/DB&F examination was scheduled on <strong>5th May, 12th May and 19th May 2019</strong>. <br>
<br>
Due to recent announcement of Lok Sabha election schedule, the examination schedule dated <strong>12th May and 19th May 2019</strong> is coinciding with the election date in 90 Centre/City. 

Therefore, Institute has decided to reschedule exam date as mentioned below for the those the affected 90 centers.
</p>

                

                  <ol style="font-size:16px">
  <li>The Exam scheduled on <strong>12-May-2019</strong> is re-scheduled on <strong>25-May 2019 (4th Saturday)</strong>  </li>
 <li>The Exam scheduled on <strong>19-May 2019</strong> is re-scheduled on <strong>26-May 2019 (4th Sunday)</strong></li>

 
</ol> 
<a href="<?php echo base_url()?>uploads/Election_Affected_Centre_List.pdf" target="_blank" style="font-size:16px">
<strong>Click here to view centre list for which schedule is changed</strong></a><br>
<p  style="font-size:16px">
<br>
For all other Centre/City the examination will be conducted as per existing scheduled
</p>
                       
					  <p style="color:#FF0000; font-size:16px" >  Candidates are advised to download Revised Admit letter from the Institute website one week before the exam date. </p>
  
                    

                    <p style="font-size:16px"> 
                    <input type="checkbox" id="agree" value="yes" name="agree">&nbsp; I agree to abide by changed schedule 
                    <!--<input type="checkbox" name="agreechk" id="agreechk" value="yes">&nbsp; I agree to abide by changed scheduled -->
                    </p>
  <br>


                  <!-- <center> <button type="submit" class="btn btn-primary" id="close">Submit</button>  </center>-->

               

            </div>

        </div>

    </div>

</div>-->

<?php }?>

<link href="<?php echo base_url()?>assets/css/parsley.css" rel="stylesheet">

<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script>


<!-- jQuery 2.2.0 -->

<!-- Bootstrap 3.3.6 -->

<script src="<?php echo  base_url()?>assets/admin/bootstrap/js/bootstrap.min.js"></script>

<!-- iCheck -->

<script src="<?php echo  base_url()?>assets/admin/plugins/iCheck/icheck.min.js"></script>



<script>

  $(function () {

    $('input').iCheck({

      checkboxClass: 'icheckbox_square-blue',

      radioClass: 'iradio_square-blue',

      increaseArea: '20%' // optional

    });

	$('#new_captcha').click(function(event){
        event.preventDefault();
    $.ajax({
 		type: 'POST',
 		url: site_url+'Applyexam/generatecaptchaajax/',
 		success: function(res)
 		{	
 			if(res!='')
 			{$('#captcha_img').html(res);
 			}
 		}
    });
	});

  });

  

</script>
<!--POPUP MESSAGE FOR jaiib BY POOJA GODSE-->
<script type="text/javascript">
/*$(document).ready(function(){
	var options = {
					"backdrop" : "static",
					"show" : true,
					"keyboard" : false	
				}
	$("#myModal").modal(options);
	$("#close").click(function(){ 
		 if($("#agree").prop('checked') == true){
			$('#myModal').modal('hide');
		 }else{
			alert('Please check I agree checkbox.');	
		 }
	});
});
	*/
</script>
<!--end  POPUP MESSAGE FOR jaiib BY POOJA GODSE--><script type="text/javascript">

  $('#loginFrm').parsley('validate');

</script>

<script>
function reloadpage() {
	var url      = window.location.href;  
	
  //location.reload();
   //window.location.reload();
   window.location.href = url;
}
</script>

</body>

</html>

