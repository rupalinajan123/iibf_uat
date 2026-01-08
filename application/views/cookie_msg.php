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
	background-color:#f1f1f1;
	border-top-left-radius:10px;
	border-top-right-radius:10px;
}
.header_blue {
	background-color:#2ea0e2 !important;
	color:#fff !important;
}
.box {
	border:1px solid #00c0ef;
	box-shadow:none;
	border-radius:10px;
}
.nobg {
	background:none !important;
	border:none !important;
}
.box-title-hd {
	color:#3c8dbc;
	font-size:16px;
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
	margin-top:0;
	margin-bottom:20px !important;
	font-size:16px;
	line-height:24px;
	padding:0 5px;
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

</style>
 <?php 
header('Cache-Control: must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>

<div class="container">
  <h2></h2>
  <!-- Trigger the modal with a button -->
 

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Instructions to Applicants</h4>
        </div>
        <div class="modal-body">
         <table>
			<tbody><tr>
				<td><b>1. </b></td> <td> <b><u> Eligibility:</u></b> </td>
			</tr>
			
			<tr>
				<td>&nbsp;</td>
				<td> a) &nbsp;Membership is open to the employees of recognized banking establishments both in the nationalized as well as private sector including the Reserve Bank of India, State Bank of India, other Financial Institutions,  both Central and State  Co-operative Banks and any other institutions in India  who are Institutional Members of the Institute as may be approved by the Council from time to time. </td>
			</tr>
			
			<tr>
				<td>&nbsp;</td>
				<td> b) &nbsp;<b>If the name of your organization does not appear in the drop box, please mail to <a href="mailto:mem-services@iibf.org.in">mem-services@iibf.org.in</a> with details of your organization.</b> </td>
			</tr>

			<tr> <td>&nbsp;</td> </tr>

			<tr>
				<td><b>2. </b></td> <td> <b><u> Enrolment Fee:</u> </b> </td>
			</tr>
				
			<tr>
				<td>&nbsp;</td>
				<td>One time life Membership fee (for candidates in India) is <b>Rs. 2/-</b> (Fees Rs.1500/- + Service Tax@15% Rs.225/- = Rs.1725/-)</td>
			</tr>

			<tr> <td>&nbsp;</td> </tr>

			<tr>
				<td><b>3. </b></td> <td> <b><u> Pre-requisites for online enrolment for Membership:</u> </b> </td>
			</tr>
				
			<tr>
				<td>&nbsp;</td>
				<td>
				 I - Applicant should have scanned copy of his/her  i) photograph ii) signature and iii) id proof (ensuring that all are within the required specifications as under)  <br>
					<ol type="a">
						<li>Images format should be in JPG 8bit and size should be minimum 8KB and maximum 20KB.</li>
						<li>Image Dimension of Photograph should be 100(Width) <span style="color:#F00">*</span> 120(Height) Pixel only;</li>
						<li>Image Dimension of Signature should be 140(Width) <span style="color:#F00">*</span> 60(Height) Pixel only.</li>
						<li>Image Dimension of ID Proof should be 400(Width) <span style="color:#F00">*</span> 420(Height) Pixel only. ID Proof should contain Name, Photo, Date of Birth and Signature. Size should be minimum 8KB and maximum 25KB.</li>

						<li>ID proof can be any one of the following: </li>

							<ul>
								<li>Aadhaar ID</li>
								<li>Driving License</li>
								<li>Election Voters card</li>
								<li>Employers card</li>
								<li>PAN card</li>
								<li>Passport</li>
							</ul>


					</ol>	
II - To make online payment, an applicant should keep ready the necessary details about his/her Credit/Debit Card/Net Banking Details) 
<br>
III - Applicant should have a valid personal e-mail id. 
<br>			
Note:  Do not upload your Credit Card/Debit Card scanned image with the application.
				</td>
			</tr>
			
			<tr> <td>&nbsp;</td> </tr>

			<tr>
				<td><b>4. </b></td> <td> <b><u> Procedure for Enrolment:</u></b> </td>
			</tr>
			
			<tr>
				<td>&nbsp;</td>
				<td>
					<ol type="i">
						<li>Visit Institute's web site www.iibf.org.in</li>
						<li>Click on 'online membership registration'.</li>
						<li>Read 'Instruction to applicants' carefully.</li>
						<li>Fill up all the online application form,(all the fields mark '<span style="color:#F00">*</span>' are mandatory), upload photo, signature, ID proff and follow the on-screen instructions to complete the registration process.</li>
						<li>On successful completion of enrolment a confirmation SMS/email will be sent to the candidate intimating the membership number(membership number is your login id), password.</li>
						<li>In case, even after 2/3 working days after enrolment  no confirmation is received intimating the membership details,  applicant should  take  up the matter with the Institute. (write to <a href="mailto:onlineservices@iibf.org.in">onlineservices@iibf.org.in</a> providing following details: 1) Membership number 2) Full Name 3) Date of Birth 4) Payment transaction details 5) Mobile no. and details problem/exact text of error message etc..)</li>
						<li>For all failed transactions the amount is debited to applicant's Account, Institute will  refund such amount within seven  working days from the date of the transaction..</li>
					</ol>
				</td>
			</tr>
			
			<tr>
				<td><b>5. </b></td> <td> <b><u>General:</u> </b> </td>
			</tr>
						
			<tr>
				<td>&nbsp;</td>
				<td>
					<ol type="a">
						<li>A permanent I-card will be mailed to the applicant at the address given in the application.</li>
						<li>The permanent I-card should be produced at the time of examination and whenever demanded for availing other services.</li>
						<li>Enrolled members can view his/her profile/payment details using the login/password and also update his/her profile except for name, date of birth, photo, signature and ID proof.</li>
						<li>Enrolled member can also apply for all other services of the Institute with the same login and password as and when available.</li>
						<li>Any communication in regard to membership enrolment should sent to <a href="mailto:onlineservices@iibf.org.in">onlineservices@iibf.org.in</a> </li>
					</ol>		
				</td>
			</tr>
				
	
		
		</tbody></table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
</div>
  <!-- Content Wrapper. Contains page content -->
  <div class="container">
<!--  <img src="<?php echo base_url();?>assets/images/iibf_logo_black.png" class="ifci_logo_black" />-->
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="register"> 
       Ordinary Membership Registration
(Please read <a data-toggle="modal" data-target="#myModal" style="cursor:pointer;">"Instructions to Applicants"</a> before filling up the form)
        
      </h1>
      <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>-->
    </section>
	<form class="form-horizontal" name="usersAddForm" id="usersAddForm"  method="post"  enctype="multipart/form-data">
   	<section class="content">
       
      <div class="row">
        <!--<div class="col-md-1"></div>     -->     
            <div class="col-md-12">
            	<h4> I, as an employee of the bank/financial institution mentioned below, apply myself for being admitted as an Ordinary Member of Indian Institute of Banking &amp; Finance (I have never been a Member of the Institute in the past.)
            Please enter your details carefully, correction may not be possible later
            </h4>
        </div>
        <!--<div class="col-md-1"></div>-->          
      </div>
      <div class="row">
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Under Review</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
               <div class="form-group">
                	<div class="col-sm-8">
                       <span style="color:#F00">Wait for 20 min, your transaction is under process!</span>
                    </div>
                </div>
              </div>
             </div> <!-- Basic Details box closed-->
            </div>
      </div>
    </section>
    </form>
  </div>

