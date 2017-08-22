<?php

require_once('../session.php');

if(!isset($row['email'])){
    header("Location: ../index.php");
}

require_once 'header.php';

?>
    
    <div class="container">
        <div class="row">
            <div class="col-md-12 lj-text-center">
                <h1>Welcome to Blue Skyline Marketing!<br><br><i class="fa fa-handshake-o fa-2x"></i></h1>
            </div>
        </div>
        <div class="row">
        <div class="col-md-6">
            
        </div>
        <div class="col-md-6">
            <h2>Instrustions:</h2>
            <p>We sent you an email to: <b><?php print_r($row['email']); ?></b>. The email contains a special link to confirm your account. The link will take you to a page where you will be able to set your account's password. If you do not find the email, check your spam folder, if you still do not find the email contact us so we can help you finish setting up your account.</p>
            
            <p>To-do list:</p>
            <ul>
                <li><i class="fa fa-check-square-o fa-lg"></i>Check email account.</li>
                <li><i class="fa fa-check-square-o fa-lg"></i>Find our email with special link.</li>
                <li><i class="fa fa-check-square-o fa-lg"></i>Click special link.</li>
                <li><i class="fa fa-check-square-o fa-lg"></i>Set new password.</li>
            </ul>
            <p><b>And Login.</b></p>
        </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h2>Thank you <b><?php print_r($row['first_name']); ?></b>.</h2>
                <p>First and foremost, We want to thank you for your business and hope we can exceed any expectations you might have. If you are having any problems with our Web Application please contact us and we will fix whatever problem you are having. Our ultimate goal is to help you achieve getting more customers to your business in-order to be successful. We will be adding different marketing tactics and strageties for all our members so please keep an eye out for that. <br><br><i>-Sincerely,<br><br>Oscar Estrada<br>Blue Skyline Marketing CEO</i></p>
            </div>
            <div class="col-md-6">
                
                
            </div>
        </div>
    </div>

<?php require_once 'footer.php'; ?>
  <script src="https://js.stripe.com/v3/"></script>
  </body>
</html>
      
    