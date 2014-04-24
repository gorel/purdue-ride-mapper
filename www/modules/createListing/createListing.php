<!-- Bootstrap core CSS -->
<link href="css/bootstrap.css" rel="stylesheet">


<!-- Custom styles for this template -->
<link href="css/custom.css" rel="stylesheet">

<!-- Custom scripts for the datatimepicker -->
<link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />
<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>		
<script type="text/javascript" src="js/moment.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
<script>
	function disablePassengers()
	{
		var passengers = document.getElementById('passengersTextBox');
		passengers.disabled = true;
	}
	function enablePassengers()
	{
		var passengers = document.getElementById('passengersTextBox');
		passengers.disabled = false;
	}
</script>

<script type="text/javascript">
function createListing()
{
	var startingLocation = document.getElementById('startingLocation').value;
	var destinationAddress = document.getElementById('endingLocation').value;
	var passengers = document.getElementById('passengersTextBox').value;
	var dateTime = document.getElementById('dateTime').value;
	var isRequest = 0;
	
	if(document.getElementById('passengersTextBox').disabled)
	{
		isRequest = 1;
	}
	else
	{
		isRequest = 0;
	}
	
	console.log(isRequest);
	console.log(startingLocation);
	console.log(destinationAddress);
	console.log(passengers);
	console.log(dateTime);

	$.ajax ({
		type: "POST",
		url: "/modules/createListing/createListingProc.php",
		dataType: "json",
		beforeSend: function() 
		{
			document.getElementById('progressCreate').style.visibility = 'visible';
			document.getElementById('submitButton').disabled = true;
		},
		complete: function()
		{
			document.getElementById('progressCreate').style.visibility = 'hidden';
			document.getElementById('submitButton').disabled = false;
		},
		data: 
		{
			"startingAddress" : startingLocation, 
			"destinationAddress" : destinationAddress, 
			"passengers" : passengers, 
			"dateTime" : dateTime, 
			"isRequest" : isRequest
		},
		success: function(data) 
		{					
			console.log("success");	

			if(data.success == "SUCCESS")
			{
				console.log("Created Listing");
				document.getElementById('startingLocation').value = "";
				document.getElementById('endingLocation').value = "";
				document.getElementById('passengersTextBox').value = "";
				document.getElementById('dateTime').value = "";
				alert("The listing was successfully created.");				
			}
			else
			{
				alert("Address or time format not valid. Please check and try again.\nPlease use the calendar tool.");
			}
		} 
	});
}

</script>

<link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500">
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>
<script type="text/javascript">
	// This example displays an address form, using the autocomplete feature
	// of the Google Places API to help users fill in the information.

	var autocomplete;

	function initialize() 
	{
		console.log("Trying to call 1");
		// Create the autocomplete object, restricting the search
		// to geographical location types.
		autocomplete = new google.maps.places.Autocomplete( /** @type{HTMLInputElement} */(document.getElementById('startingLocation')),{ types: ['geocode'] });
		// When the user selects an address from the dropdown,
		// populate the address fields in the form.
		google.maps.event.addListener(autocomplete, 'place_changed', function() 
		{
			fillInAddress();
		});
	}
</script>

<link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500">
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>
<script type="text/javascript">
	// This example displays an address form, using the autocomplete feature
	// of the Google Places API to help users fill in the information.

	var autocomplete;

	function initialize2() 
	{
		console.log("Trying to call 2");
		// Create the autocomplete object, restricting the search
		// to geographical location types.
		autocomplete = new google.maps.places.Autocomplete( /** @type{HTMLInputElement} */(document.getElementById('endingLocation')),{ types: ['geocode'] });
		// When the user selects an address from the dropdown,
		// populate the address fields in the form.
		google.maps.event.addListener(autocomplete, 'place_changed', function() 
		{
			fillInAddress();
		});
	}
</script>

<hr class="featurette-divider">
<div class="container" >
	<div class="row">
	
		<div class="col-md-4">
		</div>				

		<div class="col-md-4">		
			<form class="form-signin" role="form" id="createListingForm">
				<h2 class="form-signin-heading">Create Listing</h2>
				
				<div class="form-group">
					<input id="startingLocation" type="text" class="form-control" placeholder="Starting Location" name="startingAddress" required autofocus autocomplete="off">
				</div>

				<div class="form-group" id="test">
					<input id="endingLocation" type="text" class="form-control" placeholder="Destination" name="destinationAddress" required autofocus autocomplete="off">
				</div>
				
				<script type="text/javascript">
					initialize();
				</script>
				<script type="text/javascript">
					initialize2();
				</script>
				
				<div class="form-group">
					<input type="radio" name="isRequest" value="1" onClick="disablePassengers()" checked> Looking for a Ride 
					<input type="radio" name="isRequest" value="0" onClick="enablePassengers()"> Hosting a Ride 			
				</div>
				
				<div class="form-group">
					<input id="passengersTextBox" type="text" class="form-control" placeholder="Number of Passengers" name="passengers" required autofocus disabled>
				</div>						
				
				<div class="form-group">
					<div class='input-group date' id='datetimepicker1'>
						<input id="dateTime" type='text' class="form-control" name="dateTime" placeholder="Desired Departure Date" data-format="YYYY-MM-DD hh:mm:ss" required autofocus>
						<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
						</span>
					</div>
				</div>

				<script type="text/javascript">
					$(function () {
						$('#datetimepicker1').datetimepicker();
					});
				</script>
			</form>
			
			
			<button class="btn btn-lg btn-primary btn-block" id="submitButton" onclick="createListing();">Submit</button>
		</div> <!-- col-md-4 -->
	</div> <!-- row -->
			<div id="progressCreate" class="waiting" style="visibility:hidden">
				<br />
				<img src="/images/bigload.gif"> 				
			</div>
</div> <!-- /container -->
