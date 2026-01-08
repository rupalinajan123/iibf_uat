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
   background-color:#fff;
   }
   body.layout-top-nav .main-header h1 {
   color:#0699dd;
   margin-bottom:0;
   margin-top:30px;
   }
   .container {
   position:relative;
   }
   .box-header.with-border {
   background-color:#7fd1ea;
   border-top-left-radius:0;
   border-top-right-radius:0;
   margin-bottom:10px;
   }
   .header_blue {
   background-color:#2ea0e2 !important;
   color:#fff !important;
   margin-bottom:0 !important;
   }
   .box {
   border:none;
   box-shadow:none;
   border-radius:0;
   margin-bottom:0;
   }
   .nobg {
   background:none !important;
   border:none !important;
   }
   .box-title-hd {
   color:#3c8dbc;
   font-size:16px;
   margin:0;
   }
   .blue_bg {
   background-color:#e7f3ff;
   }
   .m_t_15 {
   margin-top:15px;
   }
   .main-footer {
   padding-left:160px;
   padding-right:160px;
   }
   .content-header > h1 {
   font-size:22px;
   font-weight:600;
   }
   h4 {
   margin-top:5px;
   margin-bottom:10px !important;
   font-size:14px;
   line-height:18px;
   padding:0 5px;
   font-weight:600;
   text-align:justify;
   }
   .form-horizontal .control-label {
   padding-top:4px;
   }
   .pad_top_2 {
   padding-top:2px !important;
   }
   .pad_top_0 {
   padding-top:0px !important;
   }
   div.form-group:nth-child(odd) {
   background-color:#dcf1fc;
   padding:5px 0;
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
   z-index:1;
   box-shadow:0 1px 3px #000;
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
   margin-bottom:10px;
   }
   .form-horizontal .form-group {
   margin-left:0;
   margin-right:0;
   }
   .form-control {
   border-color:#888;
   }
   .form-horizontal .control-label {
   font-weight:normal;
   }
   a.forget {
   color:#9d0000;
   }
   a.forget:hover {
   color:#9d0000;
   text-decoration:underline;
   }
   ol li {
   line-height:18px;
   }
   .example {
   text-align:left !important;
   padding:0 10px;
   }
</style>
<?php 
   header('Cache-Control: must-revalidate');
   header('Cache-Control: post-check=0, pre-check=0', FALSE);
   ?>
<!-- Content Wrapper. Contains page content -->
<div class="container">
<!-- Content Header (Page header) -->
<section class="content-header box-header with-border" style="height: 48px; background-color: #1287C0; ">
   <h1 class="register"> Please go through the given detail, correction may be made if necessary. <a  href="javascript:window.history.go(-1);" style="color:#0FF; font-size:25px" >Modify</a> </h1>
</section>
<br>
<?php //print_r($this->session->userdata['enduserinfo']);?>
<!--<ol class="breadcrumb">
   <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
   <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
   <li class="active">Manage Users</li>
   </ol>--> 
</section>
<section class="content">
   <div class="row">
      <div class="col-md-12">
         <!-- Horizontal Form -->
         <div class="box box-info">
            <div class="box-header with-border">
               <h3 class="box-title">Basic Details</h3>
               <div style="float:right;">
                  <!--   <a  href="javascript:window.history.go(-1);">Back</a>--> 
               </div>
            </div>
         </div>
      </div>
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
      <form class="form-horizontal" name="bankquestForm" id="bankquestForm"  method="post"   action="<?php echo base_url()?>contactClasses/addvtrecord">
         <div class="form-group">
            <label for="roleid" class="col-sm-3 control-label"> First Name &nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
            <div class="col-sm-1"><?php echo $this->session->userdata['enduserinfo']['sel_namesub'];?></div>
            <div class="col-sm-2">
            <?php 
               if(isset($this->session->userdata['enduserinfo']['fname']))
               {
               		echo $this->session->userdata['enduserinfo']['fname'];
               }
               else
               {
                echo ' ';
               }?> 
            <!--(Max 30 Characters) -->
            </div>
         </div>
         <div class="form-group">
            <label for="roleid" class="col-sm-3 control-label"> Middle Name&nbsp;:</label>
            <div class="col-sm-1"> <?php   if(isset($this->session->userdata['enduserinfo']['mname']))
               {
               echo $this->session->userdata['enduserinfo']['mname'];
               }
               else
               {
                echo ' ';
               }?> 
            </div>
         </div>
         <div class="form-group">
            <label for="roleid" class="col-sm-3 control-label"> Last Name&nbsp;:</label>
            <div class="col-sm-1"> <?php if(isset($this->session->userdata['enduserinfo']['lname']))
               {
               echo $this->session->userdata['enduserinfo']['lname'];
               }
               else
               {
                echo ' ';
               }?> 
            </div>
         </div>
         <div class="form-group">
               <label for="roleid" class="col-sm-3 control-label">Email &nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
               <div class="col-sm-5">
                  <?php if(isset($this->session->userdata['enduserinfo']['email']))
                     {
                     echo $this->session->userdata['enduserinfo']['email'];
                     } ?> 
               </div>
            </div>
            <div class="form-group">
               <label for="roleid" class="col-sm-3 control-label">Mobile Number &nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
               <div class="col-sm-5">
                  <?php if(isset($this->session->userdata['enduserinfo']['mobile']))
                     {
                     echo $this->session->userdata['enduserinfo']['mobile'];
                     }?> 
               </div>
            </div>
            <div class="form-group">
            <label for="roleid" class="col-sm-3 control-label">Bank/Institution working &nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
               <div class="col-sm-2">
                   <?php
                     if(isset($this->session->userdata['enduserinfo']['institution']))
                     {
                     		echo $this->session->userdata['enduserinfo']['institution'];
                     }
                     
                     ?>
               </div>
            </div>
            <div class="form-group">
            <label for="roleid" class="col-sm-3 control-label">Designation &nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
               <div class="col-sm-2">
                   <?php
                     if(isset($this->session->userdata['enduserinfo']['designation']))
                     {
                     		$designation=$this->master_model->getRecords('designation_master',array('dcode'=>$this->session->userdata['enduserinfo']['designation']),'dname');
                     		// echo $this->db->last_query();
                     		// print_r($designation);
                     		echo $designation[0]['dname'];
                     }
                     
                     ?>
               </div>
            </div>
         <div class="box-body">
            <div class="form-group">
               <label for="roleid" class="col-sm-3 control-label">Address line1&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
               <div class="col-sm-5">
                  <?php  if(isset($this->session->userdata['enduserinfo']['addressline1']))
                     {
                     echo $this->session->userdata['enduserinfo']['addressline1'];
                     }?>  
               </div>
               <!-- (Max 30 Characters) --> 
            </div>
            <div class="form-group">
               <label for="roleid" class="col-sm-3 control-label">Address line2 &nbsp;:</label>
               <div class="col-sm-5">
                  <?php if(isset($this->session->userdata['enduserinfo']['addressline2']))
                     {
                     echo $this->session->userdata['enduserinfo']['addressline2'];
                     }?>  
               </div>
               <!-- (Max 30 Characters) --> 
            </div>
            <div class="form-group">
               <label for="roleid" class="col-sm-3 control-label">Address line3&nbsp;:</label>
               <div class="col-sm-5">
                  <?php if(isset($this->session->userdata['enduserinfo']['addressline3']))
                     {
                     echo $this->session->userdata['enduserinfo']['addressline3'];
                     }?> 
               </div>
               <!--(Max 30 Characters) --> 
            </div>
            <div class="form-group">
               <label for="roleid" class="col-sm-3 control-label">Address line4&nbsp;:</label>
               <div class="col-sm-5">
                  <?php if(isset($this->session->userdata['enduserinfo']['addressline4']))
                     {
                     echo $this->session->userdata['enduserinfo']['addressline4'];
                     }?>  
               </div>
               <!-- (Max 30 Characters) --> 
            </div>
            <div class="form-group">
               <label for="roleid" class="col-sm-3 control-label">District&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
               <div class="col-sm-5">
                  <?php if(isset($this->session->userdata['enduserinfo']['district']))
                     {
                     echo $this->session->userdata['enduserinfo']['district'];
                     }?> 
               </div>
               <!-- (Max 30 Characters) --> 
            </div>
            <div class="form-group">
               <label for="roleid" class="col-sm-3 control-label">City&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
               <div class="col-sm-5">
                  <?php if(isset($this->session->userdata['enduserinfo']['city']))
                     {
                     echo $this->session->userdata['enduserinfo']['city'];
                     }?>  
               </div>
               <!-- (Max 30 Characters) --> 
            </div>
            <div class="form-group">
               <label for="roleid" class="col-sm-3 control-label">State&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
               <div class="col-sm-2">
                   <?php
                     if(isset($this->session->userdata['enduserinfo']['state']))
                     {
                     		$State=$this->master_model->getRecords('state_master',array('state_code'=>$this->session->userdata['enduserinfo']['state']),'state_name');
                     		// echo $this->db->last_query();
                     		// print_r($State);
                     		echo $State[0]['state_name'];
                     }
                     
                     ?>
                     <input hidden="statepincode" id="statepincode" value="" autocomplete="false">
               </div>
               <!--(Max 6 digits) -->
               <label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
               <div class="col-sm-2">
                  <?php   if(isset($this->session->userdata['enduserinfo']['pincode']))
                     {
                     echo  $this->session->userdata['enduserinfo']['pincode'];
                     
                     }?> 
               </div>
            </div>
            
            <div class="form-group">
               <label for="roleid" class="col-sm-3 control-label">Course&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
               <div class="col-sm-5"> 
                  <?php if(isset($this->session->userdata['enduserinfo']['cource_code']))
                     {
                      		$cource=array(); 
                     	$this->db->where('isactive','1');
                         	$this->db->where('course_code', $this->session->userdata['enduserinfo']['cource_code']);
                     	$cource=$this->master_model->getRecords('contact_classes_cource_master');
                      
                     
                        echo $cource[0]['course_name'];
                     }?>
                  <span class="error" id="tiitle_error">
                  <?php //echo form_error('firstname');?>
                  </span> 
               </div>
            </div>
            <div class="form-group">
               <label for="roleid" class="col-sm-3 control-label">Center&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
               <div class="col-sm-5"> 
                  <?php if(isset($this->session->userdata['enduserinfo']['center_code']))
                     {
                     $this->db->where('center_code', $this->session->userdata['enduserinfo']['center_code']);
                     $center=$this->master_model->getRecords('contact_classes_center_master');
                        echo $center[0]['center_name'];
                     }?>
                  <span class="error" id="tiitle_error">
                  <?php //echo form_error('firstname');?>
                  </span> 
               </div>
            </div>
            <div class="form-group">
               <label for="roleid" class="col-sm-3 control-label">Venue &nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
               <div class="col-sm-9">
                  <?php if(isset($this->session->userdata['enduserinfo']['center_code']) )
                     {
                     //venue
                     
                     $query = $this->db->query("SELECT DISTINCT(venue_name) FROM `contact_classes_venue_master` WHERE `center_code` = ".$this->session->userdata['enduserinfo']['center_code']."");
                     $reg_count = $query->result_array();
                     
                       echo $reg_count[0]['venue_name'];
                     }?>
                  <span class="error" id="tiitle_error">
                  <?php //echo form_error('firstname');?>
                  </span> 
               </div>
            </div>
            <div class="form-group">
               <label for="roleid" class="col-sm-3 control-label">Subjects&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
               <div class="col-sm-4">
                  <?php if(isset($this->session->userdata['enduserinfo']['subjects']) )
                     {
                     $array = implode("','", $this->session->userdata['enduserinfo']['subjects']);
                     
                     $sub=array();
                     $count=1;
                     $i=0;
                     $query = $this->db->query("SELECT DISTINCT(sub_name) FROM `contact_classes_subject_master` WHERE `sub_code` IN('".$array."')");
                     $reg_count = $query->result_array();
                     if(!empty($reg_count))
                     { $i=1;
                     foreach($reg_count as $reg)
                            {  
                              if(isset($reg['sub_name']))
                              { 
                                echo ' '.$i.'. ';
                                echo $reg['sub_name'];
                                echo '<br>';
                                $i++;
                              }
                              
                            }
                     }
                     }?>
                  <span class="error" id="tiitle_error">
                  <?php //echo form_error('firstname');?>
                  </span> 
               </div>
            </div>
         </div>
         <div class="box-footer">
            <div class="col-sm-4 col-xs-offset-3">
               <center>  <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Proceed for Payment"> </center>
            </div>
         </div>
      </form>
   </div>
</section>
<link href="<?php echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script> 
<script src="<?php echo base_url();?>assets/admin/dist/js/jquery.date-dropdowns.js"></script> 
<script src="<?php echo base_url();?>js/Fin_Quest.js?<?php echo time(); ?>"></script> 
<script>
   $(document).ready(function(){
   function createCookie(name, value, days) {
     var expires;
   
     if (days) {
         var date = new Date();
         date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
         expires = "; expires=" + date.toGMTString();
     } else {
         expires = "";
     }
     document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
   }
   createCookie('member_register_form','1','1');
   
   
   
   });
   
</script>