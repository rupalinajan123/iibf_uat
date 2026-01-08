<?php $this->load->view('iibfdra/Version_2/admin/includes/header');?>
<?php $this->load->view('iibfdra/Version_2/admin/includes/sidebar');?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      [ <?php echo $result['inst_name']; ?> ] Renew Regular Training Centers
      </h1>
      <?php echo $breadcrumb; ?>
      
    </section>
	<div class="col-md-12">
    <br />    
    <?php 
	 if($this->session->flashdata('success')!=''){ ?>
              <div class="alert alert-success alert-dismissible" id="success_id">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('success'); ?> </div>
     <?php }?>    
    </div>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
        <div class="box-header">
 <h3 class="box-title">Regular Center List</h3> </div>
          <div class="box">
          	
            <div class="box-body">            
            <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
        	<input type="hidden" name="base_url_val" id="base_url_val" value="" />
            <div class="table-responsive">
              <table id="listitems22" class="table table-bordered table-striped dataTables-examplex">
                <thead>
                <tr>
                    <th class="no-sort text-center" style="width:20px;"></th>
                  <th style="width:5%;">S.No.</th>
                  <th>Center Location</th>                
                  <th>Date of Approval</th>
                  <th>Valid From</th>
                  <th>Valid To</th>
                  <th>Center Type</th>
                  <th>Status</th>
                  <th>Due/Paid Status</th>
                    <th class="no-sort text-center">Operations</th>
                </tr>
                </thead>
                <tbody class="no-bd-y" id="list_centers">  
                    
                 <?php 	
				 // 1 for appliced for renew
				 // 2 not appilde for renew				
				 $renew_type = '';
				 $renew_apply_date = '';
				// print_r($res_center_renew);
				 
				if(count($res_center_renew) > 0){
					$renew_year = date_format(date_create($res_center_renew[0]['created_on']),"Y");
					$renew_apply_date = date_format(date_create($res_center_renew[0]['created_on']),"d-M-Y"); 
					$curr_year = date('Y');
					
					//echo '<br> renew_year'.$renew_year;
					//echo '<br> curr_year'.$curr_year;
					
					if($renew_year == $curr_year){
						$is_applied_renew = 1;
						$pay_status = $res_center_renew[0]['pay_status'];
						$renew_type = $res_center_renew[0]['renew_type'];
					}else{
						$is_applied_renew = 2;
						$pay_status = 2;
						}
					
				}else{
						$is_applied_renew = 2;
						$pay_status = 2;
					}
				 
				 			 
				$k = 1;
        $today_day = date('Y-m-d');   
        $today_date = strtotime($today_day);
				if(count($center_result) > 0){
					foreach($center_result as $res){

          $to_date =  strtotime(date('Y-m-d',strtotime($res['center_validity_to'])));
          $from_date =  strtotime(date('Y-m-d',strtotime($res['center_validity_from'])));
          $payment_date = $res['payment_date'] != '' && $res['payment_date'] != null ? strtotime(date('Y-m-d',strtotime($res['payment_date']))) : '';

                        echo '<tr>
                        <td class="text-center"><label class="css_checkbox_radio"><input type="checkbox" name="checkboxlist_new" class="checkboxlist_new" value="'.$res['center_id'].'" id="checkboxlist_new_'.$res['center_id'].'" onclick="update_center_ids_str('.$res['center_id'].')"><span class="checkmark"></span></label></td>
                        <td>'.$k.' </td>';
					
					if($res['city_name'] != ''){
						$city = $res['city_name'];
						}else{
						$city = $res['location_name'];	
						}
					echo '<td>'.$city.'</td>'; 
                    
                    if($res['date_of_approved'] != ''){
						$app_date =  date_format(date_create($res['date_of_approved']),"d-M-Y"); 
					} else { 
						$app_date =  '--'; 	
					}
					
					if($res['center_validity_from'] == ''){
						$validity_from_date = '--';
						}else{
						$validity_from_date = date_format(date_create($res['center_validity_from']),"d-M-Y");	
					}
					
					if($res['center_validity_to'] == ''){
						$validity_to_date = '--';
						}else{
						$validity_to_date = date_format(date_create($res['center_validity_to']),"d-M-Y");	
						$todate = date_format(date_create($res['center_validity_to']),"d-m-Y");
					}
					
					if($res['center_status'] == 'A'){
						$center_status = 'Approved(A)';
						}elseif($res['center_status'] == 'IR'){
						$center_status = 'In Review';
					}elseif($res['center_status'] == 'R'){
						$center_status = 'Rejected';
					}elseif($res['center_status'] == 'AR'){
						$center_status = 'Approved(R)';
					}else{
						$center_status = '--';
					}
										
					echo '<td>'.$app_date.' </td>';
					echo '<td>'.$validity_from_date.' </td>';
					echo '<td><input type="hidden" class="old_todate" value="'.$todate.'" />'.$validity_to_date.'</td>';
					echo '<td>'.$res['center_type'].' </td>';
					echo '<td>'.$center_status.' </td>';

          $payLable   = "Due";
          $color   = "#C30";
          if ( ($today_date <= $to_date && $today_date >= $from_date && $res['pay_status'] == 1) ) 
          {   
            $payLable   = "Paid";
            $color   = "#050";
          } 
          else 
          { 
            if ( ($payment_date >= $to_date && $payment_date >= $from_date && $res['pay_status'] == 1) ) 
            { 
              $payLable   = "Paid";
              $color   = "#050"; 
            }
          } 

          echo '<td><strong style="color:'.$color.';">'.$payLable.'</strong></td>';
					echo '<td><a class="btn btn-info btn-xs vbtn" href="'.base_url().'iibfdra/Version_2/agency/training_center_detail/'.$res['center_id'].'" >View</a> </td></tr>';

					$center_arry[] = $res['center_id'];
					$k++;
					}
				}?>                              
                </tbody>
              </table>
              <div id="links" class="dataTables_paginate paging_simple_numbers">             
              </div>
            </div>
            
            <br><button class="btn btn-success mt-2" onclick="update_center_validity()">Update Center Validity</button>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        
      <div class="modal fade" id="UpdateCenterValidity" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <form id="update_center_validity_Form" method="post" action="" enctype="multipart/form-data" autocomplete="off">
              <input type="hidden" name="selected_center_ids_hidden" id="selected_center_ids_hidden" value="">
              <input type="hidden" name="form_action" id="form_action" value="update_center_validity_Form">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Update Center Validity Period</h4>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label for="center_validity_from_date">From Date *</label>
                  <input type="text" class="form-control center_validity_from_to_date" id="center_validity_from_date" name="center_validity_from_date" placeholder="From Date" required readonly onchange="validate_input('center_validity_from_date')">
                </div>
                <div class="form-group">
                  <label for="center_validity_to_date">To Date *</label>
                  <input type="text" class="form-control center_validity_from_to_date" id="center_validity_to_date" name="center_validity_to_date" placeholder="To Date" required readonly onchange="validate_input('center_validity_to_date')">
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" name="update_center_validity_btn" value="update_center_validity_btn" class="btn btn-success">Submit</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      
        <div class="col-xs-12  col-md-12 acc_div" >
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Add Accreditation Period To Renew Regular Center </h3>
            <div class="box-tools pull-right"> 
              <!-- Collapse Button -->
              <button type="button" class="btn btn-box-tool" data-widget="collapse"> <i class="fa fa-minus"></i> </button>
            </div>
            <!-- /.box-tools --> 
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="table-responsive">
              <form name="renew_form" id="renew_form" method="POST">               
                <input type="hidden" id="center_add_status" name="center_add_status" value="" /> 
                <input type="hidden" value="1" name="is_renew" id="is_renew" />
                <input type="hidden" value="<?php echo $result['id']; ?>" name="agency_id" id="agency_id" />
                <input type="hidden" value="<?php echo implode(',',$center_arry); ?>" name="center_ids" id="center_ids" />
                		
                <table class="table table-bordered">
                  <tbody>
                  <?php if($is_applied_renew != 1){?>
                  
					<?php					  
                    if(1){  
                    $current_year = date('Y');
                    $next_year = $current_year+1;
                    $next_next_year = $next_year+1;
                    ?>
                  <td width="50%"><strong> Add From Date - To Date Of Accreditation Period :</strong></td>
                    <td width="50%">
                      From date : <input type="text" class="center_validity form-control rtext" name="center_validity_from_date" id="center_validity"  maxlength="10" /> To date : 
                      <select class="center_validity form-control center_validity_to rtext" name="center_validity_to_date" id="center_validity_to_date">
                      <option  value="31-03-<?php echo $next_year;?>" >31 March <?php echo $next_year;?> </option>
                      <option value="31-03-<?php echo $next_next_year;?>" >31 March <?php echo $next_next_year;?></option>
                      </select></td>                      
                    <?php } ?>
                 
                     <tr>
                    <td width="50%"><strong>Renewal Type : </strong> 
                    </td>
                    <td width="50%">
                    <select class="form-control renewal rtext" name="renewal_type" id="renewal" >
                      <option  value="" >- Select Renewal type -</option>
                      <option  value="free" >Free renewal</option>
                      <option value="pay" >Renew with Payment</option>
                      </select>
                    </td>
                  </tr>
                  
                  <tr>
                    <td width="50%"> </td>
                    <td width="50%"><a class="renew_btn btn btn-success" href="javascript:void(0);">Renew</a></td>
                  </tr>
                  <?php }else{?>
                  
                  <?php if(isset($renew_type) && $renew_type=='pay' ){ ?>
                 	<tr>
                      <td width="50%"><strong>Pay status :</strong></td>
                      <td width="50%"><strong><?php if($pay_status == 1 ){ echo '<span class="pay_green">Paid</span>'; }else{ echo '<span class="not_pay">Pending</span>';} ?></strong></td>
                    </tr>
                    <?php } ?>
                  
                    <tr>
                      <td width="50%"><strong>Renual with Pay type (Free/pay) :</strong></td>
                      <td width="50%"><strong><span class="pay_type"><?php if(isset($renew_type)){ echo $renew_type; }else{ echo '--';} ?></span></strong></td>
                    </tr>
                  <tr>
                    <td width="50%"> </td>	
                    <td width="50%"><strong><span class="act_msg">Already Applied for Renew on (
					<?php if($renew_apply_date){ echo $renew_apply_date; }?>)
                    </span></strong>
                    
                    <?php 
					if(isset($res['created_on'])){
						
					}					
					?>
                    </td>
                  </tr>
                 
                  <?php }?>
                    </tbody>
                  
                </table>
                
                <input type="hidden" name="action" value="renew_regular" />
              </form>
            </div>
          </div>
          <!-- box-footer --> 
        </div>
        <!-- /.box --> 
      </div>
      
        <div class="col-xs-12  col-md-12">
          <div class="box box-info box-solid disabled">
            <div class="box-header with-border">
              <h3 class="box-title">Agency Basic Details</h3>
              <div class="box-tools pull-right">
                <button data-widget="collapse" class="btn btn-box-tool" type="button"><i class="fa fa-minus"></i> </button>
              </div>
              <!-- /.box-tools --> 
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="display: block;">
               <?php 
					   if($result['status'] == 1 ){ 
					   		$status_text =  'Active'; 
							$str_btn = '<textarea name="reject_reason" class="reject_reason" rows="5" cols="40" placeholder="Describe deactive reason here"></textarea></br><a class="reject_aj btn btn-danger " dataid ="'.$result['id'].'" href="javascript:void(0);">Click to Deactive</a>';
							
							$div_class = '#d4edda';
							$div_class2 = '#d4edda';
					   }else { 
					   		$status_text =  'Deactive'; 
							$str_btn = '<a class="approve_aj btn btn-success " dataid ="'.$result['id'].'" href="javascript:void(0);">Click to Active</a>';
							//$str_btn = '';
							$div_class = '#f8d7da';
							$div_class2 = '#f8d7da';
					   }
					   
					   $div_class = '';	
					   $div_class2 = '';					  
				?>
              <form method="post" name="appfrom" id="approve_from" >
              <input type="hidden" name="status" value="<?php echo $result['status'] ?>" />
              </form>
              <div class="table-responsive ">
                  <table class="table table-bordered" style="background:<?php echo $div_class; ?>;">
                    <tbody>
                    <tr>                    
                      <td width="50%"><strong>Agency Name :</strong></td>
                      <td width="50%"><?php echo $result['inst_name']; ?></td>
                    </tr> 
                     <tr style="display:none;">                    
                      <td width="50%"><strong>Year Of Establishment :</strong></td>
                      <td width="50%"><?php echo $result['estb_year']; ?></td>
                    </tr>   
                                  
                    <tr style="display:none;">
                      <td width="50%"><strong> Agency Telephone Number / Fax number :</strong></td>
                      <td width="50%"><?php if($result['inst_stdcode']!=''){ echo $result['inst_stdcode']; }else{ echo '-';}; ?>&nbsp; <?php if($result['inst_phone']!=''){ echo $result['inst_phone']; }else{ echo '---';}; ?> / 
                       <?php if($result['inst_fax_no']!=''){ echo $result['inst_fax_no']; }else{ echo '---';}; ?></td>
                    </tr>
                    <tr style="display:none;">
                      <td width="50%"><strong>Agency Website :</strong></td>
                      <td width="50%"><?php if($result['inst_website']!=''){ echo $result['inst_website']; }else{ echo '---';}; ?></td>
                    </tr>
                     <tr>
                      <td width="50%"><strong>Agency Main Address :</strong></td>
                      <td width="50%"><?php echo $result['main_office_address']; ?> <?php echo $result['main_address1']; ?> <?php echo $result['main_address2']; ?> <?php echo $result['main_address3']; ?> <?php echo $result['main_address4']; ?> <?php echo $result['main_district']; ?> <?php if( $result['city_name'] != ''){ echo $result['city_name']; }else{ echo $result['main_city']; } ; ?> <?php echo $result['state_name']; ?> <?php echo $result['main_pincode']; ?></td>
                    </tr>
                    <tr style="display:none;">
                      <td width="50%"><strong>Name Of Director/ Head Of Agency :</strong></td>
                      <td width="50%"><?php echo $result['inst_head_name']; ?></td>
                    </tr>
                    <tr style="display:none;">
                      <td width="50%"><strong>Director Contact Number / Email Id :</strong></td>
                      <td width="50%">
                       <?php if($result['inst_head_contact_no']!=''){ echo $result['inst_head_contact_no']; }else{ echo '---';}; ?> /
                        <?php if($result['inst_head_email']!=''){ echo $result['inst_head_email']; }else{ echo '---';}; ?>
					</td>
                    </tr>
                                        
                     <tr>
                      <td width="50%"><strong>Agency Type :</strong></td>
                      <td width="50%"><?php 
						  if($result['inst_type'] = 'R'){
							echo 'Regular'; 
						  }else{
							echo 'Mobile';  
						  }
					  ?></td>
                    </tr>
                     <tr>
                      <td width="50%"><strong>Agency Status :</strong></td>
                      <td width="50%" style="background:<?php echo $div_class2; ?>;"><strong><?php echo $status_text; ?></strong>
					 </td>
                    </tr>
                    
                      <tr>
                      <td width="50%"><strong>Action</strong></td>
                      <td width="50%">
					  <?php echo $str_btn; ?></td>
                    </tr>
                    
                  </tbody></table>
              </div>
              
            </div>
            <!-- /.box-body --> 
          </div>
          <!-- /.box --> 
        </div>
        
      
      </div>
        
    </section>   
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
  //$('#searchDate').parsley('validate');
</script>

<style>
.rtext{
width:225px;	
}
.err{
 border:1px solid #F00;	
}
.reject_reason{
 display:none;	
}

.input_search_data{
 width:100%;	
}
 tfoot {
    display: table-header-group;
}
.vbtn{
padding: 3px 21px;
font-weight: 900;
}
table.dataTable th{
	text-align:center;
	text-transform:capitalize;	
}
.act_msg {
    font-size: 18px;
    font-style: italic;
    color: #900;
    widows: 100%;
}
.pay_green{
 color:#0C0;
 text-transform:capitalize;		
}
.not_pay{
 color:#606;
 text-transform:capitalize;		
}

.pay_type{
 color:#030;
 text-transform:capitalize;		
}
</style>

<script src="<?php echo base_url()?>js/js-paginate.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script>
  $(".center_validity_from_to_date").datepicker({
    autoclose: true, 
    format: 'yyyy-mm-dd',							        
    dateFormat: 'yyyy-mm-dd',
    keyboardNavigation: true, 
    forceParse: true, 
    autoclose: true, 
    onSelect: function(selected) {
      //$("#center_validity_to_date").datepicker("startDate", selected)
    }	
  }).attr('readonly', 'readonly');

  function update_center_ids_str(id)
  {
    var selected_ids = $("#selected_center_ids_hidden").val();	
    explode_arr = selected_ids.split(',');
    
    if($("#checkboxlist_new_"+id).prop("checked") == true) { if(selected_ids == "") { selected_ids = id; } else { if(jQuery.inArray(id, explode_arr) !== 1) { selected_ids = selected_ids + "," + id; } } }
    else { if(jQuery.inArray(id, explode_arr) !== 1) { explode_arr = jQuery.grep(explode_arr, function(value) { return value != id; }); selected_ids = explode_arr.join(',') } }
    $("#selected_center_ids_hidden").val(selected_ids);
  }

  function update_center_validity()
  {
    var myArray = [];
    var checkValues = $('#selected_center_ids_hidden').val();
    
    if(checkValues=="") { alert("Please select at least one center to update its validity period"); }
    else
    {
      $('.center_validity_from_to_date').val("").datepicker("update");
      $("#UpdateCenterValidity").modal("show");
    }
  }

  function validate_input(input_id) { $("#"+input_id).valid(); }
  $(document).ready(function() 
  {
    $.validator.addMethod("endDateHigherThanStartDate", function(value, element) 
    {
      var startDate = $("#center_validity_from_date").datepicker("getDate");
      var endDate = $("#center_validity_to_date").datepicker("getDate");
      return endDate > startDate;
    }, "To date must be greater than From date.");

    $("#update_center_validity_Form").validate(
    {
      onkeyup: function(element) { $(element).valid(); }, 
      rules: 
      {
        center_validity_from_date: { required: true, date: true },
        center_validity_to_date: { required: true, date: true, endDateHigherThanStartDate: true }
      },
      messages: 
      {
        center_validity_from_date: { required: "Please select From date.", date: "Please select a valid From date." },
        center_validity_to_date: { required: "Please select To date.", date: "Please select a valid To date." }
      },
      submitHandler: function(form) 
      {
        var myArray = [];
        var checkValues = $('#selected_center_ids_hidden').val();
        explode_cnt = checkValues.split(',');

        if(confirm("Please confirm to update the validity period for selected "+explode_cnt.length+" centers?"))
        {
          form.submit();
        }	
      }
    });
  });
</script>
<script>

$("#center_validity").datepicker({
	autoclose: true, 
	format: 'dd-mm-yyyy',							        
	dateFormat: 'dd-mm-yyyy',
	onSelect: function(selected) {
	//$("#center_validity_to_date").datepicker("startDate", selected)
	}							
}).attr('readonly', 'readonly');

$(function () {
	
	
	var msg = '';
	
    $('.renew_btn').click(function()
    { 
	var renew_type = $('#renewal').val();
	//alert(renew_type);
	$('#center_validity_to_date').removeClass('err');	
	$('#center_validity').removeClass('err');	
	$('#renewal').removeClass('err');	
	
	var center_validity_from = $('#center_validity').val();
	var center_validity_to_date = $('#center_validity_to_date').val();
	
	if(center_validity_from == ''){
	   $('#center_validity').addClass('err');		
		return false;
	}
	
	if(center_validity_to_date == ''){
	   $('#center_validity_to_date').addClass('err');		
		return false;
	}
	
	var old_todate = $('.old_todate').val();
	if( old_todate == center_validity_to_date){
		 alert("Same Accreditation period is already assigned for renew");
		 return false;	
	}
	
	 if(renew_type == ''){
		 $('#renewal').addClass('err');
		  //alert('Please Slelct Renew Type'); 
		 return false;
	  }else{
		if(renew_type == 'free'){
		 var msg = 	' For Free Renewal'
		}else{
		 var msg = 	'with payment Amount of Rs.5000'	
		}  
	  }
	  
		if (confirm('Are you sure you want to Renew Agency '+msg+' ?')) {
			$('#renew_form').submit();	
		} else {
			return false;
		}			
	});
	
	
	
	$('.approve_aj').click(function(){  		
		if (confirm('Are you sure you want to activate Agency?')) {
			$('#approve_from').submit();	
		} else {
			return false;
		}			
	});
	
	
	$('.reject_aj').click(function(){
		$('.reject_reason').show();		
		var reject_reason = $('.reject_reason').val();
		
		if(reject_reason == ''){
		  $('.reject_reason').addClass('err');
		  return false;	
		}
		
  		if (confirm('Are you sure you want to deactivate Agency?')) {
			$('#approve_from').submit();	
		} else {
			return false;
		}		
	});
		
    $("#listitems22").DataTable(
    {
      searching: true,
      "processing": false,
      "serverSide": false,
      pageLength: 10,
      responsive: true,
      rowReorder: false,
      "aaSorting": [1, 'asc'],
      "columnDefs": 
      [
        {"targets": 'no-sort', "orderable": false, },
      ]			
    });	

	$("#listitems_filter").show();	
	var base_url = '<?php echo base_url(); ?>';
	var agency_id = '<?php echo $result["id"]; ?>';

});
</script> 

 <script>
    if ( window.history.replaceState ) {
      window.history.replaceState( null, null, window.location.href );
    }
    </script>
<?php $this->load->view('iibfdra/Version_2/admin/includes/footer');?>