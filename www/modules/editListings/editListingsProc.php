<!DOCTYPE html>
<html>
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
				echo "hello!";	
			}
		?>

	</body>
</html>
