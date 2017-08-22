<?php
require_once '../session.php';
require_once 'header.php';
?>

	<div id="forgot_page" class="container">
       <div class="row">
          <div class="container">
            <h1>Forgot your password?</h1>  
           <div class="col-md-6">
            <form class="lj-text-center" id='reset_password' method='POST'>
                <h3 class='text-center'>Enter your email address</h3>
                <div id='success'></div>
                <div id='error'></div> 
                <input type='email' id='verify_email' class='form-control'>
                <input type='submit' id='submit_request' class='btn btn-primary form-item' value='Reset Password'>
            </form>
           </div>    
          </div>
        </div>
      </div>



<?php require_once 'footer.php'; ?>
 
  <script src="https://js.stripe.com/v3/"></script>
  <script type="text/javascript">

    $('#submit_request').click(function(e){
       e.preventDefault();
        $('#error').empty();
        $('#submit_request').prop("disabled", true);
        
        var verify_email = $('#verify_email').val();
        
        if(!valid_email.test(verify_email)){
            $('#error').append("<i class='fa fa-times-circle'></i>Please enter your email address correctly.");
            
            $('#submit_request').prop("disabled", false);
            
            return;
        }
        
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                
                if(xmlhttp.responseText == 1001){
                
                    $('#success').append("<i class='fa fa-check-circle'></i>An email was sent to "+verify_email);
                    
                    setTimeout(function(){
                    window.location.href = "../login.php";}, 3000);
                    
                }else if(xmlhttp.responseText == 404){
                    
                    $('#error').append("<i class='fa fa-times-circle'></i>Please enter your email address correctly.");
                    
                    $('#submit_request').prop("disabled", false);
                
                }else{
                    $('#error').append("<i class='fa fa-times-circle'></i>We do not have any records for the email provided.");
                    
                    $('#submit_request').prop("disabled", false);
                }
            }
        }
        
        xmlhttp.open("POST", "../process/reset_password_process.php", true);
        xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        
        xmlhttp.send("verify_email="+verify_email);
        
        
    });

</script>
  </body>
</html>
      
