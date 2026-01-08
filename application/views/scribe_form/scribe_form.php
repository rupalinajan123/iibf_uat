
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
			    font-size: 17px;
			    line-height: 24px;
			    border-radius: 20px;
			    padding: 2% 5%;

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
				font-size: 14px;
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
			div.scroll {
            margin: 5px, 5px;
            padding: 10px;
            width: 100%;
            height: 400px;
            overflow-x: hidden;
            overflow-y: auto;
            text-align: justify;
            direction:rtl;
            background-color: white; /* For browsers that do not support gradients */
  			background-image: linear-gradient(90deg, lightgrey, white);
        }
        .styled-button {
            padding: 5px 10px;
            background-color: #1287c0;
            color: white;
            text-transform: uppercase;
            border: none;
            font-size: 16px;
            cursor: pointer;
            position: relative;
            text-decoration: none; /* Assuming it's a link */
            display: inline-block;
            font-family: cambria;
            font-weight: bold;
            text-align: center;
            cursor: default;
            text-decoration: none;
        }
        a:hover {
		  color: white;
		}
		#nohover
		{
		 	color: #1287c0;
		}

        .styled-button::after {
            content: '';
            position: absolute;
            top: 50%;
            right: -20px;
            transform: translateY(-50%);
            border: 10px solid transparent;
            border-left-color: #1287c0;
        }
        .go-button
        {
        	display: inline-block;
        	border-radius: 5px; 
        	border: 2px solid white; 
/*        	justify-content: space-between; */
        	text-align: center; 
        	background-color: #1287c0; 
        	color:white; 
        	font-family:serif; 
        	font-weight: bold; 
        	font-size: 20px; 
        	padding:1% 5% 1% 5%; 
        	margin-left: 20%;
        }
        .apply_for_ss
        {
        	font-family: cambria; 
        	font-weight: bold; 
        	text-align:center; 
        	border: 2px solid #1287c0; 
        	width: 50%; 
        	margin-left: 25%; 
        	font-size: 20px; 
        	color: #1287c0; 
        	padding: 5px; 
        	position:absolute; 
        	background-color: white;
        }

        .button {
		  background-color: white;  /* Green */
		  border: none;
		  border-radius: 7px;
		  color: white;
		  padding: 5px 2px;
		  text-align: center;
		  text-decoration: none;
		  display: inline-block;
		  font-size: 16px;
		  margin: 4px 2px;
		  cursor: pointer;
		  -webkit-transition-duration: 0.4s; /* Safari */
		  transition-duration: 0.4s;
		}
		.button2
		{
			color: #00c1ff; 
			padding: 15px 30px; 
			float: right; 
			width: 200px; 
			height: 60px; 
			font-family: Arial;
		}

		.button1,.button2 {
		  box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2), 0 3px 10px 0 rgba(0,0,0,0.10);
		}

		.green_div
		{
			border: 2px solid green; 
			padding: 2% 2% 5% 2%; 
			margin: 5px; 
			margin-top: 20px; 
			padding-bottom: 130px;
		}
		.proceed{
			margin: auto; 
			margin-top: -25px;
		}
		.guideline
		{
			
			font-family: Calibri; 
			font-size: 18px; 
			padding: 2%;
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

			.apply_for_ss
			{
				font-family: cambria; 
	        	font-weight: bold; 
	        	text-align:center; 
	        	border: 2px solid #1287c0; 
	        	width: 75%; 
	        	margin-left: 10%; 
	        	margin-top: -5%; 
	        	font-size: 15px; 
	        	color: #1287c0; 
	        	padding: 5px; 
	        	position:absolute; 
	        	background-color: white;
			}
			h1#reg{
				text-decoration: none;
				text-align: center;
			}
			#logo-btn
			{
				margin-left: 2%;
				width: 200px;
				height: 35px;
				padding: 0% 2% 0% 2%;
			}
			.button2
			{
				color: #00c1ff; 
				padding: 15px 30px; 
				float: right; 
				width: 180px; 
				height: 50px; 
				font-family: Arial;
				font-size: 15px;
				position: absolute;
				margin-left: 6%;
			}
			.solid{
				margin-top: 8px;
			}
			.go-button
	        {
	        	display: inline-block;
	        	border-radius: 5px; 
	        	border: 2px solid white;
	        	text-align: center; 
	        	background-color: #1287c0; 
	        	color:white; 
	        	font-family:serif; 
	        	font-weight: bold; 
	        	font-size: 18px; 
	        	padding:1% 5% 1% 5%; 
	        	margin-left: 14%;
	        }
	        .green_div
			{
				border: 2px solid green; 
				padding: 5%; 
				margin: 5px; 
				margin-top: 20px; 
				padding-bottom: 30px;
			}
			.proceed
			{
				margin-bottom: 4%;
			}
			.guideline
			{
				
				font-family: Calibri; 
				font-size: 13px;
			}
		}						
		

@media screen and (max-width: 600px) 
		{
			.button2 {
			  box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2), 0 3px 10px 0 rgba(0,0,0,0.10);
			  float: none;
			}
		}
</style>
	<button class="button button1"><input type="image" height="50px" width="280px" id="logo-btn" src="https://iibf.esdsconnect.com/uploads/Logo_2024.png" /></button>
	<button class="button button2" style=""><a id="nohover" href="tel:08069260700"><i class="fa fa-phone" style="font-size:24px"></i>&nbsp;<b>08069260700</b></a></button>
<br>
<div class="top-logo">
	<p class="solid"></p>
	<section class="content-header">
			 <h1 class="register" id='reg' style="width: 400px; border: 1px solid black ; background-color: #00c1ff; font-family: Georgia; margin-bottom: 1%;">Scribe Application</h1>
	</section>
	<p class="solid"></p>
</div>
		
	</head>
	
	<body class="hold-transition skin-blue layout-top-nav">
		<div class="wrapper">
			<div><?php $this->load->view('scribe_form/inc_navbar'); ?></div>
			<div class="container-fluid">
				<div class="row">
				    <div class="col-sm-9" style="padding-right: 4%;">
				     <section class="apply_for_ss" style=""> Apply for Scribe/Special Assistance 
				     </section>
				     <!--3 Gudelines section -->
				     <div class="green_div" >
				     	<section class="guideline" >
				     		<ul style="list-style-type:none;" >
					     		<li>
					     			<span style="color: red;"><b>*</b></span> 
					     			Please read the rules/guidelines for availing the facility of scribe carefully before applying for Scribe
					     		</li></br>
					     		<li>
					     			<span style="color: red;"><b>*</b></span> 
					     			Please ensure that the scribe fulfils the eligibility criteria as prescribed in the rules/guidelines before applying.
					     		</li></br>
					     		<li>
					     			<span style="color: red;"><b>*</b></span> 
					     			Please note that, in case, it is found later that the scribe does not fulfill the eligibility criteria, candidature of the applicant will stand cancelled.
					     		</li><br>
                                <li>
									<a class="disability" style="color: #1287c0;" target="_blank" href="https://iibf.esdsconnect.com/staging/uploads/Scribe_Guideline_2024.pdf">
								            <u>GUIDELINES/RULES FOR USING SCRIBE BY VISUALLY IMPAIRED &amp; ORTHOPEDICALLY CHALLENGED CANDIDATES</u>
								        </a>
	                             </li>
				     		</ul>
				     		<form class="col-md-12" id="scribe">
								<div class="row" >
									<input type="radio" name="option" value="option1">
									<label for="myCheck">Apply for Scribe (Please apply for each subject separately) :</label>
								</div>
								<div class="row" >
									<input type="radio" name="option" value="option2">
									<label for="myCheck">Apply for special assistance/extra time (PWD candidate not opting for scribe) : </label>
								</div>
				     		
				     	</section>
				     </div>
				     <!--3 Gudelines section ends -->
				     <!-- apply buttons div -->
				     <div class="row1 proceed" style="">
				     		<button type="submit" class="go-button">Proceed</button>
				     		<a class="go-button" style="color: white;" target="_blank" href="https://iibf.esdsconnect.com/staging/index.php?/scribe_form/faq">FAQ’s</a>
				     </div>
				    </form>

				    
				    </div>
				    <!-- IMPORTANAT INSTRUCTIONS SCROLLBAR -->
				    <div class="col-sm-3" style="background-color: none;">
				    <a href="#" class="styled-button">Important Instructions</a>
				      <div class="scroll">
				      	
				      	<ul dir="ltr" style="margin:10% 5% 5% 5%; font-family: Georgia;  font-weight: bold; color: red;">
				      		<li>
				      			Candidates desirous of availing scribe facility need to apply online on the IIBF website by clicking on Apply Now> Apply for scribe.</n>
							</li></br>
							<li>
								Only the candidates who have applied Online & obtained prior approval for scribe from IIBF will be allowed to appear with the scribe on the day of the examination.
							</li></br>
							<li>
								Candidates are advised to apply online for scribe well in advance, not later than 3 days before the examination.
							</li></br>
							<li>
							    Suppressing/Misrepresenting material facts regarding the eligibility of scribe may lead to the cancellation of result and attract penal action as deemed fit.
							</li>
							<!-- <li>
								
							</li> -->
				      	</ul>
        			  </div>
				    </div>
				</div>
			</div>
		</div>
			<!-- POOJA MANE SCRIBE OPTION END: 25/07/2022 -->

		
		<footer class="footer1"><?php $this->load->view('scribe_form/inc_footerbar'); ?></footer>
	 	<!-- Script -->
  		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css" rel="stylesheet" />
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>			
		<script>
        document.getElementById("scribe").addEventListener("submit", function (event) { 
            event.preventDefault(); // Prevent form submission

            // Get the selected radio button value
            const selectedOption = document.querySelector('input[name="option"]:checked').value;

            // Redirect based on the selected option
            switch (selectedOption) {
                case "option1":
                    // window.location.href = "https://iibf.esdsconnect.com/staging/index.php?/scribe_form/scribe";
                    window.open('https://iibf.esdsconnect.com/staging/index.php?/scribe_form/scribe','_blank');
                    break;
                case "option2":
                    // window.location.href = "https://iibf.esdsconnect.com/staging/index.php?/scribe_form/special";
                    window.open('https://iibf.esdsconnect.com/staging/index.php?/scribe_form/special','_blank');
                    break;
                default:
                    // Handle any other cases or show an error message
                    console.error("Invalid option selected.");
            }
        });
    </script>
		
	</html>				