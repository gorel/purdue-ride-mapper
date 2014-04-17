<?php

$have_alt_email = $_POST['have_alt_email'];
$alt_email = $_POST['alt_email'];
$uid = $_POST['uid'];

$conn = new mysqli("collegecarpool.us", "root", "collegecarpool", "purdue_test");

if ($conn->connect_errno)
{
  echo json_encode(array("retval" => "DB_ERR"));
  die;
}

$stmt = $conn->stmt_init();

if ($have_alt_email) 
{
  $query = "UPDATE users SET have_alt_email=?,alt_email=? " .
           "WHERE user_id =?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param('dsd', $have_alt_email, $alt_email, $uid);
  $stmt->execute();
} 
else
{
  $query = "UPDATE users SET have_alt_email=?,alt_email=\"\" " .
           "WHERE user_id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param('dd', $have_alt_email,  $uid);
  $stmt->execute();
}

echo json_encode(array("retval" => "OK"));


?>
