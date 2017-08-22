<?php
require_once('session.php');
if(!isset($row['email'])){
    $login = "<a href='login.php'>Sign In</a>";
}else{
    $login = "<a href='logout.php'>Sign Out</a>";
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta property="og:url"                content="https://www.blueskylinemarketing.com">
    <meta property="og:type"               content="website">
    <meta property="og:title"              content="Blue Skyline Marketing: SMS Outreach">
    <meta property="og:description"        content="SMS Outreach is a text messaging marketing tool that allow you to see what promotions and discounts work for YOUR business. Try before you buy with our free-trial.">
    <meta property="og:image"              content="https://www.blueskylinemarketing.com/Blue-Skyline-Marketing-fb.png" />
    <title>Blue Skyline Marketing | Home</title>
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  
    <!-- Favicon -->
    <link rel="shortcut icon" href="img/favicon.png">

    <!-- Main CSS file -->
    <link rel="stylesheet" href="css/style.css">

    <!-- Font-Awesome & Icons -->
    <script src="https://use.fontawesome.com/cb6cb41e71.js"></script>
       
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/landingpage.css" type="text/css">
    <link rel="stylesheet" href="css/dashboard.css" type="text/css">
    <?php
      
      if(!isset($googleAnalytics) || $googleAnalytics == ' '){
       ?>   
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-102428282-1', 'auto');
  ga('send', 'pageview');
</script>
    <?php
      }else{
         print($googleAnalytics); 
      }
      
      ?>
  </head>
  <body>
   
    <div id="topNav" class="row">
        <div class="container">
        <div class="col-sm-4 lj-logo wow fadeInDown">
            <a href="https://www.blueskylinemarketing.com" title="">
                <img src="img/white_outline_blue_skyline.png" class="lj-logo-1x" alt=""></img>
                <img src="img/white_outline_blue_skyline.png" class="lj-logo-2x" alt=""></img>
            </a>
        </div>
        <div class="col-sm-8 wow fadeInDown text-right">
            <ul>
                <li><a href="../index.php">Home</a></li>
                <li><?php echo $login; ?></li>
            </ul>
        </div>
      </div>
    </div> 