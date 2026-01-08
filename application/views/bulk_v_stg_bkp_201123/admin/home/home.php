<?php $this->load->view('bulk/admin/includes/header');
$this->load->view('bulk/admin/includes/sidebar');?>
<div class="wrapper">
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1> Home </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url();?>bulk/admin/MainController"><i class="fa fa-home"></i> Home</a></li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
    <!-- Info boxes -->
      <div class="row mar30">
        <div class="col-md-12">
          <div class="box box-info box-solid disabled">
            <div class="box-header with-border">
              <h3 class="box-title">Welcome</h3>
              <div class="box-tools pull-right">
                <button data-widget="collapse" class="btn btn-box-tool" type="button"><i class="fa fa-minus"></i> </button>
              </div>
              <!-- /.box-tools --> 
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="display: block;">
              
              <div class="table-responsive">
                  <table class="table table-bordered">
                    <tr>
                      <td>My Home</td>
                      <td>
                        >> <a href="<?php echo base_url();?>bulk/admin/MainController" class="">Home</a> &nbsp;&nbsp;&nbsp;&nbsp;
                        >> <a href="<?php echo base_url();?>bulk/admin/transaction/transactions" class="">Transactions</a> &nbsp;&nbsp;&nbsp;&nbsp;
                        >> <a href="<?php echo base_url();?>bulk/admin/transaction/neft_transactions" class="">Approve NEFT Transactions</a> &nbsp;&nbsp;&nbsp;&nbsp;
                        >> <a href="<?php echo base_url();?>bulk/admin/ExamActiveMaster" class="">Masters</a> &nbsp;&nbsp;&nbsp;&nbsp;
                        >> <a href="<?php echo base_url(); ?>bulk/admin/login/changepassword" class="">Change Password</a> &nbsp;&nbsp;&nbsp;&nbsp;
                        >> <a href="<?php echo base_url()?>bulk/admin/login/Logout" class="">Logout</a> &nbsp;&nbsp;&nbsp;&nbsp;
                      </td>
                    </tr>
                    <!--<tr>
                      <td>Reports</td>
                      <td>
                        >> <a href="<?php //echo base_url(); ?>bulk/admin/report/billdesk_success" class="">Success BillDesk</a> &nbsp;&nbsp;&nbsp;&nbsp;
                        >> <a href="<?php //echo base_url(); ?>bulk/admin/report/billdesk_failure" class="">Failure BillDesk</a> &nbsp;&nbsp;&nbsp;&nbsp;
                        >> <a href="<?php //echo base_url(); ?>bulk/admin/report/failure_reason" class="">Failure Reason</a> &nbsp;&nbsp;&nbsp;&nbsp;
                        >> <a href="<?php //echo base_url(); ?>bulk/admin/report/billdesk_neft_report" class="">NEFT BillDesk Report</a> &nbsp;&nbsp;&nbsp;&nbsp;
                        >> <a href="<?php //echo base_url(); ?>bulk/admin/dashboard" class="">Dashboard</a> &nbsp;&nbsp;&nbsp;&nbsp;
                      </td>
                    </tr>-->
                  </table>
              </div>
              
            </div>
            <!-- /.box-body --> 
          </div>
          <!-- /.box --> 
        </div>
      </div>
      
    </section>
    <!-- /.content --> 
  </div>
  <!-- /.content-wrapper -->
<?php $this->load->view('bulk/admin/includes/footer');?>