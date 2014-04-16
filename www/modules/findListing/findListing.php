<!DOCTYPE html>

<?php session_start(); // TODO: need to handle logged in/out cases - tim ?>

<html>
<body>
<!-- Scripts for the datetimepicker -->
<link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />
<script type='text/javascript' src='js/jquery-1.11.0.min.js'></script>
<script type='text/javascript' src='js/moment.min.js'></script>
<script type='text/javascript' src='js/bootstrap.min.js'></script>
<script type='text/javascript' src='js/bootstrap-datetimepicker.min.js'></script>

<!-- VIEW ROUTE MODAL -->
<div class="modal fade" id="routeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Ride Details</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div id="modal_map_canvas" style="height: 400px; width: 100%"></div>
				</div>
				<hr class="featurette-divider">
				<div class="row">
					<div class="col-md-6">
						<table class="table table-striped">
							<tr>
								<td><b>Starting Address:</b></td>
								<td id="startingAddressModal">Foobar Drive</td>
							</tr>
							<tr>
								<td><b>Ending Address:</b></td>
								<td id="endingAddressModal">Foobar Road</td>
							</tr>
							<tr>
								<td><b>Listing Type:</b></td>
								<td id="rideTypeModal">Hosting a Ride</td>
							</tr>
							<tr>
								<td><b>Number of Passengers</b></td>
								<td id="numberOfPassengersModal">3</td>
							</tr>
							<tr>
								<td><b>Date of Departure:</b></td>
								<td id="dateOfDepartureModal">2014-03-26 08:01:00</td>
							</tr>
						</table>
					</div>
					<div class="col-md-6">
						<p><b>Send them a message!</b></p>
						<p>To: johndoe@purdue.edu</p>
						<form enctype="application/x-www-form-urlencoded" class="form-horizontal" action="/modules/contact/contactProc.php" method="POST">
							<div class="control-group">
								<div class="controls">
									<textarea name="text" id="text" rows="6" class="form-control" cols="80"></textarea>
								</div>
							</div>
							<br>
							<div class="form-actions">
								<button class="btn btn-lg btn-primary btn-block" id="sendButton">Send</button>
							</div>
						</form>
					</div>
				</div>					
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<hr class="featurette-divider">
<div class="row">
		<div id="map_canvas" style="height: 400px; width: 100%"></div>
		<hr class="featurette-divider">
		<div>
			<h2 class="form-signin-heading">Search for a ride:</h2>
			<form class="form-inline" role="form">
				<div class="form-group">
					<input id='starting_address_field' type="text" class="form-control" placeholder="Starting Address">
				</div>
				<div class="form-group">
					<input id='ending_address_field' type="text" class="form-control" placeholder="Destination Address">
				</div>
				<div class="form-group">
					<div class='input-group date' id=datetimepicker'>
						<input id='date_field' type="text" class="form-control" placeholder="Departure Date (optional)" data-format="YYYY-MM-DD hh:mm:ss"/>
						<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
					</div>
				</div>
				<script type='text/javascript'>
					$(function () {
						$('#datetimepicker').datetimepicker();
					});
				</script>

				<button type="submit" class="btn btn-default" onclick="matchNewAddress(); return false;" >Search</button>
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

			function matchListing()
			{
				var listing_id = parseInt(document.getElementById('listing_id_field').value);
				loadParameter("matchValue", listing_id);
			}

			function matchNewAddress()
			{
				var starting_address = document.getElementById('starting_address_field').value.split(' ').join('+');
				var ending_address = document.getElementById('ending_address_field').value.split(' ').join('+');
				var departure_date = document.getElementById('date_field').value.split(' ').join('+');

				$("#content").load("modules/findListing/findListing.php?starting_address=" + starting_address + "&ending_address=" + ending_address + "&date=" + departure_date);
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
		function random_color_part() {
			return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
		}

		function random_color() {
			return random_color_part() . random_color_part() . random_color_part();
		}


		function debug_to_console($data)
		{
			if (is_array($data))
				$output = "<script>console.log('Debug: " . implode(', ', $data) . "');</script>";
			else
				$output = "<script>console.log('Debug: " . $data . "');</script>";
			echo $output;
		}

		// matcher can now be taken out of the if-else condition. There was a
                // php session error and I moved the call to start session to the top - tim

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
					$matchNum = htmlspecialchars($_GET['matchValue']);
					echo "<h2>Trying to match rides to this listing:</h2>";
					echo "<table class='table table-striped'>
					<thead>
					<tr>
					<th> Listing ID </th>
					<th> Starting Address </th>
					<th> Ending Address </th>
					<th> Date of Departure </th>
					<th> Type </th>
					</thead>";

					$sql = "SELECT * FROM listings WHERE listings_id=$matchNum";
					$result = mysqli_query($con, $sql);
					$row = mysqli_fetch_array($result);
					echo '<tr id="'.$row['listings_id'].'">';
					echo '<td id="'.$row['listings_id'].'_Listing_ID">'.$row['listings_id'].'</td>';
					echo '<td id="'.$row['listings_id'].'_Starting_Address">'.$row['startingAddress'].'</td>';
					echo '<td id="'.$row['listings_id'].'_Ending_Address">'.$row['endingAddress'].'</td>';
					echo '<td id="'.$row['listings_id'].'_Date_Of_Departure">'.$row['dateOfDeparture'].'</td>';
					if($row["isRequest"] == 0)
					{
						echo '<td id="'.$row['listings_id'].'_Ride_Type">Offering Ride</td>';
					}
					else
					{
						echo '<td id="'.$row['listings_id'].'_Ride_Type">Requesting Ride</td>';
					}
					
					echo "</table>";

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

					echo "<h2>Matching listings:</h2>";
					$matches = array();
					exec('python ../../../src/matcher.py '. $matchNum, $matches);

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
								echo '<tr id="'.$row['listings_id'].'">';
								echo '<td id="'.$row['listings_id'].'_Listing_ID">'.$row['listings_id'].'</td>';
								echo '<td id="'.$row['listings_id'].'_Match">'.$match.'</td>';
								echo '<td id="'.$row['listings_id'].'_Starting_Address">'.$row['startingAddress'].'</td>';
								echo '<td id="'.$row['listings_id'].'_Ending_Address">'.$row['endingAddress'].'</td>';
								echo '<td id="'.$row['listings_id'].'_Date_Of_Departure">'.$row['dateOfDeparture'].'</td>';
								echo '<td>'.$i.'</td>';
								echo '<input id="'.$row['listings_id'].'_Start_Lat" type="hidden" value="'.$row['start_lat'].'">';
								echo '<input id="'.$row['listings_id'].'_Start_Long" type="hidden" value="'.$row['start_long'].'">';
								echo '<input id="'.$row['listings_id'].'_End_Lat" type="hidden" value="'.$row['end_lat'].'">';
								echo '<input id="'.$row['listings_id'].'_End_Long" type="hidden" value="'.$row['end_long'].'">';
								echo "<td>
										<button class=\"btn btn-success\" data-id=\"". $row['listings_id'] ."\" onclick=\"showRouteModal(".$row['listings_id'].");\">View</button>
									</td>";
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
												strokeColor: '". random_color() ."',
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
				else if (isset($_GET['starting_address']))
				{
					$starting_address = htmlspecialchars($_GET['starting_address']);
					$ending_address = htmlspecialchars($_GET['ending_address']);
					$date = htmlspecialchars($_GET['date']);
					$matches = array();
					
					echo "<h2>Finding matches starting near ". $starting_address . " and ending near ". $ending_address . "</h2>";

					echo "<h2>Requests that match your search:</h2>";
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

					exec('python ../../../src/matcher2.py "'. $starting_address . '" "' . $ending_address . '" "' . $date . '"', $matches);

					//TODO: Split on OFFERS
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
						foreach($matches as $match)
						{
							if ($match === "OFFERS")
							{
								echo "</table>";
								echo "<br>";
								echo "<h2>Offers that match your search:</h2>";
								echo "<table class=table table-striped'>
								<thead>
								<tr>
								<th> Listing ID </th>
								<th> Match % </th>
								<th> Starting Address </th>
								<th> Ending Address </th>
								<th> Date of Departure </th>
								</tr>
								</thead>";
								continue;
							}

							$val = explode(' ', $match);
							$match = $val[0];
							$id = $val[1];
							$sql = "SELECT * FROM listings WHERE listings_id=$id";
							$result = mysqli_query($con,$sql);
							while($row = mysqli_fetch_array($result))
							{
								echo '<tr id="'.$row['listings_id'].'">';
								echo '<td id="'.$row['listings_id'].'_Listing_ID">'.$row['listings_id'].'</td>';
								echo '<td id="'.$row['listings_id'].'_Match">'.$match.'</td>';
								echo '<td id="'.$row['listings_id'].'_Starting_Address">'.$row['startingAddress'].'</td>';
								echo '<td id="'.$row['listings_id'].'_Ending_Address">'.$row['endingAddress'].'</td>';
								echo '<td id="'.$row['listings_id'].'_Date_Of_Departure">'.$row['dateOfDeparture'].'</td>';
								echo "<td>". $i . "</td>";
								echo '<input id="'.$row['listings_id'].'_Start_Lat" type="hidden" value="'.$row['start_lat'].'">';
								echo '<input id="'.$row['listings_id'].'_Start_Long" type="hidden" value="'.$row['start_long'].'">';
								echo '<input id="'.$row['listings_id'].'_End_Lat" type="hidden" value="'.$row['end_lat'].'">';
								echo '<input id="'.$row['listings_id'].'_End_Long" type="hidden" value="'.$row['end_long'].'">';
								echo "<td>
										<button class=\"btn btn-success\" data-id=\"". $row['listings_id'] ."\" onclick=\"showRouteModal(".$row['listings_id'].");\">View</button>
									</td>";

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
												strokeColor: '". random_color() ."',
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
						echo "</table>";
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
					<th> Starting Address </th>
					<th> Ending Address </th>
					<th> Ride Type </th>
					<th> Passengers </th>
					<th> Date of Departure </th>
					<th> Listing ID </th>
					</tr>
					</thead>";
					$i = 0;
					while($row = mysqli_fetch_array($result))
					{
						echo '<tr id="'.$row['listings_id'].'">';
						echo '<td id="'.$row['listings_id'].'_Starting_Address">'.$row['startingAddress'].'</td>';
						echo '<td id="'.$row['listings_id'].'_Ending_Address">'.$row['endingAddress'].'</td>';
						if($row["isRequest"] == 0)
						{
							echo '<td id="'.$row['listings_id'].'_Ride_Type">Offering Ride</td>';
						}
						else
						{
							echo '<td id="'.$row['listings_id'].'_Ride_Type">Requesting Ride</td>';
						}

						echo '<td id="'.$row['listings_id'].'_Passengers">'.$row['passengers'].'</td>';
						echo '<td id="'.$row['listings_id'].'_Date_Of_Departure">'.$row['dateOfDeparture'].'</td>';
						echo "<td>". $row['listings_id'] . "</td>";
						echo "<td>
							<button class=\"btn btn-success\" data-id=\"". $row['listings_id'] ."\" onclick=\"showRouteModal(".$row['listings_id'].");\">View</button>
							</td>";
						echo '<input id="'.$row['listings_id'].'_Start_Lat" type="hidden" value="'.$row['start_lat'].'">';
						echo '<input id="'.$row['listings_id'].'_Start_Long" type="hidden" value="'.$row['start_long'].'">';
						echo '<input id="'.$row['listings_id'].'_End_Lat" type="hidden" value="'.$row['end_lat'].'">';
						echo '<input id="'.$row['listings_id'].'_End_Long" type="hidden" value="'.$row['end_long'].'">';
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
												strokeColor: '". random_color() ."',
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
	
	<script>
		var modalMap;
		var listingID;
		
		function showRouteModal(listing_ID)
		{		
			listingID = listing_ID;
			
			var startingAddressModal = document.getElementById('startingAddressModal');
			var endingAddressModal = document.getElementById('endingAddressModal');
			var dateOfDepartureModal = document.getElementById('dateOfDepartureModal');
			var rideTypeModal = document.getElementById('rideTypeModal');
			var numberOfPassengersModal = document.getElementById('numberOfPassengersModal');
			
			startingAddressModal.innerHTML = document.getElementById(listingID + "_Starting_Address").innerHTML.trim();
			endingAddressModal.innerHTML = document.getElementById(listingID + "_Ending_Address").innerHTML.trim();
			dateOfDepartureModal.innerHTML = document.getElementById(listingID + "_Date_Of_Departure").innerHTML.trim();
			rideTypeModal.innerHTML = document.getElementById(listingID + "_Ride_Type").innerHTML.trim();
			numberOfPassengersModal.innerHTML = document.getElementById(listingID + "_Passengers").innerHTML.trim();
			
			$('#routeModal').modal('show');
		}

		$('#routeModal').on('shown.bs.modal', function() {
			var modalMap = new GMaps
				({
					div: '#modal_map_canvas',
					lat: 40.431042,
					lng: -86.913651,
					zoomControl : true,
					zoomControlOpt:
					{
						style : 'SMALL',
					},
					panControl : false,
				});
			google.maps.event.trigger(modalMap, "resize");	

			var startLat = document.getElementById(listingID + '_Start_Lat');	
			var startLong = document.getElementById(listingID + '_Start_Long');	
			var endLat = document.getElementById(listingID + '_End_Lat');	
			var endLong = document.getElementById(listingID + '_End_Long');	
			
			modalMap.addMarker({
			  lat: -12.043333,
			  lng: -77.028333,
			  title: 'Lima',
			  click: function(e) {
				alert('You clicked in this marker');
			  }
			});
			/*
			modalMap.addMarker
			({
				lat:startLat,
				lng:startLong,
			});
			modalMap.drawRoute
			({
				origin: [startLat, startLong],
				destination: [endLat, endLong],
				travelMode: 'driving',
				strokeColor: '#FF0000',
				strokeOpacity: 0.6,
				strokeWeight: 6
			});
			modalMap.addMarker
			({
				lat:endLat,
				lng:endLong,
			})*/
			modalMap.fitZoom();
		});
	</script>

</body>
</html>
