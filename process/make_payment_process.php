<?php
//require_once('../session.php');
require_once '../db_links.php';
require_once('../stripe4-x/stripe-php-4.13.0/init.php');
\Stripe\Stripe::setApiKey("sk_live_1teGqmhHeAwmjkmdkINqxbqp");
$sql = "SELECT * FROM clients WHERE email = 'hello@oeinnovations.com'";
$ses_sql = mysqli_query($db_connect, $sql);
$row = mysqli_fetch_array($ses_sql, MYSQLI_ASSOC);
$login_session = $row['email'];

$stripe_client_sql = "SELECT * FROM stripe_clients WHERE email = '$login_session'";
$client_results = mysqli_query($db_connect, $stripe_client_sql);
$stripe = mysqli_fetch_array($client_results, MYSQLI_ASSOC);
    
    $method = $_POST['method'];
    $customer_id = (string)$stripe['customer_id'];
    $subscriber_id = (string)$stripe['subscriber_id'];
    $subscription_type = (string)$stripe['subscription_type'];
    $stripeToken = $_POST['stripeToken'];

    if($method == 'new_payment'){
        try{
            
            switch($subscription_type){
                case 'FREE_TRIAL':
                    $price = 3000;
                    $subscription_type = 'BSM_BETA';
                    break;
                case 'BSM_BETA':
                    $price = 3000;
                    break;
            }
            
            $customer = \Stripe\Customer::retrieve($customer_id);
            $customer->source = $stripeToken;
            $customer->save();
            
            $charge = \Stripe\Charge::create(array(
                "amount" => $price,
                "currency" => "usd",
                "customer" => $customer_id
            ));
            
            \Stripe\Subscription::create(array(
                "customer" => $customer_id,
                "plan" => $subscription_type,
            ));
            
            
            echo 101;
            exit();
        }catch(Exception $e){
            
            echo 606;
            exit();
        }
        
    }else if($method == 'old_payment'){
        try{
            
            switch($subscription_type){
                case 'FREE_TRIAL':
                    $price = 3000;
                    break;
                case 'BSM_BETA':
                    $price = 3000;
                    break;
            }
            
            $charge = \Stripe\Charge::create(array(
                "amount" => $price,
                "currency" => "usd",
                "customer" => $customer_id
            ));
            
            echo 101;
            exit();
        }catch(Exception $e){
            
            echo 606;
            exit();
        }
        
    }else{
        
        echo 404;
        exit();
    }
    

?>
