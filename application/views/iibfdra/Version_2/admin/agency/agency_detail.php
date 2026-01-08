<?php $this->load->view('iibfdra/Version_2/admin/includes/header');?>
<?php $this->load->view('iibfdra/Version_2/admin/includes/sidebar');?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Agency Details 
      </h1>
      <?php echo $breadcrumb; ?>
      
    </section>
  <div class="col-md-12">
    <br />        
    </div>
    <!-- Main content -->
    <section class="content">
      <div class="row">      
      <div class="col-md-12">
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
        
        if($this->session->flashdata('error')!=''){?>
              <div class="alert alert-danger alert-dismissible" id="error_id">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('error'); ?> </div>
              <?php } 
        
         if($var_errors!=''){?>
              <div class="alert alert-danger alert-dismissible" id="error_id">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $var_errors; ?> </div>
              <?php } 
        
        if($this->session->flashdata('success')!=''){ ?>
              <div class="alert alert-success alert-dismissible" id="success_id">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('success'); ?> </div>
              <?php } 
       if(validation_errors()!=''){?>
              <div class="alert alert-danger alert-dismissible" id="error_id">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo validation_errors(); ?> </div>
              <?php }
        if(@$var_errors!='')
        {?>
              <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $var_errors; ?> </div>
              <?php } ?>


               <?php 
             if($result['status'] == 1 ){ 
                $status_text =  'Active'; 
              $str_btn = '<textarea name="reject_reason" class="reject_reason" maxlength="300" rows="5" cols="40" placeholder="Describe deactive reason here"></textarea></br><a class="reject_aj btn btn-danger " dataid ="'.$result['id'].'" href="javascript:void(0);">Click to Deactive</a>';
              
              $div_class = '#d4edda';
              $div_class2 = '#d4edda';
             }else { 
                $status_text =  'Deactive'; 
              $str_btn = '<textarea name="reject_reason" class="reject_reason" maxlength="300" rows="5" cols="40" placeholder="Describe activate reason here"></textarea></br><a class="approve_aj btn btn-success " dataid ="'.$result['id'].'" href="javascript:void(0);">Click to Active</a>';
              //$str_btn = '';
              $div_class = '#f8d7da';
              $div_class2 = '#f8d7da';
             }
             
             $div_class = ''; 
             $div_class2 = '';            
        ?>
              <form method="post" name="appfrom" id="approve_from" >
              <input type="hidden" name="status" value="<?php echo $result['status']; ?>" />
              <input type="hidden" name="reason" id="reason" value="" />
            </form>

            <form class="form-horizontal" name="frmDrACenter" id="frmDrACenter"  method="post" action="" enctype="multipart/form-data" data-parsley-validate="parsley">
              
              <input type="hidden" name="id" value="<?php echo $result['id']; ?>">
              
              <div class="table-responsive ">
                  <table class="table table-bordered" style="background:<?php echo $div_class; ?>;">
                    <tbody>
                    <tr>                    
                      <td width="50%"><strong>Agency Name :</strong></td>
                      <td width="50%">
                        <input type="text" class="form-control" id="inst_name" name="inst_name" placeholder="Agency Name"  value="<?php echo $result['inst_name']; ?>"  data-parsley-maxlength="75" maxlength="75" required > 
                      </td>
                    </tr> 
                     <tr>                    
                      <td width="50%"><strong>Year Of Establishment :</strong></td>
                      <td width="50%">
                        <input type="text" class="form-control" id="estb_year" name="estb_year" placeholder="Year Of Establishment" data-parsley-type="number" value="<?php echo $result['estb_year']; ?>"  data-parsley-maxlength="4" maxlength="4" required>
                      </td>
                    </tr>

                    <tr>                    
                      <td width="50%"><strong>STD Code :</strong></td>
                      <td width="50%">
                        <input type="text" class="form-control" id="inst_stdcode" name="inst_stdcode" placeholder="STD Code"  value="<?php echo $result['inst_stdcode']; ?>" data-parsley-type="number" data-parsley-maxlength="6" maxlength="6" required>
                      </td>
                    </tr>

                    <tr>                    
                      <td width="50%"><strong>Telephone Number :</strong></td>
                      <td width="50%">
                        <input type="text" class="form-control" id="inst_phone" name="inst_phone" placeholder="Telephone Number"  value="<?php echo $result['inst_phone']; ?>" data-parsley-type="number" data-parsley-maxlength="12" maxlength="12" required>
                      </td>
                    </tr>   
                                  
                    <tr>
                      <td width="50%"><strong>Fax Number :</strong></td>
                      <td width="50%">
                        <input type="text" class="form-control" id="inst_fax_no" name="inst_fax_no" placeholder="Fax Number"  value="<?php echo $result['inst_fax_no']; ?>" data-parsley-type="number" data-parsley-maxlength="16" maxlength="16" required>
                      </td>
                    </tr>   
                                  
                    <!-- <tr>
                      <td width="50%"><strong> Agency Telephone Number / Fax number :</strong></td>
                      <td width="50%"> <?php if($result['inst_stdcode']!=''){ echo $result['inst_stdcode'].' -'; }else{ echo '-';}; ?>&nbsp; <?php if($result['inst_phone']!=''){ echo $result['inst_phone']; }else{ echo '---';}; ?> / 
                       <?php if($result['inst_fax_no']!=''){ echo $result['inst_fax_no']; }else{ echo '---';}; ?>
                        
                        <input type="text" class="form-control" id="estb_year" name="estb_year" placeholder="Year Of Establishment"  value="<?php echo $result['estb_year']; ?>"  data-parsley-maxlength="75" maxlength="75" required>  

                       </td>
                    </tr> -->
                    
                    <tr>
                      <td width="50%"><strong>Agency Website :</strong></td>
                      <td width="50%">
                        <input type="url" class="form-control" id="inst_website" name="inst_website" placeholder="Fax Number"  value="<?php if($result['inst_website']!=''){ echo $result['inst_website']; } ?>"  data-parsley-maxlength="100" maxlength="100" required>
                        </td>
                    </tr>

                    <tr>
                      <td width="50%"><strong>Agency Main Office Address :</strong></td>
                      <td width="50%">
                        <input type="text" class="form-control" id="main_office_address" name="main_office_address" placeholder="Agency Main Office Address"  value="<?php echo $result['main_office_address']; ?>"  data-parsley-maxlength="75" maxlength="75" required>
                        </td>
                    </tr>

                    <tr>
                      <td width="50%"><strong>Agency Main Address 1:</strong></td>
                      <td width="50%">
                        <input type="text" class="form-control" id="main_address1" name="main_address1" placeholder="Agency Main Address 1" value="<?php echo $result['main_address1']; ?>"  data-parsley-maxlength="75" maxlength="75" required>
                        </td>
                    </tr> 

                    <tr>
                      <td width="50%"><strong>Agency Main Address 2:</strong></td>
                      <td width="50%">
                        <input type="text" class="form-control" id="main_address2" name="main_address2" placeholder="Agency Main Address 2"  value="<?php echo $result['main_address2']; ?>"  data-parsley-maxlength="75" maxlength="75">
                        </td>
                    </tr> 

                    <tr>
                      <td width="50%"><strong>Agency Main Address 3:</strong></td>
                      <td width="50%">
                        <input type="text" class="form-control" id="main_address3" name="main_address3" placeholder="Agency Main Address 3"  value="<?php echo $result['main_address3']; ?>"  data-parsley-maxlength="75" maxlength="75">
                        </td>
                    </tr>   

                    <tr>
                      <td width="50%"><strong>Agency Main Address 4:</strong></td>
                      <td width="50%">
                        <input type="text" class="form-control" id="main_address4" name="main_address4" placeholder="Agency Main Address 4"  value="<?php echo $result['main_address4']; ?>"  data-parsley-maxlength="75" maxlength="75">
                        </td>
                    </tr> 

                    <tr>
                      <td width="50%"><strong>Agency Main District:</strong></td>
                      <td width="50%">
                        <input type="text" class="form-control" id="main_district" name="main_district" placeholder="Agency Main District"  value="<?php echo $result['main_district']; ?>"  data-parsley-maxlength="75" maxlength="75" required>
                        </td>
                    </tr> 

                    <tr>
                      <td width="50%"><strong>Agency State Name:</strong></td>
                      <td width="50%">
                        <select id="state_name" name="state_name" class="form-control" required>
                          <option value="">Select State</option>
                          <?php foreach ($res_state as $state_key => $state) { ?>
                            <option value="<?php echo $state['state_code'] ?>" <?php if ( $result['main_state'] == $state['state_code'] ) { echo 'Selected'; } ?>><?php echo $state['state_name'] ?></option>
                          <?php } ?>
                        </select>

                        <!-- <input type="text" class="form-control" id="state_name" name="state_name" placeholder="Agency State Name"  value="<?php echo $result['state_name']; ?>"  data-parsley-maxlength="75" maxlength="75" required> -->
                        </td>
                    </tr>
                    <tr>
                      <td width="50%"><strong>Agency Main City:</strong></td>
                      <td width="50%">
                        <select id="main_city" name="main_city" class="form-control" required>
                          <option value="">Select City</option>
                          <?php foreach ($res_city as $city_key => $city) { ?>
                            <option value="<?php echo $city['id'] ?>" <?php if( $result['main_city'] == $city['id'] ) { echo 'Selected'; } ?>><?php echo $city['city_name'] ?></option>
                          <?php } ?>
                        </select>

                        <!-- <input type="text" class="form-control" id="main_city" name="main_city" placeholder="Agency Main City"  value="<?php echo $result['main_city']; ?>"  data-parsley-maxlength="75" maxlength="75"> -->
                        </td>
                    </tr>

                    <!-- <tr>
                      <td width="50%"><strong>Agency City Name:</strong></td>
                      <td width="50%">
                        <input type="text" class="form-control" id="city_name" name="city_name" placeholder="Agency City Name"  value="<?php echo $result['city_name']; ?>"  data-parsley-maxlength="75" maxlength="75" required>
                        </td>
                    </tr> -->   

                     <tr>
                      <td width="50%"><strong>Agency Main Pincode:</strong></td>
                      <td width="50%">
                        <input type="text" class="form-control" id="main_pincode" name="main_pincode" placeholder="Agency Main Pincode"  value="<?php echo $result['main_pincode']; ?>" onkeypress="return event.charCode >= 46 && event.charCode <= 57 || event.keyCode == 8 || event.keyCode == 46 "  data-parsley-maxlength="6"  maxlength="6" size="6" data-parsley-maxlength="75" maxlength="75" required>
                        </td>
                    </tr> 

                    <!-- <tr>
                      <td width="50%"><strong>Agency Main Address :</strong></td>
                      <td width="50%"><?php echo $result['main_office_address']; ?> <?php echo $result['main_address1']; ?> <?php echo $result['main_address2']; ?> <?php echo $result['main_address3']; ?> <?php echo $result['main_address4']; ?> <?php echo $result['main_district']; ?> <?php if( $result['city_name'] != ''){ echo $result['city_name']; }else{ echo $result['main_city']; } ; ?> <?php echo $result['state_name']; ?> <?php echo $result['main_pincode']; ?></td>
                    </tr> -->

                    <tr>
                      <td width="50%"><strong>Name Of Director/ Head Of Agency:</strong></td>
                      <td width="50%">
                        <input type="text" class="form-control" id="inst_head_name" name="inst_head_name" placeholder="Name Of Director/ Head Of Agency"  value="<?php echo $result['inst_head_name']; ?>"  data-parsley-maxlength="75" maxlength="75" required>
                        </td>
                    </tr>
                    <tr>
                      <td width="50%"><strong>Director Contact Number:</strong></td>
                      <td width="50%">
                        <input type="text" class="form-control" id="inst_head_contact_no" name="inst_head_contact_no" placeholder="Director Contact Number"  value="<?php echo $result['inst_head_contact_no']; ?>" data-parsley-type="number" data-parsley-maxlength="10" maxlength="10" minlength="10" required>
                        </td>
                    </tr>
                    <tr>
                      <td width="50%"><strong>Director Email Id:</strong></td>
                      <td width="50%">
                        <input type="text" class="form-control" id="inst_head_email" name="inst_head_email" placeholder="Director Email Id"  value="<?php echo $result['inst_head_email']; ?>"  data-parsley-maxlength="75" maxlength="75" data-parsley-type="email" data-parsley-pattern="/^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@([a-zA-Z0-9-]+[.]){1,2}[a-zA-Z]{2,10}$/" required>
                        </td>
                    </tr> 

                    <!--------------------------- Alternate Contact Person Details  ----------------------------------------------->
                    <tr>
                      <td width="50%"><strong>Name of Alternate Contact Person of the agency:</strong></td>
                      <td width="50%">
                        <input type="text" class="form-control" id="inst_altr_person_name" name="inst_altr_person_name" placeholder="Name of Alternate Contact Person of the agency" value="<?php echo $result['inst_altr_person_name']; ?>"  data-parsley-maxlength="75" maxlength="75" required>
                        </td>
                    </tr> 

                    <tr>
                      <td width="50%"><strong>Mobile No. of the Alternate Contact Person of the agency:</strong></td>
                      <td width="50%">
                        <input type="text" class="form-control" id="inst_alter_contact_no" name="inst_alter_contact_no" placeholder="Mobile No. of the Alternate Contact Person of the agency"  value="<?php echo $result['inst_alter_contact_no']; ?>" data-parsley-type="number" data-parsley-maxlength="10" maxlength="10" minlength="10" required>
                        </td>
                    </tr>

                    <tr>
                      <td width="50%"><strong>Email ID of the Alternate Contact Person of the agency:</strong></td>
                      <td width="50%">
                        <input type="text" class="form-control" id="inst_altr_email" name="inst_altr_email" placeholder="Email ID of the Alternate Contact Person of the agency"  value="<?php echo $result['inst_altr_email']; ?>"  data-parsley-maxlength="75" maxlength="75" data-parsley-type="email" data-parsley-pattern="/^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@([a-zA-Z0-9-]+[.]){1,2}[a-zA-Z]{2,10}$/" required>
                        </td>
                    </tr>
                    <!--------------------------- Alternate Contact Person Details  ----------------------------------------------->

                    <!-- <tr>
                      <td width="50%"><strong>Director Contact Number / Email Id :</strong></td>
                      <td width="50%">
                       <?php if($result['inst_head_contact_no']!=''){ echo $result['inst_head_contact_no']; }else{ echo '---';}; ?> /
                        <?php if($result['inst_head_email']!=''){ echo $result['inst_head_email']; }else{ echo '---';}; ?>
          </td>
                    </tr> -->
                                        
                     <tr>
                      <td width="50%"><strong>Agency Type:</strong></td>
                      <td width="50%"><?php 
              if($result['inst_type'] = 'R'){
              echo 'Regular'; 
              }else{
              echo 'Mobile';  
              }
            ?></td>
                    </tr>
                     <tr>
                      <td width="50%"><strong>Agency Status:</strong></td>
                      <td width="50%" style="background:<?php echo $div_class2; ?>;"><strong><?php echo $status_text; ?></strong>
           </td>
                    </tr>

                    <tr>
                      <td width="50%"><strong>GSTIN No:</strong></td>
                      <td width="50%" style="background:<?php echo $div_class2; ?>;"><strong><?php echo $result['gstin_no']; ?></strong>
           </td>
                    </tr>

                      <tr>
                      <td width="50%"><strong>Action</strong></td>
                      <td width="50%">
                          <?php echo $str_btn; ?>
                        </td>
                      </tr>
                      
                      <tr>
                        <td width="50%"><strong></strong></td>
                        <td width="50%">
                          <button class="btn btn-primary" type="submit" name='agency_submit' value="true">Update</button>
                        </td>
                    </tr>
                    
                  </tbody></table>
              </div>
                </form>
            </div>
          
            <!-- /.box-body --> 
          </div>
          <!-- /.box --> 
        </div>
        


        <div class="col-xs-12">
        <div class="box-header">
          <h3 class="box-title">Agency Center Transactions : [ <?php echo $result['inst_name']; ?>  ]</h3> </div>
          <div class="box">
            
            <div class="box-body">            
            <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
          <input type="hidden" name="base_url_val" id="base_url_val" value="" />
            <div class="table-responsive">
              <table id="listitems33" class="table table-bordered table-striped dataTables-example">
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
                <tbody class="no-bd-y" id="list_centers">  
                  <?php foreach ($arr_transaction as $key => $transaction) { ?>

                    <?php
                      if ($transaction['status'] == 2) {
                        $status = "Transaction Pending";
                      } elseif ($transaction['status'] == 3) {
                        $status = "Transaction Refunded.";
                      } elseif ($transaction['status'] == 1) {
                        $status = "Transaction Successful.";
                      } elseif ($transaction['status'] == 0) {
                        $status = "Transaction Fail.";
                      } elseif ($transaction['status'] == 7) {
                        $status = "Transaction InProcess.";
                      }

                      $proforma_url = $url = base_url()."iibfdra/Version_2/Center/performance_invoice/".$transaction['qty'].'/'.base64_encode($transaction['invoice_id']);
          
                      $action = ''; 
                      $action .= '<a href="'.$proforma_url.'" class="btn btn-warning" target="_blank" style="margin:2px;"> Proforma Invoice </a> <br>';

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
                      <td><?php echo date('d-M-Y',strtotime($transaction['date'])); ?></td>
                      <td><?php echo $transaction['amount']+$tds_amount; ?></td>
                      <td><?php echo $tds_amount; ?></td>
                      <td><?php echo $transaction['amount']; ?></td>
                      <td><?php echo $action; ?></td>
                    </tr>
                  <?php } ?>                       
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




        <div class="col-xs-12">
        <div class="box-header">
 <h3 class="box-title">Training Centers list For Agency : [ <?php echo $result['inst_name']; ?>  ]</h3> </div>
          <div class="box">
            
            <div class="box-body">            
            <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
          <input type="hidden" name="base_url_val" id="base_url_val" value="" />
            <div class="table-responsive">
      <table id="listitems22" class="table table-bordered table-striped dataTables-example">
                <thead>
                <tr>
                  <th id="srNo" style="width:5%;">S.No.</th>
                  <th id="location_name">Center Location</th>                
                  <th id="date_of_approved">Date Of Approval</th>
                  <th id="center_validity_from">Valid From</th>
                  <th id="center_validity_to">Valid To</th>
                  <th id="gstin">GSTIN No</th>
                  <th id="center_type">Center Type</th>
                  <th id="center_status">Status</th>
                  <th id="action">Operations</th>
                </tr>
                </thead>
                <tbody class="no-bd-y" id="list_centers">  
                    
                 <?php         
        $k = 1;
      //  print_r($center_result); die;
        if(count($center_result) > 0){
          foreach($center_result as $res){            
          echo '<tr><td>'.$k.' </td>';
          
          if($res['city_name'] != ''){
            $city = $res['city_name'];
            if($res['center_id'] == '3039') { $city .= ' - Wagholi'; }
            }else{
            $city = $res['location_name'];  
            if($res['center_id'] == '3039') { $city .= ' - Wagholi'; }
            }
          echo '<td>'.$city.'</td>'; 
                    
                    if($res['date_of_approved'] != ''){
            $app_date =  date_format(date_create($res['date_of_approved']),"d-M-Y"); 
          } else { 
            $app_date =  '--';  
          }
          
          if($res['center_validity_from'] == '' || $res['center_validity_from'] == '0000-00-00' ){
            $validity_from_date = '--';
            }else{
            $validity_from_date = date_format(date_create($res['center_validity_from']),"d-M-Y"); 
          }
          
          if($res['center_validity_to'] == '' || $res['center_validity_to'] == '0000-00-00' ){
            $validity_to_date = '--';
            }else{
            $validity_to_date = date_format(date_create($res['center_validity_to']),"d-M-Y"); 
          }
          
          
          $today_day = date('Y-m-d');         
          $to_date =  strtotime(date('Y-m-d',strtotime($res['center_validity_to'])));       
          $today_date = strtotime($today_day);
          
          $update_date = strtotime(date('Y-m-d',strtotime($res['modified_on']))); 
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
          
          if($res['center_validity_to'] == ''){
            $expire_str = '';
            $exp_class = '';  
          }
          
          
          if($res['center_status'] == 'A'){
            //$center_status = 'Approved(A)';
            if($update_done == 1 ){             
              $center_status = 'Approved(A)';             
             }else{                       
              if($expire_str != ''){
                  $center_status = $expire_str;
              }else{
                $center_status = 'Approved(A)';
              }
            }
            
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
          echo '<td class="'.$exp_class.'">'.$validity_from_date.' </td>';
          echo '<td class="'.$exp_class.'">'.$validity_to_date.' </td>';
          echo '<td>'.$res['gstin_no'].' </td>';
          echo '<td>'.$res['center_type'].' </td>';
          echo '<td>'.$center_status.' </td>';
          echo '<td><a class="btn btn-info btn-xs vbtn" href="'.base_url().'iibfdra/Version_2/agency/training_center_detail/'.$res['center_id'].'" >View</a> </td></tr>';
          $k++;
          }
        }?>                              
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
        
        <?php
        
        // Log table information code Start//
        $k = 1;
        $str = '';  
        $reasion ='';
        $agency_log_length = count($agency_log);
        
        //print_r($agency_log);
        
        if($agency_log_length > 0){
          foreach($agency_log as $res_log){
            
            
            
            $log_data = unserialize($res_log['description']);
            //print_r($log_data);
            $pre_text = '';
            
            if(isset($res_log['userid'])){  
              $admin_name = $res_log['name'];
            }else{
              $admin_name = '';
            }
            
            if(isset($log_data['reason']) && $log_data['reason'] != ''){              
                $reasion = '<span> '.$log_data['reason'].'</span>';
            }else{
              $reasion = '';
            }
              
            if(isset($log_data['updated_by'])){
              
            if($log_data['updated_by'] == 1  || $log_data['updated_by'] == 'A'){              
                $update_by = ' by '.$admin_name.' (A) ';
              }else{
                $update_by = ' by '.$admin_name.'   (R) ';  
              }
            }else{
              $update_by = '';  
            }
            
            
          $str .='<tr><td>'.$k.' </td>';        
          $str .='<td>'.$res_log['title'].' - '.$update_by.' </td>';
          $str .='<td>'.date_format(date_create($res_log['date']),"d-M-Y H:i:s").' </td>';
          $str .='<td> '.$reasion. '</td></tr>';
          $k++; 
        }
      }
    
    ?>
        
        <div class="col-xs-12">
        <div class="box-header">
 <h3 class="box-title">Agency Admin logs: [ <?php echo $result['inst_name']; ?>  ]</h3> </div>
          <div class="box">
            
            <div class="box-body">            
            <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
          <input type="hidden" name="base_url_val" id="base_url_val" value="" />
            <div class="table-responsive">
        <table id="listitems_logs" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>S.No.</th>
                    <th>Action</th>
                    <th>Action Date </th>
                    <th>Reason</th>
                  </tr>
                </thead>
                <tbody>
                 <?php
          echo $str;
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
  /* Get City From State in Agency tab */
  $('#state_name').on('change',function(){
    var state_code = $(this).val();
    getCity(state_code);
  });

  function getCity(state_code)
  {
    var site_url = "<?php echo base_url('/'); ?>";
    if(state_code)
    {
      $.ajax({
        type:'POST',
        url: site_url+'iibfdra/Version_2/agency/getCity',
        data:'state_code='+state_code,
        success:function(html){
          // $('#main_city').show();
          $('#main_city').html(html);
        }
      });
    }
    else
    {
      $('#main_city').html('<option value="">Select State First</option>');
    }
  }
</script>

<style>
.err{
 border:1px solid #F00; 
}
.exp_font{
 font-size:13px;
 color:#600;  
}
.redclass{
color:#C30; 
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
</style>

<script src="<?php echo base_url()?>js/js-paginate.js"></script>
<script>
$(function () {
  
  $('.approve_aj').click(function(){  
  
    $('.reject_reason').show();   
    var reject_reason = $.trim($('.reject_reason').val());
    
    if(reject_reason == ''){
      $('.reject_reason').addClass('err');
      return false; 
    }
    
      if (confirm('Are you sure you want to activate Agency?')) {
        $('#reason').val($('.reject_reason').val());
        $('#approve_from').submit();  
      } else {
        return false;
      }       
  });
  
  $('.reject_aj').click(function(){
    $('.reject_reason').show();   
    var reject_reason = $.trim($('.reject_reason').val());
    
    if(reject_reason == ''){
      $('.reject_reason').addClass('err');
      return false; 
    }
    
      if (confirm('Are you sure you want to deactivate Agency?')) {
        $('#reason').val($('.reject_reason').val());
        $('#approve_from').submit();  
      } else {
        return false;
      }   
  });
    
  $("#listitems22").DataTable();    
  $("#listitems33").DataTable();    
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