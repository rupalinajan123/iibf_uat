<style>
   .modal-dialog{
   position: relative;
   display: table; 
   overflow-y: auto;    
   overflow-x: auto;
   width: 920px;
   min-width: 300px;   
   }
   #confirm .modal-dialog{
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
   #confirmBox
   {
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
   #confirmBox .button:hover
   {
   background-color: #ddd;
   }
   #confirmBox .message
   {
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
   a.forget  {color:#9d0000;}
   a.forget:hover {color:#9d0000; text-decoration:underline;}
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
   <div class="content-wrapper">
      <section class="content-header box-header with-border" style="background-color: #1287C0">
         <h1 class="register"> Examination Prize winner's for the year 2024-25 </h1>
      </section>
      <!-- Content Header (Page header) -->
      <section class="content-header  box-header with-border">
         <h1 style="font-size:18px">
            Please go through the given detail, correction may be made if necessary.
            <a  href="javascript:window.history.go(-1);" style="color:#F00">Modify</a>
         </h1>
         <br>
         <!--<ol class="breadcrumb">
            <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
            <li class="active">Manage Users</li>
            </ol>-->
      </section>
      <form class="form-horizontal" name="blendedForm" id="blendedForm" method="post" enctype="multipart/form-data" action="<?php echo base_url()?>PrizeWinner/addmember/">
         <section class="content">
            <div class="row">
               <div class="col-md-12">
                  <!-- Horizontal Form -->
                  <div class="box box-info">
                     <div class="box-header with-border">
                        <h3 class="box-title">Basic Details</h3>
                     </div>
                     <div class="box-body">
                        <div class="form-group">
                           <label for="roleid" class="col-sm-4 control-label">Membership No&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                           <div class="col-sm-5"> <?php echo $this->session->userdata['regnumber'];?> </div>
                        </div>
                        <div class="form-group">
                           <label for="roleid" class="col-sm-4 control-label">First Namer&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                           <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['sel_namesub'].'  '.$this->session->userdata['enduserinfo']['firstname'];?> </div>
                        </div>
                        <div class="form-group">
                           <label for="roleid" class="col-sm-4 control-label">Last Name&nbsp;:</label>
                           <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['lastname'];?> </div>
                        </div>
                        <div class="form-group">
                           <label for="roleid" class="col-sm-4 control-label">Moblie &nbsp;:</label>
                           <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['mobile'];?> </div>
                        </div>
                        <div class="form-group">
                           <label for="roleid" class="col-sm-4 control-label">Email ID&nbsp;:</label>
                           <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['email'];?> </div>
                        </div>
                     </div>
                     <div class="box-header with-border">
                        <h3 class="box-title">Bank Details</h3>
                     </div>
                     <div class="box-body">
                        <div class="form-group">
                           <label for="roleid" class="col-sm-4 control-label">Bank Name&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                           <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['bankname'];?></div>
                        </div>
                        <div class="form-group">
                           <label for="roleid" class="col-sm-4 control-label">Branch Name&nbsp;:</label>
                           <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['branchname'];?> </div>
                        </div>
                        <div class="form-group">
                           <label for="roleid" class="col-sm-4 control-label">Bank Address1&nbsp;:</label>
                           <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['branchadd1'];?> </div>
                        </div>
                        <div class="form-group">
                           <label for="roleid" class="col-sm-4 control-label">Bank Address 2&nbsp;:</label>
                           <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['branchadd2'];?> </div>
                        </div>
                        <div class="form-group">
                           <label for="roleid" class="col-sm-4 control-label">Bank Address3 &nbsp;:</label>
                           <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['branchadd3'];?> </div>
                        </div>
                        <div class="form-group">
                           <label for="roleid" class="col-sm-4 control-label">Bank Address4&nbsp;:</label>
                           <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['branchadd4'];?> </div>
                        </div>
                        <div class="form-group">
                           <label for="roleid" class="col-sm-4 control-label">IFS CODE&nbsp;:</label>
                           <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['ifs_code'];?> </div>
                        </div>
                        <div class="form-group">
                           <label for="roleid" class="col-sm-4 control-label">Account Type &nbsp;:</label>
                           <div class="col-sm-5"> <?php if($this->session->userdata['enduserinfo']['account_type']=='SA'){echo  'Saving Acount';}else{echo 'Current Acount';}?> </div>
                        </div>
                        <div class="form-group">
                           <label for="roleid" class="col-sm-4 control-label">Account Number &nbsp;:</label>
                           <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['acc_no'];?> </div>
                        </div>
                        <div class="form-group">
                           <label for="roleid" class="col-sm-4 control-label">Your Pan Card File &nbsp;:</label>
                           <div class="col-sm-5 ">
                               <label for="roleid" class="col-sm-2 control-label"><img src="<?php echo base_url("uploads/prize_winner_pan_card/".$this->session->userdata['enduserinfo']['pan_card_file']);?>"  height="100" width="100"></label>
                               <div id="error_dob"></div>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="roleid" class="col-sm-4 control-label">Your Cancel Cheque File &nbsp;:</label>
                           <div class="col-sm-5 ">
                               <label for="roleid" class="col-sm-2 control-label"><img src="<?php echo base_url("uploads/prize_winner_cancel_cheque/".$this->session->userdata['enduserinfo']['cancel_cheque_file']);?>"  height="100" width="100"></label>
                               <div id="error_dob"></div>
                           </div>
                        </div>
                        <!-- Basic Details box closed--> 
                        <!-- Contact Details box Start-->
                        <!-- Invoice Address Details box Closed-->
                        <div class="box box-info">
                           <div class="box-header with-border"></div>
                           <div class="box-footer">
                              <div class="col-sm-5 col-xs-offset-3" style="text-align: center">
                                 <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Submit">
                              </div>
                           </div>
                        </div>
                     </div>
                     <!-- Basic Details box closed-->
                  </div>
               </div>
            </div>
         </section>
      </form>
   </div>
</div>
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

