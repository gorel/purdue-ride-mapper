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


$con=mysqli_connect("localhost", "collegecarpool", "collegecarpool", "purdue_test");

// Check connection
if (mysqli_connect_errno())
{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
else
{				
	$sql="SELECT user_id FROM listings WHERE listings_id = $listingID";
	$result = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($result);
	$rcpt_id = $row['user_id'];
	
	$sql="SELECT email FROM users WHERE user_id = $rcpt_id";
	$result = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($result);
	$rcpt = $row['email']
	
	$sql="SELECT email FROM users WHERE user_id = $from_uid";
	$result = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($result);
	$from = $row['email']
	
	
	
	mysqli_close($con);
	
	sendUserMail($rcpt, $message, $from)
}			

echo json_encode(array('status' => "$found", 'test' => "$row")); 
?>
