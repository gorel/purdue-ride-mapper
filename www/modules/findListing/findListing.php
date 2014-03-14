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
		<script>
			//This script create the map with a default address.
			//Its current location is somewhere by College Station
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
				var rides = "<?php echo $num_rides; ?>";
				var lat1 = new Array(rides);
				var lat2 = new Array(rides);
				var lon1 = new Array(rides);
				var lon2 = new Array(rides);
				for (var i = 0; i < rides;i++)
				{
					lat1[0] = "<?php echo $start_lat1[0]; ?>";
					lat2[0] = "<?php echo $start_lat2[0]; ?>";
					lon1[0] = "<?php echo $end_lon1[0]; ?>";
					lon2[0] = "<?php echo $end_lon2[0]; ?>";
				
					//This add the start address marker
					map.addMarker
					({
						lat:lat1[0],
						lng: lon1[0],
					});

					//This will add the route drawing from start to destination
					//Colors can be changed (Its in Hex)
					map.drawRoute
					({
						origin: [lat1[0],lon1[0]],
						destination: [lat2[0],lon2[0]],
						travelMode: 'driving',
						strokeColor: '#0000FF',
						strokeOpacity: 0.6,
						strokeWeight: 6
					});

					//This add the destination address marker
					map.addMarker
					({
						lat:lat2[0],
						lng:lon2[0],
					});
				}
				//map.fitZoom();
			});
		</script>
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
				</tr>
				</thead>";
				//$i = 0;
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
					echo "</tr>";
					
					$start_lat1[]= $row["start_lat"];
					$end_lon1[]= $row["start_long"];
					$start_lat2[] = $row["end_lat"];
					$end_lon2[] = $row["end_long"];
					$num_rides = $row["listings_id"];
				}
				echo "</table>";
				
			}
			mysqli_close($con);
		}
	?>
	</div>
</body>
</html>
