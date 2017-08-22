<?php
// Set your secret key: remember to change this to your live secret key in production
// See your keys here: https://dashboard.stripe.com/account/apikeys
require_once('../stripe4-x/stripe-php-4.13.0/init.php');
require_once('../db_links.php');
require_once('../functions.php');
\Stripe\Stripe::setApiKey("sk_live_1teGqmhHeAwmjkmdkINqxbqp");


    //First Part of Security
//$endpoint_secret = "whsec_PX33jHROsjmcqQcaeMp1fsgniyRIExNJ";
//
//$payload = @file_get_contents("php://input");
//$sig_header = $_SERVER["HTTP_STRIPE_SIGNATURE"];
//$event = null;

try {
    
    $input = @file_get_contents("php://input");
    $event = json_decode($input);
    
    
    //Security
//    $event = \Stripe\Webhook::constructEvent(
//        $payload, $sig_header, $endpoint_secret
//    );
    
//    var_dump($event);
//    http_response_code(200);
//    exit();
//    
        
    //Complete Event
    $complete_event = \Stripe\Event::retrieve($event->id);
    //Event Type
    $type = $complete_event->type;
    
//    $id = $event->id; //<-$event is $complete_event
//    $type = $event->type;
//    $plan = $event->data->object->lines->data[0]->plan->id;
    
    if(isset($complete_event) && $type == 'customer.created'){
        
        $customer_id = $complete_event->data->object->id;
        $customer_email = $complete_event->data->object->email;
        
//        $complete_customer = \Stripe\Customer::retrieve($complete_event->data->object->id);
//        $customer_id = $complete_customer->id;
//        $customer_email = $complete_customer->email;
//        
        $insert_customer_info = "INSERT INTO stripe_clients ( id, email, customer_id, subscriber_id, subscription_type) ";
        $insert_customer_info.= "VALUES ( DEFAULT, '$customer_email', '$customer_id', NULL, NULL)";
        
        mysqli_query($db_connect, $insert_customer_info);
        
        http_response_code(200);
        exit();
        
    }
    
    if(isset($complete_event) && $type == 'invoice.payment_failed'){
        
        //invoice.payment_failed
        $customer = \Stripe\Customer::retrieve($complete_event->data->object->customer);
        $email = $customer->email;
        
        $amount = sprintf('$%0.2f', $complete_event->data->object->amount_due / 100.0);
        
        $to = $email;
        $subject = 'BSM | Failed Payment';
        $message = 'Unfortunately your most recent invoice payment for '.$amount.' was declined.
        This could be due to a change in your card number or your card expiring, cancelation of your credit card,or the bank not recognizing the payment and taking action to prevent it.'."<br>".'Please update your payment information as soon as possible by logging in and going to settings:'."<br>".'https://www.blueskylinemarketing.com/login.php'."<br><br>".'Sincerely,'."<br>".'Oscar Estrada'."<br>".'<i>Blue Skyline Marketing CEO</i>';
        
        $headers  = "From: Blue Skyline Marketing <contact@blueskylinemarketing.com>\r\n"; 
        $headers .= "X-Sender: Blue Skyline Marketing <contact@blueskylinemarketing.com>\r\n";
        $headers .= 'X-Mailer: PHP/' . phpversion();
        $headers .= "X-Priority: 1\r\n"; // Urgent message!
        $headers .= "Return-Path: contact@blueskylinemarketing.com\r\n "; // Return path for errors
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=iso-8859-1\n";
        
        mail($to,$subject,$message,$headers);
        
        $downgrade_access_sql = "UPDATE clients SET access = '0' WHERE email = '$email'";
        
        mysqli_query($db_connect, $downgrade_access_sql);
        
//        print('payment-failed');
        http_response_code(200); // PHP 5.4 or greater
        exit();
        
    }
    
    if(isset($complete_event) && $type == 'customer.subscription.trial_will_end'){
        //Consider getting the email from the subscriber event.
        $complete_customer = \Stripe\Customer::retrieve($complete_event->data->object->customer);
        $customer_email = $complete_customer->email;
        
        $to = $customer_email;
        $subject = 'BSM | End of Free Trial';
        $message = 'Your free trial with us is coming to an end. I really hope you enjoyed and took full advantage of our service. If you plan to keep using our service you do not need to do anything. We will upgrade everything for you.'."<br>".'If you like to stop using our service, please contact us to prevent any unwanted charges to your credit card.'."<br><br>".'Sincerely,'."<br>".'Oscar Estrada'."<br>".'<i>Blue Skyline Marketing CEO</i>';
        
        $headers  = "From: Blue Skyline Marketing <contact@blueskylinemarketing.com>\r\n"; 
        $headers .= "X-Sender: Blue Skyline Marketing <contact@blueskylinemarketing.com>\r\n";
        $headers .= 'X-Mailer: PHP/' . phpversion();
        $headers .= "X-Priority: 1\r\n"; // Urgent message!
        $headers .= "Return-Path: contact@blueskylinemarketing.com\r\n "; // Return path for errors
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=iso-8859-1\n";
        
        mail($to,$subject,$message,$headers);
        
//        print('trial-will-end');
        http_response_code(200); // PHP 5.4 or greater
        exit();
        
    }
    
    
    if(isset($complete_event) && $type == 'invoice.created'){
        //Plan id - use this with specific plan
        $amount = $complete_event->data->object->amount_due;
        $complete_subscription = \Stripe\Subscription::retrieve($complete_event->data->object->lines->data[0]->id);
        $plan = $complete_subscription->items->data[0]->plan->id;
        
        if($plan == 'FREE_TRIAL' && $amount == 0){
            
            //invoice.created 
            $customer = \Stripe\Customer::retrieve($complete_subscription->customer);
            $email = $customer->email;
            
            $to = $email;
            $subject = 'BSM | Free Trial Accounts will be upgraded automatically.';
            $message = 'First off, Thank you for trying our SMS Outreach Free Trial. You are receving this email to inform you that all Free Trial subscriptions will be upgraded and charged automatically if they are not canceled by the end of the trial. You will also receive another email accouple hours before it is upgraded and your credit card is charged. '."<br>".'If you like to stop using our service, please contact us to prevent any unwanted charges to your credit card. Thank you.'."<br><br>".'Sincerely,'."<br>".'Oscar Estrada'."<br>".'<i>Blue Skyline Marketing CEO</i>';
            
            $headers  = "From: Blue Skyline Marketing <contact@blueskylinemarketing.com>\r\n"; 
            $headers .= "X-Sender: Blue Skyline Marketing <contact@blueskylinemarketing.com>\r\n";
            $headers .= 'X-Mailer: PHP/' . phpversion();
            $headers .= "X-Priority: 1\r\n"; // Urgent message!
            $headers .= "Return-Path: contact@blueskylinemarketing.com\r\n "; // Return path for errors
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=iso-8859-1\n";
            
            mail($to,$subject,$message,$headers);
            
//            print('invoice-created');
            http_response_code(200); // PHP 5.4 or greater
            exit();
        }
        
        http_response_code(200);
        exit();
        
    }
    
    
    if(isset($complete_event) && $type == 'invoice.payment_succeeded'){
        
        //Plan id - use this with specific plan
        $complete_subscription = \Stripe\Subscription::retrieve($complete_event->data->object->lines->data[0]->id);
        $plan = $complete_subscription->plan->id;
        
//        switch($plan){
//            case 'SML_BIZ':
//                $access_number = 1;
//                break;
//            case 'FREE_TRIAL':
//                $access_number = 1;
//                break;
//            case 'BSM_BETA':
//                $access_number = 1;
//                break;
//            default:
//                $access_number = 0;
//                break;
//        }
        
        $access_number = retrieveAccess_number($plan);
        
        $customer = \Stripe\Customer::retrieve($complete_event->data->object->customer);
        $email = $customer->email;
        
        $access_sql = "SELECT access FROM clients WHERE email = '$email'";
        
        $access_result = mysqli_query($db_connect, $access_sql);
        $access_return = mysqli_fetch_array($access_result);
        $result = $access_return['access'];
        
        if($result == 1){
            
            $update_access_sql = "UPDATE clients SET access = '$access_number' WHERE email = '$email'";
            
            mysqli_query($db_connect, $update_access_sql);
            
//            print('payment-made-access-updated');
            http_response_code(200);
            exit();
            
        }
        
//        print('payment-made-update-not-needed');
        http_response_code(200);
        exit();
            
    }
    
    //Left: Invoice Created != FREE_TRIAL, Subscription.canceled, xCustomer Created, Subscriber Created.
    //Below this not tested
    
    //
    
    if(isset($complete_event) && $type == 'customer.subscription.created'){
        
        try{
            
            $customer_id = $complete_event->data->object->customer;
            $subscriber_id = $complete_event->data->object->id;
            
            $check_info_sql = "SELECT * FROM stripe_clients WHERE customer_id = '$customer_id'";
            $check_info_results = mysqli_query($db_connect, $check_info_sql);
            $check_info_results = mysqli_num_rows($check_info_results);
            
            if($check_info_results == 1){
                
                //Subscription Base
                $complete_subscription = \Stripe\Subscription::retrieve($complete_event->data->object->id);
                $subscription_id = $complete_subscription->id;
                $customer_subscription = $complete_subscription->items->data[0]->plan->id;
                
                $complete_customer = \Stripe\Customer::retrieve($complete_subscription->customer);
                $customer_email = $complete_customer->email;
                
                $update_subscriber_id = "UPDATE stripe_clients SET subscriber_id = '$subscription_id', subscription_type = '$customer_subscription' WHERE email = '$customer_email'";
                
                mysqli_query($db_connect, $update_subscriber_id);
                http_response_code(200);
                exit();
                
            }else{
                
                $complete_customer = \Stripe\Customer::retrieve($customer_id);
                $customer_email = $complete_customer->email;
                
                $insert_sql = "INSERT INTO stripe_clients (id, email, customer_id, subscriber_id, subscription_type) ";
                $insert_sql.= "VALUES (DEFAULT, '$customer_email', '$customer_id', '$subscriber_id', '$subscription_type')";
                
                mysqli_query($db_connect, $insert_sql);
                http_response_code(200);
                
            }
            
        
        }catch(Exception $e){
            
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
    }
    
    if(isset($complete_event) && $type == 'charge.succeeded'){
        
        
        $customer = \Stripe\Customer::retrieve($complete_event->data->object->customer);
        $plan = $customer->subscriptions->data[0]->items->data[0]->plan->id;
        $client_customer_id = $customer->id;
        $email = $customer->email;
        
        $access_sql = "SELECT * FROM clients WHERE email = '$email' AND access = '0'";
        
        $result = mysqli_query($db_connect, $access_sql);
        
        $result = mysqli_num_rows($result);
//       Might not be needed        
//        switch($plan){
//            case 'SML_BIZ':
//                $access_number = 1;
//                break;
//            case 'FREE_TRIAL':
//                $access_number = 1;
//                break;
//            case 'BSM_BETA':
//                $access_number = 1;
//                break;
//            default:
//                $access_number = 0;
//                break;
//        }
        
        $access_number = retrieveAccess_number($plan);
        
        $result= 1;
        
        if($result == 1){
            
            $price = $complete_event->data->object->amount;
            
            if($price == 3000 || $price == 30.00){
                $client_subscription_type = 'BSM_BETA';
            }
            /*
            \Stripe\Subscription::create(array(
                "customer" => $client_customer_id,
                "plan" => $client_subscription_type,
            ));
            */
            $update_access_sql = "UPDATE clients SET access = '$access_number' WHERE email = '$email' LIMIT 1";
            
            mysqli_query($db_connect, $update_access_sql);
            
//            print('payment-made-access-updated');
            http_response_code(200);
            exit();
            
        }
        
//        print('payment-made-update-not-needed');
        http_response_code(200);
        exit();
            
        
        
    }

    //^code not testesd

} catch(\UnexpectedValueException $e) {
  // Invalid payload
  http_response_code(400); // PHP 5.4 or greater
  exit();
} catch(\Stripe\Error\SignatureVerification $e) {
  // Invalid signature
  http_response_code(400); // PHP 5.4 or greater
  exit();
}

//$type = $event->type;
//
//echo "id: $event<br> plan: $plan<br> type: $type <br>";
//var_dump($event);
http_response_code(200); // PHP 5.4 or greater


?>