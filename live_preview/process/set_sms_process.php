 <?php
require_once('../../preview_links.php');
require_once('../validation/back_end_validation.php');

if(!isset($row['email'])){
    header("location: ../login.php");
}

if(!$db_connect){
    echo 606;
    exit;
}else{
    
    
    $outbound_date = strip_tags(trim($_POST['outbound_date']));
    $outbound_message = strip_tags(trim($_POST['outbound_message']));
    $type = $_POST['type'];
    
    if(!preg_match($valid_short_string, $type)){
        echo 606;
        exit;
    }
    
    if(!preg_match($valid_sms_message, $outbound_message)){
        echo 404;
        exit;
    }
    
    if($type == 'custom_message'){
        if(!preg_match($valid_sms_date, $outbound_date)){
            echo 404;
            exit;
        }    
    }elseif($type == 'inactive_message'){
        if(!preg_match($valid_number, $outbound_date)){
            echo 404;
            exit;
        }
    }elseif($type == 'visit_message'){
        if(!preg_match($valid_number, $outbound_date)){
            echo 404;
            exit;
        }
    }
    
    
    $outbound_date = mysqli_real_escape_string($db_connect, $outbound_date);
    $outbound_message = mysqli_real_escape_string($db_connect, $outbound_message);
    
    if($outbound_date == '' || $outbound_message == ''){
        echo 404;
        exit;
    }else{
        
    
    switch ($type){
        case 'default_message':
            
            $sql = $db_connect->prepare("UPDATE clients SET default_message = ? WHERE email = ? and business_name = ?");
            $sql->bind_param("sss", $default_message, $email, $businessName);
            
            $default_message = $outbound_message;
            $email = $row['email'];
            $businessName = $row['business_name'];
            
            if($sql->execute()){
                echo "Your default message has been succesfully set.";
                exit;
            }else{
                echo 606;
                exit;
            }
            
            break;
        case 'custom_message':
            
            $sql = $db_connect->prepare("UPDATE clients SET custom_message = ?, delivery_date = ?, sms_sent = ? WHERE email = ? and business_name = ?");
            
            $sql->bind_param("sssss", $message, $deliveryDate, $sms_sent, $email, $businessName);
            
            $message = $outbound_message;
            $deliveryDate = $outbound_date;
            $sms_sent = 'no';
            $email = $row['email'];
            $businessName = $row['business_name'];
            
            if($sql->execute()){
                echo "Your custom message has been successfully set.";
                exit;
            }else{
                echo 606;
                exit;
            }
            
            break;
        case 'inactive_message':
            
            $sql = $db_connect->prepare("UPDATE clients SET after_date = ?, after_message = ? WHERE email = ? and business_name = ?");
            
            $sql->bind_param("ssss", $deliveryDate, $message, $email, $businessName);
            
            $deliveryDate = $outbound_date;
            $message = $outbound_message;
            $email = $row['email'];
            $businessName = $row['business_name'];
            
            if($sql->execute()){
                echo "Your inactive message has been successfully set.";
                exit;
            }else{
                echo 606;
                exit;
            }
            break;
        case 'visit_message':
            
            $sql = $db_connect->prepare("UPDATE clients SET visit_goal = ?, visit_message = ? WHERE email = ? and business_name = ?");
            
            $sql->bind_param("ssss", $visit_number, $visit_message, $email, $businessName);
            
            $visit_number = $outbound_date;
            $visit_message = $outbound_message;
            $email = $row['email'];
            $businessName = $row['business_name'];
            
            if($sql->execute()){
                echo "Your Visit Achievement Message has been succesfully set.";
                exit;
            }else{
                echo 606;
                exit;
            }
            
            break;
        default:
            echo 606;
            break;
    }
    
    
    }
    
}

?>
