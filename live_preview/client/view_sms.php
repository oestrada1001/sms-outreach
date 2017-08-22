<?php
require_once('../../preview_links.php');

if(!isset($row['email'])){
    header("location: ../index.php");
}
$client_verification = $_SESSION['client_verification'];
if($client_verification == 'unapproved'){
    header("location: https://www.blueskylinemarketing.com/logout.php");
    exit();
}elseif($client_verification != 'approved' && $client_verification != 'unapproved'){
    header("location: https://www.blueskylinemarketing.com/logout.php");
}

if($row['default_message'] == null){
    $row['default_message'] = 'You do not have a Default Message set.';
}
if($row['custom_message'] == null){
    $row['custom_message'] = 'You do not have a Custom Message set.';
    $row['delivery_date'] = 'Date not set.';
}
if($row['after_message'] == null){
    $row['after_message'] = 'You do not have an Inactive Message set.';
    $row['after_date'] = 'No ';
}
if($row['visit_goal'] == null){
    $row['visit_goal'] = 'No';
    $row['visit_message'] = 'You do not have a Visit Achievement Message set.';
}

?>

<div id="viewSMS" class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="box box-success">
                <div class="box-header">
                    <h3 class="box-title">Default Message</h3><p>This is the message they will receive after they first subscribe. This is not mandatory.</p>
                </div>
                <div class="box-body">
                       <blockquote class="lj-blockquote">
                           <?php echo $row['default_message']; ?>
                       </blockquote>
                    <input type="submit" class="btn btn-danger" id="null_default" value="Delete Message">
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Custom Message</h3><p>This is a custom message you want to send to your subscribers at a given date.</p>
                </div>
                <div class="box-body">
                   <blockquote class="lj-blockquote">
                       <footer class="lj-blockquote-footer"><?php echo $row['delivery_date']; ?></footer>
                       <?php echo $row['custom_message']; ?>
                   </blockquote>
                    <input type="submit" class="btn btn-danger" id="null_custom" value="Delete Message">
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title">Inactive Customer Message</h3><p>This message is for subscribers that havent checked-in in a given amount of time.</p>
                </div>
                <div class="box-body">
                   <blockquote class="lj-blockquote">
                       <footer class="lj-blockquote-footer"><?php echo $row['after_date'] . ' days'; ?></footer>
                       <?php echo $row['after_message']; ?>
                   </blockquote>
                    <input type="submit" class="btn btn-danger" id="null_inactive" value="Delete Message">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title">Visit Achievement Message</h3><p>This message is sent after they have achieved the amount of check-ins required.</p>
                </div>
                <div class="box-body">
                   <blockquote class="lj-blockquote">
                       <footer class="lj-blockquote-footer"><?php echo $row['visit_goal'] . ' Check-ins'; ?></footer>
                       <?php echo $row['visit_message']; ?>
                   </blockquote>
                    <input type="submit" class="btn btn-danger" id="null_visit" value="Delete Message">
                </div>
            </div>
        </div>
    </div>
</div>