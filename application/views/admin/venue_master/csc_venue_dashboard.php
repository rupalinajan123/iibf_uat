<?php $this->load->view('admin/venue_master/includes/header');?>
<?php $this->load->view('admin/venue_master/includes/sidebar_csc');?>
<!-- Content Wrapper. Contains page content -->
<style>
* {
  box-sizing: border-box;
}

/* Create two equal columns that floats next to each other */
.column {
  float: left;
  width: 30%;
  padding: 10px;
  height: 300px; /* Should be removed. Only for demonstration */
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}
</style>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Venue Counts </h1>
    <?php //echo $breadcrumb; ?>
  </section>

<!-- POOJA MANE 7/8/2022 -->
  <!-- ALERT FLASH MESSAGE  -->
    <div class="col-md-12">
        <?php if ($this->session->flashdata('error_message') != "") { ?>
        <div class="alert alert-danger alert-dismissable p-1" >
        <i class="fa fa-ban"></i>
        <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
        <b>Alert!</b> <?php echo $this->session->flashdata('error_message'); ?> </div>

        <?php } ?>
        <?php if ($this->session->flashdata('success_message') != "") { ?>
        <div class="alert alert-success alert-dismissable" >
        <i class="fa fa-check"></i>
        <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
        <b>Success!</b> <?php echo $this->session->flashdata('success_message'); ?> </div>
        <?php } ?>
    </div>
<!-- POOJA MANE 7/8/2022 -->

  <section class="content">
    <form class="form-horizontal" name="addForm" id="addForm" action="<?php echo base_url();?>admin/venue_master/CSCVenueDashboard" method="post">
      <!-- Main content -->
      <div class="row">
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">
                <?php // echo $title; ?>
              </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
              <?php //echo validation_errors(); ?>
              <?php if($this->session->flashdata('error')!=''){?>
              <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                <?php echo $this->session->flashdata('error'); ?> </div>
              <?php } if($this->session->flashdata('success')!=''){ ?>
              <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
                <?php echo $this->session->flashdata('success'); ?> </div>
              <?php } ?>
			  
			  
			  <div class="row">
  <div class="column" >
     <div class="control-group" id="div_authority_CC">
            <label for="textfield" class="control-label"><strong>Exam period</strong></label>
                <!-- Multiple Board Dropdown-->
                <div class="controls" id="board_dd_CC">
                  <select class="input-large form-control" id="authority" name="exm_prd[]" multiple required size="10" width="5">
                    <option value=""> <?php echo '-Select-'; ?> </option>
					<?php 
					$C_date =date('Y-m-d');
					$date =  date('Y-m-d', strtotime($C_date .' -15 day'));
					$exam_activation = $this->Master_model->getRecords("exam_activation_master", array(
					'exam_to_date >=' => $date,'exam_code' => '991',
					));
					
					$spexam=$nospexam=array();
					if(!empty($exam_activation))
					{
					
					$i=1;
					foreach($exam_activation as $val)
					{
						$exam_name = $this->Master_model->getRecords("exam_master", array(	'exam_code' => $val['exam_code']), 'description,exam_category', array(
					'description' => 'ASC'
					));
					
					if(!empty($exam_name))
					{
						if($exam_name[0]['exam_category']==1)
						{
						$this->db->distinct();
						$exam_prd = $this->Master_model->getRecords("special_exam_dates", '','period');
						foreach ($exam_prd as $childArray) 
						{ 
					
						foreach ($childArray as $value) 
						{ 
					
							$spexam[] = $value; 
						} 
					}
						
					}else
					{
						$this->db->distinct();
						$exam_prd1 = $this->Master_model->getRecords("exam_activation_master", array(
					'exam_to_date >=' => $date,'exam_code'=>$val['exam_code']
					),'exam_period');
					if(!empty($exam_prd1))
					{
						foreach($exam_prd1  as $prd)
						{
					
						$nospexam[]=$prd['exam_period'];
					}
					
					}
					
					}
					
					}
					}
					}
										
					$final_exam_prd=array_merge($spexam,$nospexam);	
					$uniquefinal_exam_prd=array_unique($final_exam_prd);
					echo'<pre>';print_r($_POST['exm_prd']);exit;
					if(!empty($uniquefinal_exam_prd))
					{
					
					foreach($uniquefinal_exam_prd  as $val)
					{
					
					?>
					<option value="<?php echo $val;?>"> <?php if(in_array($val,$_POST['exm_prd'])) echo'selected'; ?> <?php echo $val;?> </option>
					<?php
					
					}
					
					}
					?>
                  </select>
                </div>
              </div>
  </div>
  <div class="column" >
    <div class="control-group" id="div_department_CC">
              <label for="department" class="control-label"><strong>Exam Name</strong></label>
                <!--Multiple Department Dropdown-->
                <div class="controls" id="dept_dd_optoin_CC">
                  <select class="input-large form-control" id="department_CC" name="exm_cd[]" multiple size="10" required style="width:auto">
                    <option value="select"> <?php echo '- Select -'; ?> </option>
                  </select>
                </div>
                <div class="controls form-control" id="other_dept_option_CC" style="display:none;"></div>
              </div>
  </div>
</div>

            </div>
          </div>
          <div class="box-footer">
            <div class="col-sm-3 col-xs-offset-5">
              <?php $last = $this->uri->total_segments();
						$id = $this->uri->segment($last);
				?>
        
        <?php
        if(isset($_POST['exm_cd']) && !empty($_POST['exm_cd'])) {
          ?>
          <input type="hidden" class="selectedExamCode" value="<?php if(isset($_POST['exm_cd'])) echo implode(',',$_POST['exm_cd']); ?>">
          <?php
        } ?>
        <?php
        if(isset($_POST['exm_prd']) && !empty($_POST['exm_prd'])) {
          ?>
          <input type="hidden" class="selectedExamPeriod" value="<?php if(isset($_POST['exm_prd'])) echo implode(',',$_POST['exm_prd']); ?>">
          <?php
        } ?>
              <input type="hidden" name="fromrow" value="<?php if(isset($_POST['fromrow'])) echo $_POST['fromrow']+20; else echo'0' ?>">
              <input type="hidden" name="torow" value="<?php if(isset($_POST['torow'])) echo $_POST['torow']+20; else echo'20' ?>">
              <input type="submit" class="btn btn-info pull-left" name="btnSubmit" id="btnSubmit" value="Submit">
              <a href="<?php echo base_url();?>admin/venue_master/CSCVenueDashboard" class="btn btn-default pull-right">Refresh</a> 
            </div>
          </div>
          <?php //if($result_text!=''){?>
          <div class="box-footer">
            <div class="box-body">
              <?php //echo $result_text; ?>
              <?php //echo $links; ?>
            </div>
          </div>
          <?php // } ?>
        </div>
      </div>
    </form>
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Result  <?php if(isset($examName) && !empty($examName)) echo ' of - '.$examName ?> </h3>
          </div>
          <!-- /.box-header 
            S.No. 	Exam Code 	Exam Name 	Member Count
            -->
          <div class="box-body">
            <?php  if(!empty($Total_registration) )
			 {?>
            <a href="<?php echo base_url();?>admin/venue_master/CSCVenueDashboard/download_CSV/<?php echo $exm_prd; ?>/<?php echo $exm_cd; ?>">
            <button type="button" class="btn btn-warning pull-right">Downaload CSV</button>
            <br />
            </a> <br />
            <?php }?>
            <div class="table-responsive">
              <table id="listitems" class="table table-bordered table-striped dataTables-example">
                <thead>
                  <tr>
                    <th nowrap="nowrap">Vendor Code</th>
                    <th nowrap="nowrap">Center Code</th>
                    <th nowrap="nowrap">Center Name</th>
                    <th nowrap="nowrap">Venue Code</th>
                    <th nowrap="nowrap">Venue Name</th>
                    <!--<th nowrap="nowrap">Exam Date</th>
                    <th nowrap="nowrap">Exam Time</th> -->
                    <th nowrap="nowrap">Total <br />
                      Capacity </th>
                    <th nowrap="nowrap">Registered <br />
                      Count </th>
                    <th nowrap="nowrap">Balance <br />
                      Capacity </th>
                    <th nowrap="nowrap"> Occupied%</th>
                    <th nowrap="nowrap"> Delete</th>
                    <!-- <th nowrap="nowrap"> Update</th> -->
                  </tr>
                </thead>
               
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
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
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

 <!-- <script type="text/javascript">

    $(".remove").click(function(){

        var id = $(this).parents("tr").attr("id");

       swal({

        title: "DO you really want to deactivate this venue code?",

        text: " ",

        type: "warning",

        showCancelButton: true,

        confirmButtonClass: "btn-danger",

        confirmButtonText: "Yes, delete it!",

        cancelButtonText: "No, cancel pls!",

        closeOnConfirm: false,

        closeOnCancel: false

      },

      function(isConfirm) {

        if (isConfirm) {

          $.ajax({

             url: '/listitems/'+id,

             type: 'DELETE',

             success: function(data) {

                  $("#"+id).remove();

                  swal("Deleted!", "Venue code has been deactivated.", "success");

             }

          });

        } else {

          swal("Cancelled", "deactivation has been Cancelled:)", "error");

        }

      });

     

    });


</script> --> 
<script type="text/javascript">
    $(".remove").click(function(){
        var id = $(this).parents("tr").attr("id");
 
        if(confirm('Do you really want to delete this record ?'))
        {
            $.ajax({
               url: '/listitems/'+id,
               type: 'DELETE',
               success: function(data) {
                    $("#"+id).remove();
                    alert("Record removed successfully");  
               }
            });
        }
    });
</script>

<script type="text/javascript">

function delete_venue(venue,center_code)
{
	var base_url = window.location.origin;
	//var venue = $("#venue_code1").val();
	//var center_code = $("#center_code").val(); 
	//alert(venue);
	if(venue != '' && center_code!= '' )
	{
		var datastring='venue='+venue+'&center_code='+center_code;
		 $.ajax({
         type: "POST",
         url: base_url + "/admin/venue_master/CSCVenueDashboard/delete_record", 
        data: datastring,
		dataType: "html",  
         cache:false,
         success: 
              function(data){ 
			  if(data == 'true') 
			  {
				  alert('Are you sure to deactivate the venue code?');
			  }
			  else{
				  //alert('venue code is already deactivate.');
			  }
                //as a debugging message.
              }
          });
	}
}



            $('#search').parsley('validate');
            $('#addForm').parsley('validate');

            $(document).ready(function() {

                var base_url = window.location.origin;
                //get departments for Outward form
                $('#authority').on('change', function() {
                    var period = $(this).val();
                    //alert(base_url);
                    get_exam_list(period);
                });

                if($('#authority').val()!='')
                  get_exam_list($('#authority').val());

                function get_exam_list(period) {
                  if (period) {
                        $.ajax({
                            type: 'POST',
                            url: base_url + "/admin/venue_master/CSCVenueDashboard/get_multiple_exam",
                            //url: 'admin/ExamVenueCount/get_multiple_exam',
                            data: 'period=' + period,
                            success: function(html) {
                                if (period) {
                                    $('#other_dept_option_CC').hide();
                                    $('#dept_dd_optoin_CC').show();
                                    $('#department_CC').html(html);
                                } else {
                                    $('#dept_dd_optoin_CC').hide();
                                    $('#other_dept_option_CC').show();
                                    $('#other_dept_option_CC').html(html);
                                }
                            }
                        });
                    } else {
                        $('#department_CC').html('<option value="">Select Board First</option>');
                    }
                }
                $('#from_date').datepicker({
                    format: 'yyyy-mm-dd',
                    endDate: '+0d',
                    autoclose: true
                }).on('changeDate', function() {
                    $('#to_date').datepicker('setStartDate', new Date($(this).val()));
                });

                $('#to_date').datepicker({
                    format: 'yyyy-mm-dd',
                    endDate: '+0d',
                    autoclose: true
                }).on('changeDate', function() {
                    $('#from_date').datepicker('setEndDate', new Date($(this).val()));
                });

                $('.input-selector').on('keypress', function(e) {
                    return e.metaKey || // cmd/ctrl
                        e.which <= 0 || // arrow keys
                        e.which == 8 || // delete key
                        /[0-9]/.test(String.fromCharCode(e.which)); // numbers
                });

            });

            function download_file(no, format, optval, regno, regid, records) {
                var base_url = '<?php echo base_url(); ?>';
                var url = base_url + "admin/Downloads/download_file";
                /*var width = 100;
                var height = 100;	
                mywindow=window.open(url,"mywindow11","location=0,status=0,scrollbars=1,resizable=1,menubar=0,width="+width+",height="+height);*/

                //alert("no :"+no+", format : "+format+", dateInput : "+optval+", regno : "+regno+", regid : "+regid+", records : "+records);

                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: "json",
                    data: {
                        no: no,
                        format: format,
                        dateInput: optval,
                        regno: regno,
                        regid: regid,
                        records: records
                    },
                    success: function(res) {

                    }
                });
            }
            function removeVenueCode(elm){
              if (!confirm('Are you sure?')) return false; 
              console.log(elm)
              $.ajax({
                  type: 'POST',
                  url: "<?=base_url()?>/admin/venue_master/CSCVenueDashboard/delete_venue",
                  //url: 'admin/ExamVenueCount/get_multiple_exam',
                  data: 'center_code=' + $(elm).attr('center_code')+'&venue_code='+ $(elm).attr('venue_code'),
                  success: function(html) {
                      alert('Venue code is deactivated now');
                      $(elm).closest('tr').remove();
                  }
              });

              }
            $(function() {
               // $("#listitems").DataTable();
               
               if($('.selectedExamCode').length>0) {
                $('#listitems').DataTable({
                     /* "ajax": {
                          url :"<?php echo base_url(); ?>admin/venue_master/CSCVenueDashboard/getVenueListing?exm_cd="+$('.selectedExamCode').val()+'&exm_prd='+$('.selectedExamPeriod').val(),
                          type :'GET'
                      },*/
                      'processing': true,
                      'serverSide': true,
                      'serverMethod': 'get',
                      'ajax': {
                        'url':"<?=base_url()?>admin/venue_master/CSCVenueDashboard/getVenueListing?exm_cd="+$('.selectedExamCode').val()+'&exm_prd='+$('.selectedExamPeriod').val(),
                     //   dataSrc:""
                      },
                      'columns': [
                        { data: 'vendor_code' },
                        { data: 'center_code' },
                        { data: 'center_name' },
                        { data: 'venue_code' },
                        { data: 'venue_name' },
                        { data: 'session_capacity' },
                        { data: 'registered_count' },
                        { data: 'balance_capacity' },
                        { data: 'occupiedshow' },
                        { data: 'action' },
                      ]
                  });
              }
                var base_url = '<?php echo base_url(); ?>';
                var listing_url = base_url + 'admin/venue_master/CSCVenueDashboard';

                // Pagination function call
                //paginate(listing_url,'','','');
                //$("#base_url_val").val(listing_url);
            });

            function get_loader() {
                $(".loading").show();
            }
        </script>
</script>
<?php $this->load->view('admin/venue_master/includes/footer');?>

