<?php
require_once('db_links.php');


$sql = 'SELECT * FROM clients';
$result = mysqli_query($db_connect, $sql);

$result = mysqli_num_rows($result);

$subscriptions_left =  75 - $result;

$category_list = [
    'software_apps_website' => 'Software/Apps/Websites', 
    'clothing' => 'Clothing', 
    'tools_equipment' => 'Tools/Equipment', 
    'brand' => 'Brand', 
    'electronics' => 'Electronics', 
    'games' => 'Games', 
    'pet_supplies' => 'Pet Supplies', 
    'office_supplies' => 'Office Supplies',
    'patio_garden' => 'Patio/Garden', 
    'health_beauty' => 'Health/Beauty', 
    'barbershop' => 'Barbershop', 
    'restaurant' => 'Restaurant', 
    'ecommerce' => 'E-commerce', 
    'gym' => 'Gym', 
    'freelance' => 'Freelance Services', 
    'other' => 'Other',
    'retail' => 'Retail'
];

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="title" content="Blue Skyline Marketing : SMS Marketing, Bulk Messaging, Mass Texting">
    <meta name="description" content="Stop losing customers and start bringing them in. Our bulk text messaging platform lets you customize and schedule mass sms messages. Our mass texting platform includes your statistics so you can improve your strategy.">
    <meta name="keywords" content="mass texting from computer, mass texting services, best mass texting service, bulk text message, bulk text messaging">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta property="og:url"                content="https://www.blueskylinemarketing.com">
    <meta property="og:type"               content="website">
    <meta property="og:title"              content="Blue Skyline Marketing: SMS Outreach">
    <meta property="og:description"        content="SMS Outreach is a text messaging marketing tool that allow you to see what promotions and discounts work for YOUR business. Try before you buy with our 30 free-trial.">
    <meta property="og:image"              content="https://www.blueskylinemarketing.com/smsOutreach.jpg" />
    <title>Blue Skyline Marketing: SMS Marketing, Bulk Messaging, Mass Texting</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  
    <!-- Favicon -->
    <link rel="shortcut icon" href="img/favicon.png">
    
    <!-- Font-Awesome & Icons -->
    <script src="https://use.fontawesome.com/cb6cb41e71.js"></script>

    <!-- Main CSS file -->
    <link rel="stylesheet" href="css/style.css">

    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/landingpage.css" type="text/css">
    <script>
        
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-102428282-1', 'auto');
  ga('send', 'pageview');
        
  function checkValue(value){
      
      var input_box = document.getElementById('if_other');
      
        if(value == 'other'){
        	input_box.innerHTML = "<input type='text' class='form-control' id='other_input' name='other_option' placeholder='Type of Business'>";
        }else{
            input_box.innerHTML = ' ';
        }
  }        

</script>
  </head>
  <body>

  <!-- PRELOADER -->
  <div class="lj-preloader"></div>
  <!-- /PRELOADER -->
    <div class="modal fade marketing_modal" id="marketing_modal" style="z-index: 1000;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <a href="#" data-dismiss="modal" class="class pull-right"><span class="glyphicon glyphicon-remove"></span></a>
                <div class="row">
                    <div class="col-md-12 subscription_content">
                        <h1 >Enter your email to receice our <br><b>FREE marketing newsletters and strategies.</b></h1>
                        <div id="subscription_return_msg"></div>
                         <form id="specific_subscription_form" method="post" class="clearfix">
                         <div class="form-group">
                          <input type="text" value="" name="subscriber_name" id="subscriber-name" class="form-control" placeholder="Your name">
                          <input type="text" value="" name="subscriber-email" id="subscriber-email" class="form-control" placeholder="Your e-mail">
                          <select id="business-type" name="business-type" class="form-control" onmousedown="this.value='';" onchange="checkValue(this.value);">
                              <option value="business_type">Business Type</option>
                          <?php
                            
                            asort($category_list);  
                            foreach($category_list as $key=>$type){
                             echo "<option value='$key'>$type</option><br>";
                            }  
                          ?>
                          </select>
                          <div id="if_other"></div>
                             <button type="submit" id="subscribe_btn" class="btn btn-primary" value="join now">Sign me up<i class="fa fa-envelope-open"></i></button>
                         </div>
                        </form>
                 
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

  <!-- HEADER -->
  <header>
 
    <!-- HEADER OVERLAY-->
    <div class="lj-overlay lj-overlay-color"></div>
     <!--/HEADER OVERLAY -->
    
    <div id="firstCon" class="container">
    
        <!-- LOGO & SOCIALS -->
          <div class="row" id="topRow">
            <!-- LOGO -->
            <div class="col-sm-4 lj-logo wow fadeInDown">
              <a href="https://www.blueskylinemarketing.com" title="Blue Skyline Marketing SMS Outreach">
                <img src="img/white_outline_blue_skyline.png" class="lj-logo-1x" alt="Blue Skyline Marketing">
                <img src="img/white_outline_blue_skyline.png" class="lj-logo-2x" alt="BLue Skyline Marketing">
              </a>
            </div>
            <!-- /LOGO -->
            <!-- TEXT + BUTTON -->
            <div id="login-btn" class="col-sm-8 lj-text-button wow fadeInDown">
              <span>Already A Member?</span>
              <a href="login.php">Login</a>
            </div>
            <!-- /TEXT + BUTTON -->
          </div>
        <!-- /LOGO & SOCIALS -->
        
    <div class="col-md-4">
        <!-- TITLE -->
          <div class="row">
            <div class="col-xs-12 lj-title wow fadeInDown" data-wow-delay="0.5s">
              <h1 class="lj-text-center"><span>SMS Outreach</span></h1>
            </div>
          </div>
        <!-- /TITLE -->

        <!-- TITLE PARAGRAPH -->
          <div class="row" id="check-list">
            <div class="lj-title-paragraph wow fadeInDown" data-wow-delay="1s">
                <h3>SMS Marketing Checklist:</h3><p><i class="fa fa-check"></i>Mass Text Messaging<br><i class="fa fa-check"></i>Loyalty Reward Program<br><i class="fa fa-check"></i>SMS Marketing Statistics<br><i class="fa fa-check"></i>No Hidden Fees</p>
             
            </div>
          </div>
        <!-- /TITLE PARAGRAPH -->


      </div>
      <div class="col-md-8 wow fadeInDown" id="screen_slide" data-wow-delay="0.5s">
        
         <div id="app_carousel" class="carousel slide">
             <div class="carousel-inner">
                 <figure id="first-img" class="item active">
                    <img src="img/frontpage_dash3_md.png" alt="SMS Outreach: SMS Marketing, Bulk Text Messages">
                     
                 </figure>
                 
                 <figure id="second-img" class="item">
                  <img src="img/frontpage_mass2_md.png"  alt="SMS Outreach: SMS Marketing, Bulk Text Messages">
                 </figure>
                 
                 <figure id="third-img" class="item">
                  <img src="img/frontpage_sub_md.png"  alt="SMS Outreach: SMS Marketing, Bulk Text Messages">
                 </figure>
             </div>
             
            <ol class="carousel-indicators">
                <li data-target="#app_carousel" data-slide-to="0" class="active"></li>
                <li data-target="#app_carousel" data-slide-to="1" class="active"></li>
            </ol>     
          
         </div>
          
      </div>        
        <!-- BUTTONS -->
          <div class="row" id="calltoaction">
            <h1 class ="text-center wow fadeInUp" data-wow-delay="1.0s" style="color: white;">Join The Newest SMS Marketing Platfrom And See Your Results Skyrocket.</h1>
            <div class="col-xs-12 col-sm-8 col-sm-offset-2 lj-buttons wow fadeInUp" data-wow-delay="1.5s">
              <a href="live_preview/login.php" target="_blank" class="lj-button-left">Live Preview <i id="live" class="fa fa-unlock"></i></a><a href="#pricing" class="lj-button-right">See Pricing</a>
            </div>
          </div>
        <!-- /BUTTONS -->
        <!-- SCROLL DOWN -->
          <div class="row">
            <div class="col-sm-12 lj-scroll-down wow fadeInUp" data-wow-delay="1s">
              <a href="#" title=""><i class="fa fa-chevron-down"></i></a>
            </div>
          </div>
        <!-- /SCROLL DOWN -->
    </div>
   
  </header>
     <div id="subscription_bg">
      <div class="container">
          <div class="row">
             <div class="col-md-8">
                 <h4>Sign up to our newsletter and receive <b>FREE Marketing Strategies</b>. We hate spam, too.</h4>
             </div>
             <div class="col-md-4">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#marketing_modal">Sign me up<i class="fa fa-envelope-open"></i></button>
              </div>
          </div>
      </div>
    </div>
  <!-- /HEADER -->

  <!-- PRODUCT BOX -->
  <div id="first_panel" class="product module">
    <div class="container">
      <!-- COLUMNS -->
      <div class="row">
        <div class="col-md-5 lj-product-image wow fadeInDown" data-wow-delay="0.5s">
          <img src="img/Phone5-edit-sms-portrait.png" alt="SMS Outreach: Bulk Text Messaging">
        </div>        
        <div class="col-md-7 lj-product">
          <h3><strong>Achieve Success</strong> with Bulk Text Messages</h3>
          <p>Our new bulk text messaging platform allows you to add and store your customer's information in your own personal database. It also allows you to send BULK text messages to <strong>ALL your customers</strong> when you have sales, promotions, special events and discounts.</p>
          <p>Our mass text messaging service allows you to keep track of how many people subscribed and checked in.</p> 
          <ul class="lj-product-features fa-ul">
            <li><i class="fa-li fa fa-long-arrow-right"></i> Starting is quick and easy</li>
            <li><i class="fa-li fa fa-long-arrow-right"></i> Simple to set up</li>
            <li><i class="fa-li fa fa-long-arrow-right"></i> Ready to send mass text messages with a computer, tablet and phone.</li>
          </ul>
          <a href="#features" class="lj-product-button-left">Check features <i class="fa fa-long-arrow-right"></i></a><a href="#pricing" class="lj-product-button-right">Purchase</a>
        </div>
      </div>
      <!-- /COLUMNS -->
    </div>
  </div>
  <!-- /PRODUCT BOX -->

  <!-- PRODUCT BOX #2 -->
  <div class="product product-right module">
    <div class="container">
      <!-- COLUMNS -->
      <div class="row">
        <div class="col-md-7 lj-product">
          <h3>Simple. Intuitive. <strong>Powerful.</strong></h3>
          <p>Our bulk text messaging platform was created to help businesses boost their sales and increase their number of returning customers while getting new customers. </p>
          <ul class="lj-product-features fa-ul">
            <li><i class="fa-li fa fa-cog"></i> Full control</li>
            <li><i class="fa-li fa fa-globe"></i> Data-driven Statistics</li>
<!--            <li><i class="fa-li fa fa-shopping-cart"></i> Multiple Business Configuration</li>-->
            <li><i class="fa-li fa fa-shield"></i> Scalable and trusted</li>
          </ul>
          <a href="#pricing" class="lj-product-button-right">Get started today for FREE</a>
        </div>
        <div class="col-md-5 lj-product-image wow fadeInDown" data-wow-delay="0.5s">
          <img src="img/iphone-text.png" alt="SMS Outreach: Bulk Text Messaging">
        </div>
      <!-- /COLUMNS -->
      </div>
    </div>
  </div>
  <!-- /PRODUCT BOX #2 -->

  <!-- PHOTO -->
  <div class="photo module">
    <div class="container">
      <!-- TEXTS -->
      <div class="row">
        <div class="col-sm-8 lj-photo-texts">
          <h2><strong>Perfect solution</strong> for growing businesses</h2>
          <p>Bulk text message marketing allows you to reach a large number of people. Its perfect for promotion and sales. Customers keep their mobile phones with them the majority of the time, making it easy for you to communicate your message to them.</p>
          <a href="#pricing" class="lj-photo-button wow fadeInDown" data-wow-delay="0.5s">View plans &amp; pricings</a>
        </div>
      </div>
      <!-- /TEXTS -->
    </div>
  </div>
  <!-- /PHOTO -->

  <!-- ICONS -->
  <div class="icons module">
    <div class="container">
      <!-- TITLE -->
      <div id="features" class="row">
        <div class="col-sm-12 lj-icons-title">
          <h2><strong>Cool</strong> features</h2>
        </div>
      </div>
      <!-- /TITLE -->
      <!-- COLUMNS -->
      <div class="row">
        <div class="col-sm-6 col-md-3 lj-icon-box lj-icon-box-one wow fadeInDown" data-wow-delay="0.5s">
          <div>
            <span><i class="fa fa-address-book-o"></i></span>
            <h3>Personal Database</h3>
            <p>A personal database for your business to store your customers information. <b>We do not sell or share any personal information.</b></p>
          </div>
        </div>
        
        <div class="col-sm-6 col-md-3 lj-icon-box lj-icon-box-two wow fadeInDown" data-wow-delay="1s">
          <div>
            <span><i class="fa fa-line-chart"></i></span>
            <h3>Data Charts</h3>
            <p>SMS Outreach provides live up-to-date widgets and charts to see how many people checked-in and suscribed to your business on a day-to-day basis.</p>
          </div>
        </div>
        
        <div class="col-sm-6 col-md-3 lj-icon-box lj-icon-box-three wow fadeInDown" data-wow-delay="1.5s">
          <div>
            <span><i class="fa fa-calendar-plus-o"></i></span>
            <h3>Scheduled Messages</h3>
            <p>Schedule your mass text messages ahead of time to have the greatest impact. We also offer multiple default messages with time frames that will help you when scheduling your bulk text messages. </p>
          </div>
        </div>

        <div class="col-sm-6 col-md-3 lj-icon-box lj-icon-box-four wow fadeInDown" data-wow-delay="2s">
          <div>
            <span><i class="fa fa-user-circle-o"></i></span>
            <h3>Admin Dashboard</h3>
            <p>The dashboard will have everything concerning your account; widgets, charts, text message editing page, database, and settings.</p>
          </div>
        </div>
      </div>
      <!-- /COLUMNS -->
    </div>
  </div>
  <!-- /ICONS -->

  <!-- COUNTDOWN -->
<!--
  <div class="countdown module">
    <div class="container">
-->
      <!-- TITLE -->
<!--
      <div class="row">
        <div class="col-sm-8 col-sm-offset-2 lj-countdown-title">
          <h2><strong>Full Access</strong> starts in</h2>
        </div>
      </div>
-->
      <!-- /TITLE -->
      <!-- COUNTDOWN TIMER -->
<!--
      <div class="row">
        <div class="col-sm-12 lj-countdown wow fadeInDown" data-wow-delay="0.5s">
          <div class="row">

          </div>
        </div>
      </div>
-->
      <!-- /COUNTDOWN TIMER -->
<!--
    </div>
  </div>
-->
  <!-- /COUNTDOWN -->



  <!-- PHOTOS / TEXTS -->
  <div class="photos-texts module">
    <div class="container">
      <!-- ROW #1 -->
      <div class="row">
        <div class="col-sm-6 lj-photos-texts-image wow fadeInDown" data-wow-delay="0.5s">
          <img src="img/Tablet-landscape-subscribe.png" alt="SMS Outreach: Bulk text messaging opt-in">
        </div>        
        <div class="col-sm-6 lj-photos-texts">
          <h3>Simplicity</h3>
          <p>Easy to use. With a second password verification, you dont have to worry about them accessing your dashboard, viewing your customers information or even sending mass text messages. Just place it on your tables or your check-out counter and forget about it. You can also ask them for the information needed while finish their purchase transaction.</p>
        </div>
      </div>
      <!-- /ROW #1 -->
      <!-- ROW #2 -->
      <div class="row">
        <div class="col-sm-6 lj-photos-texts">
          <h3>Opportunities</h3>
          <p>With a up-to-date dashboard, you can view how many of your customers subscribed or checked-in on any given day. This gives you endless opportunities to see how effective your bluk text messages are. <strong>Very important information to see what works for YOU and YOUR BUSINESS.</strong></p>
        </div>
        <div class="col-sm-6 lj-photos-texts-image lj-photos-texts-image-right wow fadeInDown" data-wow-delay="0.5s">
          <img src="img/Tablet-landscape-dashboard.png" alt="SMS Outreach: Bulk text message statistics">
        </div>  
      </div>
      <!-- /ROW #2 -->
      <!-- ROW #3 -->
      <div class="row">
        <div class="col-sm-6 lj-photos-texts-image wow fadeInDown" data-wow-delay="0.5s">
          <img src="img/Tablet-landscape-database.png" alt="SMS Outreach: Bulk text message customer database">
        </div>        
        <div class="col-sm-6 lj-photos-texts">
          <h3>Personal</h3>
          <p>Every business is unique and we treat them that way. <strong>All widgets and charts are based on your business</strong>. Every single account has its own database and all widgets and charts are created based on that database. It is like having a marketing specialist at the tip of your fingers.</p>
        </div>
      </div>
      <!-- /ROW #3 -->
    </div>
  </div>
  <!-- /PHOTOS / TEXTS -->
  <!-- PROMO -->
  <div class="promo module">
    <div class="container">
      <div class="row">
        <div class="col-sm-12 lj-promo">
          <h2><span id="words"></span></h2>
          <a class="wow fadeInDown" data-wow-delay="0.5s" href="setup/register.php?plan=freeTrial"><i class="fa fa-shopping-cart"></i> Subscribe NOW</a>
        </div>
      </div>
    </div>
  </div>
  <!-- /PROMO -->
  <!-- PRICING TABLE -->
  <div id="pricing" class="pricing-table module">
    <div class="container">
      <!-- TITLE -->
      <div class="row">
        <div class="col-sm-8 col-sm-offset-2 lj-pricing-table-title">
          <h2>Pricing</h2>
          <p><b>Save with quarterly subscritions</b><br><button id='change-plans' style="font-weight: bold;" class="btn btn-primary">See Quarterly Subscriptions</button></p>
        </div>
      </div>
      <!-- /TITLE -->
      
      <!-- COLUMNS -->
      <div class="row" id="monthly_1">
         <div class="col-md-4 lj-pricing-table-normal wow bounceIn" data-wow-delay="1s">
          <div>
            <h4>Free Trial</h4>
            <p>14 day trial before you buy.</p>
            <span>FREE</span>
            <!--<span>$75<span>/ month</span></span>-->
            <ul>
              <li>500 Text Messages</li>
              <li>Unlimited Contacts</li>
              <li>Data Widgets of Subscribers and Check-ins</li>
            </ul>
            <a id="freeTrial" href="setup/register.php?plan=freeTrial">choose plan</a>
          </div>
        </div>
        <div class="col-md-4 lj-pricing-table-normal lj-pricing-table-featured wow bounceIn" data-wow-delay="0.5s">
         <span>Limited Time Only!</span>
          <div>
            <h4>Startup</h4>
            <p>Simple but Efficient.</p>
            <span>$30<span>/ month</span></span>
            <ul>
              <li>1,000 Text Messages</li>
              <li>Unlimited Contacts</li>
              <li>Data Widgets of Subscribers and Check-ins</li>
            </ul>
            <a id="beta" href="setup/register.php?plan=startup">choose plan</a>
          </div>
        </div>

        <div class="col-md-4 lj-pricing-table-normal wow bounceIn" data-wow-delay="1s">
         <!--<span>most popular</span>-->
          <div>
            <h4>Small Business</h4>
            <p></p>
            <span>$50<span>/ month</span></span>
            <ul>
              <li>2,000 Text Messages<li>
              <li>Unlimited Contacts</li>
              <li>Data Widgets of Subscribers and Check-ins</li>
            </ul>
            <a id="professional" href="setup/register.php?plan=small_business">choose plan</a>
          </div>
        </div>
        </div>
        
      <div class="row do-not-display" id="quarterly_1">
          <div class="col-md-4 lj-pricing-table-normal">
          <div>
            <h4>Free Trial</h4>
            <p>14 day trial before you buy.</p>
            <span>FREE</span>
            <!--<span>$75<span>/ month</span></span>-->
            <ul>
              <li>500 Text Messages</li>
              <li>Unlimited Contacts</li>
              <li>Data Widgets of Subscribers and Check-ins</li>
            </ul>
            <a id="freeTrial" href="setup/register.php?plan=freeTrial">choose plan</a>
          </div>
        </div>
           
        <div class="col-md-4 lj-pricing-table-normal lj-pricing-table-featured">
         <span>Limited Time Only!</span>
          <div>
            <h4>Startup</h4>
            <p>Simple but Efficient.</p>
            <span>$90<span>/ 3 Mths</span></span>
            <ul>
              <li>3,000 Text Messages</li>
              <li>Unlimited Contacts</li>
              <li>Data Widgets of Subscribers and Check-ins</li>
            </ul>
            <a id="beta" href="setup/register.php?plan=quarterly-startup">choose plan</a>
          </div>
        </div>

        <div class="col-md-4 lj-pricing-table-normal">
         <!--<span>most popular</span>-->
          <div>
            <h4>Small Business</h4>
            <p></p>
            <span>$150<span>/ 3 Mths</span></span>
            <ul>
              <li>6,000 Text Messages<li>
              <li>Unlimited Contacts</li>
              <li>Data Widgets of Subscribers and Check-ins</li>
            </ul>
            <a id="professional" href="setup/register.php?plan=quarterly-small_business">choose plan</a>
          </div>
        </div>

      </div>
      <br>
      <div class="row" id="monthly_2">
        <div class="col-md-4 lj-pricing-table-normal wow bounceIn" data-wow-delay="0.5s">
          <div>
            <h4>Growth Plan</h4>
            <p>What you need and how you need it.</p>
            <span>$80<span> / month</span></span>
            <ul>
              <li>2500 Text Messages</li>
              <li>Unlimited Contacts</li>
              <li>Data Widgets of Subscribers and Check-ins</li>
            </ul>
            <a id="growth" href="setup/register.php?plan=growth">Choose Plan</a>
          </div>
        </div>
        
         <div class="col-md-4 lj-pricing-table-normal wow bounceIn" data-wow-delay="0.5s">
          <div>
            <h4>Expansion Plan</h4>
            <p>What you need and how you need it.</p>
            <span>$125<span> / month</span></span>
            <ul>
              <li>3000 Text Messages</li>
              <li>Unlimited Contacts</li>
              <li>Data Widgets of Subscribers and Check-ins</li>
            </ul>
            <a id="expansion" href="setup/register.php?plan=expansion">Choose Plan</a>
          </div>
        </div>
         
          <div class="col-md-4 lj-pricing-table-normal wow bounceIn" data-wow-delay="0.5s">
          <div>
            <h4>Enterprise</h4>
            <p>What you need and how you need it.</p>
            <span>$???<span> / month</span></span>
            <ul>
              <li>???? Text Messages</li>
              <li>Unlimited Contacts</li>
              <li>Data Widgets of Subscribers and Check-ins</li>
            </ul>
            <a id="investor" href="tel:+16613685285"><i class="fa fa-phone" style="margin-right: 5px;"></i> Call Now</a>
          </div>
        </div>
      </div>
      <div class="row do-not-display" id="quarterly_2">
        <div class="col-md-4 lj-pricing-table-normal">
          <div>
            <h4>Growth Plan</h4>
            <p>What you need and how you need it.</p>
            <span>$240<span> / 3 Mths</span></span>
            <ul>
              <li>7,500 Text Messages</li>
              <li>Unlimited Contacts</li>
              <li>Data Widgets of Subscribers and Check-ins</li>
            </ul>
            <a id="growth" href="setup/register.php?plan=quarterly-growth">Choose Plan</a>
          </div>
        </div>
        
         <div class="col-md-4 lj-pricing-table-normal">
          <div>
            <h4>Expansion Plan</h4>
            <p>What you need and how you need it.</p>
            <span>$360<span> / 3 Mths</span></span>
            <ul>
              <li>9,000 Text Messages</li>
              <li>Unlimited Contacts</li>
              <li>Data Widgets of Subscribers and Check-ins</li>
            </ul>
            <a id="expansion" href="setup/register.php?plan=quarterly-expansion">Choose Plan</a>
          </div>
        </div>
         
          <div class="col-md-4 lj-pricing-table-normal">
          <div>
            <h4>Enterprise</h4>
            <p>What you need and how you need it.</p>
            <span>$???<span> / month</span></span>
            <ul>
              <li>???? Text Messages</li>
              <li>Unlimited Contacts</li>
              <li>Data Widgets of Subscribers and Check-ins</li>
            </ul>
            <a id="enterprise" href="tel:+16613685285"><i class="fa fa-phone" style="margin-right: 5px;"></i> Call Now</a>
          </div>
        </div>
      </div>
      <!-- /COLUMNS -->
    </div>
  </div>
  <!-- /PRICING TABLE -->

  <!-- JOIN NOW -->
  <div class="join-now module">
    <div class="container">
      <div class="row">
        <!-- TITLE -->
        <div class="col-sm-8 col-sm-offset-2 lj-join-now-title">
          <h2><strong>Don't wait</strong> &amp; join now!</h2>
          <p>SMS Outreach will be expanding and offering bulk text messaging with new features and services. <strong>Be the first to know in order to get the best deals! JOIN OUR SUBSCRIPTION LIST NOW.</strong></p>
        </div>
        <!-- /TITLE -->
      </div>
      <div class="row">
        <!-- JOIN NOW FORM -->
        <div class="col-sm-12 lj-join-now-form wow fadeInUp" data-wow-delay="0.5s">
          <form id="join-now" method="post" class="clearfix">
              <input class="subscribe" type="text" value="" name="join-now-name" id="join-now-name" placeholder="Your name"><!--
              --><input type="text" value="" name="join-now-email" id="join-now-email" placeholder="Your e-mail"><!--
              --><input type="submit" id="bsmSub" value="join now" name="join-now-submit">
          </form>
          
          <!-- JOIN NOW FORM MESSAGE -->
          <div class="lj-join-now-message">
            
          </div>
          <!-- /JOIN NOW FORM MESSAGE -->
        </div>
        <!-- /JOIN NOW FORM -->
      </div>
    </div>
  </div>
  <!-- /JOIN NOW -->

  <!-- CONTACT -->
  <div class="contact module">
    <div class="container">
      <div class="row">
        <div id="followers" class="col-sm-4 lj-contact wow fadeInDown" data-wow-delay="0.5s">
          <h4>Follow us</h4>
          <p><a href="https://www.facebook.com/BlueSkylineMarketing/"><i class="fa fa-facebook fa-lg" aria-hidden="true"></i></a><a href="https://twitter.com/BSM_SmsOutreach/"><i class="fa fa-twitter fa-lg" aria-hidden="true"></i></a>
          <a href="https://www.linkedin.com/company-beta/18154180/"><i class="fa fa-linkedin fa-lg" aria-hidden="true"></i></a></p>
        </div>

        <div class="col-sm-4 lj-contact wow fadeInDown" data-wow-delay="0.5s">
          <h4>Contact</h4>
          <p><a href="mailto:contact@blueskylinemarketing.com">contact@blueskylinemarketing.com</a><br>
          <a href="tel:+16613685285">661.368.5285</a></p>
        </div>

        <div class="col-sm-4 lj-contact wow fadeInDown" data-wow-delay="0.5s">
          <h4>Tell us about you</h4>
          <p>We'd love to hear your ideas on what we can do to help you with.</p>
        </div>
      </div>
    </div>
  </div>
  <!-- /CONTACT -->

  <!-- FOOTER -->
  <div class="footer module">
    <div class="container">
      <div class="row">
        <div class="col-md-12 lj-footer-copyrights wow fadeInUp" data-wow-delay="0.5s">
          <p class="lj-text-center">Copyright &copy; <?php echo date('Y'); ?> <a href="https://www.blueskylinemarketing.com"> Blue Skyline Marketing</a>. All rights reserved. Read our <a href="../privacy.php" style="text-transform:initial;">Privacy Policy</a> and our <a href="terms.php" style="text-transform: initial;">Terms and Conditions</a></p>
        </div>

      </div>
    </div>
  </div>
  <!-- /FOOTER -->
  <!-- Bootstrap -->
  <script src="js/jquery-1.11.0.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="bootstrap/js/bootstrap.min.js"></script>
    <!-- jQuery -->
  <!-- Backstretch -->
  <script src="js/jquery.backstretch.min.js"></script>
  <!-- slick -->
  <script src="js/slick.min.js"></script>
  <!-- Featherlight -->
  <script src="js/featherlight.min.js"></script>
  <!-- WordsRotator -->
  <script src="js/jquery.wordrotator.min.js"></script>
  <!-- Countdown -->
  <script src="js/jquery.countdown.js"></script>
  <!-- Ajax mailchimp -->
  <!--<script src="js/jquery.ajaxchimp.js"></script>-->
  <!-- WOW.js -->
  <script src="js/wow.min.js"></script>
  <!-- Holy Wood js scripts -->
  <script src="js/lj-holywood.js"></script>
  <script 
  src="https://cdnjs.cloudflare.com/ajax/libs/approvejs/3.1.2/approve.min.js"></script>
  <script type="text/javascript">
      
$(document).ready(function(){
    
    $('#app_carousel').carousel({
              interval: 6000
    });
      
    var subscriptions_left = <?php echo json_encode($subscriptions_left); ?>;
    
        var window_width = window.innerWidth;
    var window_height = window.innerHeight;
    
    
    if(window_width >= 1200){
        $("header").backstretch("img/first_pic_xl.jpg");
    }
    if(window_width >= 992 && window_width < 1200){
        $("header").backstretch("img/first_pic_lg.jpg");
    }
    if(window_width >= 768 && window_width < 992){
        $("header").backstretch("img/first_pic_md.jpg");
    }
    if(window_width >= 576 && window_width < 768){
        $("header").backstretch("img/first_pic_sm.jpg");
    }else{
        $("header").backstretch("img/first_pic_sm.jpg");
    } 
    
    if(window_width >= 1200){
        $(".photo").backstretch("img/bg-2_xl.jpg");
    }
    if(window_width >= 992 && window_width < 1200){
        $(".photo").backstretch("img/bg-2_lg.jpg");
    }
    if(window_width >= 768 && window_width < 992){
        $(".photo").backstretch("img/bg-2_md.jpg");
    }
    if(window_width >= 576 && window_width < 768){
        $(".photo").backstretch("img/bg-2_sm.jpg");
    }else{
        $(".photo").backstretch("img/bg-2_sm.jpg");
    } 
    
    
    //$(".photo").backstretch("img/bg-2.jpg");    
    $(".photo-centered").backstretch("img/bg-3.jpg");
    
    if(window_width > 700 && document.body.scrollTop === 0){
        
        if (document.cookie.replace(/(?:(?:^|.*;\s*)pop_ad\s*\=\s*([^;]*).*$)|^.*$/, "$1") !== "true") {
            
            var d = new Date();
            d.setTime(d.getTime() + (24*60*60*1000));
            var expires = "expires="+ d.toUTCString();
            document.cookie = "pop_ad=true;" + expires + ";path=/";
            
            setTimeout(function(){
                
                $('#marketing_modal').modal('toggle');
            
            }, 2000);
            
            
        }
    
    } 
    
    $('#marketing_modal').on('hidden.bs.modal', function (e) {
        $(this)
        .find("input,textarea")
           .val('')
           .end()
        .find("input[type=checkbox], input[type=radio]")
           .prop("checked", "")
           .end()
        .find("select").val('business_type').end();
        
        $('#other_input').remove();
        
        $('#subscription_return_msg p').remove();
            
    });
    
    $('#specific_subscription_form').on('submit', function(e){
        e.preventDefault();
        
        var subscriber_name = $('#subscriber-name').val();
        var subscriber_email = $('#subscriber-email').val();
        var business_type = $('#business-type').val();
        
        if(business_type == 'other'){
            var business_type = $('#other_input').val().replace(/ /g,"_").toLowerCase();
            
        }
        
        
        xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function(){
            if(xmlhttp.readyState == 4 & xmlhttp.status == 200){
                
                xmlresponse = parseInt(xmlhttp.responseText);
                switch(xmlresponse){
                
                    case 606:
                        $('#subscription_return_msg').html("<p>We are having problems with our server, please try again.</p>");
                        break;
                    case 404:
                        $('#subscription_return_msg').html("<p>Sorry, please use the correct format.</p>");
                        break;
                    case 101:
                        $('#subscription_return_msg').html('<p>Thank you for subscribing. Please confirm your email to complete the process.</p>');
                        break;
                    default:
                        $('#subscription_return_msg').html("<p>We are currently having problems with our server. Please try again.</p>");
                        break;
                        
                        
                }
            }
        }
        
        xmlhttp.open("POST", "process/specific_subscription.php?subscriber_name="+subscriber_name+"&subscriber_email="+subscriber_email+"&business_type="+business_type, true);
        xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlhttp.send("subscriber_name="+subscriber_name+"&subscriber_email="+subscriber_email+"&business_type="+business_type);
 
    });
    //Toggle Prices - Monthly/Quarterly
        $('#change-plans').on('click', function(){
             
             var buttonContent = $('#change-plans').html();
             
             if(buttonContent == 'See Quarterly Subscriptions'){
                
                $(this).html('See Monthly Subscription');
                $('#monthly_1').addClass('do-not-display');
                $('#monthly_2').addClass('do-not-display');
                $('#quarterly_1').removeClass('do-not-display');
                $('#quarterly_2').removeClass('do-not-display');

             }else{
                
                 $(this).html('See Quarterly Subscriptions');
                 $('#monthly_1').removeClass('do-not-display');
                 $('#monthly_2').removeClass('do-not-display');
                 $('#quarterly_1').addClass('do-not-display');
                 $('#quarterly_2').addClass('do-not-display');
                 
             }
            
         });
    
});      
    
  </script>
  </body>
</html>