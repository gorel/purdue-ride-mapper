<?php

/**
*
* Get ride contact info (host)
*
*
* @author	Timothy Thong <tthong@purdue.edu>
* @version	1.0

*/
require '../../lib/email.php';

$listingID   = $_POST["listingID"];

$con= new mysqli("collegecarpool.us", "root", "collegecarpool", "purdue_test");

// Check connection
if ($con->connect_errno)
{
	$success = "FALSE";
}
$stmt = $con->stmt_init();

$query = "SELECT user_id from listings where listings_id=$listingID";
$stmt = $con->prepare($query);
$stmt->bind_result($uid);
$stmt->execute();
$stmt->fetch();
$stmt->close();

$query = "SELECT first_name, email, alt_email, phone FROM users where user_id = $uid";
$stmt = $con->prepare($query);
$stmt->bind_result($first_name, $email, $alt_email, $phone);
$stmt->execute();
$stmt->fetch();
$stmt->close();


$query = "SELECT list_phone, list_reg_email, list_alt_email FROM user_settings where user_id=$uid";
$stmt = $con->prepare($query);
$stmt->bind_result($list_phone, $list_reg_email, $list_alt_email);
$stmt->execute();
$stmt->fetch();
$stmt->close();

$info ="";

if ($list_reg_email)
  $info = $info . $email . "<br>";

if ($list_alt_email)
  $info = $info . $alt_email . "<br>";

if ($list_phone)
  $info = $info. $phone . "<br>";


echo json_encode(array('contact_header' => "Contact $first_name at:", 'contact_info' => $info, 'first_name' => $first_name)); 
?>
