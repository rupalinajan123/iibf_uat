<?php $this->load->view('memapplyexam_dbf/front-header'); ?>

<div class="content-wrapper"> 
  <form class="form-horizontal" >
    <input type="hidden" name="regid" id="regid" value="<?php echo $this->session->userdata('regid');?>">
    <section class="content">
      <div class="row">
        <div class="col-md-12"> 
					<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Your transaction details are forwarded to your registered e-mail id.</h3>
						</div>
            
						<div class="box-body">
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Membership No</label>
                <div class="col-sm-1"> <?php echo $this->session->userdata('mregnumber_applyexam');?> </div>
							</div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Name</label>
                <div class="col-sm-3"> <?php echo $examinfo[0]['description'];?></div>
							</div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Mode</label>
                <div class="col-sm-5"><?php echo 'Online';?></div>
							</div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Center</label>
                <div class="col-sm-5">
                  <?php 
										if(isset($center[0]['center_name'])){
											echo $center[0]['center_name'];
										}
									?>
								</div>
							</div>
              
							<?php if($this->session->userdata('memexcode') == $this->config->item('examCodeJaiib')){?>
								
								<div style="text-align:left"> 
									Ebook Link
								</div>
								
								<div style="text-align:left"> 
									<a href="https://www.amazon.in/Principles-Practices-Banking-IIBF-ebook/dp/B08M9GP9J1/ref=sr_1_14?crid=2AMGPIBVBHSR9&dchild=1&keywords=principles+and+practices+of+banking+iibf&qid=1604551798&sprefix=principles+and+%2Caps%2C322&sr=8-14" target="_blank">Principles & Practices of Banking (ISBN - 9789387687240)</a>
								</div>
								
								<div style="text-align:left"> 
									<a href="https://www.amazon.in/Accounting-Finance-Bankers-IIBF-ebook/dp/B08M9Q8M7M/ref=sr_1_2?dchild=1&keywords=Accounting+%26+Finance+for+Bankers+by+iibf&qid=1604561904&sr=8-2" target="_blank"> Accounting & Finance for Bankers (ISBN - 9789387687226)</a>
								</div>
								
								<div style="text-align:left"> 
									<a href="https://www.amazon.in/Legal-Regulatory-Aspects-Banking-IIBF-ebook/dp/B08M97G4KL/ref=sr_1_fkmr0_2?dchild=1&keywords=%E2%80%A2+Legal+%26+Regulatory+Aspects+of+Banking+%28ISBN+-+9789387687233%29&qid=1604561855&sr=8-2-fkmr0" target="_blank">Legal & Regulatory Aspects of Banking (ISBN - 9789387687233)</a> 
								</div>
							<?php }?>
              
						</div>
					</div>
				</div>
			</div>
		</section>
	</form>
</div>
<?php $this->load->view('memapplyexam_dbf/front-footer'); ?>