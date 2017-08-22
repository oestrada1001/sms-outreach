<?php
require_once '../../preview_links.php';    

if(!isset($_COOKIE['livePreview'])){
    header("location: ../logout.php");
}
$_SESSION['client_verification'] = 'unapproved';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>BSM | <?php print('Your Business Name'); ?></title>
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
  <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-102428282-1', 'auto');
  ga('send', 'pageview');

</script>
</head>
<body class="hold-transition register-page">
    <div class="row">
        <div class="rightOut">
           <a id="passVery" class="btn btn-primary" data-toggle="modal" data-target="#toDashboard">Dashboard</a>
        </div>
    </div>
<div class="register-box">
  <div class="register-logo">
    <?php print('Your Business Name'); ?>
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
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              By clicking submit, you are agreeing to the <span style="color: blue;">terms and conditions.</span>
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <input type="submit" class="btn btn-primary btn-block btn-flat" value="Submit" id="btn-login">
        </div>
        <!-- /.col -->
      </div>
    </form>

  </div>
  <!-- /.form-box -->
</div>
<!-- /.register-box -->


<div id="toDashboard" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Verify your password</h4>
            </div>
            <div class="modal-body">
              <br>
               <p>Password: <i>LivePreview!1</i></p>
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
<script src="../../js/no-back.js"></script>
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
      
  });
</script>
</body>
</html>
