<?php

include "database.php";
sleep(1);
if (!isset($_POST["case"])) return_exit("Invalid Request");

$case = $_POST["case"];
$current_date = strtotime(date("d-m-Y H:i:s"));

switch ($case) {
    case "login":
        if (isset($_POST["full_name"], $_POST["email"])) {
            $full_name = sanitize_text($_POST["full_name"]);
            $email = sanitize_text($_POST["email"]);

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return_exit("Email format is invalid");
            }
            if (is_empty($full_name)) return_exit("Full name is empty");

            $query = mysqli_query($conn, "SELECT * FROM upload_webcam WHERE user_email = '$email' ");
            if (!mysqli_num_rows($query)) {
                $query = mysqli_query($conn, "INSERT INTO upload_webcam (`user_email`,`full_name`,`photo`,`date`) VALUES ('$email','$full_name','avatar.png','$current_date') ");
                if (!$query) return_exit("Error in login".mysqli_error($conn));
            }

            $setcookie = setcookie("iuemail", $email, time() + (86400 * 30), "/"); // 86400 = 1 day
            if (!$setcookie) return_exit("Error in saving cookie");
            return_exit("success");
        } else {
            return_exit("Invalid Request");
        }
        break;

    case "uploadAvatar":
        if (!isUserLogin()) return_exit("Login required");
        $email = loginEmail();

        if (!isset($_FILES['avatar'])) return_exit("Invalid Request");
        $file = $_FILES["avatar"];
        $file = preg_replace("/\s+/", "_", $file);
        $filename = $file['name'];
        $filepath = $file['tmp_name'];
        $file_extension =  strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $fileinfo = getimagesize($file['tmp_name']);
        $fileerror = $file['error'];
        $allowed_image_extension = array("png", "jpg", "jpeg");
        $ext_err_text = "Upload a valid image. Only PNG, JPG  and JPEG are allowed.";
        if ($fileerror != 0) return_exit("Error while uploading image1");
        if (!in_array($file_extension, $allowed_image_extension)) return_exit($ext_err_text);

        if (!isset($fileinfo['mime'])) return_exit($ext_err_text);

        if (
            $fileinfo['mime'] == 'image/jpeg' ||
            $fileinfo['mime'] == 'image/png'
        ) {
        } else return_exit($ext_err_text);

        $filename = new_image_name($file_extension);
        $destfile = 'assets/images/' . $filename;
        compressImage($filepath, $destfile, 60);
				
				$db_col_name = $_POST["db_col_name"];
        $query = mysqli_query($conn,"UPDATE upload_webcam SET $db_col_name = '$filename' WHERE user_email = '$email'  ");
        if (!$query) return_exit("Error while uploading image2");
        $file_url = 'assets/images/' . $filename;
        $output = new stdClass;
        $output->url = $file_url;
        echo json_encode($output);
        break;

    case "fetch_profile":
        if (!isUserLogin()) return_exit("Login required");
        $email = loginEmail();

        $query = mysqli_query($conn, "SELECT * FROM upload_webcam WHERE user_email = '$email'  ");
        $row = mysqli_fetch_array($query);
        $avatar = $row[$_POST["db_col_name"]];
        $file_url = 'assets/images/'. $avatar;
        $output = new stdClass;
        $output->url = $file_url;
        echo json_encode($output);
        break;

    case "remove_profile":
        if (!isUserLogin()) return_exit("Login required");
        $email = loginEmail();
					
				$db_col_name = $_POST["db_col_name"];
        $query = mysqli_query($conn, "UPDATE upload_webcam SET $db_col_name = '' WHERE user_email = '$email'  ");
        if (!$query) return_exit("Error while removing image");
        $file_url = 'assets/images/avatar.png';
        $output = new stdClass;
        $output->url = $file_url;
        echo json_encode($output);
        break;

    default:
        break;
}

function compressImage($source, $destination, $quality)
{
    $info = getimagesize($source);
    if ($info['mime'] == 'image/jpeg') {
        $image = imagecreatefromjpeg($source);
        imagejpeg($image, $destination, $quality);
    } elseif ($info['mime'] == 'image/png') {
        $image = imagecreatefrompng($source);
        imagealphablending($image, false);
        imagesavealpha($image, true);
        imagepng($image, $destination, 9);
    } else {
        return_exit("Upload a valid image. Only PNG, JPG and JPEG are allowed.");
    }
}

function new_image_name($file_extension)
{
    global $current_date;
    $unique_id = rand(100, 9999999999);
    return $unique_id . $current_date . $unique_id . '.' . $file_extension;
}



function sanitize_text($string)
{
    global $conn;
    $string = mysqli_real_escape_string($conn, $string);
    $string = trim(addslashes(htmlentities(htmlspecialchars($string))));
    return $string;
}

function return_exit($text)
{
    echo $text;
    exit();
}

function is_empty()
{
    $strings = func_get_args();
    $output = false;
    foreach ($strings as $string) {
        if (is_array($string)) {
            if (empty($string)) {
                $output =  true;
            }
        } else {
            $string = sanitize_text($string);
            if (($string != '') && ($string != "undefined") && ($string != null) && (!empty($string))) {
            } else {
                $output =  true;
            }
        }
    }
    return $output;
}
