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

			function qualityCodeCheck($qualityCode)
			{
				if(strpos($qualityCode,'C') !== false)//If the quality code contains 'C' it is bad
				{
					return false;
				}
				if(strpos($qualityCode,'P1') !== false || strpos($qualityCode,'A5') !== false || strpos($qualityCode,'Z1') !== false)//If the quality code indicates an exact point or city, it's good
				{
					return true;
				}
				return false; //When in doubt, assume it's a bad code
			}
			
			$startingAddress = $destinationAddress = $passengers = $dateTime = $isRequest = $user_id = "";
			$listings_id = $_POST["listingID"];
			$startingAddress = test_input($_POST["startingAddress"]);
			$destinationAddress = test_input($_POST["destinationAddress"]);
			$passengers = test_input($_POST["passengers"]);
			$dateTime = test_input($_POST["dateTime"]);
			$isRequest = test_input($_POST["isRequest"]);
			
			session_start();
			$user_id = $_SESSION['user'];
			$con=mysqli_connect("localhost", "collegecarpool", "collegecarpool", "purdue_test");
			
			if($isRequest == 1)
			{
				$passengers = 0;
			}
			
			// Check connection
			if (mysqli_connect_errno())
			{
				echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}
			else
			{				
				$sql="UPDATE listings 
				SET startingAddress = '$startingAddress', 
				endingAddress = '$destinationAddress',
				isRequest = '$isRequest',
				passengers = '$passengers',
				dateOfDeparture = '$dateTime'
				WHERE listings_id = '$listings_id'";
				
				if (!mysqli_query($con,$sql))
				{
					die('Error: ' . mysqli_error($con));
					mysqli_close($con);
				}
				else
				{
					mysqli_close($con);
					header('Location: ../../index.php?page=editListings');
					exit();
				}	
			}			
		?>

	</body>
</html>
