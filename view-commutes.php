<?php 
include "session.php";
if ($row['Verification'] == "0"){
        header("location:verification.php");
    }
unset($_SESSION['searchString']);


if(isset($_POST['editPost'])){
    $_SESSION['commuteToChange'] = $_POST['IDj'];
    echo $_SESSION['commuteToChange'];
}

if(isset($_POST['IDj'])){
    $commuteToUpdate = $_SESSION['commuteToChange'];    
    $UserName = $_SESSION['login_user'];
    $sql = "SELECT * FROM mdb_fi2157j.Commutes Where Username= '$UserName' AND idCommute = $commuteToUpdate";
    $results = $connection->query($sql);
    $row = mysqli_fetch_array($results);

    if($row['imagePath']==null){
        $imagesArray=[];
    }else{
        $imagesArray = explode(", ", $row['imagePath']);
    }
}

if(isset($_POST['deletePost'])){
    foreach ($imagesArray as &$value) {
       unlink($value);
    }
    $commuteToDelete = $_POST['IDj'];
    $sql = "DELETE FROM mdb_fi2157j.Commutes WHERE idCommute = $commuteToDelete";
    if($connection->query($sql) === true){
            header("location: view-commutes.php");
        }else{
             echo "<br> Record not deleted $sql.".$connection->error;
        }
        $connection->close();
    unset($_POST['IDj']);
}
?>
<!DOCTYPE html>
<html lang="en-GB">
    <head>
      <title>View-Carpool</title>
 <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/site.js"></script>
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
      <a class="navbar-brand" href="http://stuweb.cms.gre.ac.uk/~fi2157j/homepage.php">Royal Borough of Greenwich Carpool</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li><a href="http://stuweb.cms.gre.ac.uk/~fi2157j/homepage.php"> <span class="glyphicon glyphicon-search"></span> Search Commutes</a></li>
        <li><a href="http://stuweb.cms.gre.ac.uk/~fi2157j/profile.php"> <span class="glyphicon glyphicon-plus"></span> Create Commute</a></li>
        <li class="active"><a href="http://stuweb.cms.gre.ac.uk/~fi2157j/view-commutes.php"> <span class="glyphicon glyphicon-list-alt"></span> View My Commutes</a></li>
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

<!--  Content  -->
    <div class="row content">  
        <?php       
            $UserName = $_SESSION['login_user'];
            $sql = "SELECT * FROM mdb_fi2157j.Commutes Where Username= '$UserName'";
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
                
        
            if(isset($_SESSION['commuteToChange'])){
                
                $commuteToUpdate = $_SESSION['commuteToChange'];
                
                $sql="SELECT * FROM mdb_fi2157j.Commutes Where Username= '".$UserName."' AND idCommute = '".$commuteToUpdate."' ORDER BY TimeStamp DESC LIMIT ". $this_page_first_result .",".$results_per_page;
                $results = mysqli_query($connection, $sql);
                $row = mysqli_fetch_array($results);
                if($row['ProvideLift']=='0'){
                    $lift = "I need a lift";
                }else{
                    $lift = "I will provide lift";
                }
        ?>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" >
            <div class="panel panel-default" >
                <!-- Default panel contents -->
                <div class="panel-heading">
                    <h3 class="panel-title text-center"><strong>Edit Commute <?php echo $row['idCommute'] ?> </strong></h3>
                </div>
                <div class="panel-body">
                      <!-- List group -->
                      <ul class="list-group">
                          <li class="list-group-item">
                            <div class="row">
                                    <?php 
                                        if($row['imagePath'] == null){
                                        }else{
                                            $imagesArray = explode(", ", $row['imagePath']);
                                            foreach ($imagesArray as &$image) {
                                               echo '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="border: solid #D3D3D3;">
                                                        <form enctype="multipart/form-data" method="post" action="updateImage.php">
                                                          <img src="'.$image.'" alt="'.$image.'" style="width:100%;">
                                                            <br/>
                                                            <div class="row">
                                                                <button type="submit" class="btn btn-default btn-sm" name="deletePhoto">
                                                                    <span class="glyphicon glyphicon-remove"></span> 
                                                                </button>
                                                                <input type="hidden" name="imageURL" value="'.$image.'">
                                                                <label class="btn btn-default btn-sm pull-right">
                                                                    <input type="file" name="updatePhoto" onchange="this.form.submit()" style="display:none"/>
                                                                    <span class="glyphicon glyphicon-upload"></span>
                                                                </label>
                                                            </div>
                                                        </form>
                                                    </div>';
                                            }
                                        }
                                        echo '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="border: solid #D3D3D3;">
                                            <form enctype="multipart/form-data" method="post" action="updateImage.php">
                                              <img src="images/NotAvailable.png" alt="NotAvailable.png" style="width:100%;">
                                                <br/>
                                                <div class="row">
                                                    <input type="hidden" name="imageURL" value="new">
                                                    <label class="btn btn-default btn-sm pull-right">
                                                        <input type="file" name="updatePhoto" onchange="this.form.submit()" style="display:none"/>
                                                        <span class="glyphicon glyphicon-upload"></span>
                                                    </label>
                                                </div>
                                            </form>
                                        </div>';
                                    ?>
                            </div>
                          </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div id="map" style="width:100%;height:500px;"></div>
                                <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDCAqnL4zmzFq7-s5NQvQLgECqAUYKBscc&callback=myMap"></script>
                            </div>                  
                        </li>
                          
                        <form method="post" action="updatecommute.php">
                          <li class="list-group-item">
                              <div class="row">
                                  <div class="col-sm-6">
                                  <label>Starting Point</label>
                                      <input id="startingPointText" name="startingPointText" type="text" class="form-control" placeholder="Starting Point" value="<?php echo $row['StartingPoint']; ?>"/>
                                          <?php 
                                            if(!empty($_SESSION['ustartingPointLabel'])) {
                                            echo $_SESSION['ustartingPointLabel']; 
                                            } 
                                            unset($_SESSION['ustartingPointLabel']); 
                                        ?>
                                        <input type="hidden" size="20" maxlength="30" id="startlat" name="startlat" value="<?php echo $row['startLatitude']; ?>"/>
                                        <input type="hidden" size="20" maxlength="30" id="startlng" name="startlng" value="<?php echo $row['startLongitude']; ?>"/>
                                  </div>
                                  <div class="col-sm-6">
                                      <label>End Point</label>
                                      <input id="endPointText" name="endPointText" type="text" class="form-control" placeholder="End Point" value="<?php echo $row['EndPoint'] ?>"/>  
                                        <?php 
                                            if(!empty($_SESSION['uendPointLabel'])) {
                                            echo $_SESSION['uendPointLabel']; 
                                            } 
                                            unset($_SESSION['uendPointLabel']); 
                                        ?>
                                        <input type="hidden" size="20" maxlength="30" id="endlat" name="endlat" value="<?php echo $row['endLatitude']; ?>"/>
                                        <input type="hidden" size="20" maxlength="30" id="endlng" name="endlng" value="<?php echo $row['endLongitude']; ?>"/>
                                  </div>
                              </div>
                          </li>
                        <li class="list-group-item">
                            <div class="row">
                              <div class="col-sm-6">
                              <label>Starting Time</label>
                                  <input name="startingTimeText" type="time" class="form-control" value="<?php echo $row['StartingTime'] ?>"/>
                                    <?php 
                                            if(!empty($_SESSION['ustartingTimeLabel'])) {
                                            echo $_SESSION['ustartingTimeLabel']; 
                                            } 
                                            unset($_SESSION['ustartingTimeLabel']); 
                                        ?>
                              </div>
                              <div class="col-sm-6">
                                  <label>End Time</label>
                                  <input name="endTimeText" type="time" class="form-control" value="<?php echo $row['EndTime'] ?>"/>
                                    <?php 
                                            if(!empty($_SESSION['uendTimeLabel'])) {
                                            echo $_SESSION['uendTimeLabel']; 
                                            } 
                                            unset($_SESSION['uendTimeLabel']); 
                                        ?>
                            </div>
                          </div>
                        </li>
                        <li class="list-group-item">
                            <label>Commute Days</label>
                            <div class="row text-center well well-sm">

                                <input type="checkbox" name="Days[]" value="Monday" <?php if (strpos($row['CommuteRepetion'], 'Monday') !== false) { echo "checked";} ?> >   Monday
                                <input type="checkbox" name="Days[]" value="Tuesday" <?php if (strpos($row['CommuteRepetion'], 'Tuesday') !== false) { echo "checked";} ?>>  Tuesday
                                <input type="checkbox" name="Days[]" value="Wednesday" <?php if (strpos($row['CommuteRepetion'], 'Wednesday') !== false) { echo "checked";} ?>>Wednesday
                                <input type="checkbox" name="Days[]" value="Thursday" <?php if (strpos($row['CommuteRepetion'], 'Thursday') !== false) { echo "checked";} ?> > Thursday
                                <input type="checkbox" name="Days[]" value="Friday" <?php if (strpos($row['CommuteRepetion'], 'Friday') !== false) { echo "checked";} ?>>   Friday
                                <input type="checkbox" name="Days[]" value="Saturday" <?php if (strpos($row['CommuteRepetion'], 'Saturday') !== false) { echo "checked";} ?> > Saturday
                                <input type="checkbox" name="Days[]" value="Sunday" <?php if (strpos($row['CommuteRepetion'], 'Sunday') !== false) { echo "checked";} ?> >   Sunday
                                    <?php 
                                            if(!empty($_SESSION['udaysLabel'])) {
                                            echo $_SESSION['udaysLabel']; 
                                            } 
                                            unset($_SESSION['udaysLabel']); 
                                        ?>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label>Journey Cost</label>
                                </div>
                                <div class="col-sm-8">
                                    <input name="journeyCostText" type="number" min="0.00" max="10000.00" step="0.01" class="form-control" placeholder="Journey Cost (£)" value="<?php echo $row['JourneyCost'] ?>"/>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label>Car Brand</label>
                                </div>
                                <div class="col-sm-8">
                                    <input name="carBrandText" type="text" class="form-control" placeholder="Car Brand"  value="<?php echo $row['CarBrand'] ?>"/>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Driving License Points</label>
                                    <select name="licensePoints" class="btn btn-default dropdown-toggle">
                                      <option value="0" <?php if($row['DrivingLicensePoints']=="0"){echo "selected";} ?> >0</option>
                                      <option value="3" <?php if($row['DrivingLicensePoints']=="3"){echo "selected";} ?> >3</option>
                                      <option value="6" <?php if($row['DrivingLicensePoints']=="6"){echo "selected";} ?> >6</option>
                                      <option value="9" <?php if($row['DrivingLicensePoints']=="9"){echo "selected";} ?> >9</option>
                                      <option value="12" <?php if($row['DrivingLicensePoints']=="12"){echo "selected";} ?> >12</option>
                                    </select>
                                    <br/>
                                    <br/>
                                </div>
                                <div class="col-sm-6">                        
                                    <label>Insurance</label>
                                    <select name="insurance" class="btn btn-default dropdown-toggle" style="width:100%;">
                                      <option value="Third party" <?php if($row['Insurance']=="Third party"){echo "selected";} ?> >Third party</option>
                                      <option value="Third party, fire and theft" <?php if($row['Insurance']=="Third party, fire and theft"){echo "selected";} ?> >Third party, fire and theft</option>
                                      <option value="Comprehensive" <?php if($row['Insurance']=="Comprehensive"){echo "selected";} ?> >Comprehensive </option>
                                    </select>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <input type="hidden" name="IDj" value="<?php echo $row['idCommute'] ?>">
                           <div class="row">
                                <div class="col-sm-1"></div>
                                <div class="col-sm-4">
                                    <button name="requestLiftButton" type="submit" class="btn btn-primary btn-block">Request Lift</button> 
                                </div>
                                <div class="col-sm-2"></div>
                                <div class="col-sm-4">
                                    <button name="provideLiftButton" type="submit" class="btn btn-primary btn-block">Provide Lift</button> 
                                </div>
                            </div>
                        </li>
                        </form>
                      </ul>
                </div>
            </div>
        </div>
        <?php
            }else{
                 $sql="SELECT * FROM mdb_fi2157j.Commutes Where Username= '".$UserName."' ORDER BY TimeStamp DESC LIMIT ". $this_page_first_result .",".$results_per_page;
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
                <h3 class="panel-title text-center">Commute <?php echo $row['idCommute'] ?> </h3>
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
                <div class="row">
                    <form method="post" action="view-commutes.php">
                        <div class="col-xs-1 col-sm-1 col-md-0 col-lg-1"></div>
                        <div class="col-xs-12 col-sm-5 col-md-5 col-lg-4">
                            <button name="editPost" type="submit" class="btn btn-primary btn-block pull-right">Edit Post</button> 
                        </div>
                        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                            <input type="hidden" name="IDj" value="<?php echo $row['idCommute'] ?>">
                        </div>
                        <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5 text-center">
                            <button name="deletePost" type="submit" class="btn btn-primary btn-block">Delete Post</button> 
                        </div>
                    </form> 
                </div>
              </div>
             <div class="panel-footer">
                 <div class="row">
                     <div class="text-center">
                        <?php echo "Last edited: ";
                         echo date('H:i:s d-M-Y', $row['TimeStamp']);?>
                    </div>
                 </div>
             </div>
            </div>
        </div>
        <?php
    } 
            
            }
        ?>
    </div>
    <div class="row text-center">
        <ul class="pagination">
            <?php
                for ($page=1;$page<=$number_of_pages;$page++) {
                    if($_GET['page']==$page){
                        ?>
                        <li class="active"><a href="view-commutes.php?page=<?php echo $page; ?>"><?php echo $page; ?></a></li>
            <?php        
                    }else{
            ?>
                        <li><a href="view-commutes.php?page=<?php echo $page; ?>"><?php echo $page; ?></a></li>
            <?php
                    }
                } 
            ?>            
        </ul>
    </div>


    <!--   Footer-->
        <footer class="container-fluid text-center">
            <hr>
            <kbd>&copy; Royal Borough of Greenwich Carpool</kbd>
        </footer>
        </div>
    </body>
</html>