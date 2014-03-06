<?php

/**
*
* Send a message to admin
*
*
* @author	Timothy Thong <tthong@purdue.edu>
* @version	1.0

*/
require '../../lib/email.php'

$cat   = $_POST["category"]);
$email = strtolower($_POST["email"]);
$msg   = $_POST["text"];

sendContactMail($email, $cat, $msg);

?>
