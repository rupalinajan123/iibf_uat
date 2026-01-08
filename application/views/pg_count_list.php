<head>
	<style> 
		.center{text-align: center;}
	</style>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<script src="<?php echo  base_url()?>js/https _code.jquery.com_jquery-3.4.1.min.js"></script>
</head>

<div class="content-wrapper" style="min-height: 946px;">

<?php 

if ($this->session->flashdata('success_message') != "") 
	{ ?>
            <div class="alert alert-success alert-dismissable">
            	<i class="fa fa-ban"></i>
            	<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
          		<b>Successfully!</b> <?php echo $this->session->flashdata('success_message'); ?>          
          	</div>
  <?php } 
?>
<?php 
	$newArray = array();
	
	$id = $this->uri->segment(3);
	foreach($result as $array) 
    {
       $newArray[$array['app_type']] = $array['cnt'];
}?>
    <form method="post" action="<?php echo base_url();?>Dailycount/daily_count/<?php echo $id; ?>">
	    <table border="1" >
	        <tr>
	            <th class="center">Module Name</th>
	            <th class="center">Counts</th>
	            <th class="center">Pg_count</th>
	            <th class="center">Edit</th>
	        </tr>
	     <?php 
		 	/*echo '<pre>';
		 	print_r($newArray1);
			echo '<br/>';
			echo count($newArray1);*/
		 if (count($newArray) > 0) 
	      {?>
	        
	        <tr>
	            <td>Bankquest Subscription</td>
	            <td class="center">

		           <?php if(isset($newArray['B']))
		                {
		                  echo $newArray['B'];
		                }
		                else
		                {
		                  echo "0";
		                }?>
	             <td class="center"><input type="text" id="edit_1" name="pg_array[1]" value="<?php if(isset($newArray1['B']) != '')
		                {
		                  echo $newArray1['B'];
		                }
		                else
		                {
		                  echo '';
		                }?>" readonly="readonly"></td>
	             
	              <td><a href="javascript:void(0);" id="edit_button_id_1" data-attr='edit_1' class="edit_cls" void>Edit</a></td>
	            </td>
	        </tr>

	        <tr>
	            <td>Duplicate Certificate</td>
	            <td class="center">

		            <?php if(isset($newArray['C']))
		              {
		                echo $newArray['C'];
		              }
		              else
		              {
		                echo "0";
		              }?>
		             <td class="center"><input type="text" id="edit_2" name="pg_array[2]" value="<?php if(isset($newArray1['C']))
		              {
		                echo $newArray1['C'];
		              }
		              else
		              {
		                echo "";
		              }?>" readonly="readonly"></td>
		             <td><a href="javascript:void(0);" id="edit_button_id_2" data-attr="edit_2"class="edit_cls">Edit</a></td>
	            </td>
	        </tr>

	        <tr>
	            <td>Duplicate ID card</td>
	            <td class="center">

		            <?php if(isset($newArray['D']))
		              {
		                echo $newArray['D'];
		              }
		              else
		              {
		                echo "0";
		              }?>
		              
		              <td class="center"><input type="text" id="edit_3" name="pg_array[3]" value="<?php if(isset($newArray1['D']))
		              {
		                echo $newArray1['D'];
		              }
		              else
		              {
		                echo "";
		              }?>" readonly="readonly"></td>
		              <td><a href="javascript:void(0);" id="edit_button_id_3" data-attr="edit_3"class="edit_cls">Edit</a></td>
	            </td>
	        </tr>

	        <tr>
	            <td>Contact Class</td>
	            <td class="center">
					<?php if(isset($newArray['E']))
		            {
		                echo $newArray['E'];
		            }
		           	else
		            {
		               echo "0";
		            }?>
	            	<td class="center"><input type="text" id="edit_4" name="pg_array[4]" value="<?php if(isset($newArray1['E']))
		            {
		                echo $newArray1['E'];
		            }
		           	else
		            {
		               echo "";
		            }?>" readonly="readonly"></td>
	            	<td><a href="javascript:void(0);" id="edit_button_id_4" data-attr="edit_4"class="edit_cls">Edit</a></td>
	            </td>
	        </tr>


	        <tr>
	            <td>Fin Quest Subscription</td>
	            <td class="center">
		            <?php if(isset($newArray['F']))
		            {
		               echo $newArray['F'];
		            }
		            else
		            {
		               echo "0";
		            }?>
		            <td class="center"><input type="text" id="edit_5" name="pg_array[5]" value="<?php if(isset($newArray1['F']))
		            {
		               echo $newArray1['F'];
		            }
		            else
		            {
		               echo "";
		            }?>" readonly="readonly"></td>
		            <td><a href="javascript:void(0);" id="edit_button_id_5" data-attr="edit_5"class="edit_cls">Edit</a></td>
	            </td>
	        </tr>


	        <tr>
	            <td>DRA Agency/Centre Registration</td>
	            <td class="center">

		            <?php if(isset($newArray['H']))
		            {
		               echo $newArray['H'];
		            }
		            else
		            {
		                echo"0";
		            }?>
		            <td class="center"><input type="text" id="edit_6" name="pg_array[6]" value="<?php if(isset($newArray1['H']))
		            {
		               echo $newArray1['H'];
		            }
		            else
		            {
		                echo "";
		            }?>" readonly="readonly"></td>
		            <td><a href="javascript:void(0);" id="edit_button_id_6" data-attr="edit_6"class="edit_cls">Edit</a></td>
	            </td>
	        </tr>

	        <tr>
	            <td>Digital Learning</td>
	            <td class="center">

		            <?php if(isset($newArray['L']))
		            {
		               echo $newArray['L'];
		            }
		            else
		            {
		               echo "0";
		            }?>
		            <td class="center"><input type="text" id="edit_7" name="pg_array[7]" value="<?php if(isset($newArray1['L']))
		            {
		               echo $newArray1['L'];
		            }
		            else
		            {
		               echo "";
		            }?>" readonly="readonly"></td>
		            <td><a href="javascript:void(0);" id="edit_button_id_7" data-attr="edit_7"class="edit_cls">Edit</a></td>
	            </td>
	        </tr>


	        <tr>
	            <td>AMP Module</td>
	            <td class="center">

		            <?php if(isset($newArray['M']))
		            {
		              echo $newArray['M'];
		            }
		            else
		            {
		              echo "0";
		            }?>
		            <td class="center"><input type="text" id="edit_8" name="pg_array[8]" value="<?php if(isset($newArray1['M']))
		            {
		              echo $newArray1['M'];
		            }
		            else
		            {
		              echo "";
		            }?>" readonly="readonly" ></td>
		            <td><a href="javascript:void(0);" id="edit_button_id_8" data-attr="edit_8"class="edit_cls">Edit</a></td>
	            </td>
	        </tr>


	        <tr>
	            <td>Membership Renewal</td>
	            <td class="center">

		            <?php if(isset($newArray['N']))
		            {
		               echo $newArray['N'];
		            }
		            else
		            {
		               echo "0";
		            }?>
		            <td class="center"><input type="text" id="edit_9" name="pg_array[9]" value="<?php if(isset($newArray1['N']))
		            {
		               echo $newArray1['N'];
		            }
		            else
		            {
		               echo "";
		            }?>" readonly="readonly"></td>
		            <td><a href="javascript:void(0);" id="edit_button_id_9" data-attr="edit_9"class="edit_cls">Edit</a></td>
	            </td>
	        </tr>


	        <tr>
	            <td>Exam</td>
	            <td class="center">

		            <?php if(isset($newArray['O']))
		            {
		              echo $newArray['O'];
		            }
		            else
		            {
		               echo "0";
		            }?>
		            <td class="center"><input type="text" id="edit_10" name="pg_array[10]" value="<?php if(isset($newArray1['O']))
		            {
		              echo $newArray1['O'];
		            }
		            else
		            {
		               echo "";
		            }?>" readonly="readonly"></td>
		            <td><a href="javascript:void(0);" id="edit_button_id_10" data-attr="edit_10"class="edit_cls">Edit</a></td>
	            </td>
	        </tr>


	        <tr>
	            <td>CPD Registration</td>
	            <td class="center">

		            <?php if(isset($newArray['P']))
		            {
		               echo $newArray['P']; 
		              }
		              else
		              {
		                echo "0";
		              }?>
		               <td class="center"><input type="text" id="edit_11" name="pg_array[11]" value="<?php if(isset($newArray1['P']))
		            {
		               echo $newArray1['P']; 
		              }
		              else
		              {
		                echo "";
		              }?>" readonly="readonly"></td>
	              <td><a href="javascript:void(0);"  id="edit_button_id_11" data-attr="edit_11"class="edit_cls">Edit</a></td>
	            </td>
	        </tr>


	        <tr>
	            <td>New Membership Registration</td>
	            <td class="center">

		            <?php if(isset($newArray['R']))
		            {
		               echo $newArray['R'];
		            }
		            else
		            {
		               echo "0";
		            }?>
		            <td class="center"><input type="text" id="edit_12" name="pg_array[12]" value="<?php if(isset($newArray1['R']))
		            {
		               echo $newArray1['R'];
		            }
		            else
		            {
		               echo "";
		            }?>" readonly="readonly"></td>
	              <td><a href="javascript:void(0);"  id="edit_button_id_12" data-attr="edit_12"class="edit_cls">Edit</a></td>
	            </td>
	        </tr>

	        <tr>
	            <td>Blended Course Registration </td>
	            <td class="center">
		            <?php if(isset($newArray['T']))
		            {
		               echo $newArray['T'];
		            }
		            else
		            {
		               echo "0";
		            }?>
		            <td class="center"><input type="text" id="edit_13" name="pg_array[13]" value="<?php if(isset($newArray1['T']))
		            {
		               echo $newArray1['T'];
		            }
		            else
		            {
		               echo "";
		            }?>" readonly="readonly"></td>
	              <td><a href="javascript:void(0);"  id="edit_button_id_13" data-attr="edit_13"class="edit_cls">Edit</a></td>
	            </td>
	        </tr>


	        <tr>
	            <td>Vision Subscription</td>
	            <td class="center">

		            <?php if(isset($newArray['V']))
		            {
		              echo $newArray['V'];
		            }
		            else
		            {
		              echo "0";
		            }?>
		            <td class="center"><input type="text" id="edit_14" name="pg_array[14]" value="<?php if(isset($newArray1['V']))
		            {
		              echo $newArray1['V'];
		            }
		            else
		            {
		              echo "";
		            }?>" readonly="readonly"></td>
	              <td><a href="javascript:void(0);"  id="edit_button_id_14" data-attr="edit_14"class="edit_cls">Edit</a></td>
	            </td>
	        </tr>

	        <tr>
	            <td>DRA Agency Centre Renewal</td>
	            <td class="center">

		            <?php if(isset($newArray['W']))
		            {
		              echo $newArray['W'];
		            }
		            else
		            {
		              echo "0";
		            }?>
		            <td class="center"><input type="text" id="edit_15" name="pg_array[15]" value="<?php if(isset($newArray1['W']))
		            {
		              echo $newArray1['W'];
		            }
		            else
		            {
		              echo "";
		            }?>" readonly="readonly"></td>
	              <td><a href="javascript:void(0);"  id="edit_button_id_15" data-attr="edit_15"class="edit_cls">Edit</a></td>
	            </td>
	        </tr>
	    </table>

		<input type="submit" name="submit" value="submit">

        <?php }
        else
      	{
        	echo "Sorry no records found ";
      	} ?>
      	
    </form>

          

<!DOCTYPE html>
<html>
<body>
<script>
	setTimeout(function() {		$('.alert').remove()	}, 3000);

        $(document).ready(function(){
				$('.edit_cls').on('click',function(){
				$('#'+$(this).attr('data-attr')).removeAttr("readonly");
				});
		});
</script>
</body>
</html>    