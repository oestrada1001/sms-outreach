<?php
require_once '../session.php';
require_once '../validation/back_end_validation.php';

$new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
$email = $_POST['email'];
$hash = $_POST['hash'];

if(!preg_match($valid_email, $email)){
    echo 404;
    exit;
}
if(!preg_match($valid_sms_message, $hash)){
    echo 404;
    exit;
}

if(!$db_connect){
    echo "404";
}else{

    $sql = "SELECT * FROM clients WHERE email = '$email' AND hash = '$hash' LIMIT 1";
    
    $search = mysqli_query($db_connect, $sql);
    $match = mysqli_num_rows($search);
    
    if($match == 1){
        
        
        $hash = md5(rand(0,1000));
                
        $sql = "UPDATE clients SET password = '$new_password', active = '1', hash='$hash' WHERE email = '$email' LIMIT 1";
        
        if(mysqli_query($db_connect, $sql)){
            echo "1001";    
        }else{
            echo "404";
        }
        
    }else{
        echo "404";
    }

}
?>