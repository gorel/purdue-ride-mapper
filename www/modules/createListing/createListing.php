<!-- Bootstrap core CSS -->
<link href="css/bootstrap.css" rel="stylesheet">


<!-- Custom styles for this template -->
<link href="signin.css" rel="stylesheet">

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

function checkForm(form)
{
	// regular expression to match required date format
	re = /^\d{1,2}\/\d{1,2}\/\d{4}$/;

	if(form.startdate.value != '' && !form.startdate.value.match(re)) 
	{
		alert("Invalid date format: " + form.startdate.value);
		form.startdate.focus();
		return false;
	}
	return true;
}

</script>
<script type="text/javascript">

function createListing()
{
	var startingLocation = document.getElementById('startingLocation').value;
	var destinationAddress = document.getElementById('endingLocation').value;
	var passengers = document.getElementById('passengersTextBox').value;
	var dateTime = document.getElementById('dateTime').value;
	var isRequest;
	
	console.log(startingLocation);
	console.log(destinationAddress);
	console.log(passengers);
	console.log(dateTime);
	return;
	
	
	$.ajax ({
		type: "POST",
		url: "/modules/createListing/createListingProc.php",
		dataType: "json",
		beforeSend: function() {
			console.log("before send");
		},
		complete: function() {
			console.log("complete");
		},
		data: {"startingAddress" : startingLocation, "destinationAddress" : destinationAddress, 
					"passengers" : passengers, "dateTime" : dateTime, "isRequest" : isRequest},
		success: function(data) {					
			console.log("success");	

			if(data.success == "SUCCESS")
			{
				console.log("Created Listing");
				alert("The listing was successfully created.");
			}
			else
			{
				alert("An error has occured. Please try again.");
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
			<form class="form-signin" role="form" id="createListingForm">
				<h2 class="form-signin-heading">Create Listing</h2>
				
				<div class="form-group">
					<input id="startingLocation" type="text" class="form-control" placeholder="Starting Location" name="startingAddress" required autofocus>
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
</div> <!-- /container -->
