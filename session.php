<?php

session_start();

if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}

if(empty($_SESSION['login_user'])){
    header("location: index.php");
    exit;
}
include "Connection.php";
$userCheck=$_SESSION['login_user'];
unset($_SESSION['login_user']); 
$sql = "SELECT * FROM mdb_fi2157j.Users where Username= '$userCheck'";
$result = $connection->query($sql);
$count=mysqli_num_rows($result);


if($count==1){ 
    $row = mysqli_fetch_array($result);
    $_SESSION['login_user']=$row['Username'];
    $_SESSION['login_email']=$row['Email'];
}else{
    header("location: index.php");
}
?>