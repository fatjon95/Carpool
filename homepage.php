<?php 
include "session.php";
if ($row['Verification'] == "0"){
        header("location:verification.php");
    }
unset($_SESSION['searchStringIndex']);
            if(isset($_POST['searchBtn'])){
                unset($_SESSION['searchString']);
                unset($_COOKIE['searchString']);
                if(!empty($_POST['searchTxt'])){
                    $_SESSION['searchString'] = $_POST['searchTxt'];
                    setcookie("searchString", $_SESSION['searchString'], time() + (86400 * 30), "/");
                    header("location: homepage.php");
                }
            }
?>
<!DOCTYPE html>
<html lang="en-GB">
<head>
  <title>Home-Carpool</title>
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
    background-size: cover;
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
                    <li class="active"><a href="http://stuweb.cms.gre.ac.uk/~fi2157j/homepage.php"> <span class="glyphicon glyphicon-search"></span> Search Commutes</a></li>
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

    <div class="row content">
        <div class="col-xs-12  col-sm-12 col-md-1 col-lg-1"></div>
        <div class="col-xs-12  col-sm-12 col-md-10 col-lg-10">
            <div class="panel panel-default panelBackground">
                <div class="panel-body text-center">
                    <form method="post" action="homepage.php">
                        <input class="searchBox" type="text" name="searchTxt" placeholder="Search.." Value="<?php if(isset($_COOKIE["searchString"])) {echo $_COOKIE["searchString"];} ?>">
                        <button name="searchBtn" type="submit" class="btn searchBtn">Search</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xs-12  col-sm-12 col-md-1 col-lg-1"></div>
    </div>
    <div class="row content">
        <div class="col-xs-12  col-sm-12 col-md-12 col-lg-12">
           
        <?php 
            
            $sql = "SELECT * FROM mdb_fi2157j.Commutes ORDER BY TimeStamp DESC";
                        
            if(isset($_SESSION['searchString'])){
                $searchString = $_SESSION['searchString'];
                $searchString = htmlentities($searchString);
                $searchString = mysqli_real_escape_string($connection, $searchString);
                $searchString = preg_replace("/[^\d\sA-Za-z:]/", "", $searchString);
                
                $pieces = explode(" ", $searchString);
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
            $results_per_page = 3;
            $number_of_pages = ceil($number_of_results/$results_per_page);
        
            if (!isset($_GET['page'])) {
                $_GET['page'] = 1;
              $page = 1;
            } else {
              $page = $_GET['page'];
            }
            $this_page_first_result = ($page-1)*$results_per_page;
            $sql .= " LIMIT ". $this_page_first_result .",".$results_per_page;
            echo "<br/>";
            $results = mysqli_query($connection, $sql);
        
            while ($row = mysqli_fetch_array($results)) { 
                if($row['ProvideLift']=='0'){
                    $lift = "I need a lift";
                }else{
                    $lift = "I will provide lift";
                }
        ?>
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 ">
        <div class="panel panel-default panelBackground">
            <div class="panel-heading">
                <h3 class="panel-title text-center"><?php echo $row['Username'] ?> </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                      <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
                      <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                          <?php
                          if($row['imagePath'] == null){
                          }else{
                          ?>
                                          <div id="myCarousel<?php echo $row['idCommute'] ?>" class="carousel slide" data-ride="carousel">
                                            <!-- Indicators -->
                                            <ol class="carousel-indicators">
                                                <?php 
                                                    $imagesArray = explode(", ", $row['imagePath']);
                                                    $i = '0';
                                                    foreach ($imagesArray as &$image) {
                                                        if($i==0){
                                                            echo '<li data-target="#myCarousel'.$row['idCommute'].'" data-slide-to="'.$i.'" class="active"></li>';
                                                        }else{
                                                            echo '<li data-target="#myCarousel'.$row['idCommute'].'" data-slide-to="1"></li>';
                                                        }
                                                        $i++;
                                                    }
                                              ?>
                                            </ol>
                                            <!-- Wrapper for slides -->
                                            <div class="carousel-inner">
                                                <?php 
                                                    $imagesArray = explode(", ", $row['imagePath']);
                                                    $i = '0';
                                                    foreach ($imagesArray as &$image) {
                                                            if($i==0){
                                                            echo '<div class="item active">
                                                            <img src="'.$image.'" alt="'.$i.'" style="width:100%;">
                                                            </div>';
                                                            }else{
                                                                echo '<div class="item">
                                                                <img src="'.$image.'" alt="'.$i.'" style="width:100%;">
                                                                </div>';
                                                            }
                                                            $i++;
                                                    }
                                                ?>
                                            </div>
                                              
                                              <?php 
                                               
                                                $imagesArray = explode(", ", $row['imagePath']);
                                                if(count($imagesArray) > 1 ){
                                                   ?>
                                                <!-- Left and right controls -->
                                                <a class="left carousel-control" href="#myCarousel<?php echo $row['idCommute'] ?>" data-slide="prev">
                                                  <span class="glyphicon glyphicon-chevron-left"></span>
                                                  <span class="sr-only">Previous</span>
                                                </a>
                                                <a class="right carousel-control" href="#myCarousel<?php echo $row['idCommute'] ?>" data-slide="next">
                                                  <span class="glyphicon glyphicon-chevron-right"></span>
                                                  <span class="sr-only">Next</span>
                                                </a>
                                                   <?php  
                                                }
                                              ?>
                                          </div>
                          <?php } ?>
                          
                          <br/>
                    </div>
                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
                </div>
                <div class="row well well-sm">
                    <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 text-center">
                        <label><strong>Starting Point</strong></label><br/>
                        <?php echo $row['StartingPoint'] ?>
                    </div>
                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2"></div>
                    <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 text-center">
                        <label><strong>End Point</strong></label><br/>
                        <?php echo $row['EndPoint'] ?>
                    </div>
                </div>
                <div class="row well well-sm">
                    <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 text-center">
                        <label><strong>Starting Time</strong></label><br/>
                        <?php echo $row['StartingTime'] ?>
                    </div>
                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2"></div>
                    <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 text-center">
                        <label><strong>End Time</strong></label><br/>
                        <?php echo $row['EndTime'] ?>
                    </div>
                </div>
                <div class="row well well-sm text-center">
                    <label>Commute  Days</label><br/>
                    <?php echo $row['CommuteRepetion'] ?>
                </div>
                <div class="row">
                    <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 well well-sm text-center">
                        <label><strong>Journey Cost</strong></label><br/>
                        £<?php echo $row['JourneyCost'] ?>
                    </div>
                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2"></div>
                    <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 well well-sm text-center">
                        <label><strong>Car Brand</strong></label><br/>
                        <?php echo $row['CarBrand'] ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 well well-sm text-center">
                        <label><strong>Driving License Points</strong></label><br/>
                        <?php echo $row['DrivingLicensePoints'] ?>
                    </div>
                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2"></div>
                    <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 well well-sm text-center">
                        <label><strong>Insurance</strong></label><br/>
                        <?php echo $row['Insurance'] ?>
                    </div>
                </div>
                <div class="row well well-sm text-center">
                        <label><?php echo $lift ?></label>
                </div>
              </div>
             <div class="panel-footer">
                 <div class="row">
                     <div class="text-center">
                        <?php echo "Uploaded on: ";
                         echo date('H:i d-M-Y', $row['TimeStamp']);?>
                    </div>
                 </div>
             </div>
            </div>
        </div>
        <?php
            }
        ?>
        
        </div>
    </div>
        <div class="row text-center">
        <ul class="pagination">
            <?php
                for ($page=1;$page<=$number_of_pages;$page++) {
                    if($_GET['page']==$page){
                        ?>
                        <li class="active"><a href="homepage.php?page=<?php echo $page; ?>"><?php echo $page; ?></a></li>
            <?php        
                    }else{
            ?>
                        <li><a href="homepage.php?page=<?php echo $page; ?>"><?php echo $page; ?></a></li>
            <?php
                    }
                } 
            ?>            
        </ul>
    </div>
    
<!--   Footer-->
    <footer class="container-fluid text-center">
        <kbd>&copy; Royal Borough of Greenwich Carpool</kbd>
    </footer>
  </div>
    </body>
</html>