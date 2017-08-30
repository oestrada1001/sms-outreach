<?php
require_once('../db_links.php');
require_once('../functions.php');
require_once('../validation/back_end_validation.php');
require_once('../twilio-php-master/Twilio/autoload.php');
require_once('../cronjobs/Twilio/twilio_keys.php');

use Twilio\Rest\Client;

$client = new Client($sid, $token);

$fingerprint = $_POST['fingerprint'];
$reAuthX = $_COOKIE['rainbow-bunny-cookie'];

$reAuthX_search = "SELECT * FROM credentials WHERE token = '$reAuthX' AND fingerprint = '$fingerprint' AND date_deleted >= NOW()";
$reAuthX_results = mysqli_query($db_connect, $reAuthX_search);
$reAuthX_rows = mysqli_num_rows($reAuthX_results);
$credentials = mysqli_fetch_array($reAuthX_results, MYSQLI_ASSOC);
$business_email = $credentials['email'];

if($reAuthX_rows == 1){
    //use email to get client values - basically same process as session.php
    $client_account_sql = "SELECT * FROM clients WHERE email = '$business_email'";
    $client_account_results = mysqli_query($db_connect, $client_account_sql);
    $row = mysqli_fetch_array($client_account_results, MYSQLI_ASSOC);
    
    //expire all previous tokens
    $expire_currentToken_sql = "UPDATE credentials SET date_deleted = NOW() WHERE fingerprint = '$fingerprint'";
    mysqli_query($db_connect, $expire_currentToken_sql);
    
    //generate new token
    $newToken = getToken(100);
    $insert_newToken_sql = "INSERT INTO credentials(id, email, fingerprint, date_created, token, date_deleted) ";
    $insert_newToken_sql.= "VALUES(DEFAULT, '$business_email', '$fingerprint', NOW(), '$newToken', NOW()+INTERVAL 3 DAY)";
    if(!mysqli_query($db_connect, $insert_newToken_sql)){
        echo mysqli_error($db_connect);
    }
    
    //update cookie
    $cookie_name = 'rainbow-bunny-cookie';
    $cookie_value = $newToken;
    setcookie($cookie_name, $cookie_value, time() + 86400*3, '/');
    
}else{
    //expire all tokens and log out
    $expire_tokens = "UPDATE credentials SET date_deleted = NOW() WHERE fingerprint = '$fingerprint'";
    $expire_tokens_results = mysqli_query($db_connect, $expire_tokens);
    
    //expire cookie
    $cookie_name = 'rainbow-bunny-cookie';
    unset($_COOKIE[$cookie_name]);
    setcookie($cookie_name, '', time() - 3600);
    
    echo '411';
    exit;
    
}



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
                if($row['collect_emails'] == 'yes'){
                    $sql = "SELECT * FROM $business_name WHERE email = '$sub_email' OR phone_number = '$sub_cell' LIMIT 1";
                    
                    $customer = mysqli_query($db_connect, $sql);
                        
                    //Test&Test1 are associative arrays, num_rows gets value 0 or 1. 0 = false, 1 = true
                    $test = mysqli_num_rows($customer);
                }elseif($row['collect_emails'] == 'no'){
                    
                    $sql = "SELECT * FROM $business_name WHERE phone_number = '$sub_cell' LIMIT 1";
                    
                    $customer = mysqli_query($db_connect, $sql);
                    
                    $test = mysqli_num_rows($customer);
                    
                }    
                if($test == 1){//check if number or email exist
        
                    
                    $customer = mysqli_fetch_array($customer, MYSQLI_ASSOC);
                    
                    $date = date('Y-m-d');
                    
                    $check_date = $customer['last_check_in'];
            
                    if($check_date == $date){
                        echo "You can only check in once-per-day.";
                        exit;
                    }
                    
                    $visit = intval($customer['visit']) + 1;
                    
                    if($visit > $row['visit_goal'] || $visit == 0){
                        $visit = 1;
                    }
                    
                    
                    if($row['collect_emails'] == 'no'){
                        
                        $sql = "UPDATE $business_name SET last_check_in = '$date', visit = $visit WHERE phone_number = '$sub_cell' LIMIT 1";
                    
                    }elseif($row['collect_emails'] == 'yes'){
                        
                        $sql = "UPDATE $business_name SET email = '$sub_email', last_check_in = '$date', visit = $visit WHERE phone_number = '$sub_cell' LIMIT 1";
                        
                    }
                    
                    if(mysqli_query($db_connect, $sql)){
                        //output number or email aleady exist    
                        echo "Your Record has been updated. || Visit $visit/{$row['visit_goal']}";
                        
                        if($visit == $row['visit_goal']){
                            
                            $sub_cell = str_replace($remove, '',$sub_cell);
                            
                            $sub_cell = '+1'.$sub_cell;
                            $visit_message = $row['visit_message'];
                            $client->messages->create(
                                $sub_cell,
                                array(
                                    'messagingServiceSid' => "MG8d8c7a3e572bd31e29103c7d3476da20",
                                    'body' => $visit_message,
                                )
                            );
                            
                            //Getting insert_sql from top
                            mysqli_query($db_connect, $insert_sql);
                            
                        }
                        
                        //Getting insert_sql from top
                        mysqli_query($db_connect, $insert_sql);
                    
                    }
                    
                }else{ //if number or email does not exist
        
                    //Record SQL
                    $sql = "INSERT INTO $business_name (id, name, phone_number, email, visit, last_check_in, registration_date, confirmed) ";
                    $sql.= "VALUES (DEFAULT, '$sub_name', '$sub_cell', '$sub_email', 1, NOW(), NOW(), 'yes')";
                    
                    //insert record
                    if(mysqli_query($db_connect, $sql)){
                        
                        //successful message
                        echo "Thank you for subscribing to our specials. || Vist 1/{$row['visit_goal']}";
                        
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
                        
                    }
                }
            
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
                
                $sql = "INSERT INTO $business_name (id, name, phone_number, email, visit, last_check_in, registration_date, confirmed) ";
                $sql.= "VALUES (DEFAULT, '$sub_name', '$sub_cell', '$sub_email', 1, NOW(), NOW(), 'yes')";
                
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
                    
                    echo 'Thank you for subscribing.';
                    exit;
                }
                
            } 
    
        }

    }


?>