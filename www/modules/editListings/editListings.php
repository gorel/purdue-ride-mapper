<link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />
<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="js/moment.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
<hr class="featurette-divider">

<!-- Edit Listings Modal -->
<div class="modal fade" id="editListingsModal" tabindex="-1" role="dialog" aria-labelledby="editListingsModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form class = "form-horizontal">
				<div class = "modal-header">
					<div class = "col-lg-1" />
					<h3>Edit Listing</h3>
				</div>
				<div class = "modal-body">
					<input type="hidden" name="listingID" id="listingID" value=""/>

					<div class="form-group">
						<div class = "col-lg-1" />
						<div class = "col-lg-10">
							<label>Starting Address</label>
							<input type="text" class="form-control" placeholder="Starting Location" name="startingAddressModal" id="startingAddressModal"required autofocus>
						</div>
					</div>


					<div class="form-group" id="test">
						<div class = "col-lg-1" />
						<div class = "col-lg-10">
							<label>Ending Address</label>
							<input type="text" class="form-control" placeholder="Destination" name="endingAddressModal"  id="endingAddressModal" required autofocus>
						</div>
					</div>

					<div class="form-group">
						<div class = "col-lg-1" />
						<div class = "col-lg-10">
							<input type="radio" name="isRequest" id="requestRadio" value="1" onClick="disablePassengers()" checked> Looking for a Ride
							<input type="radio" name="isRequest" id="offerRadio" value="0" onClick="enablePassengers()"> Hosting a Ride
						</div>
					</div>

					<div class="form-group">
						<div class = "col-lg-1" />
						<div class = "col-lg-10">
							<label>Number of Passengers</label>
							<input type="text" class="form-control" placeholder="Number of Passengers" name="numberOfPassengersModal" id="numberOfPassengersModal" required autofocus disabled>
						</div>
					</div>

					<div class="form-group">
						<div class = "col-lg-1" />
						<div class = "col-lg-10">
							<label>Date of Departure</label>
							<div class='input-group date' id='datetimepicker1'>
								<input type='text' class="form-control" name="dateOfDepartureModal" id="dateOfDepartureModal" placeholder="Desired Departure Date" data-format="YYYY-MM-DD hh:mm:ss"/>
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>
						</div>
					</div>

					<script type="text/javascript">
						$(function () {
							$('#datetimepicker1').datetimepicker();
						});
					</script>
				</div>
			</form>
			<div class = "modal-footer">
				<a class = "btn btn-default" data-dismiss="modal">Close</a>
				<button class = "btn btn-primary" id="saveButton">Save Changes</button>
			</div>
		</div>
	</div>
</div>

<div class="container" >
	<?php
		$con=mysqli_connect("localhost","collegecarpool","collegecarpool","purdue_test");

		if(mysqli_connect_errno())
		{
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		else
		{
			session_start();
			$user_id = $_SESSION['user'];

			$sql = "SELECT * FROM listings";
			$result = mysqli_query($con,$sql);
			echo "<table class='table table-striped'>
			<thead>
			<tr>
			<th> Starting Address </th>
			<th> Ending Address </th>
			<th> Hosting </th>
			<th> Number of Passengers </th>
			<th> Date of Departure </th>
			</tr>
			</thead>";
			while($row = mysqli_fetch_array($result))
			{
				if($_SESSION['user'] == $row["user_id"] || $_SESSION['isAdmin']==1)
				{
					echo "<tr>";
					echo '<td id="'.$row['listings_id'].'_Starting_Address">'. $row['startingAddress'] . '</td>';
					echo '<td id="'.$row['listings_id'].'_Ending_Address">'. $row["endingAddress"] . '</td>';
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

					echo "<td>
							<button class=\"open-EditListingDialog btn btn-success\" data-id=\"". $row['listings_id'] ."\" onclick=\"showEditListingModal(".$row['listings_id'].")\">Edit</button>
						</td>";
					echo "<td>
							<button class=\"btn btn-danger\" onclick=\"deleteListing(".$row['listings_id'].")\">Delete</button>
						</td>";

					echo "</tr>";
				}
			}
			echo "</table>";
		}
		mysqli_close($con);
	?>

	<script type="text/javascript">
		$(document).on("click", ".open-EditListingDialog", function () {
			 var listingID = $(this).data('id');
			 $(".modal-body #listingID").val( listingID );
		});
	</script>
	
	<script>
		var listingID;
		
		function deleteListing(listing_ID)
		{
			listingID = listing_ID;
			console.log("deleting");
			$.ajax ({
				type: "POST",
				url: "/modules/editListings/deleteListingsProc.php",
				dataType: "json",
				beforeSend: function() {
					console.log("before send");
				},
				complete: function() {
					console.log("complete");
				},
				data: {"listingID" : listingID},
				success: function(data) {					
					console.log("success");	

					if(data.success == "SUCCESS")
					{
						console.log("Delete successful");
					}
					else
					{
						alert("An error has occured. Please try again.");
						console.log("Delete failed");
					}
				} 
			});
		}
		
		function showEditListingModal(listing_ID)
		{
			listingID = listing_ID;
			
			var startingAddressModal = document.getElementsByName('startingAddressModal')[0];
			var endingAddressModal = document.getElementsByName('endingAddressModal')[0];
			var dateOfDepartureModal = document.getElementsByName('dateOfDepartureModal')[0];
			var numberOfPassengersModal = document.getElementsByName('numberOfPassengersModal')[0];
			var requestRadio = document.getElementById('requestRadio');
			var offerRadio = document.getElementById('offerRadio');
			
			startingAddressModal.value = document.getElementById(listingID + "_Starting_Address").innerHTML.trim();
			endingAddressModal.value = document.getElementById(listingID + "_Ending_Address").innerHTML.trim();
			dateOfDepartureModal.value = document.getElementById(listingID + "_Date_Of_Departure").innerHTML.trim();
			numberOfPassengersModal.value = document.getElementById(listingID + "_Passengers").innerHTML.trim();
			
			if(document.getElementById(listingID + "_Ride_Type").innerHTML.trim() == "Requesting Ride")
			{
				requestRadio.click();
				numberOfPassengersModal.disabled = true;
			}
			else
			{
				offerRadio.click();
				numberOfPassengersModal.disabled = false;
			}
				
			$('#editListingsModal').modal('show');
		}
		
		$('#saveButton').on('click', function()
		{				
			saveListing();
		});
		
		function disableAllCntl()
		{
			$('#startingAddressModal').prop('disabled', true);
			$('#endingAddressModal').prop('disabled', true);
			$('#dateOfDepartureModal').prop('disabled', true);
			$('#numberOfPassengersModal').prop('disabled', true);
			$('#requestRadio').prop('disabled', true);
			$('#offerRadio').prop('disabled', true);
		}

		function enableAllCntl()
		{
			$('#startingAddressModal').prop('disabled', false);
			$('#endingAddressModal').prop('disabled', false);
			$('#dateOfDepartureModal').prop('disabled', false);
			$('#numberOfPassengersModal').prop('disabled', false);
			$('#requestRadio').prop('disabled', false);
			$('#offerRadio').prop('disabled', false);
		}
				
		function saveListing()
		{
			var startingAddressModal = document.getElementsByName('startingAddressModal')[0];
			var endingAddressModal = document.getElementsByName('endingAddressModal')[0];
			var dateOfDepartureModal = document.getElementsByName('dateOfDepartureModal')[0];
			var numberOfPassengersModal = document.getElementsByName('numberOfPassengersModal')[0];
			
			console.log(listingID);
			console.log(startingAddressModal.value);
			console.log(endingAddressModal.value);
			console.log(dateOfDepartureModal.value);
			console.log(numberOfPassengersModal.value);
			var isRequest = 0;
			
			if(numberOfPassengersModal.disabled)
			{
				isRequest = 1;
			}
			else
			{
				isRequest = 0;
			}
			
			console.log(isRequest);
			disableAllCntl();
			$.ajax ({
				type: "POST",
				url: "/modules/editListings/editListingsProc.php",
				dataType: "json",
				beforeSend: function() {
					console.log("before send");
				},
				complete: function() {
					console.log("complete");
				},
				data: {"listingID" : listingID, "startingAddress" : startingAddressModal.value, "destinationAddress" : endingAddressModal.value,
						"passengers" : numberOfPassengersModal.value, "dateTime" : dateOfDepartureModal.value, "isRequest" : isRequest},
				success: function(data) {					
					console.log("success");	
					if(data.success == "SUCCESS")
					{
						console.log("Edit successful");

						document.getElementById(listingID + "_Starting_Address").innerHTML = startingAddressModal.value;
						document.getElementById(listingID + "_Ending_Address").innerHTML = endingAddressModal.value;
						document.getElementById(listingID + "_Date_Of_Departure").innerHTML = dateOfDepartureModal.value;
						
						if(isRequest)
						{
							document.getElementById(listingID + "_Passengers").innerHTML = "0";
							document.getElementById(listingID + "_Ride_Type").innerHTML = "Requesting Ride";
						}
						else
						{
							document.getElementById(listingID + "_Passengers").innerHTML = numberOfPassengersModal.value;
							document.getElementById(listingID + "_Ride_Type").innerHTML = "Offering Ride";
						}						
						
						$('#editListingsModal').modal('hide');
					}
					else
					{
						alert("An error has occured. Please try again.");
						console.log("Edit failed");
					}
					enableAllCntl();
				} 
			});
		}
		
		function disablePassengers()
		{
			var passengers = document.getElementsByName('numberOfPassengersModal')[0];
			passengers.disabled = true;
		}
		
		function enablePassengers()
		{
			var passengers = document.getElementsByName('numberOfPassengersModal')[0];
			passengers.disabled = false;
		}
	</script>
</div> <!-- /container -->
