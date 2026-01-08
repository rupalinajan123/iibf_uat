<form action="<?php echo base_url() ?>iibfbcbf/agency/transaction_details_agency/make_online_payment_agency/<?php echo $enc_pt_id; ?>" method="post" class="form-horizontal" name="neft_pay_form" id="neft_pay_form" autocomplete="off">

<table class="table table-bordered custom_inner_tbl custom_inner_tbl_dark" style="max-width:600px; background:#FFFFCC; margin:0 auto;">
  <tbody>
     
    <tr><td colspan='2' class="text-center"><b>Payment details</b></td></tr>
    
    <tr>
      <td><strong>Application: </strong></td>
      <td><?php echo $payment_data[0]['description']; ?></td>
    </tr>

    <tr>
      <td><strong>Agency ID: </strong></td>
      <td><?php echo $payment_data[0]['agency_code']; ?></td>
    </tr>
    
    <tr>
      <td><strong>Agency Name : </strong></td>
      <td><?php echo $payment_data[0]['agency_name']; ?></td>
    </tr>
    
    <tr>
      <td><strong>Centre Name : </strong></td>
      <td><?php echo $payment_data[0]['centre_name']." (".$payment_data[0]['centre_username']." - ".$payment_data[0]['city_name'].")"; ?></td>
    </tr>
    
    <tr>
      <td><strong>Receipt No.:</strong></td>
      <td><?php echo $payment_data[0]['receipt_no']; ?></td>
    </tr>
     
    
    <tr>
      <td><strong>Amount to be paid:</strong></td>
      <td><?php echo $payment_data[0]['amount']; ?></td>
    </tr>
      
    
    <tr>
      <td colspan='2' style="text-align: center;"><a href="<?php echo site_url('iibfbcbf/agency/transaction_details_agency/make_online_payment_agency/'.$enc_pt_id); ?>" class="btn btn-primary">Proceed to Pay</a></td>
    </tr>

  </tbody>
</table> 

</form>   