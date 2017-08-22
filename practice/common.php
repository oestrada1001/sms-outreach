<?php

session_start();

//init_set('display_startup_errors', 1);
//init_set('display_errors', 1);
//error_reporting(E_ALL);

//Stripe API Keys
$st_test_secret_key = 'sk_test_VqYuuA1Oql4Q3rfQGaqgiox3';
$st_test_public_key = 'pk_test_iuksXJ77wGCAnFxJVWVTTa5S';

$path_to_stripe_lib = dirname(__FILE__) . 'stripe4-x/stripe-php4.13.0/';

include_once($path_to_stripe_lib . 'init.php');

?>