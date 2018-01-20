<?php 
include "session.php";
if ($row['Verification'] == "0"){
        header("location:verification.php");
    }
unset($_SESSION['searchString']);
?>
<!DOCTYPE html>
<html lang="en-GB">
    <head>
        <title>Create-Carpool</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/site.js"></script>
        <style>
            body {
                background-image: url("greenwich-background.jpg");
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
            <li class="active"><a href="http://stuweb.cms.gre.ac.uk/~fi2157j/profile.php"> <span class="glyphicon glyphicon-plus"></span> Create Commute</a></li>
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
    <!--  Content  -->
    <div class="row content">
        <div class="col-xs-12  col-sm-12 col-md-4 col-lg-4">
            <div class="panel panel-default panelBackground">
                  <!-- Default panel contents -->
                <div class="panel-heading text-center">
                      <h3>Dashboard</h3>
                </div>
                <div class="panel-body">
                    <div class="well">
                        <p>Commuter's Name </p>
                        <label>
                            <?php 
                                if(!empty($_SESSION['login_user'])) {
                                    echo $_SESSION['login_user'];
                                } 
                            ?>
                        </label>
                    </div>
                     <div class="well">
                        <p>Commuter's Email </p>
                        <label>
                            <?php 
                                if(!empty($_SESSION['login_email'])) {
                                    echo $_SESSION['login_email'];
                                } 
                            ?>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-1  col-sm-1 col-md-1 col-lg-1"></div>
        <div class="col-xs-12  col-sm-12 col-md-7 col-lg-7">
            <div class="panel panel-default panelBackground">
                  <!-- Default panel contents -->
                  <div class="panel-heading text-center"><h3>Create Commute</h3></div>
                  <div class="panel-body">
                    <div class="image"></div>        
                  <!-- List group -->
                <ul class="list-group">
                        <li class="list-group-item">
                            <div class="row">
                                <div id="map" style="width:100%;height:500px;"></div>
                                <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDCAqnL4zmzFq7-s5NQvQLgECqAUYKBscc&callback=myMap"></script>
                            </div>                  
                        </li>
                    <form method="post" action="postcommute.php" enctype="multipart/form-data">
                        <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-6">
                                <label>Starting Point</label>
                                <input id="startingPointText" name="startingPointText" type="text" class="form-control" placeholder="Starting Point"/>
                                <?php 
                                    if(!empty($_SESSION['startingPointLabel'])) {
                                        echo $_SESSION['startingPointLabel']; 
                                    } 
                                    unset($_SESSION['startingPointLabel']); 
                                ?>
                                <input type="hidden" size="20" maxlength="30" id="startlat" name="startlat" value=""/>
                                <input type="hidden" size="20" maxlength="30" id="startlng" name="startlng" value=""/>
                            </div>
                              <div class="col-sm-6">
                                  <label>End Point</label>
                                  <input id="endPointText" name="endPointText" type="text" class="form-control" placeholder="End Point"/>
                                  <?php 
                                        if(!empty($_SESSION['endPointLabel'])) {
                                        echo $_SESSION['endPointLabel']; 
                                        } 
                                        unset($_SESSION['endPointLabel']); 
                                    ?>
                                <input type="hidden" size="20" maxlength="30" id="endlat" name="endlat" value=""/>
                                <input type="hidden" size="20" maxlength="30" id="endlng" name="endlng" value=""/>
                              </div>
                          </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                              <div class="col-sm-6">
                              <label>Starting Time</label>
                                  <input name="startingTimeText" type="time" class="form-control"/>
                                  <?php 
                                        if(!empty($_SESSION['startingTimeLabel'])) {
                                        echo $_SESSION['startingTimeLabel']; 
                                        } 
                                        unset($_SESSION['startingTimeLabel']); 
                                    ?>
                              </div>
                              <div class="col-sm-6">
                                  <label>End Time</label>
                                  <input name="endTimeText" type="time" class="form-control"/>
                                  <?php 
                                        if(!empty($_SESSION['endTimeLabel'])) {
                                        echo $_SESSION['endTimeLabel']; 
                                        } 
                                        unset($_SESSION['endTimeLabel']); 
                                    ?>
                            </div>
                          </div>
                    </li>
                    <li class="list-group-item">
                        <label>Commute Days</label>
                        <div class="row text-center well well-sm">
                            <input type="checkbox" name="Days[]"    value="Monday">   Monday
                            <input type="checkbox" name="Days[]"   value="Tuesday">  Tuesday
                            <input type="checkbox" name="Days[]" value="Wednesday">Wednesday
                            <input type="checkbox" name="Days[]"  value="Thursday"> Thursday
                            <input type="checkbox" name="Days[]"    value="Friday">   Friday
                            <input type="checkbox" name="Days[]"  value="Saturday"> Saturday
                            <input type="checkbox" name="Days[]"    value="Sunday">   Sunday
                             <?php 
                                if(!empty($_SESSION['daysLabel'])) {
                                echo $_SESSION['daysLabel']; 
                                } 
                                unset($_SESSION['daysLabel']); 
                                ?>    
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-4">
                                <label>Journey Cost</label>
                            </div>
                            <div class="col-sm-8">
                                <input name="journeyCostText" type="number" min="0.00" max="10000.00" step="0.01" class="form-control" placeholder="Journey Cost (£)"/>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-4">
                                <label>Car Brand</label>
                            </div>
                            <div class="col-sm-8">
                                <input name="carBrandText" type="text" class="form-control" placeholder="Car Brand"/>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row ">
                            <div class="col-sm-6">
                                <label>Driving License Points</label>
                                <select name="licensePoints" class="btn btn-default dropdown-toggle pull-right">
                                  <option value="0">0</option>
                                  <option value="3">3</option>
                                  <option value="6">6</option>
                                  <option value="9">9</option>
                                  <option value="12">12</option>
                                </select>
                                <br/>
                                <br/>
                            </div>
                            <div class="col-sm-6">                        
                                <label>Insurance</label>
                                <select name="insurance" class="btn btn-default dropdown-toggle pull-right">
                                  <option value="Third party">Third party</option>
                                  <option value="Third party, fire and theft">Third party, fire and theft</option>
                                  <option value="Comprehensive ">Comprehensive </option>
                                </select>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-sm-4"><label>Upload Images: </label></div>
                            <div class="col-sm-4 text-center">
                                 
                                 <label class="btn btn-default btn-block " for="useFileId">
                                     <span class="glyphicon glyphicon-upload"></span>
                                    Upload Images
                                </label>
                                    <?php 
                                        if(!empty($_SESSION['imagesLabel'])) {
                                            echo $_SESSION['imagesLabel']; 
                                        } 
                                        unset($_SESSION['imagesLabel']); 
                                    ?>
                            </div>
                            <div class="col-sm-4">
                                <input type="file" id="useFileId" name="userFile[]" style="visibility: hidden;" multiple class=""/>
                                    <script>
                                        document.getElementById("useFileId").addEventListener("change", function() {
                                            for (var index = 0; index < this.files.length; ++index) {
                                                var temp = this.files[index].name.match(/gif|png|x-png|jpeg|jpg/);
                                                console.log(this.files[index].name);
                                                if (temp == null ) {
                                                    alert("File selected is not an image!");
                                                    document.getElementById("useFileId").value = "";
                                                    }
                                            }
                                        });
                                    </script>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
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
    </div>

    <!--   Footer-->
        <footer class="container-fluid text-center">
            <hr>
            <kbd>&copy; Royal Borough of Greenwich Carpool</kbd>
        </footer>
        </div>
    </body>
</html>