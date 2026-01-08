<?php
session_start();

date_default_timezone_set('Asia/Kolkata');

$dates = date("Y-m-d H:m:s");

require 'includes/config.php';

require 'vendor/autoload.php';


use Gregwar\Captcha\CaptchaBuilder;

$builder = new CaptchaBuilder;
$builder->build();





if(isset($_POST['state_id'])){
    
    
    
    if(!isset($_POST['district_id']) || !isset($_POST['block_id'])){
        $_SESSION['err']    =   'Kindly try again.';
        header('Location:index.php');
        die;
    }
    
    
    
    if(!isset($_POST['user_type'])){
        $_SESSION['err']    =   'Select User Type';
        header('Location:index.php');
        die;
    }
    
    
    /*
     * check captcha
     */
    // if($_POST['captcha'] != $_SESSION['phrase']){
        // $_SESSION['err']    =   'Captcha Mismatch.';
        // header('Location:index.php');
        // die;
    // }
    
    
    
    

//    if(date('Y-m-d') == '2018-07-19'){
//        $_SESSION['err']    =   'Form can be submitted on 2018-07-19 ';
//        header('Location:index.php');
//        die;
//    }
    
    
    
    
    
    
    if(isset($_POST['cscid']) && strlen($_POST['cscid'] ) != '12'){
        $_SESSION['err']    =   'CSC ID Must be exactly 12 digit long.';
        header('Location:index.php');
        die;
    }
    
    if(isset($_POST['cscid']) && substr($_POST['cscid'] ,10, 1) != '1'){
        $_SESSION['err']    =   '11th Digit in CSC ID Must be 1.';
        header('Location:index.php');
        die;
    }
    
    if(isset($_POST['cscid'])){
        $res    =   $con->query(" SELECT id FROM users WHERE cscid = '".$_POST['cscid']."' ");
        if($res->num_rows){
            $_SESSION['err']    =   'This CSC ID ('.$_POST['cscid'].') is already submitted.';
            header('Location:index.php');
            die;
        }
    }
    
    
//    
//    $res    =   $con->query(" SELECT id FROM users WHERE mobile = '".$_POST['mobile']."' ");
//    if($res->num_rows){
//        $_SESSION['err']    =   'This Mobile ('.$_POST['Mobile'].') is already submitted.';
//        header('Location:index.php');
//        die;
//    }

    
    
    
    
    
    
    
    
    if($_FILES['img1']['size'] > '2000000'){
        
        $_SESSION['err']    =   'Image 1 is more than 2 mb.';
        header('Location:index.php');
        die;
        
    }
    
    
    if(isset($_FILES['img2']['size']) && $_FILES['img2']['size'] > '2000000'){
        
        $_SESSION['err']    =   'Image 2 is more than 2 mb.';
        header('Location:index.php');
        die;
        
    }
    
    
    if(isset($_FILES['img3']['size']) && $_FILES['img3']['size'] > '2000000'){
        
        $_SESSION['err']    =   'Image 3 is more than 2 mb.';
        header('Location:index.php');
        die;
        
    }
    
    
    /*
     * fix
     */
//    $_POST['staff'] = $_POST['staff']." ".date('s').rand(0,9);
    
    
    
    
    /*
     * AWS S3 Code
     * 
     */
    
    $s3 =   new \Aws\S3\S3Client([
        'version' => 'latest',
        'region'  => 'ap-south-1',
        'credentials'=>[
            'key'    => 'AKIAJT2UIV4BWYZPU3FA',
            'secret'=>'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'
        ]
    ]);

    
    try {

        
        if($_POST['user_type']=='vle'){
           $file1  =   $_POST['state_hidden'].'-'.$_POST['district_hidden'].'-'.$_POST['block_hidden'].'-'.$_POST['cscid'].'-01-'.date('s').rand(0,9).'.'.pathinfo($_FILES['img1']['name'], PATHINFO_EXTENSION);
        }else{
            $file1  =   $_POST['state_hidden'].'-'.$_POST['district_hidden'].'-'.$_POST['block_hidden'].'-'.$_POST['staff'].'-01-'.date('s').rand(0,9).'.'.pathinfo($_FILES['img1']['name'], PATHINFO_EXTENSION);
        }
        
                
        // Upload data.
        $result = $s3->putObject([
            'Bucket' => 'tec-certificate',
            'Key'    => 'pmevent11092018/'.$file1,
            'Body'   => fopen($_FILES['img1']['tmp_name'],'r'),
        ]);
        
        
        if($_FILES['img2']['size']){
            
            if($_POST['user_type']=='vle'){
                $file2  =   $_POST['state_hidden'].'-'.$_POST['district_hidden'].'-'.$_POST['block_hidden'].'-'.$_POST['cscid'].'-02-'.date('s').rand(0,9).'.'.pathinfo($_FILES['img1']['name'], PATHINFO_EXTENSION);;
            }else{
                $file2  =   $_POST['state_hidden'].'-'.$_POST['district_hidden'].'-'.$_POST['block_hidden'].'-'.$_POST['staff'].'-02-'.date('s').rand(0,9).'.'.pathinfo($_FILES['img1']['name'], PATHINFO_EXTENSION);;
            }
                
            // Upload data.
            $result = $s3->putObject([
                'Bucket' => 'tec-certificate',
                'Key'    => 'pmevent11092018/'.$file2,
                'Body'   => fopen($_FILES['img2']['tmp_name'],'r'),
            ]);

        }else{
            $file2  =   '';
        }
        
        
        if($_FILES['img3']['size']){
            
            if($_POST['user_type']=='vle'){
                $file3  =   $_POST['state_hidden'].'-'.$_POST['district_hidden'].'-'.$_POST['block_hidden'].'-'.$_POST['cscid'].'-03-'.date('s').rand(0,9).'.'.pathinfo($_FILES['img1']['name'], PATHINFO_EXTENSION);;
            }else{
                $file3  =   $_POST['state_hidden'].'-'.$_POST['district_hidden'].'-'.$_POST['block_hidden'].'-'.$_POST['staff'].'-03-'.date('s').rand(0,9).'.'.pathinfo($_FILES['img1']['name'], PATHINFO_EXTENSION);;
            }
                
            // Upload data.
            $result = $s3->putObject([
                'Bucket' => 'tec-certificate',
                'Key'    => 'pmevent11092018/'.$file3,
                'Body'   => fopen($_FILES['img3']['tmp_name'],'r'),
            ]);
            
        }else{
            $file3  =   '';
        }
        
        
        
        
        
        

    } catch (Aws\S3\Exception\S3Exception $e) {
        echo "There was an error uploading the file.\n";
        die;
    }

    $_POST['suggestions']   =   $con->real_escape_string($_POST['suggestions']);
    
    $sql    =   " 
        INSERT INTO `users` 

        SET 

        `cscid`='".$_POST['cscid']."',
        `staff`='".$_POST['staff']."',
        `mobile`='".$_POST['mobile']."',
        `state_id`='".$_POST['state_id']."',
        `district_id`='".$_POST['district_id']."',
        `block_id`='".$_POST['block_id']."',
        `panchayat`='".$_POST['panchayat']."',
        `total_participants`='".$_POST['total_participants']."',
        `suggestions`='".$_POST['suggestions']."',
        `img1`='".$file1."',
        `img2`='".$file2."',
        `img3`='".$file3."',
        `created`='".$dates."'
        
        ";
    
    
    $con->query($sql);
    $_SESSION['success']    =   'Uploaded Successfully. ';
    header('Location:index.php');
    die;
    
}


$_SESSION['phrase'] = $builder->getPhrase();

?>

<!doctype html>
<html>
    <head>


        <style type="text/css">
            .form-style-5{
                max-width: 500px;
                padding: 10px 20px;
                background: #f4f7f8;
                margin: 10px auto;
                padding: 20px;
                background: #f4f7f8;
                border-radius: 8px;
                font-family: Georgia, "Times New Roman", Times, serif;
            }
            .form-style-5 fieldset{
                border: none;
            }
            .form-style-5 legend {
                font-size: 1.4em;
                margin-bottom: 10px;
            }
            .form-style-5 label {
                display: block;
                margin-bottom: 8px;
            }
            .form-style-5 input[type="text"],
            .form-style-5 input[type="date"],
            .form-style-5 input[type="datetime"],
            .form-style-5 input[type="email"],
            .form-style-5 input[type="number"],
            .form-style-5 input[type="search"],
            .form-style-5 input[type="time"],
            .form-style-5 input[type="url"],
            .form-style-5 textarea,
            .form-style-5 select {
                font-family: Georgia, "Times New Roman", Times, serif;
                background: rgba(255,255,255,.1);
                border: none;
                border-radius: 4px;
                font-size: 16px;
                margin: 0;
                outline: 0;
                padding: 7px;
                width: 100%;
                box-sizing: border-box;
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                background-color: #e8eeef;
                color:#8a97a0;
                -webkit-box-shadow: 0 1px 0 rgba(0,0,0,0.03) inset;
                box-shadow: 0 1px 0 rgba(0,0,0,0.03) inset;
                margin-bottom: 30px;

            }
            .form-style-5 input[type="text"]:focus,
            .form-style-5 input[type="date"]:focus,
            .form-style-5 input[type="datetime"]:focus,
            .form-style-5 input[type="email"]:focus,
            .form-style-5 input[type="number"]:focus,
            .form-style-5 input[type="search"]:focus,
            .form-style-5 input[type="time"]:focus,
            .form-style-5 input[type="url"]:focus,
            .form-style-5 textarea:focus,
            .form-style-5 select:focus{
                background: #d2d9dd;
            }
            .form-style-5 select{
                -webkit-appearance: menulist-button;
                height:35px;
            }
            .form-style-5 .number {
                background: #1abc9c;
                color: #fff;
                height: 30px;
                width: 30px;
                display: inline-block;
                font-size: 0.8em;
                margin-right: 4px;
                line-height: 30px;
                text-align: center;
                text-shadow: 0 1px 0 rgba(255,255,255,0.2);
                border-radius: 15px 15px 15px 0px;
            }

            .form-style-5 input[type="submit"],
            .form-style-5 input[type="button"]
            {
                position: relative;
                display: block;
                padding: 19px 39px 18px 39px;
                color: #FFF;
                margin: 0 auto;
                background: #1abc9c;
                font-size: 18px;
                text-align: center;
                font-style: normal;
                width: 100%;
                border: 1px solid #16a085;
                border-width: 1px 1px 3px;
                margin-bottom: 10px;
            }
            .form-style-5 input[type="submit"]:hover,
            .form-style-5 input[type="button"]:hover
            {
                background: #109177;
            }
        </style>

    </head>

    <body>

    <center><img  src="banner.jpg" alt="" width="900" height="250"/></center>
    <a href="http://pmindiawebcast.nic.in/" target="_blank" style="float:right;">Click here for Live Broadcast</a>
    
    
        <br>
    <center>
      
    
        <h2>Upload Session Images</h2>
    </center>
    <br>

    
    
    <?php if(isset($_SESSION['err'])): ?>
    <br/>
    <br/>
    
    <center><h3 style="color:red;">Error : <?php echo $_SESSION['err']; ?></h3></center>
    
    <?php unset($_SESSION['err']); ?>
    <?php endif; ?>
    
    
    <?php if(isset($_SESSION['success'])): ?>
    <br/>
    <br/>
    
    <center><h3 style="color:green;">Success : <?php echo $_SESSION['success']; ?></h3></center>
    <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    
    
    <div class="form-style-5">
        <form method="post" enctype="multipart/form-data" autocomplete="off">
        <input type="hidden" name="state_hidden" id="state_hidden">
        <input type="hidden" name="district_hidden" id="district_hidden">
         <input type="hidden" name="block_hidden" id="block_hidden">
        
            <fieldset>
                
                <legend><span class="number">1</span> VLE/Staff Information</legend>
                
                <br>
                <label>User Type</label>
                <select id="user_type" name="user_type" required onChange="
                    
                    
                   userType(this)
                    
                " >  

                    <option value="">Select User Type *</option>
                    <option value="vle">VLE</option>
                    <option value="staff">CSCSPV Staff</option>
                </select>
                <br>
                <br>
                
                <div id="place-holder" ></div>
                
                
                <input type="text" id="mobile" name="mobile" placeholder="Mobile"  >
                
                <select id="state_id" name="state_id" required >  

                    <option value="">Select State *</option>
                    
                    <?php 
                        $res  =   $con->query(" SELECT id, name FROM states ORDER BY name; "); 
                        
                        while($row  =   $res->fetch_object()):
                    ?>
                    <option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
                    <?php endwhile; ?>
                    
                    
                </select>
                
                
                <select id="district_id" name="district_id" required >

                    <option value="">Select District *</option>
                    
                    
                    
                    
                </select>
                
                
                
                <select id="block_id"  name="block_id" required >

                    <option value=" ">Select Block *</option>
                    
                    
                    
                    
                </select>
                
                
                <input type="text" name="panchayat" placeholder="Panchayat *" required="" >
                
                <input type="text" id="total_participants" name="total_participants" placeholder="Total No. of Participants *" required="" >
                
                
                
                
                
                
                
                
            <textarea name="suggestions" placeholder="Suggestions, if any" style="height:100px;"></textarea>          
            </fieldset>
            
            

            <fieldset>
                <legend><span class="number">2</span> Session Images (Max 2 MB jpg jpeg png)</legend>
                
                <label>Image 1 *</label>
                <input type="file" name="img1" required="" accept="image/*" >
                <br>
                <br>
                
                <label>Image 2 (Optional)</label>
                <input type="file" name="img2" accept="image/*" >
                <br>
                <br>
                
                <label>Image 3 (Optional)</label>
                <input type="file" name="img3" accept="image/*" >
                
                <br>
                <br>
                
            </fieldset>
            
            <!--<img src="<?php echo $builder->inline(); ?>" />
            <input type="text" name="captcha" placeholder="Enter the text above here" required="" >-->

            <input type="submit" value="Submit" />
        </form>
    </div>


    <script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
  
    <script>
        
        
        function userType(arg){
            
            
            if($(arg).val() == 'vle'){
                
                $('#place-holder').html('<input id="cscid" type=\"text\" name=\"cscid\" placeholder=\"CSC ID *\" required=\"\" >');
                $('#cscid').mask('000000000000');
                
            }else if($(arg).val() == 'staff'){

                $('#place-holder').html('<input type=\"text\" name=\"staff\" placeholder=\"CSCSPV Staff Name *\" required=\"\" >');
                
            }else{
                $('#place-holder').html('');
            }
            
        }
        
        $(function(){
            
            $('#state_id').bind('change',function(){
               $stname = $('#state_id :selected').text();
			   
			   $('#state_hidden').val($stname);
			   
               
               $.ajax({
                type: "POST",
                url: "includes/get_districts.php",
                data:"state_id="+$('#state_id').val(),
                
                success: function(data){//html = the server response html code
                    //alert(html);
                    $('#district_id').html(data);	
//                    console.log(data);	
                },
                error:function(){ alert("Internal Error Server Not found!");}
            });
            });
            
            
            $('#district_id').bind('change',function(){
                $dtname = $('#district_id :selected').text();
			   
			   $('#district_hidden').val($dtname);
               
               $.ajax({
                type: "POST",
                url: "includes/get_blocks.php",
                data:"district_id="+$('#district_id').val(),
                
                success: function(data){//html = the server response html code
                    //alert(html);
                    $('#block_id').html(data);	
//                    console.log(data);	
                },
                error:function(){ alert("Internal Error Server Not found!");}
            });
            });
			
			
			$('#block_id').bind('change',function(){
                $blkname = $('#block_id :selected').text();
			   
			   $('#block_hidden').val($blkname);
               
               
            });
            
            $('#total_participants').mask('000');
            $('#mobile').mask('0000000000');
            
            
            
        });
        
        </script>

</body>



</html>
