<?php
require_once '../session.php';
require_once 'header.php';
?>

	<div class="container">
       
       <?php
        if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])){
            // Verify data
            $email = mysqli_real_escape_string($db_connect, $_GET['email']); // Set email variable
            $hash = mysqli_real_escape_string($db_connect, $_GET['hash']); // Set hash variable
            
            $sql = "SELECT * FROM clients WHERE email='$email' AND hash='$hash'";
                         
            $search = mysqli_query($db_connect, $sql);
            $match  = mysqli_num_rows($search);
                      
            if($match == 1){
                
                ?>
                  <div class="row">
                      <div>
                         <?php if($row['active'] == 0){ ?>
                              <h1 class="lj-text-center">Congradulations, your email has been verified!</h1>
                          <?php }elseif($row['active'] == 1){ ?>
                              <h1 class="lj-text-center">Setup your new password</h1>
                          <?php } ?>
                      </div>
                  </div>
                  
                   <div class="row">
                       <div id="requirement_block" class="col-md-6 lj-text-left">
                           <h2 class="lj-text-left"><b>Requirements:</b></h2>
                           <ul>
                               <li>Minimum of 6 characters</li>
                               <li>Maximum of 20 characters</li>
                               <li>Atleast one uppercase letter</li>
                               <li>Atleast one lowercase letter</li>
                               <li>Atleast one special character</li>
                           </ul>
                       </div>
                       <div id="form-size"  class="col-md-6 lj-text-center">
                           
                       <form class="lj-text-center" id='password_update' method='POST'>
                            <h3 class='text-center'>Choose your password</h3>
                            <div id='success'></div>
                            <div id='error'></div> 
                            <input type='password' id='new_password' class='form-control'>
                            <h4>Verify Password</h4>
                            <input type='password' id='match_password' class='form-control'>
                            <input type='submit' id='submit_password' class='btn btn-primary form-item' value='Set Password'>
                        </form>
                       </div>
                   </div> 
            <?php
            }else{
                // No match -> invalid url or account has already been activated.
                echo '<div class="statusmsg">The url is either invalid or you already have activated your account.</div>';
            }
                 
        }else{
            // Invalid approach
            ?>
            <div class="row">
                <div>
                    <h1 class="lj-text-center">..Oops!</h1>
                </div>
            </div>
            <div class="row">
                <div class="statusmsg">
                    <h3 class="lj-text-center">Invalid approach, please use the link that has been sent to your email.</h3>
                </div>
            </div>
            <div class="row">
               <div class="lj-text-center">
                   <i class="fa fa-window-close-o fa-5x"></i>
               </div>
            </div>
            <?php
        }
        
        ?>
       
        
	</div>

<?php require_once('footer.php'); ?>
 
  <script src="https://js.stripe.com/v3/"></script>
  <script type="text/javascript">

    $('#submit_password').click(function(e){
       e.preventDefault();
        $('#error').empty();
        $('#submit_password').prop("disabled", true);
        
        var email = <?php echo json_encode($email); ?>;
        var hash = <?php echo json_encode($hash); ?>;
        var new_password = $('#new_password').val();
        var match_password = $('#match_password').val();
        
        if(new_password !== match_password){
            $('#error').append("<i class='fa fa-times-circle'></i>The passwords does not match.");

            $('#submit_password').prop("disabled", false);
            
            return;
        }
        
        if(!valid_password.test(new_password)){
            $('#error').append("<i class='fa fa-times-circle'></i>The password does not meet our requirements. Please try again.");
            
            $('#submit_password').prop("disabled", false);
            
            return;
        }
        if(!valid_password.test(match_password)){
            $('#error').append("<i class='fa fa-times-circle'></i>The password does not meet our requirements. Please try again.");
            
            $('#submit_password').prop("disabled", false);
            
            return;
        }
        
        
        
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                
                if(xmlhttp.responseText == 1001){
                
                    $('#success').append("<i class='fa fa-check-circle'></i>Your password has been updated. You will now be redirected.");
                    
                    setTimeout(function(){
                    window.location.href = "../login.php";}, 3000);
                    
                }else if(xmlhttp.responseText == 404){
                    $('#error').append("<i class='fa fa-times-circle'></i>Invalid Email and hash. Please contact us.");
                    
                    $('#submit_password').prop("disabled", false);
                    
                }else{
                    $('#error').append("<i class='fa fa-times-circle'></i>There is a problem connecting to our system, please retry with the same link or <a href='../setup/forgot_password.php'>Click Here</a>");
                    
                    $('#submit_password').prop("disabled", false);
                }
            }
        }
        
        xmlhttp.open("POST", "../process/new_key.php", true);
        xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        
        xmlhttp.send("email="+email+"&new_password="+new_password+"&hash="+hash);
        
        
    });

</script>
  </body>
</html>
      
