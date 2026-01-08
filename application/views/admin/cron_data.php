<?php $this->load->view('admin/includes/header');?>
<?php $this->load->view('admin/includes/sidebar');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo $title; ?>
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
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Search By</h3>
              <div class="pull-right">
              	<!--<a href="<?php echo base_url();?>admin/Search/search_success" class="btn btn-info">Refresh</a>-->
              </div>
            </div>
           <div class="box-body">
            
                <div class="col-sm-12">
                <form class="form-horizontal" name="search" id="search" action="" method="post">
                   <div class="form-group">
                   		
                       <label for="from_date" class="col-sm-1">Additional WHERE Clause :</label>
                        <div class="col-sm-4">
                       <textarea id="whereCondition" name="whereCondition" required  cols="50" rows="4"></textarea>
                       </div>
                       
                       <div class="col-sm-1">&nbsp;</div>
                       
                       <div class="col-sm-6">
                       <?php if(stristr(current_url(),'Crondata/member')) { ?>
                        <span class=""><strong>Example :</strong> &nbsp; 1. AND regnumber IN (510313364,510313371,510313372,510313373) <br>
											 2. AND DATE(createdon) = "YYYY-mm-dd" <br>  </span>
                       <?php } else if(stristr(current_url(),'Crondata/exam')) { ?>
                       <span class=""><strong>Example :</strong> &nbsp; 1. AND `c`.`regnumber` IN (510042272, 510120489, 510250039, 6523313) <br>
											 2. AND DATE(a.created_on) >= 'YYYY-mm-dd' <br>  
                       						 3. AND pg_flag = 'IIBF_EXAM_REG' (NM registration with exam application) <br />
                                             4. AND a.exam_code =<?php echo $this->config->item('examCodeJaiib') ?>                     
                       </span>
                       <?php } else if(stristr(current_url(),'Crondata/edit_data')) { ?>
                       <span class=""><strong>Example :</strong> &nbsp; 1. AND regnumber IN (510042272, 510120489, 510250039, 6523313) <br>
											 2. AND DATE(editedon) = 'YYYY-mm-dd' <br>  
                       						 
                       </span>
                       <?php } else if(stristr(current_url(),'Crondata/dra_exam')) { ?>
                       <span class=""><strong>Example :</strong> &nbsp; 1. AND d.regnumber IN (801152336,801152337) <br>
											 2. AND ( DATE(a.date) = 'YYYY-mm-dd' OR DATE(a.updated_date) = 'YYYY-mm-dd') <br>
                                             3. AND c.exam_code = 45   
                       </span>
                        <?php } else if(stristr(current_url(),'Crondata/dra_member')) { ?>
                       <span class=""><strong>Example :</strong> &nbsp; 1. AND d.regnumber IN (801152336,801152337) <br>
											 2. AND ( DATE(a.date) = 'YYYY-mm-dd' OR DATE(a.updated_date) = 'YYYY-mm-dd') <br>
                                             3. AND c.exam_code = 45   
                       </span>
                      <?php } else if(stristr(current_url(),'Crondata/dup_icard')) { ?>
                       <span class=""><strong>Example :</strong> &nbsp; 1. AND a.regnumber IN (510042272, 510120489) <br>
											 2. AND DATE(added_date) = 'YYYY-mm-dd' <br>
                       </span>
                       <?php } ?>
                       </div>
                      <br>
                      <!--<span class="col-sm-2"> </span>-->
                      
                   </div>
                   
                   
                   <div class="form-group">
                        <label for="from_date" class="col-sm-2">&nbsp;</label>
                        <input type="submit" class="btn btn-info" name="generate_data" id="generate_data" value="Generate" >
                   </div>
                   <div class="form-group">
                   	<div class="col-md-6">
                    	<?php 
						if($this->session->flashdata('last_query')!='')
						{	
							//echo $this->session->flashdata('last_query');  
						} ?>
                    </div>
                   </div>
                 </form>
                 </div>
              
              </div>
          </div>
        </div>
      </div>
      
     
      
    </section>
   
  </div>
  
<!-- Data Tables -->

<link href="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">

<!-- Data Tables -->
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>

<script src="<?php echo base_url()?>assets/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/datepicker/datepicker3.css">

<script src="<?php echo base_url()?>assets/admin/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/timepicker/bootstrap-timepicker.min.css">

<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script>
<script src="<?php echo base_url()?>js/validation.js"></script>
<script type="text/javascript">
  $('#search').parsley('validate');
</script>

<!--<script src="<?php echo base_url()?>js/js-paginate.js"></script>-->
<script type="application/javascript">
$(document).ready(function() 
{
	
});

$(function () {
	//$("#listitems").DataTable();
	/*var base_url = '<?php //echo base_url(); ?>';
	var listing_url = base_url+'admin/Report/getList';
	
	// Pagination function call
	paginate(listing_url,'','','');
	$("#base_url_val").val(listing_url);*/
});
		

		
</script>
 
<?php $this->load->view('admin/includes/footer');?>