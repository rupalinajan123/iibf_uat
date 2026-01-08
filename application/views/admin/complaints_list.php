<?php $this->load->view('admin/includes/header');?>
<?php $this->load->view('admin/includes/sidebar');?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Candidates Support Services
      </h1>
     <?php echo $breadcrumb; ?>
    </section>
	<!-- Main content -->
    <section class="content">
    	<form class="form-horizontal" name="searchcmsform" id="searchcmsform" action="" method="post">
        <div class="row">
          <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-info">
              <div class="box-header with-border">
                <h3 class="box-title">Search</h3>
              </div>
              <!-- /.box-header -->
              <!-- form start -->
              <div class="box-body">
               		<?php if($this->session->flashdata('error')!=''){?>
                      <div class="alert alert-danger alert-dismissible">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          <?php echo $this->session->flashdata('error'); ?>
                      </div>
                    <?php } if($this->session->flashdata('success')!=''){ ?>
                      <div class="alert alert-success alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <?php echo $this->session->flashdata('success'); ?>
                    </div>
                   <?php } ?> 
 				   <div class="form-group">
                       <label for="from_date" class="col-sm-2">From Date :</label>
                       <div class="col-sm-3">
                          <input type="text" class="form-control" id="from_date" name="from_date" placeholder="From Date" value="<?php echo set_value('from_date');?>" >
                           <span class="error"><?php echo form_error('from_date');?></span>
                       </div>
                      
                      <label for="to_date" class="col-sm-2">To Date :</label>
                      <div class="col-sm-3">
                          <input type="text" class="form-control" id="to_date" name="to_date" placeholder="To Date" value="<?php echo set_value('to_date');?>" >
                           <span class="error"><?php echo form_error('to_date');?></span>
                      </div>
                  </div>
                  
                  <div class="form-group">
                       <label for="from_date" class="col-sm-2">Search By :</label>
                       <div class="col-sm-8">
                          <input type="radio" class="minimal" id="optsearchby" name="optsearchby" value="cmp_id"  <?php echo set_radio('optsearchby', 'cmp_id'); ?>>CMP.ID.
                          <input type="radio" class="minimal" id="optsearchby" name="optsearchby" value="memno" <?php echo set_radio('optsearchby', 'memno'); ?>>Membership No.
                          <input type="radio" class="minimal" id="optsearchby" name="optsearchby" value="name" <?php echo set_radio('optsearchby', 'name'); ?>>Candidate Name
                          <input type="radio" class="minimal" id="optsearchby" name="optsearchby" value="mobi" <?php echo set_radio('optsearchby', 'mobi'); ?>>Mobile No. 
                          <input type="radio" class="minimal" id="optsearchby" name="optsearchby" value="emailid" <?php echo set_radio('optsearchby', 'emailid'); ?>>Email Id. 
                           <span class="error"><?php echo form_error('optsearchby');?></span>
                       </div>
                  </div>
                 <div class="form-group">
                       <label for="from_date" class="col-sm-2">Enter Number/Name: </label>
                       <div class="col-sm-3">
                          <textarea id="txtsearch" class="form-control" maxlength="50" name="txtsearch"><?php echo set_value('txtsearch');?></textarea>
                          <span class="error"><?php echo form_error('txtsearch');?></span>
                       </div>
                 </div>
                  
                <div class="box-footer">
                    <div class="col-sm-2 col-xs-offset-5">
                      <input type="submit" class="btn btn-info" name="btnSearch" id="btnSearch" value="Submit">
                     <button type="reset" class="btn btn-default pull-right"  name="btnReset" id="btnReset">Reset</button>
                    </div>
                </div>
             </div>
          </div>
        </div>
      </form>
      <?php if( count($result) > 0 ){ ?>
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Members / Candidates Support Services (HELP) (BETA)</h3>
              <div class="pull-right">
                <a href="<?php echo base_url();?>admin/CmsComplaints/send_mail" class="btn  btn-primary">Send Email</a>
                <a href="<?php echo base_url();?>admin/CmsComplaints/download" class="btn  btn-primary">Download as CSV</a>
              	<input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
                <input type="hidden" name="base_url_val" id="base_url_val" value="" /> 
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <div class="table-responsive">
            	<table id="listitems" class="table table-bordered table-striped dataTables-example">
                    <thead>
                        <tr>
                            <th><!--<input type="checkbox" id="selectall"/>--></th>
                            <th id="srNo">S.No.</th>
                            <th id="sub_cat_cd">Complaint ID</th>
                            <th id="category_code">Query Category</th>
                            <th id="subcatcode">Query Sub-Category</th>
                            <th id="exam_code">Exam Name</th>
                            <th id="regnumber">Membership Registration No</th>
                            <th id="">I Don't Remember Membership Registration No</th>
                            <th id="emp_name">Employer Name</th>
                            <th id="dob">Date of Birth</th>
                            <th id="mem_name">Name</th>
                            <th id="email">Email ID</th>
                            <th id="mobile">Mobile No</th>
                            <th id="complain_details">Query In Details</th>
                            <th id="">Upload File</th>
                        </tr>
                    </thead>
                     <tbody class="no-bd-y" id="list">
                          <?php 
                              $i = 1;
                              foreach($result as $row){ 
                              ?>
                              	<tr>
                                <td align="center">
                       				<input type="checkbox" class="chkmakepay" name="chkmakepay[]" value="<?php echo $row['compid'];?>"/>
                                </td>
                                <td><?php echo $i;?></td>
                                <td><?php echo $row['sub_cat_cd'];?></td>
                                <td><?php echo $row['category_code'];?></td>
                                <td><?php echo $row['subcatcode'];?></td>
                                <td><?php echo $row['exam_code'];?></td>
                                <td><?php echo $row['regnumber'];?></td>
                                <td><?php if( empty( $row['regnumber'] ) ) echo '2'; else echo '1'; //2 for do not remember and 1 for remeber ?></td>
                                <td><?php echo $row['emp_name'];?></td>
                                <td><?php echo $row['dob'];?></td>
                                <td><?php echo $row['mem_name'];?></td>
                                <td><?php echo $row['email'];?></td>
                                <td><?php echo $row['mobile'];?></td>
                                <td><?php echo $row['complain_details'];?></td>
                                <td><?php if( !empty( $row['attachment'] ) ) { ?> <a href="<?php echo base_url().'uploads/cms/'.$row['attachment'];?>" target="_blank"><?php echo $row['attachment'];?></a> <?php }?></td>
                            </tr>
                          <?php $i++; 
						  	} 
						  ?>                
                        </tbody>
                </table>
                <div style="width:30%; float:left;">
                    <?php //echo $info; ?>
                </div>
                <div id="links" class="" style="float:right;"><?php //echo $links; ?></div>  
                
            </div>
               
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
	  <?php } ?>  
    </section>
  </div>
<script src="<?php echo base_url()?>assets/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/datepicker/datepicker3.css">

<script type="text/javascript">
$(function () {
	$('#from_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){
		$('#to_date').datepicker('setStartDate', new Date($(this).val()));
	}); 
	
	$('#to_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){
		$('#from_date').datepicker('setEndDate', new Date($(this).val()));
	});
});
</script>
<?php $this->load->view('admin/includes/footer');?>