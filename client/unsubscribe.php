<?php
require_once('../setup/header.php');
require_once('../session.php');

if(!isset($row['email'])){
    header("location: ../logout.php");
}
$client_verification = $_SESSION['client_verification'];
if($client_verification == 'denied'){
    header("location: https://www.blueskylinemarketing.com/logout.php");
    exit();
}elseif($client_verification != 'verified' && $client_verification != 'denied'){
    header("location: https://www.blueskylinemarketing.com/logout.php");
}

?>
<div id="unsubscribe_page" class="container">
    <div class="row text-center">
        <h1>Cancel Subscription</h1>
    </div>
    <div class="row">
        <div class="col-md-6">
            <h2>Thank you <?php echo $row['first_name']; ?></h2>
            <p>Thank you for using our SMS Outreach platform. Before you unsubscribe we would like to let you know what once you unsubscribe your database will be completely erased within 30 days. You will be able to prevent your database from being erased by re-activate your subscription and  by logging back in and making a payment. We're sad to see you go but we wish you the best. Thank you.</p>
        </div>
        <div class="col-md-6 text-center">
            <form method="post">
                <h3>Are you sure you want to unsubscribe?</h3>
                <br>
                <a href="../dashboard.php" class="btn btn-success" style="margin-top: 20px;">No, Take me back to my dashboard</a>
                <button id="unsubscribeNow" type="submit" value="<?php echo $row['email']; ?>" class="btn btn-danger" style="margin-top: 20px;">Yes, Unsubscribe me now</button>
            </form>
            <div id="success"></div>
            <div id="error"></div>
        </div>
    </div>
</div>

<script>

    (function(){
        
        var form = document.getElementsByTagName('form');
        var email = document.getElementById('unsubscribeNow');
        
        email.addEventListener('click', function(event){
            event.preventDefault();
            email.disabled = true;
            var client_email = email.value;
            var success = document.getElementById('success');
            var error = document.getElementById('error');
            
            
            
            xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function(){
                if(xmlhttp.readyState = 4 && xmlhttp.status == 200){
                    
                    var response = xmlhttp.responseText;
                    
                    if(response == 101){
                        success.innerHTML = "<i class='fa fa-check-circle'></i>You have been successfully unsubscribed. You will not be redirected";
                        
                        setTimeout(function(){
                            window.location.href = '../login.php';
                        }, 3000);
                        
                    }else{
                        error.innerHTML = "<i class='fa fa-times-circle'></i>Sorry, We are currently having issues. Please contact us to finish canceling your subscription.";
                    
                        email.disabled = false;
                        
                    }
                            
                    
                    
                    email.disabled = false;
                }
            }
            
            xmlhttp.open("POST", "../process/unsubscribe_process.php?email="+client_email, true);
            xmlhttp.setRequestHeader("Content-Type",  "application/x-www-form-urlencoded");
            xmlhttp.send("email="+client_email);
            
        });
        
    })();
        
</script>

<?php

require_once('../setup/footer.php');

?>