<?php
require_once '../session.php';

if(!isset($row['email'])){
    echo 404;
    exit;
    
}else{
    
    $adminCheck = "SELECT * FROM admin Where email = '$login_session'";
    $adminCheck = mysqli_query($db_connect, $adminCheck);
    $adminCheck = mysqli_num_rows($adminCheck);
    
    $clientCheck = "Select * FROM clients Where email = '$login_session'";
    $clientCheck = mysqli_query($db_connect, $clientCheck);
    $clientCheck = mysqli_num_rows($clientCheck);
    
    $direction = strtolower(strval($_POST['redirect_to']));
    
    if($adminCheck == 1 && $clientCheck == 0){
        
        switch($direction){
            case 'dashboard':
                echo 'home_process.php';
                break;
            case 'sms':
                echo 'admin/sms.php';
                break;
            case 'database':
                echo 'admin/database.php';
                break;
            case 'settings':
                echo 'admin/settings.php';
                break;
            default:
                echo 404;
                break;
        }
    }elseif($adminCheck == 0 && $clientCheck == 1){
        
        switch($direction){
            case 'dashboard':
                echo 'home_process.php';
                break;
            case 'view_cta':
                echo 'client/view_cta.php';
                break;
            case 'edit_cta':
                echo 'client/edit_cta.php';
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