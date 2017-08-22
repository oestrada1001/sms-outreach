<?php
require_once('../session.php'); 
require_once('../functions.php');

    
if(!isset($row['email'])){
    header("Location: ../logout.php");
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


$client_email = $row['email'];

$client_info_sql = "SELECT subscription_type FROM stripe_clients WHERE email = '$client_email'";
$client_results = mysqli_query($db_connect, $client_info_sql);
$client_results = mysqli_fetch_array($client_results, MYSQLI_ASSOC);


?>
<div id="settings">
    
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updatePersonalInfo" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
               <div class="modal-header">
                   <h5 class="modal-title" id="modalID"></h5>
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                   </button>
               </div>
                <form method="POST">
                   <div>
                    <div class="row" id="error"></div>
                    <div class="row" id="success"></div>
                    <input type="text" id="" class="updateInput form-control" required>
                    <input class="updateButton btn btn-primary" type="submit" value="">
                   </div>
                </form>
            </div>
        </div>
    </div>
</div>
 
  
   
    <div class="col-md-6">
        <div class="box box-primary">
        <div class="box-header with-border">
            <h1 class="box-title">Personal Information</h1>
        </div>
        <div class="box-body">
        <table class="table table-bordered">
           <tbody>
                
       <?php
        $subscription_type = $client_results['subscription_type'];
        
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
//        
        $subscription_type = subscription_name($subscription_type);       
               
        echo "<tr><th scope='row'>Subscription</th><td>$subscription_type</td></tr>";       
               
        foreach($row as $key => $value){
            
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
                case 'subscription_message':
                case 'loyalty_message':
                case 'subscription_link':
                    echo"";
                    break;
                default:
                    $key1 = str_replace('_', ' ', $key);
                    $key1 = ucwords($key1);
                    echo "<tr><th scope='row'>$key1</th>
                    <td>$value</td></tr>";
                    break;
                /* UPDATE button
                case 'email':
                    $key1 = str_replace('_', ' ', $key);
                    $key1 = ucwords($key1);
                    echo "<tr><th scope='row'>$key1</th>
                    <td>$value</td></tr>";
                    break;
                default:
                    $key1 = str_replace('_', ' ', $key);
                    $key1 = ucwords($key1);
                    echo "<tr><th scope='row'>$key1</th>
                    <td>$value<span id=".$key." data-toggle='modal' data-target='#updateModal' data-id=".$key." class='label label-primary updateInfo' >Update</span></td></tr>";
                break;
                    */
            }
        }
        
        ?>
           </tbody>
       </table>
       <div class="row text-center" style="margin-top: 20px;">
           <div class="unsubscribe-button">
               <p>Would you like to unsubscribe?</p>
               <a href="../client/unsubscribe.php" class="btn btn-danger">Unsubscribe Now</a>
           </div>
       </div>
        </div>
        </div>    
    </div>
    <div class="col-md-6 collect_emails_box">
        <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title">Do you want to collect emails?</h3><p>This allows your to collect emails from the subscription form.<br><u><b>NOTE:</b>It will make it a requirement in-order to subscribe.</u></p>
            </div>
            <div class="box-body">
                <div id="success"></div>
                <div id="error"></div>
                <form method="POST">    
                    <div class="form-group">
                        <label>
                            <h4>Collect Emails:</h4>
                        </label>
                           <br>
                            <input type="radio" name="email_answer" id="email_yes" value="yes"><span> Yes, collect emails and make it a requirement.</span>
                            <br>
                            <input type="radio" name="email_answer" id="email_no" value="no"><span> No, do not collect emails.</span>
                    </div>
                    <input id="set_collect_emails" type="submit" class="btn btn-primary righty" value="Submit Request">
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 subscription_link">
        <div class="box box-danger">
            <div class="box-header">
                <h3 class="box-title">Online Subscription Form Link</h3>
                <?php
                    if($row['subscription_link'] == null){
                        ?>
                        <p>This will create a public link for your account, in which you will be able to collect subscribers online.<br><u><b>Note:</b> You will not be able to change this once it is set.</u></p>
                        <?php
                    }
                ?>
            </div>
            <div class="box-body">
            <div id="success"></div>
            <div id="error"></div>
               <?php
                
                    if($row['subscription_link'] == null){
                        ?>   
                        <form method="POST">
                            <div class="form-group">
                                <label>
                                    <h4>Request Link Name:</h4>
                                </label>
                                <br>
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" name="link_requested" id="link_requested" placeholder="ex. Your Business Name" required>
                                    <!--<i class="fa fa-times form-control-feedback"></i>-->
                                </div>
                            </div>
                            <input id="set_subscription_link" type="submit" class="btn btn-primary righty" value="Submit Link Request">
                        </form>
                       <?php
                    }else{
                        $subscription_link = $row['subscription_link'];
                        $complete_subscription_link = 'https://www.blueskylinemarketing.com/subscriptions/'.$subscription_link;
                        
                        ?>
                        <div class="form-group has-feedback">
                            <input type="text" class="form-control" id="subscription_link" value="<?php echo $complete_subscription_link; ?>" style="cursor:pointer;" disabled>
                            <i class="fa fa-clipboard form-control-feedback"></i>
                        </div>
                        <?php
                    }
                ?>
            </div>
        </div>
    </div>
    
</div>