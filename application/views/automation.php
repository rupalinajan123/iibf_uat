<?php $this->load->view('front-header');
$this->load->view('front-sidebar');?>
<style type="text/css">
  /*Added by Priyanka Wadnere for to display note and error span*/
  .note {
    color: blue;
    font-size: small;
  }

  .note-error {
    color: rgb(185, 74, 72);
    font-size: small;
  }
</style>
  <div class="wrapper">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper"> 
      <!-- Content Header (Page header) -->
      <!-- Main content -->
      <section class="content">

        <?php if($this->session->flashdata('success')!=''){ ?>
          <div class="alert alert-success alert-dismissible" id="success_id">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo $this->session->flashdata('success'); ?> 
          </div>
        <?php } ?>
        <!-- Info boxes -->
          <div class="row mar30">
            <div class="col-md-12">
              <div class="box box-info box-solid disabled">
                <div class="box-header with-border">
                  <h3 class="box-title">Welcome</h3>
                  <div class="box-tools pull-right">
                    <button data-widget="collapse" class="btn btn-box-tool" type="button"><i class="fa fa-minus"></i> </button>
                  </div>
                  <!-- /.box-tools --> 
                </div>
                 <form class="forms-sample" data-parsley-validate method="post" enctype="multipart/form-data" id="automation_form"  name="automation_form" >
                <!-- /.box-header -->
                <div class="box-body">
                  
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="type">Select Type <span style="color: red">*</span></label>
                        <input for="exam" type="radio" id="exam" class="radio_btn" name="radio_btn"  required value="Exam">Exam
                        <input for="result" type="radio" id="result" class="radio_btn" name="radio_btn" required value="Result">Result
                        <input for="training" type="radio" id="training" class="radio_btn" name="radio_btn" required value="Training">Training
                        <span id="radio_btn_error" class="note-error"></span>
                      </div>
                    </div>
                  </div> 

                  <div class="row type_div" style="display: none;">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="type" id="type_label"> Please Select <span style="color: red">*</span> </label>
                        <select class="form-control" id="type" name="type" onchange="get_info(this.value);" required>
                          <option value="">Select Type</option>  
                        </select>
                        <span id="type_error" class="note-error"></span>
                      </div>
                    </div>
                  </div>


                  <!-- <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="type">Select Exam/Result <span style="color: red">*</span></label>
                        <select class="form-control" id="type" name="type" onchange="get_info(this.value);" >
                          <option value="">Select Type</option>  
                          <?php foreach($typeArr as $type) {?>
                            <option value="<?php echo $type['id']?>"><?php echo $type['name']?></option>  
                          <?php } ?>  
                        </select>
                        <span id="type_error" class="note-error"></span>
                      </div>
                    </div>
                  </div> -->

                  <div id="info_div" style="display: none;">

                    <div class="box box-info box-solid disabled" id="dra_exam_div" style="display: none;">
                      <div class="box-body">
                        <div>
                          <p><b>Note :  Please make a folder and copy all files that are sent on mail.</b></p>
                          <p><b>TXT Files:</b>
                          <ul>
                            <ol>1) CENTER_MASTER and CENTER_MASTER_57</ol>
                            <ol>2) ELG_LIST_EXM_45 and ELG_LIST_EXM_57</ol>
                            <ol>3) EXAM_ACTIVATE_45 and EXAM_ACTIVATE_57</ol>
                            <ol>4) EXAM_MASTER_45 and EXAM_MASTER_57</ol>
                            <ol>5) FEE_MASTER_45 and FEE_MASTER_57</ol>
                            <ol>6) MEDIUM_MASTER_45 and MEDIUM_MASTER_57</ol>
                            <ol>7) MISC_PARAMETER_45 and MISC_PARAMETER_57</ol>
                            <ol>8) SUBJECT_MASTER_45 and SUBJECT_MASTER_57</ol>
                          </ul> 
                          </p>
                        </div>
                      </div>
                    </div>

                    <div class="box box-info box-solid disabled" id="dra_result_div" style="display: none;">
                      <div class="box-body">
                        <div>
                          <p><b>Note : Please make a folder and copy all files that are sent on mail.</b></p>
                          <p><b> TXT Files:</b>
                            <ul>
                              <ol>1) EXAM_LIST_45 and EXAM_LIST_57</ol>
                              <ol>2) MARK_OBT_45 and MARK_OBT_57</ol>
                              <ol>3) Member_List_45 and Member_List_57</ol>
                              <ol>4) SUBJECT_LIST_45 and SUBJECT_LIST_57</ol>
                            </ul>
                          </p>
                        </div>
                      </div>
                    </div>

                    <div class="box box-info box-solid disabled" id="rpe_exam_div" style="display: none;">
                      <div class="box-body">
                        <div>
                          <p><b>Note : Please make a folder and copy all files that are sent on mail for all exam codes.</b></p>
                          <p><b>TXT Files: </b></p>
                            <ul>
                              <ol>1) CENTER_MASTER</ol>
                              <ol>2) ELG_LIST_EXM</ol>
                              <ol>3) EXAM_DATE_MASTER</ol>
                              <ol>4) EXAM_ACTIVATE</ol>
                              <ol>5) EXAM_MASTER</ol>
                              <ol>6) FEE_MASTER</ol>
                              <ol>7) MEDIUM_MASTER</ol>
                              <ol>8) MISC_PARAMETER</ol>
                              <ol>9) SUBJECT_MASTER</ol>
                            </ul>
                          <p><b> Excel File:</b></p>
                            <ul>
                              <ol>1) Venue Master</ol>  
                            </ul>
                        </div>
                      </div>
                    </div>

                    <div class="box box-info box-solid disabled" id="rpe_result_div" style="display: none;">
                      <div class="box-body">
                        <div>
                          <p><b>Note : Please make a folder and copy all files that are sent on mail for all exam codes.</b></p>
                          <p><b>TXT Files: </b></p>
                          <ul>
                            <ol>1) EXAM_LIST</ol>
                            <ol>2) MARK_OBT</ol>
                            <ol>3) MEMBER_LIST</ol>
                            <ol>4) SUBJECT_LIST</ol>
                            <ol>5) consolidatedMarksheetData (optional)</ol>
                          </ul>
                        </div>
                      </div>
                    </div>

                    <div class="box box-info box-solid disabled" id="dipcert_exam_div" style="display: none;">
                      <div class="box-body">
                        <div>
                          <p><b>Note : Please make a folder and copy all files that are sent on mail for all exam codes.</b></p>
                          <p><b>TXT Files: </b></p>
                            <ul>
                              <ol>1) CENTER_MASTER</ol>
                              <ol>2) ELG_LIST_EXM</ol>
                              <ol>2) EXAM_ACTIVATE</ol>
                              <ol>3) EXAM_MASTER</ol>
                              <ol>4) FEE_MASTER</ol>
                              <ol>5) MEDIUM_MASTER</ol>
                              <ol>6) MISC_PARAMETER</ol>
                              <ol>9) SUBJECT_MASTER</ol>
                            </ul>
                          <p><b> Excel File:</b></p>
                            <ul>
                              <ol>1) Venue Master</ol>  
                            </ul>
                        </div>
                      </div>
                    </div>

                    <div class="box box-info box-solid disabled" id="garp_exam_div" style="display: none;">
                      <div class="box-body">
                        <div>
                          <p><b>Note : Please make a folder and copy all files that are sent on mail for all exam codes.</b></p>
                          <p><b>TXT Files: </b></p>
                            <ul>
                              <ol>1) EXAM_ACTIVATE</ol>
                              <ol>2) FEE_MASTER</ol>
                              <ol>3) MISC_PARAMETER</ol>
                            </ul>

                          <p><b>Excel File: </b></p>
                            <ul>
                              <ol>GARP_Eligible</ol>
                            </ul>
                          </p>
                        </div>
                      </div>
                    </div>

                    <div class="box box-info box-solid disabled" id="contact_classes_div" style="display: none;">
                      <div class="box-body">
                        <div>
                          <p><b>Note :</b> Please Upload Contact Classes master Excel file.</p>
                        </div>
                      </div>
                    </div> 

                    <div class="box box-info box-solid disabled" id="cisi_exam_div" style="display: none;">
                      <div class="box-body">
                        <div>
                          <p><b>Note :</b> Please Upload Eligible Data Text file.</b></p>
                        </div>
                      </div>
                    </div> 

                  </div>
            
                  <div class="row" id="files_div" style="display: none;">
                    <div class="col-md-6 col-sm-6">
                      <div class="form-group">
                        <label for="exampleInputName1">Upload File<span style="color: red">*</span></label>
                        <input type="file" class="form-control" name="txtfiles[]" id="txtfiles" multiple  accept="text" required>
                        <span class="note">Please Upload Text and Excel files only</span></br>
                        <span class="note-error" id="file_error"></span>
                      </div>
                    </div>
                  </div>

                  <center>
                    <div id="loading" class="divLoading" style="display: none;">
                      <p>Please be Patient as master data is uploading... <img src="<?php echo base_url(); ?>assets/images/loading-4.gif" width="100" height="100" /></p>
                    </div>
                  </center> 
              
                  <div class="col-sm-6 col-sm-offset-3 btn_div" >
                    <div class="col-sm-12"> 
                      <center>
                        <input type="submit" id="btn_submit" name="submit" value="Submit" class="btn btn-success mr-2"> 
                      </center>
                    </div>
                  </div>

                  <div class="box box-info box-solid" id="exam_link_div" style="display: none;">
                    <div class="box-body">
                      <span id="dra_exam_uat_span" style="display: none;">
                        <p><b>Agency Panel Link :</b></p>
                        <p>URL :https://iibf.teamgrowth.net/iibfdra/InstituteLogin</p>
                        <p><a href = "https://iibf.teamgrowth.net/iibfdra/InstituteLogin" target = "_blank">Click here</a></p>
                            Username : 182
                            Password : 1181</p>
                        <p>http://iibf.teamgrowth.net/iibfdra/TrainingBatches/allapplicants/?exCd=NDU=</p>
                        <p>http://iibf.teamgrowth.net/iibfdra/TrainingBatches/allapplicants/?exCd=NTc=</p>
                      </span>

                      <span id="dra_exam_prod_span" style="display: none;">
                        <p><b>Agency Panel Link :</b></p>
                        <p>URL : https://iibf.esdsconnect.com/iibfdra/InstituteLogin</p>
                          <p><a href = "https://iibf.esdsconnect.com/iibfdra/InstituteLogin" target = "_blank">Click here</a></p>
                            Username : 182
                            Password : 1181</p>
                        <p>https://iibf.esdsconnect.com/iibfdra/TrainingBatches/allapplicants/?exCd=NDU=</p>
                        <p>https://iibf.esdsconnect.com/iibfdra/TrainingBatches/allapplicants/?exCd=NTc=</p>
                      </span>

                      <span id="dra_result_uat_span" style="display: none;">
                        <p><b>Agency Panel Link :</b></p>
                        <p>URL : https://iibf.teamgrowth.net/iibfdra/InstituteLogin
                        <a href = "http://iibf.teamgrowth.net/iibfdra/InstituteLogin" target = "_blank">Click here</a>
                            Username : 182
                            Password : 1181</p>
                        <p>https://iibf.teamgrowth.net/iibfdra/Result/DRA_Result</p>
                        <p>https://iibf.teamgrowth.net/iibfdra/Result/DRATC_Result</p>
                      </span>

                      <span id="dra_result_prod_span" style="display: none;">
                        <p><b>Agency Panel Link :</b></p>
                        <p>URL : https://iibf.esdsconnect.com/iibfdra/InstituteLogin
                          <a href = "http://iibf.esdsconnect.com/iibfdra/InstituteLogin" target = "_blank">Click here</a>
                            Username : 182
                            Password : 1181</p>
                        <p>https://iibf.teamgrowth.net/iibfdra/Result/DRA_Result</p>
                        <p>https://iibf.teamgrowth.net/iibfdra/Result/DRATC_Result</p>
                      </span>

                      <span id="rpe_exam_uat_span" style="display: none;">
                        <p><b>For member:</b></p>
                        <p>url : https://iibf.teamgrowth.net/Examination/?type=Tw==
                          <a href = "https://iibf.teamgrowth.net/Examination/?type=Tw==" target = "_blank">Click here</a></p>
                        <p>Testing no: 511000092/100000012</p>

                        <p><b>for Non member-</b></p>
                        <p>url : https://iibf.teamgrowth.net/Examination/?type=Tk0==
                          <a href = "https://iibf.teamgrowth.net/Examination/?type=Tk0==" target = "_blank">Click here</a></p>
                        <p>Testing no:897090321</p>
                      </span>

                      <span id="rpe_exam_prod_span" style="display: none;">
                        <p><b>For member:</b></p>
                        <p>url: https://iibf.esdsconnect.com/Examination/?type=Tw==
                          <a href = "https://iibf.esdsconnect.com/Examination/?type=Tw==" target = "_blank">Click here</a></p>
                        <p>Testing No.: Please take Member no from Eligible master of respective exam</p>
                       
                        <p><b>for Non member-</b></p>
                        <p>url: https://iibf.esdsconnect.com/Examination/?type=Tk0==
                          <a href = "https://iibf.esdsconnect.com/Examination/?type=Tk0==" target = "_blank">Click here</a></p>
                        <p>Testing No.: Please take Member no from Eligible master of respective exam</p>
                      </span>

                      <span id="garp_exam_uat_span" style="display: none;">
                        <p>url: https://iibf.teamgrowth.net/Garp_exam
                        <a href = "http://iibf.teamgrowth.net/Garp_exam" target = "_blank">Click here</a></p>
                      </span>

                      <span id="garp_exam_prod_span" style="display: none;">
                        <p>url: https://iibf.esdsconnect.com/Garp_exam
                        <a href = "http://iibf.esdsconnect.com/Garp_exam" target = "_blank">Click here</a></p>
                      </span>

                    </div>
                  </div>

                  <div class="box box-info box-solid disabled" id="show_result" style="display: none;">
                    <div class="box-body">
                      <ul>
                        <ol id="rpe_res_url"></ol>
                        <ol><a href = "" target = "_blank" id="click_result">Click here</a></ol>
                        <ol id="rpe_res_msg"></ol>
                      </ul>
                    </div>
                  </div>


                  
                </div>
                <!-- /.box-body --> 
                </form>
              </div>
              <!-- /.box --> 
            </div>
          </div>
        
      </section>
      <!-- /.content --> 
    </div>
    <!-- /.content-wrapper -->
  </div>
<?php //$this->load->view('front-footer');?>

<footer class="main-footer">
    <div class="pull-right hidden-xs">

     Powered By <b>ESDS</b> 

    </div>

    <strong>Copyright &copy;&nbsp;<?php echo date("Y"); ?> <a href="javascript:void(0);">ESDS</a>.</strong> All rights

    reserved.
</footer>


<script src="<?php echo base_url()?>assets/admin/bootstrap/js/bootstrap.min.js"></script>

<!-- FastClick -->

<script src="<?php echo base_url()?>assets/admin/plugins/fastclick/fastclick.js"></script>

<!-- AdminLTE for demo purposes -->

<script src="<?php echo base_url()?>assets/admin/dist/js/demo.js"></script>


<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script> 
<script src="<?php echo base_url('assets/js/sweetalert2.all.min.js') ?>" type="text/javascript"></script> 

<script type="text/javascript">
    var base_url = '<?php echo base_url(); ?>';
    var errfile = 0;
    var errtype = 0;
    var typeArr = '<?php echo json_encode($typeArr); ?>';
   //console.log('typeArr---'+typeArr);

    $(document).ready(function() {
      $('.radio_btn').click(function() {
        var mySelect = $('#type');  
        mySelect.empty();
        var checkedval =  $(this).val();
        //console.log('checkedval--'+checkedval);
        $('.type_div').css('display','block');
        //$('#type_label').text('Select '+checkedval+' <span style="color: red">*</span>');
        $('#info_div').css('display','none');
        $('#files_div').css('display','none');
        $('#exam_link_div').css('display','none');

        var json = $.parseJSON(typeArr);
        mySelect.append("<option value=''>Select</option>");

        $.each(json, function(idx, obj) { 
          if(obj.type == checkedval){
            console.log(obj.type+'--'+checkedval);
            mySelect.append("<option value='"+obj.id+"' >"+obj.name +"</option>");
          }
        });
      });

      $("#automation_form").on('submit',(function(e) {
        e.preventDefault();

        var type = $('#type').val();
        let formData = new FormData(this)

        console.log('type--'+type+'---'+formData);
       
        var control = document.getElementById("txtfiles");
        var filelength = control.files.length;

        var allowedFiles = [".txt", ".xlsx", ".xls"];

        console.log('---'+filelength);

        if(type == ''){
          $('#type_error').text('Please Select Type');
          errtype = 1;
        }
        else{
          $('#type_error').text(" ");
          errtype = 0;
        }

        if(filelength > 0){
          for (var i = 0; i < control.files.length; i++) {
            var file = control.files[i];
            var FileName = file.name;
            var FileExt = FileName.substr(FileName.lastIndexOf('.') + 1);
            if ((FileExt.toUpperCase() != "TXT") && (FileExt.toUpperCase() != "XLS") && (FileExt.toUpperCase() != "XLSX"))  {
              $('#file_error').text("Please upload " + allowedFiles.join(', ') + " only.");
              errfile = 1;
            }
            else{
              $('#file_error').text("");
              errfile = 0;
            }
          }
        }
        else{
          $('#file_error').text("Please Select file in given format");
          errfile = 0;
        }
        console.log('errtype---'+errtype+'errfile---'+errfile);


        if(errtype == 0 && errfile == 0){
          $('.divLoading').css('display','block');

          $.ajax({
            type: "POST",
            url: base_url+'automation/start_process',
            data: formData,
            contentType: false,
            cache: false,
            processData:false,
            success: function (data) {
              $('.divLoading').css('display','none');
              console.log('+++'+data.trim());
              if(data.trim() != ''){
                data = data.trim();

                $('#exam_link_div').css('display','block');
                if(type == 1){
                  if (base_url.includes('iibf.teamgrowth.net')) {
                    $('#dra_exam_prod_span').css('display','none');
                    $('#dra_exam_uat_span').css('display','block');
                  }
                  else{
                    $('#dra_exam_uat_span').css('display','none');
                    $('#dra_exam_prod_span').css('display','block');
                  }
                  swal(data, "", "success");
                }
                if(type == 2){
                  $('#exam_link_div').css('display','none');
                  if (base_url.includes('iibf.teamgrowth.net')) {
                    $('#dra_result_prod_span').css('display','none');
                    $('#dra_result_uat_span').css('display','block');
                  }
                  else{
                    $('#dra_result_uat_span').css('display','none');
                    $('#dra_result_prod_span').css('display','block');
                  }
                  swal(data, "", "success");
                }
                if(type == 3 || type == 5 || type == 8 || type == 9 || type == 21){
                  console.log('3');
                  if(base_url.includes('iibf.teamgrowth.net')) {
                    console.log('---');
                    $('#rpe_exam_prod_span').css('display','none');
                    $('#rpe_exam_uat_span').css('display','block');
                  }
                  else{
                    console.log('+++');
                    $('#rpe_exam_uat_span').css('display','none');
                    $('#rpe_exam_prod_span').css('display','block');
                  }
                  swal(data, "", "success");
                }
                if(type == 4 || type == 6 || type == 9 || type == 11 || type == 15 || type == 17){
                  $('#exam_link_div').css('display','none');
                  var res = data.split('---');
                  var msg = res[0];
                  var url = res[1];
                  var extramsg = res[2];
                  swal(msg, "", "success");
                  $('#show_result').css('display','block');
                  $('#rpe_res_url').text(url);
                  $('#click_result').attr('href',url);
                  $('#rpe_res_msg').text(extramsg);
                }

                if(type == 7){
                  console.log('7');
                  if(base_url.includes('iibf.teamgrowth.net')) {
                    console.log('---');
                    $('#garp_exam_prod_span').css('display','none');
                    $('#garp_exam_uat_span').css('display','block');
                  }
                  else{
                    console.log('+++');
                    $('#garp_exam_uat_span').css('display','none');
                    $('#garp_exam_prod_span').css('display','block');
                  }
                  swal(data, "", "success");
                }
                
              }
              else{
                swal("Something went wrong", "", "error");
              }
            }
          });
        }
        else{
          return false;
        }
      }));
    });

  function get_info(type){
    $('#files_div').css('display','block');
    $('.btn_div').css('display','block');
    $('#info_div').css('display','block');
    $('#exam_link_div').css('display','none');
    $('#show_result').css('display','none');
    if(type == ''){
      $('#type_error').text('Please Select Type');
      err = 1;
      $('#dra_exam_div').css('display','none');
      $('#dra_result_div').css('display','none');
      $('#rpe_exam_div').css('display','none');
      $('#rpe_result_div').css('display','none');
      $('#dipcert_exam_div').css('display','none');
      $('#garp_exam_div').css('display','none');
      $('#cisi_exam_div').css('display','none');
      $('#contact_classes_div').css('display','none');
    }
    else{
      $('#type_error').text(" ");
      err = 0;
      if(type == 1){
        $('#dra_exam_div').css('display','block');
        $('#dra_result_div').css('display','none');
        $('#rpe_exam_div').css('display','none');
        $('#rpe_result_div').css('display','none');
        $('#dipcert_exam_div').css('display','none');
        $('#garp_exam_div').css('display','none');
        $('#cisi_exam_div').css('display','none');
        $('#contact_classes_div').css('display','none');
      }
      else if(type == 2){
        $('#dra_exam_div').css('display','none');
        $('#dra_result_div').css('display','block');
        $('#rpe_exam_div').css('display','none');
        $('#rpe_result_div').css('display','none');
        $('#dipcert_exam_div').css('display','none');
        $('#garp_exam_div').css('display','none');
        $('#cisi_exam_div').css('display','none');
        $('#contact_classes_div').css('display','none');
      }
      else if(type == 3 || type == 8 || type == 21){
        $('#dra_exam_div').css('display','none');
        $('#dra_result_div').css('display','none');
        $('#rpe_exam_div').css('display','block');
        $('#rpe_result_div').css('display','none');
        $('#dipcert_exam_div').css('display','none');
        $('#garp_exam_div').css('display','none');
        $('#cisi_exam_div').css('display','none');
        $('#contact_classes_div').css('display','none');
      }
      else if(type == 4 || type == 6 || type == 9 || type == 11 || type == 15){
        $('#dra_exam_div').css('display','none');
        $('#dra_result_div').css('display','none');
        $('#rpe_exam_div').css('display','none');
        $('#rpe_result_div').css('display','block');
        $('#dipcert_exam_div').css('display','none');
        $('#garp_exam_div').css('display','none');
        $('#cisi_exam_div').css('display','none');
        $('#contact_classes_div').css('display','none');
      }
      else if(type == 5){
        $('#dra_exam_div').css('display','none');
        $('#dra_result_div').css('display','none');
        $('#rpe_exam_div').css('display','none');
        $('#rpe_result_div').css('display','none');
        $('#dipcert_exam_div').css('display','block');
        $('#garp_exam_div').css('display','none');
        $('#cisi_exam_div').css('display','none');
        $('#contact_classes_div').css('display','none');
      }
      else if(type == 7){
        $('#dra_exam_div').css('display','none');
        $('#dra_result_div').css('display','none');
        $('#rpe_exam_div').css('display','none');
        $('#rpe_result_div').css('display','none');
        $('#dipcert_exam_div').css('display','none');
        $('#garp_exam_div').css('display','block');
        $('#cisi_exam_div').css('display','none');
        $('#contact_classes_div').css('display','none');
      }
      else if(type == 17){
        $('#dra_exam_div').css('display','none');
        $('#dra_result_div').css('display','none');
        $('#rpe_exam_div').css('display','none');
        $('#rpe_result_div').css('display','none');
        $('#dipcert_exam_div').css('display','none');
        $('#garp_exam_div').css('display','none');
        $('#cisi_exam_div').css('display','none');
        $('#contact_classes_div').css('display','block');
      }
      else if(type == 18 || type == 19 || type == 20){
        $('#dra_exam_div').css('display','none');
        $('#dra_result_div').css('display','none');
        $('#rpe_exam_div').css('display','none');
        $('#rpe_result_div').css('display','none');
        $('#dipcert_exam_div').css('display','none');
        $('#garp_exam_div').css('display','none');
        $('#contact_classes_div').css('display','none');
        $('#cisi_exam_div').css('display','block');
      }
    }
  }
</script>