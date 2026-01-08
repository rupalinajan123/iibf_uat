
<div class="wrapper"> 
  <!-- Content Wrapper. Contains page content --> 
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1> Dashboard </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
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
                      <td><strong>List</strong></td>
                      <td>
            			<a href="<?php echo base_url();?>bulk/Bankdashboard/view_profile" class="" >View Profile </a> &nbsp;&nbsp;&nbsp;&nbsp;
                      </td>
                      <td>
                      	<a href="<?php echo base_url();?>bulk/BulkApply/examlist" class="" >Apply For Exam </a> &nbsp;&nbsp;&nbsp;&nbsp;
                      </td>
                      <!--<td>    
                      	<a href="<?php  //echo base_url();?>admin/finquest/Finquest/"> View Admit Card  </a>&nbsp;&nbsp;&nbsp;&nbsp;
                      </td>-->
                      <td>    
                      	<a href="<?php echo base_url();?>bulk/BulkTransaction/transactions"> Transaction Details  </a>&nbsp;&nbsp;&nbsp;&nbsp;
                      </td>
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
