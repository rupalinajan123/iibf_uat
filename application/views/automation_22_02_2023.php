<?php $this->load->view('iibfdra/admin/includes/header');
$this->load->view('iibfdra/admin/includes/sidebar');?>
<div class="wrapper">
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1> Home </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url();?>iibfdra/admin/MainController"><i class="fa fa-home"></i> Home</a></li>
      </ol>
    </section>
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
             <form class="forms-sample"  method="post" enctype="multipart/form-data" id="dra_form" name="automation_form" >
            <!-- /.box-header -->
            <div class="box-body">
              
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="type">Type <span style="color: red">*</span></label>
                    <select class="form-control" id="type" name="type" onchange="get_info(this.value);">
                      <option value="">Select Type</option>  
                      <?php foreach($typeArr as $type) {?>
                        <option value="<?php echo $type['id']?>"><?php echo $type['name']?></option>  
                      <?php } ?>  
                    </select>
                  </div>
                </div>
              </div>
             
              <div class="row" id="files_div" style="display: block;">
                <div class="col-md-6 col-sm-6">
                  <div class="form-group">
                    <label for="exampleInputName1">Upload File<span class="text-danger">*</span></label>
                    <input type="file" class="form-control" name="txtfiles[]" id="" multiple>
                  </div>
                </div>
              </div>

              <center>
                <div id="loading" class="divLoading" style="display: none;">
                  <p>Please Wait... <img src="<?php echo base_url(); ?>assets/images/loading-4.gif" width="100" height="100" /></p>
                </div>
              </center> 

              <div class="box box-info box-solid disabled" id="dra_exam_div" style="display: none;">
                <div class="box-body">
                  <div>
                    <p><b>Note :  Please make a folder and copy all files that are sent on mail.</b></p>
                    <p><b>I) TXT Files:</b> 
                    </p>1) CENTER_MASTER_45
                        2) CENTER_MASTER_57
                        3) ELG_LIST_EXM_45
                        4) ELG_LIST_EXM_57
                        5) EXAM_ACTIVATE_45
                        6) EXAM_ACTIVATE_57
                        7) EXAM_MASTER_45
                        8) EXAM_MASTER_57
                        9) FEE_MASTER_45
                        10) FEE_MASTER_57
                        11) MEDIUM_MASTER_45
                        12) MEDIUM_MASTER_57
                        13) MISC_PARAMETER_45
                        14) MISC_PARAMETER_57
                        15) SUBJECT_MASTER_45
                        16) SUBJECT_MASTER_57
                      </p>  
                  </div>
                </div>
              </div>

              <div class="box box-info box-solid disabled" id="dra_result_div" style="display: none;">
                <div class="box-body">
                  <div>
                    <p>Note : Please make a folder and copy all files that are sent on mail.
                    I) TXT Files: 
                        1) EXAM_LIST_45
                        2) EXAM_LIST_57
                        3) MARK_OBT_45
                        4) MARK_OBT_57
                        5) Member_List_45
                        6) Member_List_57
                        7) SUBJECT_LIST_45
                        8) SUBJECT_LIST_57
                    </p>
                  </div>
                </div>
              </div>

              <div class="box box-info box-solid disabled" id="rpe_exam_div" style="display: none;">
                <div class="box-body">
                  <div>
                    <p><b>Note : Please make a folder and copy all files that are sent on mail for all exam codes.</b></p>
                    <p><b>Files: </b></p>
                        <p>1) CENTER_MASTER
                           2) ELG_LIST_EXM
                           3) EXAM_DATE_MASTER
                           4) EXAM_ACTIVATE
                           5) EXAM_MASTER
                           6) FEE_MASTER
                           7) MEDIUM_MASTER
                           8) MISC_PARAMETER
                           9) SUBJECT_MASTER</p>
                    <p><b>II) CSV File:</b></p>
                       <p>open xls file and save it as csv file and remove 1st row of title from file.
                        1) Venue Master</p>
                  </div>
                </div>
              </div>

              <div class="box box-info box-solid disabled" id="rpe_result_div" style="display: none;">
                <div class="box-body">
                  <div>
                    <p>Note : Please make a folder and copy all files that are sent on mail for all exam codes.
                    I) TXT Files: 
                      1) EXAM_LIST
                      2) MARK_OBT
                      3) MEMBER_LIST
                      4) SUBJECT_LIST

                    II) CSV File: open xls file and save it as csv file and remove 1st row of title from file.
                      1) GARP_Eligible.csv
                    </p>
                  </div>
                </div>
              </div>

              <div class="box box-info box-solid disabled" id="dipcert_exam_div" style="display: none;">
                <div class="box-body">
                  <div>
                    <p><b>Note : Please make a folder and copy all files that are sent on mail for all exam codes.</b></p>
                    <p><b>Files: </b></p>
                        <p>1) CENTER_MASTER
                           2) ELG_LIST_EXM
                           3) EXAM_ACTIVATE
                           4) EXAM_MASTER
                           5) FEE_MASTER
                           6) MEDIUM_MASTER
                           7) MISC_PARAMETER
                           8) SUBJECT_MASTER</p>
                    <p><b>II) CSV File:</b></p>
                       <p>open xls file and save it as csv file and remove 1st row of title from file.
                        1) Venue Master</p>
                  </div>
                </div>
              </div>

              <div class="box box-info box-solid disabled" id="garp_exam_div" style="display: none;">
                <div class="box-body">
                  <div>
                    <p>Note : Please make a folder and copy all files that are sent on mail for all exam codes.
                    Files: 1) EXAM_ACTIVATE
                           2) FEE_MASTER
                           3) GARP_Eligible.csv
                           4) MISC_PARAMETER
                    </p>
                  </div>
                </div>
              </div>
          
              <div class="row ml-2 justify-content-center">
               <input type="submit" id="btn_submit" name="submit" value="Save & Submit" class="btn btn-success mr-2"> 
              </div> 

              <span id="dra_exam_uat_span" style="display: none;">
                <p><b>Agency Panel Result Link :</b></p>
                <p>http://iibf.teamgrowth.net/iibfdra/Result/DRA_Result</p>
                <p>http://iibf.teamgrowth.net/iibfdra/Result/DRATC_Result</p>
              </span>

              <span id="dra_exam_prod_span" style="display: none;">
                <p><b>Agency Panel Result Link :</b></p>
                <p>http://iibf.teamgrowth.net/iibfdra/Result/DRA_Result</p>
                <p>http://iibf.teamgrowth.net/iibfdra/Result/DRATC_Result</p>
              </span>


              <span id="dra_result_uat_span" style="display: none;">
                <p><b>Agency Panel Result Link :</b></p>
                <p>http://iibf.teamgrowth.net/iibfdra/Result/DRA_Result</p>
                <p>http://iibf.teamgrowth.net/iibfdra/Result/DRATC_Result</p>
              </span>

              <span id="dra_result_prod_span" style="display: none;">
                <p><b>Agency Panel Result Link :</b></p>
                <p>http://iibf.teamgrowth.net/iibfdra/Result/DRA_Result</p>
                <p>http://iibf.teamgrowth.net/iibfdra/Result/DRATC_Result
              </span>


              <span id="rpe_exam_uat_span" style="display: none;">
                <p><b>For member:</b></p>
                <p>url : https://iibf.teamgrowth.net/Examination/?type=Tw==</p>
                <p>Testing no: 511000092/100000012</p>

                <p><b>for Non member-</b></p>
                <p>url : https://iibf.teamgrowth.net/Examination/?type=Tk0==</p>
                <p>Testing no:897090321</p>
              </span>

              <span id="rpe_exam_prod_span" style="display: none;">
                <p><b>For member:</b></p>
                <p>url: https://iibf.esdsconnect.com/Examination/?type=Tw==</p>
               
                <p><b>for Non member-</b></p>
                <p>url: https://iibf.esdsconnect.com/Examination/?type=Tk0==</p>
              </span>

              <span id="dra_res_url"></span>

              
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
<?php //$this->load->view('iibfdra/admin/includes/footer');?>

<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script> 
<script src="<?php echo base_url('assets/js/sweetalert2.all.min.js') ?>" type="text/javascript"></script> 

<script type="text/javascript">
    var base_url = '<?php echo base_url(); ?>';
    var err = 0;
    $(document).ready(function (e) {
      $("#dra_form").on('submit',(function(e) {
        e.preventDefault();

        /*var fileInput = $('form[name=automation_form]').find("input[name=automation_form]")[0];
        var files = fileInput.txtfiles && fileInput.txtfiles[0];
        //alert(user_manual.size);

        var allowedFiles = [".txt", ".csv"];
        var regex = new RegExp("([a-zA-Z0-9\s_\\((\d+)\)\.\-:])+(" + allowedFiles.join('|') + ")$");
              
        if (!regex.test(fileInput.value)) {
          $('#file_error').text("Please upload " + allowedFiles.join(', ') + " only.");
          err = 1;
        }
        else{
          $('#file_error').text("");
          err = 0;
        }*/
        var type = $('#type').val();
        let formData = new FormData(this)

        console.log(formData);
        /*formData.append("type", type);
        formData.append("file", txtfiles.files[0]);
       */
        if(type == ''){
          $('#type_error').text('Please Select Type');
          err = 1;
        }
        else{
          $('#type_error').text('');
          err = 0;
        }

        if(err == 0){
          $('.divLoading').css('display','block');

          $.ajax({
            type: "POST",
            url: base_url+'automation/start_process',
            data: formData,
            contentType: false,
            cache: false,
            processData:false,
            success: function (data) {
              console.log('data:'+data);
              $('.divLoading').css('display','none');
              if(data.trim() != ''){
                data = data.trim();
                if(type == 5){
                  if (base_url.includes('iibf.teamgrowth.net')) {
                    $('#rpe_exam_prod_span').css('display','none');
                    $('#rpe_exam_uat_span').css('display','block');
                  }
                  else{
                    $('#rpe_exam_uat_span').css('display','none');
                    $('#rpe_exam_prod_span').css('display','block');
                  }
                }
                if(type == 4){
                  data.split('---');
                  msg = data[0];
                  url = data[1];
                  swal(msg, "", "success");
                  $('#dra_res_url').text(url);
                }
                else{
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
    if(type == 1){
      $('#dra_exam_div').css('display','block');
      $('#dra_result_div').css('display','none');
      $('#rpe_exam_div').css('display','none');
      $('#rpe_result_div').css('display','none');
      $('#dipcert_exam_div').css('display','none');
      $('#garp_exam_div').css('display','none');
    }
    else if(type == 2){
      $('#dra_exam_div').css('display','none');
      $('#dra_result_div').css('display','block');
      $('#rpe_exam_div').css('display','none');
      $('#rpe_result_div').css('display','none');
      $('#dipcert_exam_div').css('display','none');
      $('#garp_exam_div').css('display','none');
    }
    else if(type == 3){
      $('#dra_exam_div').css('display','none');
      $('#dra_result_div').css('display','none');
      $('#rpe_exam_div').css('display','block');
      $('#rpe_result_div').css('display','none');
      $('#dipcert_exam_div').css('display','none');
      $('#garp_exam_div').css('display','none');
    }
    else if(type == 4){
      $('#dra_exam_div').css('display','none');
      $('#dra_result_div').css('display','none');
      $('#rpe_exam_div').css('display','none');
      $('#rpe_result_div').css('display','block');
      $('#dipcert_exam_div').css('display','none');
      $('#garp_exam_div').css('display','none');
    }
    else if(type == 5){
      $('#dra_exam_div').css('display','none');
      $('#dra_result_div').css('display','none');
      $('#rpe_exam_div').css('display','none');
      $('#rpe_result_div').css('display','none');
      $('#dipcert_exam_div').css('display','block');
      $('#garp_exam_div').css('display','none');
    }
    else if(type == 7){
      $('#dra_exam_div').css('display','none');
      $('#dra_result_div').css('display','none');
      $('#rpe_exam_div').css('display','none');
      $('#rpe_result_div').css('display','none');
      $('#dipcert_exam_div').css('display','none');
      $('#garp_exam_div').css('display','block');
    }
    else if(type == 8){
      $('#dra_exam_div').css('display','none');
      $('#dra_result_div').css('display','none');
      $('#rpe_exam_div').css('display','none');
      $('#rpe_result_div').css('display','none');
      $('#dipcert_exam_div').css('display','none');
      $('#garp_exam_div').css('display','none');
      $('#files_div').css('display','none');
    }
  }
</script>