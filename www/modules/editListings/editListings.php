<hr class="featurette-divider">
<div class="container" >
	<div class="row">
		<?php
			$con=mysqli_connect("localhost","collegecarpool","collegecarpool","purdue_test");

			if(mysqli_connect_errno())
			{
				echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}
			else
			{
				session_start();
				$user_id = $_SESSION['user'];
			
				$sql = "SELECT * FROM listings";
				$result = mysqli_query($con,$sql);
				echo "<table class='table table-striped'>
				<thead>
				<tr>
				<th> StartingAddress </th>
				<th> EndingAddress </th>
				<th> Request? </th>
				<th> Passengers </th>
				<th> Date of Departure </th>
				</tr>
				</thead>";
				while($row = mysqli_fetch_array($result))
				{
					if($_SESSION['user'] == $row["user_id"])
					{
						echo "<tr>";
						echo "<td>". $row['startingAddress'] . "</td>";
						echo "<td>". $row["endingAddress"] . "</td>";
						if($row["isRequest"] == 0)
						{
							echo "<td> No </td>";
						}
						else
						{
							echo "<td> Yes </td>";
						}

						echo "<td>". $row["passengers"] . "</td>";
						echo "<td>". $row["dateOfDeparture"] . "</td>";
						echo "</tr>";
					}
				}
				echo "</table>";
			}
			mysqli_close($con);
		?>
	</div> <!-- row -->
</div> <!-- /container -->
