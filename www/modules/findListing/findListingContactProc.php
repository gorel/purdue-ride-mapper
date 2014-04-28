<?php
echo json_encode(array('success' => "SUCCESS", 'rcpt' => "$rcpt", 'from' => "$from")); 
return;
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
$success = "";
$rcpt = "";
$from = "";

$con=mysqli_connect("collegecarpool.us", "root", "collegecarpool", "purdue_test");

// Check connection
if (mysqli_connect_errno())
{
	$success = "FALSE";
}
else
{				
	$success = "TRUE";
	$sql="SELECT user_id FROM listings WHERE listings_id = $listingID";
	$result = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($result);
	$rcpt_id = $row['user_id'];

	$sql="SELECT first_name,email FROM users WHERE user_id = $rcpt_id";
	$result = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($result);
	$rcpt = $row['email'];
        $rcpt_name = $row['first_name'];
	
	$sql="SELECT first_name,email FROM users WHERE user_id = $from_uid";
	$result = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($result);
	$from = $row['email'];	

	// Send sender's info to receipient

	$sender_info = "";

	$sql="SELECT first_name,alt_email,phone FROM users where user_id=$from_uid";
	$result = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($result);
	$alt_email = $row['alt_email'];
	$phone = $row ['phone'];
	$sender_name = $row['first_name'];

	if ($alt_email != "")
	{
	  $sql="SELECT send_alt_email from user_settings where user_id=$from_uid";
	  $result = mysqli_query($con, $sql);
	  $row = mysqli_fetch_array($result);
	  $send_alt_email = $row['send_alt_email'];
	  
	  if ($send_alt_email)
		$sender_info = $sender_info . $alt_email . "<br>";

	}

	if ($phone != "")
	{
	  $sql="SELECT send_phone from user_settings where user_id=$from_uid";
	  $result = mysqli_query($con, $sql);
	  $row = mysqli_fetch_array($result);
	  $send_phone = $row['send_phone'];
	  
	  if ($send_phone)
		$sender_info = $sender_info . $phone . "<br>";

	}
	mysqli_close($con);
	
	sendUserMail($rcpt, $rcpt_name, $message, $from, $sender_name, $sender_info);
}			

echo json_encode(array('success' => "$success", 'rcpt' => "$rcpt", 'from' => "$from")); 
?>
