<?php

require "../../lib/hash.php";

$pass = $_POST['password'];
$email = $_POST['email'];

//TODO CHANGE BACK
$conn = new mysqli("collegecarpool.us", "root", "collegecarpool", "purdue_test");

if ($conn->connect_errno)
{
        echo json_encode(array('retval' => 'ERR'));
        die();
}


$stmt = $conn->stmt_init();
$pass = saltedHash($_POST["password"]);

$query = "delete from password_reset where email like '$email'";
$stmt = $conn->prepare($query);
$ret = $stmt->execute();

if (! $ret) 
{
	echo json_encode(array('retval' => 'ERR'));
	die();
}

$query = "UPDATE users set password='$pass' where email like '$email'";
$stmt = $conn->prepare($query);
$ret = $stmt->execute();

if (! $ret) 
{
	echo json_encode(array('retval' => 'ERR'));
	die();
}

echo json_encode(array('retval' => 'OK'));

?>
