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
    Duplicate ID Card Request
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
			format with 0.25" margin from all sides <a href="javascript:void(0);" class="linkfooter" style="color:#000" onclick="javascript:printDiv();">(Click to Print)</a>
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
                <label for="roleid" class="col-sm-3 control-label">Name :</label>
                	<div class="col-sm-5">
                  <?php 
				  $username=$user_info[0]['namesub'].' '.$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
				  $userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
				  echo $userfinalstrname;?>
                    </div>
                </div>
             
                 <div class="form-group">
                 <label for="roleid" class="col-sm-3 control-label">Bank/Institution working :</label>
                  <div class="col-sm-5" >
                   <?php echo $user_info[0]['name'];?>
                </div>
             </div>   
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Date of Birth :</label>
                	<div class="col-sm-2">
                    <?php echo date('d-m-Y',strtotime($user_info[0]['dateofbirth']));?>
                    </div>
                </div>
         
          <div class="form-group">
        <label for="roleid" class="col-sm-3 control-label">Mobile :</label>
            <div class="col-sm-5">
            <?php echo $user_info[0]['mobile'];?>
            </div>
        </div>
                
                
         <div class="form-group">
        <label for="roleid" class="col-sm-3 control-label">Email :</label>
            <div class="col-sm-5">
            <?php echo $user_info[0]['email']?>
            </div>
        </div>
        
         <div class="form-group">
        <label for="roleid" class="col-sm-3 control-label">Reason For Duplicate I-card :</label>
            <div class="col-sm-5">
            <?php echo $user_info[0]['description']?>
            </div>
        </div>
            
            
        
        <div class="form-group">
        <label for="roleid" class="col-sm-3 control-label">Fee :</label>
        <div class="col-sm-5">
        Rs.115
        </div>
        </div>
        
         	
                </div>
                <div class="col-sm-3">

<label for="roleid" class="col-sm-3 control-label"><img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('regnumber'),'p');?><?php echo '?'.time(); ?>" height="100" width="100" ></label>
        
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
						<img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('regnumber'),'p');?>" height="100" width="100" style=" position:absolute; right:15px; top:0px;" >
						</td>
					</tr>
					<tr>
						<td class="tablecontent2">Name :</td>
						<td colspan="2" class="tablecontent2" nowrap="nowrap"><?php echo $userfinalstrname;?>
						</td>
					</tr>
				
					<tr>
						<td class="tablecontent2">Bank/Institution Name :</td>
						<td colspan="3" class="tablecontent2" nowrap="nowrap"><?php echo wordwrap($user_info[0]['name'], 20, "<br />\n");?> </td>
					</tr>		
							
					<tr>
						<td class="tablecontent2">Date of Birth :</td>
						<td colspan="3" class="tablecontent2" nowrap="nowrap"><?php echo date('d-m-Y',strtotime($user_info[0]['dateofbirth']));?></td>
					</tr>	
			
					<tr>
						<td class="tablecontent2">Mobile :</td>
						<td colspan="3" class="tablecontent2" nowrap="nowrap"><?php echo $user_info[0]['mobile'];?></td>
					</tr>
				
				<tr>
						<td class="tablecontent2" width="51%">Email:</td>
						<td colspan="3" class="tablecontent2" width="49%" nowrap="nowrap"><?php echo $user_info[0]['email'];?></td>
					</tr>
					
					 <tr>
						<td class="tablecontent2">Reason For Duplicate I-card :</td>
						<td colspan="3" class="tablecontent2" nowrap="nowrap"><?php echo $user_info[0]['description'];?></td>
					</tr>
					
				  <tr>
						<td class="tablecontent2">Fee :</td>
						<td colspan="3" class="tablecontent2" nowrap="nowrap">Rs.115</td>
					</tr>
     


    	</tbody></table>
	</td>
</tr>
	
</tbody></table>
</div>
  </div>



<script>
function printDiv() {
     var printContents = document.getElementById('print_div').innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>