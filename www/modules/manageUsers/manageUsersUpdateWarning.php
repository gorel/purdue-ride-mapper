<?php 

session_start();

if (!isset($_SESSION['user']) || !$_SESSION['isAdmin'])
{
  die();
}


$uid   = $_POST['uid'];

$conn = new mysqli("collegecarpool.us","root","collegecarpool","purdue_test");
  
  
$query = "SELECT warned " .
         "FROM users " .
         "WHERE user_id=?";

$stmt = $conn->stmt_init();
$stmt = $conn->prepare($query);
$stmt->bind_param('d', $uid);
$stmt->bind_result($warned);
$stmt->execute();
$stmt->store_result();

$stmt->fetch();

$query = "UPDATE users " .
         "SET warned=? ".
         "WHERE user_id=?";

$stmt = $conn->prepare($query);
$stmt->bind_param('dd', ++$warned, $uid);
$stmt->execute();



?>
