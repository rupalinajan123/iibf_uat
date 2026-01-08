<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1> <?php echo $exam_name; ?> (Exam Code :
         <?php if(isset($exam_code)) { 
            ####Code added by pooja ######
            
              $get_exam_code=$this->master_model->getRecords('multiple_exam_period', array(
            
                         'exam_code' => $exam_code ,
            
                         'exam_period' =>$exam_period
            
                     ) , 'actul_exam_code,exam_period');
            
            
            
            if(count($get_exam_code) > 0)
            
            {
            
              $exam_code = $get_exam_code[0]['actul_exam_code'];
            
              $exam_period_new= $get_exam_code[0]['exam_period'];
            
            }
            
             else
            
            {
            
              $exam_code = $exam_code;
            
              $exam_period_new= $exam_period;
            
            }
            
            
            
            echo $exam_code; } ?>
         <?php if($this->session->userdata('is_elearning_course')=='n' && isset($exam_period_new)) { echo " | Exam Period :".$exam_period_new; }?>
         )
         <?php  echo '  Exam application entry and payment '; ?>
      </h1>
   </section>
   <!-- Main content -->
   <form name="draexampay" class="draexampay" method="post" action="<?php echo base_url();?>bulk/Bulk_exam_payment/make_payment<?php //echo base64_encode($examcode);?>">
      <section class="content">
         <div class="row">
            <div class="col-xs-12">
               <div class="box">
                  <div class="box-header">
                     <h3 class="box-title">
                        <?php // echo ucwords($desc);?>
                        Application List
                     </h3>
                     <div class="pull-right">
                        <?php
                           $mou_login = $this->master_model->getRecords('bulk_exam_activation_master', array('exam_code' => $exam_code,'exam_period' => $exam_period,'mou_flg'=>'1'));
                           
                           $show_payment_button = true;
                           $show_apply_btn = true;
                           $show_delete_btn = false;

                           if ($this->session->userdata('is_elearning_course')=='y' && $this->session->userdata('is_admin')== 'yes' && $this->session->userdata('is_admin')== 'yes' && count($member_list) < 35 ) {
                              $show_payment_button = false;
                              // echo '1';
                           }
                             
                           if ($this->session->userdata('is_elearning_course')=='y' && $this->session->userdata('is_admin')== 'no' ) 
                           { 
                                 // echo '2';
                              $show_payment_button = false; 
                              $show_apply_btn = true;  
                              $show_delete_btn = false;
                           }
                           elseif ($this->session->userdata('is_elearning_course')=='y' && $this->session->userdata('is_admin')== 'yes') 
                           {  
                              // echo '3';
                                 $show_payment_button = true; 
                                 $show_apply_btn = false;  
                                 $show_delete_btn = true;
                           }
                           //Added by pooja mane : 30-11-2023 to show payment button to admin only for non e-learning
                           elseif ($this->session->userdata('is_elearning_course')=='n' && $this->session->userdata('is_admin')== 'yes') 
                           {     
                                 // echo '4';
                                 $show_payment_button = true; 
                                 $show_apply_btn = true;  
                                 $show_delete_btn = true;

                           }elseif ($exam_code == '1015' ) {//To display delete button for 1015 (pooja mane 2024-01-11)
                              $show_payment_button = false;
                              $show_delete_btn = true; 
                           }elseif ($exam_code == '420' ) {//To display delete button for 1015 (pooja mane 2024-01-11)
                              $show_payment_button = true;
                              // $show_delete_btn = true; 
                           }elseif ($exam_code == '994' || $exam_code == '996' || $exam_code == '1056' || $exam_code == '1055' || $exam_code == '1046') 
                           {
                                 $show_payment_button = true; 
                                 $show_apply_btn = true;  
                                 $show_delete_btn = true;
                           }
                           elseif(isset($mou_login) && count($mou_login)>0){
                              // $show_payment_button = true;
                           }
                           else
                           {
                               $show_payment_button = false;
                               $show_delete_btn = false; 
                           }
                           //Show payment button to admin only code end (POOJA MANE)
                           
                           
                          
                            if($is_exam_activated == 1){ ?>
                        <!--<a href="<?php  echo base_url();?>bulk/BulkApplyExcel/excel/<?php echo base64_encode($exam_code)?>" class="btn btn-warning"> Download Excelsheet</a>--> 
                        <!--<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#myModal">test Download Excelsheet</button>--> 
                        <!--<a href="<?php  echo base_url();?>bulk/BulkApplyExcel/read_xlsx" class="btn btn-warning">Upload Excelsheet</a>-->
                        <?php if($exam_code != $this->config->item('examCodeDBF') && $exam_code != 1017 && $exam_code != 1018 && $show_apply_btn){ 
                           if($exam_code != 994 && $exam_code != 996 && $exam_code != 1055 && $exam_code != 1056 && $exam_code != 1046 && $exam_code != 530)
                           { ?>
                        <a href="<?php  echo base_url();?>bulk/BulkApply/add_member" class="btn btn-warning">Apply for Member</a><?php } ?> <a href="<?php  echo base_url();?>bulk/BulkApplyNM/add_member" class="btn btn-warning">Apply for Non Member</a>
                        <?php }elseif($exam_code == $this->config->item('examCodeDBF')){?>
                        <a href="<?php  echo base_url();?>bulk/BulkApplyDB/add_member" class="btn btn-warning">Apply for DB Member</a>
                        <?php }elseif($exam_code == 1017 || $exam_code == 1018){?>
                        <a href="<?php  echo base_url();?>bulk/BulkApplyNM/add_member" class="btn btn-warning">Apply for Non Member</a>
                        <?php }?>
                        <?php } ?>
                        <!--<a href="<?php  echo base_url();?>bulk/Bulk_exam_payment/make_payment" class="btn  btn-primary mk-payment">Make Payment</a>-->
                        <!-- && $exam_code != 1027 -->
                        <!-- && $exam_code != 1030 && $exam_code != 1030-->
                        <?php 
                        // && $exam_code != 1026
                        //echo $exam_code;die;
                        //
                           if( $exam_code != 1015 && $exam_code != 1028 && $show_payment_button){?>
                        <input type="submit" class="btn  btn-primary mk-payment"  value="View Payment Summary">
                        <?php }?>
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
                     <?php
                        if(isset($errmsg)){ ?>
                     <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo $errmsg; ?> 
                     </div>
                     <?php  } 
                        if(validation_errors()!=''){?>
                     <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo validation_errors(); ?> 
                     </div>
                     <?php  }
                        if(isset($succmsg)) {?>
                     <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo $succmsg; ?> 
                     </div>
                     <?php }  ?>
                     <table id="listitems2" class="table table-bordered table-striped dataTables-example">
                        <thead>
                           <tr>
                              <th class='no-sort text-center' style="padding-right:8px;"><input type="checkbox" id="selectall"/></th>
                              <th id="srNo" class='text-center'>No.</th>
                              <th id="memberno" class='text-center'>Membership No.</th>
                              <th id="emp_id" class='text-center'>Employee Id</th>
                              <th id="firstname" class='text-center'>Candidate Name</th>
                              <th  class='text-center'>Gender</th>
                              <th id="memberType" class='text-center'>Member Type</th>
                              <!--<th id="dateofbirth">Exam Period</th>-->
                              <th id="exam_center_code" class='text-center'>Center Code</th>
                              <th id="exam_center_code" class='text-center'>Center Name</th>
                              <?php if($exam_code == 994 || $exam_code == 1056){ ?>
                              <th id="exam_center_code" class='text-center'>State</th>
                              <?php } ?>  
                              <th id="exam_fee" class='text-center'>Fee</th>
                              <th id="" class='text-center'>NEFT/UTR No.</th>
                              <th id="pay_status" class='text-center'>Payment Status</th>
                              <?php if( $show_delete_btn ){ ?>
                              <th id="action" class='no-sort text-center'>Actions</th>
                              <?php } ?>
                           </tr>
                        </thead>
                        <tbody class="no-bd-y" id="list">
                           <?php
                              if(count($member_list) > 0){
                              
                              $i=0;
                              
                              foreach($member_list as $row){ 
                              
                              $i++;
                              
                              //get member basic details 
                              
                              $member_info = array();
                              
                              //to find where the memeber is existing non member of new fresh member 
                              
                              if($exam_code != '1017' && $exam_code != '1018')
                              
                              {
                              
                              $member_info = $this->master_model->getRecords('member_registration',array('regnumber'=>$row['regnumber'],'isactive'=>'1'));
                              
                              if(empty($member_info))
                              
                              {
                              
                              $member_info = $this->master_model->getRecords('member_registration',array('regid'=>$row['regnumber'],'isactive'=>'0'));
                              
                              }
                              
                              
                              
                              }
                              
                              elseif($exam_code == '1017' || $exam_code == '1018')
                              
                              {
                              
                              $member_info = $this->master_model->getRecords('dra_members',array('regnumber'=>$row['regnumber'],'isactive'=>'1'));
                              
                              if(empty($member_info))
                              
                              {
                              
                              $member_info = $this->master_model->getRecords('dra_members',array('regid'=>$row['regnumber'],'isactive'=>'0'));
                              
                              }
                              
                              }
                              
                              else
                              
                              {
                              
                              $member_info = $this->master_model->getRecords('member_registration',array('regnumber'=>$row['regnumber'],'isactive'=>'1'));
                              
                              if(empty($member_info))
                              
                              {
                              
                              $member_info = $this->master_model->getRecords('member_registration',array('regid'=>$row['regnumber'],'isactive'=>'0'));
                              
                              }
                              
                              }
                              
                              
                              
                              if(empty($member_info))
                              
                              {
                              
                              $member_info = $this->master_model->getRecords('member_registration',array('regnumber'=>$row['regnumber'],'isactive'=>'1'));
                              
                              if(empty($member_info))
                              
                              {
                              
                              $member_info = $this->master_model->getRecords('member_registration',array('regid'=>$row['regnumber'],'isactive'=>'0'));
                              
                              }
                              
                              }
                              
                              
                              
                              
                              
                              
                              
                              if(count($member_info))
                              
                              {
                              
                              foreach($member_info as $val){
                              
                              ?>
                           <tr>
                              <td align="center"><?php if($row['pay_status'] == 2 ) { ?>
                                 <input type="checkbox" class="chkmakepay" name="chkmakepay[]" value="<?php echo $row['id'];?>"/>
                                 <?php }?>
                              </td>
                              <td><?php echo $i;?></td>
                              <td><?php if($val['isactive'] == 1) { echo $val['regnumber']; } ?></td>
                              <td>
                                 <?php 
                                    if($exam_code != '1017' && $exam_code != '1018'){
                                    
                                        echo $val['bank_emp_id'];
                                    
                                      }else{
                                    
                                      echo '-';
                                    
                                      }
                                    
                                    ?>
                              </td>
                              <td><?php echo $val['firstname'].' '.$val['middlename'].' '.$val['lastname'];?></td>
                              <td align="center"><?php echo ucfirst($val['gender']) ; ?></td>
                              <td align="center"><?php echo $val['registrationtype']; ?></td>
                              <!--<td><?php //echo $row['exam_period'];?></td>-->
                              <td align="center"><?php echo $row['exam_center_code'];?></td>
                              <td align="center"><?php echo $row['center_name'];?></td>
                              <?php if($exam_code == 994 || $exam_code == 1056){ ?>
                              <td align="center"><?php echo $row['state_description'];?></td>
                              <?php } ?> 
                              <td align="center"><?php 
                              if($this->session->userdata('institute_id') == "17171"){
                                 echo $row['base_fee'];
                              }else{
                                 echo $row['base_fee']." + GST";
                              }
                              

                              ?></td>
                              <td>
                                 <?php 
                                    $this->db->select('UTR_no');
                                    
                                    $this->db->from('bulk_member_payment_transaction');
                                    
                                    $this->db->order_by('bulk_member_payment_transaction.id',"desc");
                                    
                                    $this->db->join('bulk_payment_transaction', 'bulk_member_payment_transaction.ptid = bulk_payment_transaction.id'); 
                                    
                                    $this->db->where('memexamid',$row['id']);
                                    
                                    
                                    
                                    $query=$this->db->get();
                                    
                                    $utr_no =$query->result_array();
                                    
                                      if(!empty($utr_no)  ){
                                    
                                      echo $utr_no[0]['UTR_no'];
                                    
                                    }else{
                                    
                                      echo '-'; 
                                    
                                    }
                                    
                                    ?>
                              </td>
                              <td>
                                 <?php 
                                    if( $row['pay_status'] == 0 ){
                                    
                                      echo 'Fail'; 
                                    
                                    }else if( $row['pay_status'] == 2 ){ 
                                    
                                      echo 'Pending'; 
                                    
                                    }else if($row['pay_status'] == 3){ 
                                    
                                      echo 'Released for payment-invoice generated'; 
                                    
                                    }else if( $row['pay_status'] == 1 ){
                                    
                                      echo 'Payment Done'; 
                                    
                                    } 
                                    
                                    ?>
                              </td>
                              <?php if( $show_delete_btn ){ ?>
                              <td>
                                 <?php 
                                    if( $row['pay_status'] == 0 || $row['pay_status'] == 2 ) { ?>
                                 <a href="<?php echo base_url().'bulk/BulkApply/delete/'.$row['id'];?>" onclick="return confirm('Are you sure to delete this record?');">Delete </a>
                                 <?php }?>
                              </td>
                           <?php } ?>
                           </tr>
                           <?php }}}} ?>
                        </tbody>
                     </table>
                     <div style="width:30%; float:left;">
                        <?php /*Removed pagination on 21-01-2017*/ 
                           //// echo $info; ?>
                     </div>
                     <div id="links" class="" style="float:right;">
                        <?php // echo $links; ?>
                     </div>
                  </div>
                  <!-- /.box-body --> 
               </div>
               <!-- /.box --> 
            </div>
            <!-- /.col --> 
         </div>
      </section>
   </form>
   <div class="modal fade" id="myModal"  role="dialog">
      <div class="modal-dialog" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
               <form class="form-horizontal"  method="post"  enctype="multipart/form-data" action="<?php echo base_url();?>bulk/BulkApplyExcel/excel_test">
                  <div class="form-group">
                     <label class="col-sm-3 control-label">No of Inputs</label>
                     <div class="col-sm-5">
                        <input type="text" class="form-control"  name="no_of_inputs" id="no_of_inputs"  required>
                     </div>
                     <!--<input type="submit" name="submit" value="Submit" />--> 
                  </div>
            </div>
            <div class="modal-footer"> 
            <!--  <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="preview();">Confirm</button>-->
            <input type="submit" name="btnSubmit" class="btn btn-primary" id="btnSubmit" value="Submit">
            </div>
            </form>
         </div>
         <!-- /.modal-content --> 
      </div>
      <!-- /.modal-dialog --> 
   </div>
</div>
<!-- Data Tables -->
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
<script>
   /* $(function () 
   
   {
   
      var table = $('#listitems2').DataTable();
   
      $("#listitems2 tfoot th").each( function ( i ) 
   
      {
   
           var select = $('<select  class="moption pp'+i+'" ><option value="">All</option></select>')
   
               .appendTo( $(this).empty() )
   
               .on( 'change', function () {
   
                   table.column( i )
   
                       .search( $(this).val() )
   
                       .draw();
   
               } );
   
    
   
           table.column( i ).data().unique().sort().each( function ( d, j ) {
   
               select.append( '<option value="'+d+'">'+d+'</option>' )
   
           }); 
   
       });
   
   
   
      
   
   }); */
   
   
   
   $(document).ready(function()
   
        {
   
          $('#listitems2').DataTable(
   
          {
   
            pageLength: 10,
   
            "lengthMenu": [ 10, 25, 50, 100, 200, 500, 1000, 1500 ],
   
            responsive: true,
   
            "columnDefs": 
   
            [
   
              {"targets": 'no-sort', "orderable": false, }
   
            ],
   
            "aaSorting": [],
   
            "stateSave": false,             
   
          });
   
        });
   
</script> 
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