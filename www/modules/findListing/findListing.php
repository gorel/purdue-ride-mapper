<!DOCTYPE html>
<html>
<body>
<hr class="featurette-divider">
<div class="row">
		<div id="map_canvas" style="height: 400px; width: 100%"></div>
		<hr class="featurette-divider">
		<div>
		<!-- TODO: Allow search by destination -->
			<h2 class="form-signin-heading">Search for a ride:</h2>
			<form class="form-inline" role="form">
				<div class="form-group">
					<input id='starting_address_field' type="text" class="form-control" placeholder="Starting Address">
				</div>
				<div class="form-group">
					<input id='ending_address_field' type="text" class="form-control" placeholder="Destination Address">
				</div>
				<button type="submit" class="btn btn-default" onclick="calcRoute(); return false;" >Search</button>
			</form>
		</div>
		<br>

		<div>
			<h2 class="form-signin-heading">Match a ride:</h2>
			<form class="form-inline" role="form">
				<div class="form-group">
					<input id='listing_id_field' type="text" class="form-control" placeholder="Listing ID">
				</div>
				<button type="submit" class="btn btn-default" onclick="matchListing(); return false;" >Match</button>
			</form>
		</div>
		<br>

		<script>
			var map;
			function loadParameter(key, val)
			{
				if (isNaN(val))
					$("#content").load("modules/findListing/findListing.php?NaNerror");
				else
					$("#content").load("modules/findListing/findListing.php?" + key + "=" + val);
			}

			function calcRoute()
			{
				console.log("Not yet implemented.");
			}

			function matchListing()
			{
				var listing_id = parseInt(document.getElementById('listing_id_field').value);
				loadParameter("matchValue", listing_id);
			}

			function matchNewAddress()
			{
				//var starting_address = document.getElementById('starting_address_field').value;
				//var ending_address = document.getElementById('ending_address_field').value;
				//$("#content").load("modules/findListing/findListing.php?starting_address=" + starting_address + "&ending_address=" + ending_address;
			}

			//This script create the map with a default address.
			//Its current location is somewhere by College Station
			$(document).ready(function ()
			{
				 map = new GMaps
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
			});
		</script>


	<div id='matcher_wrapper'></div>

	<div>
	<?php
		session_start();

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
				if (isset($_GET['matchValue']))
				{
					echo "<table class='table table-striped'>
					<thead>
					<tr>
					<th> Listing ID </th>
					<th> Match % </th>
					<th> Starting Address </th>
					<th> Ending Address </th>
					<th> Date of Departure </th>
					</tr>
					</thead>";

					$matchNum = htmlspecialchars($_GET['matchValue']);
					echo "<h1> Listings matched to Listing ID #" . $matchNum . ":</h1>";
					$matches = explode('\n', exec('python ../../../src/matcher.py '. $matchNum));

					//If len(output) == 0, print "no matches"
					if (strlen($matches[0]) == 0)
					{
						echo "<tr>";
						echo '<td> </td>';
						echo "<td>No matches found.</td>";
						echo "<td> </td>";
						echo "<td> </td>";
						echo "</tr>";
					}
					else
					{

						//For each match
						foreach($matches as $match)
						{
							$val = explode(' ', $match);
							$match = $val[0];
							$id = $val[1];
							$sql = "SELECT * FROM listings WHERE listings_id=$id";
							$result = mysqli_query($con,$sql);
							while($row = mysqli_fetch_array($result))
							{
								echo "<tr>";
								echo '<td>'. $row['listings_id'] . '</td>';
								echo '<td>'. $match .'</td>';
								echo "<td>". $row['startingAddress'] . "</td>";
								echo "<td>". $row['endingAddress'] . "</td>";
								echo "<td>". $row['dateOfDeparture'] . "</td>";
								echo "<td>". $i . "</td>";
								echo "</tr>";
								echo "<script>
										$(document).ready(function()
										{
											map.addMarker
											({
												lat:". $row['start_lat'] . ",
												lng:". $row['start_long'] . ",
											});
											map.drawRoute
											({
												origin: [". $row['start_lat'] .", " . $row['start_long'] . "],
												destination: [". $row['end_lat'].", " . $row['end_long'] . "],
												travelMode: 'driving',
												strokeColor: '#0000FF',
												strokeOpacity: 0.6,
												strokeWeight: 6
											});
											map.addMarker
											({
												lat:". $row['end_lat'] . ",
												lng:". $row['end_long'] . ",
											})
											map.fitZoom();
										});
									</script>
									";

							}
						}
					}
				}
				else
				{
					if (isset($_GET['NaNerror']))
						echo "<div style='color: red; font-size: 14pt;'>Error: Value is not a number.</div>";

					$sql = "SELECT * FROM listings";
					$result = mysqli_query($con,$sql);

					echo "<h1>All listings:</h1>";
					echo "<table class='table table-striped'>
					<thead>
					<tr>
					<th> StartingAddress </th>
					<th> EndingAddress </th>
					<th> Ride Type </th>
					<th> Passengers </th>
					<th> Date of Departure </th>
					<th> Listing ID </th>
					</tr>
					</thead>";
					$i = 0;
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

						echo "<td>". $row['passengers'] . "</td>";
						echo "<td>". $row['dateOfDeparture'] . "</td>";
						echo "<td>". $row['listings_id'] . "</td>";
						echo "</tr>";
						echo "<script>
										$(document).ready(function()
										{
											map.addMarker
											({
												lat:". $row['start_lat'] . ",
												lng:". $row['start_long'] . ",
											});
											map.drawRoute
											({
												origin: [". $row['start_lat'] .", " . $row['start_long'] . "],
												destination: [". $row['end_lat'].", " . $row['end_long'] . "],
												travelMode: 'driving',
												strokeColor: '#0000FF',
												strokeOpacity: 0.6,
												strokeWeight: 6
											});
											map.addMarker
											({
												lat:". $row['end_lat'] . ",
												lng:". $row['end_long'] . ",
											})
											map.fitZoom();
										});
									</script>
									";
					}
					echo "</table>";

				}
			}
			mysqli_close($con);
		}
	?>
	</div>
</body>
</html>
