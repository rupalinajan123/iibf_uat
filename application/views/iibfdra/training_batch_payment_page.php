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
                        <h3 class="box-title">Payment</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
                        <div class="form-group">
                            <div class="col-sm-12">
                                
                                <form name="payModFrm" id="payModFrm"  method="post" action="#">
                                	<table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Sr. No.</th>
                                                <th>Candidate Name</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        	<?php $totalcount = 0;
                                        	$app_category = array();
                                        	// echo "<pre>"; print_r($result); exit;
                                        	$unit_R=$base_total_R=0;
											$unit_S1=$base_total_S1=0;
											$unit_B1=$base_total_B1=0;

											$totalpay = 0;$idstopaystrencd = '';
											$idstopay = array(); $exam_period = '';
											if(count( $result ) > 0 ) {
													$totalcount = count( $result );
													$k = 1; $idarr = array();
													if( !empty( $regnostrencd ) ) {
														$regnostr = base64_decode($regnostrencd);
														$idarr = explode("|",$regnostr);	
													}
													if( count( $idarr ) > 0 ) {
														foreach( $result as $row ) { 
															if( $row['id'] == $idarr[$k-1] ) {


// Gaurav Code Check the member is fresh or from eligible master 
$GetRegnumber = "";

$checkMemberFrom = $this->master_model->getRecords('dra_members', array('regid' => $row['regid'], 'batch_id' => $row['batchId']),'regnumber,inst_code');
// print_r($checkMemberFrom); exit;
/* echo $this->db->last_query(); */
$GetRegnumber   = $checkMemberFrom[0]['regnumber'];
$institute_code = $checkMemberFrom[0]['inst_code'];
//echo $GetRegnumber; 
// Get Group Code
$group_code = "B1";
if($GetRegnumber > 0 && $GetRegnumber != "")
{
	$group_code_sql = $this->master_model->getRecords('dra_eligible_master', array('member_no' => $GetRegnumber),'app_category');
	//echo $this->db->last_query(); die;
	if(count($group_code_sql) > 0 && $GetRegnumber != '')
	{
		$group_code = $group_code_sql[0]['app_category'];
		if($group_code == "R" || $group_code == '') {
			$group_code = "B1";
			$unit_R=$unit_R+1;
		}
		else {
			$group_code=$group_code;
			$unit_S1=$unit_S1+1;
		}
	}
	else 
	{
		$group_code = "B1";	
		$unit_R=$unit_R+1;													
	}
}
else 
{
	$group_code = "B1";	
	$unit_R=$unit_R+1;													
}													
// commentted by below if else condition code upto line no 93 
/*if($row['exam_fee'] == 1770 || $row['exam_fee'] == 1770.00){
	$unit_R=$unit_R+1;

}elseif($row['exam_fee'] == 1416 || $row['exam_fee'] == 1416.00){
	$unit_S1=$unit_S1+1	;
}else{
	$unit_R=$unit_R+1;

}*/
/* Close Code to dyanamic fees - Gaurav */
																$idstopay[] = $row['id'];
																$totalpay = $totalpay+$row['exam_fee'];
																?>
																<tr>
																	<td><?php echo $k;?></td>
																	<td><?php echo $row['firstname'].' '.$row['lastname']; ?></td>
																	<td><?php echo $row['exam_fee'];?></td>
																</tr>
																<?php $exam_period = $row['exam_period'];?>
												<?php 		}
														$k++; 
														} ?>
                                                        		<tr>
																	<td colspan="2">Total</td>
																	<td><?php echo number_format((float)$totalpay, 2, '.', ''); ?></td>
																</tr>
													<?php }
											      } if( count( $idstopay ) > 0 ) {
														$idstopaystr = implode('|',$idstopay); 
														$idstopaystrencd =  base64_encode($idstopaystr);
												  } ?>
                                            <tr>
                                                <td  colspan="3" class="tablecontent1" style="text-align:center;"><input type='radio' name='pay_mode' id='pay_mode_neft' value='neft_rtgs' <?php if( count( $idstopay ) < 1 ) { echo "disabled='disabled'"; }?> /> NEFT / RTGS </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                
                                      <!--<input type="hidden" value="441021@447980@446046@" name="regNosToPay" id="regNosToPay">-->
                                      <input type="hidden" value="<?php echo $idstopaystrencd;?>" name="regNosToPay" id="regNosToPay">
                                      
                                      <!--<input type="hidden" value="Mw==" name="tot_fee" id="tot_fee">-->
                                      <input type="hidden" value="<?php echo base64_encode($totalpay);?>" name="tot_fee" id="tot_fee">
                                      <input type="hidden" name="exam_code" id="exam_code" value="<?php echo base64_encode($examcode);?>"> 
                                      <input type="hidden" name="exam_period" id="exam_period" value="<?php echo base64_encode($exam_period);?>">
                                      <!-- passing telecall const to page to identify dra or tele cands payment -->
                                       <a target="_blank"  href="<?php echo base_url().'iibfdra/TrainingBatches/performance_invocie/'.$unit_R.'/'.$unit_S1.'/'.$exam_period;?>"  name="p_invocie" id="" class="bustton">Proforma Invoice</a> 
                                      <input style="margin-left: 349px;" type="submit" value="Pay" name="Submit" id="Submit" class="button">
                                </form>
                                
                                
                            </div><!--(Max 30 Characters) -->
                        </div>
                    </div>
                </div> <!-- Basic Details box closed-->
        	</div>
    	</div>
	</section>
</div>
<script type="text/javascript">
$(document).ready(function() {
	var base_url = '<?php echo base_url(); ?>';
	$("#payModFrm").submit(function(e) {
		//e.preventDefault();
		var base_url = '<?php echo base_url(); ?>';
		var paymethod = $("input[name='pay_mode']:checked").val();
		
		if(paymethod != "neft_rtgs" && paymethod != "online")
		{
			alert("Please select payment mode.");
			return false;	
		}
		
		var frmaction = '';
		if( paymethod == 'online' ) {
			frmaction = base_url+'iibfdra/TrainingBatches/make_payment';
			
		} else {
			frmaction = base_url+'iibfdra/TrainingBatches/make_neft';
		}
		$("#payModFrm").attr('action',frmaction);
		//console.log($("#payModFrm").attr('action'));
		return true;
	});
});
</script>