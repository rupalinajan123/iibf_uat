<?php $this->load->view('count_dashboard/includes/header'); ?>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">
    <?php $this->load->view('count_dashboard/includes/topbar'); ?>
    <?php $this->load->view('count_dashboard/includes/sidebar'); ?>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>User dashboard Count</h1>
      </section>
      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <?php if ($this->session->flashdata('error') != '') { ?>
              <div class="alert alert-danger alert-dismissible" id="error_id">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('error'); ?>
              </div>
            <?php }

            if ($this->session->flashdata('success') != '') { ?>
              <div class="alert alert-success alert-dismissible" id="success_id">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('success'); ?>
              </div>
            <?php } ?>

            <div class="box box-info box-solid disabled">
              <div class="box-header with-border">
                <h3 class="box-title">Registration Details</h3>
                <div class="box-tools pull-right">
                  <button data-widget="collapse" class="btn btn-box-tool" type="button"><i class="fa fa-minus"></i> </button>
                </div>

              </div>
              <div class="box-body" style="display: block;">

                <div class="table-responsive">
                  <table class="table table-bordered">

                    <thead>
                      <tr>
                        <th width="30%">Module Name</th>
                        <th width="20%" class="text-center">Member</th>
                        <th width="20%" class="text-center">Non - Member</th>
                        <th width="20%" class="text-center">Total</th>
                      </tr>
                    </thead>

                    <tbody>
                      <tr>
                        <td>Normal Registration :</td>
                        <td class="text-center"><?php echo $count3; ?></td>
                        <td class="text-center"><?php echo $count5; ?></td>
                        <td class="text-center"><?php echo $count1; ?></td>
                      </tr>
                      <tr>
                        <td>BCBF :</td>
                        <td class="text-center"><?php echo "0"; ?></td>
                        <td class="text-center"><?php echo $bcbf_release_count; ?></td>
                        <td class="text-center"><?php echo $bcbf_release_count; ?></td>
                      </tr>
                      <tr>
                        <td>DRA :</td>
                        <td class="text-center"><?php echo "0"; ?></td>
                        <td class="text-center"><?php echo $dra_release_count; ?></td>
                        <td class="text-center"><?php echo $dra_release_count; ?></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>


          <div class="col-md-12">
            <div class="box box-info box-solid disabled">
              <div class="box-header with-border">
                <h3 class="box-title">Today's Registration Details</h3>
                <div class="box-tools pull-right">
                  <button data-widget="collapse" class="btn btn-box-tool" type="button"><i class="fa fa-minus"></i> </button>
                </div>
              </div>
              <div class="box-body" style="display: block;">

                <div class="table-responsive">
                  <table class="table table-bordered">
                    <tbody>
                      <tr>
                        <td width="30%">Total Registrations Today :</td>
                        <td width="20%" class="text-center"><?php echo $count7   ?></td>
                      </tr>
                      <tr>
                        <td width="30%">Total Member Applications Today:</td>
                        <td width="20%" class="text-center"><?php echo $count9  ?></td>
                      </tr>
                      <tr>
                        <td width="30%">Total Non-Member Applications Today:</td>
                        <td width="20%" class="text-center"><?php echo $count11  ?></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
  </div>
  </div>
  </section>
  </div>
  <?php $this->load->view('count_dashboard/includes/footer'); ?>