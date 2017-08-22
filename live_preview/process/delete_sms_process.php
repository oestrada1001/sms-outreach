<?php
require_once('../../preview_links.php');
require_once('../validation/back_end_validation.php');

if(!isset($row['email'])){
    header("location: ../login.php");
}

if(!$db_connect){
    echo 404;
    exit;
}else{
    
    $type = $_POST['type'];
    
    if(!preg_match($valid_short_string, $type)){
        echo 404;
    }
    
    switch ($type){
        case 'null_default':
            
            $sql = $db_connect->prepare("UPDATE clients SET default_message = ? WHERE email = ? and business_name = ?");
            $sql->bind_param("sss", $null_default, $email, $businessName);
            
            $null_default = null;
            $email = $row['email'];
            $businessName = $row['business_name'];
            
            if($sql->execute()){
                echo 'client/view_sms.php';
                exit;
            }else{
                echo 'bad connection';
                exit;
            }
            
            break;
        case 'null_custom':
            
            $sql = $db_connect->prepare("UPDATE clients SET custom_message = ?, delivery_date = ? WHERE email = ? and business_name = ?");
            
            $sql->bind_param("ssss", $null_custom, $null_date, $email, $businessName);
            
            $null_custom = null;
            $null_date = null;
            $email = $row['email'];
            $businessName = $row['business_name'];
            
            if($sql->execute()){
                echo 'client/view_sms.php';
                exit;
            }else{
                echo 'bad connection';
                exit;
            }
            
            break;
        case 'null_inactive':
            $sql = $db_connect->prepare("UPDATE clients SET after_date = ?, after_message = ? WHERE email = ? and business_name = ?");
            
            $sql->bind_param("ssss", $null_date, $null_after, $email, $businessName);
            
            $null_date = null;
            $null_after = null;
            $email = $row['email'];
            $businessName = $row['business_name'];
            
            if($sql->execute()){
                echo 'client/view_sms.php';
                exit;
            }else{
                echo 'bad connection';
                exit;
            }
            break;
        case 'null_visit':
            $sql = $db_connect->prepare("UPDATE clients SET visit_goal = ?, visit_message = ? WHERE email = ? and business_name = ?");
            
            $sql->bind_param("ssss", $null_goal, $null_message, $email, $businessName);
            
            $null_goal = null;
            $null_message = null;
            $email = $row['email'];
            $businessName = $row['business_name'];
            
            if($sql->execute()){
                echo 'client/view_sms.php';
                exit;
            }else{
                echo 'bad connection';
                exit;
            }
            break;
        default:
            echo 'bad connection';
            break;
    }
    
}


?>