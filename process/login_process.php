<?php
require_once "../db_links.php";
require_once "../validation/back_end_validation.php";
session_start();

if(!$db_connect){
    header("location: login.php");
}else{

    
    
    $user = strip_tags(trim($_POST['login_user']));
    $password = strip_tags(trim($_POST['login_password']));
    
    $user = mysqli_real_escape_string($db_connect, $user);
    $password = mysqli_real_escape_string($db_connect, $password);
    
    if(!filter_var($user, FILTER_VALIDATE_EMAIL)){
        echo 404;
        exit;
    }
    
    if(!preg_match($valid_password, $password)){
        echo 404;
        exit;
    }
    
    
    $admin_sql = "SELECT * FROM admin WHERE email = '$user'";
    
    $client_sql = "SELECT * FROM clients WHERE email = '$user'";
    
    $admin_result = mysqli_query($db_connect, $admin_sql);
    $client_result = mysqli_query($db_connect, $client_sql);
    
    $admin_count = mysqli_num_rows($admin_result);
    $client_count = mysqli_num_rows($client_result);
    
    if($admin_count == 1 && $client_count == 0) {
        
        while($admin_info = $admin_result->fetch_assoc()){
            $admin_row = $admin_info;
        }
        
        $admin_key = password_verify($password, $admin_row['password']);
        
        if($admin_key){

            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            
            $_SESSION['email'] = $user;
            $_SESSION['count'] = 'admin';
            
            switch($row['access'])
            {
                case 1:
                case 2:
                case 3:
                    print("dashboard.php");
                    break;
                default:
                    print("logout.php");
                    break;
            }
        }else{
            echo 404;
            exit;
        }
        //why not just echo the access? :|
    }elseif($admin_count == 0 && $client_count == 1){
        
        while($client_info = $client_result->fetch_assoc()){
            $client_row = $client_info;
        }
        
        $client_key = password_verify($password, $client_row['password']);
        
        if($client_key){
         
            $_SESSION['count'] = 'clients';
            $_SESSION['email'] = $user;
            $_SESSION['client_verification'] = 'verified';

            
            print("dashboard.php");
        
        }else{
            echo 404;
            exit;
        }
        
    }else{
        echo 404;
    }

        
}
?>