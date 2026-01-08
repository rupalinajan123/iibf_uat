
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
    <section class="content-header">
    	
    	<h1><?php // echo ucwords($desc);?> Application Entry And Payment</h1>
    </section>
	<!-- Main content -->
    <form name="draexampay" class="draexampay" method="post" action="<?php echo base_url();?>bulk/Bulk_exam_payment/make_payment<?php //echo base64_encode($examcode);?>">
    <section class="content">
	    <div class="row">
    		<div class="col-xs-12">
				<div class="box">
            		<div class="box-header">
              			<h3 class="box-title"><?php // echo ucwords($desc);?> Application</h3>
              			<div class="pull-right">
                           <!-- <a href="<?php  echo base_url();?>bulk/BulkApply/add_member" class="btn btn-warning">Add New Application</a>-->
                          <!-- <a href="<?php  //echo base_url();?>bulk/Bulk_exam_payment/make_payment" class="btn  btn-primary mk-payment">Make Payment</a>-->
                          <input type="submit" class="btn  btn-primary mk-payment"  value="Make Payment">
                                   <a  class="btn btn-info" href="<?php echo base_url();?>bulk/BulkApply/exam_applicantlst/"  style="float:right">Refresh</a>
                           
                            <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
                            <input type="hidden" name="base_url_val" id="base_url_val" value="" /> 
                      </div>
            		</div>
            		<!-- /.box-header -->
           			<div class="box-body">
						<?php if($this->session->flashdata('error')!=''){?>
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <?php // echo $this->session->flashdata('error'); ?>
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
                                    <th id="srNo">S.No.</th>
                                    <th id="memberno">Membership No.</th>
                                    <th id="firstname">Candidate Name</th>
                                    <th id="dateofbirth">Exam Name</th>
                                    <th id="dateofbirth">Exam Period</th>
                                    <th id="exam_center_code">Center Code</th>
                                    <th id="exam_fee">Fee</th>
                                    <th id="pay_status">Payment Status</th>
                                    <th id="">Neft/Utr No</th>
                             
                                </tr>
                            </thead>
                             <tbody class="no-bd-y" id="list">
								  <?php
								  
								    if(count($member_list)){
										
									$i=0;
									 foreach($member_list as $row){ 

									 $i++;
									
									 //get member basic details 
									  $member_info=array();
									  //to find where the memeber is existing non member of new fresh member 
									  $member_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$row['regnumber'],'isactive'=>'1'));
									  if(empty($member_info))
									  {
										  
										    $member_info=$this->master_model->getRecords('member_registration',array('regid'=>$row['regnumber'],'isactive'=>'0'));
										
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
                                         <td><?php if($val['regnumber']!='')
										 {
											 echo $val['regnumber'];
										
										 }else
										 {
											 
											  echo $val['regid'];
											 
										 }?></td>
                                          <td><?php echo $val['firstname'].' '.$val['middlename'].' '.$val['lastname'];?></td>
                                            <td><?php 
											 $exm_name=$this->master_model->getRecords('exam_master',array('exam_code'=>$row['exam_code']),'description');
											if(isset($exm_name))
											{
											   echo $exm_name[0]['description'];
											}else
											{
												echo '';
											}?></td>
                                             <td><?php echo $row['exam_period'];?></td>
                                               <td><?php echo $row['exam_center_code'];?></td>
                                                    <td><?php echo $row['exam_fee'];?></td>
                                                       <td><?php if( $row['pay_status'] == 0 ) {echo 'Fail';} else if( $row['pay_status'] == 2 ) { echo 'Pending'; } else if($row['pay_status'] == 3) { echo 'Payment For Approve By IIBF';} else if( $row['pay_status'] == 1 ) { echo 'Payment Done'; } ?></td> 
                                          <td><?php echo '-';?></td>
                             
                                        </tr>
									<?php }
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
