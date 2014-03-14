<!DOCTYPE html>
<html>
<body>
<hr class="featurette-divider">
<div class="row">
		<div id="map_canvas" style="height: 400px; width: 100%"></div>
		<hr class="featurette-divider">
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
		<br>
	<div>
	<?php
		session_start();
		if (!isset($_SESSION['user']))
		{
		}
		else
		{
			$con=mysqli_connect("localhost","collegecarpool","collegecarpool","purdue_test");

			if(mysqli_connect_errno())
			{
				echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}
			else
			{
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
				<th> Listing_id </th>
				</tr>
				</thead>";
				$i = 1;
				while($row = mysqli_fetch_array($result))
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
					echo "<td>". $i . "</td>";
					echo "</tr>";
					
					$start_lat1[]= $row["start_lat"];
					$end_lon1[]= $row["start_long"];
					$start_lat2[] = $row["end_lat"];
					$end_lon2[] = $row["end_long"];
					$i++;
				}
				$num_rides = $i;
				echo "</table>";
				echo " <script>
					$(document).ready(function ()
					{
						var map = new GMaps
						({
							div: '#map_canvas',
							lat: 40.431042,
							lng: -86.913651,
							zoomControl : true,
							zoomControlOpt:
							{
								style : 'SMALL',
							},
							panControl : false,
						});
						for(var i = 0; i < \"$num_rides\";i++)
						{
							//This add the start address marker
								map.addMarker
								({
									lat:\"$start_lat1\"[i],
									lng: \"$end_lon1\"[i],
								});
								//This will add the route drawing from start to destination
								//Colors can be changed (Its in Hex)
								map.drawRoute
								({
									origin: [\"$start_lat1\"[i],\"$end_lon1\"[i]],
									destination: [\"$start_lat2\"[i],\"$end_lon2\"[i]],
									travelMode: 'driving',
									strokeColor: '#0000FF',
									strokeOpacity: 0.6,
									strokeWeight: 6
								});
								//This add the destination address marker
								map.addMarker
								({
									lat:\"$start_lat2\"[i],
									lng: \"$end_lon2\"[i],
								});
						}
							map.fitZoom();
					});
				</script>"
			}
			mysqli_close($con);
		}
	?>
	</div>
</body>
</html>
