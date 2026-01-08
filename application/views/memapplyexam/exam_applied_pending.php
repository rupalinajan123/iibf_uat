  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      </h1>
      <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>-->
    </section>
	<form class="form-horizontal" name="member_exam_comApplication" id="member_exam_comApplication"  method="post"  enctype="multipart/form-data" action="<?php echo base_url();?>Applyexam/Msuccess/">
   <input type="hidden" name="regid" id="regid" value="<?php echo $this->session->userdata('mregid_applyexam');?>"> 
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
           <li>On successful completion of transaction confirmation SMS/email will be sent to the candidate intimating the receipt of examination forms.
					</li><li>In case the candidate does not receive confirmation SMS/email from the institute, the candidate should apply again till he receives the confirmation SMS/email.
					</li><li>Please note that Institute will not accept any responsibility in case of failed transactions. However fees debited if any to candidate's account will be refunded within seven working days of the transaction. Candidates need to reapply in such case. 
				</li>
                </ul>
                </div>
                
               </div>
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"> Your transaction is under process</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
            
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Transaction Number</label>
                	<div class="col-sm-5">
                      <?php echo $payment_info[0]['transaction_no'];?>
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Transaction Status</label>
                	<div class="col-sm-5">
                      <?php if($payment_info[0]['status']=='2'){echo 'Pending';}else{echo 'NA';}?>
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Transaction Date</label>
                	<div class="col-sm-5">
                      <?php echo $payment_info[0]['date'];?>
                    </div>
                </div>
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