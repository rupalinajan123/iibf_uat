<style>

  .skin-blue .main-header .navbar { background-color: #fff; }
  body.layout-top-nav .main-header h1 { color: #0699dd; margin-bottom: 0; margin-top: 30px; }
  .container { position: relative; }
  .box-header.with-border { background-color: #7fd1ea; border-top-left-radius: 0; border-top-right-radius: 0; margin-bottom: 10px; }
  .header_blue { background-color: #2ea0e2 !important; color: #fff !important; margin-bottom: 0 !important; }
  .box { border: none; box-shadow: none; border-radius: 0; margin-bottom: 0; }
  .nobg { background: none !important; border: none !important; }
  .box-title-hd { color: #3c8dbc; font-size: 16px; margin: 0; }
  .blue_bg { background-color: #e7f3ff; } 
  .m_t_15 { margin-top: 15px; }
  .main-footer { padding-left: 0px; padding-right: 0px; margin:0 auto !important; width: 95%;}
  .content-header > h1 { font-size: 22px; font-weight: 600; }
  h4 { margin-top: 5px; margin-bottom: 10px !important; font-size: 14px; line-height: 18px; padding: 0 5px; font-weight: 600; text-align: justify; }
  /* .form-horizontal .control-label { padding-top: 400px; } */
  .pad_top_2 { padding-top: 2px !important; }
  .pad_top_0 { padding-top: 0px !important; }
  /* div.form-group:nth-child(odd) { background-color: #dcf1fc; padding: 5px 0; } */
  #confirmBox { display: none; background-color: #eee; border-radius: 5px; border: 1px solid #aaa; position: fixed; width: 300px; left: 50%; margin-left: -150px; padding: 6px 8px 8px; box-sizing: border-box; text-align: center; z-index: 1; box-shadow: 0 1px 3px #000; }
  #confirmBox .button { background-color: #ccc; display: inline-block; border-radius: 3px; border: 1px solid #aaa; padding: 2px; text-align: center; width: 80px; cursor: pointer; }
  #confirmBox .button:hover { background-color: #ddd; }
  #confirmBox .message { text-align: left; margin-bottom: 8px; }
  .form-group { margin-bottom: 10px; }
  .form-horizontal .form-group { margin-left: 0; margin-right: 0; }
  .form-control { border-color: #888; }
  /* .form-horizontal .control-label { font-weight: normal; } */
  a.forget { color: #9d0000; }
  a.forget:hover { color: #9d0000; text-decoration: underline; }
  ol li { line-height: 18px; }
  .example { text-align: left !important; padding: 0 10px; }
  
  
  .main-header { max-height: none; width: 100%; max-width: 900px; }
  .container { width: 100%; max-width: 900px; }
  .error, .error > p { color: #F00; margin: 0; font-weight: 500; line-height: 15px; display: block; }
  
  ul.member_img_outer { list-style:none; margin:0; padding:0; text-align:center; }
  ul.member_img_outer li { display:inline-block; margin:10px; }
  ul.member_img_outer li a { display: table-cell; width: 180px; height: 130px; overflow: hidden; border: 4px solid #f6f6f6; padding: 2px; background: #FBFBFB; vertical-align: middle; }
  ul.member_img_outer li a.missing_img_outer, ul.member_img_outer li a.missing_img_outer:hover { color: #F00;font-size: 20px;background: #fff; }
  ul.member_img_outer li a img { max-width:100%; max-height:100%; opacity:0.8; transition: all .3s ease-in-out; }
  ul.member_img_outer li a:hover img { opacity:1; }
  ul.member_img_outer li p { text-align: center; font-weight: 600; margin: 4px 0 5px 0; }
  a.download_img_btn { margin: 0 auto 15px;display: block;max-width: 160px; }
  
  html, body { height: 100%; }
  #top_header { margin-bottom: -51px; min-height: 100%; padding-bottom:55px; }
  .main-footer { position:relative; }
  
  @media only screen and (max-width:768px) { #getDetailsForm > .form-group > .col-xs-12 { padding:0; } }
</style>

<?php
  header('Cache-Control: must-revalidate');
  header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>

<div class="container">
  <section class="content-header box-header with-border" style="height: 45px; background-color: #1287C0; ">
    <h1 class="register">Payment Details</h1><br />
  </section>
  
  <section>
    <div class="row">
      <div class="col-md-12">
        <?php
          if($this->session->flashdata('error') != '') 
          { ?>
          <div class="alert alert-danger alert-dismissible" id="error_id">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo $this->session->flashdata('error');?> 
          </div>
        <?php } ?>
        
        <form class="form-horizontal" name="getDetailsForm" id="getDetailsForm" method="post" action="" style="padding:10px 0 10px 0">
          <div class="form-group">
            <label for="member_no" class="col-lg-2 col-md-3 col-sm-4 col-xs-12 control-label">Member No. <span style="color:#F00">*</span></label>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
              <input type="text" class="form-control" id="member_no" name="member_no" placeholder="Enter Member No " autofocus value="<?php echo $member_no; ?>" style="margin:0 0px 5px 0;" required autocomplete="off">
              <?php if(form_error('member_no')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('member_no'); ?></label> <?php } ?>
              
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
              <input type="text" class="form-control" id="receipt_no" name="receipt_no" placeholder="Enter Receipt / Transaction No " autofocus value="<?php echo $receipt_no; ?>" style="margin:0 0px 5px 0;"  autocomplete="off">
              <?php if(form_error('receipt_no')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('receipt_no'); ?></label> <?php } ?>
              
            </div>
            
            <div class="col-lg-2 col-md-2 col-sm-2  col-xs-12">
              <button type="submit" class="btn btn-info" >Get Details</button>
            </div>
          </div>
          
          <?php
            if(isset($payment_details) && $payment_details != "")
            {
              if(!empty($payment_details)) 
              { ?>
                
                <section class="content">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="box box-info">
                        <div class="box-header with-border">
                          <h3 class="box-title">Payment Details</h3>
                        </div>
                        
                        <div style="border:1px solid #ddd; margin-bottom:10px;">
                          <div style="background: #f5f5f5;text-align: center;font-weight: 600;padding: 8px;margin-bottom:10px;">
                          <table id="listitems" class="table table-bordered table-striped dataTables-example">
                              <thead>
                              <tr>
                                <th id="srNo">S.No.</th>
                                <th id="description">Exam Name</th>
                                <th id="receipt_no">Receipt No.</th>
                                <th id="transaction_no">Transaction No.</th>
                                <th id="amount">Amount</th>
                                <th id="date">Date</th>
                                <th id="invoice"> Details</th>
                        
                              </tr>
                              </thead>
                              <tbody class="no-bd-y" id="list" style="text-align: left;">
                                <?php 
                                    $i = 1;
                                    foreach($payment_details as $row){
                                    //  echo date('d-m-Y',strtotime($row['date']));exit;
                                      $exam_name = $this->master_model->getRecords('exam_master',array('exam_code'=>$row['exam_code']),'description');
                                      if(count($exam_name) && date('Y-m-d',strtotime($row['date'])) > date('Y-m-d', strtotime('-60 days') )){ 
                                  ?>
                                  <tr>
                                    <td><?php echo $i; ?></td>
                                    <td> 
                                    <?php 
                                        echo $exam_name[0]['description']; 
                                      
                                      ?> </td>
                                      <td><?php echo $row['receipt_no']; ?></td>
                                      <td><?php echo $row['transaction_no']; ?></td>
                                      <td><?php echo $row['amount'];?></td>
                                      <td><?php echo date('d-M-Y',strtotime($row['date']));?></td>
                                      <td><a href="javascript:void(0);" class="getPaymentDetails" receipt_no="<?php echo $row['receipt_no']; ?>"> Details</a></td>
                                      </tr>
                                          <?php $i++;
                                          } 
                                      }
                                    ?>              
                              </tbody>
                            </table>
                            <!-- <div id="links" class="" style="float:right;"><?php //echo $links; ?></div>-->
                            <div id="links" class="dataTables_paginate paging_simple_numbers">
                            
                            </div>
                        </div>
                        
                        <?php //echo "<br><br><pre>"; print_r($member_data); echo "</pre>"; ?>
                      </div>
                    </div>
                  </div>
                </section>
        <?php }
            } ?>
        </form>  
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
       </div>
    </div>
    
  </section>
</div>
 <!-- Modal -->
 
<link href="<?php echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet">

