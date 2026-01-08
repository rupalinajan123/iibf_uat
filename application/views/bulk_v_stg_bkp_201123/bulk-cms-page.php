
<!--<div class="wrapper">-->
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1> Exam List </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Exam List</a></li>
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
            <form class="form-horizontal" name="agree_diagree_Form" id="agree_diagree_Form"  method="post"  enctype="multipart/form-data" action="<?php echo base_url();?>bulk/BulkApply/exam_applicantlst/">
            <div class="box-body" style="display: block;">
              
                   <?php 
					if(count($exam_info) >0 && $exam_info[0]['member_instruction']!='')
					{
						//echo htmlspecialchars_decode( $exam_info[0]['member_instruction']);
						$newstring = str_replace("#url#", "".base_url()."", htmlspecialchars_decode( $exam_info[0]['member_instruction']));
				    	echo  $finalstring = str_replace("{url}", "javascript:void(0);", $newstring);
					}
					?>   
                    <div class="form-group">
                	<div class="col-sm-12">
                    <input name="declaration1" value="1" id="agree" type="checkbox" required>&nbsp;I
 have read the Rules and Regulations and other instructions governing 
the above examination and I agree to abide by the said Rules, 
Regulations and Instructions
                  </div>
                </div>
              <div class="box-footer">
                  <div class="col-sm-4 col-xs-offset-3">
                    <input type="hidden" name="excode" id="excode" value="<?php echo base64_decode($this->input->get('excode2'));?>">
                      <?php 
					  $this->session->set_userdata('examcode',base64_decode($this->input->get('excode2')));
					  ?>
                    <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="I Agree" onclick="javascript:return checkEditImage()">
                     <a href="<?php echo base_url();?>bulk/Bankdashboard/" class="btn btn-info">I Disagree</a>
                    </div>
              </div>                
            </div>
            </form>
            <!-- /.box-body --> 
            
          </div>
          <!-- /.box --> 
        </div>

      </div>
      
         
    </section>
    <!-- /.content --> 
  </div>
  <!-- /.content-wrapper -->

