<?php//Working Version
require_once('../db_links.php');
require_once('../twilio-php-master/Twilio/autoload.php');

use Twilio\Rest\Client;

$businessName = "testing stripe";
$email = "testing@test.com";

try{
        
    $sid = 'AC9aa8585446af8cafc529ba2f3bff7511';
    $twilio_token = 'c7bc34c5cc067cae53726cfc22419cdf';
    $twilio = new Client($sid, $twilio_token);
    
    
    //Creating the client a Messaging Service
    $service = $twilio->messaging->v1->services->create($businessName, array('inboundRequestUrl' => "https://www.blueskylinemarketing.com/process/twilio_confirmation.php", 'fallbackUrl' => "https://www.blueskylinemarketing.com/process/twilio_confirmation.php"));
        
//    Their Messaging Service Id
    $MSiD = $service->sid;
    
//    //Getting their area code.
//    $phonenumber_array = str_split($phone_number, 3);
//    $local_area = $phonenumber_array[0];
//    $areacode = $twilio->availablePhoneNumbers('US')->local->read(
//        array("areaCode" => "$local_area")
//    );
//    
//    //Buying a local phone number
//    $purchase_number = $twilio->incomingPhoneNumbers->create(
//        array(
//            "phoneNumber" => $areacode[0]->phoneNumber
//        )
//    );
//    
//    //Actual Phone Number
//    $actual_number = $purchase_number->phoneNumber;
    
    $actual_number = '+16617784545';
        
    $twilio_sql = "INSERT INTO twilio_service (id, business_name, email, message_service_id, initial_phone_number) ";
    $twilio_sql.= "VALUES (DEFAULT, '$businessName', '$email', '$MSiD', '$actual_number')";
    
    if(mysqli_query($db_connect, $twilio_sql)){
        echo 'success';
    }
        
}catch(Exception $c){
    var_dump($c);
    exit;
}finally{
    exit;
}
?>