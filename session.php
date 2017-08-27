<?php
require_once "db_links.php";
session_start();

date_default_timezone_set('America/Los_Angeles');

$user_check = $_SESSION['email'];
$user_table = $_SESSION['count'];
//This is their account info but should be their profile :/
$sql = "SELECT * FROM $user_table WHERE email = '$user_check'";
$ses_sql = mysqli_query($db_connect, $sql);
$row = mysqli_fetch_array($ses_sql, MYSQLI_ASSOC);
$login_session = $row['email'];

?>