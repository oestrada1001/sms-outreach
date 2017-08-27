<?php

if(!isset($row['email'])){
    header('location: ../logout.php');
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

$monthly_texts = $row['monthly_texts'];

$business_name = $row['business_name'];
$businessName = str_replace(' ', '_', strtolower($business_name)); //with slashes

//Query to get the dates from mysqli database
$check = "SELECT last_check_in FROM $businessName";
$check = mysqli_query($db_connect, $check);
//Creating Empty Arrays that will be used to store data
$data = [];
//Looping $check until out of data and using $row to store in $data
while ($base = $check->fetch_assoc()){
    $data[] = $base;
}


function uptoNow($current_date, $data, $timeFrame, $yearStability){

    $finaltotal = 0;
    $date = $current_date;
    $date = new DateTime($date);
    $datecompare = $date->format($timeFrame);
    
    $stability = $yearStability;
    $stability = new DateTime($stability);
    $stability = $stability->format('Y');
    
    foreach($data as $id=>$value){
        foreach($value as $check=> $register){
            
            $data_date = $register;
            $data_date = new DateTime($data_date);
            $totals = $data_date->format($timeFrame);
            
            $store_year = $register;
            $store_year = new DateTime($store_year);
            $store_year = $store_year->format('Y');
            
            if($datecompare == $totals && $store_year == $stability){
                $finaltotal++;
            }
        }
    }

    return $finaltotal;
    
}

function today_yesterday($today_yesterday, $data){
    
    $finaltotal = 0;
    $compare_date = $today_yesterday;
    
    foreach($data as $id=>$value){
        foreach($value as $check=>$register){
            if($compare_date == $register){
                $finaltotal++;
            }
        }
    }
    
    return $finaltotal;
    
}
//Variables being passed to the function
$todayFrame = 'd';
$weekFrame = 'W';
$monthFrame = 'm';
$yearFrame = 'Y';
$todays_date = date('Y-m-d');
$yesterdays_date = date('Y-m-d', strtotime("-1 day"));
$week_date = date('Y-m-d', strtotime("this week"));
$lastweekDate = date('Y-m-d', strtotime("last week"));
$lastmonthDate = date('Y-m-d', strtotime("last month"));
$lastyearDate = date('Y-m-d', strtotime("last year"));
$year = date('Y-m-d', strtotime('this year'));

//Today's Check Ins
$todayCheckIns = today_yesterday($todays_date, $data);
$yesterdayCheckIns = today_yesterday($yesterdays_date, $data);

//CheckIns this week, month, and year
$weekCheckIns = uptoNow($week_date, $data, $weekFrame, $year);
$monthCheckIns = uptoNow($todays_date, $data, $monthFrame, $year);
$yearCheckIns = uptoNow($todays_date, $data, $yearFrame, $year);
//CheckIns last week, month, and year
$lastweekCheckIns = uptoNow($lastweekDate, $data, $weekFrame, $year);
$lastmonthCheckIns = uptoNow($lastmonthDate, $data, $monthFrame, $year);
$lastyearCheckIns = uptoNow($lastyearDate, $data, $yearFrame, $year);

//Registration Query
$subscribe = "SELECT registration_date FROM $businessName";
$subscribe = mysqli_query($db_connect, $subscribe);
$data = [];
while($base = $subscribe->fetch_assoc()){
    $data[] = $base; 
}
//Today's Subscritions
$todaySubscriptions = today_yesterday($todays_date, $data);
$yesterdaySubscriptions = today_yesterday($yesterdays_date, $data);
//Subscriptions this week, month, and year
$weekSubscriptions = uptoNow($week_date, $data, $weekFrame, $year);
$monthSubscriptions = uptoNow($todays_date, $data, $monthFrame, $year);
$yearSubscriptions = uptoNow($todays_date, $data, $yearFrame, $year);
//Subscriptions last week, month, and year
$lastweekSubscriptions = uptoNow($lastweekDate, $data, $weekFrame, $year);
$lastmonthSubscriptions = uptoNow($lastmonthDate, $data, $monthFrame, $year);
$lastyearSubscriptions = uptoNow($lastyearDate, $data, $yearFrame, $year);

$recentCheckIns = array(
    array(
        'id'=>'today_check',
        "Today's check-ins"=> $todayCheckIns,
    ),
    array(
        'id'=>'yesterday_check',
        "This week's check-ins"=> $weekCheckIns,
    ),
    array(
        'id'=>'month_check',
        "This month's check-ins"=> $monthCheckIns,
    ),
    array(
        'id'=>'year_check',
       "This year's check-ins"=> $yearCheckIns 
    )
);

$lastCheckIns = ["Yesterday's check-ins"=> $yesterdayCheckIns,"Last week's check-ins"=> $lastweekCheckIns, "Last month's check-ins"=> $lastmonthCheckIns, "Last year's check-ins"=> $lastyearCheckIns];

$recentSubscriptions = [ "Today's subscriptions" => $todaySubscriptions , "This week's subscriptions"=> $weekSubscriptions, "This month's subscriptions"=> $monthSubscriptions, "This year's subscriptions"=> $yearSubscriptions];

$lastSubscriptions = ["Yesterday's subscriptions" => $yesterdaySubscriptions, "Last week's subscriptions"=> $lastweekSubscriptions, "Last month's subscriptions"=> $lastmonthSubscriptions, "Last year's subscriptions"=> $lastyearSubscriptions];

?>
 <!-- Content Header (Page header) -->
<div id="client-home-dashboard">
    
    <section class="content-header text-center">
      <h1>
        <?php print_r($business_name); ?> Dashboard
      </h1>
      <br>
    </section>
    <?php
    
    if($monthly_texts == 0 || $monthly_texts == null){
        ?>
        
        <div class="col-md-12 monthly_texts">
            <div class="box box-danger">
                <div class="box-header text-center">
                    <h3 class="box-title">Approximately # of text messages a subscriber will receive a month. <span class="help-tip"><p><img src="img/text-to-expect.jpg"></p></span></h3>
                    <div id="success"></div>
                    <div id="error"></div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-8">
                           <p><b>Important:</b> In order to provide the best possible service to our clients and your customers. We are required by law to state the number of text messages your customer should expect on a monthly basis. This does not mean you cannot go over but we recommend for you to stay as close as possible to the number you are going to set. We are not responsible for keeping track of the number of text messages you send and by setting a number you are acknowledging that you are responsible for any misuse of this service. To change the number of text messages you plan on sending a month please contact us at <i><a href="tel:+16617784504">661-368-5285</a></i> or email us at <i><a href="mailto:contact@blueskylinemarketing.com">contact@blueskylinemarketing.com</a></i><br><br><b>It is important to note that if you do not set a number you will not be able to collect subscribers.</b></p>
                            
                        </div>
                        <div class="col-md-4">
                            <form method="POST" class="form-inline">
                              <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                                  <span class="input-group-addon">APPROX.</span>
                                  <input class="form-control text-right" type="number" name="set_monthly" id="set_monthly" placeholder="ex. 7" min="1" required>
                                  <span class="input-group-addon"> Texts / Month Per Customer</span>
                               </div>
                                  
                                 <input id="set_monthly_texts" type="submit" class="btn btn-primary form-control" value="SET">
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    
    ?>                           
                               
    <div class="row">
       <div class="container-fluid">
           
        <div class="col-lg-6 col-md-12" id="checkin_table">
            <div class="small-box bg-aqua">
                <div class="inner">
                  <h2>Check-ins</h2>
                   <table class="table table-striped table-responsive">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Check-ins</th>
                        </tr>
                    </thead>
                    <tbody>
                       <tr>
                           <th>Today's Check-ins</th>
                           <th><?php print_r($todayCheckIns); ?></th>   
                       </tr>
                       <tr>
                           <th>Yesterday's Check-ins</th>
                           <th><?php print_r($yesterdayCheckIns); ?></th>   
                       </tr>
                       <tr>
                           <th>This week's Check-ins</th>
                           <th><?php print_r($weekCheckIns); ?></th>   
                       </tr>
                       <tr>
                           <th>Last week's Check-ins</th>
                           <th><?php print_r($lastweekCheckIns); ?></th>   
                       </tr>
                       <tr>
                           <th>This month's Check-ins</th>
                           <th><?php print_r($monthCheckIns); ?></th>   
                       </tr>
                       <tr>
                           <th>Last month's Check-ins</th>
                           <th><?php print_r($lastmonthCheckIns); ?></th>   
                       </tr>
                       <tr>
                           <th>This Year's Check-ins</th>
                           <th><?php print_r($yearCheckIns); ?></th>   
                       </tr>
                       <tr>
                           <th>Last year's Check-ins</th>
                           <th><?php print_r($lastyearCheckIns); ?></th>   
                       </tr>
                       
                    </tbody>
                    
                   </table>
                </div>    
                <div class="icon">
                    <i class="fa fa-calendar"></i>
                </div>
              
            </div>
        </div>
 
        <div class="col-lg-6 col-md-12" id="subscription_table">
            <div class="small-box bg-yellow">
                <div class="inner">
                   <h2>Subscriptions</h2>
                    <table class="table table-striped table-responsive">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Subscriptions</th>
                        </tr>
                    </thead>
                    <tbody>
                       <tr>
                           <th>Today's Subscriptions</th>
                           <th><?php print_r($todaySubscriptions); ?></th>   
                       </tr>
                       <tr>
                           <th>Yesterday's Subscriptions</th>
                           <th><?php print_r($yesterdaySubscriptions); ?></th>   
                       </tr>
                       <tr>
                           <th>This week's Subscriptions</th>
                           <th><?php print_r($weekSubscriptions); ?></th>   
                       </tr>
                       <tr>
                           <th>Last week's Subscriptions</th>
                           <th><?php print_r($lastweekSubscriptions); ?></th>   
                       </tr>
                       <tr>
                           <th>This month's Subscriptions</th>
                           <th><?php print_r($monthSubscriptions); ?></th>   
                       </tr>
                       <tr>
                           <th>Last month's Subscriptions</th>
                           <th><?php print_r($lastmonthSubscriptions); ?></th>   
                       </tr>
                       <tr>
                           <th>This Year's Subscriptions</th>
                           <th><?php print_r($yearSubscriptions); ?></th>   
                       </tr>
                       <tr>
                           <th>Last year's Subscriptions</th>
                           <th><?php print_r($lastyearSubscriptions); ?></th>   
                       </tr>
                       
                    </tbody>
                    
                   </table>
                </div>    
                <div class="icon">
                    <i class="fa fa-calendar"></i>
                </div>
              
            </div>
        </div>


</div>
</div>           
</div>
        
