<?php
require_once 'session.php';
require_once 'functions.php';

if(!isset($row['email'])){
    header("location: logout.php");
}
if($row['access'] == 0){
    header("location: setup/unpaid_account.php");
}
$client_verification = $_SESSION['client_verification'];
if($client_verification == 'denied'){
    header("location: client/welcome.php");
    exit();
}elseif($client_verification != 'verified' && $client_verification != 'denied'){
    header("location: logout.php");
}
//$directions = include 'logout.php';

$reAuthX = getToken(100);


?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Blue Skyline Marketing</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Favicon -->
  <link rel="shortcut icon" href="img/favicon.png">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <!-- Bootstrap Tour -->
  <link href="bootstrap-tour-0.11.0/build/css/bootstrap-tour.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect. -->
  <link rel="stylesheet" href="dist/css/skins/skin-red.css">
    
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
  
  <link rel="stylesheet" href="css/dashboard.css" type="text/css">
  <script src='dist/client.min.js'></script>
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="skin-red hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="dashboard.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>BS</b>M</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>BlueSkyline</b>Marketing</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
        
          <!-- Tasks Menu -->
          

          <!-- Control Sidebar Toggle Button -->
          <li>
              <a href="logout.php">Sign Out<i class="fa fa-sign-out"></i></a>
          </li>
          <li>
            <a id="subscription-form" style="cursor: pointer;">Subscription Form<i class="fa fa-file-text"></i></a>
            <form method="post" id="browser-form" action="client/welcome.php">
                <input type="hidden" name="fingerprint" id="hidden-value">
            </form>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <!--<div class="image">
          <img src="img/blueCity.jpg" class="img-circle" alt="User Image">
        </div>-->
        <div class="info">
          <p><?php echo "{$row['business_name']}"; ?></p>
          <!-- Status -->
          <!--<a href="#"><i class="fa fa-circle text-success"></i> Online</a>-->
        </div>
      </div>

      <!-- search form (Optional) -->
      <!--<form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
              </button>
            </span>
        </div>
      </form>-->
      <!-- /.search form -->

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">Main Navigation</li>
        <!-- Optionally, you can add icons to the links -->
        <li class="active"><a href="#" id="dashboard"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
        <li class="treeview">
          <a href="#"><i class="fa fa-exclamation-triangle"></i> <span>Call-To-Actions</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#" id="view_cta">View Call-To-Actions</a></li>
            <li><a href="#" id="edit_cta">Edit Call-To-Actions</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-comments"></i> <span>SMS Messages</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#" id="view_sms">View SMS Messages</a></li>
            <li><a href="#" id="edit_sms">Edit SMS Messages</a></li>
          </ul>
        </li>
        <li><a href="#" id="database"><i class="glyphicon glyphicon-tasks"></i> <span>Database</span></a></li>
        <li><a href="#" id="settings"><i class="fa fa-gears"></i> <span>Settings</span></a></li>
        <li><a href="#" id="tours"><i class="fa fa-object-group"></i> <span>Tip &amp; Hints</span></a></li>
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
   

    <!-- Main content -->
    <section class="content container-fluid">
        
        <div id="mainContent">
            <?php include 'home_process.php'; ?>
        </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="text-right">
    <strong>Copyright &copy; 2017 <a href="www.blueskylinemarketing.com">Blue Skyline Marketing</a>.</strong> All rights reserved.
    </div>
    <!-- Default to the left -->
  </footer>

  <!-- Add the sidebar's background. This div must be placed
  immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3.1.1 -->
<script src="plugins/jQuery/jquery-3.1.1.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- Bootstrap Tour -->
<script src="bootstrap-tour-0.11.0/build/js/bootstrap-tour.min.js"></script>
<script src="js/tour.js"></script>
<!--DataTables-->
<script src="plugins/datatables/jquery-1.12.4.js"></script>
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css"></script>
<!--SlimScroll-->
<script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!--FastClick-->
<script src="plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
<script src="validation/front_end_validation.js"></script>
<script type="text/javascript">
    
    $(document).ready(function() {
        
        var clientBrowser = new ClientJS();
        var fingerprint = clientBrowser.getFingerprint();
        var reAuthX = <?php echo json_encode($reAuthX); ?>;
        
        //AuthX Process
        $("#subscription-form").on('click', function(e){
            e.preventDefault();
            
            var stringPrint = fingerprint + "";
            
            var hiddenValue = document.getElementById('hidden-value').value = stringPrint ;
            
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    
                    var responseText = parseInt(xmlhttp.responseText);
                    
                    if(xmlhttp.responseText == 1001){
                        
                        $("#browser-form").submit();
                    }else{
                        window.location.href = 'dashboard.php';
                    }
                    
                }
            }
            
            xmlhttp.open("POST", "process/reAuthX-process.php", true);
            xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xmlhttp.send("fingerprint="+fingerprint+"&reAuthX="+reAuthX);
            
        });
        
        
        //end Tours if any link is clicked
        $('a').on('click', function(){
            set_sms.end();
            home.end();
        });
        
        //Tips and Hints - Deletes cookies and runs both
        $('#tours').on('click', function(e){
            e.preventDefault();
            
            document.cookie = "dashboard_tour=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            document.cookie = "set_sms_tour=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            
            window.location.href = 'dashboard.php';
            
        })
        
        //Checks If Dashboard Tour has be done.
        
        if (document.cookie.replace(/(?:(?:^|.*;\s*)dashboard_tour\s*\=\s*([^;]*).*$)|^.*$/, "$1") !== "true") {
            home.init();
            home.restart(true);
            
            var d = new Date();
            d.setTime(d.getTime() + (60*24*60*60*1000));
            var expires = "expires="+ d.toUTCString();
            document.cookie = "dashboard_tour=true;" + expires + ";path=/";
            
        }
        
        //adds active and removes active
       $('ul.sidebar-menu li a').click(
    function(e) {
        e.preventDefault(); // prevent the default action
        e.stopPropagation(); // stop the click from bubbling
        $(this).closest('ul').find('.active').removeClass('active');
        $(this).parent().addClass('active');
    });
        //Default Landing Page by Attribute
        var w3 = {};
        w3.includeHTML = function(cb) {
            var z, i, elmnt, file, xhttp;
            z = document.getElementsByTagName("*");
            for (i = 0; i < z.length; i++) {
                elmnt = z[i];
                file = elmnt.getAttribute("w3-include-html");
                if (file) {
                    xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            elmnt.innerHTML = this.responseText;
                            elmnt.removeAttribute("w3-include-html");
                            w3.includeHTML(cb);
                        }
                    }      
                    xhttp.open("GET", file, true);
                    xhttp.send();
                    return;
                }
            }
            if (cb) cb();
        };
       
        w3.includeHTML();
        //Redirect W3 Attribute Landing Attribute
        function reDirectContent(url){
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    
                    document.getElementById('mainContent').innerHTML = ' ';
                    
                    if(xmlhttp.responseText != 404){
                        var htmltag = document.getElementById('mainContent');
                        var attr = document.createAttribute("w3-include-html");
                        attr.value = xmlhttp.responseText;
                        htmltag.setAttributeNode(attr);
                       
                        w3.includeHTML();
                    }else{
                        window.location.href = 'logout.php';
                        
                    }
                }
            }
            
            xmlhttp.open("POST", "process/tab_process.php?redirect_to="+url, true);
            xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xmlhttp.send("redirect_to="+url);
            
        }
        //Update Client Personal Information
        function updateInfo(columnName, newColumnValue){
            $('.updateButton').prop('disable', true);
            $("#error").empty();
            $("#success").empty();
            var columnName = columnName;
            var newColumnValue = newColumnValue;
            
            alert(newColumnValue);
            
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    
                    alert(xmlhttp.responseText);
                    
                    if(xmlhttp.responseText == 1001){
                        $('#success').html("<i class='fa fa-check'></i>Updated Successfully.");
                    }else{
                        $('#error').html("<i class='fa fa-times-circle'></i>Please use the correct format.");
                        
                    }
                }
            }
            
            xmlhttp.open("POST", "process/update_process.php?columnName="+columnName+"&newColumnValue="+newColumnValue, true);
            xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xmlhttp.send("columnName="+columnName+"&newColumnValue="+newColumnValue);
            
        }
        
        //Set Message Functions
        function set_sms_message(outbound_date, outbound_message, submit_button, type){
            var outbound_date = outbound_date;
            var outbound_message = outbound_message;
            var submit_button = submit_button;
            var type = type;
            
            outbound_message = encodeURIComponent(outbound_message);
            
            $('.'+type+' #success').empty();
            $('.'+type+' #error').empty();
            
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    
                    if(xmlhttp.responseText == 404){
                        
                        $('.'+type+' #error').html("<i class='fa fa-times-circle'></i>Please use correct format.");
                        
                    }else if(xmlhttp.responseText == 606){
                        
                        $('.'+type+' #error').html("<i class='fa fa-times-circle'></i>We are having problems with our system, if this continues please contact us.");
                        
                    }else if(xmlhttp.responseText !== 606 ||xmlhttp.responseText !== 404){
                        
                        $('.'+type+' #success').html("<i class='fa fa-check-circle'></i>"+xmlhttp.responseText);
                        
                    }
                    
                 submit_button.prop("disabled", false);   
                }
            }
            
            xmlhttp.open("POST", "process/set_sms_process.php?outbound_date="+outbound_date+"&outbound_message="+outbound_message+"&type="+type, true);
            xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xmlhttp.send("outbound_date="+outbound_date+"&outbound_message="+outbound_message+"&type="+type);
            
        }
        //Delete SMS Message - null
        function sms_null(type){
            var type = type;
            
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    var htmltag = document.getElementById('mainContent');
                        var attr = document.createAttribute("w3-include-html");
                        attr.value = xmlhttp.responseText;
                        htmltag.setAttributeNode(attr);
                       
                        w3.includeHTML();
                    
                    $('#'+type).prop("disabled", false);
                }
            }
            
            xmlhttp.open("POST", "process/delete_sms_process.php?type="+type, true);
            xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xmlhttp.send("type="+type);
            
        }
        
        //Set Texts/Monthly
        function setTextsMonthly(number){
            var numberOfText = number;
            
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    
                    var response = parseInt(xmlhttp.responseText);
                    
                    switch(response){
                        case 404:
                            $('.monthly_texts #error').html("<i class='fa fa-times-circle'></i>We are currently have problems. Please try again in a few minutes or contact us.");
                            $('#set_monthly_texts').prop('disabled', false);
                            break;
                        case 606:
                            $('.monthly_texts #error').html("<i class='fa fa-times-circle'></i>Please enter a number.");
                            $('#set_monthly_texts').prop('disabled', false);
                            break;
                        case 101:
                            $('.monthly_texts #success').html("<i class='fa fa-check-circle'></i>Number of Text Messages/Monthly has been set. This page will now reload in a few seconds.");
                            
                            setTimeout(function(){
                                window.location.href = "dashboard.php";
                            }, 3000);
                            
                            break;        
                    }
                    
                }
            }
            
            xmlhttp.open("POST", "process/set_texts_monthly_process.php?numberOfTexts="+numberOfText, true);
            xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xmlhttp.send("numberOfText="+numberOfText);
        }
        
        $('#set_monthly_texts').on('click', function(e){
            e.preventDefault();
            $('.monthly_texts #error').empty();
            $('.monthly_texts #success').empty();
            
            var submit_button = $("#set_monthly_texts").prop('disabled', true);
            var set_Monthly = $("#set_monthly").val();
            set_Monthly = parseInt(set_Monthly);
            
            if(!Number.isInteger(set_Monthly)){
                $('.monthly_texts #error').html("<i class='fa fa-times-circle'></i>Incorrect Format.");
                submit_button.prop("disabled", false);
                return
            }
            
            if(set_Monthly <= 0){
                $('.monthly_texts #error').html("<i class='fa fa-times-circle'></i>The number has to be greater than 0.");
                submit_button.prop("disabled", false);
                return
            }
            
            setTextsMonthly(set_Monthly);
            
        });
        
        //Client Dashboard
        document.getElementById('dashboard').addEventListener('click', function(){
                var url = event.currentTarget.id;
                reDirectContent(url);
            
                if (document.cookie.replace(/(?:(?:^|.*;\s*)dashboard_tour\s*\=\s*([^;]*).*$)|^.*$/, "$1") !== "true") {
                    home.init();
                    home.restart(true);
                    
                    var d = new Date();
                    d.setTime(d.getTime() + (60*24*60*60*1000));
                    var expires = "expires="+ d.toUTCString();
                    document.cookie = "dashboard_tour=true;" + expires + ";path=/";
                }
            
                $('body').on('click', '#set_monthly_texts', function(e){
                    e.preventDefault();
                    
                    $('.monthly_texts #error').empty();
                    $('.monthly_texts #success').empty();
                    
                    var submit_button = $("#set_monthly_texts").prop('disabled', true);
                    var set_Monthly = $("#set_monthly").val();
                    set_Monthly = parseInt(set_Monthly);
                    
                    if(!Number.isInteger(set_Monthly)){
                        $('.monthly_texts #error').html("<i class='fa fa-times-circle'></i>Incorrect Format.");
                        submit_button.prop("disabled", false);
                        return
                    }
                    
                    if(set_Monthly <= 0){
                        $('.monthly_texts #error').html("<i class='fa fa-times-circle'></i>The number has to be greater than zero.");
                        submit_button.prop("disabled", false);
                        return
                    }
                    
                    setTextsMonthly(set_Monthly);
                    
                });
            
            });
        
        document.getElementById('view_cta').addEventListener('click', function(){
            var url = event.currentTarget.id;
                reDirectContent(url);
            
                $('body').on('click', '#null_headline_1', function(e){
                   e.preventDefault();
                    $('#null_headline_1').prop("disabled", true);
                    var type = 'null_headline_1';
                    
                    sms_null(type);
                    
                });
            
                $('body').on('click', '#null_headline_2', function(e){
                   e.preventDefault();
                    $('#null_headline_2').prop("disabled", true);
                    var type = 'null_headline_2';
                    
                    sms_null(type);
                    
                });
        });
        
        document.getElementById('edit_cta').addEventListener('click', function(){
            var url = event.currentTarget.id;
                reDirectContent(url);
            
                $('body').on('click', '#set_cta_headline_1', function(e){
                    e.preventDefault();
                    $('.cta_headline_1 #error').empty();
                    $('.cta_headline_1 #success').empty();
                    var submit_button = $('#set_cta_headline_1');
                    submit_button.prop("disabled", true);
                    var outbound_date = null;
                    var outbound_message = $('#headline_1_msg').val();
                    var type= 'cta_headline_1';
                    
                    if(outbound_date !== null){
                        $('.cta_headline_1 #error').html("<i class='fa fa-times-circle'></i>We are having problems with our system, if this continues please contact us.");
                        submit_button.prop("disabled", false);
                        return;
                    }
                    
                    if(!valid_sms_message.test(outbound_message)){
                        $('.cta_headline_1 #error').html("<i class='fa fa-times-circle'></i>Invalid Message Format.");
                        submit_button.prop("disabled", false);
                        return;
                    }
            
                    set_sms_message(outbound_date, outbound_message, submit_button, type);
                    
                });
            
            $('body').on('click', '#set_cta_headline_2', function(e){
                    e.preventDefault();
                    $('.cta_headline_2 #error').empty();
                    $('.cta_headline_2 #success').empty();
                    var submit_button = $('#set_cta_headline_2');
                    submit_button.prop("disabled", true);
                    var outbound_date = null;
                    var outbound_message = $('#headline_2_msg').val();
                    var type= 'cta_headline_2';
                    
                    if(outbound_date !== null){
                        $('.cta_headline_2 #error').html("<i class='fa fa-times-circle'></i>We are having problems with our system, if this continues please contact us.");
                        submit_button.prop("disabled", false);
                        return;
                    }
                    
                    if(!valid_sms_message.test(outbound_message)){
                        $('.cta_headline_2 #error').html("<i class='fa fa-times-circle'></i>Invalid Message Format.");
                        submit_button.prop("disabled", false);
                        return;
                    }
            
                    set_sms_message(outbound_date, outbound_message, submit_button, type);
                    
                });
                
        });
        
        document.getElementById('view_sms').addEventListener('click', function(){
                var url = event.currentTarget.id;
                reDirectContent(url);
            
                $('body').on('click', '#null_default', function(e){
                   e.preventDefault();
                    $('#null_default').prop("disabled", true);
                    var type = 'null_default';
                    
                    sms_null(type);
                    
                });
                $('body').on('click', '#null_custom', function(e){
                   e.preventDefault();
                    $('#null_custom').prop("disabled", true);
                    var type = 'null_custom';
                    
                    sms_null(type);
                    
                });
                $('body').on('click', '#null_inactive', function(e){
                   e.preventDefault();
                    $('#null_inactive').prop("disabled", true);
                    var type = 'null_inactive';
                    
                    sms_null(type);
                    
                });
                $('body').on('click', '#null_visit', function(e){
                   e.preventDefault();
                    $('#null_visit').prop("disabled", true);
                    var type = 'null_visit';
                    
                    sms_null(type);
                    
                });
            
            
            });
        document.getElementById('edit_sms').addEventListener('click', function(){
                var url = event.currentTarget.id;
                reDirectContent(url);
                
                if (document.cookie.replace(/(?:(?:^|.*;\s*)set_sms_tour\s*\=\s*([^;]*).*$)|^.*$/, "$1") !== "true"){
                
                    setTimeout(function(){
                        set_sms.init();
                        set_sms.restart(true);
                    }, 3000);
                    
                    var d = new Date();
                    d.setTime(d.getTime() + (60*24*60*60*1000));
                    var expires = "expires="+ d.toUTCString();
                    document.cookie = "set_sms_tour=true;" + expires + ";path=/";
            
                }
            
            
                $('body').on('click', '#set_default_msg', function(e){
                        e.preventDefault();
                        $('.default_message #error').empty();
                        $('.default_message #success').empty();
                        var submit_button = $('#set_default_msg');
                        submit_button.prop("disabled", true);
                        var outbound_date = null;
                        var outbound_message = $('#default_sms').val();
                        var type= 'default_message';
                    
                        if(outbound_date !== null){
                            $('.default_message #error').html("<i class='fa fa-times-circle'></i>We are having problems with our system, if this continues please contact us.");
                            submit_button.prop("disabled", false);
                            return;
                        }
                    
                        if(!valid_sms_message.test(outbound_message)){
                            $('.default_message #error').html("<i class='fa fa-times-circle'></i>Invalid Message Format. Special Characters allowed: ! @ # ' % & - , : .");
                            submit_button.prop("disabled", false);
                            return;
                        }
            
                        set_sms_message(outbound_date, outbound_message, submit_button, type);
                    
                });
                $('body').on('click', '#set_custom_msg', function(e){
                        e.preventDefault();
                        $('.custom_message #error').empty();
                        $('.custom_message #success').empty();         
                        var submit_button = $('#set_custom_msg');
                        submit_button.prop("disabled", true);
                        var outbound_date = $('#outbound_date').val();
                        var outbound_message = $('#custom_sms').val();
                        var type = 'custom_message';

                        if(!valid_sms_date.test(outbound_date)){
                            $('.custom_message #error').html("<i class='fa fa-times-circle'></i>Invalid Date Format."+outbound_date);
                            submit_button.prop("disabled", false);
                            return;
                        }
                    
                        if(!valid_sms_message.test(outbound_message)){
                            $('.custom_message #error').html("<i class='fa fa-times-circle'></i>Invalid Message Format. Special Characters allowed: ! @ # ' % & - , :");
                            submit_button.prop("disabled", false);
                            return;
                        }
                    
                    
                        set_sms_message(outbound_date, outbound_message, submit_button, type);
                    
                        
                    
                });
                $('body').on('click', '#set_inactive_msg', function(e){
                        e.preventDefault();
                        $('.inactive_message #error').empty();
                        $('.inactive_message #success').empty();       
                        var submit_button = $('#set_inactive_msg');
                        submit_button.prop("disabled", true);
                        var outbound_date = $('#inactive_days').val();
                        var outbound_message = $('#after_sms').val();
                        var type = 'inactive_message';
            
                        if(!valid_number.test(outbound_date)){
                            $('.inactive_message #error').html("<i class='fa fa-times-circle'></i>Invalid # of inactive days.");
                            submit_button.prop("disabled", false);
                            return;
                        }
                    
                        if(!valid_sms_message.test(outbound_message)){
                            $('.inactive_message #error').html("<i class='fa fa-times-circle'></i>Invalid Message Format. Special Characters allowed: ! @ # ' % & - , :");
                            submit_button.prop("disabled", false);
                            return;
                        }
                        
                    
                        set_sms_message(outbound_date, outbound_message, submit_button, type);
                    
                    
                });
                $('body').on('click', '#set_visit_msg', function(e){
                        e.preventDefault();
                        $('.visit_message #error').empty();
                        $('.visit_message #success').empty();           
                        var submit_button = $('#set_visit_msg');
                        submit_button.prop("disabled", true);
                        var outbound_date = $('#visit_number').val();
                        var outbound_message = $('#visit_message').val();
                        var type = 'visit_message';

                        if(!valid_number.test(outbound_date)){
                            $('.visit_message #error').html("<i class='fa fa-times-circle'></i>Invalid # of visits.");
                            submit_button.prop("disabled", false);
                            return;
                        }
                    
                        if(!valid_sms_message.test(outbound_message)){
                            $('.visit_message #error').html("<i class='fa fa-times-circle'></i>Invalid Message Format. Special Characters allowed: ! @ # ' % & - , :");
                            submit_button.prop("disabled", false);
                            return;
                        }
                        
                    
                        set_sms_message(outbound_date, outbound_message, submit_button, type);
                    
                    
                });
            });
        document.getElementById('database').addEventListener('click', function(){
            var url = event.currentTarget.id;
                reDirectContent(url);
            
            $('body').on('mousemove', window, function(event){
                $('#employee_data').DataTable();
            })
        });
        
        
        document.getElementById('settings').addEventListener('click', function(){
                var url = event.currentTarget.id;
                reDirectContent(url);
            
            $('body').on('click', '#set_collect_emails', function(e){
                e.preventDefault();
                $('#set_collect_emails').prop("disabled", true);
                $('#collect_email_box #error').empty();
                $('#collect_email_box #success').empty();
                var collect_emails_answer = $("input[name='email_answer']:checked").val();
                
                if(typeof collect_emails_answer == 'undefined'){
                    collect_emails_answer = 'no';
                }
                
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function(){
                    if(this.readyState == 4 && this.status == 200){
                        
                        if(xmlhttp.responseText == 404){
                            
                            $('.collect_emails_box #error').html("<i class='fa fa-times-circle'></i>We seem to be having a problem. Please contact us if this problem continues.");   
                            
                             $("#set_collect_emails").prop("disabled", false);
                            
                            
                        }else{
                            $('.collect_emails_box #success').html("<i class='fa fa-check-circle'></i>The request was successful. You will now be redirected.")
                            
                            setTimeout(function(){
                                var htmltag = document.getElementById('mainContent');
                                var attr = document.createAttribute("w3-include-html");
                                attr.value = xmlhttp.responseText;
                                htmltag.setAttributeNode(attr);
                            
                                w3.includeHTML();
                            }, 3000);
                            
                            $("#set_collect_emails").prop("disabled", false);
                    
                        }
                    }
                }
            
            xmlhttp.open("POST", "process/collect_email_process.php?collect_emails_answer="+collect_emails_answer, true);
            xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xmlhttp.send("collect_emails_answer="+collect_emails_answer);
            });
            
            $('body').on('click', '#set_subscription_link', function(e){
                e.preventDefault();
                $('#set_subscription_link').prop('disabled', true);
                $('.subscription_link #error').empty();
                $('.subscription_link #success').empty();
                var link_requested = $('#link_requested').val();
                
                if(!valid_business_name.test(link_requested)){
                    $('.subscription_link #error').html("<i class='fa fa-times-circle'></i>Invalid Format.");
                    $('#set_subscription_link').prop("disabled", false);
                    return;
                }
                
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function(){
                    if(this.readyState == 4 && this.status == 200){
                        
                        var response = Number(xmlhttp.responseText);
                        
                        if(response == 606){
                            $('.subscription_link #error').html("<i class='fa fa-times-circle'></i>Link already taken. Please try again.");
                            $('#set_subscription_link').prop("disabled", false);
                            return;
                        }else if(response == 404){
                            $('.subscription_link #error').html("<i class='fa fa-times-circle'></i>We are currently having problems. Please try again later.");
                            $('#set_subscription_link').prop("disabled", false);
                        }else if(response == 303){
                            $('.subscription_link #error').html("<i class='fa fa-times-circle'></i>Invalid Format.");
                            $('#set_subscription_link').prop("disabled", false);
                            return;     
                        }else if(response == 101){
                            $('.subscription_link #success').html("<i class='fa fa-check-circle'></i>Link successfully made. You will now be redirected. If not, refresh the page.");
                            
                            setTimeout(function(){
                                
                            var htmltag = document.getElementById('mainContent');
                            var attr = document.createAttribute("w3-include-html");
                            attr.value = 'client/settings.php';
                            htmltag.setAttributeNode(attr);
                           
                            w3.includeHTML();    
                                
                            }, 3000);
                        }
                        
                    }
                }
                
                xmlhttp.open("POST", "process/create_subscription_page_process.php?business_name_requested="+link_requested, true);
                xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xmlhttp.send("business_name_requested="+link_requested);
                
                
            });
            
            $('body').on('click', '#subscription_link' , function(){
                
                $temp.val($("#subscription_link").val).select();
                document.execCommand('copy');

            })

            /* Opens one modal and adds id depending on which clicked, stores value and passes it up ajax function
            
            $('body').on('click', '.updateInfo', function(){
                
                $('#updateModal').find("input").removeAttr('value');
                $('#modalID').empty();
                $('#error').empty();
                $('#success').empty();
                $('.updateInput').attr('type', 'text');
                
                
                
                var columnName = $(this).data('id');
                columnName.value= " ";
                $('.updateButton').attr('id', columnName);
                
                if(columnName == 'phone_number'){
                    $('.updateInput').attr('type', 'number');
                }
                
                columnName = columnName.replace("_", " ");
                $('.updateButton').prop('value', 'Update '+columnName);
                $('#modalID').html('Update '+columnName);
                
                
            
                $('.updateButton').on('click', function(e){
                    e.preventDefault();
                    document.getElementsByClassName('updateButton').disabled = true;
                    var columnName = event.currentTarget.id;
                    var newColumnValue =$('.updateInput').val();
                    
                    updateInfo(columnName, newColumnValue);
                });
            
            });
            */
            
        });
        
        //End Client Dashboard
        //Admin Dashboard
        
        //End Admin Dashboard
        
    });
</script>     
</body>
</html>