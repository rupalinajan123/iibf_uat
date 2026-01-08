<?php $this->load->view('admin/includes/header');?>

<?php $this->load->view('admin/includes/sidebar');?>

<div class="wrapper">
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1> Home </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
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
                        >> <a href="<?php echo base_url();?>admin/MainController" class="" >Home</a> &nbsp;&nbsp;&nbsp;&nbsp;
                        >> <a href="<?php echo base_url();?>admin/Report/datewise" class="" >Date Wise</a> &nbsp;&nbsp;&nbsp;&nbsp;
                 		>> <a href="<?php echo base_url();?>admin/login/Logout" class="" >Logout</a> &nbsp;&nbsp;&nbsp;&nbsp;
                      </td>
                    </tr>
                    <tr>
                      <td>Reports</td>
                      <td>
						>> <a href="<?php echo base_url();?>admin/Report/BD_success" class="" >Exam Application Success</a> &nbsp;&nbsp;&nbsp;&nbsp;
                        >> <a href="<?php echo base_url();?>admin/Report/BD_failure" class="" >Exam Application Failure</a> &nbsp;&nbsp;&nbsp;&nbsp;
                        >> <a href="<?php echo base_url();?>admin/Report/BD_failure/reason" class="" >Exam Application Failure Reason</a> &nbsp;&nbsp;&nbsp;&nbsp;
                        >> <a href="<?php echo base_url();?>admin/Report/dup_icard_success" class="" >Dup i-card Success</a> &nbsp;&nbsp;&nbsp;&nbsp;
                        >> <a href="<?php echo base_url();?>admin/Report/dup_icard_failure" class="" >Dup i-card Failure</a> &nbsp;&nbsp;&nbsp;&nbsp;
                        >> <a href="<?php echo base_url();?>admin/Report/dup_icard_failure/reason" class="" >Dup i-card Failure Reason</a> &nbsp;&nbsp;&nbsp;&nbsp;
                      </td>
                    </tr>
                     <tr>
                      <td>Search</td>
                      <td>
                        >> <a href="<?php echo base_url();?>admin/Search/search_success" class="" >Success Transaction(Only Registration)</a> &nbsp;&nbsp;&nbsp;&nbsp;
                        >> <a href="<?php echo base_url();?>admin/Search/search_failure" class="" >Failure Transaction</a> &nbsp;&nbsp;&nbsp;&nbsp;
                        >> <a href="<?php echo base_url();?>admin/Report/failCandReport" class="" >View Failure candidates</a> &nbsp;&nbsp;&nbsp;&nbsp;
                      </td>
                    </tr>
                    <!--<tr>
                      <td>Image Downloads</td>
                      <td>
                        >> <a href="#" class="" >Edited Image</a> &nbsp;&nbsp;&nbsp;&nbsp;
                        >> <a href="#" class="" >Registered Candidate Image</a> &nbsp;&nbsp;&nbsp;&nbsp;
                      </td>
                    </tr>-->
                    <!--<tr>
                      <td>AMP Application</td>
                      <td>
                        >> <a href="#" class="" >AMP Success Billdesk</a> &nbsp;&nbsp;&nbsp;&nbsp;
                        >> <a href="#" class="" >AMP Failure Billdesk</a> &nbsp;&nbsp;&nbsp;&nbsp;
                        >> <a href="#" class="" >AMP Failure Reason</a> &nbsp;&nbsp;&nbsp;&nbsp;
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
    
      <div class="row mar30">
        <div class="col-md-12">
          <div class="box box-info box-solid disabled">
            <div class="box-header with-border">
              <h3 class="box-title">Registration Details</h3>
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
                      <td width="30%">Total Successful Transactions(15-06-2012 To 31-05-2013) :</td>
                      <td width="20%" class="text-center">45916</td>
                      <td width="30%">&nbsp;</td>
                      <td width="20%" class="text-center">&nbsp;</td>
                    </tr>
                    <tr>
                      <td width="30%">Total Successful Transactions(01-06-2013 To Till) :</td>
                      <td width="20%" class="text-center"><?php echo $total_reg_mem_1; ?></td>
                      <td width="30%">Today's Successful Transactions:</td>
                      <td width="20%" class="text-center"><?php echo $total_reg_mem_2; ?></td>
                    </tr>
                    <tr>
                      <td width="30%">Total Failure Transactions(17-06-2012 To 31-05-2013) :</td>
                      <td width="20%" class="text-center">12863</td>
                      <td width="30%">&nbsp;</td>
                      <td width="20%" class="text-center">&nbsp;</td>
                    </tr>
                    <tr>
                      <td width="30%">Total Failure Transactions(01-06-2013 To Till) :</td>
                      <td width="20%" class="text-center"><?php echo $total_reg_mem_3; ?></td>
                      <td width="30%">Today's Failure Transactions:</td>
                      <td width="20%" class="text-center"><?php echo $total_reg_mem_4; ?></td>
                    </tr>
                  </table>
              </div>
                
            </div>
            <!-- /.box-body --> 
          </div>
          <!-- /.box --> 
        </div>
      </div>
	  
      <div class="row mar30">
        <div class="col-md-12">
          <div class="box box-info box-solid disabled">
            <div class="box-header with-border">
              <h3 class="box-title">Duplicate I-card Details</h3>
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
                      <td width="30%">Duplicate I-card Total Successful Transactions</td>
                      <td width="20%" class="text-center"><?php echo $total_dup_icard_1; ?></td>
                      <td width="30%">&nbsp;</td>
                      <td width="20%" class="text-center">&nbsp;</td>
                    </tr>
                    <tr>
                      <td width="30%">Duplicate I-card Total Failure Transactions</td>
                      <td width="20%" class="text-center"><?php echo $total_dup_icard_2; ?></td>
                      <td width="30%">&nbsp;</td>
                      <td width="20%" class="text-center">&nbsp;</td>
                    </tr>
                  </table>
              </div>
                
            </div>
            <!-- /.box-body --> 
          </div>
          <!-- /.box --> 
        </div>
      </div>
      
      <div class="row mar30">
        <div class="col-md-12">
          <div class="box box-info box-solid disabled">
            <div class="box-header with-border">
              <h3 class="box-title">Examination Details</h3>
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
                      <td width="30%">Total Successful Transactions(01-01-2015 To Till)</td>
                      <td width="20%" class="text-center"><?php echo $total_reg_exam_1; ?></td>
                      <td width="30%">Today's Successful Transactions:</td>
                      <td width="20%" class="text-center"><?php echo $total_reg_exam_2; ?></td>
                    </tr>
                    <tr>
                      <td width="30%">Total Failure Transactions(01-01-2015 To Till)</td>
                      <td width="20%" class="text-center"><?php echo $total_reg_exam_3; ?></td>
                      <td width="30%">Today's Failure Transactions:</td>
                      <td width="20%" class="text-center"><?php echo $total_reg_exam_4; ?></td>
                    </tr>
                    <tr>
                      <td width="30%">Total Open Transactions(01-01-2015 To Till)</td>
                      <td width="20%" class="text-center"><?php echo $total_reg_exam_5; ?></td>
                      <td width="30%">Today's Open Transactions:</td>
                      <td width="20%" class="text-center"><?php echo $total_reg_exam_6; ?></td>
                    </tr>
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

<?php $this->load->view('admin/includes/footer');?>