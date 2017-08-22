var valid_name = /^[A-Za-z ]{3,20}$/;

var valid_business_name = /^[A-Za-z0-9 ]{3,30}$/;

var valid_email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;

var valid_phone_number = /^[2-9]\d{2}-?\d{3}-?\d{4}$/;

var valid_username = /^[A-Za-z0-9_]{3,20}$/;

var valid_password = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{6,})/;

var valid_sms_message = /^[ A-Za-z0-9!@#'%\$\-\.,:_\?\/]{5,160}$/;

// -- MM/DD/YYYY or MM-DD-YYYY
var valid_date = /^\d{2}(\/|-)\d{2}(\/|-)\d{4}$/;

// --YYYY/MM/DD or YYYY-MM-DD
var valid_sms_date = /^\d{4}(\/|-)\d{2}(\/|-)\d{2}$/;

var valid_number = /^[0-9]{1,}$/;