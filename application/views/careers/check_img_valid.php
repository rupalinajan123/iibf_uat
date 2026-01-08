<!DOCTYPE html>
<html>
	<head></head>
	<body>
		<form method="post" action="<?php echo site_url('careers/check_img_valid'); ?>" enctype="multipart/form-data" style="border: 1px solid #ccc; padding: 20px; width: 300px; margin: 50px auto 0; background: #f7f7f7; ">
			<?php if($response != "") { echo $response.'<br>'; } ?>
			<input type="file" name="chk_img" id="chk_img" value="" accept=".jpeg,.jpg" required style="border: 1px solid #ccc; background: #fff; padding: 10px; width: 280px; "><br><br>
			<button class="btn" type="submit" value="submit" style="padding: 5px 10px;">Submit</button>
		</form>
	</body>
</html>