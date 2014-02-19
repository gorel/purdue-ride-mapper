<?php

/**
 * 
 * Register a new user
 *
 * Add the user to the database who will be disabled
 * by default according to the database default values
 * An email is sent for account activation
 *
 * @author	Timothy Thong <tthong@purdue.edu>
 * @version	1.0
 *
 */ 

// include login info

require '../../../dbcredentials.php';
require '../../lib/hash.php';
require '../../lib/email.php';

// assumes valid input

$email    = strtolower($_POST["email"]);
$hashpw   = saltedHash($_POST["pass"]);
$fname    = $_POST["fname"];
$lname    = $_POST["lname"];

$dbName   = 'purdue_test';

// connect to local db

$conn =  new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_errno) 
{
    echo  $conn->connect_errno . " " . $conn->connect_error;
    die;
}
$stmt = $conn->stmt_init();

// check if user exists

$query = "SELECT user_id FROM users WHERE lower(email) like ?";
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

// add user to 'users' table

$insert = "INSERT INTO users (email, first_name, last_name, password) "
        . "VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($insert);
$stmt->bind_param('ssss', $email, $fname, $lname, $hashpw);
$stmt->execute();
$stmt->close();

// add a token entry for email verification

$uid = $conn->insert_id;
$token   = saltedHash($email . $hashpw . $fname . $lname);
$insert = "INSERT INTO unverified_user_tokens (user_id, token) VALUES (?, ?)";
$stmt = $conn->prepare($insert);
$stmt->bind_param('ds', $uid, $token);
$stmt->execute();
$stmt->close();

sendRegMail($email, $fname, $uid, $token);

?>
