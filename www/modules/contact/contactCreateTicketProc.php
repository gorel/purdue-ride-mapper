<?php

	/**
	*
	* Send a message to admin
	*
	*
	* @author	Timothy Thong <tthong@purdue.edu>
	* @version	1.0
	 */

	session_start();

	require '../../lib/email.php';

	$cat   = $_POST["category"];
	$msg   = $_POST["text"];
	$user_id = $_SESSION['user'];


	// connect to local db

	$conn =  new mysqli("localhost", "collegecarpool", "collegecarpool", "purdue_test");

	if ($conn->connect_errn)
	{
	    echo  $conn->connect_errno . " " . $conn->connect_error;
	    die;
	}
	$stmt = $conn->stmt_init();

	// check if user exists
	/*
	$query = "SELECT ticket_message FROM tickets WHERE ticket_message like msg";
	$stmt = $conn->prepare($query);
	$stmt->bind_param('s', $email);
	$stmt->execute();
	$stmt->store_result();

	if ($stmt->num_rows > 0)
	{
	    echo  "User already exists";
	    die;
	}
	$stmt->close();
	 */


	// add user to 'users' table
	date_default_timezone_set('EST');
	$today = date("Y-m-d H:i:s");
	$insert = "INSERT INTO tickets (category, user_id, ticket_date, ticket_message) "
	        . "VALUES (?, ?, ?, ?)";
	$stmt = $conn->prepare($insert);
	$stmt->bind_param('dsss', $cat, $user_id, $today, $msg);
	$stmt->execute();
	$stmt->close();

	header("Location:../../index.php");

?>
