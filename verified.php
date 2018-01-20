<?php
include "session.php";
if ($row['Verification'] == "0"){
        header("location:verification.php");
    }else{

$usern = $_SESSION['login_user'];
unset($_SESSION['Verification_Code']);
header('refresh: 2; url=http://stuweb.cms.gre.ac.uk/~fi2157j/homepage.php');  
}
?>
<!DOCTYPE html>
<html lang="en">
    
<head>
  <title>Carpool</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <script src="js/bootstrap.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
</head>
    
<body>
<!--    Header-->
       <nav class="navbar navbar-default">
            <div class="container-fluid">
                <form>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <a class="navbar-brand" href="http://stuweb.cms.gre.ac.uk/~fi2157j/homepage.php">Royal Borough of Greenwich Carpool</a>
                        </div>
                    </div>
                </form>
                <div class="col-sm-4"></div>
                <div class="col-sm-4">
                    <form  class="navbar-form navbar-right" method="post" action="logout.php">
                        <button name="signOutButton" type="submit" class="btn btn-default">Sign out</button>                    
                    </form>    
                    <form class="navbar-form navbar-right" action="http://stuweb.cms.gre.ac.uk/~fi2157j/profile.php">
                        <button class="btn btn-default" type="submit"><?php echo $_SESSION['login_user']; ?></button>
                    </form>                        
                </div>
            </div>
        </nav>

    <div class="container-fluid text-center">    
        <div class="row content">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
                <b>Account Verified</b>
            </div>
        </div>
    </div> 
    
<!--   Footer-->
    <footer class="container-fluid text-center">
        <p>&copy; Royal Borough of Greenwich Carpool</p>
    </footer>

</body>
</html>
