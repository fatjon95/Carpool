<?php
include "session.php";
if ($row['Verification'] == "1"){
        header("location:verified.php");
        exit;
    }

if(empty($_SESSION['Verification_Code'])){
    include "Register.php";
    sendVerificationEmail($_SESSION['login_user'], $row['Email']);
}

if (isset($_POST['validateButton'])){    
    if($_SESSION['Verification_Code'] == $_POST['enteredCode']){
        $usern=$_SESSION['login_user'];
        $sql = "UPDATE mdb_fi2157j.Users SET Verification = '1' WHERE Username='$usern'";
        if($connection->query($sql) === true){
            header("location:verified.php");
        }else{
             echo "<br> Record not inserted $sql.".$mysqli->error;
        } 
    }else{
        $_SESSION['wrongCodeLabel'] = '<label class="redText">Wrong Code!</label>';
    }
}
/* close connection */
$connection->close();
?>
<!DOCTYPE html>
<html lang="en">
    
<head>
    <title>Carpool</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
<link rel="stylesheet" href="css/css.css">
    <style>
        
        body {
            /* The image used */
            background-image: url("greenwich-background.jpg");

            /* Center and scale the image nicely */
            background-position: top;
            background-repeat: no-repeat;
            background-size: auto;
        }
        .panelBackground{
            background: rgba(255, 255, 255, .5);
        }
    </style>
</head>
    
<body>
    <div class="container-fluid">
<!--    Nav bar-->
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>                        
              </button>
              <a class="navbar-brand" href="http://stuweb.cms.gre.ac.uk/~fi2157j/homepage.php" style="color:Black;">Royal Borough of Greenwich Carpool</a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                    <li><a href="http://stuweb.cms.gre.ac.uk/~fi2157j/homepage.php"> <span class="glyphicon glyphicon-search"></span> Search Commutes</a></li>
                    <li><a href="http://stuweb.cms.gre.ac.uk/~fi2157j/profile.php"> <span class="glyphicon glyphicon-plus"></span> Create Commute</a></li>
                    <li><a href="http://stuweb.cms.gre.ac.uk/~fi2157j/view-commutes.php"> <span class="glyphicon glyphicon-list-alt"></span> View My Commutes</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#"> <span class="glyphicon glyphicon-user"></span> <?php if(isset($_SESSION['login_user'])){ echo $_SESSION['login_user'];} ?></a></li>
                    <li>
                        <form  class="navbar-form" method="post" action="logout.php">
                            <button name="signOutButton" type="submit" class="btn btn-default">
                                <span class="glyphicon glyphicon-log-out"></span> 
                                Sign out
                            </button>                    
                        </form>
                    </li> 
                </ul>
            </div>
        </div>
    </nav>
       
<!--    body-->
    <div class="container-fluid text-center">    
        <div class="row content">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
                <br/>
                <br/>
                <div class="panel panel-default panelBackground">
                    <div class="panel-body text-center">
                        <form method="post" action="verification.php">
                            <div class="input-group">
                                <input type="text" class="form-control" name="enteredCode" placeholder="Enter Code">
                                <span class="input-group-btn">                         
                                    <button class="btn btn-default" type="submit" name="validateButton">Validate</button>                          
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row content">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
                <br/>
                <br/>
                <?php 
                    if(!empty($_SESSION['wrongCodeLabel'])) {
                        echo $_SESSION['wrongCodeLabel'];
                        unset($_SESSION['wrongCodeLabel']);
                    }
                ?>
            </div>
        </div>
    </div> 
    
<!--   Footer-->
    <footer class="container-fluid text-center">
        <kbd>&copy; Royal Borough of Greenwich Carpool</kbd>
    </footer>
    </div>
</body>
</html>