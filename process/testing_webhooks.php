<?php
require_once('../stripe4-x/stripe-php-4.13.0/init.php');
require_once('../db_links.php');

\Stripe\Stripe::setApiKey("sk_test_uYkMFP68T1v4WkducM4LWDO0");

// Retrieve the request's body and parse it as JSON
$input = @file_get_contents("php://input");
$event_json = json_decode($input);


/* Live Mode
//Event ID
$id = \Stripe\Event::retrieve($event->id);
//Plan ID
$plan = \Stripe\Plan::retrieve($event->id);
*/

//$event = \Stripe\Event::retrieve($event_json->id);
$event_id = $event_json->data->object->lines->data[0]->plan->id;

var_dump($event_id);

?>