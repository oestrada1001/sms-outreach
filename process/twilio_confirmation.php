<?php
require_once('../db_links.php');
require_once('../twilio-php-master/Twilio/autoload.php');
require_once('../cronjobs/Twilio/twilio_keys.php');

use Twilio\Twiml;
use Twilio\Rest\Client;

$response = new Twiml;
$business_number = $_POST['To'];
$client_number = $_POST['From'];
$client_message = strtolower(trim($_POST['Body']));

//6611231234 Format
$client_number = preg_replace("/(\+1)?/", "", $client_number);

if($client_message == 'stop'){
    
    $finding_business_sql = "SELECT * FROM twilio_service WHERE initial_phone_number = '$business_number'";
    $finding_results = mysqli_query($db_connect, $finding_business_sql);
    $finding_match = mysqli_num_rows($finding_results);
    
    if($finding_match == 1){
        
        $business_twilio = mysqli_fetch_array($finding_results, MYSQLI_ASSOC);
        $business_name = $business_twilio['business_name'];
        $business_name = str_replace(" ", "_", strtolower($business_name));
        
        $check_confirmation_status = "SELECT * FROM $business_name WHERE phone_number = '$client_number'";
        $check_confirmation_results = mysqli_query($db_connect, $check_confirmation_status);
        $clients_subscription_info = mysqli_fetch_array($check_confirmation_results, MYSQLI_ASSOC);
        $clients_current_status = $clients_subscription_info['confirmed'];
        
    
        if($clients_current_status == 'no'){
            header('Content-Type: text/xml');
            ?>
                <Response>
                    <Message>Your number has already been removed from out list, Thank you.</Message>
                </Response>
            <?php
            exit;
        }
        
        $client_confirmation_sql = "UPDATE $business_name SET confirmed = 'no' WHERE phone_number = '$client_number'";
        
        if(mysqli_query($db_connect, $client_confirmation_sql)){
            header('Content-Type: text/xml');
            ?>
                <Response>
                    <Message>Sorry for the inconveniece, You will now be removed from out list. Thank You.</Message>
                </Response>
            <?php
            exit;
        }else{
            header('Content-Type: text/xml');
            ?>
                <Response>
                    <Message>We seem to be having a problem. Please try again in a few minutes.</Message>
                </Response>
            <?php
            exit;
        }
    
        
    }elseif($finding_match == 0){
        
        $retrieve_all_MSiDs_sql = "SELECT message_service_id FROM twilio_service";
        $retrieve_all_MSiDs = mysqli_query($db_connect, $retrieve_all_MSiDs_sql);
        
        $all_MSiDs = [];
        
        while($MSiD = $retrive_all_MSiDs->fetch_assoc()){
            $all_MSiDs[] = $MSiD;
        }
        
        foreach($all_MSiDs as $message_id){
        
            $twilio = new Client($sid, $twilio_token);
        
            
            $MSiD_phoneNumbers = $twilio->messaging->v1->services("$message_id")->phoneNumbers->read();
            
            foreach($MSiD_phoneNumbers as $phoneNumber){
                
                if($business_number == $phoneNumber){
                    
                    //Get Owner of MSiD
                    $correct_business_sql = "SELECT * FROM twilio_service WHERE message_service_id = '$message_id'";
                    $correct_business_results = mysqli_query($db_connect, $correct_business_sql);
                    $correct_business_info = mysqli_fetch_array($correct_business_results, MYSQLI_ASSOC);
                    
                    $business_name = $correct_business_info['business_name'];
                    $business_name = str_replace(" ", "_", strtolower($business_name));
                    
                    $check_confirmation_status = "SELECT * FROM $business_name WHERE phone_number = '$client_number'";
                    $check_confirmation_results = mysqli_query($db_connect, $check_confirmation_status);
                    $clients_subscription_info = mysqli_fetch_array($check_confirmation_results, MYSQLI_ASSOC);
                    $clients_current_status = $clients_subscription_info['confirmed'];
        
    
                    if($clients_current_status == 'no'){
                        header('Content-Type: text/xml');
                        ?>
                            <Response>
                                <Message>Your number has already been removed from out list, Thank you.</Message>
                            </Response>
                        <?php
                        exit;
                    }
    
    
                    $client_confirmation_sql = "UPDATE $business_name SET confirmed = 'no' WHERE phone_number = '$client_number'";
                
                    if(mysqli_query($db_connect, $client_confirmation_sql)){
                        header('Content-Type: text/xml');
                        ?>
                        <Response>
                            <Message>Sorry for the inconveniece, You will now be removed from out list. Thank You.</Message>
                        </Response>
                        <?php    
                        exit;
                    }else{
                        header('Content-Type: text/xml');
                        ?>
                        <Response>
                            <Message>We seem to be having problems, please try again in a few minutes. </Message>
                        </Response> 
                        <?php    
                        exit;
                    }
                
                    exit;
                
                } 
            
            }
        
        }
        
    }
    
}

if($client_message == 'no'){
    
    $finding_business_sql = "SELECT * FROM twilio_service WHERE initial_phone_number = '$business_number'";
    $finding_results = mysqli_query($db_connect, $finding_business_sql);
    $finding_match = mysqli_num_rows($finding_results);
    
    if($finding_match == 1){
        
        $business_twilio = mysqli_fetch_array($finding_results, MYSQLI_ASSOC);
        $business_name = $business_twilio['business_name'];
        $business_name = str_replace(" ", "_", strtolower($business_name));
        
        $check_confirmation_status = "SELECT * FROM $business_name WHERE phone_number = '$client_number'";
        $check_confirmation_results = mysqli_query($db_connect, $check_confirmation_status);
        $clients_subscription_info = mysqli_fetch_array($check_confirmation_results, MYSQLI_ASSOC);
        $clients_current_status = $clients_subscription_info['confirmed'];
        
    
        if($clients_current_status == 'no'){
            header('Content-Type: text/xml');
            ?>
                <Response>
                    <Message>Your number is already been removed from out list, Thank you.</Message>
                </Response>
            <?php
            exit;
        }
        
        $client_confirmation_sql = "UPDATE $business_name SET confirmed = '$client_message' WHERE phone_number = '$client_number'";
        
        if(mysqli_query($db_connect, $client_confirmation_sql)){
            header('Content-Type: text/xml');
            ?>
                <Response>
                    <Message>Sorry for the inconveniece, You will now be removed from out list. Thank You.</Message>
                </Response>
            <?php
            exit;
        }else{
            header('Content-Type: text/xml');
            ?>
                <Response>
                    <Message>We seem to be having a problem. Please try again in a few minutes.</Message>
                </Response>
            <?php
            exit;
        }
    
        
    }elseif($finding_match == 0){
        
        $retrieve_all_MSiDs_sql = "SELECT message_service_id FROM twilio_service";
        $retrieve_all_MSiDs = mysqli_query($db_connect, $retrieve_all_MSiDs_sql);
        
        $all_MSiDs = [];
        
        while($MSiD = $retrive_all_MSiDs->fetch_assoc()){
            $all_MSiDs[] = $MSiD;
        }
        
        foreach($all_MSiDs as $message_id){
        
            $twilio = new Client($sid, $twilio_token);
        
            
            $MSiD_phoneNumbers = $twilio->messaging->v1->services("$message_id")->phoneNumbers->read();
            
            foreach($MSiD_phoneNumbers as $phoneNumber){
                
                if($business_number == $phoneNumber){
                    
                    //Get Owner of MSiD
                    $correct_business_sql = "SELECT * FROM twilio_service WHERE message_service_id = '$message_id'";
                    $correct_business_results = mysqli_query($db_connect, $correct_business_sql);
                    $correct_business_info = mysqli_fetch_array($correct_business_results, MYSQLI_ASSOC);
                    
                    $business_name = $correct_business_info['business_name'];
                    $business_name = str_replace(" ", "_", strtolower($business_name));
                    
                    $check_confirmation_status = "SELECT * FROM $business_name WHERE phone_number = '$client_number'";
                    $check_confirmation_results = mysqli_query($db_connect, $check_confirmation_status);
                    $clients_subscription_info = mysqli_fetch_array($check_confirmation_results, MYSQLI_ASSOC);
                    $clients_current_status = $clients_subscription_info['confirmed'];
        
    
                    if($clients_current_status == 'no'){
                        header('Content-Type: text/xml');
                        ?>
                            <Response>
                                <Message>Your number is already been removed from out list, Thank you.</Message>
                            </Response>
                        <?php
                        exit;
                    }
    
    
                    $client_confirmation_sql = "UPDATE $business_name SET confirmed = '$client_message' WHERE phone_number = '$client_number'";
                
                    if(mysqli_query($db_connect, $client_confirmation_sql)){
                        header('Content-Type: text/xml');
                        ?>
                        <Response>
                            <Message>Sorry for the inconveniece, You will now be removed from out list. Thank You.</Message>
                        </Response>
                        <?php    
                        exit;
                    }else{
                        header('Content-Type: text/xml');
                        ?>
                        <Response>
                            <Message>We seem to be having problems, please try again in a few minutes. </Message>
                        </Response> 
                        <?php    
                        exit;
                    }
                
                    exit;
                
                } 
            
            }
        
        }
        
    }
}

if($client_message != 'yes'){
    header('Content-Type: text/xml');
    ?>
    <Response>
        <Message>To confirm your number, please reply with 'yes'.</Message>
    </Response>
    <?php
    exit;
}

$finding_business_sql = "SELECT * FROM twilio_service WHERE initial_phone_number = '$business_number'";
$finding_results = mysqli_query($db_connect, $finding_business_sql);
$finding_match = mysqli_num_rows($finding_results);

if($finding_match == 1){
    
    $business_twilio = mysqli_fetch_array($finding_results, MYSQLI_ASSOC);
    $business_name = $business_twilio['business_name'];
    $business_name = str_replace(" ", "_", strtolower($business_name));
    
    $check_confirmation_status = "SELECT * FROM $business_name WHERE phone_number = '$client_number'";
    $check_confirmation_results = mysqli_query($db_connect, $check_confirmation_status);
    $clients_subscription_info = mysqli_fetch_array($check_confirmation_results, MYSQLI_ASSOC);
    $clients_current_status = $clients_subscription_info['confirmed'];
    

    if($clients_current_status == 'yes'){
        header('Content-Type: text/xml');
        ?>
        <Response>
            <Message>Your number is already confirmed, Thank you.</Message>
        </Response>
        <?php
        exit;
    }
    
    
    $client_confirmation_sql = "UPDATE $business_name SET confirmed = '$client_message' WHERE phone_number = '$client_number'";
    
    if(mysqli_query($db_connect, $client_confirmation_sql)){
         header('Content-Type: text/xml');
    ?>
        <Response>
            <Message>Thank you for confirming your number. Your subscription is complete.</Message>
        </Response>
    <?php    
        exit;
    }else{
         header('Content-Type: text/xml');
    ?>
        <Response>
            <Message>We seem to be having problems, please try replying with 'yes' in a few minutes. </Message>
        </Response> 
    <?php    
        exit;
    }
    
    
}elseif($finding_match == 0){
    
    $retrieve_all_MSiDs_sql = "SELECT message_service_id FROM twilio_service";
    $retrieve_all_MSiDs = mysqli_query($db_connect, $retrieve_all_MSiDs_sql);
    
    $all_MSiDs = [];
    
    while($MSiD = $retrive_all_MSiDs->fetch_assoc()){
        $all_MSiDs[] = $MSiD;
    }
    
    foreach($all_MSiDs as $message_id){
        
        $twilio = new Client($sid, $twilio_token);
        
        $MSiD_phoneNumbers = $twilio->messaging->v1->services("$message_id")->phoneNumbers->read();
        
        foreach($MSiD_phoneNumbers as $phoneNumber){
            
            if($business_number == $phoneNumber){
                
                //Get Owner of MSiD
                $correct_business_sql = "SELECT * FROM twilio_service WHERE message_service_id = '$message_id'";
                $correct_business_results = mysqli_query($db_connect, $correct_business_sql);
                $correct_business_info = mysqli_fetch_array($correct_business_results, MYSQLI_ASSOC);
                
                $business_name = $correct_business_info['business_name'];
                $business_name = str_replace(" ", "_", strtolower($business_name));
                
                $check_confirmation_status = "SELECT * FROM $business_name WHERE phone_number = '$client_number'";
                $check_confirmation_results = mysqli_query($db_connect, $check_confirmation_status);
                $clients_subscription_info = mysqli_fetch_array($check_confirmation_results, MYSQLI_ASSOC);
                $clients_current_status = $clients_subscription_info['confirmed'];
    

                if($clients_current_status == 'yes'){
                    header('Content-Type: text/xml');
                    ?>
                        <Response>
                            <Message>Your number is already confirmed, Thank you.</Message>
                        </Response>
                    <?php
                    exit;
                }
    
    
                $client_confirmation_sql = "UPDATE $business_name SET confirmed = '$client_message' WHERE phone_number = '$client_number'";
                
                if(mysqli_query($db_connect, $client_confirmation_sql)){
                     header('Content-Type: text/xml');
                    ?>
                        <Response>
                            <Message>Thank you for confirming your number. Your subscription is complete.</Message>
                        </Response>
                    <?php    
                    exit;
                }else{
                    header('Content-Type: text/xml');
                    ?>
                        <Response>
                            <Message>We seem to be having problems, please try replying with 'yes' in a few minutes. </Message>
                        </Response> 
                    <?php    
                    exit;
                }
                
                exit;
                
            } 
            
        }
        
    }
    
    
    
    
    
}

?>
