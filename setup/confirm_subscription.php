<?php
require_once('header.php');
require_once('../db_links.php');


$subscriber_email = $_GET['email'];
$subscriber_hash = $_GET['hash'];

$find_email_sql = "SELECT * FROM bsm_subscribers WHERE hash = '$subscriber_hash' AND email = '$subscriber_email' AND subscribed = 'no'";

$row = mysqli_query($db_connect, $find_email_sql);

$count = mysqli_num_rows($row);

if($count >= 1){
    
    $row = mysqli_fetch_array($row, MYSQLI_ASSOC);
    
    $business_type = $row['business_type'];
    
    if($business_type !== 'special_offers'){
        $business_type = 'Free Marketing Strategies';
    }else{
        $business_type = ucwords(str_replace("_", " ", $business_type));
        
    }
    
    $update_subscribed_sql = "UPDATE bsm_subscribers SET subscribed = 'yes' WHERE email = '$subscriber_email'";
    
    mysqli_query($db_connect, $update_subscribed_sql);
    ?>
    <div id="confirm_subscription">
        
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12">
                <h1>Thank you for subscribing to our <?php echo $business_type; ?>.</h1>
            </div>
        </div>
        <div class="row">
           <h3>Thank you for sign up to receive our <?php echo $business_type; ?>.</h3>
        </div>
    </div>
    </div>
    
    <?php
}else{
    ?>
    <div id="confirm_subscription">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-12">
                    <h1>Oopps... Sorry for the inconvenience </h1>
                    <p>You are seeing this message because either this email has already been verified or this link is already expired.</p>
                </div>
            </div>
        </div>
    </div>   
    <?php
}
require_once('footer.php');
?>