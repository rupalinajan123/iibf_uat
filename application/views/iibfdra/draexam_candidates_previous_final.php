<style type="text/css">
  .isDisabled {
  pointer-events: none;
  color: #0975b36b;
 /* display: none*/
}
.types {
    color: #223fcc;
    font-weight: 800;
}
.typec {
    color: #73c5ce;
    font-weight: 800;
}
.statusa {
  color: green;
  font-weight: 800;
}
.statusc {
  color: #9aa544;
  font-weight: 800;
}
.statusr {
  color: #cc4122;
  font-weight: 800;
}
</style>
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
      <?php $_SESSION['reffer'] = $_SERVER['REQUEST_URI'];
      ?>
      <?php $_SESSION['excode'] = $examcode;
      ?>
        <h1><?php echo ucwords($desc);?> Application Entry And Payment </h1>
    </section>
  <!-- Main content -->
    <form name="draexampay" class="draexampay" method="post" action="<?php echo base_url();?>iibfdra/TrainingBatches/payment/<?php echo base64_encode($examcode);?>">

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
        <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><?php //echo ucwords($desc);?>Candidate Details</h3>
                    <div class="pull-right">
                            <input type="submit" name="make_payment" class="btn  btn-warning mk-payment" value="Make Payment"/>
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
                       <!-- <input id="myInput" type="text" placeholder="Search.." style="float: right"><br><br>-->
                        <table id="listitems" class="table table-bordered table-striped dataTables-example">
                            <thead>
                                <tr>
                                  <th id="checkbox_id" class="checkboxcls"><input type="checkbox" id="selectall"/></th>
                                    <th id="srNo" class="serial_no">Sr.No.</th>
                                    <th id="batch_code">Batch Code</th>
                                    <th id="batch_id">Batch Name</th>
                                    <th id="member_no">Member No.</th>
                                    <th id="firstname">Candidate Name</th>
                                    <th id="dateofbirth">DOB</th>
                                    <th id="email">Email</th>
                                    <th id="exam_fee">Fee</th>
                                    <th id="pay_status">Payment Status</th>
                                    <th id="">Neft/Utr No</th>
                                    <th id="exam_center_code">Exam Center Name</th>
                                    <th id="exam_medium">Exam Medium</th>
                                    <th id="action">Operations</th> 
                                </tr>
                            </thead>
                            
                             <tbody class="no-bd-y" id="list">

                    <!--  /////////////////////////////////////////////Eligible data//////////////////////////////////// -->

             <?php 
                   $i = 1;
                  // print_r($eligible); die;
                  if(count($eligible)){
                     $trg_value=0;
                                        foreach($eligible as $row){ 

                                            $training_from    = $row['batch_from_date'];
                                            $training_to    = $row['batch_to_date'];
                                            $registrationtype = $row['registrationtype'];
                                            $batch_id  = $row['batch_id'];
                      if(isset($row['trg_value'])){$trg_value = $row['trg_value'] + 1;}
                       $Todate = date_create($row['batch_to_date']);
                        date_add($Todate, date_interval_create_from_date_string($trg_value.' days'));?>
                               
                   

                     <!--  -->
                                        
                                               
                        <?php   if((isset($row['exam_date'])) ){  //check 290days < and 3attempt < alert already exist in exam list
                         
                         if(($row['exam_date'] < date_format($Todate, "Y-m-d")) && $row['re_attempt'] < 3 && (($row['pay_status'] != 1 || $row['pay_status'] == ''))  && ($row['ecreated_on'] == "" || $row['ecreated_on'] > '2018-01-09')){


                     ?>
                                     <tr>
                                    <td align="center">
                                        
                                        <?php 
                                        
                                        if((date('Y-m-d',strtotime("+0 day", strtotime($training_to))) < date('Y-m-d')))
                                        {
                                        if(($row['pay_status'] == 2 || $row['pay_status'] == '') && ($row['exam_center_code']!='' &&  $row['exam_medium']!='')) { 
                                        ?>
                                                            <input type="checkbox" class="chkmakepay" name="chkmakepay[]" value="<?php echo $row['mem_examid'];?>" data-attr='<?=$row['regid']?>'/>
                                        <?php 
                                        }
                                        }?>

                                        </td>
                                        <td></td>
                                      <td><?php echo $row['batch_code'];?></td>
                                        <td><?php echo $row['batch_name'];?></td>
                                        <td><?php echo $row['regnumber'];?></td>
                                        <td><?php echo $row['firstname'].' '.$row['middlename'].' '.$row['lastname'];?></td>
                                        <td><?php echo date("d-M-Y", strtotime($row['dateofbirth']));?></td>
                                        <td><?php echo $row['email'];?></td>
                                        <td class="fee<?=$row['regid']?>"><?php 
                                       if($row['exam_fee'] <= 0 || $row['exam_fee'] == ""){
                                        echo "0.00";
                                       }else{
                                        echo $row['exam_fee'];
                                       }
                                       ?></td>
                                        <td  class="status<?=$row['regid']?>"><?php 
                    if( $row['pay_status'] == '0' ) {echo 'Fail';} 
                    else if( $row['pay_status'] == '2' ||  $row['pay_status']=='') { echo 'Pending'; } 
                    else if($row['pay_status'] == '3') { echo 'Payment For Approve By IIBF';} ?></td>
                                        <td><?php echo $row['utr_no'];?></td>
                                        <td style="width: 117px;">
                                            <?php
                      if($row['pay_status']!='')
                       { 
                         $this->db->select('center_name');
                         $this->db->join('dra_exam_activation_master','dra_exam_activation_master.exam_code=dra_center_master.exam_name AND dra_exam_activation_master.exam_period=dra_center_master.exam_period');
                        $center_name= $this->master_model->getRecords('dra_center_master',array('center_code'=>$row['exam_center_code']),'',array('center_name'=>'ASC'));
                        if(count($center_name) > 0)
                        {
                          echo $center_name[0]['center_name'];
                        }
                                        } 
                     else
                     {?>
                     -
                      <?php 
                      }?>
                                            
                                         <input type="hidden" class="form-control" id="center_code" name="" placeholder="Center Code"  value="<?php echo set_value('center_code');?>" autocomplete="off" readonly>
                                    </td>
                                        <td style="width: 117px;">
                                               <?php
                                           if($row['pay_status']!='')
                       { 
                         $this->db->select('medium_description');
                         $this->db->join('dra_exam_activation_master','dra_exam_activation_master.exam_code=dra_medium_master.exam_code AND dra_exam_activation_master.exam_period=dra_medium_master.exam_period');
                        $medium_name = $this->master_model->getRecords('dra_medium_master',array('medium_code'=>$row['exam_medium']));
                        if(count($medium_name) > 0)
                        {
                          echo $medium_name[0]['medium_description'];
                        }
                                        } 
                       else{ ?>
                                    -
                                    <?php } ?>
                                         </td>
                                        <td>
                                            <?php //if( $row['pay_status'] == 0 ) { ?>
                                             <input type="hidden" value="<?=$batch_id?>" id="batch_id<?=$row['regid']?>">
                                             <input type="hidden" value="<?=$registrationtype?>" id="memtype<?=$row['regid']?>">
                                             <input type="hidden" value="<?=$training_from?>" id="training_from<?=$row['regid']?>">
                                             <input type="hidden" value="<?=$training_to?>" id="training_to<?=$row['regid']?>">
                                           
                                           
                                          <?php if( $row['pay_status'] == 0 || $row['pay_status'] == 2 ) { ?>
                                          <a class="mbtn btn-xs btn-warning2" target="_blank" href="<?php echo base_url().'iibfdra/TrainingBatches/editApplicant/'.$row['regid'];?>">Edit</a>
                                            
                                            <?php 
                      if(isset($row['exam_medium']) || isset($row['exam_center_code']) ){
                      if($row['exam_medium'] != '' || $row['exam_center_code'] != 0 ){ ?>
                                                
                                             <a  href="javascript:void(0)" class="<?=$row['regid']?> mbtn btn-xs btn-dange2r clearexam" data-toggle="tooltip" data-placement="top" title=" Clear Submit "  onclick="clearexam('<?=$row['regid']?>')" > | Clear</a>
                                                <?php
                                     }
                              }
                      ?>
                                           
                                            
                                            <?php } else { echo '-';}?>
                                        </td>
                                     </tr>
                   <?php 
                   }}else
                    {//echo "swati"; exit;
                    if((($row['pay_status'] != 1 || $row['pay_status'] == ''))  &&  ($row['exam_period'] == $examperiods || $row['exam_period']=='') && ($row['ecreated_on'] == "" || $row['ecreated_on'] > '2018-01-09')) {


                     ?>
                                          <tr>
                                      <td align="center">
                                        
                                        <?php 
                                        
                    if((date('Y-m-d',strtotime("+0 day", strtotime($training_to))) < date('Y-m-d')))
                    {
                    if(($row['pay_status'] == 2 || $row['pay_status'] == '') && ($row['exam_center_code']!='' &&  $row['exam_medium']!='')) { 
                    ?>
                                        <input type="checkbox" class="chkmakepay" name="chkmakepay[]" value="<?php echo $row['mem_examid'];?>" data-attr='<?=$row['regid']?>'/>
                    <?php 
                    }
                    }?>

                                        </td>
                                        <td></td>
                                      <td><?php echo $row['batch_code'];?></td>
                                        <td><?php echo $row['batch_name'];?></td>
                                        <td><?php echo $row['regnumber'];?></td>
                                        <td><?php echo $row['firstname'].' '.$row['middlename'].' '.$row['lastname'];?></td>
                                        <td><?php echo date("d-M-Y", strtotime($row['dateofbirth']));?></td>
                                        <td><?php echo $row['email'];?></td>
                                        <td class="fee<?=$row['regid']?>"><?php 
                                       if($row['exam_fee'] <= 0 || $row['exam_fee'] == ""){
                                        echo "0.00";
                                       }else{
                                        echo $row['exam_fee'];
                                       }
                                       ?></td>
                                        <td  class="status<?=$row['regid']?>"><?php 
                    if( $row['pay_status'] == '0' ) {echo 'Fail';} 
                    else if( $row['pay_status'] == '2' ||  $row['pay_status']=='') { echo 'Pending'; } 
                    else if($row['pay_status'] == '3') { echo 'Payment For Approve By IIBF';} ?></td>
                                        <td><?php echo $row['utr_no'];?></td>
                                        <td style="width: 117px;">
                                            <?php
                      if($row['pay_status']!='')
                       { 
                         $this->db->select('center_name');
                         $this->db->join('dra_exam_activation_master','dra_exam_activation_master.exam_code=dra_center_master.exam_name AND dra_exam_activation_master.exam_period=dra_center_master.exam_period');
                        $center_name= $this->master_model->getRecords('dra_center_master',array('center_code'=>$row['exam_center_code']),'',array('center_name'=>'ASC'));
                        if(count($center_name) > 0)
                        {
                          echo $center_name[0]['center_name'];
                        }
                                        } 
                     else
                     {?>
                      -
                      <?php 
                      }?>
                                            
                                         <input type="hidden" class="form-control" id="center_code" name="" placeholder="Center Code"  value="<?php echo set_value('center_code');?>" autocomplete="off" readonly>
                                    </td>
                                        <td style="width: 117px;">
                                               <?php
                                             if($row['pay_status']!='')
                       { 
                         $this->db->select('medium_description');
                         $this->db->join('dra_exam_activation_master','dra_exam_activation_master.exam_code=dra_medium_master.exam_code AND dra_exam_activation_master.exam_period=dra_medium_master.exam_period');
                        $medium_name = $this->master_model->getRecords('dra_medium_master',array('medium_code'=>$row['exam_medium']));
                        if(count($medium_name) > 0)
                        {
                          echo $medium_name[0]['medium_description'];
                        }
                                        } 
                       else{ ?>
                                     -
                                    <?php } ?>
                                         </td>
                                        <td>
                                            <?php //if( $row['pay_status'] == 0 ) { ?>
                                             <input type="hidden" value="<?=$batch_id?>" id="batch_id<?=$row['regid']?>">
                                             <input type="hidden" value="<?=$registrationtype?>" id="memtype<?=$row['regid']?>">
                                             <input type="hidden" value="<?=$training_from?>" id="training_from<?=$row['regid']?>">
                                             <input type="hidden" value="<?=$training_to?>" id="training_to<?=$row['regid']?>">
                                           
                                            
                                          <?php if( $row['pay_status'] == 0 || $row['pay_status'] == 2 ) { ?>
                                          <a class="mbtn btn-xs btn-warning2"  target="_blank"  href="<?php echo base_url().'iibfdra/TrainingBatches/editApplicant/'.$row['regid'];?>">Edit</a>  &nbsp;
                                            
                                            <?php 
                      if(isset($row['exam_medium']) || isset($row['exam_center_code']) ){
                      if($row['exam_medium'] != '' || $row['exam_center_code'] != 0 ){ ?>
                                                
                                             <a  href="javascript:void(0)" class="<?=$row['regid']?> mbtn btn-xs btn-dange2r clearexam" data-toggle="tooltip" data-placement="top" title=" Clear Submit "  onclick="clearexam('<?=$row['regid']?>')" > | Clear</a>
                                                <?php
                                     }
                              }
                      ?>
                                           
                                            
                                            <?php } else { echo '-';}?>
                                        </td>
                    </tr>  
                    <?php } 
                    elseif((($row['pay_status'] != 1 || $row['pay_status'] == '') && $row['pay_status'] != 4 ) &&  ($row['exam_period'] != $examperiods) && ($row['ecreated_on'] == "" || $row['ecreated_on'] > '2018-01-09')){
                     ?>
                                          <tr>
                                          <td align="center">
                                        
                                        <?php 
                              
                    if((date('Y-m-d',strtotime("+0 day", strtotime($training_to))) < date('Y-m-d')))
                    {
                    if(($row['pay_status'] != 2 || $row['pay_status'] != 0) && ($row['exam_center_code']!='' &&  $row['exam_medium']!='')) { 
                    ?>
                    <?php 
                    }
                    }?>

                                        </td>
                                        <td></td>
                     <td><?php echo $row['batch_code'];?></td>
                                        <td><?php echo $row['batch_name'];?></td>
                                        <td><?php echo $row['regnumber'];?></td>
                                        <td><?php echo $row['firstname'].' '.$row['middlename'].' '.$row['lastname'];?></td>
                                        <td><?php echo date("d-M-Y", strtotime($row['dateofbirth']));?></td>
                                        <td><?php echo $row['email'];?></td>
                                         <td class="fee<?=$row['regid']?>">
                                            <?php 
                    if( $row['pay_status'] == '0' ) {echo '0.00';} 
                    else if( $row['pay_status'] == '2' ||  $row['pay_status']=='') { echo '0.00'; } 
                    else if($row['pay_status'] == '3') { echo $row['exam_fee'];} ?>
                                       </td>
                                         <td  class="status<?=$row['regid']?>"><?php 
                    if( $row['pay_status'] == '0' ) {echo 'Fail';} 
                    else if( $row['pay_status'] == '2' ||  $row['pay_status']=='') { echo 'Pending'; } 
                    else if($row['pay_status'] == '3') { echo 'Payment For Approve By IIBF';} ?></td>
                                         <td><?php echo $row['utr_no'];?></td>
                                         <td style="width: 117px;">
                                           -
                                         <input type="hidden" class="form-control" id="center_code" name="" placeholder="Center Code"  value="<?php echo set_value('center_code');?>" autocomplete="off" readonly>
                                    </td>
                                         <td style="width: 117px;">
                                             -
                                         </td>
                                        <td>
                                            <?php //if( $row['pay_status'] == 0 ) { ?>
                                             <input type="hidden" value="<?=$batch_id?>" id="batch_id<?=$row['regid']?>">
                                             <input type="hidden" value="<?=$registrationtype?>" id="memtype<?=$row['regid']?>">
                                             <input type="hidden" value="<?=$training_from?>" id="training_from<?=$row['regid']?>">
                                             <input type="hidden" value="<?=$training_to?>" id="training_to<?=$row['regid']?>">
                                           
                                            
                                          <?php if( $row['pay_status'] == 0 || $row['pay_status'] == 2 ) { ?>
                                          <a class="mbtn btn-xs btn-warning2"  target="_blank"  href="<?php echo base_url().'iibfdra/TrainingBatches/editApplicant/'.$row['regid'];?>">Edit</a>  &nbsp;
                                            
                                            <?php 
                      if(isset($row['exam_medium']) || isset($row['exam_center_code']) ){
                      if($row['exam_medium'] != '' || $row['exam_center_code'] != 0 ){ ?>
                                                
                                           
                                                <?php
                                     }
                              }
                      ?>
                                           
                                            
                                            <?php } else { echo '-';}?>
                                        </td>   
                                        </tr> 
                    <?php }
                     }
                        }
              }?>
            
                 


          
                       
                                </tbody>
                        </table>
                        <div style="width:30%; float:left;">
                <?php /*Removed pagination on 21-01-2017*/ 
              //echo $info; ?>
                        </div>
                        <input type="hidden" value="<?=$examcode?>" id="examcode">
                       
                        <div id="links" class="" style="float:right;"><?php echo ""; ?></div>
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
 $(document).ready(function() {
    $('.dataTables-example').wrap('<div class="table-responsive"></div>');
  var i = 0;
    $('table tr').each(function(index) {
        $(this).find('td:nth-child(2)').html(index-1+1);
    });
  
      $("body").on("contextmenu",function(e){
         return false;
     });
  
  $('[data-toggle="tooltip"]').tooltip();   

   /*$("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#listitems tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });*/
 });
</script>
<script type="text/javascript">
$(function () {
  /*$("#listitems").DataTable();
  var base_url = '<?php //echo base_url(); ?>';
  paginate(base_url+'iibfdra/DraExam/getApplicantList','','','');
  $("#base_url_val").val(base_url+'iibfdra/DraExam/getApplicantList');*/
  // add multiple select / deselect functionality
  $("#selectall").click(function () {
      $('.chkmakepay').prop('checked', this.checked);
  });

  // if all checkbox are selected, check the selectall checkbox
  // and viceversa
  $(".chkmakepay").click(function(){
    /*var row_id=$(this).attr("data-attr");
     var exam_medium = $('#exam_medium'+row_id).val();
         var exam_center = $('#exam_center'+row_id).val();
     if(exam_medium != '' && exam_center !='')
     {*/
    if($(".chkmakepay").length == $(".chkmakepay:checked").length) {
  //    $("#selectall").prop("checked", true);
    }
    else {
    //  $(this).removeAttr("checked");
  /*  } 
     }
     else{
          $(this).removeAttr("checked");
            alert('Please select exam medium and exam center');
    }*/
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
<script>
$(function () {
   
   // $("#listitems2_filter").show();
    
     // DataTable
   /*var table = $('#listitems').DataTable();
   table.columns( '.serial_no' ).order( 'asc' ).draw();*/
   
    var table = $('#listitems').DataTable(
  {
      "columnDefs": [ {
          "targets": 'checkboxcls',
          "orderable": false,
    } ],
      "lengthMenu": [[10, 25, 50,100, 500,1000], [10, 25, 50,100,500,1000]]
    }
  );
  table.columns( '.serial_no' ).order( 'asc' ).draw();
  
        $("#listitems tfoot th").each( function ( i ) {
        var select = $('<select  class="pp'+i+'" ><option value="">All</option></select>')
            .appendTo( $(this).empty() )
            .on( 'change', function () {
                table.column( i )
                    .search( $(this).val() )
                    .draw();
            } );
 
        table.column( i ).data().unique().sort().each( function ( d, j ) {
            select.append( '<option value="'+d+'">'+d+'</option>' )
        } );
    } );
        
});


function upadteexam(obj){

      var regid = obj;
      var batchId = $('#batch_id'+obj).val();;
      var memtype = $('#memtype'+obj).val();
      var examcode = $('#examcode').val();
      var exam_medium = $('#exam_medium'+obj).val();
      var exam_center = $('#exam_center'+obj).val();
      var training_from = $('#training_from'+obj).val();
      var training_to = $('#training_to'+obj).val();
      $('#upadteexam'+obj).css('pointer-events','none');
      $('#upadteexam'+obj).css('color', '#0975b36b');
//alert(regid);
//alert(regid);

      // AJAX request
      if(exam_medium != '' && exam_center !='' && memtype !=''){
       // alert(exam_medium); 
      $.ajax({
        url:'<?=base_url()?>iibfdra/TrainingBatches/upadeApplicant',
        method: 'post',
        data: {regid: regid,examcode: examcode,exam_medium: exam_medium,exam_center: exam_center,training_from: training_from,training_to: training_to,memtype: memtype,batchId: batchId},
        dataType: 'json',
        success: function(response){
       //alert(response);
          // Add options
         if(response == 1){
            alert('Updating record is getting fail.please try again.')
         }
         else{
                location.reload();
                //$('#fee'+obj).text(response);
                //$('#status'+obj).text('Payment For Approve By IIBF');
               // $('#upadteExam').remove();
         }
          
        }
     });
}
else{
    alert('Please select exam medium and exam center and member type can not be empty.');
}

   }
   

function clearexam(obj){
      var regid = obj;
     // var memtype = $('#memtype'+obj).val();
      var regid = obj;
       var batchId = $('#batch_id'+obj).val();;
      var memtype = $('#memtype'+obj).val();
      var examcode = $('#examcode').val();
      var exam_medium = $('#exam_medium'+obj).val();
      var exam_center = $('#exam_center'+obj).val();
      var training_from = $('#training_from'+obj).val();
      var training_to = $('#training_to'+obj).val();
    
    if (confirm('Are you sure you want to Clear Exam details of the selected application')) {
   
      if(exam_medium != '' && exam_center !='' && regid != ''){     
      $.ajax({
      url:'<?=base_url()?>iibfdra/TrainingBatches/clearApplicant',
      method: 'post',
      data: {regid: regid,examcode: examcode,exam_medium: exam_medium,exam_center: exam_center,training_from: training_from,training_to: training_to,memtype: memtype,batchId: batchId},
      dataType: 'json',
      success: function(response){          
         if(response == 'fail'){
          alert('Updating record is getting fail.please try again.')
         }else{
           console.log('done');
           location.reload();             
         }
          
        }
      });   
    }else{
      alert('Please select exam medium and exam center');
    }
    }

   }
   
    
</script> 

<link href="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">

<!-- Data Tables --> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script> 
<!-- Data Tables --> 


