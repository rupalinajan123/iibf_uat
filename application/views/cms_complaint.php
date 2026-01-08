<?php 
/*echo '<img src="https://iibf.esdsconnect.com/under-maintenance.gif" style="display:block;"/>';
echo "<style text-align:center> body{ display:block; }  </style>";
header('Location: https://iibf.esdsconnect.com/uc.html');
redirect ("uc.html");
exit;*/
?>
<!-- Added by Pooja Mane 2023-9-21 To hide I don't remember div for Examination-->
 <script>
    $(document).ready(function() {
      $("select").change(function() {
        var selected = $("#querycat").val(); 
        if(selected == 'NEX'){
          $("#dont-remember").css("display", "none");
        }else{
          $("#dont-remember").css("display", "block");
        }
        
      });
    });
  </script>
<!-- added by Pooja Mane code end 2023-9-21 -->
<div class="content-wrapper">
	<div class="container">
        <section class="content-header">
            <h1 class="register">Members / Candidates Support Services (HELP)</h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-body">
                        	 <form class="form-horizontal" name="cmsfrm" id="cmsfrm"  method="post" action="<?php echo base_url().'CmsComplaint/';?>" enctype="multipart/form-data" >
                             	<section class="content">
                                	<div class="row"> 
                                    	<div class="col-md-12">
                                      			<div class="box box-info">
                                     				<div class="box-header with-border" style="background-color:transparent !important;">
                                        				<div style="color:#f00;">
                                        					Institute will strive to attend to these Queries within 3 working days. Once the Query is registered here, Pl do not repeat/send these Queries to any other Email ID of the Institute.
                                        				</div>
                                        			</div>
                                                    
                                      				<div class="box-body">
                                        				<?php if($this->session->flashdata('error')!=''){?>
                                                         <div class="alert alert-danger alert-dismissible">
                                                         	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                            <?php echo $this->session->flashdata('error'); ?>
                                                         </div>
                                                         <?php } if($this->session->flashdata('success')!=''){ ?>
                                                         <div class="alert alert-success alert-dismissible">
                                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                          	<?php echo $this->session->flashdata('success'); ?>
                                                          </div>
                                                         <?php } 
                                                         if(validation_errors()!=''){?>
                                                            <div class="alert alert-danger alert-dismissible">
                                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                                <?php echo validation_errors(); ?>
                                                            </div>
                                                          <?php } ?> 
                                                          <div class="form-group">
                                                          	<label for="category" class="col-sm-4 control-label">Select Query Category *</label>
                                                            <div class="col-sm-6">
                                                            	 <select class="form-control" id="querycat" name="querycat" required >
                                                                    <option value="">--Select--</option>
                                                                    <?php if(count($query_cats) > 0){
                                                                            foreach($query_cats as $row1){ 	?>
                                                                    <option value="<?php echo $row1['category_code'];?>" <?php echo  set_select('querycat', $row1['category_code']); ?>><?php echo $row1['name'];?></option>
                                                                    <?php } } ?>
                                                                 </select>
                                                            </div>
                                                          </div>
                                                          
                                                          <div class="form-group">
                                                          	<label for="sub-category" class="col-sm-4 control-label">Select Query Sub-Category *</label>
                                                            <div class="col-sm-6">
                                                            	 <select class="form-control" id="querysubcat" name="querysubcat" required >
                                                                    <option value="">--Select--</option>
                                                                 </select>
                                                            </div>
                                                          </div>
                                                          
                                                          <div class="form-group examnamediv">
                                                          	<label for="exam" class="col-sm-4 control-label">Exam Name *</label>
                                                            <div class="col-sm-6">
                                                            	 <select class="form-control" id="examname" name="examname" >
                                                                    <option value="">--Select--</option>
                                                                 </select>
                                                            </div>
                                                          </div>
                                                          
                                                          <div class="form-group">
                                                          	<label for="membership_no" class="col-sm-4 control-label">Membership / Registration No.</label>
                                                            <div class="col-sm-6">
                                                            	 <input type="text" name="memno" id="cms_memberno" class="form-control" autocomplete="off" value="<?php echo set_value('memno');?>"/>(Max 10 Characters)
                                                                 <input type="hidden" name="stacode" id="stacode" />
                                                                 <div class="cms-error-memno" style="display: none;color:#f00;">Invalid Membership/Registration no</div>
                                                            </div>
                                                          </div>
                                                          
                                                          <!--<div class="form-group" id="dont-remember">
                                                          	<label for="remember_membershipno" class="col-sm-4 control-label"></label>
                                                            <div class="col-sm-6">
                                                            	 <input type="checkbox" name="remember_memno" id="remember_memno" />I Don't Remember Membership/Registration No.
                                                            </div>
                                                          </div> -->
                                                          
                                                          <div class="extra-info-cms">
                                                          	  <div class="form-group">
                                                                <label for="name" class="col-sm-4 control-label">Name *</label>
                                                                <div class="col-sm-6">
                                                                     <input type="text" class="form-control" name="name" id="name" value="<?php echo set_value('name');?>" maxlength="50" data-parsley-maxlength="50" />Max 50 Characters)
                                                                </div>
                                                              </div>
                                                              
                                                              <div class="form-group">
                                                                <label for="DOB" class="col-sm-4 control-label">Date of Birth *</label>
                                                                <div class="col-sm-6">
                                                                	<input type="hidden" id="dateofbirth" name="dateofbirth1">
																	<?php 
                                                                        $min_year = date('Y', strtotime("- 18 year"));
                                                                        $max_year = date('Y', strtotime("- 60 year"));
                                                                    ?>
                                                                    <input type='hidden' name='minyear' id='minyear' value="<?php echo $min_year; ?>">
                                                                    <input type='hidden' name='maxyear' id='maxyear' value="<?php echo $max_year; ?>">
                                                                    <span id="dob_error" class="error"></span>
                                                                </div>
                                                              </div>
                                                              
                                                              <div class="form-group">
                                                                <label for="employer_name" class="col-sm-4 control-label">Employer Name *</label>
                                                                <div class="col-sm-6">
                                                                     <input type="text" name="employer_name" id="employer_name" class="form-control" value="<?php echo set_value('employer_name');?>" maxlength="50" data-parsley-maxlength="50" />(Max 50 Characters)
                                                                </div>
                                                              </div>
                                                              
                                                              <div class="form-group">
                                                                <label for="email" class="col-sm-4 control-label">Email Id *</label>
                                                                <div class="col-sm-6">
                                                                     <input class="form-control" id="email" name="email" placeholder="Email" data-parsley-type="email" value="<?php echo set_value('email');?>" autocomplete="off" type="text" required />
                                                                     <span>(Reply will be sent on this email ID)</span>
                                                                </div>
                                                              </div>
                                                              
                                                              <div class="form-group">
                                                                <label for="mobileno" class="col-sm-4 control-label">Mobile No. *</label>
                                                                <div class="col-sm-2">
                                                                     <input class="form-control" id="countrycd" name="countrycd" placeholder="Country Code" data-parsley-type="number" value="91" data-parsley-maxlength="3"  maxlength="3" data-parsley-trigger-after-failure="focusout" />
                                                                </div>
                                                                <div class="col-sm-4">
                                                                     <input class="form-control" id="mobileno" name="mobileno" placeholder="Mobile No." data-parsley-type="number" data-parsley-minlength="10" data-parsley-maxlength="10" value="" type="tel" maxlength="10" data-parsley-trigger-after-failure="focusout" required />
                                                                </div>
                                                              </div>
                                                               <div class="form-group">
                                                                    <label class="col-sm-4 control-label"></label>
                                                                    <div class="col-sm-6">
                                                                        <div id="cmsfrm-error" style="color:#f00;"></div>
                                                                    </div>
                                                               </div>
                                                          </div>
                                                         
                                                           <div class="form-group">
                                                                <label for="query" class="col-sm-4 control-label">Query In Details *</label>
                                                                <div class="col-sm-6">
                                                                     <textarea class="form-control" id="qurytxtarea" name="qurytxtarea" maxlength="1000" data-parsley-maxlength="1000" required><?php echo set_value('qurytxtarea');?></textarea>
(Max 1000 Characters) 
                                                                </div>
                                                          </div>
                                                          
                                                          <div class="form-group">
                                                                <label for="queryfile" class="col-sm-4 control-label">Upload File</label>
                                                                <div class="col-sm-6">
                                                            
                                                                       <input type="file" name="queryfile" class="form-control noborder" id="queryfile" />
                                                                     <input type="hidden" id="hiddenqueryfile" name="hiddenqueryfile" />
                                                                     <div id="error_queryfile"></div><br>
                                                                     <div id="queryfile_text"></div>
                                                                </div>
                                                          </div>
                                                          
                                                         <div class="form-group">
                                                            <label for="captcha" class="col-sm-4 control-label">Security Code *</label>
                                                            <div class="col-sm-6">
                                                            	<input type="text" name="code" id="code" required class="form-control" >
                                                                 <span class="error" id="captchaid" style="color:#B94A48;"></span>
                                                            </div>
                                                            
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <label for="captchaimg" class="col-sm-4 control-label"></label>
                                                            <div class="col-sm-3">
                                                                 <div id="captcha_img"><?php echo $image;?></div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                  <a href="javascript:void(0);" id="new_captcha" class="forget" >Change Image</a>
                                                            </div>
                                                      </div>
                                                          
                                                       	</div>
                                                        <div class="box-footer">
                                                        	<div class="col-sm-4 col-xs-offset-4 text-center">
                                                            	<input type="button" class="btn btn-info complaint-submit" name="btnSubmit" id="btnSubmit" value="Submit">
                                                                
                                                             </div>
                                                        </div>
                                         			</div>
                                                 </div>
                                  			</div>
                                        </section>
                                </form>
                         </div>
                     </div>
                 </div>
                 <div class="col-md-2"></div>
           </div>
       </section>
   </div>
</div>
<script src="<?php echo base_url();?>assets/admin/dist/js/jquery.date-dropdowns.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	
	$("#btnSubmit").on("click", function(e) {
		var code = $('#code').val();
		var flag = $('#cmsfrm').parsley().validate();
		if( code != '' && flag ) {
			var site_url="<?php echo base_url();?>";
			$.ajax({
				url: site_url+'CmsComplaint/ajax_check_captcha',
				type: 'post',
				data:'code='+code,
				success: function(result) {
					if(result=='true') {
						$("#cmsfrm").submit();
					}
					else {
						$('#captchaid').html('Enter valid captcha code.');
					}
				},
				error: function(e) {
					console.log('Error occured: ' + JSON.stringify(e));
				}
			});
		}
	});
	$('#cmsfrm').parsley('validate');
	$("#dateofbirth").dateDropdowns({
		submitFieldName: 'dateofbirth',
		minAge: 0,
		maxAge:69
	});
	$('#new_captcha').click(function(event){
		event.preventDefault();
		var sdata = {'captchaname':'cmscaptcha'};
		$.ajax({
			type: 'POST',
			data: sdata,
			url: site_url+'CmsComplaint/generatecaptchaajax/',
			success: function(res)
			{	
				if(res!='')
				{
					$('#captcha_img').html(res);
				}
			}
		});
	});
});
</script>
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
.noborder {
	border:none !important;
}
</style>