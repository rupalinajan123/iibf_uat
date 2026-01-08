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

<?php //echo '<pre>';print_r($user_info_details[0]);die;?>
	<div class="container">
		<!--<section class="content-header">
			<h1 class="register">Thank you</h1><br/>
		</section>-->
		<section class="content">
			<div class="row">
				<div class="col-md-12">
					
					<!-- Thank you -->
					<div class="box box-info">
						<div class="box-header with-border" align="center">
							<h3 class="box-title">Your application is saved successfully.</h3><br>
							<h3 class="box-title">Your Enrolment No is <?php echo $user_info_details[0]['regnumber'];?></h3><br>
							<h3 class="box-title">Please note down your Enrolment No for further reference.</h3><br>
							<h3 class="box-title">You can save the system generated application form as PDF for future reference</h3>
						</div>
                        
                        <div class="box-body">
                       	 <div class="">
                  	      <label for="roleid" class="col-sm-3 control-label">Enrolment No</label>
                    		    <div class="col-sm-1">
                    			    <?php echo $user_info_details[0]['regnumber'];?>
                   			     </div>
          			      </div>
						</div>
						
						<div class="box-body">
							<div class="">
								<label for="roleid" class="col-sm-3 control-label">Name</label>
								<div class="col-sm-6">
									<?php echo $user_info_details[0]['name'];?>
								</div>
							</div>
						</div>
                      
						<div class="box-body">
							<div class="">
								<label for="roleid" class="col-sm-3 control-label">Date of Birth</label>
								<div class="col-sm-6">
									<?php echo date('d-M-Y',strtotime($user_info_details[0]['dob']));?>
								</div>
							</div>
						</div>
                      
						<div class="box-body">
							<div class="">
								<label for="roleid" class="col-sm-3 control-label">Address</label>
								<div class="col-sm-8">
									<?php echo $user_info_details[0]['address1'].' '.$user_info_details[0]['address2'].' '.$user_info_details[0]['address3'].' '.$user_info_details[0]['address4']; ?>
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="">
								<label for="roleid" class="col-sm-3 control-label">Pincode</label>
								<div class="col-sm-1">
									<?php echo $user_info_details[0]['pincode_address']; ?>
								</div>
							</div>
						</div>
						
                      <div class="box-body">
                       	 <div class="">
                  	      <label for="roleid" class="col-sm-3 control-label">Mobile Number</label>
                    		    <div class="col-sm-1">
                    			    <?php echo $user_info_details[0]['mobile_no'];?>
                   			     </div>
          			      </div>
              		  </div>
                      
                      <div class="box-body">
                       	 <div class="">
                  	      <label for="roleid" class="col-sm-3 control-label">Email ID</label>
                    		    <div class="col-sm-1">
                    			    <?php echo $user_info_details[0]['email_id'];?>
                   			     </div>
          			      </div>
              		  </div>
                     
					 
					  
					  <div class="box-body">
                       	 <div class="">
                  	      <label for="roleid" class="col-sm-3 control-label">Sponsor</label>
                    		    <div class="col-sm-1">
                    			    <?php echo ucfirst($user_info_details[0]['sponsor']);?>
                   			     </div>
          			      </div>
              		  </div>
					  
                      
                      
                     
                      
                        <div class="box-body">
                       	 <div class="">
                  	      <label for="roleid" class="col-sm-3 control-label">Registration Status</label>
                    		    <div class="col-sm-5">
                    			       Successfully registered
                   			     </div>
          			      </div>
              		  </div>
                      
                       
             	
              	  		<div style="text-align:left">
                        <a href="<?php echo base_url()?>Amp/exampdf_bank/<?php echo base64_encode($user_info_details[0]['id']); ?>">Save as pdf</a> &nbsp; &nbsp;
						<a href="<?php echo base_url('amp/bank'); ?>" target="_blank" >Logout</a>
                        </div>
			 
					</div>
					<!-- Thank you box close -->
					
				</div>
			</div>
		</section>
	</div>
	
	<script>
(function (global) {

	if(typeof (global) === "undefined")
	{
		throw new Error("window is undefined");
	}

    var _hash = "!";
    var noBackPlease = function () {
        global.location.href += "#";

		// making sure we have the fruit available for juice....
		// 50 milliseconds for just once do not cost much (^__^)
        global.setTimeout(function () {
            global.location.href += "!";
        }, 50);
    };
	
	// Earlier we had setInerval here....
    global.onhashchange = function () {
        if (global.location.hash !== _hash) {
            global.location.hash = _hash;
        }
    };

    global.onload = function () {
        
		noBackPlease();

		// disables backspace on page except on input fields and textarea..
		document.body.onkeydown = function (e) {
            var elm = e.target.nodeName.toLowerCase();
            if (e.which === 8 && (elm !== 'input' && elm  !== 'textarea')) {
                e.preventDefault();
            }
            // stopping event bubbling up the DOM tree..
            e.stopPropagation();
        };
		
    };

})(window);
</script>