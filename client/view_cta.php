<?php
require_once('../session.php');

if(!isset($row['email'])){
    header("location: ../index.php");
}
if($row['access'] == 0){
    header("location: ../setup/unpaid_account.php");
}
$client_verification = $_SESSION['client_verification'];
if($client_verification == 'denied'){
    header("location: https://www.blueskylinemarketing.com/logout.php");
    exit();
}elseif($client_verification != 'verified' && $client_verification != 'denied'){
    header("location: https://www.blueskylinemarketing.com/logout.php");
}


if($row['subscription_message'] == null){
    $subscription_message = 'You do not have Headline &#35;1 set.';
}else{
    $subscription_message = $row['subscription_message'];
}
if($row['loyalty_message'] == null){
    $loyalty_message = 'You do not have Healine &#35;2 set.';
}else{
    $loyalty_message = $row['loyalty_message'];
}

$email = $row['email'];
$monthly_texts = $row['monthly_texts'];

$twilio_sql = "SELECT * FROM twilio_service WHERE email = '$email'";
$twilio_results = mysqli_query($db_connect, $twilio_sql);
$twilio_row = mysqli_fetch_array($twilio_results, MYSQLI_ASSOC);
$initial_number = $twilio_row['initial_phone_number'];
$initial_number = preg_replace('/(\+1)?/', '', $initial_number);
$initial_number = str_split($initial_number, 3);
$initial_number = $initial_number[0] . '-' . $initial_number[1] . '-' . $initial_number[2] . $initial_number[3]; 

$form_width = 6;
$form_style = 'none';

if(!isset($row['subscription_message']) && !isset($row['loyalty_message'])){
    $form_width = 12;
    $form_style = 'none';
}

?>
<body id="view_cta">
    <div class="row">
        <div class="col-md-6">
            <h3><?php echo $subscription_message; ?></h3>
            <input type="submit" class="btn btn-danger" id="null_headline_1" value="Delete Headline &#35;1">
        </div>
        <div class="col-md-6">
            <h3><?php echo $loyalty_message; ?></h3>
            <input type="submit" class="btn btn-danger" id="null_headline_2" value="Delete Headline &#35;2">
        </div>
    </div>
<div class="row text-center">
   <div style="display: inline-block;">

    
<div class="col-md-<?php echo $form_width; ?> register-box">
  <div class="register-logo">
    <?php print_r($row['business_name']); ?>
  </div>

  <div class="register-box-body">
    <p class="login-box-msg">Subscribe to our specials and promotions.</p>
    <form method="POST" id="login-form">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Full name" id="sub_name" disabled>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="number" class="form-control" placeholder="Cellphone Number" maxlength="10" id="sub_cell" disabled>
        <span class="glyphicon glyphicon-phone form-control-feedback"></span>
      </div>
      <?php
            if($row['collect_emails'] == 'yes'){
            ?>    
              <div class="form-group has-feedback">
                <input type="email" class="form-control" placeholder="Email" id="sub_email" disabled>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
              </div> 
            <?php
            }elseif($row['collect_emails'] == 'no'){
                ?><input type="email" id="sub_email" value="no" style="display: none;" disabled><?php
            }
        ?>
        <div class="col-xs-12">
          <input type="submit" class="btn btn-primary btn-block btn-flat" value="Submit" disabled>
        </div>
      <div class="row">
        <div class="col-xs-12">
          <div class="checkbox icheck">
            <label>
              Message and data rates may apply. Expect Approx. <? if($monthly_texts == null){echo 0;}else{ echo $monthly_texts; }?> Texts/Month. To opt-out text <b>STOP</b> to <?php echo $initial_number; ?>. For more information or terms and conditions please visit <a href="#">www.blueskylinemarketing.com/terms.php</a>
            </label>
          </div>
        </div>
        <!-- /.col -->
        <!-- /.col -->
      </div>
    </form>

  </div>
  <!-- /.form-box -->
</div>
<!-- /.register-box -->
    <?php
        
        $form_width = 6;
        $form_style = 'none';
    
        if(isset($row['subscription_message']) && isset($row['loyalty_message'])){
            ?>
            <div class="col-md-6 register-box">
                <div class="text-center">
                    <h1><?php echo $subscription_message; ?></h1>
                    <hr>
                    <span style="font-size: 18px;"><b><i>And</i></b></span>
                    <hr>
                    <h1><?php echo $loyalty_message; ?></h1>
                </div>
            </div>
            
            <?php
        }elseif(isset($row['subscription_message']) && !isset($row['loyalty_message'])){
            ?>
            
            <div class="col-md-6 register-box">
                <div class="text-center">
                   <hr>
                    <h1><?php echo $subscription_message; ?></h1>
                   <hr>
                </div>
            </div>
            
            <?php
        }elseif(!isset($row['subscription_message']) && isset($row['loyalty_message'])){
            ?>
            
            <div class="col-md-6 register-box">
                <div class="text-center">
                   <hr>
                    <h1><?php echo $loyalty_message; ?></h1>
                   <hr>
                </div>
            </div>
            
            <?php
            
        }else{
            $form_width = 12;
            $form_style = 'none';
        }
    
    ?>
</div>
</div>
</body>