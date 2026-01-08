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
																	<td><?php echo $totalpay; ?></td>
																</tr>
													<?php }
											      } if( count( $idstopay ) > 0 ) {
														$idstopaystr = implode('|',$idstopay); 
														$idstopaystrencd =  base64_encode($idstopaystr);
												  } ?>
                                            <tr>
                                                <td  colspan="3" class="tablecontent1" style="text-align:center;"><input type='radio' name='pay_mode' id='pay_mode_neft' value='neft_rtgs' <?php if( count( $idstopay ) < 10 ) { echo "disabled='disabled'"; }?> /> NEFT / RTGS &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='radio' name='pay_mode' id='pay_mode_online' value='online' /> Online Payment Gateway</td>
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
                                      
                                      <input type="submit" value="Pay" name="Submit" id="Submit" class="button">
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
			frmaction = base_url+'iibfdra/DraExam/make_payment';
			
		} else {
			frmaction = base_url+'iibfdra/DraExam/make_neft';
		}
		$("#payModFrm").attr('action',frmaction);
		//console.log($("#payModFrm").attr('action'));
		return true;
	});
});
</script>