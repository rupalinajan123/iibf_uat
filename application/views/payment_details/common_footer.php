</div>

<footer class="main-footer">
  <div class="pull-right hidden-xs"> Powered By <b>ESDS</b> </div>
  <strong>Copyright &copy;&nbsp;<?php echo date("Y"); ?> <a href="javascript:void(0);">ESDS</a>.</strong> All rights reserved. 
</footer>
<!-- ./wrapper --> 
<!-- Bootstrap 3.3.6 --> 
<script src="<?php echo base_url()?>assets/admin/bootstrap/js/bootstrap.min.js"></script> 
<!-- FastClick --> 
<script src="<?php echo base_url()?>assets/admin/plugins/fastclick/fastclick.js"></script> 
<!-- AdminLTE App --> 
<script src="<?php echo base_url()?>assets/admin/dist/js/app.min.js"></script> 
<!-- AdminLTE for demo purposes --> 
<script src="<?php echo base_url()?>assets/admin/dist/js/demo.js"></script>


<script>
  $( ".getPaymentDetails" ).each(function() {
    var postForm = { //Fetch form data
            'receipt_no'     : $(this).attr('receipt_no') //Store name fields value
        };
  $(this).click(function() {
    $.ajax({
        url: "<?php echo base_url(); ?>/payment_details/get_payment_details",
        type: "post",
        data: postForm ,
        success: function (response) {
          var json = $.parseJSON(response);
         // alert(json.amount);
          $('.showStatus').html(json.status);
          $('.showId').html(json.id);
          $('.showDate').html(json.date);
          $('.showAmount').html(json.amount);
          $("#paymentDetails").modal();
           // You will get response from your PHP page (what you echo or print)
        },
        error: function(jqXHR, textStatus, errorThrown) {
         //  console.log(textStatus, errorThrown);
        }
    });
  });
});
  </script>
</body>
</html>