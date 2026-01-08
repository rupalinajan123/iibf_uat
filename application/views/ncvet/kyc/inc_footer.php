
  <script src="<?php echo auto_version(base_url('assets/ncvet/js/jquery-3.1.1.min.js')); ?>"></script>
  <script src="<?php echo auto_version(base_url('assets/ncvet/js/popper.min.js')); ?>"></script>
  <script src="<?php echo auto_version(base_url('assets/ncvet/js/bootstrap.js')); ?>"></script>
  <script src="<?php echo auto_version(base_url('assets/ncvet/js/plugins/metisMenu/jquery.metisMenu.js')); ?>"></script>
  <script src="<?php echo auto_version(base_url('assets/ncvet/js/plugins/slimscroll/jquery.slimscroll.min.js')); ?>"></script>
  <script src="<?php echo auto_version(base_url('assets/ncvet/js/plugins/dataTables/datatables.min.js')); ?>"></script>
  <script src="<?php echo auto_version(base_url('assets/ncvet/js/plugins/dataTables/dataTables.bootstrap4.min.js')); ?>"></script>	

  <script src="<?php echo auto_version(base_url('assets/ncvet/js/bootstrap-toggle.js')); ?>"></script><!-- Toggle -->		
  <script src="<?php echo auto_version(base_url('assets/ncvet/js/plugins/chosen/chosen.jquery.js')); ?>"></script>		
  <script src="<?php echo auto_version(base_url('assets/ncvet/js/plugins/datapicker/bootstrap-datepicker.js')); ?>"></script>
  
  <!-- Custom and plugin javascript -->
  <script src="<?php echo auto_version(base_url('assets/ncvet/js/inspinia.js')); ?>"></script>
  <script src="<?php echo auto_version(base_url('assets/ncvet/js/plugins/pace/pace.min.js')); ?>"></script>

  <?php $this->load->view('ncvet/common/inc_lightbox_files'); ?>
  <?php $this->load->view('ncvet/common/inc_sweet_alert_files'); ?>

  <script language="javascript">
    /* $(document).ajaxStart(function() { $("#page_loader").css("display", "block"); });
    $(document).ajaxComplete(function() { $("#page_loader").css("display", "none"); }); */
    
    $('#custom_sidebar_close_btn').on('click', function (e) { e.preventDefault(); $("body").toggleClass("mini-navbar"); SmoothlyMenu(); });
    
    $('.chosen-select').chosen({width: "100%"});
    
    var datepicker = $('.datepicker').datepicker({ todayBtn: "linked", keyboardNavigation: true, forceParse: false, calendarWeeks: true, autoclose: true, format: "yyyy-mm-dd", todayHighlight:true, clearBtn: true, /* endDate:"1990-12-31" */ });
    
    function change_status(id, url)
    {
      var data = { 'id': encodeURIComponent($.trim(id)), 'status' : encodeURIComponent($.trim($("#toogle_id_"+id).prop('checked'))) };				
      $.ajax({ type: "POST", url: url, data: data, success:function(response) { if(response.trim() != 'success') { location.reload(); } } });
    }
    
    <?php /* ************ NEW UPDATED FUNCTION ************  ?>	
    $( "#checkboxlist_all_new" ).click(function()
    {
      if($(this).prop("checked") == true) { $('.checkboxlist_new').each(function() { $('.checkboxlist_new').prop('checked', true); }); }
      else if($(this).prop("checked") == false) { $('.checkboxlist_new').each(function() { $('.checkboxlist_new').prop('checked', false); }); }
      
      $('.checkboxlist_new').each(function() { update_delete_str(this.value) });
    });
    
    $( ".checkboxlist_new" ).click(function() { checkboxlist_new_function(); });
    
    function checkboxlist_new_function()
    {
      var total_length = document.querySelectorAll('.checkboxlist_new').length;
      var selected_length = document.querySelectorAll('.checkboxlist_new:checked').length;
      if(total_length > 0 && total_length == selected_length) { $('#checkboxlist_all_new').prop('checked', true); }
      else { $('#checkboxlist_all_new').prop('checked', false); }
    }
    
    function update_delete_str(id)
    {
      var selected_ids = $("#selcted_checkbox_all_hidden").val();	
      explode_arr = selected_ids.split(',');
      
      if($("#checkboxlist_new_"+id).prop("checked") == true) { if(selected_ids == "") { selected_ids = id; } else { if(jQuery.inArray(id, explode_arr) !== 1) { selected_ids = selected_ids + "," + id; } } }
      else { if(jQuery.inArray(id, explode_arr) !== 1) { explode_arr = jQuery.grep(explode_arr, function(value) { return value != id; }); selected_ids = explode_arr.join(',') } }
      $("#selcted_checkbox_all_hidden").val(selected_ids);
    }
    
    function delete_all(url)
    {
      var myArray = [];
      var checkValues = $('#selcted_checkbox_all_hidden').val();
      
      if(checkValues=="") { sweet_alert_only_alert("Please select at least one record to delete"); }
      else
      {
        explode_cnt = checkValues.split(',');
        swal({ 
          title: "Please confirm", 
          text: "Are you sure to delete selected "+explode_cnt.length+" record? You will not be able to recover this record(s)", 
          type: "warning", 
          showCancelButton: true, 
          confirmButtonColor: "#DD6B55", 
          confirmButtonText: "Yes, delete it!", 
          closeOnConfirm: true 
        }, 
        function () 
        { 
          var data = { 'id': checkValues };						
          $.ajax({ type: "POST", url: url, data: data, success:function(data) { location.reload(); } });
        });
      }
    }			*/ ?>	
  </script>
  
  <?php if($this->session->flashdata('success')) { ?><script>sweet_alert_success("<?php echo $this->session->flashdata('success'); ?>"); </script><?php } ?>
  <?php if($this->session->flashdata('error')) { ?><script>sweet_alert_error("<?php echo $this->session->flashdata('error'); ?>"); </script><?php } ?>
  <?php if($this->session->flashdata('alert')) { ?><script>sweet_alert_only_alert("<?php echo $this->session->flashdata('alert'); ?>"); </script><?php } ?>
  <?php if($this->session->flashdata('warning')) { ?><script>swal({ html:true, title: "warning", text: "<?php echo $this->session->flashdata('warning'); ?>" });</script><?php } ?>