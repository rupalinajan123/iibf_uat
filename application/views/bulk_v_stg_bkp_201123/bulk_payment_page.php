<!-- Content Wrapper. Contains page content -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<style>
th {
	text-align: center;
	background-color:#7FD1EA;
}
table,  th,  td {
	border: solid 1px #000 !important;
}
</style>
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
            <h3 class="box-title">Payment</h3>
          </div>
          <!-- /.box-header --> 
          <!-- form start -->
          <div class="box-body">
            <div class="form-group">
              <div class="col-sm-12">
                <div class="table-responsive">
                  <form action="<?php echo base_url() ?>bulk/Bulk_exam_payment/make_neft/" method="post" class="form-horizontal" name="neft_pay_form" id="neft_pay_form">
                    <?php 
						//display exam name 
						if(isset($exam_name))
						{?>
                    <center>
                      <b>
                      <?php 
                        echo 'EXAM NAME : '.$exam_name[0]['description'];
                        ?>
                      </b>
                    </center>
                    <?php	
                        }else
                        {
                      	  echo '';
                        }?>
                    <table class="table" style="text-align:center">
                      <thead>
                        <tr>
                          <th>Sr. No.</th>
                          <th>Title</th>
                          <th>Rate</th>
                          <th>Unit</th>
                          <th>Total</th>
                        </tr>
                      </thead>
                      <?php 
							if(!empty($exam_data)){ 
								$i=0;
								foreach ($exam_data as $row){
									$i++;
							
					?>
                      <tbody>
                        <tr>
                          <td width="10%"><?php echo $i;?></td>
                          <td width="55%">
						  	<?php
								
								if(isset($row['member_type'])){
									if($row['app_category']=='B1_1' && $row['member_type']=='NM' && $row['bulk_discount_flg']=='N' && $row['elearning_flag']=='N'){
										echo 'Fresher Non member(NM)' ;
									}elseif($row['app_category']=='B1_1' && $row['member_type']=='NM' && $row['bulk_discount_flg']=='Y' && $row['elearning_flag']=='N'){
										echo 'Fresher Non member(NM) with discount' ;
									}elseif($row['app_category']=='B1_1' && $row['member_type']=='NM' && $row['bulk_discount_flg']=='N' && $row['elearning_flag']=='Y'){
										echo 'Fresher Non member(NM) with Elearning' ;
									}elseif($row['app_category']=='B1_1' && $row['member_type']=='NM' && $row['bulk_discount_flg']=='Y' && $row['elearning_flag']=='Y'){
										echo 'Fresher Non member(NM) with Elearning and discount' ;
									}elseif($row['app_category']=='B1_1' && $row['member_type']=='O' && $row['bulk_discount_flg']=='N' && $row['elearning_flag']=='N'){
										echo 'Fresher Ordinary(O) member' ;
									}elseif($row['app_category']=='B1_1' && $row['member_type']=='O' && $row['bulk_discount_flg']=='Y' && $row['elearning_flag']=='N'){
										echo 'Fresher Ordinary(O) member with discount' ;
									}elseif($row['app_category']=='B1_1' && $row['member_type']=='O' && $row['bulk_discount_flg']=='N' && $row['elearning_flag']=='Y'){
										echo 'Fresher Ordinary(O) member with Elearning' ;
									}elseif($row['app_category']=='B1_1' && $row['member_type']=='O' && $row['bulk_discount_flg']=='Y' && $row['elearning_flag']=='Y'){
										echo 'Fresher Ordinary(O) member with Elearning and discount' ;
									}elseif($row['app_category']=='B1_1' && $row['member_type']=='DB' && $row['bulk_discount_flg']=='N' && $row['elearning_flag']=='N'){
										echo 'DB member' ;
									}elseif($row['app_category']=='B1_1' && $row['member_type']=='DB' && $row['bulk_discount_flg']=='Y' && $row['elearning_flag']=='N'){
										echo 'DB member with discount' ;
									}elseif($row['app_category']=='B1_1' && $row['member_type']=='DB' && $row['bulk_discount_flg']=='N' && $row['elearning_flag']=='Y'){
										echo 'DB member with Elearning' ;
									}elseif($row['app_category']=='B1_1' && $row['member_type']=='DB' && $row['bulk_discount_flg']=='Y' && $row['elearning_flag']=='Y'){
										echo 'DB member with Elearning and discount' ;
									}elseif($row['app_category']=='B1_2' && $row['member_type']=='NM'){
										echo' Repeater Non member(NM) - second attempt' ;
									}elseif($row['app_category']=='B1_2' && $row['member_type']=='O'){
										echo' Repeater Ordinary(O) member - second attempt' ;
									}elseif($row['app_category']=='B2_1' && $row['member_type']=='NM'){
										echo 'Repeater Non member(NM) - Third attempt' ;
									}elseif($row['app_category']=='B2_1' && $row['member_type']=='O'){
										echo 'Repeater Ordinary(O)  member - Third attempt' ;
									}elseif($row['app_category']=='B2_2' && $row['member_type']=='NM'){
										echo 'Repeater Non member(NM) - Fourth attempt' ;
									}elseif($row['app_category']=='B2_2' && $row['member_type']=='O'){
										echo 'Repeater Ordinary(O) member - Fourth attempt' ;
									}elseif($row['app_category']=='S1' && $row['member_type']=='NM' && $row['bulk_discount_flg']=='N' && $row['elearning_flag']=='N'){
										echo 'Repeater Non member(NM) -Subsequent attempts' ;
									}elseif($row['app_category']=='S1' && $row['member_type']=='NM' && $row['bulk_discount_flg']=='Y' && $row['elearning_flag']=='N'){
										echo 'Repeater Non member(NM) -Subsequent attempts with discount' ;
									}elseif($row['app_category']=='S1' && $row['member_type']=='NM' && $row['bulk_discount_flg']=='N' && $row['elearning_flag']=='Y'){
										echo 'Repeater Non member(NM) -Subsequent attempts with Elearning' ;
									}elseif($row['app_category']=='S1' && $row['member_type']=='NM' && $row['bulk_discount_flg']=='Y' && $row['elearning_flag']=='Y'){
										echo 'Repeater Non member(NM) -Subsequent attempts with Elearning and discount' ;
									}elseif($row['app_category']=='S1' && $row['member_type']=='O' && $row['bulk_discount_flg']=='N' && $row['elearning_flag']=='N'){
										echo 'Fresher Ordinary(O) member' ;
									}elseif($row['app_category']=='S1' && $row['member_type']=='O' && $row['bulk_discount_flg']=='Y' && $row['elearning_flag']=='N'){
										echo 'Fresher Ordinary(O) member with discount' ;
									}elseif($row['app_category']=='S1' && $row['member_type']=='O' && $row['bulk_discount_flg']=='N' && $row['elearning_flag']=='Y'){
										echo 'Fresher Ordinary(O) member with Elearning' ;
									}elseif($row['app_category']=='S1' && $row['member_type']=='O' && $row['bulk_discount_flg']=='Y' && $row['elearning_flag']=='Y'){
										echo 'Fresher Ordinary(O) member with Elearning and discount' ;
									}
								}
							?>
                          </td>
                          <td width="10%"><?php
																		echo $row['base_fee'];
																		?></td>
                          <td width="10%"><?php 
																		echo $row['total_cnt'];?></td>
                          <td width="15%"><?php 
																	$final_amt=0;
																	$final_amt =$row['base_fee'] * $row['total_cnt'];
																	echo $final_amt ;?></td>
                        </tr>
                        <?php }}?>
                        <tr>
                          <td width="10%" style="font-weight:bold;"></td>
                          <td width="55%" style="text-align:right; font-weight:bold;"><?php
																				echo 'Total';	?></td>
                          <td width="10%"></td>
                          <td width="10%"><?php 	echo  $no_unit_base;?></td>
                          <td width="15%"><?php 	echo $base_amt_total;?></td>
                        </tr>
                        
                        <tr>
                          <td width="10%"></td>
                          <td width="55%" style="text-align:right; font-weight:bold;"><?php	echo ' CGST';?></td>
                          <td width="10%"><?php
																		if($tax_type=='Intra')
																		{
																		echo '9%';
																		
																		}else
																		{
																		echo '-';
																		}
																		?></td>
                          <td width="10%"></td>
                          <td width="15%"><?php 
																		if($tax_type=='Intra')
																		{
																		echo $gst_amt;
																		
																		}else
																		{
																		echo '';
																		}?></td>
                        </tr>
                        <tr>
                          <td width="10%"></td>
                          <td width="55%" style="text-align:right; font-weight:bold;"><?php  echo 'SGST';   ?></td>
                          <td width="10%"><?php
																		if($tax_type=='Intra')
																		{
																		echo '9%';
																		
																		}else
																		{
																		echo '-';
																		}
																		
																		?></td>
                          <td width="10%"></td>
                          <td width="15%"><?php 
																		if($tax_type=='Intra')
																		{
																		echo $gst_amt;
																		
																		}else
																		{
																		echo '';
																		}?></td>
                        </tr>
                        <tr>
                          <td width="10%"></td>
                          <td width="55%" style="text-align:right; font-weight:bold;"><?php
																		echo ' IGST';
																		?></td>
                          <td width="10%"><?php
																		if($tax_type=='Inter')
																		{
																		echo '18%';
																		
																		}else
																		{
																		echo '-';
																		}
																		?></td>
                          <td width="10%"></td>
                          <td width="15%"><?php 
																	if($tax_type=='Inter')
																	{
																	echo $gst_amt;
																	
																	}else
																	{
																	echo '';
																	}?></td>
                        </tr>
                        <tr>
                          <td width="10%"></td>
                          <td width="55%" style="text-align:right; font-weight:bold;"><?php
																				echo ' TDS Deduction Amount (if applicable)';
																				
																				?></td>
                          <td width="10%"></td>
                          <td width="10%"></td>
                          <td width="15%"><input type="text" id="txtPassportNumber" placeholder="     Enter TDS "  class="form-control" onkeyup="tds()"  /></td>
                        </tr>
                        <tr>
                          <td width="10%"><?php  echo '';?></td>
                          <td width="55%" style="text-align:right; font-weight:bold;"><?php
																			echo 'Subtotal';
																			
																			?></td>
                          <td width="10%"></td>
                          <td width="10%"></td>
                          <td width="15%"><input type='hidden' name='amount_after_gst' id='amount_after_gst' value="<?php echo $amount_after_gst;?>" />
                            <input type="text" name='final_amt' id="final_amt" readonly="readonly" class="form-control" value="<?php echo $amount_after_gst;?>" style="text-align:center; font-weight:bold;">
                            <input type='hidden' name='final_subtotal_after_tds' id='final_subtotal_after_tds'  />
                            <input type='hidden' name='tds_amt' id='tds_amt'  />
                            <input type='hidden' name='gst_rate_amt' id='gst_rate_amt'  value="<?php echo $gst_amt;?>"  />
                            <input type='hidden' name='tax_type' id='tax_type'  value="<?php echo $tax_type;?>"  />
                            <input type='hidden' name='base_amt_tot' id='base_amt_tot'  value="<?php echo $base_amt_total;?>"  />
                            <input type='hidden' name='base_amt_after_dsct' id='base_amt_after_dsct'  value="<?php echo $base_amt_after_dsct;?>"  /></td>
                        </tr>
                        <tr>
                      </tbody>
                    </table>
                    <center>
                      <input type="submit" value="NEXT" name="Submit" id="Submit" class="w3-btn w3-black btn btn-warning" >
                    </center>
                  </form>
                </div>
              </div>
              <!--(Max 30 Characters) --> 
            </div>
          </div>
        </div>
        <!-- Basic Details box closed--> 
      </div>
    </div>
  </section>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script> 
<script type="text/javascript">
$(document).bind("contextmenu",function(e) {
 e.preventDefault();
});
    $(document).keydown(function(e){
        if(e.which === 123){
     
           return false;
     
        }
     
    });
    function tds() {
       var amount_after_gst = document.getElementById("amount_after_gst").value;
        var txtPassportNumber = document.getElementById("txtPassportNumber").value;
		var regexp1=new RegExp("[^0-9\.]");
	
		if(regexp1.test(document.getElementById("txtPassportNumber").value))
		{
			alert("Only numbers are allowed");
			return false;
		}
		
		//amount_after_gst=Math.round(amount_after_gst);
		//txtPassportNumber=Math.round(txtPassportNumber);
		
		var final_amount = amount_after_gst - txtPassportNumber;
		
				
		if(Math.round(txtPassportNumber)>=Math.round(amount_after_gst))
		{ 
		    alert("Please enter the TDS amount less than Subtotal.");
			document.getElementById('tds_amt').value ="";
			document.getElementById('final_amt').value = amount_after_gst;
			document.getElementById('final_subtotal_after_tds').value =amount_after_gst;
			 return false;
		}else
		{
		
		
			
  		if(final_amount<=0 )
		{
		alert('**2');
			alert("Please enter the TDS amount less than Subtotal.");
			
			document.getElementById('tds_amt').value ="";
			document.getElementById('final_amt').value = amount_after_gst;
			document.getElementById('final_subtotal_after_tds').value =amount_after_gst;
			 return false;
		}
		
		if(Math.round(txtPassportNumber)>=Math.round(amount_after_gst))
		{ 
		alert('**3');
			alert("Please enter the TDS amount less than Subtotal.");
			document.getElementById('tds_amt').value ="";
			document.getElementById('final_amt').value = amount_after_gst;
			document.getElementById('final_subtotal_after_tds').value =amount_after_gst;
			 return false;
		
		}else
		{
			document.getElementById('final_amt').value = final_amount;
			document.getElementById('final_subtotal_after_tds').value = final_amount;
			document.getElementById('tds_amt').value =txtPassportNumber;
		}	 
		}
       
	
    }
    $(document).ready(function() {
        var base_url = '<?php echo base_url(); ?>';
        $("#payModFrm").submit(function(e) {
            //e.preventDefault();
            var base_url = '<?php echo base_url(); ?>';
            var paymethod = $("input[name='pay_mode']:checked").val();
            if (paymethod != "neft_rtgs" && paymethod != "online") {
                alert("Please select payment mode.");
                return false;
            }
            var frmaction = '';
            if (paymethod == 'online') {
                frmaction = base_url + 'iibfdra/DraExam/make_payment';
            } else {
                frmaction = base_url + 'iibfdra/DraExam/make_neft';
            }
            $("#payModFrm").attr('action', frmaction);
            //console.log($("#payModFrm").attr('action'));
            return true;
        });
    });
    function check() {
        var ele = document.getElementsByName('pay_mode');
        var flag = 0;
        for (var i = 0; i < ele.length; i++) {
            if (ele[i].checked)
                flag = 1;
        }
        if (flag == 1)
            document.getElementById('Submit').disabled = false;
    }
    function EnableDisableTextBox(chkPassport) {
        var txtPassportNumber = document.getElementById("txtPassportNumber");
        txtPassportNumber.disabled = chkPassport.checked ? false : true;
        if (!txtPassportNumber.disabled) {
            txtPassportNumber.focus();
        }
    }
    function onlyNos(e, t) {
        try {
            if (window.event) {
                var charCode = window.event.keyCode;
            } else if (e) {
                var charCode = e.which;
            } else {
                return true;
            }
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        } catch (err) {
            alert(err.Description);
        }
    }
</script>