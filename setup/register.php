<?php
require_once '../session.php';
require_once '../functions.php';
require_once 'header.php';

$_SESSION['plan'] = null;
$plan = $_GET['plan'];
$_SESSION['plan'] = $plan;

$page_content = subscription_data($plan);

if(!isset($page_content)){
    header("Location: ../index.php");
}else{
    $page_content = explode('~', $page_content);
    $header_line = $page_content[0];
    $pricing_plan = $page_content[1];
}

$_SESSION['subscription_type'] = null;

$_SESSION['subscription_type'] = $_GET['plan'];

?>
   
    <div id="subscription_content" class="container">
        
        <div class="row">
            <div class="lj-text-center">
                <?php print($header_line); ?>
            </div>
        </div>
     
      
        <div id="webAppPrev" class="col-md-6">
           
            <h3><b>Text Message Advantages</b></h3>
           <ul>
               <li>98 percent of text messages are opened and 90 percent of those text messages are opened within 3 minutes.</li>
               <li>The majority of people people don't leave their homes without their phones and its always within an arms-reach.</li>
               <li>Its also great because the 160 charaters force you to create a straight-to-the-point messages which your customers will love and appreciate.</li>
           </ul>
           
           <div id="myCarousel" class="carousel slide">
              
              <ol class="carousel-indicators">
                  <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                  <li data-target="#myCarousel" data-slide-to="1" class="active"></li>
              </ol>
               
               
               <div class="carousel-inner">
                   <figure class="item active">
                       
                   <img class="lj-align-top" src="../img/webAppPreview.png">    
                   </figure>
                   
                   <figure class="item">
                     
                    <?php 
                    
                       if(!isset($pricing_plan)){
                           header("location: ../index.php");
                       }else{
                           echo $pricing_plan;
                       }
                       
                    ?>
                      
                       
                   </figure>
                   
               </div>
           </div>
           
           
        </div>
       
        <div class="col-md-6">
        
        
            <div id="email_biz_error"><?php if(isset($_SESSION['email_biz_error'])){ echo "{$_SESSION['email_biz_error']}"; } ?></div>
            
            <form action="../process/reg_subscriber.php" method="post" id="payment-form">
               <div id="error"></div>
                <div class="form-group">
                    <label for="cardholder-first-name" class="form-item">First Name:</label>
                    <input name="cardholder-first-name" class="field form-control" type="text" id="firstN">
                </div>
                <div class="form-group">
                    <label for="cardholder-last-name" class="form-item">Last Name:</label>
                    <input name="cardholder-last-name" class="field form-control" type="text" id="lastN">
                </div>
                <div class="form-group">
                    <label for="cardholder-business-name" class="form-item">Business Name:</label>
                    <input type="text" name="cardholder-business-name" class="field form-control" id="businessN">
                </div>
                <div class="form-group">
                    <label for="cardholder-phone-number" class="form-item">Phone Number:</label>
                    <input type="tel" name="cardholder-phone-number" class="field form-control" id="phoneN">
                </div>
                <div class="form-group">
                    <label for="cardholder-email" class="form-item">Email:</label>
                    <input type="email" class="field form-control" name="cardholder-email" id="email">
                </div>
                <div class="form-group">
                    <label for="card-element" class="form-item">Credit or Debit Card</label>
                    <div id="card-element" class="form-control"></div>
                </div>
                <div id="card-errors" role="alert"></div>
                
                <button id="submit_payment" type="submit" class="submit btn btn-primary">Subscribe Now</button>
            </form>
        
        </div>    
        
	</div>
	
<?php require_once('footer.php'); ?>
 
  <script src="https://js.stripe.com/v3/"></script>
  <script>
      (function(){
          
          
          
          
          $('#myCarousel').carousel({
              interval: 4000
          });
                        
//          var stripe = Stripe('pk_test_34RoVWFJwUqy5QWraJicQmBv');
          var stripe = Stripe('pk_live_dCTBrBZRkyx9187EDyUTVfye');
          var elements = stripe.elements();
          
          var card = elements.create('card');
          
          card.mount('#card-element');
          
          function stripeTokenHandler(token){
              var form = document.getElementById('payment-form');
              var hiddenInput = document.createElement('input');
              hiddenInput.setAttribute('type', 'hidden');
              hiddenInput.setAttribute('name', 'stripeToken');
              hiddenInput.setAttribute('value', token.id);
              form.appendChild(hiddenInput);
              
              form.submit();
              
          }
        
          var form = document.getElementById('payment-form');
          form.addEventListener('submit', function(event){
              event.preventDefault();
              $('#error').empty();
              var submit_button = $('#submit_payment');
              
              submit_button.prop('disabled', true);
              
              var first_name = $('#firstN').val();
              var last_name = $('#lastN').val();
              var business_name = $('#businessN').val();
              var phone_number = $('#phoneN').val();
              var user_email = $('#email').val();
              
              if(!valid_name.test(first_name)){
                  $('#error').append("<i class='fa fa-times-circle'></i>Invalid first name.");
                  submit_button.prop('disabled', false);
                  return;
              }
              if(!valid_name.test(last_name)){
                  $('#error').append("<i class='fa fa-times-circle'></i>Invalid last name.");
                  submit_button.prop('disabled', false);
                  return;
              }
              if(!valid_business_name.test(business_name)){
                  $('#error').append("<i class='fa fa-times-circle'></i>Business name cannot have special characters. Minimum length of 3 characters and maximum length of 30 characters.");
                  submit_button.prop('disabled', false);
                  return;
              }
              if(!valid_phone_number.test(phone_number)){
                  $('#error').append("<i class='fa fa-times-circle'></i>Invalid phone number. EX 661-800-1234 or 6618001234.");
                  submit_button.prop('disabled', false);
                  return;
              }
              if(!valid_email.test(user_email)){
                  $('#error').append("<i class='fa fa-times-circle'></i>Invalid Email. EX noreply@blueskylinemarketing.com.");
                  submit_button.prop('disabled', false);
                  return;
              }
              
              
              
              stripe.createToken(card).then(function(result){
                    if(result.error){
                        
                        var errorElement = document.getElementById('card-errors');
                        
                        errorElement.textContent = result.error.message;
                    }else{
                        
                        stripeTokenHandler(result.token);
                    }
                  });
            
          });
          
          card.addEventListener('change', function(event){
              var displayError = document.getElementById('card-errors');
              
              if(event.error){
                  displayError.textContent = event.error.message;
              }else{
                  displayError.textContent = '';
                  var submit_button = $('#submit_payment');
                  submit_button.prop('disabled', false);
              }
              
          });

      })();
  </script>
  </body>
</html>
