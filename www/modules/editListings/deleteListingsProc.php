<?php
	$listingsID = $_POST["listingsID"];
	$con=mysqli_connect("localhost","collegecarpool","collegecarpool","purdue_test");

	if(mysqli_connect_errno())
	{
		echo json_encode(array('success' => "FAILURE1"));
	}
	else
	{
		$sql = "DELETE FROM listings WHERE listings_id = $listingsID";
		if (!mysqli_query($con,$sql))
		{
			mysqli_close($con);
			echo json_encode(array('success' => "FAILURE2"));
		}
		else
		{
			mysqli_close($con);
			echo json_encode(array('success' => "SUCCESS"));
		}		
	}
?>
