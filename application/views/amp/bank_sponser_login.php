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
<?php // echo '<pre>',print_r($aCandidate),'</pre>';?>
	<div class="container">
	    <?php if($aCandidate== '') {?>
		<section class="content-header">
			<h1 class="register">Login Form (For AMP Candidates)</h1><br/>
		</section>
		<?php }else{ ?>
		<section class="content-header">
			<h1 class="register">AMP Bank Sponsored Candidate Details</h1><br/>
		</section>
		<?php }?>
		<span class="error">
			<?php
				echo validation_errors();
				
			?>
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
		</span>
		<section class="content">
			<div class="row">
				<div class="col-md-12">
					<form class="form-horizontal" method="post" data-parsley-validate="parsley">
					<!-- Search form -->
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Login</h3>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Enter temporary no.<span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="searchStr" name="searchStr" value="<?php echo set_value('searchStr');?>" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" required >
								</div>
								<div class="col-sm-3">
									<input type="hidden" name="form_type" value="search_form" />
									<input type="submit" name="submit" value="Search" />
								</div>
							</div>
						</div>
						
					</div>
					</form>
					<!-- Search form Box close -->
					
					<!-- Candidate Details -->
					<?php if(!empty($aCandidate)){ ?>
					<!--<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Candidate Details</h3>
						</div>
						
						<table class="table table-bordered">
							<thead>
							<tr>
								<!--<th>Membership no.</th>--
								<th>Name</th>
								<th>Email Id</th>
								<th>Mobile No.</th>
								<th>&nbsp;</th>
							</tr>
							</thead>
							<?php foreach($aCandidate as $candidate){ ?>
							<tbody>
							<tr>
								<!--<td><?php //echo $candidate['regnumber']; ?></td>--
								<td><?php echo $candidate['name']; ?></td>
								<td><?php echo $candidate['email_id']; ?></td>
								<td><?php echo $candidate['mobile_no']; ?></td>
								<td>
								<a href="<?php echo base_url().'amp/bank_installment/'.$candidate['id']; ?>">
									Payment
								</a>
								</td>
							</tr>
							</tbody>
							<?php } ?>
						</table>
					</div>-->
	<form class="form-horizontal" name="ampForm" id="ampForm"  method="post" action="<?php echo base_url();?>Amp/proforma_payment" enctype="multipart/form-data">
	
		
		<span class="error">
			<?php echo validation_errors(); ?>
		</span>		
		<!--<a class="right" href="javascript:void(0);" onclick="window.history.go(-1)" >Back </a>-->
		
			<div class="row">
				<div class="col-md-12">
					<?php if($aCandidate[0]['sponsor']=='bank'){ ?>
					<!-- Sponsor Details -->
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Sponsor Details</h3>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Name Of Sponsored Bank <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<?php echo $aCandidate[0]['sponsor_bank_name']; ?>
								</div>
							</div>
						</div>
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Address line1<span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<?php echo $aCandidate[0]['bank_address1']; ?>
								</div>
							</div>
						</div><div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Address line2</label>
								<div class="col-sm-5">
									<?php echo $aCandidate[0]['bank_address2']; ?>
								</div>
							</div>
						</div><div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Address line3</label>
								<div class="col-sm-5">
									<?php echo $aCandidate[0]['bank_address3']; ?>
								</div>
							</div>
						</div>
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Address line4</label>
								<div class="col-sm-5">
									<?php echo $aCandidate[0]['bank_address4']; ?>
								</div>
							</div>
						</div>
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">City<span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<?php echo $aCandidate[0]['bank_city']; ?>
								</div>
							</div>
						</div><div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">State<span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<?php echo $aCandidate[0]['bank_state']; ?>
								</div>
							</div>
						</div><div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Pincode<span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<?php echo $aCandidate[0]['bank_pincode']; ?>
								</div>
							</div>
						</div>
					
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Department Email <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<?php echo $aCandidate[0]['sponsor_email']; ?>
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Contact person name <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<?php echo $aCandidate[0]['sponsor_contact_person']; ?>
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Contact person Designation <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<?php echo $aCandidate[0]['sponsor_contact_designation']; ?>
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Contact person STD code </label>
								<div class="col-sm-5">
									<?php if($aCandidate[0]['sponsor_contact_std']!=0){ echo $aCandidate[0]['sponsor_contact_std']; } ?>
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Contact person Phone No. </label>
								<div class="col-sm-5">
									<?php if($aCandidate[0]['sponsor_contact_phone']!=0){ echo $aCandidate[0]['sponsor_contact_phone']; } ?>
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Contact person Mobile number <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<?php echo $aCandidate[0]['sponsor_contact_mobile']; ?>
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Contact person Email id <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<?php echo $aCandidate[0]['sponsor_contact_email']; ?>
								</div>
							</div>
						</div>	
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">GSTIN No.<span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<?php echo $aCandidate[0]['gstin_no']; ?>
								</div>
							</div>
						</div>
						
					</div>
					<!-- Sponsor Details Box close -->
					<?php } ?>
					
					<!-- Basic Details -->
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Basic Details</h3>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Name <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<?php echo $aCandidate[0]['name']; ?>
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">IIBF Membership no </label>
								<div class="col-sm-5">
									<?php echo $aCandidate[0]['iibf_membership_no']; ?>
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Date of Birth <span style="color:#F00">*</span></label>
								<div class="col-sm-6">
									<?php echo date('d-M-Y',strtotime($aCandidate[0]['dob'])); ?>
									<?php //echo $aCandidate[0]['bday'].'-'.$aCandidate[0]['bmonth'].'-'.$aCandidate[0]['byear']; ?>
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Office/Residential Address for communication  <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<?php echo $aCandidate[0]['address1'].' '.$aCandidate[0]['address2'].' '.$aCandidate[0]['address3'].' '.$aCandidate[0]['address4']; ?>
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">City <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<?php echo $aCandidate[0]['city']; ?>
								</div>
							</div>
						</div>
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">State <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<?php echo $aCandidate[0]['state']; ?>
								</div>
							</div>
						</div>
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Pincode <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<?php echo $aCandidate[0]['pincode_address']; ?>
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">STD code </label>
								<div class="col-sm-5">
									<?php if($aCandidate[0]['std_code']!=0){ echo $aCandidate[0]['std_code']; } ?>
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Phone No. </label>
								<div class="col-sm-5">
									<?php if($aCandidate[0]['phone_no']!=0){ echo $aCandidate[0]['phone_no']; } ?>
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Mobile No. <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<?php echo $aCandidate[0]['mobile_no']; ?>
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Email ID <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<?php echo $aCandidate[0]['email_id']; ?>
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Alternate Email ID </label>
								<div class="col-sm-5">
									<?php echo $aCandidate[0]['alt_email_id']; ?>
								</div>								
							</div>
						</div>
						
					</div> 
					<!-- Basic Details box closed-->
					
					<!-- Educational Qualification -->
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Educational Qualification</h3>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Graduation </label>
								<div class="col-sm-5">
									<?php echo $aCandidate[0]['graduation']; ?>
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Post Graduation </label>
								<div class="col-sm-5">
									<?php echo $aCandidate[0]['post_graduation']; ?>
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Special Qualification</label>
								<div class="col-sm-5">
									<?php echo $aCandidate[0]['special_qualification']; ?>
								</div>
							</div>
						</div>
					</div> 
					<!-- Educational Qualification Box close -->
					
					<!-- Work experience details (present Employer) -->
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Work Experience Details (Present Employer)</h3>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Name of the Employer</label>
								<div class="col-sm-5">
									<?php echo $aCandidate[0]['name_employer']; ?>
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Position</label>
								<div class="col-sm-5">
									<?php echo $aCandidate[0]['position']; ?>
								</div>
							</div>
						</div>
						
						
						
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Till Present</label>
								<div class="col-sm-5">
									<?php if($aCandidate[0]['till_present']==1){ echo 'Yes'; }else{ echo 'No';} ?>
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Total Experience in month</label>
								<div class="col-sm-5">
									<?php if($aCandidate[0]['work_experiance']!=0){ echo $aCandidate[0]['work_experiance']; } ?>
								</div>
							</div>
						</div>
						
					</div>
					<!-- Work experience details (present Employer) Box close -->
					
					<!-- Photograph and Signature -->
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Photograph, ID Proof and Signature</h3>
						</div>
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Photograph of the Candidate <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<img src="<?php echo base_url().'uploads/amp/photograph/'.$aCandidate[0]['photograph']; ?>" height="100" width="100"/>
								</div>
							</div>
						</div>
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">ID Proof of the Candidate <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<img src="<?php echo base_url().'uploads/amp/idproof/'.$aCandidate[0]['idproof']; ?>" height="100" width="100"/>
								</div>
							</div>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">Signature of the Candidate <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<img src="<?php echo base_url().'uploads/amp/signature/'.$aCandidate[0]['signature']; ?>" height="100" width="100"/>
								</div>
							</div>
						</div>
					</div>
					<!-- Photograph and Signature box close -->
					
					<!-- Payment Details -->
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Payment Details</h3>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-12 control-label " style="text-align:left; border-bottom:solid 2px #fff;">Payment Option <span style="color:#F00">*</span></label>
								<div class="col-sm-12">
									<?php if($aCandidate[0]['payment']=='first'){ ?>
									<input type="radio" id="payment" name="payment" required value="second" checked >Second Installment (<?php echo $this->config->item('amp_second_cs_total'); ?>/-) 
									<table border="2" style="width:100%">
									<!--<tr><th>Rs.118000/- in one lump sum</th></tr>-->
									<tr>
									<!--<td>course fee</td>-->
									<td>Fee - 15000/-</td>
									<td>GST - 2700/-</td>
									<td>Total - 17700/-</td>
									</tr>
									</table>
									<?php }elseif($aCandidate[0]['payment']=='second'){ ?>
									<input type="radio" id="payment" name="payment" required value="third" checked >Third Installment (<?php echo $this->config->item('amp_third_cs_total'); ?>/-) 
									<table border="2" style="width:100%">
									<!--<tr><th>Rs.118000/- in one lump sum</th></tr>-->
									<tr>
									<!--<td>course fee</td>-->
									<td>Fee - 15000/-</td>
									<td>GST - 2700/-</td>
									<td>Total - 17700/-</td>
									</tr>
									</table>
									<?php }else{ ?>
									<input type="radio" id="payment" name="payment" required value="first" checked >Full Payment (<?php echo $this->config->item('amp_full_cs_total'); ?>/-) 
									
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
					<!-- Payment Details box close -->
					<div class="box box-info">
						<div class="form-group">								
							<label class="col-sm-5 control-label">&nbsp;</label>
							<div class="col-sm-4">
								<input type="hidden" name="form_type" value="bank_sponser_form" />
								<input type="submit" name="submit" value="Proceed for Payment" />
							</div>
						</div>
					</div>
					
				</div>
			</div>
		
	
</form>

					
					<?php }else{ ?>
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">No Candidate available</h3>
						</div>
					</div>
					<?php } ?>
					<!-- Candidate Details Box close -->
					
				</div>
			</div>
		</section>
	</div>

<link href="<?php echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>