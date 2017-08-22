<?php
require_once('../session.php');
require_once('../stripe4-x/stripe-php-4.13.0/init.php');
require_once('../validation/back_end_validation.php');
\Stripe\Stripe::setApiKey("sk_live_1teGqmhHeAwmjkmdkINqxbqp");

if(!isset($row['email'])){
    header("location: ../logout.php");
}

$email = $_POST['email'];

if(!preg_match($valid_email, $email)){
    echo 404;
    exit;
}

$stripe_client = "SELECT * FROM stripe_clients WHERE email = '$email'";
$stripe_client = mysqli_query( $db_connect, $stripe_client);
$stripe_client = mysqli_fetch_array($stripe_client, MYSQLI_ASSOC);

$client_subscription = $stripe_client['subscriber_id'];

    try{
        
        $subscription = \Stripe\Subscription::retrieve($client_subscription);
        $subscription->cancel();
        
        $downgrade_access_sql = "UPDATE clients SET access = '0' WHERE email = '$email'";
        mysqli_query($db_connect, $downgrade_access_sql);
        
        echo 101;

    }catch(Exception $e){
        
        echo 404;
        
    }


?>