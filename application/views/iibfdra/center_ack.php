<style>
.control-label {
	font-weight: bold !important;
}
</style>
<div class="content-wrapper">
  <section class="content-header">
    <h1> Acknowledgement </h1>
  </section>
  <form class="form-horizontal" name="acknowledgement" id="acknowledgement">
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Acknowledgement</h3>
              <div class="pull-right"> <a href="<?php echo base_url();?>iibfdra/Center/listing" class="btn btn-warning">Back</a> </div>
            </div>
            <div class="form-group">
              <label for="roleid" class="col-sm-3 control-label">Center Name :</label>
              <div class="col-sm-5"> <?php
                      if( $center_info[0]['location_name']!= "")
                      {
                          echo $center_info[0]['city_name'];
                      } 
                      else
                      {
                         echo $center_info[0]['location_name']; 
                      }?> 
                  <?php //echo @$center_info[0]['location_name'];?> 
              </div>
            </div>
            <div class="form-group">
              <label for="roleid" class="col-sm-3 control-label">Amount :</label>
              <div class="col-sm-5"> <?php echo @$payment_info[0]['amount'];?> </div>
            </div>
            <div class="form-group">
              <label for="roleid" class="col-sm-3 control-label">Transaction Number : </label>
              <div class="col-sm-5"> <?php echo @$payment_info[0]['transaction_no'];?> </div>
            </div>
            <div class="form-group">
              <label for="roleid" class="col-sm-3 control-label">Transaction Date :</label>
              <div class="col-sm-2"> <?php echo date('d-M-Y',strtotime($payment_info[0]['date'])); ?> </div>
            </div>
            <div class="form-group">
              <label for="roleid" class="col-sm-3 control-label">Transaction Status :</label>
              <div class="col-sm-5">
                <?php  if(@$payment_info[0]['status']==0)
			 {
					echo 'Fail';
			}
			else  if(@$payment_info[0]['status']==1)
			{
				echo 'Success';
			}
			else if(@$payment_info[0]['status']==2)
			{
				echo 'Pending';
			}?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </form>
</div>
