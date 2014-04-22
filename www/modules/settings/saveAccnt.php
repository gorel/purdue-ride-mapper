<?php

$alt_email = $_POST['alt_email'];
$phone = $_POST['phone'];
$uid = $_POST['uid'];

$conn = new mysqli("collegecarpool.us", "root", "collegecarpool", "purdue_test");

if ($conn->connect_errno)
{
  echo json_encode(array("retval" => "DB_ERR"));
  die;
}

$stmt = $conn->stmt_init();

$query = "UPDATE users SET alt_email=?,phone=? WHERE user_id =?";

$stmt = $conn->prepare($query);
$stmt->bind_param('ssd', $alt_email, $phone, $uid);
$stmt->execute();
$stmt->close();

// Update preferences

if ($alt_email == "" && $phone != "")
{
  $query = "UPDATE user_settings SET send_alt_email=0,list_alt_email=0 WHERE user_id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param('d', $uid);
  $stmt->execute();
  $stmt->close();
}
else if ($alt_email != "" && $phone == "")
{
  $query = "UPDATE user_settings SET send_phone=0,list_phone=0 WHERE user_id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param('d', $uid);
  $stmt->execute();
  $stmt->close();
}
else if ($alt_email == "" && $phone =="")
{
  $query = "UPDATE user_settings SET send_alt_email=0,list_alt_email=0,send_phone=0,list_phone=0 WHERE user_id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param('d', $uid);
  $stmt->execute();
  $stmt->close();

}

echo json_encode(array("retval" => "OK"));


?>
