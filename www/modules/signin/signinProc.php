<?php

/**
 *
 * Sign in a user
 *
 */

// include login info

require '/var/dbcredentials.php';
require '../../lib/hash.php';
require '../../lib/email.php';

// assumes valid input

$email = strtolower($_POST["email"]);
$hashpw = saltedHash($_POST["pass"]);

$dbName = 'purdue_test';

// connect to local db

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if($conn->connect_errno)
{
	echo $conn->connect_errno . " " . $connect_error;
	die;
}
$stmt = $conn->stmt_init();

// check if user exist

$query = "SELECT user_id, password  FROM users WHERE lower(email) like ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows < 1)
{
	echo "E-Mail is incorrect or it doesn't exist";
	die;
}
else
{
	echo "binding result";
	$stmt->bind_result($user_id, $password);
	if (strcmp($password, hashpw))
	{
		session_start();
		$_SESSION['user']=user_id;

		echo "log in success";
	}
	else
	{
		echo "Password is not correct for E-Mail address";
		die;
	}
}



$stmt->close();

header("Location:../../index.php");

?>
