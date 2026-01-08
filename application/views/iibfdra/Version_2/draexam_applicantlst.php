<div class="content-wrapper">
	<!-- Content Header (Page header) -->
    <section class="content-header">
    	<?php 
			$desc = '';
			foreach( $active_exams as $exam ) {
				//if($examcode == base64_encode($exam['exam_code']))
				if($examcode == $exam['exam_code'])
				{
					$desc = strtolower($exam['description']);
					$desc = str_ireplace('debt recovery agent','DRA',$desc);
					$desc = str_ireplace('examination','Exam',$desc);
					$desc = str_ireplace('-','',$desc);
				}
			}
		?>
    	<h1><?php echo ucwords($desc);?> Application Entry And Payment</h1>
    </section>
	<!-- Main content -->
    <form name="draexampay" class="draexampay" method="post" action="<?php echo base_url();?>iibfdra/Version_2/DraExam/payment/<?php echo base64_encode($examcode);?>">
    <section class="content">
	    <div class="row">
    		<div class="col-xs-12">
				<div class="box">
            		<div class="box-header">
              			<h3 class="box-title"><?php echo ucwords($desc);?> Application</h3>
              			<div class="pull-right">
                            <a href="<?php echo base_url();?>iibfdra/Version_2/DraExam/add/?exCd=<?php echo base64_encode($examcode);?>" class="btn btn-warning">Add New Application</a>
                            <input type="submit" name="make_payment" class="btn  btn-primary mk-payment" value="Make Payment"/>
                            <input type="button" class="btn btn-info" onclick="refreshDiv('');" value="Refresh">
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
                                    <th id="srNo">S.No.</th>
                                    <th id="firstname">Candidate Name</th>
                                    <th id="dateofbirth">DOB</th>
                                    <th id="email">Email</th>
                                    <th id="exam_center_code">Center Code</th>
                                    <th id="exam_fee">Fee</th>
                                    <th id="pay_status">Payment Status</th>
                                    <th id="">Neft/Utr No</th>
                                    <th id="action">Operations</th> 
                                </tr>
                            </thead>
                             <tbody class="no-bd-y" id="list">
								  <?php 
								  if(count($result)){
									  //$i = 1;
									  $i = $startidx;
                                        foreach($result as $row){ 
											//print_r($row); 
											?>
                                  	
                                    <tr>
                                    	<td align="center">
                                        <?php if( $row['pay_status'] == 0 || $row['pay_status'] == 2 ) { ?>
                                        <input type="checkbox" class="chkmakepay" name="chkmakepay[]" value="<?php echo $row['mem_examid'];?>"/><?php }?></td>
                                        <td><?php echo $i;?></td>
                                        <td><?php echo $row['firstname'].' '.$row['middlename'].' '.$row['lastname'];?></td>
                                        <td><?php echo $row['dateofbirth'];?></td>
                                        <td><?php echo $row['email'];?></td>
                                        <td><?php echo $row['exam_center_code'];?></td>
                                        <td><?php echo $row['exam_fee'];?></td>
                                        <td><?php if( $row['pay_status'] == 0 ) {echo 'Fail';} else if( $row['pay_status'] == 2 ) { echo 'Pending'; } else if($row['pay_status'] == 3) { echo 'Payment For Approve By IIBF';} ?></td>
                                        <td><?php echo $row['utr_no'];?></td>
                                        <td>
                                        	<?php if( $row['pay_status'] == 0 || $row['pay_status'] == 2 ) { ?>
                                        	<a href="<?php echo base_url().'iibfdra/Version_2/DraExam/edit/'.$row['mem_examid'];?>">Edit |</a><a href="<?php echo base_url().'iibfdra/Version_2/DraExam/delete/'.$row['mem_examid'];?>" onclick="return confirm('Are you sure to delete this record?');">Delete </a>
                                            <?php }?>
                                        </td>
                                    </tr>
                                  <?php $i++; } } ?>                  
                                </tbody>
                        </table>
                        <div style="width:30%; float:left;">
					   		<?php echo $info; ?>
                        </div>
                        <div id="links" class="" style="float:right;"><?php echo $links; ?></div>
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
	var base_url = '<?php //echo base_url(); ?>';
	paginate(base_url+'iibfdra/Version_2/DraExam/getApplicantList','','','');
	$("#base_url_val").val(base_url+'iibfdra/Version_2/DraExam/getApplicantList');*/
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