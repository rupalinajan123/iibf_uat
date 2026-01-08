<style>
.modal-dialog {
  position: relative;
  display: table;
  overflow-y: auto;
  overflow-x: auto;
  width: 920px;
  min-width: 300px;
}
#confirm .modal-dialog {
  position: relative;
  display: table;
  overflow-y: auto;
  overflow-x: auto;
  width: 420px;
  min-width: 400px;
}
.skin-blue .main-header .navbar {
  background-color:#fff;
}
body.layout-top-nav .main-header h1 {
  color:#0699dd;
  margin-bottom:0;
  margin-top:30px;
}
.container {
  position:relative;
}
.box-header.with-border {
  background-color:#7fd1ea;
  border-top-left-radius:0;
  border-top-right-radius:0;
  margin-bottom:10px;
}
.header_blue {
  background-color:#2ea0e2 !important;
  color:#fff !important;
  margin-bottom:0 !important;
}
.box {
  border:none;
  box-shadow:none;
  border-radius:0;
  margin-bottom:0;
}
.nobg {
  background:none !important;
  border:none !important;
}
.box-title-hd {
  color:#3c8dbc;
  font-size:16px;
  margin:0;
}
.blue_bg {
  background-color:#e7f3ff;
}
.m_t_15 {
  margin-top:15px;
}
.main-footer {
  padding-left:160px;
  padding-right:160px;
}
.content-header > h1 {
  font-size:22px;
  font-weight:600;
}
h4 {
  margin-top:5px;
  margin-bottom:10px !important;
  font-size:14px;
  line-height:18px;
  padding:0 5px;
  font-weight:600;
  text-align:justify;
}
.form-horizontal .control-label {
  padding-top:4px;
}
.pad_top_2 {
  padding-top:2px !important;
}
.pad_top_0 {
  padding-top:0px !important;
}
 div.form-group:nth-child(odd) {
 background-color:#dcf1fc;
 padding:5px 0;
}
#confirmBox {
  display: none;
  background-color: #eee;
  border-radius: 5px;
  border: 1px solid #aaa;
  position: fixed;
  width: 300px;
  left: 50%;
  margin-left: -150px;
  padding: 6px 8px 8px;
  box-sizing: border-box;
  text-align: center;
  z-index:1;
  box-shadow:0 1px 3px #000;
}
#confirmBox .button {
  background-color: #ccc;
  display: inline-block;
  border-radius: 3px;
  border: 1px solid #aaa;
  padding: 2px;
  text-align: center;
  width: 80px;
  cursor: pointer;
}
#confirmBox .button:hover {
  background-color: #ddd;
}
#confirmBox .message {
  text-align: left;
  margin-bottom: 8px;
}
.form-group {
  margin-bottom:10px;
}
.form-horizontal .form-group {
  margin-left:0;
  margin-right:0;
}
.form-control {
  border-color:#888;
}
.form-horizontal .control-label {
  font-weight:normal;
}
a.forget {
  color:#9d0000;
}
a.forget:hover {
  color:#9d0000;
  text-decoration:underline;
}
ol li {
  line-height:18px;
}
.example {
  text-align:left !important;
  padding:0 10px;
}
label{
font-weight:bold !important;
}



  td.subject_checkbox input {
    position: absolute;
    right: 0;
    top: 50%;
    height: 50px;
    margin-top: -25px;
}

td.subject_checkbox {
    position: relative;
    padding-right: 25px !important;
    line-height: 20px;
}
</style>
<?php 
header('Cache-Control: must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>
<div class="container">
  <section class="content-header" style="padding-left:0;padding-right:0;">
    <h1> Please review the provided details, and make corrections if necessary. <a  href="javascript:window.history.go(-1);">Modify</a> </h1>
    <br>
  </section>
  <form class="form-horizontal" name="usersAddForm" id="usersAddForm"  method="post"  enctype="multipart/form-data" 
    action="<?php echo base_url()?>sme/addmember/">
    <input type="hidden" id="position_id" name="position_id" value="13">
    <section class="content">
    <div class="row">
    <div class="col-md-12">
      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title">BASIC DETAILS</h3>
          <div style="float:right;"> </div>
        </div>
        <!-- form start -->
        <div class="box-body">
          <?php //echo validation_errors(); ?>
          <?php if($this->session->flashdata('error')!=''){?>
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo $this->session->flashdata('error'); ?> </div>
          <?php } if($this->session->flashdata('success')!=''){ ?>
          <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo $this->session->flashdata('success'); ?> </div>
          <?php } 
       if(validation_errors()!=''){?>
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo validation_errors(); ?> </div>
          <?php } 
       ?>
          <div class="form-group">
            <label for="roleid" class="col-sm-4 control-label">Application for the post of </label>
            <div class="col-sm-5"> Subject Matter Expert (SME) Empanelment</div>
          </div>
          <div class="form-group">
            <label for="roleid" class="col-sm-4 control-label">Name</label>
            <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['name'];?></div>
          </div>
          <div class="form-group">
            <label for="roleid" class="col-sm-4 control-label">Date Of Birth</label>
            <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['dateofbirth'];?> </div>
          </div>
           <div class="form-group">
            <label for="roleid" class="col-sm-4 control-label">Education Qualification</label>
            <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['educational_qualification'];?> </div>
          </div>
          <div class="form-group">
            <label for="roleid" class="col-sm-4 control-label">CAIIB Qualification</label>
            <div class="col-sm-5"><?php echo ucfirst($this->session->userdata['enduserinfo']['CAIIB_qualification']);?></div>
          </div>
          <div class="form-group">
            <label for="roleid" class="col-sm-4 control-label">Address line1 </label>
            <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['addressline1'];?></div>
          </div>
          <div class="form-group">
            <label for="roleid" class="col-sm-4 control-label">Address line2</label>
            <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['addressline2'];?></div>
          </div>
          <div class="form-group">
            <label for="roleid" class="col-sm-4 control-label">City</label>
            <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['city'];?></div>
          </div>
          <div class="form-group">
            <label for="roleid" class="col-sm-4 control-label">State</label>
            <div class="col-sm-3">
              <?php if(count($states) > 0){
                                foreach($states as $row1){  ?>
              <?php if($this->session->userdata['enduserinfo']['state']==$row1['state_code']){echo  $row1['state_name'];}?>
              <?php } } ?>
            </div>
          </div>  
          <div class="form-group">
            <label for="roleid" class="col-sm-4 control-label">Pincode </label>
            <div class="col-sm-6"> <?php echo $this->session->userdata['enduserinfo']['pincode'];?> </div>
          </div>
          <div class="form-group">
            <label for="roleid" class="col-sm-4 control-label">Email Id</label>
            <div class="col-sm-6"><?php echo $this->session->userdata['enduserinfo']['email'];?> </div>
          </div>
          <div class="form-group">
            <label for="roleid" class="col-sm-4 control-label">Mobile No.</label>
            <div class="col-sm-6"> <?php echo $this->session->userdata['enduserinfo']['mobile'];?> </div>
          </div>
          <div class="form-group">
            <label for="roleid" class="col-sm-4 control-label">Bank/Educational Institute</label>
            <div class="col-sm-6"> <?php if($this->session->userdata['enduserinfo']['bank_education']=='education'){ echo 'Educational Institute'; } else { echo ucfirst($this->session->userdata['enduserinfo']['bank_education']); } ?> </div>
          </div>
          <div class="form-group">
            <label for="roleid" class="col-sm-4 control-label">Organization Name</label>
            <div class="col-sm-6"> <?php echo $this->session->userdata['enduserinfo']['organisation_name'];?> </div>
          </div>
          <div class="form-group">
            <label for="roleid" class="col-sm-4 control-label">Retired/Working.</label>
            <div class="col-sm-6"> <?php echo ucfirst($this->session->userdata['enduserinfo']['retired_working']);?> </div>
          </div>
          <div class="form-group">
            <label for="roleid" class="col-sm-4 control-label">Total Year of Work Experience</label>
            <div class="row">
              <div class="col-sm-2"> <?php echo $this->session->userdata['enduserinfo']['year'];?> Year  </div>
              <div class="col-sm-2"> <?php echo $this->session->userdata['enduserinfo']['month'];?> Month </div>
            </div>
          </div>
          <div class="form-group">
            <label for="roleid" class="col-sm-4 control-label">Designation</label>
            <div class="col-sm-6"> <?php echo $this->session->userdata['enduserinfo']['designation'];?> </div>
          </div>
          
        </div>
      </div>

      <?php 
        $arr_general    = $this->session->userdata['enduserinfo']['general'];
        $arr_specialise = $this->session->userdata['enduserinfo']['specialised'];
        $arr_it         = $this->session->userdata['enduserinfo']['it'];
        $arr_other      = $this->session->userdata['enduserinfo']['other'];
        // echo "<pre>"; print_r($this->session->userdata['enduserinfo']); exit;
      ?>
      <div class="form-group">
        <label for="roleid" class="col-sm-4 control-label">Subject/(s) of Expertise/Interest </label>
        <div class="col-sm-6"> 
        <a href="<?php echo $this->session->userdata['enduserinfo']['outputcv'];?>" target="blank">Download PDF</a></div>
       <div class="col-sm-12" style="padding:0;">
          <table>
           <thead style="background-color:#1287c0;color:#fff;">
              <td style="text-align:left;line-height: 18px;padding: 5px;"> <b>General Banking<br/>Subjects </b></td>
              <td style="text-align:left;line-height: 18px;padding: 5px;"><b>Specialised Banking<br/>Subjects </b></td>
              <td style="text-align:left;line-height: 18px;padding: 5px;"><b>Information Technology<br/>Subjects </b></td>
              <td style="text-align:left;line-height: 18px;padding: 5px;"><b>Other Banking<br/>Subjects </b></td>
            </thead>
            <tbody>
              <tr>
                <td style="text-align:left;" class="subject_checkbox">
                  1. AML/KYC <input type="checkbox" disabled="true"  name="general[]" value="AML/KYC" <?php if (in_array('AML/KYC',$arr_general)) { echo "checked"; } ?>>
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                  1. Auditing & Accounting <input type="checkbox" disabled="true" name="specialised[]" value="Auditing & Accounting" <?php if (in_array('Auditing & Accounting',$arr_specialise)) { echo "checked"; } ?>>
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                  1. Digital Banking <input type="checkbox" disabled="true" name="it[]" value="Digital Banking" <?php if (in_array('Digital Banking',$arr_it)) { echo "checked"; } ?>>  
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                  1. Non-banking Finance Company <input type="checkbox" disabled="true" name="other[]" value="Non-banking Finance Company" <?php if (in_array('Non-banking Finance Company',$arr_other)) { echo "checked"; } ?>> 
                </td>
              </tr>
              <tr>
                <td style="text-align:left;" class="subject_checkbox">
                  2. Banking Regulations & Business Laws <input type="checkbox" disabled="true"  name="general[]" value="Banking Regulations & Business Laws" <?php if (in_array('Banking Regulations & Business Laws',$arr_general)) { echo "checked"; } ?>>  
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                  2. Climate Risk & Sustainable Finance <input type="checkbox" disabled="true" name="specialised[]" value="Climate Risk & Sustainable Finance" <?php if (in_array('Climate Risk & Sustainable Finance',$arr_specialise)) { echo "checked"; } ?>>   
                </td>
                <td style="text-align:left;" class="subject_checkbox"> 
                  2. Emerging Technologies <input type="checkbox" disabled="true" name="it[]" value="Emerging Technologies" <?php if (in_array('Emerging Technologies',$arr_it)) { echo "checked"; } ?>> 
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                  2. Urban Co-operative Banking <input type="checkbox" disabled="true" name="other[]" value="Urban Co-operative Banking" <?php if (in_array('Urban Co-operative Banking',$arr_other)) { echo "checked"; } ?>>  
                </td>
              </tr>
              <tr>
                <td style="text-align:left;" class="subject_checkbox">
                  3. Central Banking <input type="checkbox" disabled="true" name="general[]" value="Central Banking" <?php if (in_array('Central Banking',$arr_general)) { echo "checked"; } ?>>   
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                  3. Compliance in Banking <input type="checkbox" disabled="true" name="specialised[]" value="Compliance in Banking" <?php if (in_array('Compliance in Banking',$arr_specialise)) { echo "checked"; } ?>>  
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                  3. Information Technology <input type="checkbox" disabled="true" name="it[]" value="Information Technology" <?php if (in_array('Information Technology',$arr_it)) { echo "checked"; } ?>> 
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                    
                </td>
              </tr>
              <tr>
                <td style="text-align:left;" class="subject_checkbox">
                  4. Ethics <input type="checkbox" disabled="true" name="general[]" value="Ethics" <?php if (in_array('Ethics',$arr_general)) { echo "checked"; } ?>>   
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                  4. Credit Management (including MSME) <input type="checkbox" disabled="true" name="specialised[]" value="Credit Management (including MSME)" <?php if (in_array('Credit Management (including MSME)',$arr_specialise)) { echo "checked"; } ?>>   
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                  4. IT Security <input type="checkbox" disabled="true" name="it[]" value="IT Security" <?php if (in_array('IT Security',$arr_it)) { echo "checked"; } ?>>  
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                      
                </td>
              </tr>
              <tr>
                <td style="text-align:left;" class="subject_checkbox">
                  5. Financial Management <input type="checkbox" disabled="true" name="general[]" value="Financial Management" <?php if (in_array('Financial Management',$arr_general)) { echo "checked"; } ?>>  
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                  5. Human Resources Management <input type="checkbox" disabled="true" name="specialised[]" value="Human Resources Management" <?php if (in_array('Human Resources Management',$arr_specialise)) { echo "checked"; } ?>>   
                </td>
                <td style="text-align:left;" class="subject_checkbox"> 
                  5. Prevention of Cyber Crimes & Fraud Management <input type="checkbox" disabled="true" name="it[]" value="Prevention of Cyber Crimes & Fraud Management" <?php if (in_array('Prevention of Cyber Crimes & Fraud Management',$arr_it)) { echo "checked"; } ?>>
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                      
                </td>
              </tr>
              <tr>
                <td style="text-align:left;" class="subject_checkbox">
                  6. Financial System <input type="checkbox" disabled="true" name="general[]" value="Financial System" <?php if (in_array('Financial System',$arr_general)) { echo "checked"; } ?>>   
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                  6. Insolvency & Bankruptcy Code <input type="checkbox" disabled="true" name="specialised[]" value="Insolvency & Bankruptcy Code" <?php if (in_array('Insolvency & Bankruptcy Code',$arr_specialise)) { echo "checked"; } ?>> 
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                  6. Systems Audit <input type="checkbox" disabled="true" name="it[]" value="Systems Audit" <?php if (in_array('Systems Audit',$arr_it)) { echo "checked"; } ?>> 
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                      
                </td>
              </tr>
              <tr>
                <td style="text-align:left;" class="subject_checkbox">
                  7. Indian Economy <input type="checkbox" disabled="true" name="general[]" value="Indian Economy" <?php if (in_array('Indian Economy',$arr_general)) { echo "checked"; } ?>>  
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                  7. International Banking & Forex Operations <input type="checkbox" disabled="true" name="specialised[]" value="International Banking & Forex Operations" <?php if (in_array('International Banking & Forex Operations',$arr_specialise)) { echo "checked"; } ?>>   
                </td>
                <td style="text-align:left;" class="subject_checkbox"> 
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                </td>
              </tr>
              <tr>
                <td style="text-align:left;" class="subject_checkbox">
                  8. Microfinance <input type="checkbox" disabled="true" name="general[]" value="Microfinance" <?php if (in_array('Microfinance',$arr_general)) { echo "checked"; } ?>>   
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                  8. Risk Management <input type="checkbox" disabled="true" name="specialised[]" value="Risk Management" <?php if (in_array('Risk Management',$arr_specialise)) { echo "checked"; } ?>>  
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                      
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                      
                </td>
              </tr>
              <tr>
                <td style="text-align:left;" class="subject_checkbox">
                  9. Principles of Banking <input type="checkbox" disabled="true" name="general[]" value="Principles of Banking" <?php if (in_array('Principles of Banking',$arr_general)) { echo "checked"; } ?>>   
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                  9. Strategic Management <input type="checkbox" disabled="true" name="specialised[]" value="Strategic Management" <?php if (in_array('Strategic Management',$arr_specialise)) { echo "checked"; } ?>>   
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                </td>
              </tr>
              <tr>
                <td style="text-align:left;" class="subject_checkbox">
                  10. Retail Banking <input type="checkbox" disabled="true" name="general[]" value="Retail Banking" <?php if (in_array('Retail Banking',$arr_general)) { echo "checked"; } ?>>   
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                  10. Treasury Management <input type="checkbox" disabled="true" name="specialised[]" value="Treasury Management" <?php if (in_array('Treasury Management',$arr_specialise)) { echo "checked"; } ?>>   
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                      
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                      
                </td>
              </tr>
              <tr>
                <td style="text-align:left;" class="subject_checkbox">
                  11. Rural Banking <input type="checkbox" disabled="true" name="general[]" value="Rural Banking" <?php if (in_array('Rural Banking',$arr_general)) { echo "checked"; } ?>>   
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                      
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                      
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                      
                </td>
              </tr>
              <tr>
                <td style="text-align:left;" class="subject_checkbox">
                  12. Statistics <input type="checkbox" disabled="true" name="general[]" value="Statistics" <?php if (in_array('Statistics',$arr_general)) { echo "checked"; } ?>>  
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                      
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                      
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                      
                </td>
              </tr>
              <tr>
                <td style="text-align:left;" class="subject_checkbox">
                  13. Trade Finance <input type="checkbox" disabled="true" name="general[]" value="Trade Finance" <?php if (in_array('Trade Finance',$arr_general)) { echo "checked"; } ?>>   
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                      
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                      
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                      
                </td>
              </tr>
              <tr>
                <td style="text-align:left;" class="subject_checkbox">
                  14. Wealth Management <input type="checkbox" disabled="true" name="general[]" value="Wealth Management" <?php if (in_array('Wealth Management',$arr_general)) { echo "checked"; } ?>> 
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                      
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                      
                </td>
                <td style="text-align:left;" class="subject_checkbox">
                      
                </td>
              </tr>
            </tbody>
          </table>    
          <span class="error"> </span> 
        </div>
       </div>


      <div class="box box-info">
          <div class="box-footer text-center">
            <div class="col-sm-12">
              <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Submit Application">
            </div>
          </div>
        </div>
      </div>
      </div>
    </div>
    </section>
  </form>
</div>
