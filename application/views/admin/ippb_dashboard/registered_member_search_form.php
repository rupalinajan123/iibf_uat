<?php $this->load->view('admin/ippb_dashboard/includes/header');?>
<?php $this->load->view('admin/ippb_dashboard/includes/sidebar');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Edit Member Details
        
      </h1>
      <?php echo $breadcrumb; ?>
    </section>
    <br />
  <div class="col-md-12">
    <?php if($this->session->flashdata('error')!=''){?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
            <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php } if($this->session->flashdata('success')!=''){ ?>
        <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
        <?php echo $this->session->flashdata('success'); ?>
        </div>
    <?php } ?>
    </div>

    <!-- Search function -->
     <div class="searchfilter">
          <div class="box-header">
            <!-- <div class="box-header with-border"> -->
                <!-- tools box -->
                <div class="pull-right box-tools">
                    <button type="button" class="mb-2 float-right btn btn-primary" data-toggle="collapse" data-target="#collapseExample">
                     <i class="fa fa-filter"></i></button>
                </div>
                <!-- /. tools -->
                <h3 class="page-title">Search Filter</h3>
            <!-- </div> -->
            <div class="collapsee" id="collapseExample">
  
              <form class="form-control" name="" id="" action="<?php echo base_url('admin/ippb/IppbDashboard/registered_member_search_form'); ?>" method="get" autocomplete="off">
                <div class="row">
                <div class="col-md-3"></div>
                  <div class="col-md-3">
                    <div class="form-group">
                        <label>Regnumber</label>
                        <input type="text" class="form-control" id="regnumber" name="regnumber" placeholder="" required value="<?php if(isset($_GET['regnumber'])) echo $_GET['regnumber']; ?>" >
                    </div>
                  </div>
                

                  <div class="col-md-2">
                    <div class="form-group">
                        <label>Search Details</label>
                        <input type="submit" class="mb-2 float-right btn btn-primary" name="btnSearch" id="btnSearch" value="Search Details" >
                    </div>
                  </div>


                </div>     
               </form>
              </div>
            
            </div>
          </div>
    <!-- Search function -->

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
              <h3 class="box-title"></h3>
          
              
                <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
                <input type="hidden" name="base_url_val" id="base_url_val" value="" /> 
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
      <div class="table-responsive">    
      <?php
                     if(!empty($mem_info)) {
                      ?>
      <table id="" class="table table-bordered table-striped dataTables-example" role="grid">
                <thead>
                <tr>
                  <!-- <th id="srNo">S.No.</th> -->
                  <th id="mem_mem_no">Registration No</th>
                  <th id="emp_id">Employee Id</th>
                  <th id="mam_nam_1">Full Name</th>
                  <th >Mobile</th>
                  <th >DOB</th>
                  <th >Edit</th>
                  <th >Admit card</th>
                </tr>
                </thead>
                <tbody class="no-bd-y" id="list">
                     <tr>
                        <?php
                      foreach($mem_info as $m) {
                        ?>
                        <td><?php echo $m['regnumber']; ?></td>
                        <td><?php echo $m['emp_id']; ?></td>
                        <td><?php echo $m['firstname'].' '.$m['middlename'].' '.$m['lastname']; ?></td>
                        <td><?php echo $m['mobile']; ?></td>
                        <td><?php echo $m['dateofbirth']; ?></td>
                        <td><a  href="<?php echo base_url(); ?>admin/ippb/IppbDashboard/edit_registered_member/<?php echo base64_encode($m['regid']); ?>">Edit</a></td>
                        <td><a target="_blank" href="<?php echo base_url(); ?>/admin/ippb/IppbDashboard/re_generate_admit_card/<?php echo $_GET['regnumber']; ?>">Re-generate</td>
                        <?php
                      } ?></tr> 
                </tbody>
              </table>
              <?php 
                    
                     } ?>
           </div>       
          
            </div>
          </div>
        </div>
      </div>
      
    </section>
   
  </div>
  

<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script>
<script type="text/javascript">

</script>


<?php $this->load->view('admin/ippb_dashboard/includes/footer');?>