<?php $this->load->view('admin/blended_dashboard/includes/header');?>
<?php $this->load->view('admin/blended_dashboard/includes/sidebar');?>

<div class="wrapper">
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Home </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
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
                  <td>Members</td>
                  <td><a href="<?php echo base_url();?>admin/BlendedDashboard/">Blended</a> &nbsp;&nbsp;&nbsp;&nbsp;</td>
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

<?php $this->load->view('admin/blended_dashboard/includes/footer');?>
