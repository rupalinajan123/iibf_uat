<div class="modal fade" id="RegistrationTypeModal" tabindex="-1" role="dialog" aria-labelledby="RegistrationTypeModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="RegistrationTypeModalLabel">Add Registration Type</h4>
      </div>
      
      <div class="modal-body">
        <div class="form-group">
          <label for="new_registration_type">Registration Type</label>
          <input type="text" class="form-control" id="new_registration_type" name="new_registration_type" placeholder="Registration Type" required onkeyup="check_registration_type_msg()" autocomplete="off">
          <label id="registration_type_error" class="error"></label>
        </div>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="add_new_registration_type()">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('webmanager/includes/footer');?>

<script>
  $('#RegistrationTypeModal').on('shown.bs.modal', function () { $('#new_registration_type').focus(); })
  
  function open_RegistrationTypeModal()
  {
    $("#registration_type-error").remove();
    $("#new_registration_type").val("");
    $("#registration_type_error").html("");
    $("#RegistrationTypeModal").modal("show");
    $("#custom_msg_outer").html("");					
  }
  
  function check_registration_type_msg()
  {
    var new_registration_type = $("#new_registration_type").val();
    if(new_registration_type != "") { $("#registration_type_error").html(""); }
    $("#new_registration_type").focus();
  }
  
  function add_new_registration_type()
  {
    var new_registration_type = $("#new_registration_type").val();
    $("#new_registration_type").focus();
    if(new_registration_type == "")
    {
      $("#registration_type_error").html("Please enter the registration type");
    }
    else
    {
      $("#registration_type_error").html("");
      
      var security_token = $("#security_token").val();
      parameters = { "new_registration_type":new_registration_type, "sel_registration_type" : $( "#registration_type" ).val(), "security_token":security_token }
      $.ajax(
      {
        type: "POST",
        url: "<?php echo site_url('webmanager/member_registration/add_new_registration_type_ajax') ?>",
        data: parameters,
        cache: false,
        dataType: 'JSON',
        success:function(data)
        {
          if(data.flag == "success")
          {	
            $("#registration_type_error").html("");
            $("#registration_type_outer").html(data.registration_type_sel);
            $('.chosen-select').chosen({width: "100%"});
            
            $("#custom_msg_outer").html(data.message);
            $("#RegistrationTypeModal").modal("hide");
          }
          else if(data.flag == "error")
          { 
            if(data.message != "")
            {
              $("#registration_type_error").html(data.message);
            }
            else
            {
              location.reload(); 
            }
          } 
        }
      });
    }
  }				
  
  //START : FORM VALIDATION CODE 
  $(document ).ready( function() 
  {
    $.validator.addMethod("nowhitespace", function(value, element) { if($.trim(value).length == 0) { return false; } else { return true; } });
    
    $.validator.setDefaults({ ignore: ":hidden:not(.chosen-select)" })// For chosen validation
    
    $("#myForm").validate( 
    {
      ignore: [], // For Ckeditor
      debug: false, // For Ckeditor
      rules:
      {
        "registration_type[]": { required : true },
      },
      messages:
      {
        "registration_type[]": { required : "Please select the Registration Type" },
      },
      errorPlacement: function(error, element) // For replace error 
      {
        if (element.attr("name") == "registration_type[]") 
        {
          error.insertAfter("#registration_type_msg");
        }
        else 
        {
          error.insertAfter(element);
        }
      }
    });
  });
  //END : FORM VALIDATION CODE 
</script>
