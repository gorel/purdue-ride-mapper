<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript" src="js/gmaps.js"></script>

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
		<div>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Starting Location</th>
						<th>Destination</th>
						<th>Capacity</th>
						<th>Date of Departure</th>
						<th>Request</th>
						<th>User</th>
					</tr>
				</thead>
					<tr>
						<td>2243 US HWY 52 W, West Lafayette IN 47906</td>
						<td>Purdue University, West Lafayette IN 47906</td> 
						<td>3</td>
						<td>2/25/2014</td>
						<td>Yes</td>
						<td>evan@purdue.edu</td>
					</tr>
			</table>
		</div>
	</div>
	<div class="col-lg-6">
		<div id="map_canvas" style="height: 500px; width: 400px"></div>
		<script>
			$(document).ready(function () 
			{
				var map = new GMaps
				({
					div: '#map_canvas',
					lat: 40.463666,
					lng: -86.945828,
				    zoom: 12,
					zoomControl : true,
					zoomControlOpt: 
					{
						style : 'SMALL',
					},
					panControl : false,
				});
				
				map.addMarker
				({
					lat:40.463666,
					lng: -86.945828,
				});
				
				map.drawRoute
				({
					origin: [40.463666,-86.945828],
					destination: [40.422906,-86.910637],
					travelMode: 'driving',
					strokeColer: '#131540',
					strokeOpacity: 0.6,
					strokeWeight: 6
				});
				
				map.addMarker
				({
					lat:40.422906,
					lng:-86.910637,
				});
			});
		</script>
	</div>
</div>