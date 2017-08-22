<?php
require_once('../db_links.php');
require_once('../twilio-php-master/Twilio/autoload.php');
require_once('../cronjobs/Twilio/twilio_keys.php');

use Twilio\Twiml;

$response = new Twiml;
$business_name = $_POST['Body'];
$phone_number = $_POST['From'];
$business_table = $_POST['ToCity'];
//$business_name = $_POST['ToCity'];

$phone_number = preg_replace("/(\+1)?/", "", $phone_number);

$change_email_sql = "UPDATE $business_table SET email ='$business_name' WHERE phone_number = '$phone_number'";

if(mysqli_query($db_connect, $change_email_sql)){

    header('Content-Type: text/xml');
    ?>
    
    <Response>
        <Message>Success</Message>
    </Response>
    
<!--
    $message = $response->message();
    $message->body($business_name);
    echo $response;
-->
    <?php
}else{
    
    header('Content-Type: text/xml');
    ?>
    
    <Response>
        <Message>Failed</Message>
    </Response>    
<!--
    $message = $response->message();
    $message->body($business_name);
    echo $response;
-->
    <?php
}

?>