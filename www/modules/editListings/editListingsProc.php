<?php
	$listings_id = $_GET['listings_id'];
	$con=mysqli_connect("localhost","collegecarpool","collegecarpool","purdue_test");

	if(mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	else
	{
		$sql = "DELETE FROM listings WHERE listings_id = $listings_id";
		$result = mysqli_query($con,$sql);		
	}
	mysqli_close($con);
?>