<?php $this->load->view('admin/includes/header');?>
<?php 
$userRole = $this->session->userdata('roleid');

if($userRole == 1){
		?>
<?php $this->load->view('admin/includes/sidebar');?>
<?php } else {
  ?>
  <?php $this->load->view('admin/includes/centerchange_sidebar');?>
  <?php
} ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
     
    
    <form class="form-horizontal" name="" id=""  method="post"  enctype="multipart/form-data" action="">
    
			<input type="hidden" name="regid" id="regid" value="<?php echo $row['regid'];?>"> 
				<section class="content">
					<div class="row">
					
										<div class="box box-info">
												<div class="box-header with-border">
													<h3 class="box-title">Center Change Request</h3>
												</div>
								
						
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
													<?php } 
														if(validation_errors()!=''){?>
														<div class="alert alert-danger alert-dismissible">
																<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
																
																<?php echo validation_errors(); ?>
														</div>
													<?php } 
													?> 
                          <input type="hidden" name="txtCenterCode" id="txtCenterCode"  class="form-control pull-right" readonly="readonly"
                          value="<?php echo $row['center_code'] ?>">
                          <input type="hidden" name="center_name" id="center_name"  class="form-control pull-right" readonly="readonly"
                          value="<?php echo $row['center_name'] ?>">
                          
                          <input type="hidden" name="regnumber" id="regnumber" value="<?php echo $row['regnumber'];?>">
                          <input type="hidden" name="extype" id="extype" value="<?php echo $row['exam_type'];?>">
                          <input type="hidden" name="examcode" id="examcode" value="<?php echo $row['exam_code'];?>">
                          <input type="hidden" id="excd" name="excd"  value="<?php echo base64_encode($row['exam_code']);?>">
                          <input id="eprid" name="eprid" type="hidden" value="<?php echo $row['exam_period'];?>">
													<div class="form-group">
														<label for="roleid" class="col-sm-3 control-label">Exam </label>
														<div class="col-sm-4">
                                <?php echo $row['description'] ?>
																<input type="hidden" class="exam_period" name="exam_period" value="">
																<span class="error"><?php //echo form_error('idproofphoto');?></span>
														</div>
													</div>
													
													<div class="form-group">
														<label for="roleid" class="col-sm-3 control-label">Current Centre <span style="color:#F00">*</span></label>
														<div class="col-sm-4">
                            <?php echo $admitcard_details['center_name'] ?>
															<br>
                              <a href="<?php echo base_url(); ?>/uploads/admitcardpdf/<?php echo $admitcard_details['admitcard_image'] ?>" target="_blank">View Admit card</a>
															</div>
													</div>
													
													
																
													<div class="form-group">
															<label for="roleid" class="col-sm-3 control-label">Transfer order letter <span style="color:#F00">**</span></label>
															<div class="col-sm-5">
                              <a target="_blank" href="<?php echo base_url(); ?>/uploads/transfer_letters/<?php echo $row['transfer_letter'] ?>"><img class="mem_reg_img" id="image_upload_transfer_letter_preview" height="100" width="100" src="<?php echo base_url(); ?>/uploads/transfer_letters/<?php echo $row['transfer_letter'] ?>"/></a>
																
														</div>
                            </div>
                            <div class="form-group">
                              <label for="roleid" class="col-sm-3 control-label">Set Centre <span style="color:#F00">*</span></label>
                              <div class="col-sm-4">
                                  <select readonly  id="selCenterName" name="selCenterName" class="form-control  center_name ">
                                    <option selected class=<?php echo $row['exammode'];?> value="<?php echo $row['center_code']?>"><?php echo $row['center_name'] ?></option>
                                </select>
                              </div>
													  </div>
												
                            <div class="form-group">
                              <label for="roleid" class="col-sm-3 control-label">Application Status <span style="color:#F00">*</span></label>
                              <div class="col-sm-4">
                                  <select <?php if($row['status']!=2) echo'disabled'; ?> required  id="application_status" name="application_status" class="form-control application_status">
                                    <option value="">Select</option>                                    
                                    <option <?php if($row['status']==1) echo 'selected' ?>  value="1">Approved</option>
                                    <option  <?php if($row['status']==0) echo 'selected' ?> value="0">Reject</option>
                                </select>
                                <?php if($row['status']!=2)  { ?>
                                  <input type="hidden" id="application_status"  value="<?php echo $row['status'] ?>" name="application_status" class="form-control application_status">
                                <?php } ?>
                              </div>
													  </div>
                            <div class="form-group reject_div" style="display:none;">
                              <label for="roleid" class="col-sm-3 control-label">Reject Reason <span style="color:#F00">*</span></label>
                              <div class="col-sm-4">
                                  <input type="text" id="reject_reason" name="reject_reason" class="form-control reject_reason">
                                    
                              </div>
													  </div>
                            <div class="approved_div" style="display:none;">
                              <?php  
                                $i=1;
                                foreach($compulsory_subjects as $subject_arr)
                                {
                                  foreach($subject_arr as $subject)
                                  { ?>
                                                <div class="form-group div_subject">
                                                  <label for="roleid" class="col-sm-3 control-label"><?php echo $subject['subject_description']?><span style="color:#F00">*</span></label>
                                                    <div class="col-md-2 col-sm-3">
                                                    <label for="roleid" class="col-sm-3 control-label">Venue<span style="color:#F00">*</span></label>
                                            <select  name="venue[<?php echo $subject['subject_code']?>]" id="venue_<?php echo $i;?>" class="form-control venue_cls"   onchange="venue(this.value,'date_<?php echo $i;?>','time_<?php echo $i;?>','<?php echo $subject['subject_code']?>','seat_capacity_<?php echo $i;?>');" attr-data='<?php echo $subject['subject_code']?>'>
                                                    <option value="">Select</option>
                                                    </select>
                                                    </div>
                                                    
                                                    <div class="col-md-2 col-sm-3">
                                                    <label for="roleid" class="col-sm-3 control-label">Date<span style="color:#F00">*</span></label>
                                                    <select name="date[<?php echo $subject['subject_code']?>]" id="date_<?php echo $i;?>" class="form-control date_cls"   onchange="date(this.value,'venue_<?php echo $i;?>','time_<?php echo $i;?>');">
                                                    <option value="">Select</option>
                                                    </select>
                                                    </div>
                                                    
                                                    <div class="col-md-2 col-sm-3">
                                                    <label for="roleid" class="col-sm-3 control-label">Time<span style="color:#F00">*</span></label>
                                                    <select name="time[<?php echo $subject['subject_code']?>]" id="time_<?php echo $i;?>" class="form-control time_cls"  onchange="time(this.value,'venue_<?php echo $i;?>','date_<?php echo $i;?>','seat_capacity_<?php echo $i;?>');">
                                                    <option value="">Select</option>
                                                    </select>
                                                    </div>
                                                    
                                                  
                                                    <label for="roleid" class="col-sm-9 col-sm-offset-3 col-md-2 col-md-offset-0  control-label" style="text-align:left;">Seat(s) Available<span style="color:#F00">*</span>  <div id="seat_capacity_<?php echo $i;?>" >
                                                    -
                                                    </div></label>
                                                  
                                                </div>
                                                  
                                                  
                                                <?php 
                                                $i++;}
                                 }
                                 ?>
                              </div>
                            
													<div class="box-footer">
														<div class="col-sm-12 text-center">
                            <?php if($row['status']==2)  { ?>
															<input type="submit" class="btn btn-info" name="btnPreviewSubmit" id="btnPreviewSubmit" value="Submit" >
                              <?php } ?>
														</div>
													</div>
												</div>
										</div>

										

								</div>     
					
					</div>
				</section> 
  
		</form>
	</div>

<!-- Data Tables -->

<link href="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">

<!-- Data Tables -->
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>

<script src="<?php echo base_url()?>assets/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/datepicker/datepicker3.css">

<script src="<?php echo base_url()?>assets/admin/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/timepicker/bootstrap-timepicker.min.css">

<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script>
<script type="text/javascript">
  //$('#searchExamDetails').parsley('validate');
  //$('#searchReg').parsley('validate');
</script>

<script src="<?php echo base_url()?>js/js-paginate.js"></script>
<script type="text/javascript">

var site_url = "<?php echo base_url(); ?>";

//approved_reject_func();
$('select.application_status').on('change', function() {
  //approved_reject_func();
});
function approved_reject_func() {
  
  if($('select.application_status').val()==1) {
    $('.approved_div').show();
    $("select.venue_cls").each(function() {
      $(this).attr('required','required');
    });
    $("select.date_cls").each(function() {
      $(this).attr('required','required');
    });
    $("select.time_cls").each(function() {
      $(this).attr('required','required');
    });
    $('input.reject_reason').removeAttr('required');
    $('.reject_div').hide();
    
  }
  else if($('select.application_status').val()==3) {
    $('.approved_div').hide();
    $("select.venue_cls").each(function() {
      $(this).removeAttr('required','required');
    });
    $("select.date_cls").each(function() {
      $(this).removeAttr('required','required');
    });
    $("select.time_cls").each(function() {
      $(this).removeAttr('required','required');
    });
    $('input.reject_reason').attr('required','required');
    $('.reject_div').show();
  }
}
function date(date_code,venue_id,time_id)
	{
		var selCenterCode= document.getElementById('selCenterName').value;
		var venue_code= document.getElementById(venue_id).value;
		
		//if(date_code!='' && venue_code!='' && selCenterCode!='')
		//{
		$(".loading").show();
			var datastring='centerCode='+selCenterCode+'&venue_code='+venue_code+'&date_code='+date_code;
			$.ajax({
								url:site_url+'Venue/getTime/',
								data: datastring,
								type:'POST',
								async: false,
								dataType: 'json',
								success: function(data) {
								//$.parseJSON(data);
								 if(data)
								{
									
									document.getElementById(time_id).innerHTML = data.time_option;
                 
								}
							}
					});		
				//}	
		$(".loading").hide();
		}
		
	
		
	function time(time,venue_id,date_id,seat_capcity_id)
	{
		var selCenterCode= document.getElementById('selCenterName').value;
		var venue_code= document.getElementById(venue_id).value;
		var date_id= document.getElementById(date_id).value;
		
		$(".loading").show();
			var datastring='centerCode='+selCenterCode+'&venue_code='+venue_code+'&date_code='+date_id+'&time='+time;
			$.ajax({
							url:site_url+'Venue/getCapacity/',
							data: datastring,
							type:'POST',
							async: false,
							dataType: 'json',
							success: function(data) {
							
							 if(data)
							{
								document.getElementById(seat_capcity_id).innerHTML = data.capacity;
							}
              
						}
					});		
			$(".loading").hide();
		}
	
	

  function venue(venue_code,date_id,time_id,subject_code,seat_capacity_id)
	{
		var selCenterCode= document.getElementById('selCenterName').value;
		var eprid= document.getElementById('eprid').value;
		var examcode= document.getElementById('examcode').value;
		document.getElementById(seat_capacity_id).innerHTML = '-';
		
		
			$(".loading").show();
				
				var datastring='eprid='+eprid+'&examcode='+examcode+'&subject_code='+subject_code+'&venue_code='+venue_code;
				$.ajax({
								url:'<?php echo base_url(); ?>Venue/getDate/',
								data: datastring,
								type:'POST',
								async: false,
								dataType: 'json',
								success: function(data) {
								
								 if(data)
								{
								
									document.getElementById(date_id).innerHTML = data.date_option;
									document.getElementById(time_id).innerHTML = data.time_option;
									
								}
							}
					});		
				
			$(".loading").hide();
	}
$(document).ready(function() 
{
  valCentre("<?php echo $row['center_code']?>");
  function valCentre(cCode)
	{
		 var subject_array=new Array();  
		document.getElementById('txtCenterCode').value = cCode ;
		var examType = document.getElementById('extype').value;
		var examCode = document.getElementById('examcode').value;
		var temp = document.getElementById("selCenterName").selectedIndex;
		var selected_month = document.getElementById("selCenterName").options[temp].className;
		var eprid = document.getElementById('eprid').value;
		var excd = document.getElementById('excd').value;
		
		var venue_elements=document.getElementsByClassName('venue_cls');
		for (var i=0; i<venue_elements.length; i++) {
			if(venue_elements[i].getAttribute("attr-data")!='' && venue_elements[i].getAttribute("attr-data")!=null)
			{
				subject_array[i]=venue_elements[i].getAttribute("attr-data");
			}
		}
	
		
		if(cCode!='')
		{ 		
						
						
				var datastring_exam='centerCode='+cCode+'&examCode='+examCode+'&subject_array='+subject_array;
				$.ajax({
								url:'<?php echo base_url(); ?>Venue/getVenue/',
								data: datastring_exam,
								type:'POST',
								async: false,
								dataType: 'json',
								success: function(data) {
								//$.parseJSON(data);
								 if(data)
								{
									
									var venue_elements=document.getElementsByClassName('venue_cls');
							
									for (var i=1; i<=venue_elements.length; i++) {
										
											 document.getElementById("venue_"+i).innerHTML = data["venue_option_" + i]; 
                  }
                   $("select.venue_cls").each(function() {
                    //$(this).find('option:eq(1)').prop("selected", true).change();
                  });
									
									}
								}
						});		
			}
			
		
	}
	
	
$(window).keydown(function(event){
	if(event.keyCode == 13) {
		event.preventDefault();
		return false;
	}
});
	
});

$("#garplistitems").DataTable();
</script>

 
<?php $this->load->view('admin/includes/footer');?>