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
			else if(data.success == "FAILURE1")
			{
				alert("Could not connect to the database. Please try again soon, or contact us.");
			}
			else if(data.success == "FAILURE2")
			{
				alert("You must fill out all required fields.");
			}
			else if(data.success == "FAILURE3")
			{
				alert("Starting address is not valid. Please try again or be more specific.");
			}
			else if(data.success == "FAILURE4")
			{
				alert("Destination address is not valid. Please try again or be more specific.");
			}
			else if(data.success == "FAILURE5")
			{
				alert("The time of departure does not match the required format.\nPlease use the calendar tool.");
			}
			else if(data.success == "FAILURE6")
			{
				alert("You cannot enter a past date or a date over a year in the future.");
			}
			else if(data.success == "FAILURE7")
			{
				alert("Database entry failed. Please contact an administrator.");
			}
			else
			{
				alert("There was an inexplicable error. This should never happen.");
			}
		} 
	});
}

</script>
<hr class="featurette-divider">
<div class="container" >
	<div class="row">
	
		<div class="col-md-4">
		</div>				

		<div class="col-md-4">		
			<form class="form-signin" role="form" id="createListingForm" action="javascript:void(0);">
				<h2 class="form-signin-heading">Create Listing</h2>
				
				<div class="form-group">
					<input id="startingLocation" type="text" class="form-control" placeholder="Starting Location" name="startingAddress">
		
				</div>

				<div class="form-group" id="test">
					<input id="endingLocation" type="text" class="form-control" placeholder="Destination" name="destinationAddress" required autofocus>
				</div>
				
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

<script type="text/javascript">

	$( document ).ready(function() 
	{
		console.log("Ready");		
		
		var input = document.getElementById('startingLocation');
		var autocomplete = new google.maps.places.Autocomplete(input);
		
		google.maps.event.addListener(autocomplete, 'place_changed', function() {
			console.log("fired listener");
		});
	});
/*
	//Autocomplete variables
	var input = document.getElementById('startingLocation');
	var place;
	var autocomplete = new google.maps.places.Autocomplete(input);
 
	//Google Map variables
	var map;
	var marker;
 
	//Add listener to detect autocomplete selection
	google.maps.event.addListener(autocomplete, 'place_changed', function () 
	{
		place = autocomplete.getPlace();
		//console.log(place);
	});
 	 
	//Reset the inpout box on click
	input.addEventListener('click', function()
	{
		input.value = "";
	});
 
	function initialize() 
	{
		var myLatlng = new google.maps.LatLng(51.517503,-0.133896);
		var mapOptions = 
		{
			zoom: 1,
			center: myLatlng
		}
		map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
 
		marker = new google.maps.Marker({
			position: myLatlng,
			map: map,
			title: 'Main map'
		});
	}
 
	google.maps.event.addDomListener(window, 'load', initialize); */
</script>
