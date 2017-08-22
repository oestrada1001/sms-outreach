<?php
require_once('../session.php');
require_once('../validation/back_end_validation.php');
require_once('../functions.php');


// Please put your PHP code here
//
// You can use entered name variable as $_POST['join_now_name'], e-mail variable as $_POST['join_now_email'], message variable as $message


$name = strip_tags(trim($_POST['join_now_name']));
$email = strip_tags(trim($_POST['join_now_email']));
$hash = randomPassword();
if(!preg_match($valid_short_string, $name)){
    exit;
}
if(!preg_match($valid_email, $email)){
    exit;
}

$date = date('Y-m-d');
$business_type = 'special_offers';

$sql = "INSERT INTO bsm_subscribers VALUES ( DEFAULT, '$name', '$email', 'no', '$date', '$business_type', '$hash')";

mysqli_query($db_connect, $sql);

//Please insert here your email address:
    $to = $email;
    $subject = 'BSM | Thank you for subscribing.';
    $message = 'Thank you for subscribing. You will be the first to know when our products are going to launch as well as be the first to get our specials and our deals.<br>Please click on the link below to confirm your email:<br><a href="https://www.blueskylinemarketing.com/setup/confirm_subscription.php?email='.$email.'&hash='.$hash.'">https://www.blueskylinemarketing.com/setup/confirm_subscription?email='.$email.'&hash='.$hash.'</a><br><br>Sincerely,'. "<br>". 'Oscar Estrada'. "<br>". '<i>Blue Skyline Marketing CEO</i>';

    $headers  = "From: Blue Skyline Marketing <contact@blueskylinemarketing.com>\r\n";
    $headers .= "Cc: Blue Skyline Marketing <contact@blueskylinemarketing.com>\r\n"; 
    $headers .= "X-Sender: Blue Skyline Marketing <contact@blueskylinemarketing.com>\r\n";
    $headers .= 'X-Mailer: PHP/' . phpversion();
    $headers .= "X-Priority: 1\r\n"; // Urgent message!
    $headers .= "Return-Path: contact@blueskylinemarketing.com\r\n "; // Return path for errors
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=iso-8859-1\n";    
 
    mail($to,$subject,$message,$headers);

?>