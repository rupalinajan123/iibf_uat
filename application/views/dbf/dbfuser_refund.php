<!-- Content Wrapper. Contains page content -->
<?php $this->load->view('dbf/front-header-dbnf');?>
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
              <h3 class="box-title">  <img src="<?php echo base_url()?>assets/images/logo1.png"></h3>
            </div>
            <!-- /.box-header --> 
            <!-- form start -->
            <div class="box-body">
              
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
                <label for="roleid" class="col-sm-3 control-label">Application Fail:</label>
                <div class="col-sm-6"> <strong style="color:#000">Center capacity for <?php 
				if(isset($exam_name[0]['description']))
				{
					echo  $exam_name[0]['description'];
				}?> has been full.</strong> </div>
              </div><br />
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Transaction Number</label>
                <div class="col-sm-5"> <?php echo $payment_info[0]['transaction_no'];?> </div>
              </div><br />
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Transaction Status:</label>
                <div class="col-sm-5"> <?php if($payment_info[0]['status']==0)
                {
                echo 'Fail';
                }
                else  if($payment_info[0]['status']==1)
                {
                echo 'Success';
                }
                else if($payment_info[0]['status']==2)
                {
                echo 'Pending';
                } ?> </div>
              </div><br />
          
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Note:</label>
                <div class="col-sm-5">   <div style="color:#F00">Refund for this application  will be initiated within 7 to 10 days!</div> </div>
              </div><br />
              
              
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
</script><?php $this->load->view('dbf/front-footer-dbnf');?>