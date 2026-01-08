<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
    <section class="content-header">
    	<h1></h1>
    </section>
    <section class="content">
	    <div class="row">
    	    <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">NEFT / RTGS Information</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
                       <div class="col-md-12">
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
                             <?php } ?> 
                            <form action="<?php echo base_url() ?>iibfdra/Version_2/TrainingBatches/make_neft" method="post" class="form-horizontal" name="neft_pay_form" id="neft_pay_form" autocomplete="off">
                                <div class="form-group">
                                    <?php 
                                    $instdata = $this->session->userdata('dra_institute');
                                    $inst_code = $instdata['institute_code']; ?>
                                    <label for="institute_name" class="col-sm-4 control-label">Institute Name</label>
                                    <div class="col-sm-8">
                                        <?php echo $instdata['institute_name'];?>
                                        <input type="hidden" class="form-control" id="institute_code" name="institute_code" value="<?php echo $inst_code;?>" >
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label for="institute_name" class="col-sm-4 control-label">To Payee(IIBF)</label>
                                    <div class="col-sm-8">
                                        Beneficiary Name: Indian Institute of Banking & Finance
                                        <br>
                                        Bank: State bank of India
                                        <br>
                                        Branch: Kurla (west)
                                        <br>
                                        Code: 1886
                                        <br>
                                        IFSC: SBIN0001886
                                        <br>
                                        Current Acct No: 32344902738
                                        <br>
                                        MICR No: 400002091
                                        <br>
                                        SWIFT CODE: SBININBB357
                                        <br>
                                     </div>
                                </div>
                                <div class="form-group">
                                    <label for="institute_name" class="col-sm-4 control-label">Amount</label>
                                    <div class="col-sm-8">
										<?php echo number_format((float)base64_decode($tot_fee), 2, '.', '');?>
                                    </div>
                                </div> 
								
                                <div class="form-group">
                                    <label for="institute_name" class="col-sm-4 control-label">NEFT / RTGS (UTR) Number *</label>
                                    <div class="col-sm-8">
                                        <?php $chkFees = base64_decode($tot_fee);
																			if($chkFees > 0)
																			{	?>
																				<input type="text" name="utr_no" id="utr_no" maxlength="25" data-parsley-type="alphanum" required />
																<?php	}
																			else
																			{	?>
																				<input type="text" name="utr_no" id="utr_no" maxlength="25" data-parsley-type="alphanum" required value="0000000000000" readonly style="background:#d8d8d8" />
																				<p class="small text-danger">Note : For zero(0) fees, UTR number is default '0000000000000'</p>
																	<?php	} ?>
										
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="institute_name" class="col-sm-4 control-label">Payment Date *</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="payment_date" id="payment_date" data-date-minDate="<?php echo date('m/d/Y'); ?>" required autocomplete="off"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="utr_slip" class="col-sm-4 control-label">Upload payment (UTR) slip (50 kb min & 100 kb max) *</label>
                                    <div class="col-sm-8">
                                        <input  type="file" class="form-control" name="utr_slip" id="utr_slip" required autocomplete="off">
                                        <input type="hidden" id="hiddenutrslip" name="hiddenutrslip">
                                        <div id="error_utrslip"></div>
                                        <div id="error_utrslip_size"></div>
                                        <span class="utrslip_text" style="display:none;"></span>
                                        <span class="error"><?php //echo form_error('utr_slip');?></span>
                                    </div>
                                </div>
                                
                                 <div class="form-group">
                                    <label for="utr_slip" class="col-sm-4 control-label"> Select GST No to be displayed on Invoice *</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="centerid" name="center_id" required >
                                            <option value="">Select</option>
                                             <option value="-">NA</option>
                                            
                                             <?php 
                                            if(!empty($inst_registration_info))
                                            {
                                                //echo  "swati"; die;
                                               foreach($inst_registration_info as $values)
                                                { 
                                                 if($values['gstin_no'] == "" || $values['gstin_no']== NULL)
                                                    {
                                                    ?>
                                                    <option value="-" disabled="disabled">Institute GSTIN-(No GSTIN Number)</option>
                                                   <?php }else{ ?>
                                                    <option value="Institute">Institute GSTIN - <?php echo $values['gstin_no']; ?></option>
                                               <?php }
                                            }
                                        } ?>


                                            <?php $validity_to=$validity_from='';
                                            if(!empty($agency_center))
                                            {
                                                foreach($agency_center as $val)
                                                {
                                            
                                                    if($val['city_name']!="" || $val['city_name']!=NULL)
                                                    {
                                                     $val['location_name'] = $val['city_name'];
                                                    } 
                                                    else
                                                    { 
                                                      $val['location_name'] = $val['location_name'];
                                                    }
                                                 
                                                 if($val['renew_pay_status'] != "")  {//not empty
                                                    if(($val['renew_type'] == 'free' && $val['center_type'] == 'R') || ($val['renew_pay_status'] == 1 && $val['center_type'] == 'R')){ ?>

                                                         <!-- <option  <?php //echo (set_value('center_id')==$val['center_id'])?" selected=' selected'":""?>   value="<?php //echo $val['center_id']; ?>"><?php //echo $val['location_name']; ?><?php //echo set_value('location_name'); ?> </option> -->
                                                  <?php     
                                                  //////////////////////////


                                               if($val['center_validity_from'] == '' && $val['center_validity_to'] == '' && $val['pay_status']==1){
                                                    $_SESSION['validity_to']=$val['center_validity_to'];?>
                                             <option value="<?php echo $val['gstin_no'].'_'.$val['center_id']; ?>" disabled="disabled">  <?php   echo $val['location_name'].' - '.$val['gstin_no'].' ( The accreditation period is not defined for this centre, please contact admin. )';?></option>
                                             <?php }elseif($val['center_status'] == 'A' && $val['pay_status'] == 0){
                                                           
                                                    
                                             ?>
                                              <option value="<?php echo $val['gstin_no'].'_'.$val['center_id']; ?>" disabled="disabled"><?php echo $val['location_name'].' - '.$val['gstin_no'].' (The selected centre is approved by the admin, kindly make the payment for this centre in order to add the batch. )'; ?></option>
                                                <?php }elseif($val['center_status'] == 'A' && $val['pay_status'] == 1 && $val['center_validity_to'] > date('Y-m-d') && $val['center_validity_from'] <= date('Y-m-d') && $val['gstin_no'] == '' || $val['gstin_no'] == NULL){ ?>

                                                <option value="<?php echo $val['gstin_no'].'_'.$val['center_id']; ?>" disabled="disabled"><?php echo $val['location_name']. '(No GSTIN Number)'; ?></option>

                                                <?php }elseif($val['center_status'] == 'A' && $val['pay_status'] == 1 && $val['center_validity_to'] > date('Y-m-d') && $val['center_validity_from'] <= date('Y-m-d'))
                                                {
                                                $validity_to=$val['center_validity_to'];
                                                $validity_from=(date('Y-m-d',strtotime("+6 day", strtotime($val['center_validity_from']))));
                                                
                                                ?>
                                                
                                                 <option  <?php echo (set_value('center_id')==$val['gstin_no'].'_'.$val['center_id'])?" selected=' selected'":""?>   value="<?php echo $val['gstin_no'].'_'.$val['center_id']; ?>"><?php echo $val['location_name']; ?> - <?php echo $val['gstin_no'];?>  </option>
                                                
                                                <?php }elseif( $val['center_validity_to']< date('Y-m-d')  && $val['pay_status'] == 1)
                                                {?>
                                                
                                                 <option value="<?php echo $val['gstin_no'].'_'.$val['center_id']; ?>" disabled="disabled"><?php echo $val['location_name'].' - '.$val['gstin_no'].' ( The accreditation period for the selected centre is expired, please contact admin. )'; ?></option>
                                                 <?php }elseif($val['center_status'] == 'R')
                                                 {?>
                                                 <option value="<?php echo $val['gstin_no'].'_'.$val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' - '.$val['gstin_no'].' ( The selected centre is rejected by the admin, please check the centre status in centre list or else contact admin. )'; ?></option>
                                                 <?php }elseif($val['center_status'] == 'IR')
                                                 {?>
                                                 <option value="<?php echo $val['gstin_no'].'_'.$val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' - '.$val['gstin_no'].' ( IN Review Process. )'; ?></option>
                                                 <?php }elseif($val['center_status'] == 'AR')
                                                 {?>
                                                 <option value="<?php echo $val['gstin_no'].'_'.$val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' - '.$val['gstin_no'].' ( Approve by Recommender. )'; ?></option>
                                                 <?php }elseif($val['center_status'] == 'A' && $val['pay_status'] == 2)
                                                 {?>
                                                 <option value="<?php echo $val['gstin_no'].'_'.$val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' - '.$val['gstin_no'].' ( Approve by Approver. )'; ?></option>
                                                 <?php }elseif($val['center_validity_from'] > date('Y-m-d'))
                                                 {?>
                                                 <option value="<?php echo $val['gstin_no'].'_'.$val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' - '.$val['gstin_no'].' ( Your Accreditation period is not started. )'; ?></option>
                                                 <?php }
                                               
                                     /////////////////////////////////////////////////////////////////

                                                      } elseif(($val['renew_pay_status'] == 0 || $val['renew_pay_status'] == 2) && $val['center_type'] == 'R' && $val['renew_type'] != 'free'){ ?>
                                                         <option value="<?php echo $val['gstin_no'].'_'.$val['center_id']; ?>" disabled="disabled">  <?php   echo $val['location_name'].' - '.$val['gstin_no'].' ( Your renewal process payment is pending. )';?><?php echo set_value('location_name'); ?></option>
                                                   <?php }elseif(($val['renew_pay_status'] == 0 || $val['renew_pay_status'] == 2) && $val['center_type'] == 'T'){ ?>
                                                        <option value="<?php echo $val['gstin_no'].'_'.$val['center_id']; ?>" disabled="disabled">  <?php   echo $val['location_name'].' - '.$val['gstin_no'].' ( Your renewal process payment is pending. )';?></option>
                                                   <?php }elseif($val['renew_pay_status'] == 1 && $val['center_type'] == 'T'){ 

                                                    if($val['center_validity_from'] == '' && $val['center_validity_to'] == '' && $val['pay_status']==1){
                                                    $_SESSION['validity_to']=$val['center_validity_to'];?>
                                             <option value="<?php echo $val['gstin_no'].'_'.$val['center_id']; ?>" disabled="disabled">  <?php   echo $val['location_name'].' - '.$val['gstin_no'].' ( The accreditation period is not defined for this centre, please contact admin. )';?></option>
                                             <?php }elseif($val['center_status'] == 'A' && $val['pay_status'] == 0){
                                                           
                                                    
                                             ?>
                                              <option value="<?php echo $val['gstin_no'].'_'.$val['center_id']; ?>" disabled="disabled"><?php echo $val['location_name'].' - '.$val['gstin_no']. ' (The selected centre is approved by the admin, kindly make the payment for this centre in order to add the batch. )'; ?></option>
                                                <?php }elseif($val['center_status'] == 'A' && $val['pay_status'] == 1 && $val['center_validity_to'] > date('Y-m-d') && $val['center_validity_from'] <= date('Y-m-d') && $val['gstin_no'] == '' || $val['gstin_no'] == NULL){ ?>

                                                <option value="<?php echo $val['gstin_no'].'_'.$val['center_id']; ?>" disabled="disabled"><?php echo $val['location_name'].' - '.$val['gstin_no']. '(No GSTIN Number)'; ?></option>

                                                <?php } elseif($val['center_status'] == 'A' && $val['pay_status'] == 1 && $val['center_validity_to'] > date('Y-m-d') && $val['center_validity_from'] <= date('Y-m-d'))
                                                {
                                                $validity_to=$val['center_validity_to'];
                                                $validity_from=(date('Y-m-d',strtotime("+6 day", strtotime($val['center_validity_from']))));
                                                
                                                ?>
                                                
                                                 <option  <?php echo (set_value('center_id')==$val['gstin_no'].'_'.$val['center_id'])?" selected=' selected'":""?>   value="<?php echo $val['gstin_no'].'_'.$val['center_id']; ?>"><?php echo $val['location_name']; ?> - <?php echo $val['gstin_no']; ?></option>
                                                
                                                <?php }elseif( $val['center_validity_to']< date('Y-m-d')  && $val['pay_status'] == 1)
                                                {?>
                                                
                                                 <option value="<?php echo $val['gstin_no'].'_'.$val['center_id']; ?>" disabled="disabled"><?php echo $val['location_name'].' - '.$val['gstin_no'].' ( The accreditation period for the selected centre is expired, please contact admin. )'; ?></option>
                                                 <?php }elseif($val['center_status'] == 'R')
                                                 {?>
                                                 <option value="<?php echo $val['gstin_no'].'_'.$val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' - '.$val['gstin_no'].' ( The selected centre is rejected by the admin, please check the centre status in centre list or else contact admin. )'; ?></option>
                                                 <?php }elseif($val['center_status'] == 'IR')
                                                 {?>
                                                 <option value="<?php echo $val['gstin_no'].'_'.$val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' - '.$val['gstin_no'].' ( IN Review Process. )'; ?></option>
                                                 <?php }elseif($val['center_status'] == 'AR')
                                                 {?>
                                                 <option value="<?php echo $val['gstin_no'].'_'.$val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' - '.$val['gstin_no'].' ( Approve by Recommender. )'; ?></option>
                                                 <?php }elseif($val['center_status'] == 'A' && $val['pay_status'] == 2)
                                                 {?>
                                                 <option value="<?php echo $val['gstin_no'].'_'.$val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' - '.$val['gstin_no'].' ( Approve by Approver. )'; ?></option>
                                                 <?php }elseif($val['center_validity_from'] > date('Y-m-d'))
                                                 {?>
                                                 <option value="<?php echo $val['gstin_no'].'_'.$val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' - '.$val['gstin_no'].' ( Your Accreditation period is not started. )'; ?></option>
                                                 <?php }
                                                 
                                                 ?>



                                                <?php } 
                                                    } //not empty renew payemnt status
                                               else{//empty renew payemnt status



                                                if($val['center_validity_from'] == '' && $val['center_validity_to'] == '' && $val['pay_status']==1){
                                                    $_SESSION['validity_to']=$val['center_validity_to'];?>
                                             <option value="<?php echo $val['gstin_no'].'_'.$val['center_id']; ?>" disabled="disabled">  <?php   echo $val['location_name'].' - '.$val['gstin_no'].'( The accreditation period is not defined for this centre, please contact admin. )';?></option>
                                             <?php }elseif($val['center_status'] == 'A' && $val['pay_status'] == 0){
                                                           
                                                    
                                             ?>
                                              <option value="<?php echo $val['gstin_no'].'_'.$val['center_id']; ?>" disabled="disabled"><?php echo $val['location_name'].' - '.$val['gstin_no']. '(The selected centre is approved by the admin, kindly make the payment for this centre in order to add the batch. )'; ?></option>
                                                <?php }elseif($val['center_status'] == 'A' && $val['pay_status'] == 1 && $val['center_validity_to'] > date('Y-m-d') && $val['center_validity_from'] <= date('Y-m-d') && $val['gstin_no'] == '' || $val['gstin_no'] == NULL){ ?>

                                                <option value="<?php echo $val['gstin_no'].'_'.$val['center_id']; ?>" disabled="disabled"><?php echo $val['location_name']. '(No GSTIN Number)'; ?></option>

                                                <?php }elseif($val['center_status'] == 'A' && $val['pay_status'] == 1 && $val['center_validity_to'] > date('Y-m-d') && $val['center_validity_from'] <= date('Y-m-d'))
                                                {
                                                $validity_to=$val['center_validity_to'];
                                                $validity_from=(date('Y-m-d',strtotime("+6 day", strtotime($val['center_validity_from']))));
                                                
                                                ?>
                                                
                                                 <option  <?php echo (set_value('center_id')==$val['gstin_no'].'_'.$val['center_id'])?" selected=' selected'":""?>   value="<?php echo $val['gstin_no'].'_'.$val['center_id']; ?>"><?php echo $val['location_name']; ?> - <?php echo $val['gstin_no']; ?></option>
                                                
                                                <?php }elseif( $val['center_validity_to']< date('Y-m-d')  && $val['pay_status'] == 1)
                                                {?>
                                                
                                                 <option value="<?php echo $val['gstin_no'].'_'.$val['center_id']; ?>" disabled="disabled"><?php echo $val['location_name'].' - '.$val['gstin_no'].' ( The accreditation period for the selected centre is expired, please contact admin. )'; ?></option>
                                                 <?php }elseif($val['center_status'] == 'R')
                                                 {?>
                                                 <option value="<?php echo $val['gstin_no'].'_'.$val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' - '.$val['gstin_no'].' ( The selected centre is rejected by the admin, please check the centre status in centre list or else contact admin. )'; ?></option>
                                                 <?php }elseif($val['center_status'] == 'IR')
                                                 {?>
                                                 <option value="<?php echo $val['gstin_no'].'_'.$val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' - '.$val['gstin_no'].' ( IN Review Process. )'; ?></option>
                                                 <?php }elseif($val['center_status'] == 'AR')
                                                 {?>
                                                 <option value="<?php echo $val['gstin_no'].'_'.$val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' - '.$val['gstin_no'].' ( Approve by Recommender. )'; ?></option>
                                                 <?php }elseif($val['center_status'] == 'A' && $val['pay_status'] == 2)
                                                 {?>
                                                 <option value="<?php echo $val['gstin_no'].'_'.$val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' - '.$val['gstin_no'].' ( Approve by Approver. )'; ?></option>
                                                 <?php }elseif($val['center_validity_from'] > date('Y-m-d'))
                                                 {?>
                                                 <option value="<?php echo $val['gstin_no'].'_'.$val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' - '.$val['gstin_no'].' ( Your Accreditation period is not started. )'; ?></option>
                                                 <?php }
                                                 
                                                 ?>

                                            <?php } }
                                            }
                                            ?>
                                            
                                            
                                           
                                        </select>
                                    
                                    </div>
                                </div>
                                

                               <div class="form-group">
                                    <label for="institute_name" class="col-sm-4 control-label">Exam Code & Exam Period</label>
                                    <div class="col-sm-8">
                                        <?php echo base64_decode($exam_code).' & '.base64_decode($exam_period);?>
                                    </div>
                                </div>
                                <input type="hidden" name="processPayment" value="processPayment" />
                                <input type="hidden" name="regNosToPay" value="<?php echo $regNosToPay; ?>" />
                                <input type="hidden" name="tot_fee" value="<?php echo $tot_fee; ?>" />
                                <input type='hidden' name='exam_code' id='exam_code' value="<?php echo $exam_code;?>" /> <!-- passing telecall const to page to identify dra or tele cands payment -->
                                <input type='hidden' name='exam_period' id='exam_period' value="<?php echo $exam_period;?>" />
                                <div class="col-sm-4 col-xs-offset-3">
                                <!--   <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Submit" onclick="this.disabled=true; this.value='Please Wait...';">  -->
                                <input type="button" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Submit"> <input type="submit" class="btn btn-info" name="btnSubmit1" id="btnSubmit1" style="display:none">                     
                                <?php 
                                if(!empty($active_exams))
                                            {
                                               
                                                ?>
                                <input type="hidden" id='exam_from_date' name="exam_from_date" value="<?php echo $active_exams[0]['exam_from_date']; ?>" />
                                <?php }
                                ?>                                                 
                                </div>
                            </form>
                       </div><!--(Max 30 Characters) -->
                    </div>
                </div> <!-- Basic Details box closed-->
        	</div>
    	</div>
	</section>
</div>
<script src="<?php echo base_url()?>js/validation_dra.js"></script>
<script>
		/* $('#utr').on('click', function () 
			{
			alert('swa');
			var lvl = $('#utr').val()
			if(lvl.length>0)
			{
				var sdata = {'utr_no':'lvl'};
				$.ajax({
					
					type: 'POST',
					data: sdata,
					url: site_url+'iibfdra/Version_2/TrainingBatches/getcount_utrno/',
					
					
					success: function (response) {
						alert('unique');
						
					},
					error: function (response) {
						alert("This UTR No is already present.Please enetr unique utr no.");
						$('#utr_no').val('');
						
					}
				});
			}
			else
			{
				alert("Please enter Value")
			}
		}); */

		$( document ).ready(function() 
		{
			$('#btnSubmit').click(function()
			{
				if($('#utr_no').val() != "" && $('#payment_date').val() != "" && $('#utr_slip').val() != "" && $('#centerid').val() != "")
				{
					var utr_no = $('#utr_no').val();
					var sdata = { 'utr_no':utr_no };
					$.ajax(
					{
						type: 'POST',
						data: sdata,
						url: site_url+'iibfdra/Version_2/TrainingBatches/getcount_utrno/',
						success: function (response) 
						{
							if(response == 'success')
							{
            $(this).prop('disabled', true);
            $(this).val('Please Wait...');
            $("#btnSubmit1").click(); 
							}
							else
							{
								alert("This UTR No is already present. Please enter unique utr no.");
							}
						},
						error: function (response) 
						{
							alert("This UTR No is already present.Please enter unique utr no.");														
						}
					});
				}
				else
				{
                alert("Please enter data for mandatory fields. ");
            }
        });
			
        var s_date = $('#exam_from_date').val();
        // alert(s_date);
        $('#payment_date').datepicker({format: 'yyyy-mm-dd',startDate: new Date(s_date),endDate: '+0d',autoclose: true}); 
		//$('#payment_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}); 
		$('#neft_pay_form').parsley('validate');
	});
		
  /*history.pushState(null, null, document.title);
  window.addEventListener('popstate', function () {
      history.pushState(null, null, document.title);
  });*/
</script>