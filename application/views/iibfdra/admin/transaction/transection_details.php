<?php $this->load->view('iibfdra/admin/includes/header');?>
<?php $this->load->view('iibfdra/admin/includes/sidebar');?>

 
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
    <section class="content-header">
    	<h1></h1>
    </section>
    <section class="content" style="min-height: 598px;">
	    <div class="row">
    	    <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Payment</h3>
                        <div class="pull-right">
                        <a href="<?php echo base_url().'iibfdra/admin/transaction/neft_transactions'?>" class="btn btn-warning"> Back </a>
                    </div>
                    </div>
                    
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
                        <div class="form-group">
                            <div class="col-sm-12">
                                
                             
                                	<table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Sr. No.</th>
                                                <th>Candidate Name</th>
                                                <th>App Category</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        	<?php $totalcount = 0;
											
											if(count( $result ) > 0 ) {
                                                if(isset( $result )){
													$totalcount = count( $result );
													$k = 1; 
														foreach( $result as $row ) { 
																?>
																<tr>
																	<td><?php echo $k;?></td>
																	<td><?php echo $row['firstname'].' '.$row['lastname']; ?></td>
                                                                    <td><?php if($row['exam_fee'] >= '1770.00') echo 'R (FRESH)'; else echo "S1 (REPETER)";?></td>
																	<td><?php echo $row['exam_fee'];?></td>
																</tr>
																
												<?php 		
														$k++; 
													} ?>
                                                        		<tr>
																	<td colspan="3">Total</td>
																	<td><?php echo $row['amount']; ?></td>
																</tr>
													<?php 
											      } }  ?>
                                            <!-- <tr>
                                                <td  colspan="4" class="tablecontent1" style="text-align:center;">BACK</td>
                                            </tr> -->
                                        </tbody>
                                    </table>
                                
                            </div><!--(Max 30 Characters) -->
                        </div>
                    </div>
                </div> <!-- Basic Details box closed-->
        	</div>
    	</div>
	</section>
</div>
