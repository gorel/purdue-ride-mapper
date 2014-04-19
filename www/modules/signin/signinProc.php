<?php
session_start();

/**
 *
 * Sign in a user
 *
 */

// include login info
ob_start();

require '/var/dbcredentials.php';
require '../../lib/hash.php';
require '../../lib/email.php';

// assumes valid input

$email = strtolower($_POST["email"]);
$hashpw = saltedHash($_POST["password"]);

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

$query = "SELECT user_id, password, verified , is_admin, enabled FROM users WHERE lower(email) like ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows < 1)
{
        echo json_encode(array("retval" => "AUTH_NO_USER"));
	die;
}
else
{
	$stmt->bind_result($user_id, $password, $verified, $isAdmin, $enabled);
	$stmt->fetch();


        
	if (!strcmp($password, $hashpw) && $verified==1 && $enabled==1)
	{
                if ($isAdmin)
                {
		        $_SESSION['isAdmin']=$isAdmin;
                }
		$_SESSION['user']=$user_id;
                echo json_encode(array("retval" => "AUTH_OK"));
		
	}
	else if (strcmp($password, $hashpw))
	{
                echo json_encode(array("retval" => "AUTH_FAILED_PW"));
	}
	else if(!strcmp($password, $hashpw) && $verified==0)
	{
                echo json_encode(array("retval" => "AUTH_UNVERIFIED"));
	}
        else if(!strcmp($password, $hashpw) && $enabled==0)
        {
                echo json_encode(array("retval" => "AUTH_BANNED"));
        }
}



$stmt->close();


?>
