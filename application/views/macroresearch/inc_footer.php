
  <script src="<?php echo auto_version(base_url('assets/macroresearch/js/jquery-3.1.1.min.js')); ?>"></script>
  <script src="<?php echo auto_version(base_url('assets/macroresearch/js/popper.min.js')); ?>"></script>
  <script src="<?php echo auto_version(base_url('assets/macroresearch/js/bootstrap.js')); ?>"></script>
  <script src="<?php echo auto_version(base_url('assets/macroresearch/js/plugins/metisMenu/jquery.metisMenu.js')); ?>"></script>
  <script src="<?php echo auto_version(base_url('assets/macroresearch/js/plugins/slimscroll/jquery.slimscroll.min.js')); ?>"></script>
  <script src="<?php echo auto_version(base_url('assets/macroresearch/js/plugins/dataTables/datatables.min.js')); ?>"></script>
  <script src="<?php echo auto_version(base_url('assets/macroresearch/js/plugins/dataTables/dataTables.bootstrap4.min.js')); ?>"></script>	

  <script src="<?php echo auto_version(base_url('assets/macroresearch/js/bootstrap-toggle.js')); ?>"></script><!-- Toggle -->		
  <script src="<?php echo auto_version(base_url('assets/macroresearch/js/plugins/chosen/chosen.jquery.js')); ?>"></script>		
  <script src="<?php echo auto_version(base_url('assets/macroresearch/js/plugins/datapicker/bootstrap-datepicker.js')); ?>"></script>
  <script src="<?php echo auto_version(base_url('assets/macroresearch/lightbox/lightbox.min.js')); ?>"></script><!-- LIGHTBOX -->		
  <script src="<?php echo auto_version(base_url('assets/macroresearch/js/plugins/sweetalert/sweetalert.min.js')); ?>"></script>
  <!-- Custom and plugin javascript -->
  <script src="<?php echo auto_version(base_url('assets/macroresearch/js/inspinia.js')); ?>"></script>
  <script src="<?php echo auto_version(base_url('assets/macroresearch/js/plugins/pace/pace.min.js')); ?>"></script>

  <script language="javascript">
    /* $(document).ajaxStart(function() { $("#page_loader").css("display", "block"); });
    $(document).ajaxComplete(function() { $("#page_loader").css("display", "none"); }); */
    
    $('#custom_sidebar_close_btn').on('click', function (e) { e.preventDefault(); $("body").toggleClass("mini-navbar"); SmoothlyMenu(); });
    
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
    
    $('.chosen-select').chosen({width: "100%"});
    
    var datepicker = $('.datepicker').datepicker({ todayBtn: "linked", keyboardNavigation: true, forceParse: false, calendarWeeks: true, autoclose: true, format: "yyyy-mm-dd", todayHighlight:true, clearBtn: true, /* endDate:"1990-12-31" */ });
    
    function change_status(id, url)
    {
      var data = { 'id': encodeURIComponent($.trim(id)), 'status' : encodeURIComponent($.trim($("#toogle_id_"+id).prop('checked'))) };				
      $.ajax({ type: "POST", url: url, data: data, success:function(response) { if(response.trim() != 'success') { location.reload(); } } });
    }
    
  </script>
  
  <?php if($this->session->flashdata('success')) { ?><script>sweet_alert_success("<?php echo $this->session->flashdata('success'); ?>"); </script><?php } ?>
  <?php if($this->session->flashdata('error')) { ?><script>sweet_alert_error("<?php echo $this->session->flashdata('error'); ?>"); </script><?php } ?>
  <?php if($this->session->flashdata('alert')) { ?><script>sweet_alert_only_alert("<?php echo $this->session->flashdata('alert'); ?>"); </script><?php } ?>