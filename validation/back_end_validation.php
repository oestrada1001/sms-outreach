<?php

$remove = ['(', ')',' ','-','+', '.', 'x'];

$valid_name = "/^[A-Za-z ]{3,20}$/";

$valid_business_name = "/^[A-Za-z0-9 ]{3,30}$/";

$valid_email = "/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i";

$valid_phone_number = "/^[2-9]\d{2}-?\d{3}-?\d{4}$/";

$valid_username = "/^[A-Za-z0-9_]{3,20}$/";

$valid_short_string = "/^[ A-Za-z0-9_]{3,20}$/";

$valid_password = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{6,})/";

$valid_sms_message = "/^[ A-Za-z0-9!@#'%\$\-\.,:_\?\/]{5,160}$/";

// -- MM/DD/YYYY or MM-DD-YYYY
$valid_date = "/^\d{2}(\/|-)\d{2}(\/|-)\d{4}$/";

// --YYYY/MM/DD or YYYY-MM-DD
$valid_sms_date = "/^\d{4}(\/|-)\d{2}(\/|-)\d{2}$/";

$valid_number = "/^[0-9]{1,}$/";

$valid_token = "/^([0-9a-zA-Z]){100}$/";


?>
