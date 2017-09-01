<?php
require_once '../db_links.php';
require_once '../functions.php';
require_once '../validation/back_end_validation.php';

$fingerprint = $_POST['fingerprint'];
$reAuthX = $_COOKIE['rainbow-bunny-cookie'];

$reAuthX_search = "SELECT * FROM credentials WHERE token = '$reAuthX' AND fingerprint = '$fingerprint' AND date_deleted >= NOW()";
$reAuthX_results = mysqli_query($db_connect, $reAuthX_search);
$reAuthX_rows = mysqli_num_rows($reAuthX_results);
$credentials = mysqli_fetch_array($reAuthX_results, MYSQLI_ASSOC);
$business_email = $credentials['email'];

//use email to get client values - basically same process as session.php
$client_account_sql = "SELECT * FROM clients WHERE email = '$business_email'";
$client_account_results = mysqli_query($db_connect, $client_account_sql);
$row = mysqli_fetch_array($client_account_results, MYSQLI_ASSOC);

//expire all previous tokens
$expire_currentToken_sql = "UPDATE credentials SET date_deleted = NOW() WHERE fingerprint = '$fingerprint'";
mysqli_query($db_connect, $expire_currentToken_sql);

//generate new token
$newToken = getToken(100);
$insert_newToken_sql = "INSERT INTO credentials(id, email, fingerprint, date_created, token, date_deleted) ";
$insert_newToken_sql.= "VALUES(DEFAULT, '$business_email', '$fingerprint', NOW(), '$newToken', NOW()+INTERVAL 3 DAY)";
mysqli_query($db_connect, $insert_newToken_sql);

//update cookie
$cookie_name = 'rainbow-bunny-cookie';
$cookie_value = $newToken;
setcookie($cookie_name, $cookie_value, time() + 86400*3, '/');

$password = $row['password'];
$verify_key = strip_tags(trim($_POST['verify_key']));

$row['business_name'];

if(!preg_match($valid_password, $verify_key)){
    echo "404";
    exit;
}

if(password_verify($verify_key, $password)){
    
    $_SESSION['client_verification'] = 'verified';

    print("../dashboard.php");
    exit;
}else{
    echo "404";
    exit;
}
//if($password != $verify_key){
//    echo 404;
//    exit;
//}elseif($password == $verify_key){
//    echo "../dashboard.php";
//    exit;
//}
?>
