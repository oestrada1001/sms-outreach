<?php
require_once('../session.php');
require_once('../validation/back_end_validation.php');

$verify_email = $_POST['verify_email'];

if(!preg_match($valid_email, $verify_email)){
    echo 404;
    exit;
}

$email_search_sql = "SELECT * FROM clients WHERE email = '$verify_email'";

$email_search_results = mysqli_query($db_connect, $email_search_sql);

$email_row_results = mysqli_num_rows($email_search_results);

while ($row = $email_search_results->fetch_assoc()){
    $client_row = $row;
}

$hash = $client_row['hash'];

if($email_row_results == 1){

    $to = $verify_email;
    $subject = 'Reset your password.';
    $message = 'Click on the link below to reset your password or copy and paste the link to the address bar.'. "<br>". 'If you didnt request this email, just delete this email and ignore it.'. "<br><br>". ' https://www.blueskylinemarketing.com/setup/verify.php?email='.$verify_email.'&hash='.$hash.'<br><br>Sincerely,'. "<br>". 'Oscar Estrada'. "<br>". '<i>Blue Skyline Marketing CEO</i>';

    $headers  = "From: Blue Skyline Marketing <contact@blueskylinemarketing.com>\r\n";
    $headers .= "Cc: Blue Skyline Marketing <contact@blueskylinemarketing.com>\r\n"; 
    $headers .= "X-Sender: Blue Skyline Marketing <contact@blueskylinemarketing.com>\r\n";
    $headers .= 'X-Mailer: PHP/' . phpversion();
    $headers .= "X-Priority: 1\r\n"; // Urgent message!
    $headers .= "Return-Path: contact@blueskylinemarketing.com\r\n "; // Return path for errors
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=iso-8859-1\n";    
 
    mail($to,$subject,$message,$headers);
    
    echo 1001;
    exit;

}else{
    echo 404;
    exit;
}
?>