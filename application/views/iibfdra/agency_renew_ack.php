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
              <div class="pull-right"> <!--<a href="< ?php echo base_url();?>iibfdra/Center/listing" class="btn btn-warning">Back</a>--> </div>
            </div>
            
             <div class="form-group">
              <label for="roleid" class="col-sm-3 control-label">Agency Name :</label>
              <div class="col-sm-5"> <?php echo $agency_details[0]['inst_name'];?> </div>
            </div>
            <div class="form-group">            
              <label for="roleid" class="col-sm-3 control-label">Center Name :</label>
              <div class="col-sm-5" style="padding:0;">
              <ol>
               <?php
			  // $k = 1;
			 
			   if(count($center_arr) > 0 )
			   foreach($center_arr as $center_info){
				   
				  // print_r($center_info);
				   
				   if( $center_info['location_name']!= "")
                      {
                          echo '<li>'.$center_info['city_name'].'</li>';
                      } 
                      else
                      {
                         echo '<li>'.$center_info['location_name'].'</li>'; 
                      }
				   
				  // $k++;
			   }
                      ?> 
                  <?php //echo @$center_info[0]['location_name'];?> 
                  </ol>
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
					echo '<strong>Fail</strong>';
			}
			else  if(@$payment_info[0]['status']==1)
			{
				echo '<strong>Success</strong>';
			}
			else if(@$payment_info[0]['status']==2)
			{
				echo '<strong>Pending</strong>';
			}?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </form>
</div>
