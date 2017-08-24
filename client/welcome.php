<?php
require_once '../session.php';

if(!isset($_SESSION['email'])){
    header("location: ../logout.php");
}
if($row['access'] == 0){
    header("location: ../setup/unpaid_account.php");
}

if($_SESSION['client_verification'] == 'unapproved'){
    header("Location: https://www.blueskylinemarketing.com/");
}

$_SESSION['client_verification'] = 'denied';

$email = $row['email'];

$val = "SELECT * FROM clients WHERE email = '$email'";

$val = mysqli_query($db_connect, $val);
$val = mysqli_num_rows($val);

if($val == 0){
    header("Location: ../logout.php");
}
$subscription_message = $row['subscription_message'];
$loyalty_message = $row['loyalty_message'];
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
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>BSM | <?php print_r($row['business_name']); ?></title>
  <!-- Favicon -->
  <link rel="shortcut icon" href="../img/favicon.png">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <link rel='stylesheet' href="../css/dashboard.css" type="text/css">
</head>
<body class="hold-transition register-page">
    <div class="row">
        <div class="rightOut">
           <a id="passVery" class="btn btn-primary" data-toggle="modal" data-target="#toDashboard">Dashboard</a>
        </div>
    </div>
    <div style="display: table; margin: auto;">
    
    
<div class="col-md-<?php echo $form_width; ?> register-box">
  <div class="register-logo">
    <?php print_r($row['business_name']); ?>
  </div>

  <div class="register-box-body">
    <p class="login-box-msg">Subscribe to our specials and promotions.</p>
    <div class="row" id="error"></div>
    <form method="POST" id="login-form">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Full name" id="sub_name" required>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="number" class="form-control" placeholder="Cellphone Number" maxlength="10" id="sub_cell" required>
        <span class="glyphicon glyphicon-phone form-control-feedback"></span>
      </div>
      <?php
            if($row['collect_emails'] == 'yes'){
            ?>    
              <div class="form-group has-feedback">
                <input type="email" class="form-control" placeholder="Email" id="sub_email">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
              </div> 
            <?php
            }elseif($row['collect_emails'] == 'no'){
                ?><input type="email" id="sub_email" value="no" style="display: none;"><?php
            }
        ?>
        <div class="col-xs-12">
         <?php
         
            if($monthly_texts == 0 || $monthly_texts == null){
                ?>
                      <input type="submit" class="btn btn-primary btn-block btn-flat" value="Submit" id="btn-login" disabled>      
                <?php
            }else{
                ?>
                      <input type="submit" class="btn btn-primary btn-block btn-flat" value="Submit" id="btn-login">
                <?php
            }
            
         ?>
        </div>
      <div class="row">
        <div class="col-xs-12">
          <div class="checkbox icheck">
            <label>
              Message and data rates may apply. Expect Approx. <? if($monthly_texts == null){echo 0;}else{ echo $monthly_texts; }?> Texts/Monthly. To opt-out text <b>STOP</b> to <?php echo $initial_number; ?>. For more information or terms and conditions please visit <a href="#">www.blueskylinemarketing.com/terms.php</a>
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

<div id="toDashboard" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Verify your password</h4>
            </div>
            <div class="modal-body">
                <form id="form-login" method="POST">
                   <div class="form-group">
                      <div class="row" id="error1"></div>
                       <input type="password" name="verify_key" class="form-control" id="verify_key">
                   </div>
                   <input type="submit" id="btn-verify" class="btn btn-primary" value="Verify">
                </form>
            </div>
        </div>
    </div>
</div>
<div id="subModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <h2 id="subResponse"></h2>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery 3.1.1 -->
<script src="../plugins/jQuery/jquery-3.1.1.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="../plugins/iCheck/icheck.min.js"></script>
<script src="../validation/front_end_validation.js"></script>
<script src="../js/no-back.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
    
    $("#btn-login").on('click', function(e){
        e.preventDefault();
        $('#btn-login').prop("disabled", true);
        $("#subResponse").empty();
        $("#error").empty();
        var sub_name = $('#sub_name').val();
        var sub_cell = $('#sub_cell').val();
        var sub_email = $('#sub_email').val();
        
        if(!valid_name.test(sub_name)){
            $("#error").append("<i class='fa fa-times-circle col-md-2'></i>Name cannot contain special characters and must have a minimum of 3 characters and maximum of 20 characters.");
            
            $('#btn-login').prop("disabled", false);
            
            return;
        }
        if(!valid_phone_number.test(sub_cell)){
            $("#error").append("<i class='fa fa-times-circle col-md-2'></i>Phone number must be in the following format: 661-800-1234 OR 6618001234.");
            
            $('#btn-login').prop("disabled", false);
            
            return;
        }
        
        if(sub_email !== 'no'){
            if(!valid_email.test(sub_email)){
                $("#error").append("<i class='fa fa-times-circle col-md-2'></i>Email cannot contain any special characters and must be in the following format: noreply@blueskylinemarketing.com");
                
                $('#btn-login').prop("disabled", false);
                
                return;
            }
        }
            
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                
                    $('#sub_name').prop('value', '');
                    $('#sub_cell').prop('value', '');
                    if(sub_email !== 'no'){
                        $('#sub_email').prop('value', '');
                    }
                
                    $('#subResponse').append(xmlhttp.responseText);
                    $("#subModal").modal();
                    
                    $('#btn-login').prop("disabled", false);
            }
            
        }
        
        xmlhttp.open("POST", "../process/subscribe_process.php?sub_name="+sub_name+"&sub_cell="+sub_cell+"&sub_email="+sub_email, true);
        xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); xmlhttp.send("sub_name="+sub_name+"&sub_cell="+sub_cell+"&sub_email="+sub_email);
        
    });
      
    $('#passVery').on('click', function(){
        $('#error').empty();
    })  
      
    $("#btn-verify").on('click', function(e){
        e.preventDefault();
        $('#btn-verify').prop("disabled", true);
        $('#error1').empty();
        var verify_key = $("#verify_key").val();
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                if(xmlhttp.responseText == 404){
                    //Error
                     $('#error1').append("<i class='fa fa-times-circle'></i>Invalid Password. Please try again.");
                    
                    $('#btn-verify').prop("disabled", false);
                }else{
                    window.location.href = xmlhttp.responseText;
                    
                    $('#btn-verify').prop("disabled", false);
                }
            }
        }
        
        xmlhttp.open("POST", "../process/verify_password.php", true);
        xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xmlhttp.send("verify_key="+verify_key);
        
    }); 
    
      function refresh_sesh(){
          
        setTimeout(function(){
            
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    console.log('sesh');
                    refresh_sesh();
                }
            }
            
            xmlhttp.open("POST", "../process/refresh_session.php", true);
            xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xmlhttp.send();
          
        }, 1200000);  
      }
      
      refresh_sesh();
      
  });
</script>
</body>
</html>
