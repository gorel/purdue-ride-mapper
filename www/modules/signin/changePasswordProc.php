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
$query = "UPDATE users set password=\"$pass\" where email like \"$email\"";
$stmt = $conn->prepare($query);
$stmt->execute();
echo json_encode(array('retval' => 'OK'));


?>
