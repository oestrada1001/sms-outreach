<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Blue Skyline Marketing | Login</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta property="og:url"                content="https://www.blueskylinemarketing.com">
    <meta property="og:type"               content="website">
    <meta property="og:title"              content="Blue Skyline Marketing: Massive SMS Outreach">
    <meta property="og:description"        content="Massive SMS Outreach is a text messaging marketing tool that allow you to see what promotions and discounts work for YOUR business.">
    <meta property="og:image"              content="https://www.blueskylinemarketing.com/Blue-Skyline-Marketing-fb.png" />
  <!-- Favicon -->
  <link rel="shortcut icon" href="img/favicon.png">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <link rel="stylesheet" href="plugins/pace/themes/blue/pace-theme-minimal.css" type="text/css">
  <link rel='stylesheet' href="css/dashboard.css" type="text/css">
  <script src="plugins/pace/pace.min.js"></script>
  <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-102428282-1', 'auto');
  ga('send', 'pageview');

</script>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
      <a href="../index.php"><b>Blue Skyline</b> Marketing</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg"><b>Login</b></p>
      <div class="row" id="error"></div>
      <p>Email: <i>live@preview.com</i></p>
      <p>Password: <i>LivePreview!1</i></p>
    <form method="POST" id="login-form">
      <div class="form-group has-feedback">
        <input type="email" class="form-control" placeholder="Email" name="login_user" id="loginUser">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div id="loader"></div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Password" id="loginPassword" name="login_password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <!-- /.col -->
        <div class="col-xs-4 btn-center">
          <input type="submit" class="btn btn-primary btn-block btn-flat" value="Sign In" id="btn-login">
        </div>
        <!-- /.col -->
      </div>
    </form>



  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3.1.1 -->
<script src="plugins/jQuery/jquery-3.1.1.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="plugins/iCheck/icheck.min.js"></script>
<script src="validation/front_end_validation.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
    
    $("#btn-login").on('click', function(e){
        e.preventDefault();
        $('#error').empty();
        $("#btn-login").prop("disabled", true);
        
        
        var email = $('#loginUser').val();
        var password = $('#loginPassword').val();
        
        if(!valid_email.test(email)){
            $('#error').append("<i class='fa fa-times-circle'></i>Invalid Email Format.");
            $('#btn-login').prop("disabled", false);
            return;
        }
        if(!valid_password.test(password)){
            $('#error').append("<i class='fa fa-times-circle'></i>Invalid Password.");
            $('#btn-login').prop("disabled", false);
            return;
        }
        
        
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                
                //Loader
                
                if(xmlhttp.responseText != 404){
                
                    window.location.href = xmlhttp.responseText;
                }else{
                    
                        $('#error').append("<i class='fa fa-times-circle'></i>Email or password is not valid.");
                        $('#btn-login').prop("disabled", false);
                    
                }
            }
        }
        
        xmlhttp.open("POST", "process/login_process.php", true);
        xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xmlhttp.send("login_user="+email+"&login_password="+password);
    });  
      
  });
</script>
</body>
</html>
