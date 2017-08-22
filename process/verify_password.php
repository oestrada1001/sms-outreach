<?php
require_once '../session.php';
require_once '../validation/back_end_validation.php';

if(!isset($_SESSION['email'])){
    header("location: ../logout.php");
}
$password = $row['password'];
$verify_key = strip_tags(trim($_POST['verify_key']));

$row['business_name'];

if(!preg_match($valid_password, $verify_key)){
    echo "404";
    exit;
}

if(password_verify($verify_key, $password)){
    
    $_SESSION['client_verification'] = 'verified';

    print("../dashboard.php");
    exit;
}else{
    echo "404";
    exit;
}
//if($password != $verify_key){
//    echo 404;
//    exit;
//}elseif($password == $verify_key){
//    echo "../dashboard.php";
//    exit;
//}
?>