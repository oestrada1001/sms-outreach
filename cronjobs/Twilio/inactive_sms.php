<?php
require_once('public_html/db_links.php');
require_once('twilio_keys.php');
require_once('public_html/twilio-php-master/Twilio/autoload.php');

use Twilio\Rest\Client;


$scheduled_sms = "SELECT * FROM clients";

if($results = mysqli_query($db_connect, $scheduled_sms)){
    
    $client = new Client($sid, $token);
    
    while($sender = $results->fetch_array()){

        $business_name = str_replace(' ', '_', strtolower($sender['business_name']));
        
        $business_message = $sender['after_message'];
        
        $business_active_limit = $sender['after_date'];
        
        
        $inactive = date("Y-m-d", strtotime("-".$business_active_limit." day"));

        $customer_sql = "SELECT * FROM $business_name WHERE last_check_in = '$inactive'";
        
        if($customer_results = mysqli_query($db_connect, $customer_sql)){
            
            $remove = ['(', ')',' ','-','+', '.', 'x'];
            
            while($customer = $customer_results->fetch_array()){
                
                $customer_number = str_replace($remove, '', $customer['phone_number']);
                
                try{
                    
                    
                    $business_history_table = $business_name . "_message_history";
                            
                    $update_business_history = "INSERT INTO $business_history_table (id, delivery_date, message_delivered, total_messages_sent) ";
                    $update_business_history.= "VALUES (DEFAULT, NOW(), '$business_message', DEFAULT)";
                    
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
                    
                 
                
                    if ($count == 0 || $count == null){
                        $count = 1;
                    }else{
                        $count++;
                    }
                    
                    $email = $sender['email'];
                    $businessName = $sender['business_name'];
                    $businessMessage = $sender['after_message'];
                    $business_history_table = $business_name . "_message_history";
                    $today = date('Y-m-d');
                    
                    $count_sql = "UPDATE $business_history_table SET total_messages_sent = '$count' WHERE message_delivered = '$businessMessage' AND delivery_date ='$today'";
                    
                    mysqli_query($db_connect, $count_sql);                   
                    
                    
                }catch(Exception $e){
                    
                    $to = 'o.estrada1001@gmail.com';
                    $subject = 'Cronjob Error';
                    $message = $e->getMessage();
                    
                    $headers  = "From: Blue Skyline Marketing <contact@blueskylinemarketing.com>\r\n"; 
                    $headers .= "X-Sender: Blue Skyline Marketing <contact@blueskylinemarketing.com>\r\n";
                    $headers .= 'X-Mailer: PHP/' . phpversion();
                    $headers .= "X-Priority: 1\r\n"; // Urgent message!
                    $headers .= "Return-Path: contact@blueskylinemarketing.com\r\n "; // Return path for errors
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=iso-8859-1\n";
                    
                    mail($to,$subject,$message,$headers);
                    
                    
                    
                }

                
            }
            
        }else{
            mysqli_error($db_connect);
        }
        
    }
    
    
}else{
    mysqli_error($db_connect);
}

?>