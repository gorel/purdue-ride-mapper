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
				$sql = "DELETE FROM listings WHERE listings_id = $listings_id";
				if (!mysqli_query($con,$sql))
				{
					echo "error";
					echo mysqli_error($con);
					mysqli_close($con);
				}
				else
				{
					mysqli_close($con);
					header('Location: ../../index.php?sort=title');
					exit();
				}		
			}
		?>

	</body>
</html>
