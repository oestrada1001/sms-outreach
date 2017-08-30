<?php
require_once '../session.php';
require_once '../functions.php';
require_once('../validation/back_end_validation.php');

if(!isset($row['email'])){
    header("location: ../login.php");
}

$email = $row['email'];
$fingerprint = trim($_POST['fingerprint']);
$reAuthX = trim($_POST['reAuthX']);

if(!preg_match($valid_number, $fingerprint)){
    echo '404';
}

if(!preg_match($valid_token, $reAuthX)){
    echo '404';
}

$check_table ="CREATE TABLE IF NOT EXISTS credentials(";
$check_table.="id INT AUTO_INCREMENT PRIMARY KEY, ";
$check_table.="email VARCHAR(30) NOT NULL, ";
$check_table.="fingerprint BIGINT NOT NULL, ";
$check_table.="date_created DATETIME NOT NULL, ";
$check_table.="token VARCHAR(255) NOT NULL, ";
$check_table.="date_deleted DATETIME NOT NULL)";

mysqli_query($db_connect, $check_table);


$check_lastest_token = "SELECT * FROM credentials WHERE fingerprint = '$fingerprint'";
$lastest_token = mysqli_query($db_connect, $check_lastest_token);    
$lastest_token_result = mysqli_num_rows($lastest_token);

if($lastest_token_result >= 1){
    $expire_credentials = "UPDATE credentials SET date_deleted = NOW() WHERE fingerprint = '$fingerprint'";
    mysqli_query($db_connect, $expire_credentials);
}

$insert_credentials = "INSERT INTO credentials(id, email, fingerprint, date_created, token, date_deleted) ";
$insert_credentials.= "VALUES (DEFAULT, '$email', '$fingerprint', NOW(), '$reAuthX', NOW() + INTERVAL 3 Day)";

if(mysqli_query($db_connect, $insert_credentials)){

    $cookie_name = 'rainbow-bunny-cookie';
    $cookie_value = $reAuthX;
    setcookie($cookie_name, $cookie_value, time() + 86400*3, '/');

    echo '1001';
    
}else{
    echo '404';
}
    


?>