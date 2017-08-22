<?php
require_once('../session.php');
require_once('../validation/back_end_validation.php');

if(!isset($row['email'])){
    header("location: ../login.php");
}

$email = $row['email'];

$collect_emails_answer = strip_tags(trim($_POST['collect_emails_answer']));

$collect_emails_answer = mysqli_real_escape_string($db_connect,$collect_emails_answer);

$update_collect_emails_sql = "UPDATE clients SET collect_emails = '$collect_emails_answer' WHERE email = '$email' LIMIT 1";

$result = mysqli_query($db_connect, $update_collect_emails_sql);

if($result){
    echo 'client/settings.php';
    exit;
}else{
    echo 404;
    exit;
}



?>