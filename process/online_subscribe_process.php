<?php
require_once('../db_links.php');
require_once('../validation/back_end_validation.php');
require_once('../twilio-php-master/Twilio/autoload.php');
require_once('../cronjobs/Twilio/twilio_keys.php');
session_start();

use Twilio\Rest\Client;

$client = new Client($sid, $token);

$subscription_link = $_SESSION['subscription_link'];
$sql = "SELECT * FROM clients WHERE subscription_link = '$subscription_link'";
$ses_sql = mysqli_query($db_connect, $sql);
$row = mysqli_fetch_array($ses_sql, MYSQLI_ASSOC);

$_SESSION['business_name'] = $row['business_name'];

if($row['default_message'] == null){
    $row['default_message'] = "Thank you for subscribing to {$row['business_name']}.";
}

$business_name = str_replace(' ', '_', strtolower($row['business_name']));

$sub_name = strip_tags(trim($_POST['sub_name']));
$sub_cell = strip_tags(trim($_POST['sub_cell']));
$sub_email = strip_tags(trim($_POST['sub_email']));

if(!preg_match($valid_name, $sub_name)){
    echo 'Invalid Name Format';
    exit;
}
if(!preg_match($valid_phone_number, $sub_cell)){
    echo 'Invalid phone number, please try again.';
    exit;
}
if($sub_email !== 'no'){
    if(!preg_match($valid_email, $sub_email)){
        echo "Invalid email, please try again.";
        exit;
    }
}elseif($sub_email == 'no'){
    $sub_email = null;
}


$sub_name = mysqli_real_escape_string($db_connect, $sub_name);
$sub_cell = mysqli_real_escape_string($db_connect, $sub_cell);
$sub_email = mysqli_real_escape_string($db_connect, $sub_email);

if(!$db_connect){ //database cannot connection

    die('field to connect to the server: ' . mysqli_connect_error());
    mysqli_close($db_connect);
    
}elseif(isset($_POST)){
    
    $remove = ['(', ')',' ','-','+', '.', 'x'];
    
    $business_name = str_replace(' ', '_', strtolower($row['business_name']));
    
    $business_default = $row['default_message'];
    $business_history_table = $business_name . "_message_history";
    $today = date('Y-m-d');
    
    $check_status_sql = "SELECT * FROM $business_history_table WHERE delivery_date = '$today' AND message_delivered = '$business_default'";
    $check_status = mysqli_query($db_connect, $check_status_sql);
    $check_status = mysqli_num_rows($check_status);
    
    if($check_status >= 1){
        $insert_sql = "UPDATE $business_history_table SET total_messages_sent = total_messages_sent+1 WHERE delivery_date = '$today' AND message_delivered = '$business_default'";
    }else{
        $insert_sql = "INSERT INTO $business_history_table (id,delivery_date, message_delivered, total_messages_sent) ";
        $insert_sql.= "VALUES (DEFAULT, '$today', '$business_default', '1') ";
    }
    
        if($row['visit_goal'] != null){

            
        }else{
            
            if($row['collect_emails'] == 'yes'){
                
                $sql = "SELECT * FROM $business_name WHERE email = '$sub_email' OR phone_number = '$sub_cell' LIMIT 1";
                
                $customer = mysqli_query($db_connect, $sql);
                
                $test = mysqli_num_rows($customer);
                
            
            }elseif($row['collect_emails'] == 'no'){
                
                $sql = "SELECT * FROM $business_name WHERE phone_number = '$sub_cell' LIMIT 1";
                
                $customer = mysqli_query($db_connect, $sql);
                
                $test = mysqli_num_rows($customer);
                
            }

            if($test == 1){
                
                if($sub_email == null){
                    
                    echo "This number is already registered in our system.";
                    exit;
                    
                }else{
                    
                    echo "This number or email is already registered in our system.";
                    exit;
                    
                }
                
            }else{
                
                $sql = "INSERT INTO $business_name (id, name, phone_number, email, visit, last_check_in, registration_date) ";
                $sql.= "VALUES (DEFAULT, '$sub_name', '$sub_cell', '$sub_email', 1, NOW(), NOW())";
                
                //insert record
                if(mysqli_query($db_connect, $sql)){
                    
                    $sub_cell = str_replace($remove, '',$sub_cell);
                    
                    $sub_cell = '+1'.$sub_cell;
                    $default_message = $row['default_message'] . "\n To unsubscribe reply with STOP or HELP for more infomation.";
                    $client->messages->create(
                        $sub_cell,
                        array(
                            'messagingServiceSid' => "MG8d8c7a3e572bd31e29103c7d3476da20",
                            'body' => $default_message,
                        )
                    );
                    
                    //Getting insert_sql from top
                    mysqli_query($db_connect, $insert_sql);
                    
                    echo 'Thank you for subscribing. We will be sending you a confirmation text so be sure to text <b>YES</b> to the message you will receive in order to receive our messages.';
                    exit;
                }
                
            } 
    
        }

    }


?>