<!DOCTYPE html>
<html>
  <head>    
    <style>
      body { color:#000 !important; font-size:14px; }
      h1, h2, h3, h4, h5, h6 { margin:0  !important; }

      .mt-2 { margin-top: 4px !important; }
      .mt-3 { margin-top: 6px !important; }
      .mt-4 { margin-top: 8px !important; }
      .mb-2 { margin-bottom: 4px !important; }
      .mb-3 { margin-bottom: 6px !important; }
      .mb-4 { margin-bottom: 8px !important; }

      .heading_row, th { background: #eee !important; }
      .text-center { text-align:center !important; }
      .text-danger { color:#ed5565 !important; }
      .table { border-collapse: collapse !important; border:none !important; }
      .table thead th, .table tbody td { border-collapse: collapse !important; border : 1px solid #000 !important; padding:8px 10px !important; color:#000 !important; font-size:12px !important; vertical-align:top !important; }
      .table tbody td.empty_row { background: #fff !important; border: none !important; padding: 0 !important; height: 25px !important; }
      td.wrap { word-break: break-all !important;  word-wrap: anywhere !important; white-space: wrap !important; }

      .ibox { margin-top:25px !important; }
      .ibox-title { padding: 8px 10px !important; min-height: unset !important; border-radius: 0 !important; background: #dedede !important; border: 1px solid #000 !important; box-shadow: 0 0 0 0 !important; border-bottom: none !important; text-align: center !important; }
      .ibox-title > h5 { margin:0 !important; font-size:14px !important; }
      .ibox-title > .ibox-tools { display:none !important; }
      .ibox-content { padding: 0 !important; border-radius: 0 !important; box-shadow: 0 0 0 0 !important; border: none !important; }
    </style>
  </head>
  
	<body>
    <section class="text-center">
      <h2><b>NCVET Inspection - Online Training Form</b></h2>
      <h3 class="text-danger mt-3"><b>(This form will be filled in by the inspector while inspecting the batch)</b></h3>     
      <h4 class="mt-4">The NCVET Training Programs are to be conducted as per the latest terms and conditions as laid down by IIBF and abided by all the NCVET Institutions / Agencies.</h4> 
      <h4 class="mt-2 mb-4">Below mentioned format is to be filled with the fact of the training activities as delivered by the agencies and experienced by the assigned Inspector.</h4>               
    </section>

    <?php $this->load->view('ncvet/common/inc_inspection_report_content_common'); ?>
  </body>
</html>