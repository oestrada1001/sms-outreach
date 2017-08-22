<?php

require_once 'session.php';

if(!isset($row['email'])){
    header("location: login.php");
}
if($row['access'] == 0){
    header("location: setup/unpaid_account.php");
}


$adminCheck = "SELECT * FROM admin Where email = '$login_session'";
$adminCheck = mysqli_query($db_connect, $adminCheck);
$adminCheck = mysqli_num_rows($adminCheck);

$clientCheck = "Select * FROM clients Where email = '$login_session'";
$clientCheck = mysqli_query($db_connect, $clientCheck);
$clientCheck = mysqli_num_rows($clientCheck);

if($adminCheck == 1 && $clientCheck == 0){

        include 'employee/register.php';
        //Admin Dashboard home link here;
    
}elseif($adminCheck == 0 && $clientCheck == 1){
    
        include 'client/dashboard_home.php';
  
}else{
    //
    header("location: logout.php");
}
?>
