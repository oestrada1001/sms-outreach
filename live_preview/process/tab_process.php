<?php
require_once '../../preview_links.php';

if(!isset($row['email'])){
    
    echo 404;
    exit;
    
}else{
    
    
    $clientCheck = "Select * FROM clients Where email = '$login_session'";
    $clientCheck = mysqli_query($db_connect, $clientCheck);
    $clientCheck = mysqli_num_rows($clientCheck);
    
    $direction = strtolower(strval($_POST['redirect_to']));
    
    if($clientCheck == 1){
        
        switch($direction){
            case 'dashboard':
                echo 'home_process.php';
                break;
            case 'view_sms':
                echo 'client/view_sms.php';
                break;
            case 'edit_sms':
                echo 'client/edit_sms.php';
                break;
            case 'database':
                echo 'client/database.php';
                break;
            case 'settings':
                echo 'client/settings.php';
                break;
            default:
                echo 404;
                break;
        }
        
    }else{
        //
        echo 404;
        exit;
    }
}
?>