<?php

require '../../lib/hash.php';
require '../../lib/email.php';

//TODO Replace jscript alerts with friendly div elements

//TODO CHANGE BACK
$conn = new mysqli("collegecarpool.us", "root", "collegecarpool", "purdue_test");

if ($conn->connect_errno)
{
        echo json_encode(array('retval' => 'ERR'));
        die();
}

// Hash the email to produce the link to change the password

$email = $_POST['email'];
$query = "SELECT link from password_reset where email like ?";

$stmt = $conn->stmt_init();
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $email);
$stmt->bind_result($hash);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) 
{
  sendPwResetMail($email, $hash);		
  echo json_encode(array('retval' => 'OK'));
  return;
}

$hash = saltedHash($email);

$query = "INSERT into password_reset (email, link) VALUES (?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param('ss', $email, $hash);
$stmt->execute();
sendPwResetMail($email, $hash);
echo json_encode(array('retval' => 'OK'));

?>

