$(document).ready(function() 
{
   var dtable = $('.dataTables-example').DataTable({
		responsive: true,
		autoWidth: false,
		"dom": 'T<"clear">lfrtip',
   }); 
   
    
   /*var columnstr = '';
   $('#listitems th').each(function(){
	var th = $('#listitems th').eq($(this).index());
		if(th.attr('id')!='' && th.attr('id')!=undefined)
		{
			columnstr+=th.attr('id')+',';
		}
		
		$("#search_on_fields").val(columnstr);
	});*/
	
	
   
   //var dtable = $('.dataTables-example').DataTable();
 
   //paginate('');
   
   $( "#links" ).on( "click", ".page", function(e) {
		e.preventDefault();
		var url = $(this).find('a').attr('href');
		//alert(url);
		//return false;
		if($(this).find('a').attr('id') != 'currLink')
		{
			paginate(url,'');
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
		paginate('',searcharr,perPage)
	});
	
	$(".sorting, .sorting_asc, .sorting_desc ").click(function(e){
		e.preventDefault();
		var label = $(this).attr('aria-label');
		var sortarr = [];
		var searcharr = [];
		
		var sort_key = $(this).attr('id');
		
		var sort_value = $(this).attr('aria-sort');
		sortarr['sortkey'] = sort_key;
		sortarr['sortval'] = sort_value;
		var perPage = $('#perPage').val();
		
		paginate('',searcharr,perPage,sortarr);
		
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
	
	$.ajax({
		url: url,
		type: 'POST',
		dataType:"json",
		data: {field : searcharr['field'], value : searcharr['value'], per_page : perPage, sortkey : sortarr['sortkey'], sortval : sortarr['sortval'] },
		success: function(res) {
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
						
						var listdata = '<tr id="" style="text-align: center;"><td>'+index+'</td>';
						var columnstr = '';
						$('#listitems th').each(function(){
						var th = $('#listitems th').eq($(this).index());
							if(th.attr('id')!='' && th.attr('id')!=undefined)
							{
								label = th.attr('id');
								if(label == 'action')
								{
									listdata += '<td>'+res.action[i]+'</td>';
								}
								else
								{
									if(resultrow[label]!=null)
										listdata += '<td>'+resultrow[label]+'</td>';
									else
										listdata += '<td></td>';
								}
								
								if(i==0)
									columnstr+=th.attr('id')+',';
							}
							if(i==0)
							{
								$("#search_on_fields").val(columnstr);
							}
						});
						//listdata += '<td>Add/Edit</td></tr>';
						listdata += '</tr>';
						index++;
						
						//$("#list").append(tr);
						$("#list").append(listdata);
						
						$(".DTTT_button_print").hide();
						$("#listitems_length").css({"width":"45%","float":"left"});
						$('#listitems_info').html(res.info);
						$(".paging_simple_numbers").html('');
					}
					$('#links').html(res.links);	
				}
				else
				{
					//var colSpan = $( "#listitems thead tr:nth-child(1) td" ).length;
					var colspan = 0;
					$('#listitems').find('tr:first').children().each(function(){
						var cs = $(this).attr('colspan');
						if(cs > 0){ colspan += Number(cs); }
						else{ colspan++; }
					});
					
					//var colSpan = $("#listitems  table > thead > tr:first > td").length;alert(colSpan);
					$("#list").html('');
					var tr = '<tr id="" style="text-align: center;"><td colspan="'+colspan+'">No records found</td></tr>';
					$("#list").append(tr);
					$('#links').html('');
				}
			}	
		}
	});
}

