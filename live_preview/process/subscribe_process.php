<?php
require_once('../../preview_links.php');
require_once('../validation/back_end_validation.php');

if(!isset($_COOKIE['livePreview'])){
    header("Location: ../login.php");
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

setcookie('subscriber_name', $sub_name, time()+900, "/");
setcookie('subscriber_number', $sub_cell, time()+900, "/");
setcookie('subscriber_email', $sub_email, time()+900, "/");

$sub_name = mysqli_real_escape_string($db_connect, $sub_name);
$sub_cell = mysqli_real_escape_string($db_connect, $sub_cell);
$sub_email = mysqli_real_escape_string($db_connect, $sub_email);

if(!$db_connect){ //database cannot connection

    die('field to connect to the server: ' . mysqli_connect_error());
    mysqli_close($db_connect);
    
}elseif(isset($_POST)){
    
    $remove = ['(', ')',' ','-','+', '.', 'x'];
    
    
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
                    
             
                    if($visit == $row['visit_goal']){
                            
                        echo "<b>TEXT SENT: </b> <i>{$row['visit_message']}</i>";
                            
                    }else{

                        echo "Your Record has been updated. || Visit $visit/{$row['visit_goal']}";
                            
                    }
                    
                    
                    
                }else{ //if number or email does not exist
        
                    //Record SQL
                    $default_message = $row['default_message'] . "<br><br> To unsubscribe reply with STOP or HELP for more infomation.";
                        
                        //successful message
                    echo "Thank you for subscribing to our specials. || Vist 1/{$row['visit_goal']}<br><br><b>TEXT SENT:</br> <i>$default_message</i>";
                    
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
                
                $default_message = $row['default_message'] . "<br><br> To unsubscribe reply with STOP or HELP for more infomation.";
                    
                    
                echo "Thank you for subscribing.<br><br><b>TEXT SENT:</b> <i>$default_message</i>";
                exit;
                
            } 
    
        }

    }


?>