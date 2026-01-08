<link href="<?php echo auto_version(base_url('assets/iibfbcbf/css/plugins/sweetalert/sweetalert.css')); ?>" rel="stylesheet">
<script src="<?php echo auto_version(base_url('assets/iibfbcbf/js/plugins/sweetalert/sweetalert.min.js')); ?>"></script>

<script language="javascript">
  function sweet_alert_delete(del_url) 
  { 
    swal({ html:true, title: "Please confirm", text: "You will not be able to recover this record", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes, delete it!", closeOnConfirm: true }, function () 
    { window.location.href = del_url; }); 
  }
  
  function sweet_alert_confirm(msg, url) 
  { 
    swal({ html:true, title: "Please confirm", text: msg, type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: true }, function () 
    { window.location.href = url; }); 
  }
  
  function sweet_alert_img_delete(del_url, img_name) { swal({ html:true, title: "Please confirm", text: "Please confirm to delete the "+img_name, type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes, delete it!", closeOnConfirm: true }, function () { window.location.href = del_url; }); }
  
  function sweet_alert_success(msg) { swal({ html:true, title: "Success", text: msg, type: "success" }); }
  function sweet_alert_error(msg) { swal({ html:true, title: "Error", text: msg, type: "error" }); }
  function sweet_alert_only_alert(msg) { swal({ html:true, title: "Alert", text: msg }); }
</script>