<?php

require_once '../preview_links.php';

if(!isset($row['email'])){
    header("location: login.php");
}

$clientCheck = "Select * FROM clients Where email = '$login_session'";
$clientCheck = mysqli_query($db_connect, $clientCheck);
$clientCheck = mysqli_num_rows($clientCheck);

if($clientCheck == 1){
    
        include 'client/dashboard_home.php';
  
}else{
    //
    header("location: logout.php");
}
?>
