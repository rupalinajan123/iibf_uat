<style type="text/css">
	tfoot { display: table-header-group; }
	.pp0 , .pp1, .pp4 , .pp5, .pp6 , .pp7 { display:none; }	

	#example1_processing { display:none !important; }
	#page_loader { background: rgba(0, 0, 0, 0.35) none repeat scroll 0 0; height: 100%; left: 0; position: fixed; top: 0; width: 100%; z-index: 99999; display:none; }
	/* #page_loader .loading { margin: 0 auto; position: relative;border: 16px solid #f3f3f3;border-radius: 50%;border-top: 16px solid #064b86;border-bottom: 16px solid #064b86;width: 80px;height: 80px;-webkit-animation: spin 2s linear infinite;animation: spin 2s linear infinite;top: calc( 50% - 40px);} */
	#page_loader .loading { margin: 0 auto; position: relative;	width: 80px;height: 80px;top: calc( 50% - 40px);color: #fff;font-size: 30px; }
	@-webkit-keyframes spin { 0% { -webkit-transform: rotate(0deg); } 100% { -webkit-transform: rotate(360deg); } }
	@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
</style>
<div id="page_loader"><div class="loading">Processing...</div></div>

<div class="content-wrapper">
    <section class="content-header">
		<?php $_SESSION['reffer'] = $_SERVER['REQUEST_URI']; ?>		
        <h1>All Candidates</h1>
    </section>
	
    <form name="draexampay" autocomplete="off" class="draexampay" method="post" action="<?php echo base_url();?>iibfdra/Version_2/DraExam/payment/<?php //echo base64_encode($examcode);?>">
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"><?php //echo ucwords($desc);?>Candidates Details</h3>
                        <div class="pull-right">
                            <a href="<?php echo base_url();?>iibfdra/Version_2/TrainingBatches" class="btn btn-warning">Back</a>
                       </div>
                    </div>
						
                    <div class="box-body">
                        <?php if($this->session->flashdata('error')!=''){?>
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <?php echo $this->session->flashdata('error'); ?>
                            </div>
                        <?php } if($this->session->flashdata('success')!=''){ ?>
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <?php echo $this->session->flashdata('success'); ?>
                            </div>
                        <?php } ?> 
                     
                        <table  id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
									<th class="text-center no-sort" id="srNo" style="padding-right:8px;">S.No.</th>
									<th class="text-center" id="exam_center_code">Center Name</th>
									<th class="text-center" id="exam_center_code">Batch Code</th>
									<th class="text-center" id="batch_id">Batch Name</th>
									<th class="text-center" id="regnumber">Member Number</th>
									<th class="text-center" id="firstname">Candidate Name</th>
									<th class="text-center" id="dateofbirth">DOB</th>
									<th class="text-center" id="email">Email</th>										
									<th class="text-center no-sort" id="action" style="padding-right:8px;">Operations</th> 
                                </tr>
                            </thead>
							<tbody></tbody>
							<!-- <tfoot>
                            	<tr>
									<th class="text-center no-sort" id="srNo" style="padding-right:8px;">S.No.</th>
									<th class="text-center">Center Name</th>
									<th class="text-center">Batch Code</th>
									<th class="text-center">Batch Name</th>
									<th class="text-center">Member Number</th>
									<th class="text-center">Candidate Name</th>
									<th class="text-center">DOB</th>
									<th class="text-center">Email</th>										
									<th class="text-center no-sort" style="padding-right:8px;">Operations</th> 
                            	</tr>
							</tfoot> -->
                        </table>
                        </div>
                    </div>
                </div>
        </div>
    </section>
    </form>
</div>
<script type="text/javascript">
	$(function () 
	{
    /*$("#listitems").DataTable();
    var base_url = '<?php //echo base_url(); ?>';
    paginate(base_url+'iibfdra/Version_2/DraExam/getApplicantList','','','');
    $("#base_url_val").val(base_url+'iibfdra/Version_2/DraExam/getApplicantList');*/
    // add multiple select / deselect functionality
		$("#selectall").click(function () 
		{
          $('.chkmakepay').prop('checked', this.checked);
    });

    // if all checkbox are selected, check the selectall checkbox
    // and viceversa
		$(".chkmakepay").click(function()
		{
        if($(".chkmakepay").length == $(".chkmakepay:checked").length) {
            $("#selectall").prop("checked", true);
        } else {
            $("#selectall").removeAttr("checked");
        }

    });
		
		$( ".draexampay" ).submit(function() 
		{
        if( $(".chkmakepay:checked").length == 0 ) {
            alert('Please select at least one candidate to pay');
            return false;   
        } else {
            return true;    
        }
    });
});
</script>
<!-- DataTables -->
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">

<!-- Data Tables -->
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
<!-- Data Tables -->

<script>
	/* $(function () 
		{
   
   // $("#listitems2_filter").show();
    
     // DataTable
    var table = $('#example1').DataTable();
 
        $("#example1 tfoot th").each( function ( i ) {
        var select = $('<select  class="pp'+i+'" ><option value="">All</option></select>')
            .appendTo( $(this).empty() )
            .on( 'change', function () {
                table.column( i )
                    .search( $(this).val() )
                    .draw();
            } );
 
        table.column( i ).data().unique().sort().each( function ( d, j ) {
            select.append( '<option value="'+d+'">'+d+'</option>' )
        } );
    } );
        $("body").on("contextmenu",function(e){
        return false;
    });
	}); */
</script> 

<script type="text/javascript">
	$(document).ready(function()
	{
		var userDataTable = $('#example1').DataTable(
		{
			"processing": true,
			"serverSide": true,
			"ajax": {
				"url": '<?php echo site_url("iibfdra/Version_2/TrainingBatches/get_candidates_data_ajax"); ?>', 
				"type": "POST",
				"data": function ( d ) 
				{
					/* d.s_agency_name = $("#s_agency_name").val(); */
				}
			},
			"lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
			"language": 
			{
				"lengthMenu": "_MENU_",
				/* "zeroRecords": "Nothing found - sorry",
					"info": "Showing page _PAGE_ of _PAGES_",
					"infoEmpty": "No records available",
				"infoFiltered": "(filtered from _MAX_ total records)" */
			},
			pageLength: 10,
			responsive: true,
			"columnDefs": 
			[
			{"targets": 'no-sort', "orderable": false, },
			{"targets": [0], "className": "text-center"},
			{"targets": [8], "className": "text-center"},
			/* {"targets": [6], "className": "text-center"},
			{"targets": [7], "className": "text-center"},
			{"targets": [8], "className": "text-center"},
			{"targets": [9], "className": "text-center"}, */
			],
			"aaSorting": [],
			"stateSave": false,
			/* dom: '<"html5buttons"B>lTfgitp',
				buttons: 
				[
				{extend: 'copy'},
				{extend: 'csv', title: 'list_csv_<?php echo date("Ymdhis"); ?>', exportOptions: { columns: [ 0, 1, 2, 3, 4, 5, 6, 8 ] }},
				{extend: 'excel', title: 'list_csv_<?php echo date("Ymdhis"); ?>', exportOptions: { columns: [ 0, 1, 2, 3, 4, 5, 6, 8 ] }},
				{extend: 'pdf', title: 'ExampleFile'},					
				{
				extend: 'print',
				customize: function (win)
				{
				$(win.document.body).addClass('white-bg');
				$(win.document.body).css('font-size', '10px');
				$(win.document.body).find('table')
				.addClass('compact')
				.css('font-size', 'inherit');
				}
				}
			], */
			'drawCallback': function(settings)
			{
				/* $( ".checkboxlist_new" ).click(function() { checkboxlist_new_function(); });
					$('#checkboxlist_all_new').prop('checked', false);
					checkboxlist_new_function();
				$('input[type=checkbox][data-toggle^=toggle]').bootstrapToggle(); */
				/* var response = settings.json;        
					$("#s_agency_name").html(response.agency_name_str);
					$("#s_center_location").html(response.center_location_str);
					$("#s_batch_code").html(response.batch_code_str);
				$("#s_batch_name").html(response.batch_name_str); */
			}			
});
	});
  
  $(document).ajaxStart(function() { $("#page_loader").css("display", "block"); });
  $(document).ajaxComplete(function() { $("#page_loader").css("display", "none"); });
</script> 