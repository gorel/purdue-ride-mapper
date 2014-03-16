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
		
		<div id="listing_id_input" style="height: 400px; width: 100%"></div>
		<hr class="featurette-divider">
		<div>
			<h2 class="form-signin-heading">Match a ride:</h2>
			<form class="form-inline" role="form">
				<div class="form-group">
					<input type="text" class="form-control" placeholder="Listing ID">
				</div>
				<button type="submit" class="btn btn-default" onclick="getMatches();" >Search</button>
			</form>
		</div>
		<br>
		<script>
			function getMatches()
			{
				document.write('<h1>It works!</h1>');
			}
		
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
				var lat1 = <?php echo json_encode($start_lat1); ?>;
				var lat2 = <?php echo json_encode($start_lat2); ?>;
				var lon1 = <?php echo json_encode($end_lon1); ?>;
				var lon2 = <?php echo json_encode($end_lon2); ?>;
				for (var i = 0; i < rides;i++)
				{
					//This add the start address marker
					map.addMarker
					({
						lat:lat1[i],
						lng: lon1[i],
					});

					//This will add the route drawing from start to destination
					//Colors can be changed (Its in Hex)
					map.drawRoute
					({
						origin: [lat1[i],lon1[i]],
						destination: [lat2[i],lon2[i]],
						travelMode: 'driving',
						strokeColor: '#0000FF',
						strokeOpacity: 0.6,
						strokeWeight: 6
					});

					//This add the destination address marker
					map.addMarker
					({
						lat:lat2[i],
						lng:lon2[i],
					});
				}
				map.fitZoom();
				
			});
		</script>
	<div>
	<?php
		session_start();
		
		//Use this to print variables to the web console for debugging purposes
		function debug_to_console($data)
		{
			if (is_array($data))
				$output = "<script>console.log('Debug: " . implode(', ', $data) . "');</script>";
			else
				$output = "<script>console.log('Debug: " . $data . "');</script>";
			echo $output;
		}

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
				//Find matches to this listing
				$output = exec('python ../../../src/matcher.py 1');
				
				if (0)
				{
				
				}
				else
				{
					echo "<table class='table table-striped'>
					<thead>
					<tr>
					<th> Listing ID </th>
					<th> Match Percentage </th>
					<th> Starting Address </th>
					<th> Ending Address </th>
					<th> Date of Departure </th>
					</tr>
					</thead>";
				}

				//TODO: If len(output) == 0, print "no matches"

				//For each match
				foreach(explode('\n', $output) as $line)
				{
					//Get the match's score and id
					$val = explode(' ', $line);
					$score = $val[0]
					$id = $val[1]
					
					//Get the match from the database
					$sql = "SELECT * FROM listings WHERE listings_id=$id";
					$result = mysqli_query($con,$sql);
					
					//Print the match to the table
					while($row = mysqli_fetch_array($result))
					{
						echo "<tr>";
						echo '<td>'. $id . '</td>';
						echo '<td>'. $score .'</td>';
						echo "<td>". $row['startingAddress'] . "</td>";
						echo "<td>". $row["endingAddress"] . "</td>";
						echo "<td>". $row["dateOfDeparture"] . "</td>";
						echo "</tr>";
					}
				}
			
				//Print every listing in the database
				$sql = "SELECT * FROM listings";
				$result = mysqli_query($con,$sql);
				echo "<table class='table table-striped'>
				<thead>
				<tr>
				<th> StartingAddress </th>
				<th> EndingAddress </th>
				<th> Ride Type </th>
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
						echo "<td>Offering Ride</td>";
					}
					else
					{
						echo "<td>Requesting Ride</td>";
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
				
			}
			mysqli_close($con);
		}
	?>
	</div>
</body>
</html>
