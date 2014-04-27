<?php
	$ticket_id = $_POST["ticket_id"];
	$ticket_answer = $_POST["ticket_answer"];
	$con=mysqli_connect("localhost","collegecarpool","collegecarpool","purdue_test");

	echo json_encode(array('success' => "CONNECTED"));
	die;

	if(mysqli_connect_errno())
	{
		echo json_encode(array('success' => "FAILURE1"));
	}
	else
	{
		$sql = "UPDATE tickets SET ticket_answer = $ticket_answer WHERE ticket_id = $ticket_id";
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
