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

$fname =     $_POST['fname'];
$lname =     $_POST['lname'];
$email =     $_POST['email'];
$alt_email = $_POST['alt_email'];
$phone =     $_POST['phone'];
$enabled =   $_POST['enabled'];
$admin =     $_POST['admin'];
$warnings =  $_POST['warnings'];
$uid =       $_POST['uid'];

$query = "UPDATE users set first_name=?, last_name=?, email=?      , " .
                           "alt_email=?, phone=?    , enabled=?    , " .
                           "is_admin=? , warned=? where user_id=?";
$stmt = $conn->stmt_init();
$stmt = $conn->prepare($query);
$stmt->bind_param('sssssdddd', $fname, $lname, $email, $alt_email, $phone, $enabled, $admin, $warnings, $uid);
$stmt->execute();
echo json_encode(array('retval' => 'OK'));


?>
