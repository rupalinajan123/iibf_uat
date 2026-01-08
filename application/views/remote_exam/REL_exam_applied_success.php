<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> </h1>
    <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>--> 
  </section>
  <form class="form-horizontal" name="member_exam_comApplication" id="member_exam_comApplication"  method="post"  enctype="multipart/form-data" >
    <section class="content">
      <div class="row">
        <div class="col-md-12"> 
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"></h3>
            </div>
            <!-- /.box-header --> 
            <!-- form start -->
            <div class="box-body">
              <ul>
                <li>On successful completion of transaction confirmation SMS/email will be sent to the candidate intimating the receipt of examination forms. </li>
                <li>In case the candidate does not receive confirmation SMS/email from the institute, the candidate should apply again till he receives the confirmation SMS/email. </li>
                <li>Please note that Institute will not accept any responsibility in case of failed transactions. However fees debited if any to candidate's account will be refunded within seven working days of the transaction. Candidates need to reapply in such case. </li>
              </ul>
            </div>
          </div>
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Your admitcard pdf forwarded to your registered e-mail id.</h3>
            </div>
            <!-- /.box-header --> 
            <!-- form start -->
            <div class="box-body">
              <div class="form-group" >
                <label for="roleid" class="col-sm-3 control-label">Membership No</label>
                <div class="col-sm-1"> <?php echo $admitcard_info[0]['mem_mem_no'];?> </div>
                <span></span>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Name</label>
                <div class="col-sm-6">  
					<?php echo str_replace("\\'","",html_entity_decode($exam_name[0]['description']));?> 
                     <span class="error"><?php //echo form_error('firstname');?></span>
                  </div>
              </div>
              <?php /*?><div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Fee Amount</label>
                	<div class="col-sm-5">  <?php echo $fee_amt[0]['fee_amt']. ' + GST AS APPLICABLE';?>  </div>
              </div><?php */?>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Period</label>
                <div class="col-sm-5">
                  <?php   echo 'JULY-SEP 2020';?>
                  </div>
              </div>
              
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Mode</label>
                <div class="col-sm-5">
                  <?php echo 'Online'; ?>
				</div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Medium</label>
                <div class="col-sm-5">
                  <?php echo 'English';?>
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Center</label>
                <div class="col-sm-5">
                  <?php echo 'Remote Proctored Exam';?>
                  <span class="error">
                  <?php //echo form_error('email');?>
                  </span> </div>
              </div>
            </div>
            <div style="text-align:left"> 
            <a href="<?php echo base_url()?>/uploads/admitcardpdf/<?php echo $admitcard_info[0]['admitcard_image']?>" target="_blank">Download admitcard</a> &nbsp;&nbsp;
           <?php
					  		$link = '#';
							if($applied_exam_info[0]['exam_code'] == 1002){
								$link = 'https://www.amazon.in/dp/B088FXM796';
							}elseif($applied_exam_info[0]['exam_code'] == 1003){
								$link = 'https://www.amazon.in/Micro-Small-Medium-Enterprises-India-ebook/dp/B07Z9CJTDJ/ref=sr_1_1?dchild=1&keywords=9789386394071&qid=1589462229&sr=8-1';
							}elseif($applied_exam_info[0]['exam_code'] == 1004){
								$link = 'https://www.amazon.in/dp/B088FXM796';
							}elseif($applied_exam_info[0]['exam_code'] == 1005){
								$link = 'https://www.amazon.in/Bankers-Handbook-Credit-Management-IIBF-ebook/dp/B07KPGWNCM/ref=tmm_kin_swatch_0?_encoding=UTF8&qid=1542791029&sr=8-1';
							}elseif($applied_exam_info[0]['exam_code'] == 1008){
								$link = 'https://www.amazon.in/Bankers-Handbook-Accounting-Institute-Banking-ebook/dp/B089FY5BXQ/ref=sr_1_1?keywords=9789387957251&qid=1594714135&s=books&sr=1-1';
							}elseif($applied_exam_info[0]['exam_code'] == 1010){
								$link = 'https://www.amazon.in/Customer-Service-Banking-Codes-Standards-ebook/dp/B07Y9Y23YY/ref=sr_1_1?dchild=1&keywords=9789386189707&qid=1594713806&sr=8-1';
							}elseif($applied_exam_info[0]['exam_code'] == 1011){
								$link = 'https://www.amazon.in/Security-Indian-Institute-Banking-Finance-ebook/dp/B07Z9DF2RY/ref=sr_1_1?dchild=1&keywords=9789350719572&qid=1594713837&sr=8-1';
							}elseif($applied_exam_info[0]['exam_code'] == 1012){
								$link = 'https://www.amazon.in/Information-System-Institute-Banking-Finance-ebook/dp/B089FZ8JTN/ref=sr_1_2?dchild=1&keywords=9789386394439&qid=1594713869&sr=8-2';
							}elseif($applied_exam_info[0]['exam_code'] == 1013){
								$link = 'https://www.amazon.in/Digital-Banking-Indian-Institute-Finance-ebook/dp/B082XB2SCX/ref=sr_1_1?dchild=1&keywords=9789389546347&qid=1594713890&sr=8-1';
							}elseif($applied_exam_info[0]['exam_code'] == 1014){
								$link = 'https://www.amazon.in/International-Finance-Indian-Institute-Banking-ebook/dp/B07Z9DZYC3/ref=sr_1_1?dchild=1&keywords=9789386394729&qid=1594713911&sr=8-1';
							}
						?>
            
            <a href="<?php echo $link;?>" target="_blank">Ebook</a> 
            </div>
            <?php /*?><div class="box-footer">
              <div class="col-sm-2 col-xs-offset-5"> <a href="<?php echo  base_url()?>Remote_exam/logout/">Logout</a> </div>
            </div><?php */?>
          </div>
          
          <!-- Basic Details box closed--> 
          
        </div>
      </div>
    </section>
</form>
</div>
<!-- Data Tables --> 
<script src="<?php echo base_url()?>assets/admin/plugins/jQuery/jQuery-2.2.0.min.js"></script>
<script>

(function (global) {
	if(typeof (global) === "undefined")
	{
		throw new Error("window is undefined");
	}
    var _hash = "!";
    var noBackPlease = function () {
        global.location.href += "#";
		// making sure we have the fruit available for juice....
		// 50 milliseconds for just once do not cost much (^__^)
        global.setTimeout(function () {
            global.location.href += "!";
        }, 50);
    };
	// Earlier we had setInerval here....
    global.onhashchange = function () {
        if (global.location.hash !== _hash) {
            global.location.hash = _hash;
        }
    };
    global.onload = function () {
		noBackPlease();
		// disables backspace on page except on input fields and textarea..
		document.body.onkeydown = function (e) {
            var elm = e.target.nodeName.toLowerCase();
            if (e.which === 8 && (elm !== 'input' && elm  !== 'textarea')) {
                e.preventDefault();
            }
            // stopping event bubbling up the DOM tree..
            e.stopPropagation();
        };
    };
})(window);
</script>
