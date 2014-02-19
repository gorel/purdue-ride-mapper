<?php

require '../../../dbcredentials.php';
require 'hash.php';

/**
 *
 * Verify user id and registration token to activate account
 *
 * @author	Timothy Thong <tthong@purdue.edu>
 * @version	1.0
 *
 */

// do sainty checks

$token    = $_GET["token"];
$uid      = $_GET["id"];

if (preg_match("/[^[a-z0-9]+/", $token))
{
        die;
}

if (preg_match("/[^0-9]+/", $uid))
{
        die;
}

$dbName   = 'purdue_test';

// connect to local db

$conn =  new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_errno) 
{
    echo  $conn->connect_errno . " " . $conn->connect_error;
    die;
}

$stmt = $conn->stmt_init();

$query = "SELECT token FROM unverified_user_tokens WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('d', $uid);
$stmt->execute();
$stmt->store_result();

if (!$stmt->num_rows ) 
{
        echo "Activation link does not exist!";
}

// compare token from URL and database

$stmt->bind_result($db_token);
$stmt->fetch();
$stmt->close();

if ($db_token == $token) 
{
        $update = "UPDATE users set enabled=1, verified=1 WHERE user_id = ?";
        $stmt = $conn->prepare($update);
        $stmt->bind_param('d', $uid);
        $stmt->execute();
        $stmt->close();

        $delete = "DELETE FROM unverified_user_tokens WHERE user_id = ?";
        $stmt = $conn->prepare($delete);
        $stmt->bind_param('d', $uid);
        $stmt->execute();
        $stmt->close();
}

?>
