<?php

/**
*
* Warn a user about their behavior (modifed from findListingContactProc.php)
*
*
* @author	Logan Gore <gorel@purdue.edu>
* @version	1.0

*/
require '../../lib/email.php';

$from_uid   = $_POST["from_uid"];
$to_uid = $_POST['to_uid'];
$message   = $_POST["message"];
$success = "";
$rcpt = "";
$from = "";

$con=mysqli_connect("localhost", "collegecarpool", "collegecarpool", "purdue_test");

// Check connection
if (mysqli_connect_errno())
{
	$success = "FALSE";
}
else
{				
	$success = "TRUE";
	
	$sql="SELECT email FROM users WHERE user_id = $to_uid";
	$result = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($result);
	$rcpt = $row['email'];
	
	$sql="SELECT email FROM users WHERE user_id = $from_uid";
	$result = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($result);
	$from = $row['email'];	
	
	mysqli_close($con);
	
	sendUserMail($rcpt, $message, $from);
}			

echo json_encode(array('success' => "$success", 'rcpt' => "$rcpt", 'from' => "$from", 'to' => "$to")); 
?>
