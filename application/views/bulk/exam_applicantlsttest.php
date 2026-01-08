<div class="content-wrapper">

	<!-- Content Header (Page header) -->
    <section class="content-header">
    	<h1> <?php echo $exam_name; ?> (Exam Code : <?php if(isset($exam_code)) { echo $exam_code; } ?> | Exam Period : <?php if(isset($exam_period)) { echo $exam_period; }?>)<?php  echo '  Exam application entry and payment '; ?></h1>
    </section>
	<!-- Main content -->
    <form name="draexampay" class="draexampay" method="post" action="<?php echo base_url();?>bulk/Bulk_exam_payment/make_payment<?php //echo base64_encode($examcode);?>">
    <section class="content">
	    <div class="row">
    		<div class="col-xs-12">
				<div class="box">
            		<div class="box-header">
              			<h3 class="box-title"><?php // echo ucwords($desc);?> Application List</h3>
              			<div class="pull-right">
						
							<a href="<?php  echo base_url();?>bulk/BulkApplyTest/add_member" class="btn btn-warning">Apply for Member</a>
                            <a href="<?php  echo base_url();?>bulk/BulkApplyNMTest/add_member" class="btn btn-warning">Apply for Non Member</a>
							
                         	<!--<a href="<?php  echo base_url();?>bulk/Bulk_exam_payment/make_payment" class="btn  btn-primary mk-payment">Make Payment</a>-->
                            <input type="submit" class="btn  btn-primary mk-payment"  value="Make Payment">
                            <!--<a class="btn btn-info" href="<?php //echo base_url();?>bulk/BulkApply/exam_applicantlst/"  style="float:right">Refresh</a>-->
                            <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
                            <input type="hidden" name="base_url_val" id="base_url_val" value="" /> 
                      </div>
            		</div>
            		<!-- /.box-header -->
           			<div class="box-body">
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
                        <table id="listitems" class="table table-bordered table-striped dataTables-example">
                            <thead>
                                <tr>
                                	<th><input type="checkbox" id="selectall"/></th>
                          			<th id="srNo">No.</th>
                                    <th id="memberno">Membership No.</th>
                                    <th id="emp_id">Employee Id</th>
                                    <th id="firstname">Candidate Name</th>
                                    <th id="memberType">Member Type</th>
                                    <!--<th id="dateofbirth">Exam Period</th>-->
                                    <th id="exam_center_code">Center Code</th>
                                    <th id="exam_fee">Fee</th>
                                    <th id="">NEFT/UTR No.</th>
                                    <th id="pay_status">Payment Status</th>
                                   <?php if($is_exam_activated == 1){ ?> <th id="action">Actions</th> <?php } ?>
                                </tr>
                            </thead>
                             <tbody class="no-bd-y" id="list">
								<?php
								
			              	    if(count($member_list)){
									 $i=0;
									 foreach($member_list as $row){ 
									  $i++;
								 	  //get member basic details 
									  $member_info = array();
									  //to find where the memeber is existing non member of new fresh member 
									  $member_info = $this->master_model->getRecords('member_registration',array('regnumber'=>$row['regnumber'],'isactive'=>'1'));
									  if(empty($member_info))
									  {
											$member_info = $this->master_model->getRecords('member_registration',array('regid'=>$row['regnumber'],'isactive'=>'0'));
									  }
									  if(count($member_info))
									  {
										   foreach($member_info as $val){
									 ?>
									     <tr>
                                    	<td align="center">
                                         <?php if( $row['pay_status'] == 0 || $row['pay_status'] == 2 ) { ?>
                                        <input type="checkbox" class="chkmakepay" name="chkmakepay[]" value="<?php echo $row['id'];?>"/><?php }?></td>
                                         <td><?php echo $i;?></td>
                                          <td><?php if($val['isactive'] == 1) { echo $val['regnumber']; } ?></td> 
                                          <td><?php echo $val['bank_emp_id']; ?></td> 
                                          <td><?php echo $val['firstname'].' '.$val['middlename'].' '.$val['lastname'];?></td>
                                          <td align="center"><?php echo $val['registrationtype']; ?></td>
                                          <!--<td><?php //echo $row['exam_period'];?></td>-->
                                          <td align="center"><?php echo $row['exam_center_code'];?></td>
                                          <td align="center"><?php echo $row['base_fee'];?></td>
                                          <td><?php 
										  				$this->db->select('UTR_no');
														$this->db->from('bulk_member_payment_transaction');
														$this->db->order_by('bulk_member_payment_transaction.id',"desc");
														$this->db->join('bulk_payment_transaction', 'bulk_member_payment_transaction.ptid = bulk_payment_transaction.id'); 
														$this->db->where('memexamid',$row['id']);
														
														$query=$this->db->get();
														$utr_no =$query->result_array();
													  if(!empty($utr_no)	)
													 {
														echo $utr_no[0]['UTR_no'];
													}else
													{  echo '-'; 
													}?></td>
                                          <td><?php if( $row['pay_status'] == 0 ) { echo 'Fail'; } else if( $row['pay_status'] == 2 ) { echo 'Pending'; } else if($row['pay_status'] == 3) { echo 'Released for payment-invoice generated'; } else if( $row['pay_status'] == 1 ) { echo 'Payment Done'; } ?></td> 
                                          <td>
                                        	<?php if($is_exam_activated == 1){ 
											if( $row['pay_status'] == 0 || $row['pay_status'] == 2 ) { ?>
                                        	<a href="<?php echo base_url().'bulk/BulkApply/delete/'.$row['id'];?>" onclick="return confirm('Are you sure to delete this record?');">Delete </a>
                                            <?php } }?>
                                        </td>
                                        </tr>
									<?php 	}
										  }
										}
									  }
									  else
									  { ?>
										  <tr><td colspan="10">No records found.</td></tr>
								      <?php } ?>         
                                </tbody>
                        </table>
								
                        <div style="width:30%; float:left;">
					   		<?php /*Removed pagination on 21-01-2017*/ 
							//// echo $info; ?>
                        </div>
                        <div id="links" class="" style="float:right;"><?php // echo $links; ?></div>
              		</div>
            		<!-- /.box-body -->
          		</div>
          		<!-- /.box -->
        	</div>
        	<!-- /.col -->
      	</div>
	</section>
    </form>
</div>

<script type="text/javascript">

$(function () {
	/*$("#listitems").DataTable();
	var base_url = '<?php //// echo base_url(); ?>';
	paginate(base_url+'iibfdra/DraExam/getApplicantList','','','');
	$("#base_url_val").val(base_url+'iibfdra/DraExam/getApplicantList');*/
	
	// add multiple select / deselect functionality
	$("#selectall").click(function () {
		  $('.chkmakepay').prop('checked', this.checked);
	});

	// if all checkbox are selected, check the selectall checkbox
	// and viceversa
	$(".chkmakepay").click(function(){
		if($(".chkmakepay").length == $(".chkmakepay:checked").length) {
			$("#selectall").prop("checked", true);
		} else {
			$("#selectall").removeAttr("checked");
		}

	});
	$( ".draexampay" ).submit(function() {
		if( $(".chkmakepay:checked").length == 0 ) {
			alert('Please select at least one candidate to pay');
			return false;	
		} else {
			return true;	
		}
	});
});
</script>
<!--//back button disable -->
<script>
history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
</script>
