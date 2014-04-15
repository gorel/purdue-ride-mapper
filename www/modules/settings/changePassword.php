<?php

require '../../lib/hash.php';

$currPw = saltedHash($_POST['currPw']);
$newPw = saltedHash($_POST['newPw']);
$uid = $_POST['uid'];

$conn = new mysqli("collegecarpool.us", "root", "collegecarpool", "purdue_test");

if ($conn->connect_errno)
{
  die("Failed to connect to MYSQL: " . mysqli_connect_error());
}

$query = "SELECT password from users where user_id=?";
$stmt = $conn->stmt_init();
$stmt = $conn->prepare($query);
$stmt->bind_param('d', $uid);
$stmt->bind_result($currHash);
$stmt->execute();
$stmt->fetch();

if ($currPw != $currHash) 
{
  echo json_encode(array('status' => 'AUTH_FAILED', 'password' => "$currPw")); 
  die();
}

$query = "UPDATE users set password=? where user_id =?";
$stmt->reset();
$stmt = $conn->prepare($query);
$stmt->bind_param('sd', $newPw, $uid);
$stmt->execute();

echo json_encode(array('status' => 'PW_SUCCESS', 'password' => "$currPw")); 


?>
