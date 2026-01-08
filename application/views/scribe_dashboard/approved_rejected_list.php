<!DOCTYPE html>
<html>
    <head>
    <?php $this->load->view('scribe_dashboard/includes/header');?>
    <style type="text/css">
      .box-header{
        background: #eaeaea;
      }
      form#myForm,.box-header{
        background: #7fd1ea;
     }
    </style>
    </head>
    
    <body class="hold-transition skin-blue sidebar-mini">


    <div class="wrapper">
            <?php /*print_r($scribe_show);die;*/$this->load->view('scribe_dashboard/includes/topbar'); ?>
            <?php  $this->load->view('scribe_dashboard/includes/sidebar'); ?>
            
            <div class="content-wrapper" style="min-kash: 946px;">
                <section class="content">
                    <div id="custom_msg_outer"></div>
                    
                    <div class="hide d-none"></div>
                    <?php if($scribe_show[0]['scribe_approve'] == 1){ ?>
                      <h4 class="title_common">Scribe Approved List</h4>
                    <?php } elseif ($scribe_show[0]['scribe_approve'] == 3) { ?>
                      <h4 class="title_common">Scribe Rejected List</h4>
                    <?php } ?>

                    <!-- FlashMassages -->
                    <?php if($this->session->flashdata('error')!=''){?>
                    <div class="alert alert-danger alert-dismissible" id="error_id">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <?php echo $this->session->flashdata('error'); ?> </div>
                    <?php } if($this->session->flashdata('success')!=''){ ?>
                    <div class="alert alert-success alert-dismissible" id="success_id">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <?php echo $this->session->flashdata('success'); ?> </div>
                    <?php } 
                  if(validation_errors()!=''){?>
                    <div class="alert alert-danger alert-dismissible" id="error_id">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <?php echo validation_errors(); ?> </div>
                    <?php } ?>

    <!-- Search Bar POOJA MANE:09/08/2022-->
     <div class="searchfilter">
          <div class="box-header">
            <!-- <div class="box-header with-border"> -->
                <!-- tools box -->
                <div class="pull-right box-tools">
                    <button type="button" class="mb-2 float-right btn btn-primary" data-toggle="collapse" data-target="#collapseExample">
                     <i class="fa fa-filter"></i></button>
                </div>
                <!-- /. tools -->
               <!-- CHECK FOR APPROVED/REJECTED SCRIBE  -->
                <?php if($scribe_show[0]['scribe_approve'] == 1){ ?>
                      <h3 class="page-title">Approved list</h3>  <!-- FOR APPROVED LIST -->
                <?php } elseif ($scribe_show[0]['scribe_approve'] == 3) { ?>
                      <h3 class="page-title">Rejected list</h3> <!-- FOR REJECTED LIST -->
                <?php } ?>
            <!-- </div> -->

            

            <div class="collapsee" id="collapseExample">
  
              <?php if($scribe_show[0]['scribe_approve'] == 1)
              { ?> <!-- FORM APPROVED LIST -->
                <form class="form-control" name="searchScribeDetails" id=" " action="<?php echo base_url('scribe_dashboard/Scribe_list/approved_list'); ?>" method="post">

                  <div class="row">

                  <div class="col-md-3">
                    <div class="form-group">
                        <label>From Date</label>
                        <input type="text" class="form-control custom_filter" name="from_date" id="from_date" value="<?php if(set_value('from_date')) { echo set_value('from_date'); } else { echo $from_date; } ?>">
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-group">
                        <label>To Date</label>
                        <input type="text" class="form-control custom_filter" name="to_date" id="to_date" value="<?php if(set_value('to_date')) { echo set_value('to_date'); } else { echo $to_date; } ?>">
                    </div>
                  </div>

                  <div class="col-md-2">
                    <div class="form-group">
                        <label>Search Details</label>
                        <input type="submit" class="mb-2 float-right btn btn-primary" name="btnSearch1" id="btnSearch1" value="Search">
                    </div>
                  </div>

                  <div class="col-md-2">
                    <div class="form-group">
                        <label>Download Data</label>
                        <button type="submit" name="download1" id="download1" value="Download" class="mb-2 btn btn-warning" href=" ">Download CSV</button>
                    </div>
                  </div>

                </div>     
               </form>

              <?php } 
              elseif ($scribe_show[0]['scribe_approve'] == 3) 
                { ?><!-- FORM REJECTED LIST -->
              <form class="form-control" name="searchScribeDetails" id=" " action="<?php echo base_url('scribe_dashboard/Scribe_list/rejected_list'); ?>" method="post"> 

                <div class="row">

                  <div class="col-md-3">
                    <div class="form-group">
                        <label>From Date</label>
                        <input type="text" class="form-control custom_filter" name="from_date" id="from_date" value="<?php if(set_value('from_date')) { echo set_value('from_date'); } else { echo $from_date; } ?>">
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-group">
                        <label>To Date</label>
                        <input type="text" class="form-control custom_filter" name="to_date" id="to_date" value="<?php if(set_value('to_date')) { echo set_value('to_date'); } else { echo $to_date; } ?>">
                    </div>
                  </div>

                  <div class="col-md-2">
                    <div class="form-group">
                        <label>Search Details</label>
                        <input type="submit" class="mb-2 float-right btn btn-primary" name="btnSearch2" id="btnSearch2" value="Search">
                    </div>
                  </div>

                  <div class="col-md-2">
                    <div class="form-group">
                        <label>Download Data</label>
                        <button type="submit" name="download2" id="download2" value="Download" class="mb-2 btn btn-warning" href=" ">Download CSV</button>
                    </div>
                  </div>

                </div>     
               </form>

              <?php } ?> 

              </div>
            </div>
          </div>
    <!-- Search Bar End POOJA MANE:09/08/2022--->
                    
                    <div class="">
                        <div class="row">
                            <div class="col-lg-12">
                                
                                 <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
                                <input type="hidden" name="base_url_val" id="base_url_val" value="" />
                                <form id="myForm" name="myForm" method="post" action="" enctype="multipart/form-data" role="form">
                                    <input type="hidden" id="security_token" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                    
                                    
                                    <div class="table-responsive">
                                      
                                    <table id="listitems" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                          <th id="srNo">Sr.No.</th>
                                          <th id="id">Scribe URN</th>
                                          <th id="id">Member no</th>     
                                          <th id="id">Member Name</th>     
                                          <th id="title">Exam Name</th>
                                          <th id="title">Subject Name</th>
                                          <th id="title">Center Name</th>                     
                                          <th id="member_no">Scribe Name</th> 
                                          <th id="module_name">Exam Date</th>
                                          <!-- <th id="inst_head_name">Maker Name</th> -->
                                          <th id="status">Status</th>
                                          <th id="action">Resend Mail</th>
                                          
                                        </tr>
                                        </thead>
                                          
                                        <tbody class="no-bd-y" id="list2">
                                        <?php 
                                        $k = 1;
                                        if(count($scribe_show) > 0)
                                        {
                                            foreach($scribe_show as $res){
                                            echo '<tr><td>'.$k.' </td>';
                                            echo '<td>'.$res['scribe_uid'].' </td>';
                                            echo '<td>'.$res['regnumber'].' </td>';
                                            echo '<td>'.$res['firstname'].' </td>';
                                            echo '<td>'.$res['exam_name'].' </td>';
                                            echo '<td>'.$res['subject_name'].' </td>';
                                            echo '<td>'.$res['center_name'].' </td>';
                                            echo '<td>'.$res['name_of_scribe'].' </td>';
                                            echo '<td>'.$res['exam_date'].' </td>';                 
                                            //echo '<td>'.$res['transaction_no'].' </td>';
                                            
                                            if($res['scribe_approve'] == 1){
                                                $reuest_status = '<span class="reuest_status" style="color: green">APPROVED</span>' ;
                                            }elseif($res['scribe_approve'] == 2){
                                                $reuest_status = '<span class="reuest_status" style="color: red">PENDING</span>';   
                                            }elseif($res['scribe_approve'] == 3){
                                                $reuest_status = '<span class="reuest_status" >REJECTED</span>';    
                                            }elseif($res['scribe_approve'] == 0){
                                                $reuest_status = '<span class="reuest_status" style="color: blue">NEW</span>';  
                                            }else{
                                                $reuest_status  = '-';
                                            }
                                            echo '<td>'.$reuest_status.'</td>';
                                            if($scribe_show[0]['scribe_approve'] == 1){ 
                                          echo '<td><a id="btn1" class="btn btn-success btn-xs vbtn" href="'.base_url().'scribe_dashboard/Scribe_list/approve_mail/'.$res['id'].'">Mail <span>'.$res['email_flag'].'</span></a></td> ';}
                                          elseif ($scribe_show[0]['scribe_approve'] == 3){
                                          echo '<td><a id="btn2" class="btn btn-danger btn-xs vbtn" href="'.base_url().'scribe_dashboard/Scribe_list/reject_mail/'.$res['id'].'">Mail <span>'.$res['email_flag'].'</span></a></td>';}
                                          echo '</tr>';
                                            $k++;   
                                            }
                                        }
                                         ?>                 
                                        </tbody>
                                      
                                      </table>
                                      <div id="links" class="dataTables_paginate paging_simple_numbers">
                                     
                                      </div>
                                    </div>
                                                            
                                </form>
                            </div>
                        </div>
                    </div>
                
                </section>
            </div>
                                    
            <?php $this->load->view('scribe_dashboard/includes/footer');?>
            

<!-- Data Tables -->
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">

<!-- Data Tables -->
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>

<script src="<?php echo base_url()?>js/js-paginate.js"></script>


<!----- FOR DATEPICKER ----->
<script src="<?php echo base_url('assets/admin/plugins/datepicker/bootstrap-datepicker.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo base_url('assets/admin/plugins/datepicker/datepicker3.css'); ?>">

<!-- FONT-AWESOME -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<script>
$(function () {
    $('#listitems').DataTable({
      "bStateSave": true,
    });
    $("#listitems_filter").show();
    $('#ToolTables_listitems_4').hide();
});
</script>
            
            <script>$( document ).ready( function () { $('#page_loader').delay(0).fadeOut('slow'); });</script>
        </div>
    </body>
</html>
<script>
    $('.chosen-select').chosen({width: "100%"});
    
</script>
<script>    
    $(document).ready(function() 
    {       
        $("#from_date").attr('autocomplete', 'off');
        $("#to_date").attr('autocomplete', 'off');
                
        $('#from_date').datepicker({ format: 'yyyy-mm-dd', endDate: '+0d', autoclose: true, forceParse: true }).on('changeDate', function()
        {
            $('#to_date').datepicker('setStartDate', new Date($(this).val()));
        }); 
        
        $('#to_date').datepicker({ format: 'yyyy-mm-dd', endDate: '+0d', autoclose: true, forceParse: true }).on('changeDate', function()
        {
            $('#from_date').datepicker('setEndDate', new Date($(this).val()));
        });
    });
    </script>

<?php if(isset($from_date) && $from_date != "") {   ?> 
    <script>$('#to_date').datepicker({ format: 'yyyy-mm-dd', startDate:'<?php echo $from_date; ?>', endDate: '+0d', autoclose: true });</script>    
<?php }

if(isset($to_date) && $to_date != "") { ?> 
    <script>$('#from_date').datepicker({ format: 'yyyy-mm-dd', endDate: '<?php echo $to_date; ?>', autoclose: true });</script> 
<?php } ?>

<script src="<?php echo base_url('assets/js/jquery.validate.js'); ?>"></script><!----- FOR JQUERY VALIDATION ----->
<script>  
    $(document).ajaxStart(function() { $("#page_loader").css("display", "block"); });
    $(document).ajaxComplete(function() { $("#page_loader").css("display", "none"); });
    
    $(".allowd_only_numbers").keydown(function (e) 
    {
        // Allow: backspace, delete, tab, escape, enter
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
        // Allow: Ctrl+A
        (e.keyCode == 65 && e.ctrlKey === true) || 
        // Allow: home, end, left, right
        (e.keyCode >= 35 && e.keyCode <= 39)) 
        {
            // let it happen, don't do anything
            return;
        }
        
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
</script>   
<!-- UPDATE COUNT OF SENT MAIL -->  
<script type="text/javascript">
  var count1 = 0;
      var count2 = 0;
  var btn1 = document.getElementById("btn1");
  var disp1 = document.getElementById("display1");
      var btn2 = document.getElementById("btn2");
  var disp2 = document.getElementById("display2");

  btn1.onclick = function () {
    count1++;
    disp1.innerHTML = count1;
  }
      

  btn2.onclick = function () {
    count2++;
    disp2.innerHTML = count2;
  }
</script>


<style>
 .box
 {
  width:100%;
  max-width: 650px;
  margin:0 auto;
 }
 .typeahead, .tt-query, .tt-hint {
  width: 340px;
  height: 30px;
  padding: 8px 12px;
  font-size: 15px;
  line-height: 30px;
  outline: none;
}
.box {
  position: relative;
  border-radius: 3px;
  background: #ffffff;
  border: 1px solid #00c0ef;
  margin-bottom: 15px;
  width: 100%;
  
}
#listitems_length{
  width:10%;
}

 </style>