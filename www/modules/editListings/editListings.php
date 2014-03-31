<link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />
<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>		
<script type="text/javascript" src="js/moment.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
<hr class="featurette-divider">
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
				if($_SESSION['user'] == $row["user_id"])
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
					echo "<td> 
							<button class=\"open-EditListingDialog btn btn-success\" data-id=\"". $row['listings_id'] ."\" data-toggle=\"modal\" data-target=\"#myModal\">Edit</button>	
						</td>";
					echo "<td> 
							<form action=\"modules/editListings/deleteListingsProc.php\" method=\"post\" onsubmit=\"return confirm('Are you sure you want to delete this listing?')\">
								<input type=\"hidden\" name=\"listings_id\" value=\"". $row['listings_id'] ."\">
								<button class=\"btn btn-danger\" type=\"submit\">Delete</button>
							</form> 
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
	
	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				  <form class = "form-horizontal" action="modules/editListings/editListingsProc.php" method="post">
						<div class = "modal-header">
							<div class = "col-lg-1" />
							<h4>Edit Listing</h4>
						</div>
						<div class = "modal-body">
							<input type="hidden" name="listingID" id="listingID" value=""/>								
							
							<div class="form-group">
								<div class = "col-lg-1" />
								<div class = "col-lg-10">
									<label>Starting Address</label>
									<input type="text" class="form-control" placeholder="Starting Location" name="startingAddress" required autofocus>
								</div>
							</div>
							   

							<div class="form-group" id="test">
								<div class = "col-lg-1" />
								<div class = "col-lg-10">
									<label>Ending Address</label>
									<input type="text" class="form-control" placeholder="Destination" name="destinationAddress" required autofocus>
								</div>
							</div>
							
							<div class="form-group">
								<div class = "col-lg-1" />
								<div class = "col-lg-10">
									<input type="radio" name="isRequest" value="1" onClick="disablePassengers()" checked> Looking for a Ride 
									<input type="radio" name="isRequest" value="0" onClick="enablePassengers()"> Hosting a Ride 			
								</div>
							</div>
							
							<div class="form-group">
								<div class = "col-lg-1" />
								<div class = "col-lg-10">
									<label>Number of Passengers</label>
									<input id="passengersTextBox" type="text" class="form-control" placeholder="Number of Passengers" name="passengers" required autofocus disabled>
								</div>
							</div>						
							
							<div class="form-group">
								<div class = "col-lg-1" />
								<div class = "col-lg-10">
									<label>Date of Departure</label>
									<div class='input-group date' id='datetimepicker1'>								
										<input type='text' class="form-control" name="dateTime" placeholder="Desired Departure Date" data-format="YYYY-MM-DD hh:mm:ss"/>
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
						<div class = "modal-footer">
                            <a class = "btn btn-default" data-dismiss = "modal">Close</a>    
							<button class = "btn btn-primary" type = "submit">Save Changes</button>
						</div>
					</form>
			</div>
		</div>
	</div>

</div> <!-- /container -->
