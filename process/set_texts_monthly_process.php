<?php
require_once('../session.php');

$email = $row['email'];
$numberOfTextMonthly = $_POST['numberOfText'];

if(!isset($numberOfTextMonthly)){
    echo 606;
    exit;
}
if($numberOfTextMonthly <= 0){
    echo 606;
}

if(!isset($row['email'])){
    header("location: ../login.php");
}

if(!$db_connect){
    echo 404;
    exit;
}else{
    
    $set_monthly_sql = $db_connect->prepare("UPDATE clients SET monthly_texts = ? WHERE email = ?");
    
    $set_monthly_sql->bind_param("ss", $numberOfTexts, $business_email);
    
    $numberOfTexts = $numberOfTextMonthly;
    $business_email = $email;
    
    if($set_monthly_sql->execute()){
        echo 101;
        exit;
    }else{
        echo 606;
        exit;
    }
    /*
    $set_monthly_sql = "UPDATE clients SET monthly_texts = '$numberOfTextMonthly' WHERE email = '$email'";
    
    if(mysqli_query($db_connect, $set_monthly_sql)){
        echo 101;
        exit;
    }else{
        echo 404;
        exit;
    }*/
    
}

?>