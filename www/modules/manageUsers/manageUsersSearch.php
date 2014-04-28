<?php 

session_start();

$uid = $_SESSION['user'];

if (!isset($uid))
  die;

$by   = $_POST['by'];
$term = $_POST['term'];
$page = $_POST['page'];

$conn = new mysqli("collegecarpool.us","root","collegecarpool","purdue_test");
  

$sqlCount = "SELECT COUNT(user_id) FROM users";
$countRes = mysqli_query($conn,$sqlCount);
$rowCount = mysqli_fetch_row($countRes);

//Total row count
$total = $rowCount[0];
echo "<script>console.log(\"".$total."\"); </script>";
//Display this number of results
$page_rows = 5;

//Keep track of previous page number
$last = ceil($total/$page_rows);
echo "<script>console.log(\"".$last."\"); </script>";
// This makes sure $last cannot be less than 1
if($last < 1){
	$last = 1;
}
// Establish the $pagenum variable
$pagenum = 1;

// Get pagenum from URL vars if it is present, else it is = 1
if(isset($_GET['pn'])){
	//echo "<script>console.log(\"ISSET\"); </script>";
	$pagenum = preg_replace('#[^0-9]#', '', $_GET['pn']);
}
// This makes sure the page number isn't below 1, or more than our $last page
if ($pagenum < 1) { 
	$pagenum = 1; 
} else if ($pagenum > $last) { 
	$pagenum = $last; 
}
// This sets the range of rows to query for the chosen $pagenum
$limit = 'LIMIT ' .($pagenum - 1) * $page_rows .',' .$page_rows;
echo "<script>console.log(\"".$limit."\"); </script>";
//$query = "SELECT * FROM users $limit";

$query = "SELECT" .
         " user_id, email, alt_email, phone, first_name, last_name, verified, enabled, warned, is_admin " .
         "FROM users " .
         "WHERE LOWER($by) LIKE '%$term%' $limit";
 
$result = mysqli_query($con,$query);

// Establish the $paginationCtrls variable
$paginationCtrls = '';
// If there is more than 1 page worth of results
if($last != 1){
	/* First we check if we are on page one. If we are then we don't need a link to 
	   the previous page or the first page so we do nothing. If we aren't then we
	   generate links to the first page, and to the previous page. */
	if ($pagenum > 1) {
		$previous = $pagenum - 1;
		$paginationCtrls .= '
			<ul class="pagination">
			  <li><a href="#" onclick="changePage('.$previous.');">Previous</a></li>
			</ul>
		';
		// Render clickable number links that should appear on the left of the target page number
		for($i = $pagenum-4; $i < $pagenum; $i++){
			if($i > 0){
				//$paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'">'.$i.'</a> &nbsp; ';
				$paginationCtrls .= '<ul class="pagination">
				  <li><a href="#" onclick="changePage('.$i.');">'.$i.'</a></li>
				</ul>
				';
			}
		}
	}
	// Render the target page number, but without it being a link
	$paginationCtrls .= '
			<ul class="pagination">
			  <li class="disabled"><a href="#" onclick="changePage('.$pagenum.');">'.$pagenum.'</a></li>
			</ul>
		';

	// Render clickable number links that should appear on the right of the target page number
	for($i = $pagenum+1; $i <= $last; $i++){
		$paginationCtrls .= '<ul class="pagination">
				  <li><a href="#" onclick="changePage('.$i.');">'.$i.'</a></li>
				</ul>
				';
		if($i >= $pagenum+4){
			break;
		}
	}
	// This does the same as above, only checking if we are on the last page, and then generating the "Next"
	if ($pagenum != $last) {
		$next = $pagenum + 1;
		$paginationCtrls .= '<ul class="pagination">
				  <li><a href="#" onclick="changePage('.$next.');">Next</a></li>
				</ul>
				';
	}
}

$stmt = $conn->stmt_init();
$stmt = $conn->prepare($query);
$stmt->bind_result( $user_id, $email, $alt_email, $phone, $fname, $lname, $verified, $enabled, $warned, $is_admin);
$stmt->execute();
$stmt->store_result();

$set = array();
while ($stmt->fetch())

{
  array_push($set, json_encode(array('user_id'    => $user_id, 
                                     'email'      => $email, 
                                     'alt_email'  => $alt_email, 
                                     'phone'      => $phone, 
                                     'first_name' => $fname, 
                                     'last_name'  => $lname, 
                                     'verified'   => $verified, 
                                     'enabled'    => $enabled, 
                                     'warned'     => $warned, 
                                     'is_admin'   => $is_admin)));
}

echo json_encode(array("results" => $set, "num" => $stmt->num_rows));


?>
