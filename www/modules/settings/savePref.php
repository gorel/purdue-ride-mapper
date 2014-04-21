<?php

$send_phone = $_POST['send_phone'];
$list_phone = $_POST['list_phone'];
$send_alt_email = $_POST['send_alt_email'];
$list_alt_email = $_POST['list_alt_email'];
$list_reg_email = $_POST['list_reg_email'];
$uid = $_POST['uid'];

$conn = new mysqli("collegecarpool.us", "root", "collegecarpool", "purdue_test");

if ($conn->connect_errno)
{
  echo json_encode(array("retval" => "DB_ERR"));
  die;
}

$stmt = $conn->stmt_init();
$query = "UPDATE user_settings " .
         "SET send_phone=?, list_phone=?, send_alt_email=?, list_alt_email=?, list_reg_email=? " .
         "WHERE user_id=?";

$stmt = $conn->prepare($query);
$stmt->bind_param('dddddd', $send_phone, $list_phone, $send_alt_email, $list_alt_email, $list_reg_email, $uid);
$stmt->execute();

echo json_encode(array("retval" => "OK"));


?>
