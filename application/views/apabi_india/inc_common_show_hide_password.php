<script type="text/javascript">
  function show_hide_password(this_val,type,password_id)
  {
    var passwordId = password_id;
    if (type=="show") 
    {
      $("#" + passwordId).attr("type", "text");
      $(this_val).parent().find(".show-password").hide();
      $(this_val).parent().find(".hide-password").show();
    }
    else if (type=="hide") 
    {
      $("#" + passwordId).attr("type", "password");
      $(this_val).parent().find(".hide-password").hide();
      $(this_val).parent().find(".show-password").show();
    }
  }
</script>