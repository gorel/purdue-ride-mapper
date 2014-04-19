<?php 

session_start();

$uid = $_SESSION['user'];

if (!isset($uid))
  die;

$by   = $_POST['by'];
$term = $_POST['term'];
$page = $_POST['page'];

$conn = new mysqli("collegecarpool.us","root","collegecarpool","purdue_test");
  
  
$query = "SELECT user_id, email, first_name, last_name, verified, enabled, is_admin " .
         "FROM users " .
         "WHERE LOWER($by) LIKE '%$term%'";

$stmt = $conn->stmt_init();
$stmt = $conn->prepare($query);
$stmt->bind_result( $user_id, $email, $fname, $lname, $verified, $enabled, $is_admin);
$stmt->execute();
$stmt->store_result();

//$results = $stmt->get_result();
//echo "$stmt->num_rows matches found<br><br>";

$set = array();
while ($stmt->fetch())

{
  array_push($set, json_encode(array('user_id'    => $user_id, 
                                     'email'      => $email, 
                                     'first_name' => $fname, 
                                     'last_name'  => $lname, 
                                     'verified'   => $verified, 
                                     'enabled'    => $enabled, 
                                     'is_admin'   => $is_admin)));
}

echo json_encode(array("results" => $set, "num" => $stmt->num_rows));


?>
