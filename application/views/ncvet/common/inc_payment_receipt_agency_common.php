<table class="table table-bordered custom_inner_tbl custom_inner_tbl_dark" style="max-width:600px; background:#FFFFCC; margin:0 auto;">
  <tbody>
    <tr>
      <td colspan='2'>
        <p>Dear Sir/Madam, <br /><br /> We acknowledge with thanks the receipt of the payment for NCVET Application as per the details given below:</p>
      </td>
    </tr>
    
    <tr><td colspan='2' class="text-center"><b>Payment details</b></td></tr>
    
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
      <td><strong>Customer ID:</strong></td>
      <td><?php echo $payment_data[0]['receipt_no']; ?></td>
    </tr>
    
    <tr>
      <td><strong>Transaction No:</strong></td>
      <td><?php if($payment_data[0]['gateway'] == 1) { echo $payment_data[0]['UTR_no']; } else { echo $payment_data[0]['transaction_no']; } ?></td>
    </tr>
    
    <tr>
      <td><strong>Amount:</strong></td>
      <td><?php echo $payment_data[0]['amount']; ?></td>
    </tr>
    
    <tr>
      <td><strong>Transaction Status: </strong></td>
      <td>
        <?php 
          if($payment_data[0]['status'] == 0) { echo "Failure"; } 
          else if($payment_data[0]['status'] == 1) { echo "Success"; } 
          else if($payment_data[0]['status'] == 2) { echo "Pending"; } 
          else if($payment_data[0]['status'] == 3) { echo "Payment Pending for Approval by IIBF"; } 
          else if($payment_data[0]['status'] == 4) { echo "Cancelled"; } 
        ?>
      </td>
    </tr>
    <tr>
      <td><strong>Transaction Date :</strong></td>
      <td><?php if($payment_data[0]['date'] != "" && $payment_data[0]['date'] != "0000-00-00 00:00:00") { echo date("Y-m-d", strtotime($payment_data[0]['date'])); } ?></td>
    </tr>

    <?php 
      $candidate_receipt_url = site_url('ncvet/agency/transaction_details_agency/payment_receipt_candidate_listing_agency/'.url_encode($payment_data[0]['id']));
      
      if($this->session->userdata('NCVET_USER_TYPE') == 'admin')
      {
        $candidate_receipt_url = site_url('ncvet/admin/transaction/transactions_details/'.url_encode($payment_data[0]['id']));
      } 
    ?>

    <tr>
      <td><strong>Candidate Receipts:</strong></td>
      <td><a target="_blank" href="<?php echo $candidate_receipt_url; ?>">View Candidates Receipt</a> &nbsp;&nbsp;&nbsp;</td>
    </tr>
    
    <tr>
      <td colspan='2'><b>Kindly note,<b><br />
        <ul>
          <li>The fees once paid will not be refunded.</li>
          <li>IIBF reserves the right to accept or reject your Enrollment in NCVET Application without assigning any reason what so ever.</li>
          <li>IIBF will process  application based on the information provided and in case, any of the information found false or incorrect your Enrollment in NCVET Application is liable for cancellation.</li>
        </ul>
      </td>
    </tr>
  </tbody>
</table>    