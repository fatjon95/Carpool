<?php
session_start(); // Starting Session

if (isset($_POST['signInButton'])) {
        
if (empty($_POST['signInUsername']) || empty($_POST['signInPassword'])) {
$_SESSION['invalidLoginLabel'] ='<span class="label label-danger center-block">Username or Password is invalid!</span>';
    header("location:index.php");
}
else
{
    // Establishing Connection with Server by passing server_name, user_id and password 
    include "Connection.php";
    
    // Define $username and $password
    $UserName=$_POST['signInUsername'];
    $UserName = htmlentities($UserName);
    $UserName = mysqli_real_escape_string($connection, $UserName);
    
    $PassWord = $_POST['signInPassword'];
    $PassWord = htmlentities($PassWord);
    $PassWord = mysqli_real_escape_string($connection, $PassWord);
    $PassWord = md5($PassWord);

    $sql = "select * from mdb_fi2157j.Users where BINARY Password='$PassWord' AND BINARY Username='$UserName'";

    $results = $connection->query($sql);
    $rows=mysqli_num_rows($results);

    if ($rows == 1) {
    $row = mysqli_fetch_array($results);
    $_SESSION['login_user']=$row['Username']; // Initializing Session
    header("location: homepage.php"); // Redirecting To Other Page
    setcookie("UserName", $_SESSION['login_user'], time() + (86400 * 30), "/");
} else {
    $_SESSION['invalidLoginLabel'] ='<span class="label label-danger center-block">Username or Password is invalid!</span>';
    header("location:index.php");
}
/* close connection */
$connection->close();
    
}
}
?>