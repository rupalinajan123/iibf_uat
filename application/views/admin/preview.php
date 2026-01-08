<?php $this->load->view('admin/includes/header');?>
<?php $this->load->view('admin/includes/sidebar');?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
     <!-- <h1>
      Please go through the given detail, correction may be made if necessary.
      </h1>-->
      <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>-->
    </section>
	<form class="form-horizontal" name="usersAddForm" id="usersAddForm"  method="post"  enctype="multipart/form-data" 
    action="<?php echo base_url()?>register/member/">
    <section class="content">
      <div class="row">
       
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">
              	 <img src="<?php echo base_url()?>assets/images/logo1.png"></h3>
              <br>
              <span id="1001a1" style="color:#F00" class="alert">Please check Print Preview in A4 size Portrait
			format with 0.25" margin from all sides <a href="javascript:void(0);" class="linkfooter" style="color:#000" onclick="javascript:printDiv();">(Click to Print)</a>
		</span></h3>
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
                  <?php echo $regData['regnumber'];?>
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
					$decpass = $aes->decrypt(trim($regData['usrpassword']));
					echo $decpass;?>
                    </div>
                </div>
               
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Full Name : </label>
                	<div class="col-sm-5">
                  <?php 
				  $username=$regData['namesub'].' '.$regData['firstname'].' '.$regData['middlename'].' '.$regData['lastname'];
				  $userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
				  echo $userfinalstrname;?>
                    </div>
                </div>
              
                <?php if($regData['registrationtype']!='NM'){ ?>
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Name as to appear on Card : </label>
                	<div class="col-sm-5">
                     <?php echo $regData['displayname'];?>
                    </div>
                </div>
                <?php } ?>
                </div>
                <div class="col-sm-3">
                <?php $actual_photo = get_img_name($regData['regnumber'],'p');?>
                    <label for="roleid" class="col-sm-3 control-label"><img src="<?php echo base_url();?><?php echo $actual_photo;?><?php echo '?'.time(); ?>" height="100" width="100" ></label>
        
                </div>
                <br />
                   <br />
                
              <div class="col-sm-9"> 
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Office/Residential Address for communication : </label>
                	<div class="col-sm-8">
                    <?php /* echo $regData['address1']."<br>".$regData['address2']."<br>".$regData['address3'].", ".$regData['address4']."<br>".$regData['district'].", ".$regData['city']."<br>".$regData['state_name']." - ".$regData['pincode'];*/ ?>
                    
                    <?php 
							if($regData['address2']!='')
							{
								 $regData['address2']=','.$regData['address2'].'*';
							}
							if($regData['address3']!='')
							{
								 $regData['address3']=','.$regData['address3'];
							}
							if($regData['address4']!='')
							{
								$regData['address4']=','.$regData['address4'];
							}
							$string1=$regData['address1'].$regData['address2'].$regData['address3'].$regData['address4'];
							$finalstr1= str_replace("*","<br>",$string1);
						   	$string2=','.$regData['district'].','.$regData['city'].'*'.$regData['state_name'].','.$regData['pincode'];
						   	$finalstr2=str_replace("*",",<br>",$string2);
						   	$useradd=$finalstr1.$finalstr2;
						   	echo $useradd;
					?>
                    
                    </div>
                  
                </div>
                
               
              
                 <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Date of Birth : </label>
                	<div class="col-sm-2">
                    <?php echo date('d-m-Y',strtotime($regData['dateofbirth']));?>
                    </div>
                </div>
                
                
                    <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Gender : </label>
       
                    <div class="col-sm-5">
                    	<?php if($regData['gender']!=''){echo  ucfirst($regData['gender']);} ?>
                    </div>
                </div>
                
                
                  <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Qualification : </label>
                	<div class="col-sm-4">
                 	  <?php if($regData['qualification']=='U'){echo  'Under Graduate';}?>
                      <?php if($regData['qualification']=='G'){echo  'Graduate';}?>
						<?php if($regData['qualification']=='P'){echo  'Post Graduate';}?>
                    </div>
                </div>
                 
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Specify : </label>
                <div class="col-sm-5">
				<?php echo $regData['q_name'];?>
             	</div>
                </div>

                <!-- Start: OLD BCBF Extra fields added by Anil on 30 Sep 2024 -->
                <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Name of Bank where working as BC</label>
                    <div class="col-sm-5 ">
                     <?php 
                     $get_bank_inst_details=$this->master_model->getRecords('bcbf_old_exam_institute_master',array('institute_id'=>$regData['name_of_bank_bc']));
                     echo $get_bank_inst_details[0]['institute_name'];?>
                     <div id="error_dob"></div>
                    </div>
                </div> 
                <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Date of commencement of operations/joining as BC</label>
                    <div class="col-sm-5 ">
                     <?php echo ($regData['date_of_commenc_bc'] != "" ? date("d-m-Y",strtotime($regData['date_of_commenc_bc'])) : '');?>
                     <div id="error_dob"></div>
                    </div>
                </div>  
                <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Bank BC ID No</label>
                    <div class="col-sm-5 ">
                     <?php echo $regData['ippb_emp_id'];?>
                     <div id="error_dob"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Bank BC ID Card</label>
                    <?php //print_r($regData); ?>
                    <div class="col-sm-5 ">
                        <label for="roleid" class="col-sm-2 control-label"><img src="<?php echo base_url('uploads/empidproof/'.$regData['bank_bc_id_card'].'?'.time());?>"  height="100" width="100"></label>
                        <div id="error_dob"></div>
                    </div>
                </div>
                <!-- End: OLD BCBF Extra fields added by Anil on 30 Sep 2024 -->
                
                
                
             <?php if($regData['registrationtype']!='NM'){ ?>
                <div class="form-group">
                 <label for="roleid" class="col-sm-3 control-label">Bank/Institution working : </label>
                  <div class="col-sm-5" >
                   <?php echo $regData['institute_name'];?>
                </div>
                </div>
                
                <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Branch/Office : </label>
                    <div class="col-sm-5">
                    <?php echo $regData['office'];?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Designation : </label>
                    <div class="col-sm-5"  style="display:block" id="">
                    <?php echo $regData['dname'];?>
                    </div>
                </div>
                
                 <?php if($regData['registrationtype'] == 'O'){?>         
                <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Date of joining Bank/Institution :  </label>
                    <div class="col-sm-3">
                    <?php echo date('d-m-Y',strtotime($regData['dateofjoin']));?>
                    </div>
                </div>
                <?php }?>
                
         <?php } ?>
                
        <div class="form-group">
        <label for="roleid" class="col-sm-3 control-label">Email : </label>
            <div class="col-sm-5">
            <?php echo $regData['email']?>
            </div>
        </div>  
        
        
        <div class="form-group">
        <label for="roleid" class="col-sm-3 control-label">Phone : </label>
            <div class="col-sm-3">
              STD Code :
              <?php echo $regData['stdcode'];?>
            
            </div>
            <div class="col-sm-4">
            Phone No :
            <?php echo $regData['office_phone'];?>
            </div>
        </div>
         
        <div class="form-group">
        <label for="roleid" class="col-sm-3 control-label">Mobile : </label>
            <div class="col-sm-5">
            <?php echo $regData['mobile'];?>
            </div>
        </div> 
        
          <div class="form-group">
        <label for="roleid" class="col-sm-3 control-label">Aadhar Card No. : </label>
        <div class="col-sm-5">
        <?php echo $regData['aadhar_card'];?>
        </div>
        </div> 
        
         <div class="form-group">
        <label for="roleid" class="col-sm-3 control-label">Id Proof : </label>
        <div class="col-sm-5">
        <?php echo $regData['id_name'];?>
        </div>
        </div>
                
        <?php if($regData['registrationtype'] == 'NM' || $regData['registrationtype'] == 'DB'){?>         
        <div class="form-group">
        <label for="roleid" class="col-sm-3 control-label">ID No. : </label>
        <div class="col-sm-5">
        <?php echo $regData['idNo'];?>
        </div>
        </div>
        <?php } ?>
        
      
        
        <div class="form-group">
        <label for="roleid" class="col-sm-3 control-label">Transaction No : </label>
        <div class="col-sm-5">
        <?php echo $regData['transaction_no'];?>
        </div>
        </div> 
        
        <div class="form-group">
        <label for="roleid" class="col-sm-3 control-label">Transaction Date : </label>
        <div class="col-sm-5">
        
        <?php
		 if($regData['transaction_no']!='')
		 {
				if($regData['date']!='' || $regData['date']!='0000-00-00 00:00:00')
				{
					 echo date('d-m-Y',strtotime($regData['date']));
				}else
				{
					echo '-';
				}
		}else
		 {
			 	echo '-';
		 }?>
       </div>
        </div> 
        
        <div class="form-group">
        <label for="roleid" class="col-sm-3 control-label">Transaction Amount : </label>
        <div class="col-sm-5">
        <?php echo $regData['amount'];?>
        </div>
        </div> 
                
        <div class="form-group">
        <label for="roleid" class="col-sm-3 control-label">I agree to receive the Annual report from the Institute in a softcopy, at my registered email ID, in place of physical copy : </label>
        <div class="col-sm-2">
        	<?php if($regData['optnletter']=='Y'){echo  'Yes';}else{echo "No";} ?>
        
        </div>
        </div>        
       
       <div class="form-group">
        <label for="roleid" class="col-sm-3 control-label">Bank Employee Id : </label>
        <div class="col-sm-5">
        <?php echo $regData['bank_emp_id'];?>
        </div>
        </div> 
               
       <div class="form-group">
        <label for="roleid" class="col-sm-3 control-label">ID Proof : </label>
        <?php $actual_idproof = get_img_name($regData['regnumber'],'pr');?>
        <label for="roleid" class="col-sm-3 control-label"><img src="<?php echo base_url();?><?php echo $actual_idproof;?><?php echo '?'.time(); ?>"  height="100" width="100"></label> 
        </div> 
         
        <div class="form-group">
        <label for="roleid" class="col-sm-3 control-label">Signature : </label>
        <?php $actual_sign = get_img_name($regData['regnumber'],'s');?>
        <label for="roleid" class="col-sm-3 control-label"><img src="<?php echo base_url();?><?php echo $actual_sign;?><?php echo '?'.time(); ?>" height="100" width="100"></label>
        </div> 

        <?php if(in_array($regData['excode'], array(1009))){ ?>
            <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Employment Proof : </label>
                <?php $actual_empr = get_img_name($regData['regnumber'],'empr');?>
                <label for="roleid" class="col-sm-3 control-label"><img src="<?php echo base_url();?><?php echo $actual_empr;?><?php echo '?'.time(); ?>" height="100" width="100"></label>
            </div>
            <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Declaration Form : </label>
                <?php $actual_declaration = get_img_name($regData['regnumber'],'declaration');?>
                <label for="roleid" class="col-sm-3 control-label"><img src="<?php echo base_url();?><?php echo $actual_declaration;?><?php echo '?'.time(); ?>" height="100" width="100"></label>
            </div>
        <?php }else if($regData['registrationtype'] == 'O'){ ?>   
        <div class="form-group">
        <label for="roleid" class="col-sm-3 control-label">Declaration: </label>
        <?php $actual_declaration = get_img_name($regData['regnumber'],'declaration');?>
        <label for="roleid" class="col-sm-3 control-label"><img src="<?php echo base_url();?><?php echo $actual_declaration;?><?php echo '?'.time(); ?>"  height="100" width="100"></label> 
        </div> 
        <?php } ?>
       
       <div class="form-group">
            <label for="roleid" class="col-sm-3 control-label">Date : </label>
            <div class="col-sm-5">
            <?php echo date('d-m-Y h:i:s A',strtotime($regData['createdon']));?>
            </div>
        </div> 
                
      </div>
     </div> 
       
      </div>
      </div>
      
    </section>
    </form>
  </div>
  
<div class="content-wrapper" id="print_div" style="display: none;">
    <!-- Content Header (Page header) -->
<div  style=" background: #fff ;border: 1px solid #000; padding:25px;width:90%;margin-left:5%;  ">
<table width="100%" cellspacing="0" cellpadding="10" border="0" align="center" >         

<tbody>
<!--<tr> <td colspan="4" align="left">&nbsp;</td> </tr>

<tr>

	<td colspan="4" align="center" height="25">
		<span id="1001a1" class="alert">
		</span>
	</td>
</tr>-->

<tr> 
	<td colspan="4"  height="1"><img src="<?php echo base_url()?>assets/images/logo1.png" class="img"></td>
</tr>
<tr>
	<td>
    	<div style="color: #F00; text-align: center;">Please check Print Preview in A4 size Portrait
			format with 0.25" margin from all sides <a href="javascript:void(0);" class="linkfooter" style="color:#000" onclick="javascript:printDiv();">(Click to Print)</a>
        </div>
    </td>
</tr>
		   
<tr>
	<td colspan="4">
<div style=" background:#000; width:100%; height:1px; margin-bottom:10px;"></div>
	<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center"  style="position:relative;" class="tablecontent2" >
					<tbody><tr>
			<td class="tablecontent2" width="45%">Membership No : </td>
			<td colspan="1" class="tablecontent2" width="45%" valign="middle" nowrap="nowrap" align="left"> <?php echo $regData['regnumber'];?></td>
			<td width="10%" valign="top" class="tablecontent">
			 <?php $actual_photo = get_img_name($regData['regnumber'],'p');?>
            <img src="<?php echo base_url();?><?php echo $actual_photo;?><?php echo '?'.time(); ?>" height="100" width="100" style=" position:absolute; right:15px; top:0px;" >
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
				  $username=$regData['namesub'].' '.$regData['firstname'].' '.$regData['middlename'].' '.$regData['lastname'];
				  $userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
				  echo $userfinalstrname;?>
			</td>
		</tr>

		<?php if($regData['registrationtype']!='NM'){ ?>
        <tr>
			<td class="tablecontent2">Name as to appear on Card :</td>
			<td colspan="2" class="tablecontent2" nowrap="nowrap">  <?php echo $regData['displayname'];?> </td>
		</tr>				
		<?php } ?>

		<tr>
			<td class="tablecontent2" width="45%">Office/Residential Address <br /> for communication :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">
				<?php 
								
							   echo $useradd;?>		</td>
		</tr>
				
		<tr>
			<td class="tablecontent2">Date of Birth :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap"><?php echo date('d-m-Y',strtotime($regData['dateofbirth']));?> </td>
		</tr>	

		<tr>
			<td class="tablecontent2">Gender :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap"><?php if($regData['gender']=='female'){echo 'Female';}?>
                   <?php if($regData['gender']=='male'){echo  'Male';}?> </td>
		</tr>			  			
				
		<tr>
			<td class="tablecontent2">Qualification :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap"> <?php if($regData['qualification']=='U'){echo  'Under Graduate';}?>
                      <?php if($regData['qualification']=='G'){echo  'Graduate';}?>
						<?php if($regData['qualification']=='P'){echo  'Post Graduate';}?> </td>
		</tr>

		<tr>
			<td class="tablecontent2">Specify :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap"><?php echo $regData['q_name'];?> </td>
		</tr>

	<?php if($regData['registrationtype']!='NM'){ ?>
		<tr>
			<td class="tablecontent2">Bank/Institution working :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap"><?php echo $regData['institute_name'];?> </td>
		</tr>

		<tr>
			<td class="tablecontent2">Branch/Office :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">  <?php echo $regData['office'];?> </td>
		</tr>

		<tr>
			<td class="tablecontent2">Designation :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">  <?php echo $regData['dname'];?> </td>
		</tr>

		<tr>
			<td class="tablecontent2">Date of Joining :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap"> <?php echo date('d-m-Y',strtotime($regData['dateofjoin']));?> </td>
		</tr>
	<?php } ?>
		<tr>
			<td class="tablecontent2">Email :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap"><?php echo $regData['email']?> </td>
		</tr>
				
		
		<tr>
			<td class="tablecontent2">Mobile :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap"><?php echo $regData['mobile'];?> </td>
		</tr>

		<tr>
			<td class="tablecontent2">ID Proof :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap"> <?php echo $regData['id_name'];?></td>
		</tr>
		
        <?php if($regData['registrationtype'] == 'NM' || $regData['registrationtype'] == 'DB'){?>
		<tr>
			<td class="tablecontent2">ID No :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap"><?php echo $regData['idNo'];?></td>
		</tr>
        <?php } ?>
        <tr>
			<td class="tablecontent2">Aadhar Card No. :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap"><?php echo $regData['aadhar_card'];?></td>
		</tr>
        
        <tr>
			<td class="tablecontent2">Transaction No :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap"><?php echo $regData['transaction_no'];?></td>
		</tr>
        
        <tr>
			<td class="tablecontent2">Transaction Date :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap"><?php echo date('d-m-Y',strtotime($regData['date']));?></td>
		</tr>
        
        <tr>
			<td class="tablecontent2">Transaction Amount :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap"> <?php echo $regData['amount'];?></td>
		</tr>
        
       <tr>
			<td class="tablecontent2">I agree to receive the Annual report from the Institute in a softcopy,<br /> at my registered email ID, in place of physical copy :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap"> <?php if($regData['optnletter']=='Y'){echo  'Yes';}?>
          <?php if($regData['optnletter']=='N'){echo  'No';}?> </td>
		</tr>

		<tr>
			<td class="tablecontent2">ID Proof :</td>
           <?php $actual_idproof = get_img_name($regData['regnumber'],'pr');?>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">  <img src="<?php echo base_url();?><?php echo $actual_idproof;?><?php echo '?'.time(); ?>"  height="180" width="100"></td>
		</tr>
        <?php if($regData['registrationtype'] == 'O'){?>   
        <tr>
            <td class="tablecontent2">Declaration :</td>
           <?php $actual_declaration = get_img_name($regData['regnumber'],'declaration');?>
            <td colspan="3" class="tablecontent2" nowrap="nowrap">  <img src="<?php echo base_url();?><?php echo $actual_declaration;?><?php echo '?'.time(); ?>"  height="180" width="100"></td>
        </tr>
        <?php } ?>
        
		<tr>
			<td class="tablecontent2">Signature :</td>
             <?php $actual_sign = get_img_name($regData['regnumber'],'s');?>
			<td colspan="3" class="tablecontent2" nowrap="nowrap"><img src="<?php echo base_url();?><?php echo $actual_sign;?><?php echo '?'.time(); ?>" height="100" width="100"></td>
		</tr>

		<tr>
			<td class="tablecontent2">Date :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">
				<?php echo date('d-m-Y h:i:s A',strtotime($regData['createdon']));?>		</td>
		</tr>

		</tbody></table>
	</td>
</tr>
	
</tbody></table>
</div>
</div>
  
<script>
function printDiv(divName) {
     var printContents = document.getElementById('print_div').innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>

<?php $this->load->view('admin/includes/footer');?>