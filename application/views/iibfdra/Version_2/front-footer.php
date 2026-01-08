<footer class="main-footer">

    <div class="pull-right hidden-xs">

     Powered By <b>ESDS</b> 

    </div>

    <strong>Copyright &copy; &nbsp;<?php echo date("Y"); ?> <a href="javascript:void(0);">ESDS</a>.</strong> All rights

    reserved.

  </footer>



  

</div>

<!-- ./wrapper -->


<!-- Bootstrap 3.3.6 -->

<script src="<?php echo base_url()?>assets/admin/bootstrap/js/bootstrap.min.js"></script>

<!-- SlimScroll -->
<script src="<?php echo base_url()?>assets/admin/plugins/slimScroll/jquery.slimscroll.min.js"></script>

<!-- FastClick -->

<script src="<?php echo base_url()?>assets/admin/plugins/fastclick/fastclick.js"></script>

<!-- AdminLTE App -->

<script src="<?php echo base_url()?>assets/admin/dist/js/app.min.js"></script>

<!-- AdminLTE for demo purposes -->

<script src="<?php echo base_url()?>assets/admin/dist/js/demo.js"></script>

<!--Added by Priyanka Wadnere for Image validations for all cases -->
<script src="<?php echo base_url('js/validateFile.js')?>"></script>

<!--Added by Priyanka Wadnere to show sweet alert -->
<script src="<?php echo base_url('assets/js/sweetalert2.all.min.js') ?>" type="text/javascript"></script> 

<script type="text/javascript">
    var site_path = '<?php echo base_url(); ?>';

    function confirm_action(ref, evt, msg, check_status, controller, prim_id, func) {
        var site_path = '<?php echo base_url(); ?>';
        var msg = msg || false;
        evt.preventDefault();
        swal({
            title: 'Are you sure?',
            text: msg,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3f51b5',
            cancelButtonColor: '#ff4081',
            confirmButtonText: 'OK ',
            buttons: {

                confirm: {
                    text: "OK",
                    value: true,
                    visible: true,
                    className: "btn btn-primary",
                    closeModal: true
                },
                cancel: {
                    text: "Cancel",
                    value: null,
                    visible: true,
                    className: "btn btn-danger",
                    closeModal: true,
                }
            }
        }).then(OK => {
            if (OK.value) {
                if (check_status == 'yes') {
                    $.ajax({
                        url: site_path + "iibfdra/Version_2/" + controller + "/" + func,
                        type: 'POST',
                        data: {
                            'ci_csrf_token': '',
                            prim_id: prim_id
                        },
                        success: function(response) {
                            //alert(response); return false;
                            if (response != '') {
                                swal(response, "", "warning");
                            } else {
                                //alert($(ref).attr('href'));return false;
                                window.location = $(ref).attr('href');
                            }
                        }
                    })
                } else {
                    window.location = $(ref).attr('href');
                }
            }
        });

    }

</script>

</body>

</html>
