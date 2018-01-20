<?php 
include "session.php";

//function GetCoordinates($address) {
// 
////$address = str_replace(" ", "+", $address); // replace all the white space with "+" sign to match with google search pattern
// 
//$address = 'avenida+gustavo+paiva,maceio,alagoas,brasil';
//
//$geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$address.'&sensor=false');
//
//$output= json_decode($geocode);
//
//$lat = $output->results[0]->geometry->location->lat;
//$long = $output->results[0]->geometry->location->lng;
//    
//    echo $lat;
//    echo "<br/>";
//    echo $long;
//    return "$lat, $long";
// 
//}
    


if (isset($_POST['requestLiftButton']) || isset($_POST['provideLiftButton'])){
    foreach($_FILES['userFile']['size'] as $results) {
        $sum =+ $results;
    }
    if ($sum == '0'){
        $implodedImages = '';
    }else{
        $images = array();
        for($i = 0; $i < count($_FILES['userFile']['name']); $i++)
        {
        if ( !preg_match( '/gif|png|x-png|jpeg|jpg/', $_FILES['userFile']['type'][$i]) ) {
           $_SESSION['imagesLabel'] = '<span class="label label-danger center-block">File selected is not an image!</span>';
            header("location:profile.php");
            exit;
        }  else if ( !($handle = fopen ($_FILES['userFile']['tmp_name'][$i], "r")) ) {
            $_SESSION['imagesLabel'] = '<span class="label label-danger center-block">There is a problem with the selected file!</span>';
            header("location:profile.php");
            exit;
        } else if ( !($image = fread ($handle, filesize($_FILES['userFile']['tmp_name'][$i]))) ) {
            $_SESSION['imagesLabel'] = '<span class="label label-danger center-block">There is a problem with the selected file!</span>';
            header("location:profile.php");
            exit;
        } else {
           fclose ($handle);
            $filetmp  = $_FILES["userFile"]["tmp_name"][$i];
            $filename = $_FILES["userFile"]["name"][$i];
            $filetype = $_FILES["userFile"]["type"][$i];
            $filepath = "images/".time().$filename;

            move_uploaded_file($filetmp,$filepath);
            $images[] = $filepath;
            }
        }
        $implodedImages = implode(", ", $images);
        
    }

    if(empty($_POST['startingPointText'])){
        $_SESSION['startingPointLabel'] = '<span class="label label-danger center-block">Enter Starting Point!</span>';
        header("location:profile.php");
        exit;
    }else{
        $StartingPoint = $_POST['startingPointText'];
        $StartingPoint = htmlentities($StartingPoint);
        $StartingPoint = mysqli_real_escape_string($connection, $StartingPoint);
    }

    if(empty($_POST['endPointText'])){
        $_SESSION['endPointLabel'] = '<span class="label label-danger center-block">Enter End Point!</span>';
        header("location:profile.php");
        exit;
    }else{
        $EndPoint = $_POST['endPointText'];
        $EndPoint = htmlentities($EndPoint);
        $EndPoint = mysqli_real_escape_string($connection, $EndPoint);
    }
        
    if(empty($_POST['startingTimeText'])){
        $_SESSION['startingTimeLabel'] = '<span class="label label-danger center-block">Enter Starting Time!</span>';
        header("location:profile.php");
        exit;
    }else{
        $StartingTime = $_POST['startingTimeText'];
        $StartingTime = htmlentities($StartingTime);
        $StartingTime = mysqli_real_escape_string($connection, $StartingTime);
    }
    
    if(empty($_POST['endTimeText'])){
        $_SESSION['endTimeLabel'] = '<span class="label label-danger center-block">Enter End Time!</span>';
        header("location:profile.php");
        exit;
    }else{
        $EndTime = $_POST['endTimeText'];
        $EndTime = htmlentities($EndTime);
        $EndTime = mysqli_real_escape_string($connection, $EndTime);
    }
        
    if(empty($_POST["Days"])){
        $_SESSION['daysLabel'] = '<span class="label label-danger center-block">Choose Commute Day!</span>';
        header("location:profile.php");
        exit;
    }else{
        $days = implode(", ",$_POST["Days"]);
        $days = htmlentities($days);
        $days = mysqli_real_escape_string($connection, $days);
    }
    if(empty($_POST['journeyCostText'])){
        $JourneyCost = '0';
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
    
//    echo $startLatitude;
//    echo "<br/>";
//    echo $startLongitude;
//    echo "<br/>";
//    echo GetCoordinates($StartingPoint);
//    exit();

    if (isset($_POST['requestLiftButton'])){ 
        $sql = "INSERT into mdb_fi2157j.Commutes(TimeStamp, Username, StartingPoint, startLatitude, startLongitude, EndPoint, endLatitude, endLongitude, StartingTime, EndTime, CommuteRepetion, JourneyCost, CarBrand, DrivingLicensePoints, Insurance, imagePath, ProvideLift)                          VALUES('$timeStamp','$Username','$StartingPoint','$startLatitude','$startLongitude','$EndPoint','$endLatitude','$endLongitude','$StartingTime','$EndTime','$days', '$JourneyCost','$CarBrand','$DrivingLicensePoints','$Insurance','$implodedImages','0')";
    }
    
    if (isset($_POST['provideLiftButton'])){
        $sql = "INSERT into mdb_fi2157j.Commutes(TimeStamp, Username, StartingPoint, startLatitude, startLongitude, EndPoint,  endLatitude, endLongitude,StartingTime, EndTime, CommuteRepetion, JourneyCost, CarBrand, DrivingLicensePoints, Insurance, imagePath, ProvideLift)                          VALUES('$timeStamp','$Username','$StartingPoint','$startLatitude','$startLongitude','$EndPoint','$endLatitude','$endLongitude','$StartingTime','$EndTime','$days', '$JourneyCost','$CarBrand','$DrivingLicensePoints','$Insurance','$implodedImages','1')";
    }
    if($connection->query($sql) === true){
            header("location: view-commutes.php");
        }else{
             echo "<br> Record not inserted $sql.".$connection->error;
        }
        $connection->close();   
}
?>