<script type="text/javascript">
  function get_logs() 
  {
    $("#page_loader").show();
    var parameters = {
      "enc_pk_id": "<?php echo $enc_pk_id; ?>",
      'module_slug': "<?php echo $module_slug; ?>",
      'log_title': "<?php echo $log_title; ?>",
    }
    $.ajax({
      type: "POST",
      url: "<?php echo site_url('iibfdra/candidate/dashboard_candidate/candidate_logs'); ?>",
      data: parameters,
      cache: false,
      dataType: 'JSON',
      success: function(data) {
        if (data.flag == "success") {
          $("#common_log_outer").html(data.response);
          $('.log_table').DataTable();
        }
        $("#page_loader").hide();
      }
    });
  }
  get_logs();
</script>
