<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript">
	function download_training_schedule_api(file_name,binary_pdf_data) {
		var site_url = 'https://iibf.esdsconnect.com/staging/';
		//var binary_pdf_data = $("#training_file_download_"+row_no).val();
		//alert(site_url+'/'+row_no);
		$.ajax({
			type: 'POST',
			url: site_url + 'Custom_sm/Show_Brochure_File_Pdf_Download/',
			data : {'binary_pdf_data':binary_pdf_data,'file_name':file_name},
			beforeSend: function(xhr) {
		      //$(selector).attr('disabled',true).text('Processing..')  
		    },
			//async: true,
			success: function(response) { 
			 	setTimeout(function() {
                    //alert(response);  
	                var urlPattern = /(https?:\/\/[^\s]+)/g;
		            var urls = response.match(urlPattern); 
		            if (urls) {
						var extractedUrl = urls[0]; // Assuming there's only one URL in the input string
						//console.log('Extracted URL:', extractedUrl); 
						var href = extractedUrl;
						var newTab = 1;
						var a = document.createElement('a');
						a.href = href;
						if (newTab) {
						  a.setAttribute('target', '_blank');
						}
						a.click(); 
		            } else {
		                console.log('No URL found');
		            } 
            	}, 2000); 
			}
		});
	}
</script>