<?php
require_once('../stripe4-x/stripe-php-4.13.0/init.php');
require_once('../twilio-php-master/Twilio/autoload.php');
require_once('../session.php');
require_once('../functions.php');
require_once('../validation/back_end_validation.php');
\Stripe\Stripe::setApiKey("sk_live_1teGqmhHeAwmjkmdkINqxbqp");

use Twilio\Rest\Client;

session_start();


// Set your secret key: remember to change this to your live secret key in production
// See your keys here: https://dashboard.stripe.com/account/apikeys


if(!isset($_POST)){
    header('Location: ../setup/register.php');
}


if(!$db_connect){
    die('field to connect to the server: '. mysqli_connect_error());
    mysqli_close($db_connect);
}else{
    
    $first_name = strip_tags(trim($_POST['cardholder-first-name']));
    $last_name = strip_tags(trim($_POST['cardholder-last-name']));
    $phone_number = strip_tags(trim($_POST['cardholder-phone-number']));
    $email = strip_tags(trim($_POST['cardholder-email']));
    $password = randomPassword();
    $businessName = strip_tags(trim($_POST['cardholder-business-name']));
    $subscription_type = strip_tags(trim($_SESSION['subscription_type']));
    
    if(!preg_match($valid_name, $first_name)){
        $_SESSION['email_biz_error'] = 'Invalid First Name.';
        header("Location: ../setup/register.php?plan=" .$_SESSION['plan']);
        exit;
    }
    if(!preg_match($valid_name, $last_name)){
        $_SESSION['email_biz_error'] = 'Invalid Last Name.';
        header("Location: ../setup/register.php?plan=" .$_SESSION['plan']);
        exit;
    }
    if(!preg_match($valid_phone_number, $phone_number)){
        $_SESSION['email_biz_error'] = 'Invalid Phone Number.';
        header("Location: ../setup/register.php?plan=" .$_SESSION['plan']);
        exit;
    }
    if(!preg_match($valid_email, $email)){
        $_SESSION['email_biz_error'] = 'Invalid Email.';
        header("Location: ../setup/register.php?plan=" .$_SESSION['plan']);
        exit;
    }
    if(!preg_match($valid_business_name, $businessName)){
        $_SESSION['email_biz_error'] = 'Invalid Business Name.';
        header("Location: ../setup/register.php?plan=" .$_SESSION['plan']);
        exit;
    }
//    if(!preg_match($valid_short_string, $subscription_type)){
//        $_SESSION['email_biz_error'] = 'Invalid Subscription.';
//        header('Location: ../setup/register.php');
//        exit;
//    }
    
    
    $first_name = mysqli_real_escape_string($db_connect, $first_name);
    $last_name = mysqli_real_escape_string($db_connect, $last_name);
    $phone_number = mysqli_real_escape_string($db_connect, $phone_number);
    $email = mysqli_real_escape_string($db_connect, $email);
    $businessName = mysqli_real_escape_string($db_connect, $businessName);
    $subscription_type = mysqli_real_escape_string($db_connect, $subscription_type);
    
    $email_sql = "SELECT * FROM clients WHERE email = '$email'";
    $admin_sql = "SELECT * FROM admin WHERE email = '$email'";

    $business_name = str_replace(' ', '_', strtolower($businessName));

    $business_sql = "SHOW TABLES LIKE '$business_name'";

    $email_result = mysqli_query($db_connect, $email_sql);
    $email_result = mysqli_num_rows($email_result);

    $business_result = mysqli_query($db_connect, $business_sql);
    $business_result = mysqli_num_rows($business_result);
    
    $admin_result = mysqli_query($db_connect, $admin_sql);
    $admin_result = mysqli_num_rows($admin_result);
    
    
    if(($email_result >= 1 || $admin_result >=1) && $business_result >= 1){
        $_SESSION['email_biz_error'] = 'Email and business name already taken, please try again.';
        header("Location: ../setup/register.php?plan=" .$_SESSION['plan']);
        exit;
    }elseif(($email_result >= 1 || $admin_result >= 1) && $business_result == 0){
        $_SESSION['email_biz_error'] = 'Email already taken, please try again.';
        header("Location: ../setup/register.php?plan=" .$_SESSION['plan']);
        exit;
    }elseif($email_result == 0 && $business_result >= 1 && $admin_result == 0){
        $_SESSION['email_biz_error'] = 'Business name already taken, please try again.';
        header("Location: ../setup/register.php?plan=" .$_SESSION['plan']);
        exit;
    }elseif($email_result == 0 && $business_result == 0 && $admin_result == 0){
    
        $_SESSION['email_biz_error'] = null;
            
        $subscription_results = subscription_plan($subscription_type);
        
        if(!isset($subscription_results)){
            $_SESSION['email_biz_error'] = "We are currently having issues, please try again later.";
            header("Location: ../setup/register.php?=plan=" .$_SESSION['plan']);
            exit();
        }else{
            $subscription_results = explode('-', $subscription_results);
            $access= intval($subscription_results[0]);
            $type= $subscription_results[1];
        }
        
        try{
            
            $sid = 'AC9aa8585446af8cafc529ba2f3bff7511';
            $twilio_token = 'c7bc34c5cc067cae53726cfc22419cdf';
            $twilio = new Client($sid, $twilio_token);
            
            //Creating the client a Messaging Service
            $service = $twilio->messaging->v1->services->create($businessName, array('inboundRequestUrl' => "https://www.blueskylinemarketing.com/process/twilio_confirmation.php", 'fallbackUrl' => "https://www.blueskylinemarketing.com/process/twilio_confirmation.php"));
            
            //    Their Messaging Service Id
            $MSiD = $service->sid;
            
            //Getting their area code.
            $phonenumber_array = str_split($phone_number, 3);
            $local_area = $phonenumber_array[0];
            $areacode = $twilio->availablePhoneNumbers('US')->local->read(
                array("areaCode" => "$local_area")
            );
    
            //Buying a local phone number
            $purchase_number = $twilio->incomingPhoneNumbers->create(
                array("phoneNumber" => $areacode[0]->phoneNumber)
            );
    
            $PNiD = $purchase_number->sid;
            
            //Adding Phone Number to their Messaging Service
            $addingNumber = $twilio->messaging->v1->services("$MSiD")->phoneNumbers->create("$PNiD");
            
            //Actual Phone Number
            $actual_number = $purchase_number->phoneNumber;
            
            $twilio_sql = "INSERT INTO twilio_service (id, business_name, email, message_service_id, initial_phone_number) ";
            $twilio_sql.= "VALUES (DEFAULT, '$businessName', '$email', '$MSiD', '$actual_number')";
    
            mysqli_query($db_connect, $twilio_sql);
            
            // Token is created using Stripe.js or Checkout!
            // Get the payment token submitted by the form:
            $token = $_POST['stripeToken'];
            
            $customer = \Stripe\Customer::create(array(
                "email" => $email,
                "source" => $token
            ));
            
            \Stripe\Subscription::create(array(
                "customer" => $customer->id,
                "plan" => $type
            ));
            
            $hash = md5(rand( 0, 1000));
            
            $client_sql = "INSERT INTO clients (id, access, first_name, last_name, phone_number, email, password, business_name, delivery_date, default_message, custom_message, after_date, after_message, hash, active, visit_goal, sms_sent, collect_emails, subscription_message, loyalty_message, subscription_link, monthly_texts) ";
            $client_sql.= "VALUES (DEFAULT, $access, '$first_name', '$last_name', '$phone_number', '$email', '$password', '$businessName', DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, '$hash', DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT)";
        
            mysqli_query($db_connect, $client_sql);
            
               
            $business_sql = "CREATE TABLE $business_name";
            $business_sql.= "(id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, ";
            $business_sql.= "name VARCHAR(30) NOT NULL, ";
            $business_sql.= "phone_number VARCHAR(15) NOT NULL, ";
            $business_sql.= "email VARCHAR(50) NULL, ";
            $business_sql.= "visit TINYINT(5) NOT NULL DEFAULT '1', ";
            $business_sql.= "last_check_in DATE NOT NULL, ";
            $business_sql.= "registration_date DATE NOT NULL ";
            $business_sql.= "confirmed VARCHAR(3) NOT NULL DEFAULT 'no')";
            
            mysqli_query($db_connect, $business_sql);
            
            $business_history_table = $business_name ."_message_history";
            
            $business_history = "CREATE TABLE $business_history_table";
            $business_history.= "(id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, ";
            $business_history.= "delivery_date DATE NOT NULL, ";
            $business_history.= "message_delivered VARCHAR(200) NOT NULL, ";
            $business_history.= "total_messages_sent SMALLINT NOT NULL DEFAULT '0')";
            
            mysqli_query($db_connect, $business_history);
            
            $to = $email;
            $subject = 'BSM | Verify your email';
            $message = 'Thanks for signing up! Click the link to activate your email and set your password.'. "<br>". 'Click the link below to activate your account or copy and paste it to the address bar:'. "<br><br>". ' https://www.blueskylinemarketing.com/setup/verify.php?email='.$email.'&hash='.$hash."<br><br>".'Sincerely,'. "<br>". 'Oscar Estrada'. "<br>". '<i>Blue Skyline Marketing CEO</i>';
            
            $headers  = "From: Blue Skyline Marketing <contact@blueskylinemarketing.com>\r\n"; 
            $headers .= "X-Sender: Blue Skyline Marketing <contact@blueskylinemarketing.com>\r\n";
            $headers .= 'X-Mailer: PHP/' . phpversion();
            $headers .= "X-Priority: 1\r\n"; // Urgent message!
            $headers .= "Return-Path: contact@blueskylinemarketing.com\r\n "; // Return path for errors
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=iso-8859-1\n";
            
            mail($to,$subject,$message,$headers);
            
            $_SESSION['email'] = $email;
            $_SESSION['count'] = 'clients';
            
            header('location: ../setup/confirmAccount.php');
            exit;
            
//        }catch(Stripe_CardError $e) {
//            $error1 = $e->getMessage();
//            print($error1);
//            exit;
//        } catch (Stripe_InvalidRequestError $e) {
//            // Invalid parameters were supplied to Stripe's API
//            $error2 = $e->getMessage();
//            print($error2);
//            exit;
//        } catch (Stripe_AuthenticationError $e) {
//            // Authentication with Stripe's API failed
//            $error3 = $e->getMessage();
//            print($error3);
//            exit;
//        } catch (Stripe_ApiConnectionError $e) {
//            // Network communication with Stripe failed
//            $error4 = $e->getMessage();
//            print($error4);
//            exit;
//        } catch (Stripe_Error $e) {
//            // Display a very generic error to the user, and maybe send
//            // yourself an email
//            $error5 = $e->getMessage();
//            print($error5);
//            exit;
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
//            $error6 = $e->getMessage();
//            print($error6);
            $_SESSION['email_biz_error'] = 'We are currently having problems. Please try in a few minutes.';
            header("Location: ../setup/register.php?plan=". $_SESSION['plan']);
            exit;
        }
    }else{
        $_SESSION['email_biz_error'] = 'We encounted an error. Please try in a few minutes.';
        header("Location: ../setup/register.php?plan=" . $_SESSION['plan']);
        exit;
    }
}



?>