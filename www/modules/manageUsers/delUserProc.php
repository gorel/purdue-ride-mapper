<?php

if ($_SERVER['REQUEST_METHOD'] != "POST")
{
        header("Location: /index.php");
	die();
}

//TODO CHANGE BACK
$conn = new mysqli("collegecarpool.us", "root", "collegecarpool", "purdue_test");

if ($conn->connect_errno)
{
	echo json_encode(array('retval' => 'ERR'));
	die();
}

$uid = $_POST['uid'];

$query = "DELETE FROM users where user_id=?";
$stmt = $conn->stmt_init();
$stmt = $conn->prepare($query);
$stmt->bind_param('d', $uid);
$stmt->execute();
$query = "DELETE FROM listings where user_id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param('d', $uid);
$stmt->execute();
echo json_encode(array('retval' => 'OK'));


?>
