<?php
session_start();

if(isset($_SESSION['email'])){
    $_SESSION['email'] = $_SESSION['email'];
    $_SESSION['count'] = 'clients';
}


?>