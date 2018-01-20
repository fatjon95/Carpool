<?php 
include "session.php";

if (isset($_POST['requestLiftButton']) || isset($_POST['provideLiftButton'])){ 

    if(!empty($_POST['IDj'])) {
        $commuteToUpdate = $_POST['IDj'];  
    }else{
        header("location:view-commutes.php");
    }
    if(empty($_POST['startingPointText'])){
        $_SESSION['ustartingPointLabel'] = '<span class="label label-danger center-block">Enter Starting Point!</span>';
        header("location:view-commutes.php");
        exit;
    }else{
        $StartingPoint = $_POST['startingPointText'];
        $StartingPoint = htmlentities($StartingPoint);
        $StartingPoint = mysqli_real_escape_string($connection, $StartingPoint);
    }
    
    if(empty($_POST['endPointText'])){
        $_SESSION['uendPointLabel'] = '<span class="label label-danger center-block">Enter End Point!</span>';
        header("location:view-commutes.php");
        exit;
    }else{
        $EndPoint = $_POST['endPointText'];
        $EndPoint = htmlentities($EndPoint);
        $EndPoint = mysqli_real_escape_string($connection, $EndPoint);
    }  
    
    if(empty($_POST['startingTimeText'])){
        $_SESSION['ustartingTimeLabel'] = '<span class="label label-danger center-block">Enter Starting Time!</span>';
        header("location:view-commutes.php");
        exit;
    }else{
        $StartingTime = $_POST['startingTimeText'];
        $StartingTime = htmlentities($StartingTime);
        $StartingTime = mysqli_real_escape_string($connection, $StartingTime);
    }
    if(empty($_POST['endTimeText'])){
        $_SESSION['uendTimeLabel'] = '<span class="label label-danger center-block">Enter End Time!</span>';
        header("location:view-commutes.php");
        exit;
    }else{
        $EndTime = $_POST['endTimeText'];
        $EndTime = htmlentities($EndTime);
        $EndTime = mysqli_real_escape_string($connection, $EndTime);
    }
    
    if(empty($_POST["Days"])){
        $_SESSION['udaysLabel'] = '<span class="label label-danger center-block">Choose Commute Day!</span>';
        header("location:view-commutes.php");
        exit;
    }else{
        $days = implode(", ",$_POST["Days"]);
    }
    
    if(empty($_POST['journeyCostText'])){
        $JourneyCost = '0.00';
    }else{
        $JourneyCost = $_POST['journeyCostText'];
        $JourneyCost = htmlentities($JourneyCost);
        $JourneyCost = mysqli_real_escape_string($connection, $JourneyCost);
    }
    
    if(empty($_POST['carBrandText'])){
        $CarBrand ='Unknown';
    }else{
        $CarBrand = $_POST['carBrandText'];
        $CarBrand = htmlentities($CarBrand);
        $CarBrand = mysqli_real_escape_string($connection, $CarBrand);
    }
    
    $Username = $_SESSION['login_user'];
    $timeStamp = $_SERVER['REQUEST_TIME'];
    
    if(empty($_POST['startlat'])){
        $startLatitude ='';
    }else{
        $startLatitude = $_POST['startlat'];
        $startLatitude = htmlentities($startLatitude);
        $startLatitude = mysqli_real_escape_string($connection, $startLatitude);
    }
    
    if(empty($_POST['startlng'])){
        $startLongitude ='';
    }else{
         $startLongitude = $_POST['startlng'];
        $startLongitude = htmlentities($startLongitude);
        $startLongitude = mysqli_real_escape_string($connection, $startLongitude);
    }
    
    if(empty($_POST['endlat'])){
        $endLatitude ='';
    }else{
        $endLatitude = $_POST['endlat'];
        $endLatitude = htmlentities($endLatitude);
        $endLatitude = mysqli_real_escape_string($connection, $endLatitude);
    }

    if(empty($_POST['endlng'])){
        $endLongitude ='';
    }else{
        $endLongitude = $_POST['endlng'];
        $endLongitude = htmlentities($endLongitude);
        $endLongitude = mysqli_real_escape_string($connection, $endLongitude);
    }
    
    $DrivingLicensePoints = $_POST['licensePoints'];
    $Insurance = $_POST['insurance'];
    
    if (isset($_POST['requestLiftButton'])){        
         $sql ="UPDATE mdb_fi2157j.Commutes SET TimeStamp = '$timeStamp', StartingPoint = '$StartingPoint', startLatitude = '$startLatitude', startLongitude = '$startLongitude', EndPoint = '$EndPoint', endLatitude= '$endLatitude', endLongitude = '$endLongitude', StartingTime = '$StartingTime', EndTime = '$EndTime', CommuteRepetion = '$days', JourneyCost = '$JourneyCost', CarBrand = '$CarBrand', DrivingLicensePoints = '$DrivingLicensePoints', Insurance = '$Insurance', ProvideLift = '0'
         WHERE idCommute = $commuteToUpdate";
        
    }
    
    if (isset($_POST['provideLiftButton'])){
        $sql ="UPDATE mdb_fi2157j.Commutes SET TimeStamp = '$timeStamp', StartingPoint = '$StartingPoint', startLatitude = '$startLatitude', startLongitude = '$startLongitude', EndPoint = '$EndPoint', endLatitude= '$endLatitude', endLongitude = '$endLongitude',EndPoint = '$EndPoint', StartingTime = '$StartingTime', EndTime = '$EndTime', CommuteRepetion = '$days', JourneyCost = '$JourneyCost', CarBrand = '$CarBrand', DrivingLicensePoints = '$DrivingLicensePoints', Insurance = '$Insurance', ProvideLift = '1'
        WHERE idCommute = $commuteToUpdate";
    }
    if($connection->query($sql) === true){
            unset($_SESSION['commuteToChange']); 
            header("location: view-commutes.php");
        }else{
             echo "<br> Record not inserted $sql.".$connection->error;
        }
        $connection->close(); 
    }
?>