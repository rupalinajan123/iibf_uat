<?php $this->load->view('admin/includes/header');?>
<?php $this->load->view('admin/includes/sidebar');?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Add Page</h1>
      <?php echo $breadcrumb; ?>
    </section>
	<form class="form-horizontal" action="" method="post" enctype="multipart/form-data" name="addPage" id="addPagefrm">
    <!-- Main content -->
    <section class="content">
    	<div class="row">
      		<div class="col-md-12">
            <!-- Horizontal Form -->
          		<div class="box box-info">
            		<div class="box-header with-border">
              			<h3 class="box-title">Pages - Add</h3>
            		</div>
            		<!-- /.box-header -->
            		<!-- form start -->
            		<div class="box-body">
						<?php echo validation_errors(); ?>
                        <?php if($this->session->flashdata('error')!=''){?>
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                                <?php echo $this->session->flashdata('error'); ?>
                            </div>
                        <?php } if($this->session->flashdata('success_message')!=''){ ?>
                            <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
                            <?php echo $this->session->flashdata('success_message'); ?>
                            </div>
                        <?php } ?> 
	                	<div class="form-group">
                			<label for="title" class="col-sm-2 control-label">Title <span class="red"> *</span></label>
                			<div class="col-sm-3">
                      			<input type="text" class="form-control" id="title" name="title" placeholder="Page Title" required value="<?php echo set_value('title');?>" >
                      			<span class="error"><?php echo form_error('title');?></span>
                    		</div>
                       		<label for="url_word" class="col-sm-2 control-label">URL Title <span class="red"> *</span></label>
                     		<div class="col-sm-3">
                        		<input type="text" class="form-control" id="url_word" name="url_word" placeholder="URL Title" required value="<?php echo set_value('url_word');?>" >
                         		<span class="error"><?php echo form_error('url_word');?></span>
                    		</div>
                    	</div>
                        <div class="form-group">
                            <label for="page_description" class="col-sm-2 control-label">Description <span class="red"> *</span></label>
                             <div class="col-sm-10">
                                 <textarea id="description" name="description" rows="10" cols="80" requierd><?php echo set_value('description');?>
                                 </textarea>
                                 <span class="error"><?php echo form_error('description');?></span>
                            </div>
                        </div>
                        <div class="form-group">
                			<label for="page_type" class="col-sm-2 control-label">Page Type <span class="red"> *</span></label>
                			<div class="col-sm-3">
                      			<input type="text" class="form-control" id="page_type" name="page_type" placeholder="Page Type" required value="<?php echo set_value('page_type');?>" >
                      			<span class="error"><?php echo form_error('page_type');?></span>
                    		</div>
                       		<label for="status" class="col-sm-2 control-label">Select Status <span class="red"> *</span></label>
                            <?php
							$activesel = $inactivesel = '';
                            if( set_value('status') ) {
								if( set_value('status') == 'Active' ) 
									$activesel = "selected='selected'";
								elseif( set_value('status') == 'Inactive' ) 
									$inactivesel = "selected='selected'"; 
							}
							?>
                     		<div class="col-sm-3">
                        		<select name="status" id="status" class="form-control" required>
                                    <option value="">--Select Status--</option>
                                    <option value="Active" <?php echo $activesel;?>>Active</option>
                                    <option value="Inactive" <?php echo $inactivesel;?>>Inactive</option>
                                </select>
                         		<span class="error"><?php echo form_error('status');?></span>
                    		</div>
                    	</div>
                
					</div>
                    <div class="box-footer">
                    	<div class="col-sm-2 col-xs-offset-5">
                        	<input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Submit">
                        	<a href="<?php echo base_url();?>admin/Pages" class="btn btn-default pull-right">Back</a>
                        </div>
                    </div>
				</div>
			</div>
      </div>
	</section>
    </form>
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
<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script>
<script type="text/javascript">
	$('#addPagefrm').parsley('validate');
</script>
<script src="<?php echo base_url();?>assets/admin/plugins/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
	$(function () {
		CKEDITOR.replace('description');
	});
</script> 
<?php $this->load->view('admin/includes/footer');?>