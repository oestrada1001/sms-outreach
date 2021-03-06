<?php

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

date_default_timezone_set('America/Los_Angeles');

$db_connect = mysqli_connect(
    $_ENV['DB_HOST'], 
    $_ENV['DB_USERNAME'], 
    $_ENV['DB_PASSWORD'], 
    $_ENV['DB_DATABASE']
);

?> 