 <style>
 .tablecontent2 {
  background-color: #ffffff;
  bottom: 5px;
  color: #000000;
  font-family: Tahoma;
  font-size: 11px;
  font-weight: normal;
  height: 10px;
  left: 5px;
  padding: 5px;
  right: 5px;
  top: 5px;
}
.img{ width:100%; height:auto; padding:15px;}
 
 </style>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Ordinary Membership Registration Print Page 
      </h1>
      <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>-->
    </section>

<form class="form-horizontal" name="usersAddForm" id="usersAddForm"  method="post"  enctype="multipart/form-data" 
    action="">
    <section class="content">
      <div class="row">
       
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info" style=" border: solid 1px #000;">
            <div class="box-header with-border">
              <h3 class="box-title">  <img src="<?php echo base_url()?>assets/images/logo1.png"></h3>
              <br>
              <span id="1001a1" style="color:#F00" class="alert">Please check Print Preview in A4 size Portrait
			format with 0.25" margin from all sides <a href="javascript:void(0);" class="linkfooter" style="color:#000" onclick="javascript:printDiv_nonMem();">(Click to Print)</a>
		</span>
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
            
             <div class="col-sm-9">
              
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Membership No :</label>
                	<div class="col-sm-5">
                  <?php echo $user_info[0]['regnumber'];?>
                    </div>
                </div>
                
                 <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Password : </label>
                	<div class="col-sm-5">
                  <?php 
					include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
					$key = $this->config->item('pass_key');
					$aes = new CryptAES();
					$aes->set_key(base64_decode($key));
					$aes->require_pkcs5();
					$decpass = $aes->decrypt(trim($user_info[0]['usrpassword']));
					echo $decpass;?>
                    </div>
                </div>
                
                
                
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">First Name : </label>
                	<div class="col-sm-5">
                  <?php 
				  $username=$user_info[0]['namesub'].' '.$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
				  $userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
				  echo $userfinalstrname;?>
                    </div>
                </div>
                
              
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Office/Residential Address for communication :</label>
                	<div class="col-sm-5">
                     <?php 
					  			if($user_info[0]['address2']!='')
								{
									 $user_info[0]['address2']=','.$user_info[0]['address2'].'*';
								}
								if($user_info[0]['address3']!='')
								{
									 $user_info[0]['address3']=','.$user_info[0]['address3'];
								}
								if($user_info[0]['address4']!='')
								{
									$user_info[0]['address4']=','.$user_info[0]['address4'];
								}
								$string1=$user_info[0]['address1'].$user_info[0]['address2'].$user_info[0]['address3'].$user_info[0]['address4'];
								$finalstr1= str_replace("*","<br>",$string1);
							   $string2=','.$user_info[0]['district'].','.$user_info[0]['city'].'*'.$user_info[0]['state_name'].','.$user_info[0]['pincode'];
							   $finalstr2=str_replace("*",",<br>",$string2);
							   $useradd=$finalstr1.$finalstr2;
							   echo $useradd;?>
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Date of Birth :</label>
                	<div class="col-sm-2">
                    <?php echo date('d-m-Y',strtotime($user_info[0]['dateofbirth']));?>
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Gender :</label>
       		    <div class="col-sm-2">
					<?php if($user_info[0]['gender']=='female'){echo 'Female';}?>
                   <?php if($user_info[0]['gender']=='male'){echo  'Male';}?>
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Qualification :</label>
                	<div class="col-sm-4">
                 	  <?php if($user_info[0]['qualification']=='U'){echo  'Under Graduate';}?>
                      <?php if($user_info[0]['qualification']=='G'){echo  'Graduate';}?>
						<?php if($user_info[0]['qualification']=='P'){echo  'Post Graduate';}?>
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">specify : </label>
                <div class="col-sm-5">
				<?php echo $qualification[0]['qname'];?>
             	</div>
                </div>
                
              <div class="form-group">
        <label for="roleid" class="col-sm-3 control-label">Email :</label>
            <div class="col-sm-5">
            <?php echo $user_info[0]['email']?>
            </div>
        </div>
        
        
        <div class="form-group">
        <label for="roleid" class="col-sm-3 control-label">Mobile :</label>
            <div class="col-sm-5">
            <?php echo $user_info[0]['mobile'];?>
            </div>
        </div>
        
        
         <div class="form-group">
        <label for="roleid" class="col-sm-3 control-label">Select Id Proof :</label>
        <div class="col-sm-5">
        <?php 
		if(isset($idtype_master[0]['name']))
		{
		echo $idtype_master[0]['name'];
		}?>
        </div>
        </div>
        
        <div class="form-group">
        <label for="roleid" class="col-sm-3 control-label">ID No. :</label>
        <div class="col-sm-5">
        <?php echo $user_info[0]['idNo'];?>
        </div>
        </div>
        
        
         <div class="form-group">
        <label for="roleid" class="col-sm-3 control-label">Aadhar Card Number :</label>
        <div class="col-sm-5">
        <?php echo $user_info[0]['aadhar_card'];?>
        </div>
        </div>
    
        
                
                
                   <div class="form-group">
        <label for="roleid" class="col-sm-3 control-label">ID Proof :</label>
        <div class="col-sm-5">
        <img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('cscnmregnumber'),'pr');?><?php echo '?'.time(); ?>"  height="180" width="100">
        </div>
        </div>
        
            <div class="form-group">
        <label for="roleid" class="col-sm-3 control-label">Signature :</label>
        <div class="col-sm-5">
  		<img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('cscnmregnumber'),'s');?><?php echo '?'.time(); ?>" height="100" width="100">
        </div>
        </div>
        
        
        	<div class="form-group">
        <label for="roleid" class="col-sm-3 control-label">Date :</label>
        <div class="col-sm-5">
        <?php echo date('d-m-Y h:i:s A',strtotime($user_info[0]['createdon']));?>
        </div>
        </div>
                </div>
                <div class="col-sm-3">

                	<label for="roleid" class="col-sm-3 control-label"><img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('cscnmregnumber'),'p');?><?php echo '?'.time(); ?>" height="100" width="100" ></label>
        
                </div>
          </div>
        </div> 
        </div>
      </div>
    </section>
    </form>
  </div>
  
  <!-- Div to print  -->
<div class="content-wrapper" id="print_div" style="display: none;">
    <!-- Content Header (Page header) -->
     <div  style=" background: #fff ;
    border: 1px solid #000; padding:25px;
  ">
   <table width="100%" cellspacing="0" cellpadding="10" border="0" align="center" >         

<tbody>
<tr> <td colspan="4" align="left">&nbsp;</td> </tr>

<tr>

	<td colspan="4" align="center" height="25">
		<span id="1001a1" class="alert">
		</span>
	</td>
</tr>

<tr> 
	<td colspan="4"  height="1"><img src="<?php echo base_url()?>assets/images/logo1.png" class="img"></td>
</tr>
		   
<tr>
	<td colspan="4">
<div style=" background:#000; width:100%; height:1px; margin-bottom:10px;"></div>
	<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center"  style=" position:relative;" class="tablecontent2" >
					<tbody><tr>
			<td class="tablecontent2" width="51%">Membership No : </td>
			<td colspan="1" class="tablecontent2" width="28%" valign="middle" nowrap="nowrap" align="left"> <?php echo $user_info[0]['regnumber'];?></td>
			<td class="tablecontent" valign="top">
			<img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('cscnmregnumber'),'p');?>" height="100" width="100" style=" position:absolute; right:15px; top:0px;" >
			</td>
		</tr>
				<tr>
			<td class="tablecontent2">Password :</td>
			<td colspan="2" class="tablecontent2" nowrap="nowrap"><?php echo $decpass;?> </td>
		</tr>

		<tr>
			<td class="tablecontent2">Full Name :</td>
			<td colspan="2" class="tablecontent2" nowrap="nowrap">
				<?php 
				  $username=$user_info[0]['namesub'].' '.$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
				  $userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
				  echo $userfinalstrname;?>
			</td>
		</tr>

	

		<tr>
			<td class="tablecontent2" width="51%">Office/Residential Address for communication :</td>
			<td colspan="3" class="tablecontent2" width="49%" nowrap="nowrap">
				<?php echo wordwrap($useradd,50,"<br>\n");?>			</td>
		</tr>
				
		<tr>
			<td class="tablecontent2">Date of Birth :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap"><?php echo date('d-m-Y',strtotime($user_info[0]['dateofbirth']));?> </td>
		</tr>	

		<tr>
			<td class="tablecontent2">Gender :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap"><?php if($user_info[0]['gender']=='female'){echo 'Female';}?>
                   <?php if($user_info[0]['gender']=='male'){echo  'Male';}?> </td>
		</tr>			  			
				
		<tr>
			<td class="tablecontent2">Qualification :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap"> <?php if($user_info[0]['qualification']=='U'){echo  'Under Graduate';}?>
                      <?php if($user_info[0]['qualification']=='G'){echo  'Graduate';}?>
						<?php if($user_info[0]['qualification']=='P'){echo  'Post Graduate';}?> </td>
		</tr>

		<tr>
			<td class="tablecontent2">Specify :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap"><?php echo $qualification[0]['qname'];?> </td>
		</tr>

		<tr>
			<td class="tablecontent2">Date of Joining :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap"> <?php echo date('d-m-Y',strtotime($user_info[0]['dateofjoin']));?> </td>
		</tr>

		<tr>
			<td class="tablecontent2">Email :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap"><?php echo $user_info[0]['email']?> </td>
		</tr>
				
		
		<tr>
			<td class="tablecontent2">Mobile :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap"><?php echo $user_info[0]['mobile'];?> </td>
		</tr>

		<tr>
			<td class="tablecontent2">ID Proof :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap"> <?php echo $idtype_master[0]['name'];?></td>
		</tr>

		<tr>
			<td class="tablecontent2">ID No :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap"><?php echo $user_info[0]['idNo'];?></td>
		</tr>
        
        
        <tr>
			<td class="tablecontent2">Aadhar Card Number :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap"><?php echo $user_info[0]['aadhar_card'];?></td>
		</tr>
				

		<tr>
			<td class="tablecontent2">ID Proof :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap"><img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('cscnmregnumber'),'pr');?>"  height="180" width="100"></td>
		</tr>

		<tr>
			<td class="tablecontent2">Signature :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap"><img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('cscnmregnumber'),'s');?>" height="100" width="100"></td>
		</tr>

		<tr>
			<td class="tablecontent2">Date :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">
				<?php echo date('d-m-Y h:i:s A',strtotime($user_info[0]['createdon']));?>		</td>
		</tr>

		</tbody></table>
	</td>
</tr>
	
</tbody></table>
</div>
  </div>



<script>
function printDiv_nonMem(divName) {
     var printContents = document.getElementById('print_div').innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>