<?php
require_once('../stripe4-x/stripe-php-4.13.0/init.php');
require_once('../session.php');
require_once('../functions.php');
require_once('../validation/back_end_validation.php');
\Stripe\Stripe::setApiKey("sk_live_1teGqmhHeAwmjkmdkINqxbqp");
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
    
    $access = 1;
    
    
    
    
    $first_name = strip_tags(trim($_POST['cardholder-first-name']));
    $last_name = strip_tags(trim($_POST['cardholder-last-name']));
    $phone_number = strip_tags(trim($_POST['cardholder-phone-number']));
    $email = strip_tags(trim($_POST['cardholder-email']));
    $password = randomPassword();
    $businessName = strip_tags(trim($_POST['cardholder-business-name']));
    
    if(!preg_match($valid_name, $first_name)){
        $_SESSION['email_biz_error'] = 'Invalid First Name.';
        header("Location: ../setup/register.php");
        exit;
    }
    if(!preg_match($valid_name, $last_name)){
        $_SESSION['email_biz_error'] = 'Invalid Last Name.';
        header("Location: ../setup/register.php");
        exit;
    }
    if(!preg_match($valid_phone_number, $phone_number)){
        $_SESSION['email_biz_error'] = 'Invalid Phone Number.';
        header("Location: ../setup/register.php");
        exit;
    }
    if(!preg_match($valid_email, $email)){
        $_SESSION['email_biz_error'] = 'Invalid Email.';
        header("Location: ../setup/register.php");
        exit;
    }
    if(!preg_match($valid_business_name, $businessName)){
        $_SESSION['email_biz_error'] = 'Invalid Business Name.';
        header("Location: ../setup/register.php");
        exit;
    }
    
    
    
    $first_name = mysqli_real_escape_string($db_connect, $first_name);
    $last_name = mysqli_real_escape_string($db_connect, $last_name);
    $phone_number = mysqli_real_escape_string($db_connect, $phone_number);
    $email = mysqli_real_escape_string($db_connect, $email);
    $businessName = mysqli_real_escape_string($db_connect, $businessName);
    
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
        header("Location: ../setup/register.php");
        exit;
    }elseif(($email_result >= 1 || $admin_result >= 1) && $business_result == 0){
        $_SESSION['email_biz_error'] = 'Email already taken, please try again.';
        header("Location: ../setup/register.php");
        exit;
    }elseif($email_result == 0 && $business_result >= 1 && $admin_result == 0){
        $_SESSION['email_biz_error'] = 'Business name already taken, please try again.';
        header("Location: ../setup/register.php");
        exit;
    }elseif($email_result == 0 && $business_result == 0 && $admin_result == 0){
        $_SESSION['email_biz_error'] = null;
        
        try{
        
            // Token is created using Stripe.js or Checkout!
            // Get the payment token submitted by the form:
            $token = $_POST['stripeToken'];
            
            $customer = \Stripe\Customer::create(array(
                "email" => $email,
                "source" => $token
            ));
            
            \Stripe\Subscription::create(array(
                "customer" => $customer->id,
                "plan" => "BSM_BETA"
            ));
            
            $hash = md5(rand( 0, 1000));
        
            $client_sql = "INSERT INTO clients (id, access, first_name, last_name, phone_number, email, password, business_name, delivery_date, default_message, custom_message, after_date, after_message, hash, active, visit_goal, sms_sent, collect_emails) ";
            $client_sql.= "VALUES (DEFAULT, $access, '$first_name', '$last_name', '$phone_number', '$email', '$password', '$businessName', DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, '$hash', DEFAULT, DEFAULT, DEFAULT, DEFAULT)";
        
            mysqli_query($db_connect, $client_sql);
            
            $business_sql = "CREATE TABLE $business_name";
            $business_sql.= "(id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, ";
            $business_sql.= "name VARCHAR(30) NOT NULL, ";
            $business_sql.= "phone_number VARCHAR(15) NOT NULL, ";
            $business_sql.= "email VARCHAR(50) NULL, ";
            $business_sql.= "visit TINYINT(5) NOT NULL DEFAULT '1', ";
            $business_sql.= "last_check_in DATE NOT NULL, ";
            $business_sql.= "registration_date DATE NOT NULL)";
            
            mysqli_query($db_connect, $business_sql);
            
            $business_history_table = $business_name ."_message_history";
            
            $business_history = "CREATE TABLE $business_history_table";
            $business_history.= "(id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, ";
            $business_history.= "delivery_date DATE NOT NULL, ";
            $business_history.= "message_delivered VARCHAR(200) NOT NULL, ";
            $business_history.= "total_messages_sent SMALLINT NOT NULL DEFAULT '0')";
            
            mysqli_query($db_connect, $business_history);
            
            $to = $email;
            $subject = 'Signup | Verify your email';
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
            
        }catch(Stripe_CardError $e) {
            $error1 = $e->getMessage();
            print($error1);
            exit;
        } catch (Stripe_InvalidRequestError $e) {
            // Invalid parameters were supplied to Stripe's API
            $error2 = $e->getMessage();
            print($error2);
            exit;
        } catch (Stripe_AuthenticationError $e) {
            // Authentication with Stripe's API failed
            $error3 = $e->getMessage();
            print($error3);
            exit;
        } catch (Stripe_ApiConnectionError $e) {
            // Network communication with Stripe failed
            $error4 = $e->getMessage();
            print($error4);
            exit;
        } catch (Stripe_Error $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            $error5 = $e->getMessage();
            print($error5);
            exit;
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            $error6 = $e->getMessage();
            print($error6);
            exit;
        }
    }else{
        $_SESSION['email_biz_error'] = 'We encounted an error. Please try in a few minutes.';
        header("Location: ../setup/register.php");
        exit;
    }
}



?>