<?php 
session_start();

if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}

if(!empty($_SESSION['login_user'])){
    header("location: profile.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en-GB">
    <head>
        <title>Carpool</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/css.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <style>
            body {
                /* The image used */
                background-image: url("greenwich-background.jpg");

                /* Center and scale the image nicely */
                background-position: top;
                background-repeat: no-repeat;
                background-size: cover;
            }
            .panelBackground{
                background: rgba(255, 255, 255, .5);
            }
        </style>
    </head>
    <body class="bg">
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
                      <ul class="nav navbar-nav navbar-right">
                        <li>
                            <form class="navbar-form navbar-right" method="post" action="login.php">
                                <div class="form-group">
                                    <input name="signInUsername" type="text" class="form-control" placeholder="Username" Value="<?php if(isset($_COOKIE["UserName"])) {echo $_COOKIE["UserName"];} ?>"/>
                                    <input name="signInPassword" type="password" class="form-control" placeholder="Password"/>
                                        <?php 
                                            if(!empty($_SESSION['invalidLoginLabel'])) {
                                                echo $_SESSION['invalidLoginLabel'];
                                            } 
                                            unset($_SESSION['invalidLoginLabel']); 
                                        ?>
                                </div>
                                <button name="signInButton" type="submit" class="btn btn-default">Sign In</button>
                            </form>
                        </li> 
                      </ul>
                    </div>
                </div>
            </nav>
     <!--    Content-->   
        <div class="row container-fluid text-center">    
            <div class="col-xs-12  col-sm-12 col-md-7 col-lg-8">
                <h3 class="text-success text-center">Find your commute partner</h3>
                <div class="panel panel-default panelBackground">
                    <div class="panel-body text-center">
                        <form method="post" action="index.php">
                            <input class="searchBox" type="text" name="searchTxt" placeholder="Search..">
                            <button name="searchBtn" type="submit" class="btn searchBtn">Search</button>
                        </form>
                    </div>
                </div>
                <div class="col-xs-12  col-sm-12 col-md-12 col-lg-12">
                    <?php 
                     include "Connection.php";
                        $sql = "SELECT * FROM mdb_fi2157j.Commutes where Concat(StartingPoint, EndPoint, StartingTime, EndTime, CommuteRepetion) like '%abc1234%'";
                        if(isset($_POST['searchBtn'])){
                            unset($_SESSION['searchStringIndex']);
                            if(!empty($_POST['searchTxt'])){
                            $_SESSION['searchStringIndex'] = $_POST['searchTxt'];
                            }
                        }
                        if(isset($_SESSION['searchStringIndex'])){
                                $searchStringIndex = $_SESSION['searchStringIndex'];
                                $searchStringIndex = htmlentities($searchStringIndex);
                                $searchStringIndex = mysqli_real_escape_string($connection, $searchStringIndex);
                            
                            $searchStringIndex = preg_replace("/[^\d\sA-Za-z:]/", "", $searchStringIndex);
                            
                            $pieces = explode(" ", $searchStringIndex);
                            for ($x = 0; $x < count($pieces); $x++) {
                                if($x==0){
                                    $sql = "SELECT * FROM mdb_fi2157j.Commutes where Concat(StartingPoint, EndPoint, StartingTime, EndTime, CommuteRepetion) like '%".$pieces[$x]."%'";
                                }else{
                                    $sql .= " UNION SELECT * FROM mdb_fi2157j.Commutes where Concat(StartingPoint, EndPoint, StartingTime, EndTime, CommuteRepetion) like '%".$pieces[$x]."%'";

                                }
                            }   
                        }
                        $results = $connection->query($sql);
                        $number_of_results = mysqli_num_rows($results);
                        $results_per_page = 2;
                        $number_of_pages = ceil($number_of_results/$results_per_page);

                        if (!isset($_GET['page'])) {
                            $_GET['page'] = 1;
                            $page = 1;
                        } else {
                            $page = $_GET['page'];
                        }
                        $this_page_first_result = ($page-1)*$results_per_page;
                        $sql .= " LIMIT ". $this_page_first_result .",".$results_per_page;
                        $results = mysqli_query($connection, $sql);

                        while ($row = mysqli_fetch_array($results)) { 
                    ?>
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="panel panel-default panelBackground">
            <div class="panel-heading">
                <h3 class="panel-title text-center">Sign up to start you commute!</h3>
            </div>
            <div class="panel-body">
                <div class="row well well-sm">
                    <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 text-center">
                        <label><strong>Starting Point</strong></label>
                        <p><?php echo $row['StartingPoint'] ?></p>
                    </div>
                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2"></div>
                    <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 text-center">
                        <label><strong>End Point</strong></label>
                        <p><?php echo $row['EndPoint'] ?></p>
                    </div>
                </div>
                <div class="row well well-sm">
                    <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 text-center">
                        <label><strong>Starting Time</strong></label>
                        <p><?php echo $row['StartingTime'] ?></p>
                    </div>
                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2"></div>
                    <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 text-center">
                        <label><strong>End Time</strong></label>
                        <p><?php echo $row['EndTime'] ?></p>
                    </div>
                </div>
                <div class="row well well-sm text-center">
                    <label>Commute  Days</label>
                    <p><?php echo $row['CommuteRepetion'] ?></p>
                </div>
              </div>
            </div>
        </div>
        <?php
            }
        ?>
                </div>
                <div class="row text-center">
                    <ul class="pagination">
                        <?php
                            for ($page=1;$page<=$number_of_pages;$page++) {
                                if($_GET['page']==$page){
                                    ?>
                                    <li class="active"><a href="index.php?page=<?php echo $page; ?>"><?php echo $page; ?></a></li>
                        <?php        
                                }else{
                        ?>
                                    <li><a href="index.php?page=<?php echo $page; ?>"><?php echo $page; ?></a></li>
                        <?php
                                }
                            } 
                        ?>            
                    </ul>
            </div>
            </div>
                <!--Registration section-->
                <div class="col-xs-12  col-sm-12 col-md-5 col-lg-4">
                    <div class="panel panel-default panelBackground">
                        <div class="panel-body text-center">
                        <h1>Register</h1>
                        <form method="post" action="Register.php">
                            <div class="form-group row content">
                                <div class="col-sm-3">
                                    <label for="regUsername">Username:</label>
                                </div>
                                <div class="col-sm-6">
                                    <input name="regUsername" id="regUsername" type="text" class="form-control" value="<?php 
                                    if(isset($_SESSION['userNameValidated'])){echo $_SESSION['userNameValidated'];}
                                      unset($_SESSION['userNameValidated']); ?>" placeholder="Username" required/>
                                </div>
                                <div class="col-sm-3">
                                    <?php 
                                            if(!empty($_SESSION['usernameLabel'])) {
                                                echo $_SESSION['usernameLabel'];} 
                                            unset($_SESSION['usernameLabel']); 
                                        ?>
                                </div>
                            </div>
                            <div class="form-group row content">
                                <div class="col-sm-3">
                                    <label>Email:</label>
                                </div>
                                <div class="col-sm-6">
                                    <input name="regEmail" type="email" class="form-control" value="<?php 
                                    if(isset($_SESSION['emailValidated'])){echo $_SESSION['emailValidated'];}
                                      unset($_SESSION['emailValidated']); ?>" placeholder="Email Address"/>
                                </div>
                                <div class="col-sm-3">
                                    <?php 
                                            if(!empty($_SESSION['emailLabel'])) {echo $_SESSION['emailLabel'];} 
                                            unset($_SESSION['emailLabel']); 
                                        ?>
                                </div>
                            </div>
                            <div class="form-group row content">
                                <div class="col-sm-3">
                                    <label>Password:</label>
                                </div>
                                <div class="col-sm-6">
                                    <input name="regPassword" type="password" class="form-control" placeholder="Password"/>
                                </div>
                                <div class="col-sm-3">
                                    <?php 
                                            if(!empty($_SESSION['passwordLabel'])) {
                                                echo $_SESSION['passwordLabel']; 
                                            } 
                                            unset($_SESSION['passwordLabel']); 
                                        ?>
                                </div>
                            </div>
                            <div class="form-group row content">
                                <div class="col-sm-3">
                                    <label>Confirm Password:</label>
                                </div>
                                <div class="col-sm-6">
                                    <input name="regConfirmPassword" type="password" class="form-control" placeholder="Confirm Password"/>
                                </div>
                                <div class="col-sm-3">
                                        <?php 
                                            if(!empty($_SESSION['confirmPasswordLabel'])) {
                                            echo $_SESSION['confirmPasswordLabel']; 
                                            } 
                                            unset($_SESSION['confirmPasswordLabel']); 
                                        ?>
                                </div>
                            </div>  
                            <div class="form-group row content" >
                                <div class="col-sm-3">
                                    <br/>
                                    <br/>
                                    <label>Captcha:</label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="well well-sm"><img src="Captcha.php" alt="Captcha"/></div>
                                    <input type="text" class="form-control" placeholder="Enter Captcha" name="boxCaptcha"/>
                                </div>
                                <div class="col-sm-3">
                                    <br/>
                                    <br/>
                                    <?php 
                                            if(!empty($_SESSION['CaptchaLabel'])) {
                                            echo $_SESSION['CaptchaLabel']; 
                                            } 
                                            unset($_SESSION['CaptchaLabel']); 
                                        ?>
                                </div>
                            </div>
                            <div class="form-group">                        
                                <button name="registerButton" type="submit" class="btn btn-default">Register</button> 
                            </div>
                    </form>
                </div></div>
                        </div>
            </div>
    <!--   Footer-->
            <footer class="container-fluid text-center">
                <kbd>&copy; Royal Borough of Greenwich Carpool</kbd>
                <div class="alert">
                  <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                  <strong>Welcome to Greenwich Carpool. By using this site you agree to our cookies.</strong>
                </div>
            </footer>
        </div>  
    </body>
</html>