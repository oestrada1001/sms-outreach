<?php

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
        <div class="col-md-4 cta_headline_1">
            <div class="box box-success">
                <div class="box-header">
                    <h3 class="box-title">Headline &#35;1</h3><p>This headline will appear next to the subscription form.</p>
                </div>
                <div class="box-body">
                <div id="success"></div>
                <div id="error"></div>
                <form method="POST">
                    
                <div class="form-group">
                    <label>
                        Headline &#35;1:
                    </label>
                    <textarea id="headline_1_msg" type="text" class="form-control setMessage" row="4"></textarea>
                </div>
                <input type="reset" class="btn btn-default" value="Reset">
                <input id="set_cta_headline_1" type="submit" class="btn btn-primary righty" value="Set Headline &#35;1">
                </form>
                </div>
            </div>
        </div>
        <div class="col-md-4 cta_headline_2">
            <div class="box box-success">
                <div class="box-header">
                    <h3 class="box-title">Headline &#35;2</h3><p>This headline will appear below Headline &#35;1(Next to the subscription form).</p>
                </div>
                <div class="box-body">
                <div id="success"></div>
                <div id="error"></div>
                <form method="POST">
                    
                <div class="form-group">
                    <label>
                        Healine &#35;2:
                    </label>
                    <textarea id="headline_2_msg" type="text" class="form-control setMessage" row="4"></textarea>
                </div>
                <input type="reset" class="btn btn-default" value="Reset">
                <input id="set_cta_headline_2" type="submit" class="btn btn-primary righty" value="Set Headline &#35;2">
                </form>
                </div>
            </div>
        </div>


    </div>

</div>