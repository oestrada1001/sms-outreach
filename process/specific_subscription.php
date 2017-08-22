<?php
require_once('../session.php');
require_once('../validation/back_end_validation.php');
require_once('../functions.php');


    $subscriber_name = strip_tags(trim($_POST['subscriber_name']));
    $subscriber_email = strip_tags(trim($_POST['subscriber_email']));
    $business_type = strip_tags(trim($_POST['business_type']));
    
    $hash = randomPassword();
    $date = date('Y-m-d');


    if(!preg_match($valid_short_string, $subscriber_name)){
        echo 404;
        exit;
    }
    if(!preg_match($valid_email, $subscriber_email)){
        echo 404;
        exit;
    }
    if(!preg_match($valid_short_string, $business_type)){
        echo 404;
        exit;
    }

    $business_type = "other:-".$business_type;
    
    $sql = "INSERT INTO bsm_subscribers VALUES ( DEFAULT, '$subscriber_name', '$subscriber_email', 'no', '$date', '$business_type', '$hash')";

    if(mysqli_query($db_connect, $sql)){
        
        $to = $subscriber_email;
        $subject = 'BSM | Thank you for subscribing.';
        $message = 'Thank you for subscribing. You will be the first to know when our products are going to launch as well as be the first to get our specials and our deals.<br>Please click on the link below to confirm your email:<br><a href="https://www.blueskylinemarketing.com/setup/confirm_subscription.php?email='.$subscriber_email.'&hash='.$hash.'">https://www.blueskylinemarketing.com/setup/confirm_subscription.php?email='.$subscriber_email.'&hash='.$hash.'</a><br><br>Sincerely,'. "<br>". 'Oscar Estrada'. "<br>". '<i>Blue Skyline Marketing CEO</i>';

        $headers  = "From: Blue Skyline Marketing <contact@blueskylinemarketing.com>\r\n";
        $headers .= "Cc: Blue Skyline Marketing <contact@blueskylinemarketing.com>\r\n"; 
        $headers .= "X-Sender: Blue Skyline Marketing <contact@blueskylinemarketing.com>\r\n";
        $headers .= 'X-Mailer: PHP/' . phpversion();
        $headers .= "X-Priority: 1\r\n"; // Urgent message!
        $headers .= "Return-Path: contact@blueskylinemarketing.com\r\n "; // Return path for errors
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=iso-8859-1\n";    
        
        mail($to,$subject,$message,$headers);

        echo 101;
        exit;
        
    }else{
        echo 606;
        exit;
    }
        
        

?>