
<!DOCTYPE html>
<html>
	<head>
		<?php $this->load->view('scribe_form/inc_header'); ?>
		<style type="text/css">
		p.solid 
		{
			border-style: solid;
			border-right-style: none;
  			border-left-style: none;
  			border-width: 3px;
  			border-color: #1287c0;
		}
		#scribe
			{
				text-align: left;
			    background-color: #1287c0;
			    margin: 10px 0;
			    border-radius: 2px;
			    color: #fff;
			    font-size: 15px;
			    line-height: 24px;
			}
		.main-footer
			{
				border-top: none;
			}
		#row1,#row2{
			
			    display: flex;
			    flex-wrap: nowrap;
			}
		#refresh1,#refresh2
			{
				font-size: 12px;
				line-height: normal;
				margin-left: 5px;
			}
		.content-header h1.register{
			text-decoration: none;
			}
		.questions{
			    text-align:center;
			    width: 95%;
			    background-color: #1287c0;
			    padding: 5px 0;
			    border-radius: 0;
			    color: #fff;
			    font-size: 18px;
			    line-height: 24px;
			}
			.answer
			{
				padding: 10px; 
				margin: auto; 
				text-indent: 50px; 
				text-align: center; 
				width: 90%; 
				font-size: 15px;
			}
			.faq{
			    text-align: center;
			    background-color: #1287c0;
			    padding: 10px 5px;
			    border-radius: 0;
			    color: #fff;
			    font-size: 18px;
			    line-height: 24px;
			}
			.form-horizontal{
				padding: 12px;
			}
		@media screen and (max-width: 480px) 
		{
			.login-logo a 
			{
				text-align: center;
				font-size: 18px;
				display: inline-block;
			}
			label
			{  padding: 2%; }

			.container 
			{   width: 90%; }
			.main-header 
			{  width: 90%; }
		}						
		</style>
		
	</head>
	
	<body class="hold-transition skin-blue layout-top-nav">
		<div class="wrapper">
			<div><?php $this->load->view('scribe_form/inc_navbar'); ?></div>
			
			<div class="container1">				
				<section class="content">
					<?php if($this->session->flashdata('error')!=''){?>								
						<div class="alert alert-danger alert-dismissible" id="error_id">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<?php echo $this->session->flashdata('error'); ?>
						</div>								
						<?php } 
						
						if($this->session->flashdata('success')!=''){ ?>
						<div class="alert alert-success alert-dismissible" id="success_id">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<?php echo $this->session->flashdata('success'); ?>
						</div>
					<?php } ?>
								
					<div class="col-md-12" >  						
						<div  class ="row" id="form2" style="display:block;">
							<div class="box box-info">
								<form class="form-horizontal" name="usersAddForm" id="usersAddForm2"  method="post"  action="<?php echo site_url('Scribe_form/getDetails_Special/'); ?>" autocomplete="off">
									<h3 class="alert text-center text-dark text-bold">Apply for special assistance/extra time</h3>
									<div class="form-group">
										<label for="roleid" class="col-sm-4 control-label">Membership/Registration No.<span style="color:#F00">*</span></label>
										<div class="col-sm-6">
											<input type="text" class="form-control" id="member_no" name="member_no" placeholder="Membership/Registration No" required value="<?php echo set_value('member_no'); ?>" >
											<?php if(form_error('member_no')!=""){ ?><label class="error"><?php echo form_error('member_no'); ?></label> <?php } ?>
										</div>
									</div>	
									<div class="form-group">
										<label for="roleid" class="col-sm-4 control-label">Exam Name<span style="color:#F00">*</span></label>
										<div class="col-sm-6">
											<select class="form-control chosen-select" id="exam_code2" name="exam_code" required autofocus data-placeholder="<?php if(!empty($active_exam_data) && count($active_exam_data) > 0) { echo 'Select Exam'; } else { echo 'No Exam Available'; } ?>">
											<?php
												if(!empty($active_exam_data) && count($active_exam_data) > 0)
												{
													foreach($active_exam_data as $active_exam_res)
													{ ?>
													<option value="<?php echo $active_exam_res['exam_code']; ?>" <?php if(set_value('exam_code') != '') { $exam_code_arr = set_value('exam_code'); } else { $exam_code_arr = $exam_code; } if(is_array($exam_code_arr) && in_array($active_exam_res['exam_code'],$exam_code_arr)) { echo "selected = 'selected'"; } ?>><?php if($active_exam_res['description'] != "") { echo $active_exam_res['description']." - "; } if($active_exam_res['exam_code'] == '2027'){echo '1017';}else{ echo $active_exam_res['exam_code']; }?></option>
													<?php	}
												} ?>
										</select>
										<?php if(form_error('exam_code')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('exam_code'); ?></label> <?php } ?>
										</div>
									</div>

									<div class="form-group">
										<label for="roleid" class="col-sm-4 control-label">Subject Name<span style="color:#F00">*</span></label>
										<div class="col-sm-6">
											<select id='sel_subject2' class="form-control chosen-select" name="subject_code" >
									          <option>-- Select subject --</option>
									        </select>
										</div>
									</div>		
									
									<div class="form-group">
										
										
										<label for="roleid" class="col-sm-4 control-label" style="line-height:20px;">Security Code <span style="color:#F00; ">*</span></label>										
										
										<div class="col-sm-3">
											<input type="text" name="captcha_code" id="captcha_code" required class="form-control" placeholder="Security Code" maxlength="5" value="">
										</div>										
										
										<div class="col-sm-5" id="row2">
											<div id="captcha_imgs"><?php echo $captcha_img; ?></div>
											<a id="refresh2" href="javascript:void(0);" onclick="refresh_captcha_img();" class="text-danger btn btn-info"><i class="fa fa-refresh" aria-hidden="true"></i></a>
										</div>
									</div>
																		
									<div class="col-sm-12 text-center">
										<input type="submit" class="btn btn-info" name="btn_Submit" id="btn_Submit" value="Get Details">&nbsp;&nbsp;
                    
                   
                    
                    			<input type="button" class="btn btn-info" value="Cancel" onclick="window.location='<?php echo base_url('Scribe_form/index');?>'">
									</div>
								</form>
							</div>
							
						</div>
						<!-- POOJA MANE SCRIBE OPTION Second form END: 25/07/2022 -->
						
					</div>
				</section>
			</div>
		</div>
				<div style="text-align: center; width: 100%; margin-top: 10%;">
			        <a class="disability" style="color: #1287c0;" target="_blank" href="https://iibf.esdsconnect.com/uploads/Scribe_Guideline_2024.pdf">
			            <u>GUIDELINES/RULES FOR USING SCRIBE BY VISUALLY IMPAIRED &amp; ORTHOPEDICALLY CHALLENGED CANDIDATES</u>
			        </a>
			    </div>

		<footer class="footer"><?php $this->load->view('scribe_form/inc_footerbar'); ?></footer>
	 	<!-- Script -->
  		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css" rel="stylesheet" />
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>			
		<script type="text/javascript">
			$(document).ready(function(){
    			setTimeout(function() {
        			$("#error_id").hide();
    			}, 5000);
			});
		</script>

		<script>
		$(document ).ready( function() 
		{	
			
        
	        $.validator.addMethod("custom_check_member_no_ajax", function(value, element)
	        {
	          if($.trim(value).length == 0) { return true; }
	          else
	          {
	            var isSuccess = false;
	            var parameter = { "member_no":$.trim(value) }
	            $.ajax(
	            {
	              type: "POST",
	              url: "<?php echo site_url('Scribe_form/check_member_no_ajax') ?>",
	              data: parameter,
	              async: false,
	              dataType: 'JSON',
	              success: function(data)
	              {
	                if($.trim(data.flag) == 'success')
	                {
	                  isSuccess = true;
	                }
	                else
	                {
						refresh_captcha_img();
	                }

                	$.validator.messages.custom_check_member_no_ajax = data.response;
              	  }
                });
            
               return isSuccess;
              }
            }, '');
				
		       // $("#usersAddForm1").validate( 
			// 	{
		       //    onkeyup: false,
		       //    onclick: false,
		       //    onblur: false,
		       //    onfocusout: false,
		       //    rules:
			// 		{
			// 			member_no: { required : true, custom_check_member_no_ajax:true  }, 					
			// 			/* val3: { required : true, check_captcha : true }, */  		
			// 			captcha_code: { required : true, remote: { url: "<?php echo site_url('Scribe_form/check_captcha_code_ajax') ?>", type: "post", data: { "session_name": "LOGIN_SCRIBE" } } },  		
			// 		},
			// 		messages:
			// 		{
			// 			member_no: { required : "Please enter Membership/Registration No", custom_check_member_no_ajax : "Please enter valid Membership/Registration No" },
			// 			/* val3: { required : "Please enter code" } */
			// 			captcha_code: { required : "Please enter code", remote:"Please enter valid captcha" }
			// 		}
			// 	});
		        $("#usersAddForm2").validate( 
						{
		          onkeyup: false,
		          onclick: false,
		          onblur: false,
		          onfocusout: false,
		          rules:
					{
						member_no: { required : true, custom_check_member_no_ajax:true  }, 					
						/* val3: { required : true, check_captcha : true }, */  		
						captcha_code: { required : true, remote: { url: "<?php echo site_url('Scribe_form/check_captcha_code_ajax') ?>", type: "post", data: { "session_name": "LOGIN_SCRIBE" } } },  		
					},
					messages:
					{
						member_no: { required : "Please enter Membership/Registration No", custom_check_member_no_ajax : "Please enter valid Membership/Registration No" },
						/* val3: { required : "Please enter code" } */
						captcha_code: { required : "Please enter code", remote:"Please enter valid captcha" }
					}
				});
			});	
		
			function refresh_captcha_img()
			{
				$(".loading").show();
				$.ajax(
				{
					type: 'POST',
					url: '<?php echo site_url("Scribe_form/generate_captcha_ajax/"); ?>',
					data: { "session_name":"LOGIN_SCRIBE" },
					async: false,
					success: function(res)
					{	
						if(res!='')
						{
							$('#captcha_img').html(res);
							$('#captcha_imgs').html(res);
							$("#captcha_code").val("");
							$("#captcha_code-error").html("");
						}
						$(".loading").hide();
					}
				});
			}

			/*Exam Change POOJA MANE : 12-9-2022*/		
			// Exam change
			    // $('#exam_code1').change(function()
			    // {
			    //   var exam_code = $('#exam_code1').val();
			    //     //alert(exam_code);
			    //      // AJAX request
			    //     $.ajax(
			    //     {
			    //       url:'<?=base_url()?>Scribe_form/getSubjects',
			    //       method: 'post',
			    //       data: {exam_code: exam_code},
			    //       dataType: 'json',
			    //       success: function(response)
			    //       {
			    //         //alert(response);

			    //         // Remove options 
			    //           $('#sel_subject1').find('option').not(':first').remove();
			    //           // Add options
			    //           $.each(response,function(index,subjects)
			    //           {
			    //            $('#sel_subject1').append('<option value="'+subjects['subject_code']+'">'+subjects['subject_description']+'</option>'); 
			    //           });
			    //       }
			    //     });
			    // });
			/*Exam Change End POOJA MANE : 12-9-2022*/	

			/*Exam Change POOJA MANE : 12-9-2022*/		
			// Exam change
			    $('#exam_code2').change(function()
			    {
			      var exam_code = $('#exam_code2').val();
			        //alert(exam_code);
			         // AJAX request
			        $.ajax(
			        {
			          url:'<?=base_url()?>Scribe_form/getSubjects',
			          method: 'post',
			          data: {exam_code: exam_code},
			          dataType: 'json',
			          success: function(response)
			          {
			            //alert(response);

			            // Remove options 
			              $('#sel_subject2').find('option').not(':first').remove();
			              // Add options
			              $.each(response,function(index,subjects)
			              {
			               $('#sel_subject2').append('<option value="'+subjects['subject_code']+'">'+subjects['subject_description']+'</option>'); 
			              });
			          }
			        });
			    });
			/*Exam Change End POOJA MANE : 12-9-2022*/		
		</script>
		
		<script>	
			$( document ).ready( function () { $('.loading').delay(0).fadeOut('slow'); });
			/* $(document).ready(function() { setTimeout(function() { $('#alert_fadeout').fadeOut(3000); }, 8000 ); }); */
		</script>

		<!-- POOJA MANE SCRIBE OPTION : 25/07/2022 -->
		<!-- <script type="text/javascript">
			function showFunction1() {
			  var x = document.getElementById("form1");
			  var y = document.getElementById("form2");
			  if (y.style.display === "block") {
			    y.style.display = "none";
			    x.style.display = "block";
			  }
			  else{
			  	x.style.display = "block";
			  }
			  } 
			function  showFunction2(){
			   var x = document.getElementById("form1");
			   var y = document.getElementById("form2");
			  if (x.style.display === "block") {
			    x.style.display = "none";
			    y.style.display = "block";
			  }
			  else{
			  	y.style.display = "block";
			  }
			  }
			
		</script> -->
		<!-- POOJA MANE SCRIBE OPTION : 25/07/2022 -->
		
		<!-- Do you want to apply again prompt 08/12/2022 -->
		<script type="text/javascript">
			function change_scribe()
		    {
		        var member_no = $("#member_no").val(); 
		        var exam_code = $("#exam_code1").val(); 
		        var sel_subject = $("#sel_subject1").val();
		        var scribe_uid = '';
		        alert(member_no);
		        alert(exam_code);
		        alert(sel_subject);
		        $.ajax(
		        {
		          type: 'POST',
		          url: '<?php echo site_url("Scribe_form/change_scribe"); ?>',
		          data: { exam_code : $("#exam_code1").val(), member_no: $("#member_no").val(), sel_subject: $("#sel_subject1").val()  },
		          async: false,
		          success: function(res)
		          { 
		           	//console.log(res);
		           	if(res =='true')
		           	{
		           		
			           	ConfirmDialog('You have already taken the Scribe against the Selected Exam. Do You Want to Change the scribe?',member_no,exam_code,sel_subject);
			           	
					}	
					else{
							
							var URL = 'staging/Scribe_form/getDetails_Special/'+member_no+'/'+exam_code+'/'+sel_subject;
						     window.location.pathname = URL;
						}

		          }
		        });
		    }

		    /*DIALOG BOX*/
			    		function ConfirmDialog(message,member_no,exam_code1,sel_subject1) {

						  $('<div></div>').appendTo('body')
						    .html('<div><h6>' + message + '?</h6></div>')
						    .dialog({
						      modal: true,
						      title: 'Delete message',
						      zIndex: 10000,
						      autoOpen: true,
						      width: 'auto',
						      resizable: false,
						      buttons: {
						        Yes: function() {

						          $('body').append('<h1>Confirm Dialog Result: <i>Yes</i></h1>');

						          var URL = 'Scribe_form/getDetails_Scribe/'+member_no+'/'+exam_code1+'/'+sel_subject1;
						          window.location.pathname = URL;
						          
						        },
						        No: function() {
						          

						          $(this).dialog("close");
						        }
						      },
						      close: function(event, ui) {
						        $(this).remove();
						      }
						    });
						    event.preventDefault();

						};
			/*DIALOG BOX END*/
		</script>
		<!-- Do you want to apply again prompt END-->
		</body>
	</html>				