<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Member Payment List</title>

        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawR/h/gU6yh2zXN2+s+k8iQWw/Y98uTfI8w=" crossorigin="anonymous"></script>

        <link href="https://iibf.esdsconnect.com/staging/assets/ncvet/css/bootstrap.min.css?ver=1754918264" rel="stylesheet">
        <script src="https://iibf.esdsconnect.com/staging/assets/ncvet/js/bootstrap.js?ver=1754918266"></script>
        
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <style>
            /* == LAYOUT AND GENERAL STYLES == */
            body {
                background-color: #f7f9fc; 
                font-family: Arial, sans-serif;
            }
            .page-content-wrapper {
                padding: 20px 30px; 
                max-width: 1300px; 
                margin: 0 auto; 
            }

            /* == ALERT MESSAGE STYLES == */
            .message-container {
                margin-top: 20px;
                margin-bottom: 25px; 
            }
            .alert-success {
                background-color: #d4edda; 
                color: #155724; 
                border-color: #c3e6cb;
                padding: 15px 20px; 
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                border-radius: 8px;
                text-align: center;
                font-weight: bold;
                border-left: 5px solid #28a745; 
            }
            .alert-dismissible .close {
                padding: 1.2rem 1rem;
            }

            /* == HEADING BOX STYLES == */
            .card-header-box {
                background-color: #ffffff; 
                border: 1px solid #e0e6ed;
                border-radius: 8px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05); 
                margin-bottom: 20px;
                padding: 15px 20px;
                text-align: center;
            }
            .card-header-box h3 {
                margin: 0;
                color: #007bff; 
                font-weight: 600;
                font-size: 1.5rem;
            }

            /* == DATATABLE STYLES == */
            .table-container {
                background-color: #ffffff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
                overflow-x: auto; 
            }
            
            /* General table styling */
            #paymentDataTable {
                width: 100% !important;
                margin-bottom: 0;
            }
            #paymentDataTable thead th {
                background-color: #e9ecef; 
                color: #495057; 
                border-bottom: 2px solid #dee2e6;
                font-weight: bold;
                text-transform: uppercase;
                font-size: 0.9rem;
            }

            /* DataTable wrapper styling (search, pagination controls) */
            .dataTables_wrapper .dataTables_filter,
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate {
                padding: 10px 0;
                margin-bottom: 5px;
                font-size: 0.9rem;
            }

            /* Pagination button styling */
            .dataTables_wrapper .dataTables_paginate .paginate_button {
                padding: 0.3em 0.8em;
                margin-left: 2px;
                border-radius: 4px;
            }
            .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
            .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
                background: #007bff !important;
                color: white !important;
                border-color: #007bff !important;
            }

            /* Table row hover effect */
            #paymentDataTable tbody tr:hover {
                background-color: #f1f5f9;
                cursor: pointer;
            }
            
            /* Transaction Status Button Styling */
            #paymentDataTable .btn-warning {
                font-size: 0.85rem;
                padding: 4px 10px;
                font-weight: 600;
                border-radius: 15px; 
            }
            #paymentDataTable .btn-danger {
                font-size: 0.85rem;
                padding: 6px 12px;
                font-weight: 600;
                border-radius: 4px;
            }
        </style>
    </head>
    <body>
        
        <div class="container-fluid page-content-wrapper">

            <div class="message-container">
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $this->session->flashdata('success'); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $this->session->flashdata('error'); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card-header-box">
                        <h3><b> DRA Transaction List </b></h3> 
                    </div>
                </div>
            </div>
            <div class="table-container">
                <table id="paymentDataTable" class="table table-bordered custom_inner_tbl" style="width:100%">
                    <thead>
                        <tr> 
                            <th class="wrap"><b>Agency Name</b></th>
                            <th class="wrap"><b>Proformo Invoice No.</b></th>
                            <th class="wrap">Transaction No.</th> 
                            <th class="wrap">Receipt No.</th> 
                            <th class="wrap">Exam Period</th>  
                            <th class="wrap"><b>Amount</b></th>
                            <th class="wrap">Payment Date</th>
                            <th class="wrap">Status</th>
                            <!-- <th class="wrap">Transaction Details</th> -->
                            <th class="wrap">Action</th>  
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ( count($arr_payment_data) > 0 ) { ?>
                            <?php foreach ($arr_payment_data as $key => $payment_value) { ?>
                                <tr>
                                    <?php 
                                        $status = 'Failed';
                                        if ($payment_value['status'] == 1) {
                                            $status = 'Success';
                                            $buttonClass = 'btn btn-primary';
                                        } elseif ($payment_value['status'] == 0) {
                                            $status = 'Failed';
                                            $buttonClass = 'btn btn-danger';
                                        } elseif ($payment_value['status'] == 5) {
                                            $status = 'Inprocess';
                                            $buttonClass = 'btn btn-warning';
                                        } elseif ($payment_value['status'] == 6) {
                                            $status = 'Failed';
                                            $buttonClass = 'btn btn-danger';
                                        }
                                    ?>
                                    <td> <?php echo $payment_value['institute_name']; ?> </td>
                                    <td> <?php echo $payment_value['proformo_invoice_no']; ?> </td>
                                    <td> <?php echo $payment_value['transaction_no']; ?> </td> 
                                    <td> <?php echo $payment_value['receipt_no']; ?> </td> 
                                    <td> <?php echo $payment_value['exam_period']; ?> </td>  
                                    <td> <?php echo $payment_value['amount']; ?> </td>
                                    <td> <?php echo $payment_value['date']; ?> </td>
                                    <td> <button type="button" class="btn btn-warning">  <?php echo $status; ?>  </button> </td>
                                    <!-- <td> <?php echo $payment_value['transaction_details']; ?> </td> -->
                                    <td>
                                        <?php if ($payment_value['status'] == 5) { ?>
                                            <a class="btn btn-danger js-make-failed-transaction" 
                                               href="javascript:void(0);" 
                                               data-url="<?php echo base_url('DipcertDRAExam/update_payment_status').'/'.$payment_value['id']; ?>"> 
                                                Make Failed transaction 
                                            </a>
                                        <?php } ?>
                                    </td>
                                </tr>    
                            <?php } ?>
                        <?php } ?>     
                    </tbody>
                </table>
            </div>
            </div>
        <script src="https://iibf.esdsconnect.com/assets/ncvet/js/jquery-3.1.1.min.js?ver=1760418759"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script src="https://iibf.esdsconnect.com/staging/assets/ncvet/js/plugins/dataTables/datatables.min.js?ver=1754918279"></script>
        <script src="https://iibf.esdsconnect.com/staging/assets/ncvet/js/plugins/dataTables/dataTables.bootstrap4.min.js?ver=1754918279"></script>

        <script>
            $(document).ready(function () {
                $('#paymentDataTable').DataTable({
                    "pageLength": 10,
                    "lengthMenu": [5, 10, 25, 50, 100],
                    "order": [], // Disable default sorting
                    "responsive": true
                });

                // START: SweetAlert Confirmation Logic
                $('.js-make-failed-transaction').on('click', function(e) {
                    e.preventDefault(); // Stop the default link action
                    
                    var targetUrl = $(this).data('url');

                    Swal.fire({
                        title: 'Are you absolutely sure?',
                        text: "You are about to mark this transaction as Failed. This action cannot be undone.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33', // Red color for danger action
                        cancelButtonColor: '#3085d6', // Blue color for cancel
                        confirmButtonText: 'Yes, Mark as Failed!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // If confirmed, redirect to the stored URL
                            window.location.href = targetUrl;
                        }
                    });
                });
                // END: SweetAlert Confirmation Logic
            });
        </script>
    </body>
</html>