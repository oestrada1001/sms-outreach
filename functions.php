<?php
function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

//Secure Random Number
function crypto_rand_secure($min, $max)
{
    $range = $max - $min;
    if ($range < 1) return $min; // not so random...
    $log = ceil(log($range, 2));
    $bytes = (int) ($log / 8) + 1; // length in bytes
    $bits = (int) $log + 1; // length in bits
    $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
    do {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter; // discard irrelevant bits
    } while ($rnd > $range);
    return $min + $rnd;
}
//ReAuthentication Token
function getToken($length)
{
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "0123456789";
    $max = strlen($codeAlphabet); // edited

    for ($i=0; $i < $length; $i++) {
        $token .= $codeAlphabet[crypto_rand_secure(0, $max-1)];
    }

    return $token;
}

//Register.php - Returns Page Headline, Sub-headline(freeTrial), and Pricing Tab 
function subscription_data($plan){
switch($plan){
    case 'freeTrial':
        $header_line = "<h1>14-Day Free Trial</h1><p><b>Heads-up:</b> Free Trials will be <i>upgraded and charged automatically</i> if they are not canceled by the end of the trial.</p> ~ <div id='beta_table' class='lj-pricing-table-normal lj-pricing-table-featured wow bounceIn' data-wow-delay='0.5s'><div><h4>Free Trial</h4><p>14 day trial before you buy.</p><span>FREE<span></span></span><ul><li>500 Text Messages</li><li>Unlimited Contacts</li><li>Data Widgets of Subscribers and Check-ins</li></ul></div></div>";
        return $header_line;
        break;
    case 'startup':
        $header_line = "<h1>Startup Subscription Plan</h1> ~ <div id='beta_table' class='lj-pricing-table-normal lj-pricing-table-featured wow bounceIn' data-wow-delay='0.5s'><span>Limited Time Only!</span><div><h4>Startup</h4><p>Simple but Efficient.</p><span>$30<span>/ month</span></span>
        <ul><li>1,000 Text Messages</li><li>Unlimited Contacts</li><li>Data Widgets of Subscribers and Check-ins</li></ul></div></div>";
        return $header_line;
        break;
    case 'small_business':
        $header_line = "<h1>Small Business Subscription Plan</h1> ~ <div id='small_business' class='lj-pricing-table-normal lj-pricing-table-featured wow bounceIn' data-wow-delay='0.5s'><div><h4>Small Business</h4><span>$50<span>/ month</span></span><ul>
        <li>2,000 Text Messages</li><li>Unlimited Contacts</li><li>Data Widgets of Subscribers and Check-ins</li></ul></div></div>";
        return $header_line;
        break;
    case 'growth':
        $header_line = "<h1>Growth Subscription Plan</h1> ~ <div class='col-md-12 lj-pricing-table-normal wow bounceIn' data-wow-delay='0.5s'>
          <div>
            <h4>Growth Plan</h4>
            <p>What you need and how you need it.</p>
            <span>$80<span> / month</span></span>
            <ul>
              <li>2500 Text Messages</li>
              <li>Unlimited Contacts</li>
              <li>Data Widgets of Subscribers and Check-ins</li>
            </ul>
          </div>
        </div>";
        return $header_line;
        break;
    case 'expansion':
        $header_line = "<h1>Expansion Subscription Plan</h1> ~ <div class='col-md-12 lj-pricing-table-normal wow bounceIn' data-wow-delay='0.5s'>
          <div>
            <h4>Expansion Plan</h4>
            <p>What you need and how you need it.</p>
            <span>$125<span> / month</span></span>
            <ul>
              <li>3000 Text Messages</li>
              <li>Unlimited Contacts</li>
              <li>Data Widgets of Subscribers and Check-ins</li>
            </ul>
          </div>
        </div>";
        return $header_line;
        break;
    //Quarterly Plans
    case 'quarterly-startup':
        $header_line = "<h1><b>Quarterly</b> - Startup Subscription Plan</h1> ~ <div class='col-md-12 lj-pricing-table-normal lj-pricing-table-featured'>
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
          </div>
        </div>";
        return $header_line;
        break;
    case 'quarterly-small_business':
        $header_line = "<h1><b>Quarterly</b> - Small Business Subscription Plan</h1> ~ <div class='col-md-12 lj-pricing-table-normal'>
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
          </div>
        </div>";
        return $header_line;
        break;
    case 'quarterly-growth':
        $header_line = "<h1><b>Quarterly</b> - Growth Subscription Plan</h1> ~ <div class='col-md-12 lj-pricing-table-normal wow bounceIn' data-wow-delay='0.5s'>
          <div>
            <h4>Growth Plan</h4>
            <p>What you need and how you need it.</p>
            <span>$240<span> / 3 Mths</span></span>
            <ul>
              <li>7,500 Text Messages</li>
              <li>Unlimited Contacts</li>
              <li>Data Widgets of Subscribers and Check-ins</li>
            </ul>
          </div>
        </div>";
        return $header_line;
        break;
    case 'quarterly-expansion':
        $header_line = "<h1><b>Quarterly</b> - Expansion Subscription Plan</h1> ~ <div class='col-md-12 lj-pricing-table-normal wow bounceIn' data-wow-delay='0.5s'>
          <div>
            <h4>Expansion Plan</h4>
            <p>What you need and how you need it.</p>
            <span>$360<span> / 3 Mths</span></span>
            <ul>
              <li>9,000 Text Messages</li>
              <li>Unlimited Contacts</li>
              <li>Data Widgets of Subscribers and Check-ins</li>
            </ul>
          </div>
        </div>";
        return $header_line;
        break;
    default:
        $header_line = null;
        return $header_line;
        break;
        
        
}
}

//reg_subscriber.php
function subscription_plan($subscription_type){
    
    switch($subscription_type){
            case 'freeTrial':
                $access = "1-FREE_TRIAL";
                return $access;
                break;
            case 'startup':
                $access = "1-BSM_BETA";
                return $access;
                break;
            case 'quarterly-startup':
                $access = "1-QTRLY_BSM_BETA";
                return $access;
                break;
            case 'small_business':
                $access = "1-SML_BIZ";
                return $access;
                break;
            case 'quarterly-small_business':
                $access = "1-QTRLY_SML_BIZ";
                return $access;
                break;
            case 'growth':
                $access = "1-GRWTH";
                return $access;
                break;
            case 'quarterly-growth':
                $access = "1-QTRLY_GRWTH";
                return $access;
                break;
            case 'expansion':
                $access = "1-EXPNSN";
                return $access;
                break;
            case 'quarterly-expansion':
                $access = "1-QTRLY_EXPNSN";
                return $access;
                break;
            default:
                $access = null;
                return $access;
                break;
        }
    
}

//client_webhooks.php - starts line 157 && line 250
function retrieveAccess_number($plan){
    
    switch($plan){
            case 'FREE_TRIAL':
                $access_number = 1;
                return $access_number;
                break;
            case 'SML_BIZ':
                $access_number = 1;
                return $access_number;
                break;
            case 'QTRLY_SML_BIZ':
                $access_number = 1;
                return $access_number;
                break;
            case 'BSM_BETA':
                $access_number = 1;
                return $access_number;
                break;
            case 'QTRLY_BSM_BETA':
                $access_number = 1;
                return $access_number;
                break;
            case 'GRWTH':
                $access_number = 1;
                return $access_number;
                break;
            case 'QTRLY_GRWTH':
                $access_number = 1;
                return $access_number;
                break;
            case 'EXPNSN':
                $access_number = 1;
                return $access_number;
                break;
            case 'QTRLY_EXPNSN':
                $access_number = 1;
                return $access_number;
                break;
            default:
                $access_number = 0;
                return $access_number;
                break;
        }
    
}

function subscription_name($subscription_type){
    switch($subscription_type){
        case 'FREE_TRIAL':
                $subscription_type = 'Free Trial';
                return $subscription_type;
                break;
            case 'SML_BIZ':
                $subscription_type = 'Small Business';
                return $subscription_type;
                break;
            case 'QTRLY_SML_BIZ':
                $subscription_type = '<b>Quarterly</b>: Small Business';
                return $subscription_type;
                break;
            case 'BSM_BETA':
                $subscription_type = 'Startup';
                return $subscription_type;
                break;
            case 'QTRLY_BSM_BETA':
                $subscription_type = '<b>Quarterly</b>: Startup';
                return $subscription_type;
                break;
            case 'GRWTH':
                $subscription_type = 'Growth';
                return $subscription_type;
                break;
            case 'QTRLY_GRWTH':
                $subscription_type = '<b>Quarterly</b>: Growth';
                return $subscription_type;
                break;
            case 'EXPNSN':
                $subscription_type = 'Expansion';
                return $subscription_type;
                break;
            case 'QTRLY_EXPNSN':
                $subscription_type = '<b>Quarterly</b>: Expansion';
                return $subscription_type;
                break;
            default:
                $subscription_type = 'Blue Skyline Marketing: SMS Outreach';
                return $subscription_type;
                break;
    }    
    
}

function upgrade_price($subscription_type){
        switch($subscription_type){
        case 'FREE_TRIAL':
                $subscription_type = 'Upgrade your Free Trial to Startup ~ $30/Month';
                return $subscription_type;
                break;
            case 'SML_BIZ':
                $subscription_type = 'Small Business ~ $50/Month';
                return $subscription_type;
                break;
            case 'QTRLY_SML_BIZ':
                $subscription_type = '<b>Quarterly</b>: Small Business ~ $150/3 Month';
                return $subscription_type;
                break;
            case 'BSM_BETA':
                $subscription_type = 'Startup ~ $30/Month';
                return $subscription_type;
                break;
            case 'QTRLY_BSM_BETA':
                $subscription_type = '<b>Quarterly</b>: Startup ~ $150/3 Month';
                return $subscription_type;
                break;
            case 'GRWTH':
                $subscription_type = 'Growth ~ $80/Month';
                return $subscription_type;
                break;
            case 'QTRLY_GRWTH':
                $subscription_type = '<b>Quarterly</b>: Growth ~ $240/3 Month';
                return $subscription_type;
                break;
            case 'EXPNSN':
                $subscription_type = 'Expansion ~ $125 Month';
                return $subscription_type;
                break;
            case 'QTRLY_EXPNSN':
                $subscription_type = '<b>Quarterly</b>: Expansion ~ $360/3 Months';
                return $subscription_type;
                break;
            default:
                $subscription_type = 'Blue Skyline Marketing: SMS Outreach';
                return $subscription_type;
                break;
    }    
}
//bulk_sms.php - line 36 - cronjob -inactive_sms.php - line 37
function max_amount_allowed($subscription_type){
        switch($subscription_type){
        case 'FREE_TRIAL':
                $max_amount = 500;
                return $max_amount;
                break;
            case 'SML_BIZ':
                $max_amount = 2000;
                return $max_amount;
                break;
            case 'QTRLY_SML_BIZ':
                $max_amount = 6000;
                return $max_amount;
                break;
            case 'BSM_BETA':
                $max_amount = 1000;
                return $max_amount;
                break;
            case 'QTRLY_BSM_BETA':
                $max_amount = 3000;
                return $max_amount;
                break;
            case 'GRWTH':
                $max_amount = 2500;
                return $max_amount;
                break;
            case 'QTRLY_GRWTH':
                $max_amount = 7500;
                return $max_amount;
                break;
            case 'EXPNSN':
                $max_amount = 3000;
                return $max_amount;
                break;
            case 'QTRLY_EXPNSN':
                $max_amount = 9000;
                return $max_amount;
                break;
            default:
                $max_amount = 500;
                return $max_amount;
                break;
    }    
}






?>