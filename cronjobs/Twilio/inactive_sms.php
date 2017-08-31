<?php
require_once('public_html/db_links.php');
require_once('public_html/functions.php');
require_once('twilio_keys.php');
require_once('public_html/twilio-php-master/Twilio/autoload.php');

use Twilio\Rest\Client;

$scheduled_sms = "SELECT * FROM clients";

if($results = mysqli_query($db_connect, $scheduled_sms)){
    
    $client = new Client($sid, $twilio_token);
    
    while($sender = $results->fetch_array()){

        $business_name = str_replace(' ', '_', strtolower($sender['business_name']));
        
        $business_message = $sender['after_message'];
        
        $business_active_limit = $sender['after_date'];
        
        
        $inactive = date("Y-m-d", strtotime("-".$business_active_limit." day"));

        $customer_sql = "SELECT * FROM $business_name WHERE last_check_in = '$inactive' AND confirmed = 'yes'";
        
        if($customer_results = mysqli_query($db_connect, $customer_sql)){
            
            $remove = ['(', ')',' ','-','+', '.', 'x'];
            
            $subscriber_email = $sender['email'];
            $subscription_sql = "SELECT subscription_type FROM stripe_clients WHERE email = '$subscriber_email'";
            $subscription_type = mysqli_query($db_connect, $subscription_sql);
            
            $max_amount = max_amount_allowed($subscription_type);
            
            $businessName = $sender['business_name'];
            $business_history_table = $business_name . "_message_history";
            $date = date('Y-m-d');
            
            
            $current_sms_sent = "SELECT total_messages_sent FROM $business_history_table WHERE month(delivery_date)=month('$date') AND year(delivery_date)=year('$date')";
            $current_sms_results = mysqli_query($db_connect, $current_sms_sent);
            $sms_result_array = mysqli_fetch_array($current_sms_results, MYSQLI_ASSOC);
            $sms_total_result = array_sum($sms_result_array);
            
            $max_amount = $max_amount - $sms_total_result;
            
            while($customer = $customer_results->fetch_array()){
                
                if(!isset($count)){
                    $count = 0;
                }
                
                if($count >= $max_amount){
                    
                    $alert_message = "You are receiving this message because you have reach the maximum amount of sms messages you can send. Please upgrade or contact us if you would like to pay extra for every message exceeding your account's limit. Sorry for the inconvenience. - BSM : SMS Outreach";
                    
                    $client_number = str_replace($remove, '', $sender['phone_number']);
                    
                    $client_number = '+1'.$client_number;
                        
                    $client->messages->create(
                        $client_number,
                        array(
                            'messagingServiceSid' => 'MG8d8c7a3e572bd31e29103c7d3476da20',
                        'body' => $alert_message,
                        )
                    );
                    
                    $to = $subscriber_email;
                    $subject = 'BSM | SMS Limit Reached';
                    $message = $alert_message;
                    
                    $headers  = "From: Blue Skyline Marketing <contact@blueskylinemarketing.com>\r\n"; 
                    $headers .= "X-Sender: Blue Skyline Marketing <contact@blueskylinemarketing.com>\r\n";
                    $headers .= 'X-Mailer: PHP/' . phpversion();
                    $headers .= "X-Priority: 1\r\n"; // Urgent message!
                    $headers .= "Return-Path: contact@blueskylinemarketing.com\r\n "; // Return path for errors
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=iso-8859-1\n";
                    
                    mail($to,$subject,$message,$headers);
                    
                    break;
                }
                
                
                $customer_number = str_replace($remove, '', $customer['phone_number']);
                
                try{
                    
                    $today = date('Y-m-d');
                    
                    $business_history_table = $business_name . "_message_history";
                            
                    $update_business_history = "INSERT INTO $business_history_table (id, delivery_date, message_delivered, total_messages_sent) ";
                    $update_business_history.= "VALUES (DEFAULT, '$today', '$business_message', DEFAULT)";
                    
                    mysqli_query($db_connect, $update_business_history);
                    
                        $customer_number = '+1'.$customer_number;
                        
                        $client->messages->create(
                            $customer_number,
                            array(
                                'messagingServiceSid' => 'MG8d8c7a3e572bd31e29103c7d3476da20',
                            'body' => $business_message,
                            )
                        );
                    
                    //COUNT UPDATE 7/10/17
                    if(isset($count)){
                        $count++;
                    }else{
                        $count = 1;
                    }
                    
                }catch(Exception $e){
                    
                    if(isset($error_count)){
                        $error_count++;
                    }else{
                        $error_count = 1;
                    }
                    
                    $to = 'o.estrada1001@gmail.com';
                    $subject = 'BSM | INACTIVE SMS ERROR';
                    $message = $e;
                    
                    $headers  = "From: Blue Skyline Marketing <contact@blueskylinemarketing.com>\r\n"; 
                    $headers .= "X-Sender: Blue Skyline Marketing <contact@blueskylinemarketing.com>\r\n";
                    $headers .= 'X-Mailer: PHP/' . phpversion();
                    $headers .= "X-Priority: 1\r\n"; // Urgent message!
                    $headers .= "Return-Path: contact@blueskylinemarketing.com\r\n "; // Return path for errors
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=iso-8859-1\n";
                    
                    @mail($to,$subject,$message,$headers);
                    
                }
                
                if(isset($count)){
                    $count++;
                }else{
                    $count = 1;
                }
                
            }
            
        }else{
            mysqli_error($db_connect);
        }
        
        //This is Testing. Remember how try/catch/finally work and take into consideration that after the loop the code continues. Good luck future Oscar. You will be successful, never give up on your dream. 
        
        $email = $sender['email'];
        $businessName = $sender['business_name'];
        $businessMessage = $sender['after_message'];
        $business_history_table = $business_name . "_message_history";
        $today = date('Y-m-d');
        
        $count_sql = "UPDATE '$business_history_table' SET total_messages_sent = '$count' WHERE message_delivered = '$businessMessage' AND delivery_date ='$today'";
        
        mysqli_query($db_connect, $count_sql);          
        
        
    
    }
    
}else{
    mysqli_error($db_connect);
}

?>
