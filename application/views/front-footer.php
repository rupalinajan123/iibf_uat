<footer class="main-footer">

    <div class="pull-right hidden-xs">

     Powered By <b>ESDS</b> 

    </div>

    <strong>Copyright &copy;&nbsp;<?php echo date("Y"); ?> <a href="https://www.esds.co.in/" target="blank">ESDS</a>.</strong> All rights

    reserved.  <?php $app_server=explode('.',gethostname());if(isset($app_server[0])){echo $app_server[0];}?>

  </footer>



  

</div>

<!-- ./wrapper -->


<!-- Bootstrap 3.3.6 -->

<script src="<?php echo base_url()?>assets/admin/bootstrap/js/bootstrap.min.js"></script>

<!-- FastClick -->

<script src="<?php echo base_url()?>assets/admin/plugins/fastclick/fastclick.js"></script>

<!-- AdminLTE App -->

<script src="<?php echo base_url()?>assets/admin/dist/js/app.min.js"></script>

<!-- AdminLTE for demo purposes -->

<script src="<?php echo base_url()?>assets/admin/dist/js/demo.js"></script>


<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-120791984-2"></script>


<!-- Added by Priyanka Wadnere for Image Validation -->
<script src="<?php echo base_url('js/validateFile.js')?>"></script>

<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'UA-120791984-2');
</script>

</body>

</html>
