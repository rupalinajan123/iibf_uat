<style type="text/css">

.form-horizontal .controls {
    margin-left: 190px !important;
}

.form-horizontal .control-label {
	
	width: 180px !important;
}
label {
	font-weight: bold;
}
.error {
	color: red;
	font-size: 12px;
}
.po_in_rec_r, .po_in_rec_l {
	float: left;
	width: 50%;
	padding: 0 15px;
	box-sizing: border-box;
}
.w_100 {
	float: left;
	width: 100%;
}
.d_inline_block {
	display: inline-block;
}
.po_in_rec_btn .form-actions {
	padding-left: 20px !important;
}
.inward_wrap {
	height: auto !important;
}
.mb-2 {
	margin-bottom: 0.5em;
}
.check-demo-col {
	width: 29.00% !important;
}

 @media screen and (max-width:991px) {
.po_in_rec_r, .po_in_rec_l {
	width: 100%;
}
}
</style>
<script>
$(document).ready(function(){
    
        $("#loader_div").hide();
		$("center").hide();
   
});
$("#save_forword").click(function(){
       $("#loader_div").show();
		$("center").show();
});
$("#save").click(function(){
       $("#loader_div").show();
		$("center").show();
});
</script>



<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>


<div class="container-fluid" id="content"> 
  <div id="main" class="nomar-left">
    <div class="container-fluid">
      <div class="row-fluid ">
        <div class="span12">
          <div class="box box-bordered box-color">
            <?php 
			
            	if($this->session->flashdata('success_message') != ""){ 
			?>
            <br>
			
            <div class="alert alert-success" style="text-align:center">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <input type="hidden" name="success_message" id="success_message" value="yes" />
              <strong><?php echo $this->session->flashdata('success_message');?></strong> </div>
            <?php } ?>
			<br>
			<?php if ($this->session->flashdata('error') != '') {?>
     		 <div class="alert alert-error">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
          <?php echo $this->session->flashdata('error');?> </div>
        <?php }?>
            <div class="box-title">
              <h3> <?php echo $this->lang->line('Add New E-Meeting Form',false); ?> </h3>
            </div>
            <?php 
				if (validation_errors()!="" ){ 
			?>
            <br>
            <div class="alert alert-error">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <?php 
                    echo validation_errors();
                    //echo $error; ?>
            </div>
            <?php } ?>
			
			
            <div class="box-content nopadding">
              <form name="frm_emeeting_update" class="form-horizontal form-bordered" method="post" enctype="multipart/form-data" id="frm_emeeting_update">
			  
			  <?php if(!empty($meeting_info))
			  {?>
			  <div class="po_in_rec_l">
                <div class="control-group">
                  <label class="control-label" for="textfield"> <?php echo $this->lang->line('Meeting Title',false); ?> <font color="#FF0000"> * </font> </label>
                  <div class="controls">
                    <input type="text" class="input-large" name="title" id="title" value="<?php 
					if(isset($meeting_info[0]['title']))
					{
						echo $meeting_info[0]['title'];
					}else
					{
						echo $this->input->post('title');
					}?>" placeholder = "<?php echo  $this->lang->line('Meeting Title',false);?>"/>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="textfield"> <?php echo $this->lang->line('Meeting Subject',false); ?> <!-- <font color="#FF0000"> * </font> --> </label>
                  <div class="controls">
                    <input type="text" class="input-large" name="subject" id="subject" value="<?php 
					if(isset($meeting_info[0]['subject']))
					{
						echo $meeting_info[0]['subject'];
					}else
					{
						echo $this->input->post('subject');
					} ?>" placeholder = "<?php echo  $this->lang->line('Meeting Subject',false);?>"/>
                  </div>
                </div>
				
				
				<div class="control-group">
                  <label class="control-label" for="textfield"> <?php echo $this->lang->line('Meeting Description/Agenda',false); ?><font color="#FF0000"> * </font> </label>
                  <div class="controls">
                    <input type="text" class="input-large" name="description" id="description" value="<?php if(isset($meeting_info[0]['description']))
					{
						echo $meeting_info[0]['description'];
					}else
					{
						echo $this->input->post('description');
					} ?>" placeholder = "<?php echo  $this->lang->line('Meeting Description/Agenda',false);?>"/>
                  </div> 
                </div>
                <div class="control-group">
                  <label class="control-label" for="textfield"> <?php echo $this->lang->line('Meeting Date',false); ?> <font color="#FF0000"> * </font> </label>
                  <div class="controls">
                    <input type="text" name="file_date" id="file_date"  class="input-large" value="<?php 
					if($meeting_info[0]['date']!='0000-00-00' || $meeting_info[0]['date']!=' ')
					{
						echo $meeting_info[0]['date'];
					}else				{
						echo $this->input->post('date');
					}
					
					?>" readonly="readonly"  placeholder = "<?php echo  $this->lang->line('Meeting Date',false);?>" />
                  </div>
                </div>
				
				<div class="control-group">
                  <label class="control-label" for="textfield"> <?php echo $this->lang->line('Meeting Time',false); ?> <font color="#FF0000"> * </font> </label>
                  <div class="controls">
				 
                  
			 <div class="input-group bootstrap-timepicker timepicker">
			<input id="timepicker1" name="time" type="text" class="form-control input-small" value="<?php 
					if($meeting_info[0]['time']!='' )
					{
						echo $meeting_info[0]['time'];
					}else				{
						echo $this->input->post('time');
					}
					
					?>">
			 <span class="input-group-addon"><h4><i class="icon-time"></h4></i></span>
			 </div>
			<script type="text/javascript">
			  $('#timepicker1').timepicker();
			 </script>
                  </div>
                </div>
				
				
				<div class="control-group">
                  <label class="control-label" for="textfield"> <?php echo $this->lang->line('Meeting Venue',false); ?> <font color="#FF0000"> * </font> </label>
                  <div class="controls">
				 
                    <input type="text" name="venue" id="venue"  class="input-large" value="<?php if(									$meeting_info[0]['venue']!='' )
					{
						echo $meeting_info[0]['venue'];
					}else				{
						echo $this->input->post('venue');
					}?>"  placeholder = "<?php echo  $this->lang->line('Meeting Venue',false);?>" />
                  </div>
                </div>
				
				
                <div class="control-group">
                  <label class="control-label" for="textfield"> <?php echo $this->lang->line('Remarks',false); ?> </label>
                  <div class="controls">
                    <input type="text" class="input-large" name="remark" id="remark" value="<?php if(									$meeting_info[0]['remarks']!='' )
					{
						echo $meeting_info[0]['remarks'];
					}else				{
						echo $this->input->post('remark');
					}?>" placeholder = "<?php echo  $this->lang->line('Remarks',false);?>"/>
                  </div> 
                </div>
			
                
                <div class="control-group">
                  <label class="control-label" for="textfield"> <?php echo $this->lang->line('Upload letter/document',false); ?> </label>
				
                  <div class="controls">
                    <input type="file" name="uploaded_file" id="uploaded_file"/>
					
                  </div>
				   <div class="controls">
				  <?php if($meeting_info[0]['document_uploaded']!='' )
					{?>
					
						<a href="<?php echo base_url().'Uploads/e_meeting/'.$meeting_info[0]['document_uploaded'];?>" download><?php echo $meeting_info[0]['document_uploaded'];  ?></a>
					<?php }?></div>
                  <div style="color:#F00; font-size:12px">   ( Note: Max file size 10MB & Allowed to upload only (pdf, doc, docx, xls, xlsx, csv, txt, jpg, jpeg, png)</div>
                </div>
				
				</div>
				<div class="po_in_rec_r">
				
				<div class="control-group" id="div_authority_update">
				<label for="textfield" class="control-label"><?php echo $this->lang->line('Email TO Board',false); ?> <font color="#FF0000"> * </font></label>
				
				
				
				<!-- Multiple Board Dropdown-->
				<div class="controls" id="board_dd_update">
				<select class="input-large" id="authority_update" name="authority[]" multiple>
				<option value=""><?php echo $this->lang->line('- Select -',false); ?></option>
				<?php if(count($boards)){
				foreach($boards as $row){  
				if($this->session->userdata('site_language') == 'marathi'){  ?>
				<option value="<?php echo $row['id'];?>"><?php echo $row['board_marathi']?></option>
				<?php } elseif ($this->session->userdata('site_language') == 'english') { ?>
				<option value="<?php echo $row['id'];?>"><?php echo $row['board']?></option>
				<?php } } } ?>
				</select>
				</div>
				</div>
				<div class="control-group" id="div_department_update">
				<label for="department" class="control-label"><?php echo $this->lang->line('Email TO Attendees/HOD',false); ?><font color="#FF0000"> * </font></label>
				
				<!--Single Department Dropdown--> 
				<!--<div class="controls" id="dept_dd_optoin_update">
				<select class="input-large" id="department" name="department">
				<option value="select"><?php //echo $this->lang->line('- Select -',false); ?> </option>
				</select>
				</div>--> 
				
				<!--Multiple Department Dropdown-->
				<div class="controls" id="dept_dd_optoin_update">
				<select class="input-large" id="department_update" name="department[]" multiple>
				<option value="select"><?php echo $this->lang->line('- Select -',false); ?> </option>
				</select>
				</div>
				<div class="controls" id="other_dept_option" style="display:none;"></div>
				</div>
				
				
				<div class="control-group" id="div_authority_CC_update">
				<label for="textfield" class="control-label"><?php echo $this->lang->line('Email CC Board',false); ?> </label>
				
				
				
				<!-- Multiple Board Dropdown-->
				<div class="controls" id="board_dd_CC_update">
				<select class="input-large" id="authority_CC_update" name="authority_CC[]" multiple>
				<option value=""><?php echo $this->lang->line('- Select -',false); ?></option>
				<?php if(count($boards)){
				foreach($boards as $row){  
				if($this->session->userdata('site_language') == 'marathi'){  ?>
				<option value="<?php echo $row['id'];?>"><?php echo $row['board_marathi']?></option>
				<?php } elseif ($this->session->userdata('site_language') == 'english') { ?>
				<option value="<?php echo $row['id'];?>"><?php echo $row['board']?></option>
				<?php } } } ?>
				</select>
				</div>
				</div>
				<div class="control-group" id="div_department_CC">
				<label for="department" class="control-label"><?php echo $this->lang->line('Email CC Attendees/HOD',false); ?></label>
				
				<!--Single Department Dropdown--> 
				<!--<div class="controls" id="dept_dd_optoin_update">
				<select class="input-large" id="department" name="department">
				<option value="select"><?php //echo $this->lang->line('- Select -',false); ?> </option>
				</select>
				</div>--> 
				
				<!--Multiple Department Dropdown-->
				<div class="controls" id="dept_dd_optoin_update_CC_update">
				<select class="input-large" id="department_CC_update" name="department_CC[]" multiple>
				<option value="select"><?php echo $this->lang->line('- Select -',false); ?> </option>
				</select>
				</div>
				<div class="controls" id="other_dept_option_CC" style="display:none;"></div>
				</div>
				
				
				</div>
				<br />
				<br />
					<div id="loader_div" style="color: White;  position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgb(102, 102, 102); z-index: 30001; opacity: 0.8; margin: 0px;" align="center">
<center>  <img border="0" src="<?php echo base_url();?>images/gif-load.gif"></center> 
			  </div>
                 <div class="w_100 text-center po_in_rec_btn">
                  <div class="form-actions d_inline_block">
                
                 <input type="submit"  class="btn-blue" value="Submit" name="btnSubmit" id="save"/>
				 <input type="reset"  class="btn-blue" value="Reset" name="Reset" id="Reset"/>
               
                 
                
                  <!--<input type="submit"  class="btn-blue" value="<?php echo $this->lang->line('Save',false); ?>" name="btnSubmit" id="save"/>
                  <input type="submit"  class="btn-blue" value="<?php echo $this->lang->line('Save',false); ?> <?php echo $this->lang->line('And',false); ?> <?php echo $this->lang->line('Forword File',false); ?>" name="btnSubmit" id="save_forword"/>
                  <button class="btn-blue" type="reset"><?php echo $this->lang->line('Reset',false); ?></button>-->
                </div>
				</div>
				<?php }?>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="application/javascript" src="<?php echo base_url();?>js/emeeting_validation.js"></script>
<script>
  var session_lang = '<?php echo $this->session->userdata('site_language')?>';
  
	
</script>
</body></html>