<hr class="featurette-divider">
<div class="container" >
	<?php
		$con=mysqli_connect("localhost","collegecarpool","collegecarpool","purdue_test");

		if(mysql_connect_errno())
		{
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		else
		{
			session_start();
			$user_id = $_SESSION['user'];

			$sql = "SELECT * FROM users";
			$result = mysqli_query($con, $sql);




		}

	?>



	<div class="row">
		<p>Manage Users</p>
	</div> <!-- row -->
</div> <!-- /container -->
