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

$email = trim($_POST['email']);

$stmt = $conn->stmt_init();

// First check if user exists

$query = "SELECT user_id from users where email like ?";

$stmt = $conn->prepare($query);
$stmt->bind_param('s', $email);
$stmt->bind_result($uid);
$stmt->execute();
$stmt->store_result();
$stmt->fetch();

if ($stmt->num_rows == 0)
{
  echo json_encode(array('retval' => 'FORGOTPW_NO_USER'));
  die;
}

// Hash email+time to produce the link to change the password

date_default_timezone_set('EST');

$hash = saltedHash($email . date("H:i:s"));

$query = "SELECT link from password_reset where email like ?";

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

mysqli_stmt_reset($stmt);
$query = "INSERT into password_reset (user_id, email, link) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param('dss', $uid, $email, $hash);
$stmt->execute();
sendPwResetMail($email, $hash);

echo json_encode(array('retval' => 'OK', "uid" => $uid));

?>

