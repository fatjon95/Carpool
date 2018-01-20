<?php
include "session.php";

$timeStamp = $_SERVER['REQUEST_TIME'];
$commuteToUpdate = $_SESSION['commuteToChange'];    
$UserName = $_SESSION['login_user'];

$sql = "SELECT * FROM mdb_fi2157j.Commutes Where Username= '$UserName' AND idCommute = $commuteToUpdate";
$results = $connection->query($sql);
$row = mysqli_fetch_array($results);

if($row['imagePath']==null){
    $imagesArray=[];
}else{
    $imagesArray = explode(", ", $row['imagePath']);           
    $imageKey = array_search($_POST['imageURL'], $imagesArray);
}
if (isset($_POST['deletePhoto'])) {
        unlink($_POST['imageURL']); //deletes image from folder
        unset($imagesArray[$imageKey]);
        $implodedImages = implode(", ", $imagesArray);
}
        
if($_FILES['updatePhoto']['type']){
        if ( !preg_match( '/gif|png|x-png|jpeg/', $_FILES['updatePhoto']['type']) ) {
           die('<p>Only browser compatible images allowed</p></body></html>');
        }  else if ( !($handle = fopen ($_FILES['updatePhoto']['tmp_name'], "r")) ) {
           die('<p>Error opening temp file</p></body></html>');
        } else if ( !($image = fread ($handle, filesize($_FILES['updatePhoto']['tmp_name']))) ) {
           die('<p>Error reading temp file</p></body></html>');
        } else {
            fclose ($handle);
            
            $filetmp = $_FILES["updatePhoto"]["tmp_name"];
            $filename = $_FILES["updatePhoto"]["name"];
            $filetype = $_FILES["updatePhoto"]["type"];
            $filepath = "images/".time().$filename;
            move_uploaded_file($filetmp,$filepath);
            if($_POST['imageURL'] == 'new'){
                array_push($imagesArray, $filepath);
            }else{
                $replacement = array($imageKey => $filepath);
                $imagesArray = array_replace($imagesArray, $replacement);
            }
                $implodedImages = implode(", ", $imagesArray);
        }
    }
        
    $sql ="UPDATE mdb_fi2157j.Commutes SET TimeStamp = '$timeStamp', imagePath = '$implodedImages'  WHERE idCommute = $commuteToUpdate";
        if($connection->query($sql) === true){
            header("location: view-commutes.php");
        }else{
            echo "<br> Record not inserted $sql.".$connection->error;
        }
        $connection->close(); 
        header("location: view-commutes.php");

?>