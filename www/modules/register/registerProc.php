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

require '/var/dbcredentials.php';
require '../../lib/hash.php';
require '../../lib/email.php';

// assumes valid input

$email    = strtolower($_POST["email"]);
$hashpw   = saltedHash($_POST["pw"]);
$fname    = $_POST["fname"];
$lname    = $_POST["lname"];

$dbName   = 'purdue_test';

// connect to local db

$conn =  new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_errno)
{
    echo json_encode(array("retval" => "ERR"));
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
    echo json_encode(array("retval" => "REG_USER_EXIST"));
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


$uid = $conn->insert_id;

// add user settings to user_settings table

$insert = "INSERT INTO user_settings (user_id, list_reg_email) values ($uid, 1)";
$stmt = $conn->prepare($insert);
$stmt->execute();
$stmt->close();

// add a token entry for email verification

$token   = saltedHash($email . $hashpw . $fname . $lname);
$insert = "INSERT INTO unverified_user_tokens (user_id, token) VALUES (?, ?)";
$stmt = $conn->prepare($insert);
$stmt->bind_param('ds', $uid, $token);
$stmt->execute();
$stmt->close();

sendRegMail($email, $fname, $uid, $token);

echo json_encode(array("retval" => "REG_OK"));

?>
