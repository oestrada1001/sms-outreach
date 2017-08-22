<?php
require_once('public_html/db_links.php');
require_once('twilio_keys.php');
require_once('public_html/twilio-php-master/Twilio/autoload.php');

use Twilio\Rest\Client;

$today = date("Y-m-d");

$scheduled_sms = "SELECT * FROM clients WHERE delivery_date = '$today' AND sms_sent = 'no'";

if($results = mysqli_query($db_connect, $scheduled_sms)){
    
    $client = new Client($sid, $token);
    
    
    
    while($sender = $results->fetch_array()){

        $business_name = str_replace(' ', '_', strtolower($sender['business_name']));
        
        $business_message = $sender['custom_message'];

        $customer_sql = "SELECT name, phone_number FROM $business_name";
        
        if($customer_results = mysqli_query($db_connect, $customer_sql)){
            
            $remove = ['(', ')',' ','-','+', '.', 'x'];
            
            while($customer = $customer_results->fetch_array()){
                
                $customer_number = str_replace($remove, '', $customer['phone_number']);
                
                try{
                    
                    if($sender['sms_sent'] == 'yes'){
                    
                        $customer_number = '+1'.$customer_number;
                        
                        $client->messages->create(
                            $customer_number,
                            array(
                                'messagingServiceSid' => 'MG8d8c7a3e572bd31e29103c7d3476da20',
                            'body' => $business_message,
                            )
                        );
                    
                    }elseif($sender['sms_sent'] == 'no'){
                        try{
                            
                            $email = $sender['email'];
                            $businessName = $sender['business_name'];
                            
                            $update_sms_sent_sql = "UPDATE clients SET sms_sent = 'yes' WHERE email = '$email' AND business_name = '$businessName'";
                            
                            mysqli_query($db_connect, $update_sms_sent_sql);
                            
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
                    
                        
                        }catch(Expection $e){
                        
                            $to = "contact@blueskylinemarketing.com, o.estrada1001@gmail.com";
                            $subject = 'UPDATE HISTORY TABLE Error! FIX IMMEDIATLY!';
                            $message = $e->getMessage();
                            $headers = 'From: Server'."\r\n".'Reply-To: contact@blueskylinemarketing.com'."\r\n".'X-Mailer: PHP/'.phpversion();
                            
                            mail($to, $subject, $message, $headers);
                        
                        }
                    }
                    
                    //COUNT UPDATE 7/10/17
                    
                    if ($count == 0 || $count == null){
                        $count = 1;
                    }else{
                        $count++;
                    }
                    
                    $email = $sender['email'];
                    $businessName = $sender['business_name'];
                    $businessMessage = $sender['custom_message'];
                    $business_history_table = $business_name . "_message_history";
                    $today = date('Y-m-d');
                    
                    $count_sql = "UPDATE $business_history_table SET total_messages_sent = '$count' WHERE message_delivered = '$businessMessage' AND delivery_date ='$today'";
                    
                    mysqli_query($db_connect, $count_sql);
                    
                    
                    
                }catch(Exception $e){
                    
                    $to = "contact@blueskylinemarketing.com, o.estrada1001@gmail.com";
                    $subject = 'BULK SMS Error! FIX IMMEDIATLY!';
                    $message = $e->getMessage();
                    $headers = 'From: Server'."\r\n".'Reply-To: contact@blueskylinemarketing.com'."\r\n".'X-Mailer: PHP/'.phpversion();
                    
                    mail($to, $subject, $message, $headers);
                    
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