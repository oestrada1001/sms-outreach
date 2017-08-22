<?php
//Not Used
require_once "../session.php";
require_once "../functions.php";


if(isset($_POST)){
    header("Location: register.php");
}

$access = $_POST['access'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$phone_number = $_POST['phone_number'];
$personal_email = $_POST['personal_email'];
$password = randomPassword();
$business_name = $_POST['business_name'];




if(!$db_connect){
    
    die('field to connect to the server: '. mysqli_connect_error());
    mysqli_close($db_connect);
    
}else{
    
    $sql = "SELECT * FROM clients WHERE email = '$personal_email'";
    
    $business_name = str_replace(' ', '_', strtolower($business_name));
            
    $sql1 = "SELECT * FROM information_schema.tables WHERE table_schema = '$business_name'";
    
    $result = mysqli_query($db_connect, $sql);
    $result = mysqli_num_rows($result);
    $result1 = mysqli_query($db_connect,$sql1);
    $result1 = mysqli_num_rows($result1);
    
    if($result >= 1 || $result1 >= 1){ //If true record already exist
        echo "Records already exist.";
    }else{
        
        $hash = md5(rand(0,1000));
        
        $sql = "INSERT INTO clients (id, access, first_name, last_name, phone_number, email, password, business_name, hash, active) ";
        $sql.= "VALUES (DEFAULT,'$access', '$first_name', '$last_name', '$phone_number', '$personal_email', '$password', '$business_name', '$hash', DEFAULT)";
        
        mysqli_query($db_connect, $sql);
            
//        $sql = "CREATE TABLE $business_name";
//        $sql.= "(id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, ";
//        $sql.= "name VARCHAR(30) NOT NULL, ";
//        $sql.= "phone_number VARCHAR(15) NOT NULL, ";
//        $sql.= "email VARCHAR(50) NOT NULL, ";
//        $sql.= "visit TINYINT(5) NOT NULL DEFAULT '1', ";
//        $sql.= "last_check_in DATE NOT NULL, ";
//        $sql.= "registration_date DATE NOT NULL)";
//        
//        $to = $personal_email;
//        $subject = 'Signup | Verify your email';
//        $message = 'Thanks for signing up! Click the link to activate your email and set your password.<br> Please click this link to activate your account: http://www.blueskylineapp.com/verify.php?email='.$personal_email.'&hash='.$hash.'<br>Sincerely,<br>Blue Skyline Agency';
//        
//        $headers = 'From: noreply@blueskylineagency.com' . "\r\n";
//        
//        mail($to,$subject,$message,$headers);
                
        if(mysqli_query($db_connect, $sql)){
            echo "Table successfully created.";
        }
    }
            
}

?>