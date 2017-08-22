<?php

include_once 'common.php';

header('Content-Type: application/json');

\Stripe\Stripe::setApiKey($st_test_secret_key);

$return_data['success'] = false;
    \Stripe\Plan::create(array(
        "id" => "pro_1",
        "amount" => 1999,
        "interval" => "month",
        "name" => "Pro",
        "statement_descriptor" => "Acme Pro Plan",
        "currency" => "usd"
    )
);

$return_data['success'] = true;
echo json_encode($return_data);
?>