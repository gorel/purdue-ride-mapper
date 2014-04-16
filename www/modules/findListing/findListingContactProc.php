<?php

/**
*
* Send a message to admin
*
*
* @author	Timothy Thong <tthong@purdue.edu>
* @version	1.0

*/
require '../../lib/email.php';

$from_uid   = $_POST["from_uid"];
$message   = $_POST["message"];
$listingID   = $_POST["listingID"];

echo json_encode(array('status' => "$found", 'referralLink' => "$referralLink", 'length' => "$length",
							'completed' => "$completed", 'queue' => "$queue", 'percentCompleted' => "$percentCompleted",
							'estimate' => "$estimate")); 
?>
