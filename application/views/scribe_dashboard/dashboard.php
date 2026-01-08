

<?php $this->load->view('scribe_dashboard/includes/header'); ?>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
            <?php $this->load->view('scribe_dashboard/includes/topbar'); ?>
            <?php $this->load->view('scribe_dashboard/includes/sidebar'); ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Welcome </h1>
            
        </section>

    <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <!-- Flash Data : POOJA MANE : 01/09/2022 -->
                    <?php if($this->session->flashdata('error')!=''){?>               
                      <div class="alert alert-danger alert-dismissible" id="error_id">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          <?php echo $this->session->flashdata('error'); ?>
                      </div>                
                      <?php } 
                
                          if($this->session->flashdata('success')!=''){ ?>
                      <div class="alert alert-success alert-dismissible" id="success_id">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          <?php echo $this->session->flashdata('success'); ?>
                      </div>
                    <?php } ?>
                    <!-- Flash Data : POOJA MANE : 01/09/2022 -->
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
                                 <tbody>
                                <tr>
                                  <td width="30%">Total Successful Scribe Registration :</td>
                                  <td width="20%" class="text-center"><?php echo  $count1 ?></td>
                                  <td width="30%">Total Successful Special assistance Registration:</td>
                                  <td width="20%" class="text-center"><?php echo $count2 ?></td>
                                </tr>
                                <tr>
                                  <td width="30%">Total approved Scribe Registration:</td>
                                  <td width="20%" class="text-center"><?php echo $count3  ?></td>
                                  <td width="30%">Total approved Special assistance Registration:</td>
                                  <td width="20%" class="text-center"><?php echo $count4 ?></td>
                                </tr>
                                <tr>
                                  <td width="30%">Total Rejected Scribe Registration:</td>
                                  <td width="20%" class="text-center"><?php echo $count5  ?></td>
                                  <td width="30%">Total Rejected Special assistance Registration:</td>
                                  <td width="20%" class="text-center"><?php echo $count6 ?></td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                            
                        </div>
                        <!-- /.box-body --> 
                      </div>
                      <!-- /.box --> 
                    </div>       
                
    
                <div class="col-md-12">
                      <div class="box box-info box-solid disabled">
                        <div class="box-header with-border">
                          <h3 class="box-title">Today's Registration Details</h3>
                          <div class="box-tools pull-right">
                            <button data-widget="collapse" class="btn btn-box-tool" type="button"><i class="fa fa-minus"></i> </button>
                          </div>
                          <!-- /.box-tools --> 
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body" style="display: block;">
                            
                          <div class="table-responsive">
                              <table class="table table-bordered">
                                 <tbody>
                                <tr>
                                  <td width="30%">Total Scribe Registrations Today :</td>
                                  <td width="20%" class="text-center"><?php echo $count7   ?></td>
                                  <td width="30%">Total Special assistance Registrations Today:</td>
                                  <td width="20%" class="text-center"><?php echo $count8  ?></td>
                                </tr>
                                <tr>
                                  <td width="30%">Total approved Scribe Applications Today:</td>
                                  <td width="20%" class="text-center"><?php echo $count9  ?></td>
                                  <td width="30%">Total approved Special assistance Applications Today:</td>
                                  <td width="20%" class="text-center"><?php echo $count10 ?></td>
                                </tr>
                                <tr>
                                  <td width="30%">Total Rejected Scribe Applications Today:</td>
                                  <td width="20%" class="text-center"><?php echo $count11  ?></td>
                                  <td width="30%">Total Rejected Special assistance Applications Today:</td>
                                  <td width="20%" class="text-center"><?php echo $count12  ?></td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                            </div>
                <!-- /.col -->
            </div>
                        </div>
                        <!-- /.box-body --> 
                      </div>
                      <!-- /.box --> 
                    </div>       
                </div>
                <!-- /.col -->
            </div>
        </section>
    <!-- /.content -->
</div>
<?php $this->load->view('scribe_dashboard/includes/footer');?>