<?php $this->load->view('creditnote/admin/includes/header');?>
<?php $this->load->view('creditnote/admin/includes/sidebar');?>

<div class="content-wrapper">
  <section class="content-header">
    <h1> Request Details </h1>
       
  </section>
 
    <section class="content">
      <div class="row myview">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Preview</h3>
              <div class="pull-right"> <a href="<?php echo base_url();?>creditnote/admin/Maker/refundrequest_list" class="btn btn-warning">Back</a>
                
               </div>
            </div>

            <div class="box-body" style="padding-left: 10px">
             
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
              <?php }

           
             $creditnoteuserdata = $this->session->userdata('creditnote_admin');
             if (isset($reuest_list)) {
              # code...
              //print_r($reuest_list); die;
              foreach ($reuest_list as $res) { ?>
               
                 <div class="table-responsive ">
                  <table class="table table-bordered" style="">
                    <tbody>
                    <tr>                    
                      <td width="50%"><strong>REQUEST ID:</strong></td>
                      <td width="50%"><?php echo $res['req_id']; ?></td>
                    </tr> 
                     <tr>                    
                      <td width="50%"><strong>REQUEST TITLE:</strong></td>
                      <td width="50%"><?php echo $res['req_title']; ?></td>
                    </tr>
                     <tr>                    
                      <td width="50%"><strong>REQUEST DESCRIPTION:</strong></td>
                      <td width="50%"><?php echo $res['req_desc']; ?></td>
                    </tr>
                     <tr>                    
                      <td width="50%"><strong>MEMBER NUMBER:</strong></td>
                      <td width="50%"><?php echo $res['req_member_no']; ?></td>
                    </tr>
                     <tr>                    
                      <td width="50%"><strong>MODULE NAME:</strong></td>
                      <td width="50%"><?php echo $res['module_name']; ?></td>
                    </tr>
                     <tr>                    
                      <td width="50%"><strong>TRANSACTION NO.:</strong></td>
                      <td width="50%"><?php echo $res['transaction_no']; ?></td>
                    </tr>
                      <tr>                    
                      <td width="50%"><strong>REQUEST EXCEPTIONAL CASE:</strong></td>
                      <td width="50%"><?php echo $res['req_exceptional_case']; ?></td>
                    </tr>
                    <?php if( $res['req_exceptional_case'] == 'YES'){ ?>
                    <tr>                    
                      <td width="50%"><strong>REQUEST EXCEPTIONAL CASE REASON:</strong></td>
                      <td width="50%"><?php echo $res['req_reason']; ?></td>
                    </tr>
                    <?php } ?>
                     <tr>                    
                      <td width="50%"><strong>STATUS:</strong></td>
                      <td width="50%">
                        <?php
                        if($res['req_status'] == 1){
                          $reuest_status = '<span class="reuest_status" style="color: green">APPROVED</span>' ;
                          }elseif($res['req_status'] == 2){
                          $reuest_status = '<span class="reuest_status" style="color: red">REJECTED</span>'; 
                          }elseif($res['req_status'] == 3){
                          $reuest_status = '<span class="reuest_status" >DROP</span>'; 
                          }elseif($res['req_status'] == 0){
                          $reuest_status = '<span class="reuest_status" style="color: blue">NEW</span>'; 
                          }elseif($res['req_status'] == 6){
                          $reuest_status = '<span class="reuest_status" style="color: #da8be8">RESUBMITED</span>'; 
                          }elseif($res['req_status'] == 4){
                          $reuest_status = '<span class="reuest_status" style="color: #ff8d00">CANCELLED</span>'; 
                          }elseif($res['req_status'] == 5){
                          $reuest_status = '<span class="reuest_status" style="color: #089ac5">REFUND</span>'; 
                              }else{
                          $reuest_status = '-';
                          }
                          echo $reuest_status; ?>
                      </td>
                    </tr>
                    
                    <tr>                    
                      <td width="50%"><strong>CREDITNOTE :</strong></td>
                      <td width="50%"><?php if(!empty($res['credit_note_image'])){
                        $creditnote_no = explode('.',  $res['credit_note_image']);
                        echo str_replace('_','/',$creditnote_no[0]); ?>
                        <a href="<?php echo base_url();?>uploads/CreditNote/<?=$res['credit_note_image']?>" target="_blank" style="color: #2ad47e;">&nbsp; View Image</a>
                      <?php  } else{
                        echo '-';
                      }
                      ?>
                        </td>
                    </tr>
                   
                  </tbody>
                  </table>
                 <div id="reason_form" style="display: none">
                  <form action="<?php echo base_url();?>creditnote/admin/Maker/action_status" method="post"  class=""  enctype="multipart/form-data" data-parsley-validate="parsley">
                    <input type="hidden" name="action" id="action_id" value="3">
                    <input type="hidden" name="id" value="<?=$res['id']?>">
                    <input type="hidden" name="checker_id" value="<?=$res['checker_id']?>">
                    <input type="hidden" name="maker_id" value="<?=$res['req_maker_id']?>">
                    <input type="hidden" name="req_id" value="<?=$res['req_id']?>">
                    <table  class="table table-bordered" style="">
                      <tbody>
                        <tr>                    
                            <td width="50%"><strong>DROP REASON <span style="color: red">*</span></strong>:</strong><textarea maxlength="300" required="" class="form-control" name="description"></textarea></td>
                            <td width="50%"><br><input type="submit" class="btn btn-success" name="btnSubmit" value="Submit"></td>
                         </tr>

                      </tbody>
                    </table>
                   </form>
                  </div>    
                
               <div align="center"> 
                 <!--  <a href="<?php //echo base_url();?>creditnote/admin/maker/refundrequest_list" class="btn btn-primary">Approve</a> -->
                 <?php

                  if($creditnoteuserdata['id'] == $res['req_maker_id'] ){
 
                  if($res['req_status'] == 0 || $res['req_status'] == 2 || $res['req_status'] == 6){ ?>
                 <a href="<?php echo base_url();?>creditnote/refundrequest/refundRequest/edit/<?=base64_encode($res['id'])?>" class="btn btn-primary" >Edit</a>
                  <a  class="btn btn-warning action" id="3">Drop</a>
                  <?php }
                   } ?>
               </div>
                  </div>     
    
            
            </div>
          </div>
        </div>
      </div>
    
     
</section>
<?php  
                }
              } 
            ?>

  
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box"> 
            <div class="box-header with-border">
              <h3 class="box-title">Action Taken On Request </h3>          
            </div>
         
            <div class="box-body">
            
            <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
          <input type="hidden" name="base_url_val" id="base_url_val" value="" />
            <div class="table-responsive">
      <table id="listitems2" class="table table-bordered table-striped dataTables-example">
                <thead>
                <tr>
                  <th id="srNo">Sr.No.</th>
                  <th id="id">Request Id</th>     
                  <th id="title">Cheker Name</th>                     
                  <th id="member_no">Maker Name</th> 
                  <th id="module_mame">Staus</th>
                  <th id="transaction_no">Date</th>
                  <!-- <th id="inst_head_name">Maker Name</th> -->
                 
                  <th id="action">Description</th>
                </tr>
                </thead>
                  
                <tbody class="no-bd-y" id="list2">
                <?php 
        $k = 1;
        if(count($reuest_action_list) > 0){
          foreach($reuest_action_list as $res){
          echo '<tr><td>'.$k.' </td>';
          echo '<td>'.$res['req_id'].' </td>';
          echo '<td>'.$res['checker_name'].' </td>';
          echo '<td>'.$res['maker_name'].' </td>';
         
          if($res['action_status'] == 1){
          $reuest_status = '<span class="reuest_status" style="color: green">APPROVED</span>' ;
          }elseif($res['action_status'] == 2){
          $reuest_status = '<span class="reuest_status" style="color: red">REJECTED</span>';  
          }elseif($res['action_status'] == 3){
          $reuest_status = '<span class="reuest_status" >DROP</span>';  
          }elseif($res['action_status'] == 0){
          $reuest_status = '<span class="reuest_status" style="color: blue">NEW</span>';  
          }elseif($res['action_status'] == 6){
          $reuest_status = '<span class="reuest_status" style="color: #da8be8">RESUBMITED</span>'; 
          }elseif($res['action_status'] == 4){
          $reuest_status = '<span class="reuest_status" style="color: #ff8d00">CANCELLED</span>'; 
          }elseif($res['action_status'] == 5){
          $reuest_status = '<span class="reuest_status" style="color: #089ac5">REFUND</span>'; 
          }
          echo '<td>'.$reuest_status.' </td>';
          // if(!empty($res['created_on'])){
          //   $date = explode(' ', $res['created_on']);
          //   $date =$date['0'];
          // }else{
          //   $date = '-';
          // }
          echo '<td>'.$res['created_on'].' </td>';
          echo '<td>'.$res['description'].' </td>';
          // echo '|<a class="btn btn-primary btn-xs vbtn" href="'.base_url().'creditnote/admin/maker/request_approve'.$res['id'].'">Approve</a>';
          
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
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>

    </section>   
</div>
<script type="text/javascript">
 $(document).ready(function() {
    
    $("body").on("contextmenu",function(e){
       return false;
   });


// on click of button take action 
  $(".action").click(function(){
    $name = this.id;
    
    $('#action_id').val($name);
    //$('.reason_class').html($text);
    //alert($('#action_id').val())
    $("#reason_form").show();
  });


 });
</script>
<!-- Data Tables -->
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">
<style>
.active_batch{
color:#00a65a;  
font-weight:600;
}

.deactive_batch{
color:#930; 
font-weight:600;
}
.input_search_data{
 width:100%;  
}
tfoot {
    display: table-header-group;
}
.vbtn{
padding: 3px 4px;
font-weight: 600;
}
</style>

<!-- Data Tables -->
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>

<script src="<?php echo base_url()?>js/js-paginate.js"></script>
<script>
$(function () {
  $('#listitems2').DataTable(
  {
	 pageLength: 1000,
  });
  $("#listitems_filter").show();
});


</script>

<?php $this->load->view('creditnote/admin/includes/footer');?>