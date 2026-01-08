<?php $this->load->view('admin/includes/header');?>
<?php $this->load->view('admin/includes/sidebar');?>
  <?php //if(@$member){echo '<pre>$member',print_r($member),'</pre>';} ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Exam Registration Details
        
      </h1>
      <?php echo $breadcrumb; ?>
    </section>
    <br />
  <div class="col-md-12">
    <?php if($this->session->flashdata('error')!=''){?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
            <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php } if($this->session->flashdata('success')!=''){ ?>
        <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
        <?php echo $this->session->flashdata('success'); ?>
        </div>
    <?php } ?>
    </div>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
              <h3 class="box-title"></h4>
              
                <div class="col-sm-12">
                  <form class="form-horizontal" name="searchExamDetails" id="searchExamDetails" action="" method="post">      
                        <label for="to_date" class="col-sm-2">Search By</label>
                         <div class="col-sm-2">
                           <select class="form-control" name="searchOn" id="searchOn" required>
                               <!-- <option value="">Select</option>-->
                                <option value="01" <?php if(isset($_POST['searchOn']) && $_POST['searchOn'] == '01'){echo "selected='selected'";}?>>Exam Details</option>
                                <option value="02" <?php if(isset($_POST['searchOn']) && $_POST['searchOn'] == '02'){echo "selected='selected'";}?>>Duplicate i-card Details</option>
                                <option value="03" <?php if(isset($_POST['searchOn']) && $_POST['searchOn'] == '03'){echo "selected='selected'";}?>>Amp Details</option>
                                <option value="04" <?php if(isset($_POST['searchOn']) && $_POST['searchOn'] == '04'){echo "selected='selected'";}?>>Duplicate Certificate</option>
                                <option value="05" <?php if(isset($_POST['searchOn']) && $_POST['searchOn'] == '05'){echo "selected='selected'";}?>>Member Renewal  Details</option>
                <option value="06" <?php if(isset($_POST['searchOn']) && $_POST['searchOn'] == '06'){echo "selected='selected'";}?>>Contact Class  Details</option>
                <option value="07" <?php if(isset($_POST['searchOn']) && $_POST['searchOn'] == '07'){echo "selected='selected'";}?>>Membership  Details</option>
                            </select>
                          </div>
                          <div class="col-sm-2">
                            <select class="form-control" name="searchBy" id="searchBy" required>
                                <!--<option value="">Select</option>-->
                                <option value="regnumber" <?php if(isset($_POST['searchBy']) && $_POST['searchBy'] == 'regnumber'){echo "selected='selected'";}?>>Registration No</option>
                                <option value="mobile" <?php if(isset($_POST['searchBy']) && $_POST['searchBy'] == 'mobile'){echo "selected='selected'";}?>>Mobile Number</option>
                                <option value="transaction_no" <?php if(isset($_POST['searchBy']) && $_POST['searchBy'] == 'transaction_no'){echo "selected='selected'";}?>>Transaction Number</option>
                                 <!--    ADDED BY - POOJA MANE 29/06/2022  -->
                                 <option value="receipt_no" <?php if(isset($_POST['searchBy']) && $_POST['searchBy'] == 'receipt_no'){echo "selected='selected'";}?>>Receipt Number</option>
                                <option value="email" <?php if(isset($_POST['searchBy']) && $_POST['searchBy'] == 'email'){echo "selected='selected'";}?>>Email</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                             <input type="text" class="form-control" id="SearchVal" name="SearchVal" placeholder="" required value="<?php if(isset($_POST['SearchVal'])){echo $_POST['SearchVal'];}?>" >
                        </div>
                        <div class="col-sm-2">
                            <input type="submit" class="btn btn-info" name="btnSearch" id="btnSearch" value="Search">
                            
                            <input type="button" class="btn btn-warning" name="btnPrint" id="btnPrint" value="Print" onclick="return printDiv('print_div');">
                        </div> 
                    </form> 
                </div>
              
             
              <!--<div class="pull-right">
                <a href="javascript:void(0);" class="btn btn-warning" onclick="javascript:printDiv();" id="printBtn" style="display:none;">Print</a>
                <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
                <input type="hidden" name="base_url_val" id="base_url_val" value="" /> 
              </div>-->
            </div>
            <!-- /.box-header -->
           <div class="box-body">
<?php if($_POST['searchOn'] != '07'){?>
      <center><h4>Member Details</h4></center>
           <table id="regDetails" class="table table-bordered table-striped ">
                <thead>
                <tr style="background-color:#00c0ef;color:#fff;">
                  <th>Membership No.<br />(Login ID)</th>
                  <th>Employer ID</th>  <!-- ADDED BY - POOJA MANE 18-05-2023 -->
                  <th>Name</th>
                  <th>Member Type</th>
                  <th>Password</th>
                  <th>Send Mail</th>
                </tr>
                </thead>
                <tbody class="no-bd-y" id="">
                <?php if(count($member_details)){
            foreach($member_details as $row1){   ?>
                 <tr>
                  <td><?php echo $row1['regnumber'];?></td>
                  <td><?php 
                  if($row1['emp_id']) {echo $row1['emp_id'];}else { echo $row1['bank_emp_id']; }/*ADDED BY POOJA MANE 18-05-2023*/
                  ?></td>
                    <td><?php echo $row1['firstname']." ".$row1['lastname'];?></td>
                    <td><?php echo $row1['registrationtype'];?></td>
                    <td><?php echo $row1['usrpassword'];?></td>
                    <td>
                      <?php if($row1['regnumber']) { ?>
                      <!--<a href="javascript:void(0);">Send Password</a>-->
                        <a href="<?php echo base_url(); ?>admin/Report/send_mail/<?php echo base64_encode($row1['regid']); ?>/<?php echo base64_encode($row1['regnumber']); ?>/1" onclick="return confirmMailSend();">Send Password</a>
                        <?php }else { echo "Incomplete <br> Transaction"; } ?>
                    </td>
                 </tr>
                 <?php }}else{ ?>
                 <tr><td colspan="4" align="center">No records found...</td></tr>
                 <?php } ?>                   
                </tbody>
              </table>
            <br />
            
            <?php 
      }
      if($membership_details) { //print_r($member); ?> 
            <center><h4>Transaction Details - Membership Payment </h4></center>
            <table id="" class="table table-bordered table-striped">
                <thead>
                <tr style="background-color:#00c0ef;color:#fff;">
                  <th>Membership No.<br />(Login ID)</th>
                  <th>Employer ID</th> <!-- ADDED BY - POOJA MANE 18-05-2023 -->
          <?php if(@$member[0]['transaction_no']){?>
          <th>Tran.No</th>
          <?php } 
           /* ADDED BY - POOJA MANE 29/06/2022 */
          elseif(@$member[0]['receipt_no']){ ?>
                  <th>Receipt NO</th>
          <?php } 
          elseif(@$member[0]['UTR_no']){ ?>
                  <th>UTR NO</th>
          <?php } ?>
                  <th>Exam Fees </th>
                  <th>Payment Status</th>
                  <th>Authentication Code</th>
                  <th>Transaction Time</th>
                  <th>View Invoice</th>
                  <th>Send Mail</th>
                  <th>Mail Sent</th>
                </tr>
                </thead>
                <?php if(count($member)){ ?>
                <tbody class="no-bd-y" id="list">
                <?php 
            foreach($member as $memnew){
         ?>
                  <tr>
            <td><?php echo $memnew['regnumber'];?></td>
            <td><?php 
                  if($row1['emp_id']){echo $row1['emp_id'];} else { echo $row1['bank_emp_id']; }/*ADDED BY POOJA MANE 18-05-2023*/
            ?></td>
          <?php if($memnew['transaction_no']){?>
            <td><?php echo $memnew['transaction_no'];?></td>
          <?php } elseif($memnew['UTR_no']){ ?>
            <td><?php echo $memnew['UTR_no'];?></td>
          <?php } ?>
          <?php if($memnew['transaction_no']){?>
            <td><?php echo $memnew['amount'];?></td>
          <?php } elseif(@$memnew['UTR_no']) { ?>
            <td><?php echo $memnew['base_fee'];?></td>
          <?php } ?>
                    <td>
                      <?php   if($memnew['status'] == 0)
                { $status = "Failure"; $color = "Red";  }
                else if($memnew['status'] == 1)
                { $status = "Success"; $color = "Green";  }
                else if($memnew['status'] == 2)
                { $status = "Pending"; $color = "Red";  }
              else if($row3['status'] == 3)
              { $status = "Refund"; $color = "Red"; }
              else if($row1['status'] == '6')
          { $status = "ChargeBack"; $color = "Blue";
          }else if($row1['status'] == '7')
          { $status = "Trn cancel no response from billdesk T-2Days"; $color = "Red";
          }else
              { $status = "Failure"; $color = "Red";    }
            ?>
                                    
                      <strong><font color="<?php echo $color; ?>"><?php echo $status; ?></font> <br><hr size="1/">
                        <?php if($memnew['transaction_details']){ ?> Trans Details: </strong>
                        <br>      
            <?php echo $memnew['transaction_details'];?>
            <?php } elseif($memnew['description']) {  
            echo $memnew['description'];
            }
            ?>
            
            
                    </td>
                    <td><?php echo $memnew['auth_code'];?></td>
          <?php if($memnew['transaction_no']){?>
                    <td><?php if($memnew['date']!=''){echo date('d-M-y H:i:s',strtotime($memnew['date']));}?></td>
          <?php }elseif($memnew['UTR_no']){?>
          <td><?php if($memnew['updated_date']!=''){echo date('d-M-y H:i:s',strtotime($memnew['updated_date']));}?></td>
                    
                    
          <?php } ?>
              <td>
                      <?php if($memnew['transaction_no']!=''){
             
                  $this->db->where('invoice_image!=',""); // status = 0 for FAILURE
              $this->db->where('transaction_no',$memnew['transaction_no']);
                   $invoice_name = $this->master_model->getRecords('exam_invoice');
                
                
                //print_r($invoice_name);exit;
              if(!empty($invoice_name))
              { 
                if(count($member_details)){
                 //print_r($member_details);exit;
            foreach($member_details as $row1){  
                  if($row1['registrationtype']!='O')
                   {
                    ?>
                    <a href="<?php echo base_url();?>uploads/examinvoice/user/<?php echo $invoice_name[0]['invoice_image'];?>" target="_blank">Invoice</a>
                   <?php }else
                   {?>
                    <a href="<?php echo base_url();?>uploads/reginvoice/user/<?php echo $invoice_name[0]['invoice_image'];?>" target="_blank">Invoice</a>
                  <?php  }
                  }
              }else
              {
                echo "-"; 
              }
            }elseif(@$memnew['UTR_no'])
            {
              
              $this->db->where('invoice_image!=',""); // status = 0 for FAILURE
              $this->db->where('transaction_no',$memnew['UTR_no']);
                   $invoice_name = $this->master_model->getRecords('exam_invoice');
                            if(!empty($invoice_name))
              {
              ?>
                         <!-- <a href="<?php echo base_url();?>uploads/bulkexaminvoice/user/<?php //echo $invoice_name[0]['invoice_image'];?>" target="_blank">Invoice</a>-->
                        
                         
                        <?php echo 'Bulk Exam Payment';  }else
            {
              echo "-"; 
            }?>
                           

            <?php }else{
         echo "-"; 
         } ?>
                     <?php } ?>
                    </td>                     
               <td>
               
               <a href="javascript:void(0);" transaction_no="<?php echo $memnew['transaction_no'] ?>"  class=" sendMailBtn">Send</a></td>
               <td> <?php if(@$member['emailLogs'] && !empty($member['emailLogs'] )){?>
                
                <span style="color:red;">Not Sent</span>
              <?php } else { ?>
                <span style="color:green;">Sent</span>
              <?php } ?></td>
                    </tr>
                <?php } ?>
                </tbody>
                <?php } ?>
              </table>
             <?php } ?>
             
             <br />
            
            <?php if(count($exams)){ ?>
            <center><h4>Transaction Details - Exam Payment</h4></center>
          <div class="table-responsive">
              <table id="" class="table table-bordered table-striped">
                <thead>
                <tr style="background-color:#00c0ef;color:#fff;">
                  <th>Membership No.<br />(Login ID)</th>
                  <th>Customer Id</th>
                  <th>Exam Name</th>
                  <th>Exam Fees</th>
                  <th>Exam Mode</th>
                  <th>Exam Medium</th>
                  <th>Center Name</th>
                  <th>Exam Date</th>
          <th>Gateway</th>
          <?php /*if($exams[0]['transaction_no']){?> <?php }elseif(@$exams[0]['UTR_no']){?>
          <th>UTR NO</th>
          <?php } */ ?>
                  <th>Transaction No</th>
                  <th>Payment Status</th>
                  <th>Auth.Code</th>
                  <th>Transaction Time</th>
                  <th>Send Mail</th>
                  <th>View Receipt</th>
                  <th>View Invoice</th>
                </tr>
                </thead>
                <tbody class="no-bd-y" id="">
                 <?php 
         //echo '<pre>';
        // print_r($exams);exit;
         if(count($exams)){
            
            foreach($exams as $row3){ ?>
                 <tr>
                  <td><?php echo $row3['regnumber'];?></td>
                    <td><?php echo $row3['receipt_no'];?></td>
                    <td>
          <?php 
            //echo get_exam_name($row3['exam_code'],$row3['exam_period']);
            $ename =  get_exam_name($row3['exam_code'],$row3['exam_period']);
              $explode_ename = explode("-",$ename);
              //echo $explode_ename[0];
              
              $arr = array('JAN','DEC','2019','2020');
              
              foreach ($arr as $char) {
                $pos = 0;
                while ($pos = strpos($ename, $char, $pos)) {
                  $positions[$char][] = $pos;
                  $pos += strlen($char);
                }
              }
              
              echo str_replace($arr, '', $ename);
          ?>
                    </td>
          <?php if($row3['transaction_no']){?>
                    <td><?php echo $row3['exam_fee'];?></td>
          <?php }elseif(@$row3['UTR_no']){ ?>
          <td><?php echo $row3['base_fee'];?></td>
          <?php } ?>
                    <td><?php if($row3['exam_mode'] == 'ON'){echo "Online";}else{ echo "Offline";}?></td>
                    <td><?php echo $row3['exam_medium'];?></td>
                    <td><?php echo $row3['center_name'];?></td>
                    <td><?php echo $row3['exam_date'];?></td>
          <td><?php echo $row3['gateway'];?></td>
          <?php if($row3['transaction_no']){?>
                    <td><a href="javascript:void(0);" class="getPaymentDetails" receipt_no="<?php echo $row3['receipt_no']; ?>"><?php echo $row3['transaction_no'];?></a></td>
          <?php }elseif(@$row3['UTR_no']){ ?>
          <td><?php echo $row3['UTR_no'];?></td>
          <?php }else{ ?>
          <td><?php echo "-";?></td>
          <?php } ?>
                    <!--<td><?php echo $row3['transaction_details'];?></td>-->
          
                    <td>
                      <?php 
              if($row3['status'] == 0)
              { $status = "Failure"; $color = "Red";  }
              else if($row3['status'] == 1)
              { $status = "Success"; $color = "Green";  }
              else if($row3['status'] == 2)
              { $status = "Pending"; $color = "Red";  }
              else if($row3['status'] == 3)
              { $status = "Refund"; $color = "Red"; }
        else if($row3['status'] == 7)
              { $status = "Trn cancel no response from billdesk T-2Days"; $color = "Red"; }
              else
              { $status = "Failure"; $color = "Red";    }
            ?>
                                    
                      <strong><font color="<?php echo $color; ?>"><?php echo $status; ?></font> <br><hr size="1/">
                        
                        <?php if(isset($row3[0]['transaction_details'])){ ?> Trans Details: <?php } ?></strong>
                        <br>      
            <?php if(isset($row3[0]['transaction_details'])){echo $row3[0]['transaction_details'];}?>
                    </td>
                    <td><?php echo $row3['auth_code'];?></td>
          <?php if($row3['transaction_no']){?>
                    <td><?php if($row3['date']!='') echo date('d-M-y H:i:s',strtotime($row3['date']));?></td>
          <?php }elseif(@$row3['UTR_no']){?>
          <td><?php if($row3['updated_date']!='') echo date('d-M-y H:i:s',strtotime($row3['updated_date']));?></td>
          <?php } ?>
          
                    <td><?php if($row3['status']==1){
                if($row3['transaction_no']){ ?>
            <a href="<?php echo base_url();?>admin/Report/transaction_mail/<?php echo base64_encode($row3['transaction_no']);?>/<?php echo base64_encode($row3['regnumber']);?>" onclick="return confirm('Do you want to send transaction mail?');">Send Mail</a><?php } elseif($row3['UTR_no']){ ?>
            <a href="<?php echo base_url();?>admin/Report/transaction_mail_bulkmem/<?php echo base64_encode($row3['UTR_no']); ?>/<?php echo base64_encode($row3['regnumber']);?>" onclick="return confirm('Do you want to send transaction mail?');">Send Mail</a>
            <?php }} else { echo "-";}?>
          </td>
                    <td>
                      <?php if($row3['transaction_no']){?>
                          <a href="<?php echo base_url();?>admin/Report/receipt/<?php echo base64_encode($row3['transaction_no']); ?>/<?php echo base64_encode($row3['regnumber']); ?>" target="_blank">Receipt</a>
                        <?php }elseif(@$row3['UTR_no']){?>
              <a href="<?php echo base_url();?>admin/Report/mem_receipt/<?php echo base64_encode($row3['bulk_pay_id']); ?>/<?php echo base64_encode($row3['regid']); ?>" target="_blank">Receipt</a>
            <?php }else{ echo "-"; } ?>
                    </td>


                      <td>
                      <?php 
            if($row3['transaction_no']){
                  $this->db->where('invoice_image!=',""); // status = 0 for FAILURE
              $this->db->where('transaction_no',$row3['transaction_no']);
                   $invoice_name = $this->master_model->getRecords('exam_invoice');
              // echo $this->db->last_query();
                //print_r($invoice_name);
                /*echo '<pre>';
                print_r($invoice_name);*/
              if(!empty($invoice_name))
              {
              ?>
                          <a href="<?php echo base_url();?>uploads/examinvoice/user/<?php echo $invoice_name[0]['invoice_image'];?>" target="_blank">Invoice</a>
                         
                        <?php  }else
            {
              echo "-"; 
            }
            }elseif(@$row3['UTR_no']){
                          $this->db->where('invoice_image!=',""); // status = 0 for FAILURE
              $this->db->where('transaction_no',$row3['UTR_no']);
                   $invoice_name = $this->master_model->getRecords('exam_invoice');
                            if(!empty($invoice_name))
              {
              ?>
                         <!-- <a href="<?php echo base_url();?>uploads/bulkexaminvoice/user/<?php //echo $invoice_name[0]['invoice_image'];?>" target="_blank">Invoice</a>-->
                         
                        <?php  echo 'Bulk Exam Payment'; }else
            {
              echo "-"; 
            }?>

            <?php }else{ echo "-"; } ?>
                    </td>
              
                 </tr>
                 <?php }}else{ ?>
                 <tr><td colspan="13" align="center">No records found...</td></tr>
                 <?php } ?>                                    
                </tbody>
              </table>
             </div> 
             <?php } ?> 
             
<!--added by chaitali on 2021-09-06 -->
             <?php if(count($non_member_csc)){ ?>     
            <center><h4>Membership Transaction Details</h4></center>
          <div class="table-responsive">
              <table id="" class="table table-bordered table-striped">
                <thead>
                <tr style="background-color:#00c0ef;color:#fff;">
                  <th>Membership No.<br />(Login ID)</th>
                  <th>Customer Id</th>
                  <th>Exam Code</th>
                  <th>Exam Period</th>
                  <th>Exam Name</th>
                  <th>Amount</th>
                  <th>Transaction No</th>
                  <th>Payment Status</th>
                  <th>Auth.Code</th>
                  <th>Transaction Time</th>
                  <!--<th>Send Mail</th>
                  <th>View Receipt</th> -->
                   <th>View Invoice</th>
                </tr>
                </thead>
                <tbody class="no-bd-y" id="">
      <?php /*if($non_member_csc[0]['status'] == ''){?>

                 <tr><td colspan="13" align="center">No records found...</td></tr>

       <?php } */ ?>
                 <?php /*if(count($non_member_csc)){
          foreach($non_member_csc as $res)
          {
          ?>
          <tr>
          <td><?php echo 'in'.$res[0]['member_regnumber']; ?></td>
          </tr>
          <?php }
        }*/

      if(count($non_member_csc)){
            foreach($non_member_csc as $memberdata){  ?>
                 <tr>
                  <td><?php echo $memberdata[0]['member_regnumber'];?></td>
                    <td><?php echo $memberdata[0]['receipt_no'];?></td>
                    <td><?php echo $memberdata[0]['exam_code'];?></td>
                    <td><?php echo $memberdata[0]['exam_period'];?></td>
                    <td><?php echo $memberdata[0]['description'];?></td>
                    <td><?php echo $memberdata[0]['amount'];?></td>
                    <td><?php echo $memberdata[0]['transaction_no'];?></td>
                    <td>
          <?php //echo $icard['transaction_details']; ?>
                    <?php 
              if($memberdata[0]['status'] == '0')
              { $status = "Failure"; $color = "Red";  }
              else if($memberdata[0]['status'] == '1')
              { $status = "Success"; $color = "Green";  }
              else if($memberdata[0]['status'] == '2')
              { $status = "Pending"; $color = "Red";  }
                else if($memberdata[0]['status'] == '3')
              { $status = "Refund"; $color = "sky blue"; }
        else if($memberdata[0]['status'] == '7')
              { $status = "Trn cancel no response from billdesk T-2Days"; $color = "Red"; }
            /*   else
              { $status = "Pending"; $color = "Red";    } */
            ?>
                                    
                      <strong><font color="<?php echo $color; ?>"><?php echo $status; ?></font> <br><hr size="1/">
                        
                        <?php if($memberdata[0]['transaction_details']){ ?> Trans Details: <?php } ?></strong>
                        <br>      
            <?php echo $memberdata[0]['transaction_details'];?>
                    
                    </td>
                    <td><?php echo $memberdata[0]['auth_code'];?></td>
                    <td><?php if($memberdata[0]['date']!=''){echo $memberdata[0]['date'];}?></td>
                   <!-- <td><?php if($memberdata[0]['status']==1){?><a href="<?php echo base_url();?>admin/Report/dup_icard_mail/<?php echo base64_encode($memberdata[0]['transaction_no']); ?>/<?php echo base64_encode($memberdata[0]['regnumber']); ?>" onclick="return confirm('Do you want to send transaction mail?');">Send Mail</a><?php } else { echo "-";}?></td>
                    <td>
                      <?php if($memberdata[0]['transaction_no']){?>
                          <a href="<?php echo base_url();?>admin/Report/id_receipt/<?php echo base64_encode($memberdata[0]['transaction_no']); ?>/<?php echo base64_encode($memberdata[0]['regnumber']); ?>" target="_blank">Receipt</a>
                        <?php } else{ echo "-"; } ?>
                    </td> -->

    

                  <td>
                      <?php if($memberdata[0]['transaction_no']){
                  $this->db->where('invoice_image!=',""); // status = 0 for FAILURE
              $this->db->where('transaction_no',$memberdata[0]['transaction_no']);
                   $invoice_name = $this->master_model->getRecords('exam_invoice');
                //print_r($invoice_name);
              if(!empty($invoice_name))
              {
              ?>
                          <a href="<?php echo base_url();?>/uploads/examinvoice/user/<?php echo $invoice_name[0]['invoice_image'];?>" target="_blank">Invoice</a>
                         
                        <?php  }else
            {
              echo "-"; 
            }
            }elseif(@$memberdata[0]['UTR_no']){
                $this->db->where('invoice_image!=',""); // status = 0 for FAILURE
              $this->db->where('transaction_no',$memberdata[0]['UTR_no']);
                   $invoice_name = $this->master_model->getRecords('exam_invoice');
                            if(!empty($invoice_name))
              {
              ?>
                          <!--<a href="<?php echo base_url();?>uploads/bulkexaminvoice/user/<?php echo $invoice_name[0]['invoice_image'];?>" target="_blank">Invoice</a>-->
                         
                        <?php echo 'Bulk Exam Payment'; }else
            {
              echo "-"; 
            }?>

            <?php }else{ echo "-"; } ?>
                    </td>
               
                 </tr>
                 <?php }}else{ ?>

                 <tr><td colspan="13" align="center">No records found...</td></tr>

                 <?php }  ?> 
                                   
                </tbody>
              </table>
             </div> 
             <?php }else{ ?> 
<tr><td colspan="13" align="center">No records found...</td></tr>

                 <?php } ?> 

<!-- end -->
             
            <?php if(count($duplicate_icard)){ ?>
            <center><h4>Transaction Details - Duplicate i-card Payment</h4></center>
          <div class="table-responsive">
              <table id="" class="table table-bordered table-striped">
                <thead>
                <tr style="background-color:#00c0ef;color:#fff;">
                  <th>Membership No.<br />(Login ID)</th>
                  <th>Customer Id</th>
                  <th>Amount</th>
                  <th>Transaction No</th>
                  <th>Payment Status</th>
                  <th>Auth.Code</th>
                  <th>Transaction Time</th>
                  <th>Send Mail</th>
                  <th>View Receipt</th>
                   <th>View Invoice</th>
                </tr>
                </thead>
                <tbody class="no-bd-y" id="">
                 <?php if(count($duplicate_icard)){
            foreach($duplicate_icard as $icard){   ?>
                 <tr>
                  <td><?php echo $icard['regnumber'];?></td>
                    <td><?php echo $icard['receipt_no'];?></td>
                    <td><?php echo $icard['amount'];?></td>
                    <td><?php echo $icard['transaction_no'];?></td>
                    <td>
          <?php /*echo $icard['transaction_details'];*/ ?>
                    <?php 
              if($icard['status'] == 0)
              { $status = "Failure"; $color = "Red";  }
              else if($icard['status'] == 1)
              { $status = "Success"; $color = "Green";  }
              else if($icard['status'] == 2)
              { $status = "Pending"; $color = "Red";  }
                else if($row3['status'] == 3)
              { $status = "Refund"; $color = "Red"; }
          else if($row3['status'] == 7)
              { $status = "Trn cancel no response from billdesk T-2Days"; $color = "Red"; }
              else
              { $status = "Failure"; $color = "Red";    }
            ?>
                                    
                      <strong><font color="<?php echo $color; ?>"><?php echo $status; ?></font> <br><hr size="1/">
                        
                        <?php if($icard['transaction_details']){ ?> Trans Details: <?php } ?></strong>
                        <br>      
            <?php echo $icard['transaction_details'];?>
                    
                    </td>
                    <td><?php echo $icard['auth_code'];?></td>
                    <td><?php if($icard['date']!=''){echo $icard['date'];}?></td>
                    <td><?php if($icard['status']==1){?><a href="<?php echo base_url();?>admin/Report/dup_icard_mail/<?php echo base64_encode($icard['transaction_no']); ?>/<?php echo base64_encode($icard['regnumber']); ?>" onclick="return confirm('Do you want to send transaction mail?');">Send Mail</a><?php } else { echo "-";}?></td>
                    <td>
                      <?php if($icard['transaction_no']){?>
                          <a href="<?php echo base_url();?>admin/Report/id_receipt/<?php echo base64_encode($icard['transaction_no']); ?>/<?php echo base64_encode($icard['regnumber']); ?>" target="_blank">Receipt</a>
                        <?php } else{ echo "-"; } ?>
                    </td>

    

                  <td>
                      <?php if($icard['transaction_no']){
                  $this->db->where('invoice_image!=',""); // status = 0 for FAILURE
              $this->db->where('transaction_no',$icard['transaction_no']);
                   $invoice_name = $this->master_model->getRecords('exam_invoice');
                //print_r($invoice_name);
              if(!empty($invoice_name))
              {
              ?>
                          <a href="<?php echo base_url();?>/uploads/dupicardinvoice/user/<?php echo $invoice_name[0]['invoice_image'];?>" target="_blank">Invoice</a>
                         
                        <?php  }else
            {
              echo "-"; 
            }
            }elseif(@$icard['UTR_no']){
                $this->db->where('invoice_image!=',""); // status = 0 for FAILURE
              $this->db->where('transaction_no',$icard['UTR_no']);
                   $invoice_name = $this->master_model->getRecords('exam_invoice');
                            if(!empty($invoice_name))
              {
              ?>
                          <!--<a href="<?php echo base_url();?>uploads/bulkexaminvoice/user/<?php echo $invoice_name[0]['invoice_image'];?>" target="_blank">Invoice</a>-->
                         
                        <?php echo 'Bulk Exam Payment'; }else
            {
              echo "-"; 
            }?>

            <?php }else{ echo "-"; } ?>
                    </td>
               
                 </tr>
                 <?php }}else{ ?>
                 <tr><td colspan="13" align="center">No records found...</td></tr>
                 <?php } ?>                                    
                </tbody>
              </table>
             </div> 
             <?php } ?> 
            
             <?php if(count($renewal_details)){ ?>
            <center><h4>Transaction Details - Member Renewal Details</h4></center>
          <div class="table-responsive">
              <table id="" class="table table-bordered table-striped">
                <thead>
                <tr style="background-color:#00c0ef;color:#fff;">
                  <th>Membership No.<br />(Login ID)</th>
                  <th>Customer Id</th>
                  <th>Amount</th>
                  <th>Transaction No</th>
                  <th>Payment Status</th>
                  <th>Auth.Code</th>
                  <th>Transaction Time</th>
                  <th>Send Mail</th>
                  <th>View Receipt</th>
                   <th>View Invoice</th>
                </tr>
                </thead>
                <tbody class="no-bd-y" id="">
                 <?php if(count($renewal_details)){
            foreach($renewal_details as $renewal){ 
              ?>
                 <tr>
                  <td><?php echo $renewal['regnumber'];?></td>
                    <td><?php echo $renewal['receipt_no'];?></td>
                    <td><?php echo $renewal['amount'];?></td>
                    <td><?php echo $renewal['transaction_no'];?></td>
                    <td>
          <?php /*echo $icard['transaction_details'];*/ ?>
                    <?php 
              if($renewal['status'] == 0)
              { $status = "Failure"; $color = "Red";  }
              else if($renewal['status'] == 1)
              { $status = "Success"; $color = "Green";  }
              else if($renewal['status'] == 2)
              { $status = "Pending"; $color = "Red";  }
                else if($renewal['status'] == 3)
              { $status = "Refund"; $color = "Red"; }
      else if($renewal['status'] == 7)
              { $status = "Trn cancel no response from billdesk T-2Days"; $color = "Red"; }
              else
              { $status = "Failure"; $color = "Red";    }
            ?>
                                    
                      <strong><font color="<?php echo $color; ?>"><?php echo $status; ?></font> <br><hr size="1/">
                        
                        <?php if($renewal['transaction_details']){ ?> Trans Details: <?php } ?></strong>
                        <br>      
            <?php echo $renewal['transaction_details'];?>
                    
                    </td>
                    <td><?php echo $renewal['auth_code'];?></td>
                    <td><?php if($renewal['date']!=''){echo $renewal['date'];}?></td>
                    <td><?php if($renewal['status']==1){?><a href="<?php echo base_url();?>admin/Report/dup_icard_mail/<?php echo base64_encode($renewal['transaction_no']); ?>/<?php echo base64_encode($renewal['regnumber']); ?>" onclick="return confirm('Do you want to send transaction mail?');">Send Mail</a><?php } else { echo "-";}?></td>
                    <td>
                      <?php if($renewal['transaction_no']){?>
                          <a href="<?php echo base_url();?>admin/Report/id_receipt/<?php echo base64_encode($renewal['transaction_no']); ?>/<?php echo base64_encode($renewal['regnumber']); ?>" target="_blank">Receipt</a>
                        <?php } else{ echo "-"; } ?>
                    </td>

    

                  <td>
                      <?php if($renewal['transaction_no']){
                  $this->db->where('invoice_image!=',""); // status = 0 for FAILURE
              $this->db->where('transaction_no',$renewal['transaction_no']);
                   $invoice_name = $this->master_model->getRecords('exam_invoice');
                //print_r($invoice_name);
              if(!empty($invoice_name))
              {
              ?>
                          <a href="<?php echo base_url();?>/uploads/dupicardinvoice/user/<?php echo $invoice_name[0]['invoice_image'];?>" target="_blank">Invoice</a>
                         
                        <?php  }else
            {
              echo "-"; 
            }
            }elseif(@$renewal['UTR_no']){
                $this->db->where('invoice_image!=',""); // status = 0 for FAILURE
              $this->db->where('transaction_no',$renewal['UTR_no']);
                   $invoice_name = $this->master_model->getRecords('exam_invoice');
                            if(!empty($invoice_name))
              {
              ?>
                          <!--<a href="<?php echo base_url();?>uploads/bulkexaminvoice/user/<?php echo $invoice_name[0]['invoice_image'];?>" target="_blank">Invoice</a>-->
                         
                        <?php echo 'Bulk Exam Payment'; }else
            {
              echo "-"; 
            }?>

            <?php }else{ echo "-"; } ?>
                    </td>
               
                 </tr>
                 <?php } }else{ ?>
                 <tr><td colspan="13" align="center">No records found...</td></tr>
                 <?php } ?>                                    
                </tbody>
              </table>
             </div> 
             <?php } ?>
       
       <?php if(count($contact_classes)){ ?>
            <center><h4>Transaction Details - Contact Class Payment</h4></center>
          <div class="table-responsive">
              <table id="" class="table table-bordered table-striped">
                <thead>
                <tr style="background-color:#00c0ef;color:#fff;">
                  <th>Membership No.<br />(Login ID)</th>
                  <th>Customer Id</th>
                  <th>Amount</th>
                  <th>Transaction No</th>
                  <th>Payment Status</th>
                  <th>Auth.Code</th>
                  <th>Transaction Time</th>
                  <th>Send Mail</th>
                  <th>View Receipt</th>
                   <th>View Invoice</th>
                </tr>
                </thead>
                <tbody class="no-bd-y" id="">
                 <?php if(count($contact_classes)){
            foreach($contact_classes as $contact_classes){   ?>
                 <tr>
                  <td><?php echo $contact_classes['regnumber'];?></td>
                    <td><?php echo $contact_classes['receipt_no'];?></td>
                    <td><?php echo $contact_classes['amount'];?></td>
                    <td><?php echo $contact_classes['transaction_no'];?></td>
                    <td>
          <?php /*echo $icard['transaction_details'];*/ ?>
                    <?php 
              if($contact_classes['status'] == 0)
              { $status = "Failure"; $color = "Red";  }
              else if($contact_classes['status'] == 1)
              { $status = "Success"; $color = "Green";  }
              else if($contact_classes['status'] == 2)
              { $status = "Pending"; $color = "Red";  }
                else if($contact_classes['status'] == 3)
              { $status = "Refund"; $color = "Red"; }
      else if($contact_classes['status'] == 7)
              { $status = "Trn cancel no response from billdesk T-2Days"; $color = "Red"; }
              else
              { $status = "Failure"; $color = "Red";    }
            ?>
                                    
                      <strong><font color="<?php echo $color; ?>"><?php echo $status; ?></font> <br><hr size="1/">
                        
                        <?php if($contact_classes['transaction_details']){ ?> Trans Details: <?php } ?></strong>
                        <br>      
            <?php echo $contact_classes['transaction_details'];?>
                    
                    </td>
                    <td><?php echo $contact_classes['auth_code'];?></td>
                    <td><?php if($contact_classes['date']!=''){echo $contact_classes['date'];}?></td>
                    <td><?php if($contact_classes['status']==1){?><a href="<?php echo base_url();?>admin/Report/contact_classes_mail/<?php echo base64_encode($contact_classes['transaction_no']); ?>/<?php echo base64_encode($contact_classes['regnumber']); ?>" onclick="return confirm('Do you want to send transaction mail?');">Send Mail</a><?php } else { echo "-";}?></td>
                    <td>
                      <?php if($contact_classes['transaction_no']){?>
                          <a href="<?php echo base_url();?>admin/Report/contactclass_receipt/<?php echo base64_encode($contact_classes['transaction_no']); ?>/<?php echo base64_encode($contact_classes['regnumber']); ?>" target="_blank">Receipt</a>
                        <?php } else{ echo "-"; } ?>
                    </td>

    

                  <td>
                      <?php if($contact_classes['transaction_no']){
                  $this->db->where('invoice_image!=',""); // status = 0 for FAILURE
              $this->db->where('transaction_no',$contact_classes['transaction_no']);
                   $invoice_name = $this->master_model->getRecords('exam_invoice');
                //print_r($invoice_name);
              if(!empty($invoice_name))
              {
        $zone=  substr($invoice_name[0]['invoice_image'], 2, 2);
           
              ?>
                          <a href="<?php echo base_url();?>/uploads/contact_classes_invoice/user/<?php echo $zone;?>/<?php echo $invoice_name[0]['invoice_image'];?>" target="_blank">Invoice</a>
                         
                        <?php  }else
            {
              echo "-"; 
            }
            }elseif(@$contact_classes['UTR_no']){
                $this->db->where('invoice_image!=',""); // status = 0 for FAILURE
              $this->db->where('transaction_no',$contact_classes['UTR_no']);
                   $invoice_name = $this->master_model->getRecords('exam_invoice');
                            if(!empty($invoice_name))
              {
              ?>
                          <!--<a href="<?php echo base_url();?>uploads/bulkexaminvoice/user/<?php echo $invoice_name[0]['invoice_image'];?>" target="_blank">Invoice</a>-->
                         
                        <?php echo 'Bulk Exam Payment'; }else
            {
              echo "-"; 
            }?>

            <?php }else{ echo "-"; } ?>
                    </td>
               
                 </tr>
                 <?php }}else{ ?>
                 <tr><td colspan="13" align="center">No records found...</td></tr>
                 <?php } ?>                                    
                </tbody>
              </table>
             </div> 
             <?php } ?> 
            <!-- <div id="links" class="" style="float:right;"><?php //echo $links; ?></div>-->
            <!-- <div id="links" class="dataTables_paginate paging_simple_numbers">
               
               </div>-->
                 <?php if(count($duplicate_certi)){ ?>
            <center><h4>Transaction Details - Duplicate Certificate Payment</h4></center>
          <div class="table-responsive">
              <table id="" class="table table-bordered table-striped">
                <thead>
                <tr style="background-color:#00c0ef;color:#fff;">
                  <th>Membership No.<br />(Login ID)</th>
                  <th>Customer Id</th>
                  <th>Amount</th>
                  <th>Transaction No</th>
                  <th>Payment Status</th>
                  <th>Auth.Code</th>
                  <th>Transaction Time</th>
                  <th>Send Mail</th>
                  <th>View Receipt</th>
                   <th>View Invoice</th>
                </tr>
                </thead>
                <tbody class="no-bd-y" id="">
                 <?php if(count($duplicate_certi)){
            foreach($duplicate_certi as $icard){   ?>
                 <tr>
                  <td><?php echo $icard['regnumber'];?></td>
                    <td><?php echo $icard['receipt_no'];?></td>
                    <td><?php echo $icard['amount'];?></td>
                    <td><?php echo $icard['transaction_no'];?></td>
                    <td>
          <?php /*echo $icard['transaction_details'];*/ ?>
                    <?php 
              if($icard['status'] == 0)
              { $status = "Failure"; $color = "Red";  }
              else if($icard['status'] == 1)
              { $status = "Success"; $color = "Green";  }
              else if($icard['status'] == 2)
              { $status = "Pending"; $color = "Red";  }
                else if($row3['status'] == 3)
              { $status = "Refund"; $color = "Red"; }
      else if($row3['status'] == 7)
              { $status = "Trn cancel no response from billdesk T-2Days"; $color = "Red"; }
              else
              { $status = "Failure"; $color = "Red";    }
            ?>
                                    
                      <strong><font color="<?php echo $color; ?>"><?php echo $status; ?></font> <br><hr size="1/">
                        
                        <?php if($icard['transaction_details']){ ?> Trans Details: <?php } ?></strong>
                        <br>      
            <?php echo $icard['transaction_details'];?>
                    
                    </td>
                    <td><?php echo $icard['auth_code'];?></td>
                    <td><?php if($icard['date']!=''){echo $icard['date'];}?></td>
                    <td><?php if($icard['status']==1){?><a href="<?php echo base_url();?>admin/Report/dup_icard_mail/<?php echo base64_encode($icard['transaction_no']); ?>/<?php echo base64_encode($icard['regnumber']); ?>" onclick="return confirm('Do you want to send transaction mail?');">Send Mail</a><?php } else { echo "-";}?></td>
                    <td>
                      <?php if($icard['transaction_no']){?>
                          <a href="<?php echo base_url();?>admin/Report/id_receipt/<?php echo base64_encode($icard['transaction_no']); ?>/<?php echo base64_encode($icard['regnumber']); ?>" target="_blank">Receipt</a>
                        <?php } else{ echo "-"; } ?>
                    </td>

    

                  <td>
                      <?php if($icard['transaction_no']){
                  $this->db->where('invoice_image!=',""); // status = 0 for FAILURE
              $this->db->where('transaction_no',$icard['transaction_no']);
                   $invoice_name = $this->master_model->getRecords('exam_invoice');
                //print_r($invoice_name);
              if(!empty($invoice_name))
              {
              ?>
                          <a href="<?php echo base_url();?>/uploads/dupicardinvoice/user/<?php echo $invoice_name[0]['invoice_image'];?>" target="_blank">Invoice</a>
                         
                        <?php  }else
            {
              echo "-"; 
            }
            }elseif(@$icard['UTR_no']){
                $this->db->where('invoice_image!=',""); // status = 0 for FAILURE
              $this->db->where('transaction_no',$icard['UTR_no']);
                   $invoice_name = $this->master_model->getRecords('exam_invoice');
                            if(!empty($invoice_name))
              {
              ?>
                          <!--<a href="<?php echo base_url();?>uploads/bulkexaminvoice/user/<?php echo $invoice_name[0]['invoice_image'];?>" target="_blank">Invoice</a>-->
                         
                        <?php echo 'Bulk Exam Payment'; }else
            {
              echo "-"; 
            }?>

            <?php }else{ echo "-"; } ?>
                    </td>
               
                 </tr>
                 <?php }}else{ ?>
                 <tr><td colspan="13" align="center">No records found...</td></tr>
                 <?php } ?>                                    
                </tbody>
              </table>
             </div> 
             <?php } ?> 


            </div>
          </div>
        </div>
      </div>
      
    </section>
   
  </div>
  
<!-- Print Content -->

<div class="content-wrapper" id="print_div" style="display: none;">
<!-- Content Header (Page header) -->
    <div  style=" background: #fff;border: 1px solid #000; padding:10px; width:100%;">
        <table width="90%" cellspacing="0" cellpadding="10" border="0" align="center" >         
          <tr> <td colspan="4" align="left">&nbsp;</td> </tr>
            <tr>
            
                <td colspan="4" align="center" height="25"> 
                    <span id="1001a1" class="alert">
                    </span>
                </td>
            </tr>
        
            <tr> 
                <td colspan="4"  height="1"><img src="<?php echo base_url()?>assets/images/logo1.png" class="img"></td>
            </tr>
            <tr> 
                <td colspan="4"  height="1" align="center" color="red"><h4><font color="red">Member Details</font></h4><br /></td>
            </tr>
            <tr colspan="4">
              <table id="" class="table table-bordered table-striped ">
                <thead>
                <tr style="background-color:#00c0ef;color:#fff;">
                  <th>Membership No.<br />(Login ID)</th>
                  <th>Employer ID</th>
                  <th>Name</th>
                  <th>Member Type</th>
                  <th>Password</th>
                  <th>Send Mail</th>
                </tr>
                </thead>
                <tbody class="no-bd-y" id="">
                <?php if(count($member_details)){
            foreach($member_details as $row1){   ?>
                 <tr>
                  <td><?php echo $row1['regnumber'];?></td>
                  <td><?php 
                  if($row1['emp_id']){echo $row1['emp_id'];}else{ echo $row1['bank_emp_id']; } /*ADDED BY POOJA MANE 18-05-2023*/
                  ?></td>
                    <td><?php echo $row1['firstname']." ".$row1['lastname'];?></td>
                    <td><?php echo $row1['registrationtype'];?></td>
                    <td><?php echo $row1['usrpassword'];?></td>
                    <td>
                      <?php if($row1['regnumber']) { ?>
                          Send Password
                        <?php }else { echo "Incomplete <br> Transaction"; } ?>
                    </td>
                 </tr>
                 <?php }}else{ ?>
                 <tr><td colspan="4" align="center">No records found...</td></tr>
                 <?php } ?>                   
                </tbody>
              </table>
            </tr>

            <tr> 
                <td colspan="4"  height="1" align="center"><center><h4><font color="red">Transaction Details - Membership Payment</font></h4></center></td>
            </tr>
    
            <table id="" class="table table-bordered table-striped">
                <thead>
                <tr style="background-color:#00c0ef;color:#fff;">
                  <th>Membership No.<br />(Login ID)</th>
                  <th>Tran.No</th>
                  <th>Exam Fees </th>
                  <th>Payment Status</th>
                  <th>Authentication Code</th>
                  <th>Transaction Time</th>
                     <th>View Invoice </th>
                </tr>
                </thead>
                <?php if(count($member)){ ?>
                <tbody class="no-bd-y" id="list">
                <?php foreach($member as $mempr){ ?>
                  <tr>
                    <td><?php echo $mempr['regnumber'];?></td>
                    <td><?php echo $mempr['transaction_no'];?></td>
          <?php if($mempr['transaction_no']){?>
           <td><?php echo $mempr['amount'];?></td>
          <?php } elseif($mempr['UTR_no']) { ?>
                    <td><?php echo $mempr['exam_fee'];?></td>
          <?php } ?>
                    <td>
                      <?php   if($mempr['status'] == 0)
                { $status = "Failure"; $color = "Red";  }
                else if($mempr['status'] == 1)
                { $status = "Success"; $color = "Green";  }
                else if($mempr['status'] == 2)
                { $status = "Pending"; $color = "Red";  }
                else if($row3['status'] == 3)
              { $status = "Refund"; $color = "Red"; }
      else if($row3['status'] == 7)
              { $status = "Trn cancel no response from billdesk T-2Days"; $color = "Red"; }
              else
              { $status = "Failure"; $color = "Red";    }
            ?>
                                    
                      <strong><font color="<?php echo $color; ?>"><?php echo $status; ?></font> <br><hr size="1/">
                        <?php if($mempr['transaction_details']){ ?> Trans Details: <?php } ?></strong>
                        <br>      
            <?php echo $mempr['transaction_details'];?>
                    </td>
                    <td><?php echo $mempr['auth_code'];?></td>
                    <td><?php if($mempr['date']!=''){echo date('d-M-y H:i:s',strtotime($mempr['date']));}?></td>
                    
                        
                    </tr>
                <?php } ?>
                </tbody>
                <?php } ?>
              </table>
            
        <tr> 
                <td colspan="4"  height="1" align="center"><center><h4><font color="red">Transaction Details - Exam Payment</font></h4></center></td>
            </tr>
            <tr colspan="4">
                <table class="table table-bordered table-striped" style="width:95%;" >
                    <thead>
                        <tr style="background-color:#00c0ef;color:#fff;">
                            <th>Membership No.<br />(Login ID)</th>
                            <th>Customer Id</th>
                            <th>Exam Name</th>
                            <th>Exam Fees</th>
                            <th>Exam Mode</th>
                            <th>Exam Medium</th>
                            <th>Center Name</th>
                            <th>Transaction No</th>
                            <th>Payment Status</th>
                            <th>Auth.Code</th>
                            <th>Transaction Time</th>
                         </tr>
                    </thead>
                    <tbody class="no-bd-y" id="print_list">
                      <?php 
             if(count($exams)){
                foreach($exams as $row3){  ?>
             <tr>
              <td><?php echo $row3['regnumber'];?></td>
              <td><?php echo $row3['receipt_no'];?></td>
              <td><?php echo $row3['description'];?></td>
              <td><?php echo $row3['exam_fee'];?></td>
              <td><?php if($row3['exam_mode'] == 'ON'){echo "Online";}else{ echo "Offline";}?></td>
              <td><?php echo $row3['exam_medium'];?></td>
              <td><?php echo $row3['center_name'];?></td>
              <td><?php echo $row3['transaction_no'];?></td>
              <!--<td><?php echo $row3['transaction_details'];?></td>-->
              <td>
                <?php 
                  if($row3['status'] == 0)
                  { $status = "Failure"; $color = "Red";  }
                  else if($row3['status'] == 1)
                  { $status = "Success"; $color = "Green";  }
                  else if($row3['status'] == 2)
                  { $status = "Pending"; $color = "Red";  }
                  else if($row3['status'] == 3)
              { $status = "Refund"; $color = "Red"; }
      else if($row3['status'] == 7)
              { $status = "Trn cancel no response from billdesk T-2Days"; $color = "Red"; }
              else
              { $status = "Failure"; $color = "Red";    }
                ?>
                      
                <strong><font color="<?php echo $color; ?>"><?php echo $status; ?></font> <br><hr size="1/">
                
                <?php if(isset($row3[0]['transaction_details'])){ ?> Trans Details: <?php } ?></strong>
                <br>      
                <?php if(isset($row3[0]['transaction_details'])){echo $row3[0]['transaction_details'];}?>
              </td>
              <td><?php echo $row3['auth_code'];?></td>
              <td><?php if($row3['date']!='') echo date('d-M-y H:i:s',strtotime($row3['date']));?></td>
            </tr>
             <?php }}else{ ?>
             <tr><td colspan="13" align="center">No records found...</td></tr>
             <?php } ?>       
                    </tbody>
                </table>
            </tr>
            <tr> 
                <td colspan="4"  height="1" align="center"><center><h4><font color="red">Transaction Details - Duplicate i-card Payment</font></h4></center></td>
            </tr>
            <tr colspan="4">
              <table id="" class="table table-bordered table-striped">
                <thead>
                <tr style="background-color:#00c0ef;color:#fff;">
                  <th>Membership No.<br />(Login ID)</th>
                  <th>Customer Id</th>
                  <th>Amount</th>
                  <th>Transaction No</th>
                  <th>Payment Status</th>
                  <th>Auth.Code</th>
                  <th>Transaction Time</th>
                </tr>
                </thead>
                <tbody class="no-bd-y" id="">
                 <?php if(count($duplicate_icard)){
            foreach($duplicate_icard as $icard){   ?>
                 <tr>
                  <td><?php echo $icard['regnumber'];?></td>
                    <td><?php echo $icard['receipt_no'];?></td>
                    <td><?php echo $icard['amount'];?></td>
                    <td><?php echo $icard['transaction_no'];?></td>
                    <td>
          <?php /*echo $icard['transaction_details'];*/ ?>
                    <?php 
              if($icard['status'] == 0)
              { $status = "Failure"; $color = "Red";  }
              else if($icard['status'] == 1)
              { $status = "Success"; $color = "Green";  }
              else if($icard['status'] == 2)
              { $status = "Pending"; $color = "Red";  }
              else if($row3['status'] == 3)
              { $status = "Refund"; $color = "Red"; }
      else if($row3['status'] == 7)
              { $status = "Trn cancel no response from billdesk T-2Days"; $color = "Red"; }
              else 
              { $status = "Failure"; $color = "Red";    }
            ?>
                                    
                      <strong><font color="<?php echo $color; ?>"><?php echo $status; ?></font> <br><hr size="1/">
                        
                        <?php if($icard['transaction_details']){ ?> Trans Details: <?php } ?></strong>
                        <br>      
            <?php echo $icard['transaction_details'];?>
                    
                    </td>
                    <td><?php echo $icard['auth_code'];?></td>
                    <td><?php if($icard['date']!=''){echo $icard['date'];}?></td>
                 </tr>
                 <?php }}else{ ?>
                 <tr><td colspan="13" align="center">No records found...</td></tr>
                 <?php } ?>                                    
                </tbody>
              </table>
            </tr>
        </table>
    </div>
</div>
<!-- Print Content End -->
  
<!-- Data Tables -->
<div class="modal fade" id="paymentDetails" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h3 class="modal-title"> Payment Details From billdesk</h3>
        </div>
        <div class="modal-body" style="border-top: 1px solid skyblue;">
          <p>
          <div class="col-md-12"><label class="col-md-3">Status</label><div class="col-md-8 showStatus"></div></div>

            <div class="col-md-12"><label class="col-md-3">ID</label><div class="col-md-8 showId"></div></div>
            <div class="col-md-12"><label class="col-md-3">Date</label><div class="col-md-8 showDate"></div></div>
            <div class="col-md-12"><label class="col-md-3">Amount</label><div class="col-md-8 showAmount"></div></div>

          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div> 
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
  $('#searchExamDetails').parsley('validate');
</script>

<script src="<?php echo base_url()?>js/js-paginate.js"></script>
<script type="text/javascript">
$(document).ready(function() 
{
  
});

  $( ".getPaymentDetails" ).each(function() {
    var postForm = { //Fetch form data
            'receipt_no'     : $(this).attr('receipt_no') //Store name fields value
        };
  $(this).click(function() {
    $.ajax({
        url: "<?php echo base_url(); ?>/payment_details/get_payment_details",
        type: "post",
        data: postForm ,
        success: function (response) {
          var json = $.parseJSON(response);
         // alert(json.amount);
          $('.showStatus').html(json.status);
          $('.showId').html(json.id);
          $('.showDate').html(json.date);
          $('.showAmount').html(json.amount);
          $("#paymentDetails").modal();
           // You will get response from your PHP page (what you echo or print)
        },
        error: function(jqXHR, textStatus, errorThrown) {
         //  console.log(textStatus, errorThrown);
        }
    });
  });
});

$( ".sendMailBtn" ).each(function() {
  $(this).click(function(){
    sendMailFunc($(this).attr('transaction_no')) ;
  });
});
function sendMailFunc (transaction_no) {
$.ajax({
    url: '<?php echo base_url() ?>admin/Report/sendAcknowledgeMail?transaction_no='+transaction_no,
    type: 'GET', 
  //  dataType:"json",
   // data: {field : searchBy, value : searchkey },
    success: function(res) {
      if(res=='ok')
      {
        alert('Mail sent successfully');
      }
      else
      alert('Something went wrong');
    }
  });
}

function confirmMailSend()
{
  if(confirm("Do you want to re-send registration mail?"))
  {
    return true;  
  }
  else
  {
    return false;
  }
    
}

/*function printContent(searchBy,searchkey)
{
  var base_url = '<?php echo base_url(); ?>';
  $.ajax({
    url: base_url+'admin/Report/getExamDetailsToPrint',
    type: 'POST',
    dataType:"json",
    data: {field : searchBy, value : searchkey },
    success: function(res) {
      if(res)
      {
        if(res.success == 'Success')
        {
          var content = '';
          for(i=0;i<res.result.length;i++)
          {
            var resultrow = res.result[i].firstname;
            //alert(resultrow);
            var index = i+1;
            content += '<tr><td>'+index+'</td><td>'+res.result[i].regnumber+'</td><td>'+res.result[i].firstname+'</td><td>'+res.result[i].gender+'</td><td>'+res.result[i].description+'</td><td>'+res.result[i].exam_fee+'</td><td>'+res.result[i].medium_description+'</td><td>'+res.result[i].center_name+'</td><td>'+res.result[i].transaction_no+'</td><td>'+res.result[i].transaction_details+'</td><td>'+res.result[i].date+'</td></tr>';
          }
          $("#print_list").html(content);
          $("#printBtn").show();
        }
        else
          $("#printBtn").hide();
      }
      else
        $("#printBtn").hide();
    }
  });
}*/

$(function () {
  //$("#listitems").DataTable();
  //$("#regDetails").DataTable();
});
    
</script>

<script>
function printDiv(divName) {
  
     var printContents = document.getElementById('print_div').innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>
 

<?php $this->load->view('admin/includes/footer');?>