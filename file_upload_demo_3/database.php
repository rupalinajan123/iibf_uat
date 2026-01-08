<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

$host = "10.11.38.120";
$userName = "supp0rttest_iibf_staging";
$password = "o2pc+oHa3iwx";
$dbName = "supp0rttest_iibf_staging";
$conn =  mysqli_connect($host, $userName, $password, $dbName);
if (!$conn) die(mysqli_connect_error($conn));

function isUserLogin()
{
    global $conn;
    if (!isset($_COOKIE["iuemail"])) return false;
    $email = $_COOKIE["iuemail"];
    $query = mysqli_query($conn, "SELECT * FROM upload_webcam WHERE user_email = '$email' ");
    return mysqli_num_rows($query) ? true : false;
}

function loginEmail()
{
    $email = $_COOKIE["iuemail"];
    return $email;
}