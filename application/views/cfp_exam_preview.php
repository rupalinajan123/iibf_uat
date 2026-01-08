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
.content-header>h1 {
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
<!-- Content Wrapper. Contains page content -->
<div class="container">
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    
    <section class="content-header box-header with-border" style="background-color: #1287C0">
      <h1 class="register"> Certified Financial Planner (CFP) Certification-Fast Track Pathway </h1>
    </section>
    
    <section class="">
      <div class="row">
        <div class="col-md-12">
          <div class="">
            <div for="roleid" class="col-sm-4 control-label" style="text-align: right;">Membership No.&nbsp;<span style="color:#F00">*</span>&nbsp;:</div>
            <div class="col-sm-4" style="width: 25%;"> <?php echo $this->session->userdata['enduserinfo']['regnumber'];?> </div>
          </div>
        </div>
      </div>
    </section>
    <br />
    <form class="form-horizontal" name="blendedForm" id="blendedForm" method="post" enctype="multipart/form-data" action="<?php echo base_url()?>cfp_exam/addmember/">
      <div class="content-wrapper">
        <section class="content">
          <div class="row">
            <div class="col-md-12"> 
              <!-- Basic Details box Start-->
              
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Processing Fees</h3>
                </div>
                <div class="box-body">
                 <!-- <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Course&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                    <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['exam_name'];?> </div>
                  </div>
                  
                  -->
                  
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Processing Fees Amount&nbsp;:</label>
                    <div class="col-sm-5"> <strong>Rs. <?php echo $this->session->userdata['enduserinfo']['preview_fees']; ?> </strong> </div>
                  </div>
                  
                </div>
              </div>
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Basic Details</h3>
                </div>
                <div class="box-body">
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">First Name&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                    <div class="col-sm-5"> <?php echo $row['namesub'];?>&nbsp; <?php echo $row['firstname'];?> </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Middle Name&nbsp;:</label>
                    <div class="col-sm-5"> <?php echo $row['middlename'];?> </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Last Name&nbsp;:</label>
                    <div class="col-sm-5"> <?php echo $row['lastname'];?> </div>
                  </div>
                </div>
              </div>
              <!-- Basic Details box closed--> 
              
              <!-- Contact Details box Start-->
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Contact Details</h3>
                </div>
                <div class="box-body">
                  <div class="form-group">
                    <?php
          if (isset($row['dateofbirth'])) {
            $originalDate = $row['dateofbirth'];
            $newDate      = date("d/m/Y", strtotime($originalDate));
          }
          ?>
                    <label for="roleid" class="col-sm-4 control-label">Date of Birth&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                    <div class="col-sm-3 example"> <?php echo $newDate;?>&nbsp;(DD/MM/YYYY)</div>
                  </div>

                  <!-- <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Designation&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                    <div class="col-sm-5" style="display:block" id="edu">
                      <?php if(count($designation)){
               foreach($designation as $designation_row){
                if($row['designation']==$designation_row['dcode']){
                  echo  $designation_row['dname'];}
                } 
              } ?>
                    </div>
                  </div> -->
                  <!-- <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Bank/Institution working&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                    <div class="col-sm-5" id="edu">
                      <?php if(count($institution_master)){
                foreach($institution_master as $institution_row){   
                if($row['associatedinstitute']==$institution_row['institude_id']){
                  echo  $institution_row['name'];}
                  }
            } ?>
                    </div>
                  </div> -->
                  
                  <?php /*?><div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">GSTIN No.&nbsp;<!--<span style="color:#F00">*</span>&nbsp;-->:</label>
                    <div class="col-sm-3 example"> <?php echo $this->session->userdata['enduserinfo']['gstin_no'];?></div>
                  </div><?php */?>
                  
                  
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Email&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                    <div class="col-sm-5"> <?php echo $row['email']?> </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Mobile&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                    <div class="col-sm-5"> <?php echo $row['mobile']?> </div>
                  </div>
                  <!-- <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Qualification&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                    <div class="col-sm-4">
                      
                      <?php echo $row['exam_name']?>
                    </div>
                  </div>
                  
                </div> -->
              </div>
              <!-- Invoice Address Details box Closed-->
              
              <div class="box box-info">
                <div class="box-header with-border"></div>
                <div class="box-footer">
                  <div class="col-sm-5 col-xs-offset-3" style="text-align: center">
                    <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Proceed for Payment">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </form>
  </div>
</div>
<script>

history.pushState(null, null, '<?php echo $_SERVER["REQUEST_URI"]; ?>');
window.addEventListener('popstate', function(event) {
    window.location.assign(site_url+"Cfp_exam/");
});


    $(document).ready(function() {
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
        createCookie('member_register_form', '1', '1');



    });
</script>