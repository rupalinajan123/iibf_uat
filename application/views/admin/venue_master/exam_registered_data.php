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
    <h1> Exam Venue Registered data </h1>
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
    <form class="form-horizontal" name="addForm" id="addForm" action="" method="post">
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
          <div class="col-md-4" >
            <div class="control-group" id="div_authority_CC">
                    <label for="textfield" class="control-label"><strong>Registered On</strong></label>
                        <!-- Multiple Board Dropdown-->
                        <div class="controls" id="board_dd_CC">
                          <input class="input-large form-control register_date" autocomplete="off"  name="register_date" value="<?php if(isset($_POST['register_date'])) echo $_POST['register_date']; ?>" id="register_date" placeholder="Registered on"  >
                          
                        </div>
            </div>
          </div>
          <div class="col-md-4" >
            <div class="control-group" id="div_authority_CC">
                    <label for="textfield" class="control-label"><strong>Exam date</strong></label>
                        <!-- Multiple Board Dropdown-->
                        <div class="controls" id="board_dd_CC">
                          <input class="input-large form-control exam_date" autocomplete="off"  name="exam_date" value="<?php if(isset($_POST['exam_date'])) echo $_POST['exam_date']; ?>" id="exam_date" placeholder="Exam date"  >
                          
                        </div>
            </div>
          </div>
            <div class="control-group col-md-4" id="div_authority_CC">
                    <label for="textfield" class="control-label"><strong>Member Id(s)</strong></label>
                        <!-- Multiple Board Dropdown-->
                        <div class="controls" id="board_dd_CC">
                          <input class="input-large form-control member_ids" name="member_ids" value="<?php if(isset($_POST['member_ids'])) echo $_POST['member_ids']; ?>" id="member_ids" placeholder="(comma separated)" >
                          
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
          <input type="submit" class="btn btn-info pull-left" name="btnSubmit" id="btnSubmit" value="Show Data">
          &nbsp;
              <a href="" class="btn btn-default">Refresh</a> 
            </div>
            <?php
            if(isset($_POST) && !empty($_POST)) { ?>
            <div class="col-sm-4">
              <a href="<?php echo base_url();?>admin/venue_master/CSCVenueDashboard/download_CSV_of_examdata/?member_ids=<?php echo $_POST['member_ids']; ?>&exam_date=<?php echo $_POST['exam_date']; ?>&register_date=<?php echo $_POST['register_date']; ?>"> 
              <button type="button" class="btn btn-warning ">Downaload CSV</button>
              
              </a> &nbsp;
              <a href="javascript:void(0);" link="<?php echo base_url();?>admin/venue_master/Send_data_to_csc/?member_ids=<?php echo $_POST['member_ids']; ?>&exam_date=<?php echo $_POST['exam_date']; ?>&register_date=<?php echo $_POST['register_date']; ?>"> 
              <button type="button" class="btn btn-success sendDataToCsc ">Send Data To CSC</button>
              
              </a>
            </div>
            <?php } ?>
            <div style="text-align: center;
    padding: 2%;
    color: green;" class="col-md-12 showapiresponse"></div>
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
            <h3 class="box-title">Result  </h3>
          </div>
          <!-- /.box-header 
            S.No. 	Exam Code 	Exam Name 	Member Count
            -->
          <div class="box-body">
           
            <div class="table-responsive">
              <table id="listitems" class="table table-bordered table-striped dataTables-example">
                <thead>
                  <tr>
                    <th nowrap="nowrap">Id</th>
                    <th nowrap="nowrap">Candidate name</th>
                    <th nowrap="nowrap">Registered on</th>
                    <th nowrap="nowrap">Email Id</th>
                    <th nowrap="nowrap">Phone No</th>
                    <th class="nosort" nowrap="nowrap">Exam</th>
                    <th class="nosort" nowrap="nowrap">Exam Date</th>
                    
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



<script type="text/javascript">
  


            $('#search').parsley('validate');
            $('#addForm').parsley('validate');

            $(document).ready(function() {

                var base_url = window.location.origin;
               

                $('#exam_date').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true
                });

                $('#register_date').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true
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
            
            $('.sendDataToCsc').on('click', function(e) {
              if($('.exam_date').val()=='' && $('.member_ids').val()=='' && $('.register_date').val()=='')
              {
                alert('Please enter either register date, exam date or member Id');
                return false;
              }
              $('.sendDataToCsc').text('Processing...');
              $.ajax({
                  type: 'POST',
                  url: "<?php echo base_url();?>admin/venue_master/Send_data_to_csc/?member_ids="+$('.member_ids').val()+"&exam_date="+$('.exam_date').val()+"&register_date="+$('.register_date').val(),//$(this).parent('a').attr('link'),
                  //url: 'admin/ExamVenueCount/get_multiple_exam',
                 // data: 'center_code=' + $(elm).attr('center_code')+'&venue_code='+ $(elm).attr('venue_code'),
                  success: function(html) {
                      //alert('Data sent successfully. Got Response : '+html);
                      $('.sendDataToCsc').text('Send Data To CSC');
                      $('.showapiresponse').html(html);
                  }
              });
            });
            //
            $('#btnSubmit').on('click', function(e) {
              e.preventDefault();
              if($('.exam_date').val()=='' && $('.member_ids').val()=='' && $('.register_date').val()=='')
              {
                alert('Please enter either register date, exam date or member Id');
                return false;
              }
              else
                $('#addForm').submit();
            });
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
               <?php if(isset($_POST) && !empty($_POST)) { ?>
              
                $('#listitems').DataTable({
                   
                      'processing': true,
                      'serverSide': true,
                      'serverMethod': 'get',
                      'ajax': {
                        'url':"<?=base_url()?>admin/venue_master/CSCVenueDashboard/showExamRegisteredData?exam_date="+$('.exam_date').val()+'&member_ids='+$('.member_ids').val()+'&register_date='+$('.register_date').val(),
                     //   dataSrc:""
                      },
                     
                      'columns': [
                        { data: 'member_number' },
                        { data: 'name' },
                        { data: 'registration_date' },
                        { data: 'email_id' },
                        { data: 'mobile' },
                        { data: 'course' },
                        { data: 'exam_date' },
                      ],
                      'aoColumnDefs': [{
                          'bSortable': false,
                          'aTargets': ['nosort']
                      }]
                  });
                  <?php } ?>
                var base_url = '<?php echo base_url(); ?>';
              //  var listing_url = base_url + 'admin/venue_master/CSCVenueDashboard';

            });

            function get_loader() {
                $(".loading").show();
            }
        </script>
</script>
<?php $this->load->view('admin/venue_master/includes/footer');?>

