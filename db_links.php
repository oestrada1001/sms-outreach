<?php

//define('DB_HOST','localhost');
//define('DB_USER','root');
//define('DB_PASS','root');
//define('DB_DATA','dbregistration');

define('DB_HOST','localhost');
define('DB_USER','bluewgsx');
define('DB_PASS','41xl0yo4fdm');
define('DB_DATA','bluewgsx_dbregistration');

date_default_timezone_set('America/Los_Angeles');


$db_connect = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_DATA);

?> 