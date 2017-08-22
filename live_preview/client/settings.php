<?php
require_once('../../preview_links.php'); 

if(!isset($row['email'])){
    header("Location: ../login.php");
}
$client_verification = $_SESSION['client_verification'];
if($client_verification == 'unapproved'){
    header("location: https://www.blueskylinemarketing.com/logout.php");
    exit();
}elseif($client_verification != 'approved' && $client_verification != 'unapproved'){
    header("location: https://www.blueskylinemarketing.com/logout.php");
}

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
                    echo"";
                    break;
                case 'access':
                    $key = str_replace('_', ' ', $key);
                    $key = ucwords($key);
                    echo "<tr><th scrope='row'>Subscription</th>";
                        switch($value){
                            case 0:
                                echo "<td>Free Trial</td></tr>";
                                break;
                            case 1:
                                echo "<td>Startup Subscription</td></tr>";
                                break;
                            case 2:
                                echo "<td>Startup Subscription</td></tr>";
                                break;
                            case 3:
                                echo "<td>Professional Subscription</td></tr>";
                                break;
                            case 4:
                                echo "<td>Smart Investor Subscription</td></tr>";
                                break;
                                
                        }
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
               <a href="#" class="btn btn-danger">Unsubscribe Now</a>
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
</div>