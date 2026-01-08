<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>IIBF Complaint</title>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/bs-3.3.5/jq-2.1.4,dt-1.10.8/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/r/bs-3.3.5/jqc-1.11.3,dt-1.10.8/datatables.min.js"></script>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		$('#example').DataTable();
		
	} );
	
</script>
</head>
<body>
<div class="container" style="width:100%">
<h1 style="text-align:center;">CMS COMPLAINT</h1>
<hr>
<?php
/*$login = 'iibfadmin';
$pass = 'iibf@123';

if(($_SERVER['PHP_AUTH_PW']!= $pass || $_SERVER['PHP_AUTH_USER'] != $login)|| !$_SERVER['PHP_AUTH_USER'])
{
    header('WWW-Authenticate: Basic realm="Test auth"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Auth failed';
    exit;
}*/
?>

<form method="post" name="form" action="<?php echo base_url()?>complaint">
	<input type="date" name="fday">
	<input type="submit" name="submit" value="Submit">
</form>

<hr>

  
  <table id="example" class="display" cellspacing="0" width="100%">
    <thead>
      <tr>
      	<th>Sr.No</th>
        <th>Member Number</th>
        <th>Member Type</th>
        <th>Complaint</th>
        <th>Email ID</th>
        <th>Mobile</th>
        <th>Exam Code</th>
        <th>Subject Code</th>
        <th>Category Code</th>
        <th>Complain Date</th>
        <th>Attachment</th>
      </tr>
    </thead>
    <tbody>
    <?php
		$i = 1;
	 	foreach($record as $result){
    ?>
      <tr>
      	<td><?php echo $i; ?></td>
        <td><?php echo $result['regnumber']; ?></td>
        <td><?php echo $result['member_type'];?></td>
        <td><?php echo $result['complain_details'];?></td>
        <td><?php echo $result['email'];?></td>
        <td><?php echo $result['mobile'];?></td>
        <td><?php echo $result['exam_code'];?></td>
        <td><?php echo $result['subcatcode'];?></td>
        <td><?php echo $result['category_code'];?></td>
        <td><?php echo $result['complain_date'];?></td>
        <td>
        	<?php
				if($result['attachment']!=''){
		    ?>
        	<a href="<?php echo base_url()."uploads/cms/".$result['attachment']?>" target="_blank">view</a>
            <?php }?>
		</td>
      </tr>
    <?php $i++; }?>  
    </tbody>
  </table>
</div>
<script type="text/javascript">
	// For demo to fit into DataTables site builder...
	$('#example')
		.removeClass( 'display' )
		.addClass('table table-striped table-bordered');
		
		
		
</script>
</body>
</html>