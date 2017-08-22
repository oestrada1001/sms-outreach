<?php
require_once '../../preview_links.php';
require_once '../validation/back_end_validation.php';

if(!isset($row['email'])){
    header("location: ../logout.php");
}
$password = 'LivePreview!1';
$verify_key = strip_tags(trim($_POST['verify_key']));

$row['business_name'];

if(!preg_match($valid_password, $verify_key)){
    echo "404";
    exit;
}

if($password != $verify_key){
    echo 404;
    exit;
}elseif($password == $verify_key){
    $_SESSION['client_verification'] = 'approved';
    echo "../dashboard.php";
    exit;
}
?>