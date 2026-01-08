<?php /*?><?php  error_reporting(0);
?><?php */?>
<script src="<?php echo base_url('assets/dist/js/jquery.min.js')?>"></script>
<style>
/* The Modal (background) */
.modal {
	display: none; /* Hidden by default */
	position: fixed; /* Stay in place */
	z-index: 1; /* Sit on top */
	padding-top: 100px; /* Location of the box */
	left: 0;
	top: 0;
	width: 100%; /* Full width */
	height: 100%; /* Full height */
	overflow: auto; /* Enable scroll if needed */
	background-color: rgb(0,0,0); /* Fallback color */
	background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
}
/* Modal Content */
.modal-content {
	position: relative;
	background-color: #fefefe;
	margin: auto;
	padding: 0;
	border: 1px solid #888;
	width: 60%;
	height: 70%; /* Full height */
	-webkit-animation-name: animatetop;
	-webkit-animation-duration: 0.4s;
	animation-name: animatetop;
	animation-duration: 0.4s
}

/* Add Animation */
@-webkit-keyframes animatetop {
 from {
top:-300px;
opacity:0
}
to {
	top:0;
	opacity:1
}
}
 @keyframes animatetop {
 from {
top:-300px;
opacity:0
}
to {
	top:0;
	opacity:1
}
}
/* The Close Button */
.close {
	color: white;
	float: right;
	font-size: 20px;
	line-height : 20px;
	font-weight: bold;
}
.close:hover, .close:focus {
	color: #000;
	text-decoration: none;
	cursor: pointer;
}
.modal-header {
	padding: 2px 16px;
	color: Red;
}
.modal-body {
	line-height : 20px;
}
</style>

<div class="content-wrapper">
<section class="content-header">
  <h1> Membership/Duplicate ID Card</h1>
</section>
<section class="content">
<div class="row">
  <div class="col-md-12">
    <div class="box box-info">
      <div class="box-header with-border"> </div>
      <div class="box-body">
        <div class="form-group">
          <div class="col-sm-12">
            <?php 
			   $kystatus=array();
			  $regnumber = $this->session->userdata('regnumber');
			  $kystatus= $this->master_model->getRecords('member_registration',array('regnumber'=> $regnumber,'isactive'=>'1'),'kyc_status,kyc_edit');
		//	  print_r($kystatus[0]['kyc_status']);exit;
				//payment status
				$pay_status=$cnthistory=$hisaarr=array();
				
				$where1 = array('regnumber'=> $regnumber);
				$orderby1 = array("did"=>"Desc");
				$pay_status= $this->master_model->getRecords('duplicate_icard',$where1,'pay_status,did',$orderby1);
				
			//  echo $kyc_status ;exit;
			   if($kystatus[0]['kyc_status']=='1')
				{   ?>
            <center>
              You are only allowed maximum 2 downloads of your Membership ID Card free of cost. Hence  please Save the file, Print, Laminate and keep it in safe custody.
            </center>
            <br />
            <?php	}else
			{
				if(isset($pay_status[0]['pay_status']))
				{ 
				if($pay_status[0]['pay_status']==1 && $kystatus[0]['kyc_edit']==0 )
				{
				?>
            <center>
              You are only allowed maximum 2 downloads of your Membership ID Card free of cost. Hence  please Save the file, Print, Laminate and keep it in safe custody.
            </center>
            <br />
          </div>
          <?php }
				}
			}
				 if(isset($error) && $error != '')
				{?>
          <div style="color:#F00">
            <center>
              <?php echo $error;?>
            </center>
          </div>
          <?php }
			

				// Changes by pooja godse
				$regnumber = $this->session->userdata('regnumber');
				 $kyc_state1= $dowanload_count=$cnthistory=	$hisaarr=array();
			
				$where1 = array('regnumber'=> $regnumber);
				$orderby1 = array("kyc_id"=>"Desc");
				$kyc_state1= $this->master_model->getRecords('member_kyc',$where1,'kyc_status,user_edited_date',$orderby1);
				
			
				//to get the count of download 
				$hisaarr=array('member_number'=> $regnumber);
				$cnthistory = $this->master_model->getRecords('member_idcard_cnt',$hisaarr);
				$dowanload_count= $this->master_model->getRecords('member_idcard_cnt',array('member_number'=>$this->session->userdata('regnumber')),'card_cnt');	
		
				$kyc_start_date=$this->config->item('kyc_start_date');
				
				if(!empty($kyc_state1)  )
				{
						
						//	$this->db->where_not_in('regnumber', $kyc_state1[0]['regnumber']);
						//	$array=
				//	echo '<pre>';
					//	print_r($dowanload_count);
						if(!empty($dowanload_count))
						{
								 if($dowanload_count[0]['card_cnt']!='2' && $kystatus[0]['kyc_status'] == '1' )
								 {  
                                 	?>
          <a href="<?php echo base_url();?>idcard/downloadidcard_new/1"  target="_new">
          <b1>
          <center>
            <button type="Submit" name="dwn_card" class="btn btn-primary" target="_new" onclick="javascript:checkcount()">Download Membership ID Card </button>
          </center>
          <b1>
          <p></p>
          </a>
          <?php 	}
										elseif($dowanload_count[0]['card_cnt']=='2' && $kystatus[0]['kyc_status']==' 1' )
										{  ?>
          <?php  /*?><a href="<?php echo base_url();?>Duplicate/card" >
										<button type="Submit" name="dwn_card" class="btn btn-warning">Apply for Duplicate ID card</button>		
										</a><?php */?>
          <center>
            <button type="Submit" class="btn btn-primary" id="myBtn">Apply for Duplicate ID card</button>
          </center>
          <?php }
										elseif($kystatus[0]['kyc_status'] =='1')
										{
											?>
          <a href="<?php echo base_url();?>idcard/downloadidcard_new/1" target="_new">
          <center>
            <button type="Submit" name="dwn_card" class="btn btn-primary" target="_new" >Download Membership ID Card </button>
          </center>
          </a>
          <?php }
										elseif($kystatus[0]['kyc_status']=='0')
										{ 
											 $error="Your KYC process is pending therefore membership ID Card is unavailable.Once your KYC process complete you will be able to download your membership ID Card. KYC process is expected to complete within 45 days. ";
											 ?>
          <div style="color:#F00"> <?php echo  $error;?> </div>
          <?php	}
					
						}else
						{
									 if($kystatus[0]['kyc_status'] =='1')
									{
								?>
          <a href="<?php echo base_url();?>idcard/downloadidcard_new/1"  target="_new">
          <center>
            <button type="Submit" name="dwn_card" class="btn btn-primary" onclick="javascript:checkcount()">Download Membership ID Card </button>
          </center>
          </a>
          <?php }
									else
									{
										 $error="Your KYC process is pending therefore membership ID Card is unavailable.Once your KYC process complete you will be able to download your membership ID Card. KYC process is expected to complete within 45 days. ";
										 ?>
          <div style="color:#F00"> <?php echo  $error;?> </div>
          <?php }
						}
		
				}else
				{	$kyc_edit= $this->master_model->getRecords('member_registration',array('regnumber'=> $regnumber,'isactive'=>'1'),'regnumber,kyc_edit,DATE(createdon)');
						if(!empty($dowanload_count))
						{
						
							//	print_r($kyc_edit);exit;
								if($kystatus[0]['kyc_status']=='0' && $kyc_edit[0]['kyc_edit']==0  && $kyc_edit[0]['DATE(createdon)'] <=$kyc_start_date && $dowanload_count[0]['card_cnt']=='2')
								{  ?>
          <?php  /*?>   
									<a href="<?php echo base_url();?>Duplicate/card" >
								<button type="Submit" name="dwn_card" class="btn btn-warning">Apply for Duplicate ID card</button>		
									</a>	<?php */?>
          <center>
            <button type="Submit" class="btn btn-primary" id="myBtn">Apply for Duplicate ID card</button>
          </center>
          <?php }elseif($kystatus[0]['kyc_status']=='0' && $kyc_edit[0]['kyc_edit']==0  && $kyc_edit[0]['DATE(createdon)'] <=$kyc_start_date && $dowanload_count[0]['card_cnt']!='2')
{		?>
          <a href="<?php echo base_url();?>idcard/downloadidcard_new/1"  target="_new">
          <center>
            <button type="Submit" name="dwn_card" class="btn btn-primary" onclick="javascript:checkcount()">Download Membership ID Card </button>
          </center>
          </a>
          <?php	
		  
		  
		  }
		  elseif($kystatus[0]['kyc_status']=='0' && $kyc_edit[0]['kyc_edit']==1  && $kyc_edit[0]['DATE(createdon)'] <=$kyc_start_date)
	    {
							
        $error="Your KYC process is pending therefore membership ID Card is unavailable.Once your KYC process complete you will be able to download your membership ID Card. KYC process is expected to complete within 45 days. ";
							?>
          <div style="color:#F00"> <?php echo  $error;?>
          <?php	}
		
						}else 
						{
						if(!empty($pay_status))
						{
							if($kystatus[0]['kyc_status']=='0' && $kyc_edit[0]['kyc_edit']==0  && $kyc_edit[0]['DATE(createdon)'] <=$kyc_start_date && $pay_status[0]['pay_status']== 0 )
								{  ?>
          <?php    /*?>   
									<a href="<?php echo base_url();?>Duplicate/card" >
								<button type="Submit" name="dwn_card" class="btn btn-warning">Apply for Duplicate ID card</button>		
									</a>	<?php */?>
          <center>
            <button type="Submit" class="btn btn-primary" id="myBtn">Apply for Duplicate ID card</button>
          </center>
          <?php }else
							{	if($kystatus[0]['kyc_status']=='0' && $kyc_edit[0]['kyc_edit']==0  && $kyc_edit[0]['DATE(createdon)'] <=$kyc_start_date && $pay_status[0]['pay_status']== 1)
							  { ?>
          <a href="<?php echo base_url();?>idcard/downloadidcard_new/1"  target="_new">
          <center>
            <button type="Submit" name="dwn_card" class="btn btn-primary" onclick="javascript:checkcount()">Download Membership ID Card </button>
          </center>
          </a>
          <?php   }elseif($kystatus[0]['kyc_status']=='0' && $kyc_edit[0]['kyc_edit']==1  && $kyc_edit[0]['DATE(createdon)'] <=$kyc_start_date && $pay_status[0]['pay_status']== 1)
		  {
			  
           $error="Your KYC process is pending therefore membership ID Card is unavailable.Once your KYC process complete you will be able to download your membership ID Card. KYC process is expected to complete within 45 days. ";?>
						
          <div style="color:#F00"> <?php echo  $error;
		}elseif($kystatus[0]['kyc_status']=='0' && $kyc_edit[0]['kyc_edit']==1  && $kyc_edit[0]['DATE(createdon)'] <=$kyc_start_date)
		  {
			  ?>
          <a href="<?php echo base_url();?>idcard/downloadidcard_new/1"  target="_new">
          <center>
            <button type="Submit" name="dwn_card" class="btn btn-primary" onclick="javascript:checkcount()">Download Membership ID Card </button>
          </center>
          </a>
		  <?php }
		  
							 }
						}
					else
					{  if($kyc_edit[0]['DATE(createdon)']<=$kyc_start_date && $kyc_edit[0]['kyc_edit']==0)
					   { 
					?>
          <center>
            <button type="Submit" class="btn btn-primary" id="myBtn">Apply for Duplicate ID card</button>
          </center>
          <?php }
					else
					{
							if($kyc_edit[0]['kyc_edit']==1 && $kyc_edit[0]['DATE(createdon)'] <=$kyc_start_date && $kystatus[0]['kyc_status'] ==0)
							{
								$error="Your KYC process is pending therefore membership ID Card is unavailable.Once your KYC process complete you will be able to download your membership ID Card. KYC process is expected to complete within 45 days. ";
							?>
          <div style="color:#F00"> <?php echo  $error;?>
            <?php 	}
					}
				 }
		}  
		
			if($kyc_edit[0]['kyc_edit']==1 && $kyc_edit[0]['DATE(createdon)'] >=$kyc_start_date && $kystatus[0]['kyc_status'] ==0)
			{
				
					$error="Your KYC process is pending therefore membership ID Card is unavailable.Once your KYC process complete you will be able to download your membership ID Card. KYC process is expected to complete within 45 days. ";
					?>
            <div style="color:#F00"> <?php echo  $error;?> </div>
            <?php	}
				else
				{     if($kyc_edit[0]['kyc_edit']==0 && $kyc_edit[0]['DATE(createdon)'] >=$kyc_start_date && $kystatus[0]['kyc_status'] ==0)
						{
						
								$error="Your KYC process is pending therefore membership ID Card is unavailable.Once your KYC process complete you will be able to download your membership ID Card. KYC process is expected to complete within 45 days. ";
									?>
            <div style="color:#F00"> <?php echo  $error;?>
              <?php
						 }
				 }
			
			}
			
			?>
              <p id="demo"></p>
              <?php 
						/*change fee as per state */
		$this->db->join('state_master','member_registration.state = state_master.state_code', 'LEFT');
			$member_deatails=$this->master_model->getRecords('member_registration',array('regnumber'=>$this->session->userdata('regnumber'),'isactive'=>'1'),'state_master.state_name,member_registration.state');
			if($member_deatails[0]['state']=='JAM')
			{
				$fee=$this->config->item('Dup_Id_apply_fee');
			}else
			{
				 $fee=$this->config->item('Dup_Id_cs_total');
			}?>
              
              <!-- The Modal -->
              
              <div id="myModal" class="modal"> 
                
                <!-- Modal content -->
                <div class="modal-content">
                  <div class="modal-header"> <span class="close">&times;</span>
                    <center>
                      IMPORTANT (Please read carefully before applying)
                    </center>
                  </div>
                  <div class="modal-body"> 1) For members who have already registered before 1st June 2017, if required can apply for Duplicate ID Card after paying <?php echo $fee.'/-'?> (inclusive of  Service Tax)  and  Soft Copy of the ID Card will be made available for download through their Edit Profile. Hard copy of ID card(original or duplicate) will not be sent to any members. please read the detailed information given in this regard in MEMBERSHIP NOTICES available  under IMPORATNT ANNOUNCEMENT / NOTICE on the home page of our web site.<br />
                    2) Please check the images of your Photo, Signature and ID Proof and other details appearing here, if it is not clear/correct, pl get it corrected, then only apply for Duplicate ID Card. If the images/details are not proper in the ID card, fee will not be refunded.<br />
                    3) Please note that to appear for examination/s use of any one of the following ID card bearing Photo & Signature(original)in place of Membership ID Card is  also permitted.<br />
                    a) Photo Id Card issued by the employer<br />
                    b) PAN Card <br />
                    c) Driving License<br />
                    d) Election Voter's I Card<br />
                    e) Passport<br />
                    f) Aadhar Card<br />
                    <center>
                      <a href="<?php echo base_url();?>Duplicate/card" >
                      <button type="Submit" name="dwn_card" class="btn btn-primary" >I Agree</button>
                      </a>
                    </center>
                  </div>
                </div>
              </div>
              <script>
// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

	function checkcount()
	{
		$.ajax({
					url:site_url+'Idcard/getCount/',
					type:'POST',
					async: false,
					success: function(data) {
					 if(data)
					{
						if(data>=2)
						{
							 $("a").removeAttr("target");
						}
					}
				}
			});
	}
</script> 
              <br/>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </section>
</div>
