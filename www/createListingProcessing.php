<!DOCTYPE html>
<html>
	<head>
		<!-- Bootstrap core CSS -->
		<link href="css/bootstrap.css" rel="stylesheet">


		<!-- Custom styles for this template -->
		<link href="signin.css" rel="stylesheet">

		<!-- Custom scripts for the datatimepicker -->
		<link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />
		<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>		
		<script type="text/javascript" src="js/moment.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
	</head>
	<body>
		<h1 align="center">Processing your request...</h1>
		<h2 align="center">You will be redirected once your request has been processed.</h2>

		<?php
			function test_input($data)
			{
			   $data = trim($data);
			   $data = stripslashes($data);
			   $data = htmlspecialchars($data);
			   return $data;
			}
			
			$startingAddress = $destinationAddress = $passengers = $dateTime = $isRequest = "";
			
			$startingAddress = test_input($_POST["startingAddress"]);
			$destinationAddress = test_input($_POST["destinationAddress"]);
			$passengers = test_input($_POST["passengers"]);
			$dateTime = test_input($_POST["dateTime"]);
			$isRequest = test_input($_POST["isRequest"]);
			
			$con=mysqli_connect("localhost", "collegecarpool", "collegecarpool", "purdue_test");
			// Check connection
			if (mysqli_connect_errno())
			{
				echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}
			else
			{
				echo "IT WORKED!!!";
				
				$sql="INSERT INTO listings (startingAddress, endingAddress, isRequest, passengers, dateOfDeparture)
				VALUES
				('$startingAddress','$destinationAddress','$isRequest','$passengers','$dateTime')";

				if (!mysqli_query($con,$sql))
				{
					die('Error: ' . mysqli_error($con));
					mysqli_close($con);
				}
				else
				{
					echo $startingAddress;
					echo $destinationAddress;
					echo $isRequest;
					echo $passengers;
					echo $dateTime;
					mysqli_close($con);
					//header('Location: findListing.html');
					//exit();
				}		
			}			
		?>
	</body>
</html>