<script>
$(document).ready(function(){
$('#confirm').modal('show');
});
function Show(){ 
$('#confirm').modal('hide');
$('#confirmTwo').modal('show');
} 
</script>
<div class="modal fade" id="confirm"  role="dialog" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header"> 
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <div class="message" style="color:#F00; text-align:center; font-size:20px;"><strong>VERY IMPORTANT</strong></div>
        <br />
        <br />
        <div class="message" style="color:#F00; text-align:justify;font-size:16px;"> Candidates are required to take utmost care and precaution in selecting Centre, Venue and Time slot, as there is no provision to change the Centre, Venue and Time slot in the system.<br />
          <br />
          Hence no request for change of centre, venue and time slot will be entertained for any reason.<br />
          <br />
          THE FEES ONCE PAID WILL NOT BE REFUNDED OR ADJUSTED ON ANY ACCOUNT</div>
      </div>
      <div class="modal-footer"><!--data-dismiss="modal"-->
        <input type="button" name="btnSubmit"  class="btn btn-primary" id="btnSubmit" value="Okay" onclick="Show();" >
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="confirmTwo"  role="dialog" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header"> 
        <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <div class="message" style="color:#F00; text-align:center; font-size:20px;"><strong>VERY IMPORTANT</strong></div>
        <br />
        <br />
        <div class="message" style="color:#F00; text-align:justify;font-size:16px;"> For candidates who are unable to view the Venue details, in the drop down list they are required to do the following to solve this issue.<br />
          <br />
          Clear the browsers history by going to the settings menu of the browser and click the <strong>"Clear browsing history"</strong>.After clearing the browsing history candidates are required to close the browser and start again for registration. </div>
      </div>
      <div class="modal-footer">
        <input type="button" name="btnSubmit_two"  data-dismiss="modal" class="btn btn-primary" id="btnSubmit_two" value="Okay" >
      </div>
    </div>
  </div>
</div>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Exam Instructions</h1>
    </section>
    <section class="content">
        <div class="row">
        	<div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="box box-info">
                    <!--<div class="box-body">-->
                    <div class="">
                    	<?php echo htmlspecialchars_decode($instructions);?> 
                    </div><!-- .box-body -->
                 </div><!-- .box-info -->
             </div><!--.col-md-8-->
             <div class="col-md-2"></div>
       </div><!--.row-->
   </section><!--.content-->
</div><!-- .content-wrapper -->