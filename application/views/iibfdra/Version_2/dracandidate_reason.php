<style>
.control-label {
  font-weight: bold !important;
}
label {      
      border-color: #80808059;
}
.types {
    color: green;
    font-weight: 800;
}
.status_div{
 font-weight: 800 !important;
}

.status {
  color: #223fcc;
  font-weight: 800;
}
.myview .form-group{
  clear:both;
}


</style>
<div class="content-wrapper">
  <section class="content-header">
    <h1>Member Exam Status </h1>
   
  </section>
    <section class="content">
      <div class="row myview">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
            
              <div class="pull-right"> <a href="<?php echo base_url();?>iibfdra/Version_2/TrainingBatches/allcandidates/" class="btn btn-warning"> Back </a> </div>
            </div>
            <div class="box-body" style="padding-left: 45px">
            
                     <div class="form-group">
                      <label for="total_candidates" class="col-sm-3 control-label">Member Number :</label>
                      <div class="col-sm-5"> <input type="text" name="member_no" id="member_no" /> <button id="search">Search</button></div>
                    </div>
                     <div class="form-group" id="reasons"><hr></div>
                    <div class="form-group" id="html">
                    </div>
                   
                      
            </div>
          </div>

           
      </div>
    </section>
</div>
<script type="text/javascript">
 $(document).ready(function() {
    
    //  $("body").on("contextmenu",function(e){
    //     return false;
    // });


$('#search').click(function(){
      var member_nos = $('#member_no').val();

      if(member_nos == ''){
        alert("please enter member number");
        return false;
      }
//alert(center_id);
      // AJAX request
      $.ajax({
        url:'<?=base_url()?>iibfdra/Version_2/TrainingBatches/member_search',
        method: 'post',
        data: {member_no: member_nos},
        dataType: 'json',
        success: function(response){
          if(response == 0){
            alert('Number you have entered is not your agency member.Please enter valid number');
           
          }
//alert(response);
         // Remove options 
          //$('#sel_user').find('option').not(':first').remove();
          //$('#sel_depart').find('option').not(':first').remove();

          // Add options
          $.each(response,function(index,data){
            // if(data['error_message'] != ''){
            //   alert("Number you have entered is not your agency member.Please enter valid number");
            //   return false;
            // }
            
            $('#html').html('<div class="form-group"><hr><label for="total_candidates" class="col-sm-3 control-label">Batch name :</label><div class="col-sm-5">'+data['batch_name']+'</div></div><div class="form-group"><label for="total_candidates" class="col-sm-3 control-label">Batch Code :</label><div class="col-sm-5">'+data['batch_code']+'</div></div><div class="form-group"><label for="total_candidates" class="col-sm-3 control-label">Batch Status :</label><div class="col-sm-5">'+data['batch_status']+'</div></div><div class="form-group"><label for="total_candidates" class="col-sm-3 control-label">Batch From date :</label><div class="col-sm-5">'+data['batch_from_date']+'</div></div><div class="form-group"><label for="total_candidates" class="col-sm-3 control-label">Batch To Date :</label><div class="col-sm-5">'+data['batch_to_date']+'</div></div><div class="form-group"><label for="total_candidates" class="col-sm-3 control-label">Name :</label><div class="col-sm-5">'+data['firstname']+' '+data['middlename']+' '+data['lastname']+'</div></div><div class="form-group"><label for="total_candidates" class="col-sm-3 control-label">Re-attempts :</label><div class="col-sm-5">'+data['re_attempt']+'</div></div><div class="form-group"><label for="total_candidates" class="col-sm-3 control-label">Exam Code :</label><div class="col-sm-5">'+data['excode']+'</div></div><div class="form-group"><label for="total_candidates" class="col-sm-3 control-label">Member Number :</label><div class="col-sm-5">'+data['regnumber']+'</div></div><div class="form-group"><label for="total_candidates" class="col-sm-3 control-label">Eligible Exam Code :</label><div class="col-sm-5">'+data['exam_code']+'</div></div><div class="form-group"><label for="total_candidates" class="col-sm-3 control-label">Exam Status :</label><div class="col-sm-5">'+data['exam_status']+'</div></div><div class="form-group"><label for="total_candidates" class="col-sm-3 control-label">App Category :</label><div class="col-sm-5">'+data['app_category']+'</div></div>');       
           
            var Todate = (data['batch_to_date']);
           
      var myDate = new Date(Todate);
      myDate.setDate(myDate.getDate()+290);

       var examdate = new Date(data['exam_date']);
            var examdate1 = examdate.toISOString().slice(0,10);
            var myDate1 = myDate.toISOString().slice(0,10);
  
            if(data['batch_id'] == '' ||  data['batch_id'] == null || data['batch_id'] == 0){
              var reason ="No batch assign to member.";
            }else if(data['batch_status'] == 'R'){
              var reason ="Your batch is Rejected.";
            }else if(data['batch_status'] == 'C'){
              var reason ="Your batch is cancelled.";
            }else if(data['batch_status'] == 'IR'){
              var reason ="Your batch is InReview.";
            }else if(examdate1 > myDate1){
              var reason ="290 Days are over with the associated batch.Add candidate in new batch.";
            }else{
            var reason ="";
            }
            
            //alert(examdate.toISOString().slice(0,10));
           if(data['id'] == '' || data['id'] == null){
             var reason1 ="Member not in eligible.";
            }else if(data['exam_status'] == 'P'){
              var reason1 ="Member has passed this exam.";
            }else if(data['exam_status'] == 'V'){
               var reason1 ="Exam status is V.";
            }else if(data['exam_status'] == 'D'){
               var reason1 ="Member Debarred For this Exam.";
            }else if(data['app_category'] == '' || data['app_category'] == null){
               var reason1 ="App category is blank.";
            }else {
              var reason1 ="";
      }

            if(data['re_attempt'] >= '3'){
              var reason2 ="Member attempts are 3.";
            }else if(data['isdeleted'] == '1'){
               var reason2 ="Member is inactive.";
            }else{
              var reason2 ="";
            }

            if(reason1 !='' || reason2 !='' || reason !=''){
            $('#reasons').html('<hr><h3>Reasons :</h3><div class="form-group"><label for="total_candidates" class="col-sm-3 control-label">1. :</label><div class="col-sm-5" style="color:red">'+reason+'</div></div><div class="form-group"><label for="total_candidates" class="col-sm-3 control-label">2. :</label><div class="col-sm-5" style="color:red">'+reason1+'</div></div><div class="form-group"><label for="total_candidates" class="col-sm-3 control-label">3. :</label><div class="col-sm-5" style="color:red">'+reason2+'</div></div>');
            }else{
              $('#reasons').html('<hr><h3>Reason:</h3><div class="form-group"><label for="total_candidates" class="col-sm-10 control-label" style="color:green">No issue found.please check member in DRA/DRA-TC Exam Menu</label></div>');
            }

            

          });
          
        }
     });

    
   });



 });
</script><link href="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">

<!-- Data Tables --> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script> 
<!-- Data Tables --> 