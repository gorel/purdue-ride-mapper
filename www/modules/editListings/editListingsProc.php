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
			$listings_id = $_POST["listings_id"];
			$con=mysqli_connect("localhost","collegecarpool","collegecarpool","purdue_test");

			if(mysqli_connect_errno())
			{
				echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}
			else
			{
				$sql = "DELETE FROM listings WHERE listings_id = $listings_id";
				if (!mysqli_query($con,$sql))
				{
					die('Error: ' . mysqli_error($con));
					mysqli_close($con);
				}
				else
				{
					mysqli_close($con);
					header('Location: ../../index.php');
					exit();
				}				
			}
		?>

	</body>
</html>
