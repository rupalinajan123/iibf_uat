$(document).ready(function() 
{
   var dtable = $('.dataTables-example').DataTable({
		responsive: true,
		autoWidth: false,
		"dom": 'T<"clear">lfrtip',
   }); 
   
   var dtable = $('.dataTables-example').DataTable();
 
   paginate('');
   
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
		searcharr['field'] = 'exam_code,description,qualifying_exam1,qualifying_part1,qualifying_exam2,qualifying_part2,qualifying_exam3,qualifying_part2,exam_type';
		searcharr['value'] = searchkey;
		paginate('',searcharr,perPage)
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