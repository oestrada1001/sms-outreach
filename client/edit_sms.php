<?php
//delivery_date;
//default_message;
//custom_message;
//after_date;
//after_message;

require_once('../session.php');

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


?>

<div id="editSMS" class="container-fluid">
    <div class="row">
        <div class="col-md-4 default_message">
            <div class="box box-success">
                <div class="box-header">
                    <h3 class="box-title">Default Message</h3><p>This is the message they will receive after they first subscribe. This is not mandatory.</p>
                </div>
                <div class="box-body">
                <div id="success"></div>
                <div id="error"></div>
                <form method="POST">
                    
                <div class="form-group">
                    <label>
                        Default SMS Message:
                    </label>
                    <textarea id="default_sms" type="text" class="form-control setMessage" row="4"></textarea>
                </div>
                <input type="reset" class="btn btn-default" value="Reset">
                <input id="set_default_msg" type="submit" class="btn btn-primary righty" value="Set Default Message">
                </form>
                </div>
            </div>
        </div>
        <div class="col-md-4 custom_message">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Custom Message</h3><p>This is a custom message you want to send to your subscribers at a given date.</p>
                </div>
                <div class="box-body">
                <div id="success"></div>
                <div id="error"></div>    
                <form method="POST">
                    <div class="form-group">
                        <label>Schedule Date:</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input id="outbound_date" type="date" class="form-control">
                        </div>
                            <label>Message:</label>
                            <textarea id="custom_sms" type="text" class="form-control setMessage" row="4"></textarea>
                    </div>
                    <input type="reset" class="btn btn-default" value="Reset">
                    <input type="submit" id="set_custom_msg" class="btn btn-primary righty" value="Set Custom Message">
                </form>
                </div>
            </div>
        </div>
        <div class="col-md-4 inactive_message">
            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title">Inactive Customer Message</h3><p>This message is for subscribers that havent checked-in in a given amount of time.</p>
                </div>
                <div id="success"></div>
                <div id="error"></div>
                <div class="box-body">
                    <form method="POST">
                        <div class="form-group">
                            <label>Inactive Days</label>
                            <input id="inactive_days" type="number" class="form-control">
                            <label>Message:</label>
                            <textarea class="form-control" id="after_sms" row="4"></textarea>
                        </div>
                        <input type="reset" class="btn btn-default" value="Reset">
                        <input type="submit" id="set_inactive_msg" class="btn btn-primary righty" value="Set Inactive Message">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 visit_message">
            <div class="box box-warning">
                <div class="box-header">
                    <h3 class="box-title">Visit Achievement Message</h3><p>This message is sent after they have achieved the amount of check-ins required.</p>
                </div>
                <div id="success"></div>
                <div id="error"></div>
                <div class="box-body">
                    <form method="POST">
                        <div class="form-group">
                            <label># of visits to receive message</label>
                            <input id="visit_number" type="number" class="form-control">
                            <label>Message:</label>
                            <textarea class="form-control" id="visit_message" row="4"></textarea>
                        </div>
                        <input type="reset" class="btn btn-default" value="Reset">
                        <input type="submit" id="set_visit_msg" class="btn btn-primary righty" value="Set Visits Message">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>