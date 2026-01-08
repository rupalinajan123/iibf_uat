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
    width: 85%;
    margin: 0 auto;
    text-align: center;
    color: #000 !important;
    font-size: 19px;
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
<script type = "text/javascript" >
    function preventBack() { window.history.forward(); }
    setTimeout("preventBack()", 0);
    window.onunload = function () { null };
</script>
<div class="container">
  <div class="content-wrapper">
    <section class="content-header box-header with-border" style="background-color: #1287C0">
      <h1 class="register">Blended Training  Feedback Form </h1>
    </section>
     <form name="getDetailsForm" id="getDetailsForm" method="post" action="<?php echo base_url(); ?>blended_feedback/infrastructure_feedback/<?php echo $batch_code;?>">
   
    <center><h1> </h1></center>
    
     <br />
              <br />
       <div class="form-group" >
                <strong >Title of Programme :<?php echo $title;?></strong>
                     <input type="hidden" name="batch_code" id="batch_code" value="<?php echo $batch_code;?>"/>
           <!--    <select class="form-control" id="batch_code" name="batch_code" required  >
            <option value="">-Select-</option>
                  <option value="VCCP002">Certified Credit Professionals</option>
                  
                </select>-->
          
             
              <br />
              <br />
           
           <label for="roleid" >Period of Training :&nbsp;<?php echo date('d-m-Y', strtotime($start_date)).' to '.date('d-m-Y', strtotime($end_date)); ?></label>
              </div>
         
           <br />
              <br />
            
   
 <center><input type="submit" name="btnSubmit" class="btn btn-info" id="btnSubmit" value="Next">   </center>


    </form>

</div>

<script>
history.pushState(null, null, '<?php echo $_SERVER["REQUEST_URI"]; ?>');
window.addEventListener('popstate', function(event) {
    window.location.assign(site_url+"blended/");
});
</script>