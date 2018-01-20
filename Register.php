<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include "Connection.php";
function generateRandomString($length = 5) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function sendVerificationEmail($Username, $email){
    $_SESSION['Verification_Code']= generateRandomString();
    $subject = 'Activation code';
    $message = "
        <html>
        <h2 style=\"text-align: center;\">Royal Borough of Greenwich Carpool</h2></html>
        <p style=\"text-align: center;\">Hello, ".$Username."<br />Verification from&nbsp;Royal Borough of Greenwich Carpool</p>
        <p style=\"text-align: center;\">Your activation code is: <strong>".$_SESSION['Verification_Code']."</strong></p>
        <p style=\"text-align: center;\">&nbsp;</p>
        <p style=\"text-align: center;\">Sincerly,<br />Royal Borough of Greenwich Carpool</p>
        <p style=\"text-align: center;\">This is an automated response, please DO NOT reply.</p>
        <p style=\"text-align: center;\">&nbsp;</p>
        </html>
        ";
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= "From: noreply@gre.ac.uk\r\n";

    mail( $email, $subject, $message,$headers);
}

if (isset($_POST['registerButton'])) {
    
    if(empty($_POST['regUsername'])){
        $_SESSION['usernameLabel'] = '<span class="label label-danger">Enter Username!</span>';
        header("location:index.php");
        exit;
    }else{
        $userCheck = $_POST['regUsername'];
        $userCheck = htmlentities($userCheck);
        $userCheck = mysqli_real_escape_string($connection, $userCheck);
        $sql = "SELECT * FROM mdb_fi2157j.Users where Username= '$userCheck'";
        $result = $connection->query($sql);
        $count=mysqli_num_rows($result);
        if($count==1){
           $_SESSION['usernameLabel'] = '<span class="label label-danger">Username exist!</span>';
            header("location:index.php");
            exit; 
        }else{
            $Username = $_POST['regUsername'];
            $Username = htmlentities($Username);
            $Username = mysqli_real_escape_string($connection, $Username);
            $_SESSION['userNameValidated'] = $Username;
        } 
    }
    if(empty($_POST['regEmail'])){ 
        $_SESSION['emailLabel'] = 
            '<span class="label label-danger">Enter Email!</span>';
        header("location:index.php");
        exit;
    }else{
        $email = $_POST['regEmail'];
        $email = htmlentities($email);
        $email = mysqli_real_escape_string($connection, $email);
        $_SESSION['emailValidated'] = $email;
    }
    if(empty($_POST['regPassword'])){
        $_SESSION['passwordLabel'] = '<span class="label label-danger">Enter Password!</span>';
        header("location:index.php");
        exit;
    }else{
        $password = $_POST['regPassword']; 
        $password_encrypted = md5($password);
        $password_encrypted = htmlentities($password_encrypted);
        $password_encrypted = mysqli_real_escape_string($connection, $password_encrypted);
    }
    if(empty($_POST['regConfirmPassword'])){
        $_SESSION['confirmPasswordLabel'] = '<span class="label label-danger">Confirm Password!</span>';
        header("location:index.php");
        exit;
    }else{
        $repasswords = $_POST['regConfirmPassword'];
        $repasswords_encrypted = md5($repasswords);
        $repasswords_encrypted = htmlentities($repasswords_encrypted);
        $repasswords_encrypted = mysqli_real_escape_string($connection, $repasswords_encrypted);
    }
    if(empty($_POST['boxCaptcha'])){
        $_SESSION['CaptchaLabel'] = '<span class="label label-danger">Confirm Captcha!</span>';
        header("location:index.php");
        exit;
    }else{
        $captcha = $_POST['boxCaptcha'];
    }

    if ($password_encrypted != $repasswords_encrypted){
        $_SESSION['confirmPasswordLabel'] = '<span class="label label-danger">Password does not match!</span>';
        header("location:index.php");
        exit;
        }else{

            if($_SESSION['custom_captcha'] != $captcha){
                $_SESSION['CaptchaLabel'] = 
                '<span class="label label-danger">Wrong Captcha!</span>';
                header("location:index.php");
                exit;
            }else{
                $sql = "INSERT into mdb_fi2157j.Users( Username, Email, Password) VALUES ('$Username','$email','$password_encrypted')";
                if($connection->query($sql) === true){
                    $_SESSION['login_user']= $Username;
                    header("location: verification.php");
                    sendVerificationEmail($Username, $email);
                }else{
                     echo "<br> Record not inserted $sql.".$mysqli->error;
                }
                $connection->close();
            }        
        }
}
?>