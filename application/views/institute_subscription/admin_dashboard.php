<!DOCTYPE html>
<html>

<head>
  <?php $this->load->view('institute_subscription/inc_header_admin'); ?>
  <style>
    .break_word {
      word-break: break-word;
      white-space: normal;
      word-wrap: anywhere;
    }

    .nowrap {
      white-space: nowrap;
    }

    .dataTables_wrapper {
      max-width: 97%;
      margin: 20px auto 0;
    }

    .table thead th { vertical-align: top !important; }
  </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">
    <?php $this->load->view('institute_subscription/inc_navbar_admin'); ?>

    <div class="loading" style="display:none;"><img src="<?php echo base_url(); ?>assets/images/loading.gif"></div>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>Welcome to IIBF Institute Subscription Admin Panel - Payment History</span></h1>
      </section>
      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="box box-info">
              <div class="box-body">
                <?php
                if ($this->session->flashdata('error') != '')
                { ?>
                  <div class="alert alert-danger alert-dismissible" id="error_id">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo $this->session->flashdata('error'); ?>
                  </div>
                <?php }

                if ($this->session->flashdata('success') != '')
                { ?>
                  <div class="alert alert-success alert-dismissible" id="success_id">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo $this->session->flashdata('success'); ?>
                  </div>
                <?php }  ?>

                <div class="table-responsive">
                  <table class="table table-bordered dataTables-example">
                    <thead>
                      <th class="text-center">Sr. No</th>
                      <th class="text-center">Institute Name</th>
                      <th class="text-center">Institute Number</th>
                      <th class="text-center">Invoice Number</th>
                      <th class="text-center">Base Amount</th>
                      <th class="text-center">GST Amount</th>
                      <th class="text-center">IT TDS, if any</th>
                      <th class="text-center">IT TDS %</th>
                      <th class="text-center">IT TDS Amount</th>
                      <th class="text-center">GST TDS, if any</th>
                      <th class="text-center">GST TDS %</th>
                      <th class="text-center">GST TDS Amount</th>
                      <th class="text-center">Final Amount</th>
                      <th class="text-center">Payment Type</th>
                      <th class="text-center">Subscription Year</th>
                      <th class="text-center">Payment Date</th>
                      <th class="text-center">Payment Status</th>
                      <th class="text-center">Transaction No</th>
                      <th class="text-center">Invoice</th>
                      <th class="text-center">Receipt</th>
                    </thead>

                    <tbody>
                      <?php if (count($payment_history_data) > 0)
                      {
                        $sr_no = 1;
                        foreach ($payment_history_data as $res)
                        {  ?>
                          <tr>
                            <td class="text-center"><?php echo $sr_no; ?></td>
                            <td class="text-center"><?php echo $res['institute_name']; ?></td>
                            <td class="text-center"><?php echo $res['institute_no']; ?></td>
                            <td class="text-center"><?php echo $res['invoice_no']; ?></td>
                            <td class="text-right"><?php echo 'Rs.'.$res['subscription_base_amount']; ?></td>
                            <td class="text-right"><?php echo 'Rs.'.$res['subscription_gst_amount']; ?></td>
                            <td class="text-center"><?php echo strtoupper($res['is_it_tds_applicable']); ?></td>
                            <td class="text-right"><?php echo $res['is_it_tds_applicable']=='yes' ? $res['it_tds_percentage_rate'].'%' : "-"; ?></td>
                            <td class="text-right"><?php echo $res['is_it_tds_applicable']=='yes' ? 'Rs.'.$res['it_tds_percentage_amount'] : "-"; ?></td>
                            <td class="text-center"><?php echo strtoupper($res['is_gst_tds_applicable']); ?></td>
                            <td class="text-right"><?php echo $res['is_gst_tds_applicable']=='yes' ? $res['gst_tds_percentage_rate'].'%' : "-"; ?></td>
                            <td class="text-right"><?php echo $res['is_gst_tds_applicable']=='yes' ? 'Rs.'.$res['gst_tds_percentage_amount'] : "-"; ?></td>
                            <td class="text-right"><?php echo 'Rs.'.$res['amount']; ?></td>
                            <td class="text-center"><?php echo $res['gateway']; ?></td>
                            <td class="text-center"><?php echo $res['subscription_year']; ?></td>
                            <td><?php echo $res['PaymentDate']; ?></td>
                            <td class="text-center">
                              <?php
                              if ($res['PaymentStatus'] == 0 || $res['PaymentStatus'] == 7)
                              {
                                echo 'Fail';
                              }
                              else if ($res['PaymentStatus'] == 1)
                              {
                                echo 'Success';
                              }
                              else if ($res['PaymentStatus'] == 2)
                              {
                                echo 'Pending';
                              }
                              else if ($res['PaymentStatus'] == 3)
                              {
                                echo 'Refund';
                              }  ?>
                            </td>
                            <td><?php echo $res['transaction_no']; ?></td>
                            <td class="text-center no-sort"><a href="<?php echo site_url('institute_subscription/admin_invoice/' . base64_encode($res['receipt_no'])); ?>" class="btn btn-sm btn-primary" style="padding:1px 5px 2px" target="_blank">View</a></td>
                            <td class="text-center no-sort"><a href="<?php echo site_url('institute_subscription/admin_receipt/' . base64_encode($res['receipt_no'])); ?>" class="btn btn-sm btn-primary" style="padding:1px 5px 2px" target="_blank">View</a></td>
                          </tr>
                      <?php $sr_no++;
                        }
                      }  ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <?php $this->load->view('institute_subscription/inc_footer_text'); ?>
  </div>
  <?php $this->load->view('institute_subscription/inc_footer'); ?>

  <link href="<?php echo base_url('assets/admin/plugins/datatables/dataTables.bootstrap.css'); ?>" rel="stylesheet">
  <link href="<?php echo base_url('assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.cssx'); ?>" rel="stylesheet">
  <link href="<?php echo base_url('assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css'); ?>" rel="stylesheet">

  <!-- Data Tables -->
  <script src="<?php echo base_url('assets/admin/plugins/datatables/jquery.dataTables.js'); ?>"></script>
  <script src="<?php echo base_url('assets/admin/plugins/datatables/dataTables.bootstrap.js'); ?>"></script>
  <script src="<?php echo base_url('assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.jsx'); ?>"></script>
  <script src="<?php echo base_url('assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js'); ?>"></script>

  <script>
    $(document).ready(function() {
      $('.dataTables-example').DataTable({
        pageLength: 10,
        responsive: true,
        "columnDefs": [{
          "targets": 'no-sort',
          "orderable": false,
        }],
        "aaSorting": [],
        //"stateSave": true,
      });
    });
  </script>
</body>

</html>