<?php
require_once '../session.php';
require_once '../valid_account.php';
require_once '../functions.php';

if(!isset($row['email'])){
    header("location: ../logout.php");
}
if($row['access'] != 0){
    header("location: ../logout.php");
}
if(!isset($stripe['email'])){
    header("Location: ../logout.php");
}
if($stripe['email'] != $row['email']){
    header("Location: ../logout.php");
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Blue Skyline Marketing</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Favicon -->
  <link rel="shortcut icon" href="../img/favicon.png">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <!-- Bootstrap Tour -->
  <link href="../bootstrap-tour-0.11.0/build/css/bootstrap-tour.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect. -->
  <link rel="stylesheet" href="../dist/css/skins/skin-red.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  
  <link rel="stylesheet" href="../css/dashboard.css" type="text/css">
  <script src="https://js.stripe.com/v3/"></script>
  <script>
     
    function checkValue(value){
      
      var new_payment_form = document.getElementById('new_payment_form');
      
        if(value == 'new_payment'){
            new_payment_form.innerHTML = "<label for='card-element'>Credit/Debit Card</label><br><div id='card-element'><div id='card-errors' role='alert'></div>";
            
            
            // Stripe Key
            var stripe = Stripe('pk_live_dCTBrBZRkyx9187EDyUTVfye');
            
            // Create an instance of Elements
            var elements = stripe.elements();
            
            // Custom styling can be passed to options when creating an Element.
            // (Note that this demo uses a wider set of styles than the guide below.)
            var style = {
                base: {
                    color: '#32325d',
                    lineHeight: '24px',
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '16px',
                    '::placeholder': {
                        color: '#aab7c4'
                    }
                },
                invalid: {
                    color: '#fa755a',
                    iconColor: '#fa755a'
                }
            };
            
            // Create an instance of the card Element
            var card = elements.create('card', {style: style});
            
            // Add an instance of the card Element into the `card-element` <div>
            card.mount('#card-element');
            
            // Handle real-time validation errors from the card Element.
            card.addEventListener('change', function(event) {
                var displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });
            
            //Pass Object
            function stripeTokenHandler(token) {
                // Insert the token ID into the form so it gets submitted to the server
                var form = document.getElementById('payment-form');
                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripeToken');
                hiddenInput.setAttribute('value', token.id);
                form.appendChild(hiddenInput);
                
                var stripeToken = $("input[type='hidden']").val();
                
                // Submit the form
                //Ajax
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function(){
                    if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                        var response = parseInt(xmlhttp.responseText);
                
                        switch(response){
                            case 404:
                                
                                $("#error").html("<i class='fa fa-times-circle'></i>We are currently having problems with your system. Please try again later.");
                                
                                $('#submit_payment').prop('disabled', false);
                                
                                break;
                            case 606:
                               
                                
                                $('#error').html("<i class='fa fa-times-circle'></i>Payment failed. Please check everything is correct and the card is valid.");
                                
                                $('#submit_payment').prop('disabled', false);
                                
                                break;
                            case 101:
                                var success = $('#success')
                                var error = $('#error')
                                
                                success.html("<i class='fa fa-check-circle'></i>The payment was successful. You will now be redirected.");
                                
                                setTimeout(function(){
                                    window.location.href = "../logout.php";
                                }, 3000);
                                
                                setTimeout(function(){
                                    error.html("<i class='fa fa-times-circle'></i>Oops...Sign out and Log in and your account should be active. If problems procceed, please contact us.");    
                                }, 5000);
                                break;
                            
                                
                        }
                        
                    }
                }
                
                xmlhttp.open("POST", "../process/make_payment_process.php?method=new_payment&stripeToken="+stripeToken, true);
                xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xmlhttp.send("method=new_payment&stripeToken="+stripeToken);
            }
            
            // Handle form submission
            var form = document.getElementById('payment-form');
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                $('#submit_payment').prop('disabled', true);
                $('#success').empty();
                $('#error').empty();
                
                
                stripe.createToken(card).then(function(result) {
                    if (result.error) {
                        $('#submit_payment').prop('disabled', false);
                        // Inform the user if there was an error
                        var errorElement = document.getElementById('card-errors');
                        errorElement.textContent = result.error.message;
                    } else {
                        
                        // Send the token to your server
                        stripeTokenHandler(result.token);
                    }
                });
                
            });
        }else{
            new_payment_form.innerHTML = ' ';
        }
    }
  </script>
  <style>
       #denied-access{
            display: none;
            width: 20%;
            position: absolute;
            background: darkred;
            z-index: 100000;
            padding: 20px;
            border-radius: 2px;
            color: white;
            top: 10vh;
            min-width: 300px;
            left: 38vw;
       }
       #denied-image{
            margin-bottom: 14px;
       }
       #denied-image .fa{
            margin: auto;
            display: table;
            font-size: 50px;
            color: whitesmoke;
       }
      .pop-open{
          display: initial;
      }
      .keep-it-cool{
          filter: blur(2px) grayscale(100%);
      }
      a:hover, a:focus{
          text-decoration: none;
          outline: none;
          outline-offset: 0px;
      }
   </style>
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
    <div id="denied-access">
       <div id="denied-image"><i class="fa fa-times-circle-o" aria-hidden="true"></i></div>
       <div id="denied-content"><p>To enable these features please upgrade your account.</p></div>
    </div>
<body id="unpaid_page" class="skin-red hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header"> 

    <!-- Logo -->
    <a href="#" class="logo">
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
              <a href="../logout.php">Sign Out<i class="fa fa-sign-out"></i></a>
          </li>
          <li>
            <a href="#">Subscription Form<i class="fa fa-file-text"></i></a>
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

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">Main Navigation</li>
        <!-- Optionally, you can add icons to the links -->
        <li><a href="#" id="dashboard"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
        <li class="treeview">
          <a href="#" id="not-click"><i class="fa fa-comments"></i> <span>SMS Messages</span>
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
        <li class="active"><a href="#" id="settings"><i class="fa fa-gears"></i> <span>Settings</span></a></li>
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
<div id="settings">
   
    <div class="col-md-6">
        <div class="box box-primary">
        <div class="box-header with-border">
            <h1 class="box-title">Personal Information</h1>
        </div>
        <div class="box-body">
        <table class="table table-bordered">
           <tbody>     
       <?php
        $subscription_type = $stripe['subscription_type'];
        
//        switch($subscription_type){
//            case 'FREE_TRIAL':
//                $subscription_type = 'Free Trial';
//                break;
//            case 'BSM_BETA':
//                $subscription_type = 'Startup';
//                break;
//            case 'SML_BIZ':
//                $subscription_type = 'Small Business';
//                break;
//            default:
//                $subscription_type = 'Blue Skyline Markeing: SMS Outreach';
//                break;
//        }
               
        $subscription_type = subscription_name($subscription_type);
               
        echo "<tr><th scope='row'>Subscription</th><td>$subscription_type</td></tr>";       
               
        foreach($row as $key=>$value){
            
            switch($key) {
                case 'id':
                case 'password':
                case 'hash':
                case 'active':
                case 'delivery_date':
                case 'default_message':
                case 'custom_message':
                case 'after_date':
                case 'after_message':
                case 'sms_sent':
                case 'visit_goal':
                case 'visit_message':
                case 'access':
                    echo"";
                    break;
                default:
                    $key1 = str_replace('_', ' ', $key);
                    $key1 = ucwords($key1);
                    echo "<tr><th scope='row'>$key1</th>
                    <td>$value</td></tr>";
                    break;
            }
        }
        
        ?>
           </tbody>
       </table>
        </div>
        </div>    
    </div>
    <div class="col-md-6 collect_emails_box">
        <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title">Subscription Information:</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered">
                   <thead>
                       <tr>
                           <th>Subscription</th>
                           <th>Price</th>
                       </tr>
                   </thead>
                   <tbody>
                       <?php
                        
                       $subscription_type = $stripe['subscription_type'];
        
//                        switch($subscription_type){
//                            case 'FREE_TRIAL':
//                                $subscription_type = 'Upgrade Free Trial to Startup';
//                                $subscription_price = '$30/Month';
//                                break;
//                            case 'BSM_BETA':
//                                $subscription_type = 'Startup';
//                                $subscription_price = '$30/Month';
//                                break;
//                            case 'SML_BIZ':
//                                $subscription_type = 'Small Business';
//                                $subscription_price = '$50/Month';
//                                break;
//                            default:
//                                $subscription_type = 'Blue Skyline Markeing: SMS Outreach';
//                                break;
//                        }
                       
                       $subscription_info = explode('~', upgrade_price($subscription_type));
                       
                       $subscription_type = $subscription_info[0];
                       $subscription_price = $subscription_info[1];
                       
                       echo "<tr><td>$subscription_type</td><td>$subscription_price</td></tr>";       
                               
                       ?>
                   </tbody>
                </table>
                <div id="success"> </div>
                <div id="error"> </div>
                <form method="POST" id="payment-form">    
                    <div class="form-group">
                        <label>
                            <h4>Payment Method:</h4>
                        </label>
                        <select id="payment-type" name="payment-type" class="form-control" onmousedown="this.value='';" onchange="checkValue(this.value);">
                            <option value='payment_method'>Select Payment Method</option>
                            <option value="stored_payment">Stored Payment Method</option>
                            <option value="new_payment">New Payment Method</option>
                        </select>
                        <div id="new_payment_form"></div>
                    </div>
                    <input id="submit_payment" type="submit" class="btn btn-primary righty" value="Submit Payment">
                </form>
            </div>
        </div>
    </div>
</div>
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
<!-- Denied Access Modal -->

<!-- REQUIRED JS SCRIPTS -->
<!-- Bootstrap 3.3.7 -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<!--DataTables-->
<script src="../plugins/datatables/jquery-1.12.4.js"></script>
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css"></script>
<!--SlimScroll-->
<script src="../plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!--FastClick-->
<script src="../plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
<script src="../validation/front_end_validation.js"></script>
<script type="text/javascript">
    
    $(document).ready(function() {
        
        $("a[href='#']:not(.sidebar-toggle):not(#not-click)").on('click', function(e){
            e.stopPropagation();
            e.preventDefault();
            
            $('#denied-access').toggle(function(){
                $('#denied-access').addClass('pop-open');
            }, function(){
                $('#denied-access').removeClass('pop-open');
            });
            
            var checkClass = $('div:not(#denied-access):not(#denied-image):not(#denied-content)').hasClass('keep-it-cool');
            
            if(!checkClass){
                $('div:not(#denied-access):not(#denied-image):not(#denied-content)').addClass("keep-it-cool");
            }else{
                $('div:not(#denied-access):not(#denied-image):not(#denied-content)').removeClass("keep-it-cool");
            }
            
        });
        
        
        $('body').on( 'click', function(){
            
            if( $('#denied-access').is(':visible') ){
                
                $('div:not(#denied-access):not(#denied-image):not(#denied-content)').removeClass("keep-it-cool");
                
                $('#denied-access').toggle(function(){
                    $('#denied-access').removeClass('pop-open');
                }, function(){
                    $('#denied-access').addClass('pop-open');
                });
                
            }
            
        });

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
               
        document.getElementById('settings').addEventListener('click', function(){
                var url = event.currentTarget.id;
                reDirectContent(url);
            
            $('body').on('click', '#set_collect_emails', function(e){
                e.preventDefault();
                $('#set_collect_emails').prop("disabled", true);
                $('#collect_email_box #error').empty();
                $('#collect_email_box #success').empty();
                var collect_emails_answer = $("input[name='email_answer']:checked").val();
                
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function(){
                    if(this.readyState == 4 && this.status == 200){
                        
                        if(xmlhttp.responseText == 404){
                            
                            $('.collect_emails_box #error').html("<i class='fa fa-times-circle'></i>We seem to be having a problem. Please contact us if this problem continues.");   
                            
                             $("#set_collect_emails").prop("disabled", false);
                            
                            
                        }else{
                            $("#set_collect_emails").prop("disabled", false);
                            
                            var htmltag = document.getElementById('mainContent');
                            var attr = document.createAttribute("w3-include-html");
                            attr.value = xmlhttp.responseText;
                            htmltag.setAttributeNode(attr);
                           
                            w3.includeHTML();
                            
                    
                        }
                    }
                }
            
            xmlhttp.open("POST", "process/collect_email_process.php?collect_emails_answer="+collect_emails_answer, true);
            xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xmlhttp.send("collect_emails_answer="+collect_emails_answer);
            });

        });
        
        $('#payment-form').on('submit', function(e){
            e.preventDefault();
            $('#submit_payment').prop('disabled', true);
            var payment_method = document.getElementById('payment-type').value;
            
            if(payment_method == 'stored_payment'){
                //Pass along
                var stripeToken = 'token';
                
                // Submit the form
                $('#submit_payment').prop('disabled', true);
                $('#success').empty();
                $('#error').empty();
                
                
                //Ajax
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function(){
                    if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                        
                        alert(xmlhttp.responseText);
                        
                        var response = parseInt(xmlhttp.responseText);
                
                        alert(response);
                        
                        switch(response){
                            case 404:
                                
                                $("#error").html("<i class='fa fa-times-circle'></i>We are currently having problems with your system. Please try again later.");
                                
                                $('#submit_payment').prop('disabled', false);
                                
                                break;
                            case 606:
                                
                                $('#error').html("<i class='fa fa-times-circle'></i>Payment failed. Please check everything is correct and the card is valid.");
                                
                                $('#submit_payment').prop('disabled', false);
                                
                                break;
                            case 101:
                                var success = $('#success');
                                var error = $('#error');
                                
                                success.html("<i class='fa fa-check-circle'></i>The payment was successful. You will now be redirected.");
                                
                                setTimeout(function(){
                                    $(location).attr('href', '../logout.php');
                                }, 3000);
                                
                                setTimeout(function(){
                                    error.html("<i class='fa fa-times-circle'></i>Oops...Sign out and Log in and your account should be active. If problems procceed, please contact us.");    
                                }, 5000);
                                
                                break;
                                
                        }
                        
                    }
                }
                
                xmlhttp.open("POST", "../process/make_payment_process.php?method=old_payment&stripeToken="+stripeToken, true);
                xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xmlhttp.send("method=old_payment&stripeToken="+stripeToken);
            }else{
                $('#error').empty();
                $('#error').html("<i class='fa fa-times-circle'></i>Please choose a payment method.");
                $('#submit_payment').prop('disabled', false);
            }
            
        });
        
        
    });
</script>     
</body>
</html>