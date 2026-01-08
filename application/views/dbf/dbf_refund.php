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
  <form>
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
              <h3 class="box-title">Application fail.</h3>
            </div>
            <!-- /.box-header --> 
            <!-- form start -->
            <div class="box-body">
              <div class="form-group">
                <label for="roleid" class="col-sm-2 control-label">Application Fail:</label>
                <div class="col-sm-0"> <strong style="color:#000">Center capacity for <?php 
				 if(isset($exam_name[0]['description']))
				 {
					 echo $exam_name[0]['description'];
				 }?> has been full.</strong> </div>
              </div><br />
              
               <div class="form-group">
                <label for="roleid" class="col-sm-2 control-label">Transaction Number:</label>
                 <div class="col-sm-0"> <?php echo $payment_info[0]['transaction_no'];?> </div>
              </div><br />
              
                <div class="form-group">
                <label for="roleid" class="col-sm-2 control-label">Transaction Status:</label>
                <div class="col-sm-0">
                  <?php if($payment_info[0]['status']=='1'){echo 'Success';}else{echo 'Unsuccess';}?>
                </div>
              </div><br />
              
              <div class="form-group">
                <label for="roleid" class="col-sm-2 control-label">Transaction Date:</label>
               <div class="col-sm-0"> <?php echo $payment_info[0]['date'];?> </div>
              </div><br />
              
               <div class="form-group">
                <label for="roleid" class="col-sm-2 control-label">Note:</label>
                <div class="col-sm-0">
        <div style="color:#F00">Refund for this application  will be initiated within 7 to 10 days!</div>
              </div></div><br />

          </div>
          </div>
          <!-- Basic Details box closed--> 
        </div>
      </div>
    </section>
  </form>
</div>
<!-- Data Tables --> 

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