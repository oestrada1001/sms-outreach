<?php
require_once "../validation/back_end_validation.php";
session_start();

    
$user = strip_tags(trim($_POST['login_user']));
$password = strip_tags(trim($_POST['login_password']));


if(!filter_var($user, FILTER_VALIDATE_EMAIL)){
    echo 404;
    exit;
}

if(!preg_match($valid_password, $password)){
    echo 404;
    exit;
}

if($user == 'live@preview.com' && $password == 'LivePreview!1'){
    
    $cookieValue = 'access_granted';
    
    setcookie("livePreview", $cookieValue, time()+3600, "/");
    
    $_SESSION['client_verification'] = 'approved';
    
    print("dashboard.php");
    
    
}else{
    echo '404';
}
?>