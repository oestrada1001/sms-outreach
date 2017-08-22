<?php

$login_session = $row['email'];
//This is their stripe informations.
$stripe_client_sql = "SELECT * FROM stripe_clients WHERE email = '$login_session'";
$client_results = mysqli_query($db_connect, $stripe_client_sql);
$stripe = mysqli_fetch_array($client_results, MYSQLI_ASSOC);


?>