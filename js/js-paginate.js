$(document).ready(function() 
{
   var dtable = $('.dataTables-example').DataTable({
		//responsive: true, /* commented as datatable is getting diturbed with this property */
		autoWidth: false,
		"dom": 'T<"clear">lfrtip',
   }); 
   
   $( "#links" ).on( "click", ".page", function(e) {
		e.preventDefault();
		var url = $(this).find('a').attr('href');

		var searchkey = $(this).val();

		var reg_no = $("#reg_no").val();
		var txn_no = $("#txn_no").val();
		var from_date = $("#from_date").val();
		var to_date = $("#to_date").val();
		var payment_mode = $("#payment_mode").val();
		var payment_status = $("#payment_status").val();
		var inst_code = $("#inst_code").val();
		var exam_period = $("#exam_period").val();
		var exam_code = $("#exam_code").val();

		var searcharrs = [];
		searcharrs['value'] = reg_no+'~'+txn_no+'~'+from_date+'~'+to_date+'~'+payment_mode+'~'+payment_status+'~'+inst_code+'~'+exam_period+'~'+exam_code;

		//alert(url);
		//return false;
		if($(this).find('a').attr('id') != 'currLink')
		{
			paginate(url,searcharrs);
		}
	});
	
	$("#perPage").change(function(){
		//alert($(this).val());
			var perPage = $(this).val();
			var searcharr = [];
			paginate('',searcharr,perPage)
		});
	
	$("#search-table").keyup(function(){
		
		var perPage = $('#perPage').val();
		//var perPage = 10;
		var searchkey = $(this).val();
		var searcharr = [];
		searcharr['field'] = $("#search_on_fields").val();
		//'exam_code,description,qualifying_exam1,qualifying_part1,qualifying_exam2,qualifying_part2,qualifying_exam3,qualifying_part2,exam_type';
		searcharr['value'] = searchkey;

		/*add date range filter POOJA MANE : 14/7/22*/
		//searcharr['from_date'] = $('#from_date').val().trim();
    		//searcharr['to_date'] = $('#to_date').val().trim();
    		/*add date range filter POOJA MANE : 14/7/22*/
		paginate('',searcharr,perPage);
	});
	
	$(".sorting, .sorting_asc, .sorting_desc ").click(function(e){
		
		e.preventDefault();
		var label = $(this).attr('aria-label');
		var sortarr = [];
		var searcharr = [];
		if($(this).attr('id').indexOf('~') == -1) 
		{
			var sort_key = $(this).attr('id');
			
			var sort_value = $(this).attr('aria-sort');
			sortarr['sortkey'] = sort_key;
			sortarr['sortval'] = sort_value;
			var perPage = $('#perPage').val();
			
			paginate('',searcharr,perPage,sortarr);
		}
		else
		{
			sortarr['sortkey'] = '';
			sortarr['sortval'] = '';
			var perPage = $('#perPage').val();
			
			paginate('',searcharr,perPage,sortarr);
		}
	});
  });
 	
function refreshDiv()
{
	$('#perPage').val(10);
	$("#search-table").val('');
	var searcharr = [];
	searcharr['field'] = '';
	searcharr['value'] = '';
	paginate('',searcharr,10);
}

function paginate(url,searcharr,perPage,sortarr)
{  
	if(searcharr['field']=='' && searcharr['field'] === undefined)
	{	
		searcharr['field'] = '';
		searcharr['value'] = '';
	}
	/*add date range filter POOJA MANE : 14/7/22*/
	if (searcharr['from_date'] == '' && searcharr['from_date'] === undefined) 
	{
		searcharr['from_date'] = '';
	}
	if (searcharr['to_date'] == '' && searcharr['to_date'] === undefined) 
	{
		searcharr['to_date'] = '';
	}
	/*add date range filter POOJA MANE : 14/7/22*/

	if(perPage=='' && perPage === undefined)
	{
		perPage = '';
	}
	
	if(sortarr!='')
	{
		var sortarr = [];
		if(sortarr['sortkey']=='' && sortarr['sortkey'] === undefined)
		{
			sortarr['sortkey'] = '';
			sortarr['sortval'] = '';
		}
	}
	
	
	
	if(url == ''){
		var url = $("#base_url_val").val();
		//var url = defaultUrl;
	}
	
	$(".loading").show();
	// console.log(url,33);

	if (searcharr['value'] == '~~~~~~~~') {
		searcharr['value'] = '';
	}

	$.ajax({
		url: url,
		type: 'POST',
		dataType:"json",
		data: {	
				field : searcharr['field'], 
				value : searcharr['value'],
				/*add date range filter POOJA MANE : 14/7/22*/
				from_date : searcharr['from_date'],
				to_date : searcharr['to_date'],
				/*add date range filter POOJA MANE : 14/7/22*/  
				per_page : perPage, 
				sortkey : sortarr['sortkey'], 
				sortval : sortarr['sortval'] },
		success: function(res) {
			//console.log(res);

			if(res)
			{ 
				if(res.success == 'Success')
				{ 
					$('.searchby-dropdown').hide();
					
					var index = res.index;
					$("#list").html('');
					
					for(i=0;i<res.result.length;i++)
					{
						var resultrow = res.result[i];
						
						var with_tds_amount = parseFloat(resultrow.amount);
						
						var tds_amount = 0;
						if (resultrow.tds_amount != null && resultrow.tds_amount != '') {
							tds_amount = parseFloat(resultrow.tds_amount);
						}
						
						var without_tds_amount = with_tds_amount+tds_amount; 
						
						var listdata = '<tr id="" style="text-align: center;">';
						var columnstr = '';
						$('#listitems th').each(function(){
						var th = $('#listitems th').eq($(this).index());
							if(th.attr('id')!='' && th.attr('id')!=undefined)
							{
								label = th.attr('id');
								
								if(label.indexOf('~') != -1) 
								{
									var colVal = label.slice(0,-1);								   
								   listdata += '<td>'+resultrow[colVal]+'</td>';
								}
								else
								{ 
									if(label == 'select')
									{
										if(res.checklist.length)
										{
											listdata += '<td>'+res.checklist[i]+'</td>';
										}
										else
										{
											listdata += '<td>-</td>';
										}
									}
									else if(label == 'srNo')
									{
										listdata += '<td>'+index+'</td>';
									}
									else if(label == 'action')
									{ 
										if(res.action.length)
										{
											listdata += '<td>'+res.action[i]+'</td>';
										}
										else
										{
											listdata += '<td>-</td>';
										}
									}
									else if(label == 'without_tds_amount')
									{ 
										if(res.action.length)
										{

											 
											listdata += '<td>'+without_tds_amount+'</td>';
										}
										else
										{
											listdata += '<td>-</td>';
										}
									}
									else if(label == 'with_tds_amount')
									{ 
										if(res.action.length)
										{

											 
											listdata += '<td>'+with_tds_amount+'</td>';
										}
										else
										{
											listdata += '<td>-</td>';
										}
									}
									else if(label == 'tds_amount')
									{ 
										if(res.action.length)
										{
											listdata += '<td>'+tds_amount+'</td>';
										}
										else
										{
											listdata += '<td>-</td>';
										}
									}
									else
									{ 
										if(resultrow[label]!=null)
											listdata += '<td>'+resultrow[label]+'</td>';
										else
											listdata += '<td></td>';
									}
								}
								
								if(i==0)
								{
									if(label.indexOf('~') == -1)
									{
										columnstr+=th.attr('id')+',';
									}
								}
							}
							else 
							{
								listdata += '<td></td>';
							}
							
							if(i==0)
							{
								$("#search_on_fields").val(columnstr);
							}
						});
						
						listdata += '</tr>';
						index++;
						
						$("#list").append(listdata);
						
						// code for DRA Admin Transaction page only, Added by Bhagwan Sahane
						if(res.total_mem_count)
						{
							$('#total_mem_count').text('Total : ' + res.total_mem_count);
							$('#total_mem_count').show();
						}
						// eof code
						
						$(".DTTT_button_print, .DTTT_button_copy, .DTTT_button_csv, .DTTT_button_xls, .DTTT_button_pdf ").hide();
						$("#listitems_length").css({"width":"45%","float":"left"});
						$('#listitems_info').html(res.info);
						$(".paging_simple_numbers").html('');
					}

					console.log(res.links);
					
					$('#links').html(res.links);
					$(".loading").hide();	
				}
				else
				{
					var colspan = 0;
					$('#listitems').find('tr:first').children().each(function(){
						var cs = $(this).attr('colspan');
						if(cs > 0){ colspan += Number(cs); }
						else{ colspan++; }
					});
					
					$("#list").html('');
					$(".DTTT_button_print, .DTTT_button_copy, .DTTT_button_csv, .DTTT_button_xls, .DTTT_button_pdf ").hide();
					var tr = '<tr id="" style="text-align: center;"><td colspan="'+colspan+'">No records found</td></tr>';
					$("#list").append(tr);
					$('#links').html('');
					$(".loading").hide();
				}
			}	
		},
		error: function(jqXHR, textStatus, errorThrown) {
			$(".loading").hide();
		  	console.log(textStatus, errorThrown);
		}
	});
}

