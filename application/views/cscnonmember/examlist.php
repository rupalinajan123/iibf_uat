<!DOCTYPE html>

<html>

<head>
<?php $this->load->view('google_analytics_script_common'); ?>

  <meta charset="utf-8">

  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>IIBF - Exam List</title>

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

</head>

<body class="hold-transition login-page">

<div class="login-box">

  <div class="login-logo" style="font-size:30px;">

    <a href="javascript:void(0);"><b>IIBF</b>- <?php if(count($exam_type_name) > 0){echo $exam_type_name[0]['type'];}?> </a>

  </div>

  

  <?php //echo $this->session->userdata('adminlogincaptcha');?>

  

  <!-- /.login-logo -->

  <div class="login-box-body">

    <p class="login-box-msg">Name of Examination</p>

    <?php if(validation_errors()){?>

    <div class="callout callout-danger"><?php echo validation_errors();?></div>

    <?php }?>    
    
     
    
     <?php if($this->session->flashdata('error_message')){?>

    <div class="callout callout-danger"><?php echo $this->session->flashdata('error_message')?></div>

    <?php }?>   
   <?php 
	 if(count($exam_list) > 0)
	 {
		 $i=1;
	  foreach($exam_list as $row)
	  {?>
              <div class="form-group has-feedback">
    		    <?php echo $i?>:  
                <?php if($row['exam_category']==1)
				{?>
					 <a  href="<?php echo base_url();?>SplexamNM/memlogin/?Extype=<?php echo $Extype;?>&Mtype=<?php echo $Mtype;?>&ExId=<?php echo base64_encode($row['exam_code']);?>"><?php echo $row['description'];?>
                    </a>
                    
				<?php }
				else
				{?>
                    <a title="<?php echo $row['exam_code']; ?>" href="<?php echo base_url();?>nonreg/memlogin/?Extype=<?php echo $Extype;?>&Mtype=<?php echo $Mtype;?>&ExId=<?php echo base64_encode($row['exam_code']);?>"><?php echo $row['description'];?>
                    </a>
              <?php 
				}?>
              </div>
<?php 
	$i++;}
}else
{
	echo 'No Exams Available...';
}
?>
 </div>

  

    <!-- /.social-auth-links -->

    <!--<a href="#">I forgot my password</a><br>-->

  </div>

  <!-- /.login-box-body -->

</div>

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

	

  });

  

</script>

<script type="text/javascript">

  //$('#loginFrm').parsley('validate');

</script>

</body>

</html>

