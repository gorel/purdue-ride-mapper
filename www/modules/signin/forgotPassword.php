<?php

require '../../lib/hash.php';
require '../../lib/email.php';


//TODO CHANGE BACK
$conn = new mysqli("collegecarpool.us", "root", "collegecarpool", "purdue_test");

if ($conn->connect_errno)
{
        echo json_encode(array('retval' => 'ERR'));
        die();
}

// Hash email+time to produce the link to change the password

date_default_timezone_set('EST');

$email = $_POST['email'];
$hash = saltedHash($email . date("H:i:s"));

$query = "SELECT link from password_reset where email like ?";

$stmt = $conn->stmt_init();
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $email);
$stmt->bind_result($hash);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) 
{
  
  $query = "UPDATE password_reset set link=? where email =?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param('ss', $hash, $email);
  $stmt->execute();

  sendPwResetMail($email, $hash);		

  echo json_encode(array('retval' => 'OK'));
  return;
}

$query = "INSERT into password_reset (email, link) VALUES (?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param('ss', $email, $hash);
$stmt->execute();
sendPwResetMail($email, $hash);

echo json_encode(array('retval' => 'OK'));

?>

