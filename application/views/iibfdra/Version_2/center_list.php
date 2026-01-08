<div class="content-wrapper">
  <section class="content-header">
    <h1>Center List</h1>
  </section>

  <form class="form-horizontal draexampay" name="draexampay" action="<?php echo base_url();?>iibfdra/Version_2/Center/generate_proforma" method="post">
    <section class="content">
      <div class="row"> 
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Added Center List</h3>
              <div class="pull-right"><a href="<?php echo base_url();?>iibfdra/Version_2/Center" class="btn btn-success">Add New Center</a>&nbsp;   &nbsp; <a href="<?php echo base_url();?>iibfdra/Version_2/CenterRenew/regular" class="btn btn-warning">Renewed Center(s)</a> 
              <?php if($agency_type != 'BANK') { ?>  
                <input type="submit" name="generate_proforma" class="btn  btn-primary mk-payment" value="Preview and Generate Proforma Invoice"/>  
              <?php } ?>

              </div>
              <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <?php if($this->session->flashdata('error')!=''){?>
              <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('error'); ?> </div>
              <?php } if($this->session->flashdata('success')!=''){ ?>
              <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('success'); ?> </div>
              <?php } ?>

              <div class="col-md-12" style="font-style: italic; font-size: 16px;">
                  <b>Disclaimer :</b> <span>Only one center can be selected while generating the proforma invoice and making</span> payment. 
              </div><br><br>

              <table id="listitems" class="table table-bordered table-striped Tables-example table-hover">
                <thead>
                  <tr>
                    <th>
                      <!-- <div>
                        <input type="checkbox" class="all-checkbox" id="selectall" style='margin:0' />
                      </div> -->
                    </th>
                    <th id="srNo">S.No.</th>
                    <th id="centername">Name Of Location(City)</th>
                    <th id="ststus">Status</th>
                    <th id="name">Contact Person Name</th>
                    <th id="mobile_no">Contact Person Mobile No.</th>
                    <th id=centertype>Center Type</th>
                    <th id=accradation>Accreditation Period</th>
                    <th id=status>Status</th>
                    <th id="batchaction">Action</th>
                  </tr>
                </thead>
                <tbody class="no-bd-y" id="list">

                  <?php 
                  $is_approve_status = '';
                  $today_day = date('Y-m-d');   
                  if( count( $center_listing )  > 0 ) { 
                    $i = 1;
                    foreach($center_listing as $center ) { ?>

          <?php
          $to_date =  strtotime(date('Y-m-d',strtotime($center['center_validity_to'])));        
          $from_date =  strtotime(date('Y-m-d',strtotime($center['center_validity_from'])));
          $payment_date = $center['payment_date'] != '' && $center['payment_date'] != null ? strtotime(date('Y-m-d',strtotime($center['payment_date']))) : '';

          $today_date = strtotime($today_day);
          
          $update_date = strtotime(date('Y-m-d',strtotime($center['modified_on'])));  
          $exp_class = '';          
          if($to_date < $today_date){
            $expire_str = ' <span class="exp_font">(Expired)</span> ';
            $exp_class = 'redclass';
          }else{
            $expire_str = '';
            $exp_class = '';  
          }
          
          if($update_date > $to_date){
            $update_done = 1;
          }else{
            $update_done = 0;
          }
          
          if($center['center_validity_to'] == ''){
            $expire_str = '';
            $exp_class = '';  
          }
                  
          if($center['city_name'] == "")
          {
            $extra = '';
            if($center['center_id'] == '3039') { $extra = ' - Wagholi'; }
              $center_name = $center['location_name'].$extra;
                      } 
                      else
                      {
            $extra = '';
            if($center['center_id'] == '3039') { $extra = ' - Wagholi'; }
            $center_name = $center['city_name'].$extra;
          }
        ?>
                     

                    <tr>        
          <?php if($center['center_status'] == 'IR' || $center['center_status'] == 'AR'){ 
         
          }elseif($center['center_status'] == 'R'){ 
          }else{             
           if($update_done == 1 ){
            $is_approve_status = 1;
                           }
                           else
                           {
             if($expire_str != ''){
                             }
                             else
                             {
                $is_approve_status = 1;
              }
            }
          }   
          ?>
                    
                      <?php 
                      //START : THIS CONDITION IS ADDED BY SAGAR & ANIL ON 01-11-2023. AS DISCUSS WITH PRAKASH MISHRA, THEY JUST WANT TO DISPLAY THE CENTER HAVING SAME ADDRESS(CITY/STATE) AS INSTITUTE PROFILE.
                      if(count($dra_accerdited_data) > 0)
                      {
                        if($_SESSION['dra_institute']['institute_code'] == '257') 
                        { 
                          $dra_accerdited_data[0]['address3'] = $dra_accerdited_data[0]['address4'] = $dra_accerdited_data[0]['address6'] = 'new delhi'; 
                        }

                        if($is_approve_status == '1' &&
                          (strtolower($dra_accerdited_data[0]['address3']) == strtolower($center['location_name']) ||
                          strtolower($dra_accerdited_data[0]['address3']) == strtolower($center['city_name']) ||
                          strtolower($dra_accerdited_data[0]['address3']) == strtolower($center['state_name']) ||
                          
                          strtolower($dra_accerdited_data[0]['address4']) == strtolower($center['location_name']) ||
                          strtolower($dra_accerdited_data[0]['address4']) == strtolower($center['city_name']) ||
                          strtolower($dra_accerdited_data[0]['address4']) == strtolower($center['state_name']) ||
                          
                          strtolower($dra_accerdited_data[0]['address6']) == strtolower($center['location_name']) || 
                          strtolower($dra_accerdited_data[0]['address6']) == strtolower($center['city_name']) ||
                          strtolower($dra_accerdited_data[0]['address6']) == strtolower($center['state_name']) ||
                          
                          (strtolower($dra_accerdited_data[0]['state_name']) != "" && strtolower($dra_accerdited_data[0]['state_name']) == strtolower($center['check_city_state_for_active'])))
                        )
                        { $center_status =  'Active'; ?>

                        <?php 
                          // Now currently the add the opposite condition because admin already extend the accridation pariod(20th May 2025)
                          $payVisible = false;
                          $payLable   = "Paid";
                          $color   = "#050";
                          if ( ($today_date <= $to_date && $today_date >= $from_date && ($center['pay_status'] == 1 || $center['pay_status'] == 6)) ) 
                          {  
                            $payVisible = true;
                            if ($payment_date == '') {
                              $payLable   = "Due";
                              $color   = "#C30";  
                            } 
                            
                          } 
                          else 
                          { 
                            if ( ($payment_date >= $to_date && $payment_date >= $from_date && ($center['pay_status'] == 1 || $center['pay_status'] == 6)) ) 
                            { 
                              $payVisible = true;
                              if ($payment_date == '') {
                                $payLable   = "Due";
                                $color   = "#C30";  
                              } 
                            }
                          } 
                        ?>
                               
                            <td>
                              <?php if ( $payment_date == '' && $payVisible && $agency_type != 'BANK') { ?>    
                                <input type="checkbox" class="chkmakepay" name="chkmakepay[]" value="<?php echo $center['center_id'].'|'.$center_name; ?>" data-attr="<?php echo $center['center_id']; ?>"/>
                              <?php } ?>
                            </td>  

                      <?php } 
                        else { ?> <td></td>  <?php $center_status =  'Inactive'; }
                      } //END : THIS CONDITION IS ADDED BY SAGAR & ANIL ON 01-11-2023. AS DISCUSS WITH PRAKASH MISHRA, THEY JUST WANT TO DISPLAY THE CENTER HAVING SAME ADDRESS(CITY/STATE) AS INSTITUTE PROFILE. ?>


                  
                    
                    <td><?php echo $i;?></td>
                    <td><?php echo $center_name; ?></td>
                     
                    <td>
          <?php if($center['center_status'] == 'IR' || $center['center_status'] == 'AR'){ 
          echo '<span style="color:#00C;">InReview</span>' ;
          }elseif($center['center_status'] == 'R'){ 
          echo '<span style="color:#F00;">Rejected</span>' ;
          }else{             
           if($update_done == 1 ){
            $is_approve_status = 1;
            echo  '<span style="color:#093;">Approved</span>' ; 
           }else{
            
             if($expire_str != ''){
                echo $expire_str;
               }else{
                $is_approve_status = 1;
              echo  '<span style="color:#093;">Approved</span>' ;    
              }
            }
          }   
          ?>
                    </td>
                    <td><?php echo $center['contact_person_name'];?></td>
                    <td><?php echo $center['contact_person_mobile'];?></td>
                    <td><?php if($center['center_type'] == 'T'){ echo 'Temporary';} else { echo 'Regular'; } ?></td>
                    <td class="<?php echo $exp_class; ?>"><?php if( $center['center_validity_to'] != '' &&  $center['center_validity_to'] != '0000-00-00' )  {?>
                     FROM <strong> <?php  if( $center['center_validity_from'] != '' &&  $center['center_validity_from'] != '0000-00-00' )  {  echo date_format(date_create($center['center_validity_from']),"d-M-Y"); } else{ echo '--'; } ?>
                     </strong> TO <strong>
           <?php  if( $center['center_validity_to'] != '' &&  $center['center_validity_to'] != '0000-00-00' )  { echo date_format(date_create($center['center_validity_to']),"d-M-Y");}else{ echo '--'; } ?>
                     </strong>
                     <?php  }else{ ?>
                    Accreditation  Period Not Added
                     <?php   }?></td>
                    
                    <td>
                      <?php echo $center_status; ?>
                    </td>

                    <td>
                    <?php 
          // add cundition to show renw table for Temporary center by Manoj 
          
          if ($center_status ==  'Active' && $is_approve_status == 1) { ?>
            <a href="<?php echo base_url().'iibfdra/Version_2/Center/GSTEdit/'.$center['center_id'];?>">Edit |</a>
          <?php }          

          if($center['center_validity_to'] != ''&& $center['center_validity_to'] != '0000-00-00' && $to_date < $today_date && $center['center_type'] == 'T'){ ?>
            <a href="<?php echo base_url().'iibfdra/Version_2/CenterRenew/view/'.$center['center_id'];?>">View</a>  
           
                     <?php if($center['is_renew'] != '1'){ ?>
                   | &nbsp; <a href="<?php echo base_url().'iibfdra/Version_2/CenterRenew/edit/'.$center['center_id'];?>">Renew</a>
                   <?php }elseif($center['is_renew'] == '1' && $center['center_status'] == 'R'){ ?>
          | &nbsp; <a href="<?php echo base_url().'iibfdra/Version_2/CenterRenew/renew_edit/'.$center['center_id'];?>">edit</a>   
          <?php }elseif($center['pay_status'] == '1') { ?> 
                    | <strong style="color:<?php echo $color;?>"><?php echo $payLable; ?></strong>
                <?php } ?>
                     
          <?php }  else{ ?>
                    
                    <a href="<?php echo base_url().'iibfdra/Version_2/Center/view/'.$center['center_id'];?>">View</a>
                    <?php if(($center['center_status'] == 'IR' ||  $center['center_status'] == 'R') && ( $center['center_add_status']!='F')) {?>
                     <a href="<?php echo base_url();?>iibfdra/Version_2/Center/edit/<?php echo $center['center_id'];?>"> | Edit</a>
                     <?php } else {
                      
                     }
           
                     if($center['pay_status'] == '1') { ?> 
                              | <strong style="color:<?php echo $color;?>"><?php echo $payLable; ?></strong>
                          <?php } ?>
                    <?php  
                    }
                    ?>
                    </td>
                    
                    
                    
                  </tr>
                  <?php $i++; } }?>  
                </tbody>
              </table>
              <div style="width:30%; float:left;">
                <?php /*Removed pagination on 21-01-2017*/ 
              //echo $info; ?>
              </div>
              <div id="links" class="" style="float:right;"><!-- <?php //echo $links; ?> --></div>
            </div>
            <!-- /.box-body --> 
          </div>
          <!-- /.box --> 
        </div>
        <!-- /.col --> 
      </div>
    </section>


    <section class="content">
      <div class="row"> 
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Proforma Invoice Payment</h3>
            </div>                        
              <div class="box-body">
                <table id="translistitems" class="table table-bordered table-striped Tables-example table-hover">
                  <thead>
                    <tr>
                      <th id="srNo">S.No.</th>
                      <th id="proforma_invoice_no">Proforma Invoice No.</th>
                      <th id="centername">Centers</th>
                      <!-- <th id=accradation>Center Count</th> -->
                      <th id="ststus">Transaction No.</th>
                      <th id=centertype>Receipt No.</th>
                      <th id="ststus">Payment Status</th>
                      <th id="name">Transaction Date</th>
                      <th id="mobile_no">Total Amount Before TDS</th>
                      <th id="mobile_no">TDS Amount</th>
                      <th id="mobile_no">Final Amount After TDS</th>
                      <th id="action">Proforma Invoice/Receipt</th>
                    </tr>
                  </thead>
                  <tbody class="no-bd-y" id="list">
                    <?php foreach ($arr_transaction as $key => $transaction) { ?>
                    <?php
                      if ($transaction['status'] == 2) {
                        $status = "Transaction Pending";
                      } elseif ($transaction['status'] == 1) {
                        $status = "Transaction Successful.";
                      } elseif ($transaction['status'] == 3) {
                        $status = "Transaction Refunded.";
                      } elseif ($transaction['status'] == 0) {
                        $status = "Transaction Fail.";
                      } elseif ($transaction['status'] == 7) {
                        $status = "Transaction InProcess.";
                      }

                      $proforma_url = $url = base_url()."iibfdra/Version_2/Center/performance_invoice/".$transaction['qty'].'/'.base64_encode($transaction['invoice_id']);
          
                      $action = ''; 
                      $action .= '<a href="'.$proforma_url.'" class="btn btn-warning" target="_blank" style="margin:2px;"> Proforma Invoice </a> <br>';
                      
                      if ($transaction['status'] == 2 || $transaction['status'] == 0) {

                        $payment_url = base_url()."iibfdra/Version_2/Center/goToPayment/".base64_encode($transaction['id'])."/".base64_encode($transaction['center_name']);

                        $action .= '<a href="'.$payment_url.'" class="btn btn-primary" style="margin:2px;"> Make Payment </a>';  
                      }

                      if (($transaction['status'] == 1 || $transaction['status'] == 3) && $transaction['invoice_image'] != '' && $transaction['invoice_no'] != '' ) 
                      {
                        $receipt_url = base_url()."uploads/drainvoice/user/".$transaction['invoice_image'];
                        $action .= '<a href="'.$receipt_url.'" class="btn btn-success" style="margin:2px;"> Receipt </a>';  
                      }

                      $tds_amount = $transaction['tds_amount'] != '' ? $transaction['tds_amount']:0;

                    ?>  

                    <tr>
                      <td><?php echo $key+1; ?></td>
                      <td><?php echo $transaction['proformo_invoice_no']; ?></td>
                      <td><?php echo $transaction['center_name']; ?></td>
                      <td><?php echo $transaction['transaction_no']; ?></td>
                      <td><?php echo $transaction['receipt_no']; ?></td>
                      <td><?php echo $status; ?></td>
                      <td><?php echo $transaction['date']; ?></td>
                      <td><?php echo $transaction['amount']+$tds_amount; ?></td>
                      <td><?php echo $tds_amount; ?></td>
                      <td><?php echo $transaction['amount']; ?></td>
                      <td><?php echo $action; ?></td>
                      <!-- <td><?php echo $transaction['transaction_no']; ?></td> -->
                    </tr>
                  <?php } ?>
                  </tbody>
                </table>                  
            </div>  
          </div>
        </div>
      </div>
    </section>        
  </form>
</div>
<style>
.exp_font{
 font-size:13px;
 color:#600;  
}
.redclass{
color:#C30; 
}
</style>
<script type="text/javascript">

$(function () {

  // add multiple select / deselect functionality
  // $("#selectall").click(function () {
  //    $('.chkmakepay').prop('checked', this.checked);
  // });

  // if all checkbox are selected, check the selectall checkbox
  // and viceversa
  // $(".chkmakepay").click(function(){
  //  if($(".chkmakepay").length == $(".chkmakepay:checked").length) {
  //    $("#selectall").prop("checked", true);
  //  } else {
  //    $("#selectall").removeAttr("checked");
  //  }
  // });
  
  $(document).on('change', '.chkmakepay', function () {
      if (this.checked) {
          $('.chkmakepay').not(this).prop('checked', false);
    }
  });

  $( ".draexampay" ).submit(function() {
    if( $(".chkmakepay:checked").length == 0 ) {
      alert('Please select at least one center to pay.');
      return false; 
    } else {
      return true;  
    }
  });
});

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
<script type="text/javascript">
 $(document).ready(function() {
    $('#listitems').DataTable({
        responsive: true,
        columnDefs: [
            { orderable: false, targets: 1 } // Disable sorting for the first column (checkboxes)
        ]
    });

   $('#translistitems').DataTable({
        responsive: true,
        columnDefs: [
            { orderable: false, targets: 1 } // Disable sorting for the first column (checkboxes)
        ]
  });
 });
</script>