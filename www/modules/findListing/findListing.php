<!DOCTYPE html>
<html>
<body>
<hr class="featurette-divider">
<div class="row">
	<div class="col-lg-6">
		<div>
		<h2 class="form-signin-heading">Search for a ride:</h2>
			<form class="form-inline" role="form">
				<div class="form-group">
					<input type="text" class="form-control" placeholder="Starting Address">
				</div>
				<div class="form-group">
					<input type="text" class="form-control" placeholder="Destination Address">
				</div>
				<button type="submit" class="btn btn-default" onclick="calcRoute();" >Search</button>
			</form>
		</div>		
	</div>
	<div class="col-lg-6">
		<div id="map_canvas" style="height: 500px; width: 400px"></div>
	</div>
</div>
<?php
	$con=mysqli_connect("localhost","collegecarpool","collegecarpool","purdue_test");
	
	if(mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	else
	{
		$sql = "SELECT * FROM listings";
		$result = mysqli_query($con,$sql);
		echo "<table border='1'>
		<tr>
		<th> StartingAddress </th>
		<th> EndingAddress </th>
		<th> Request? </th>
		<th> Passengers </th>
		<th> Date of Departure </th>
		<th> User ID </th>
		</tr>";
		while($row = mysqli_fetch_array($result))
		{
		echo "<tr>";
		echo "<td>". $row['startingAddress'] . "</td>";
		echo "<td>". $row["endingAddress"] . "</td>";
		echo "<td>". $row["isRequest"] . "</td>";
		echo "<td>". $row["passengers"] . "</td>";
		echo "<td>". $row["dateOfDeparture"] . "</td>";
		echo "<td>". $row["user_id"] . "</td>";
		echo "</tr>";
		}
		echo "</table>";
	}
	mysqli_close($con);
?>
</body>
</html>