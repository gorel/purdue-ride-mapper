<link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />
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
							<form action=\"index.php\" method=\"get\">
								<input type=\"hidden\" name=\"listings_id\" value=\"". $row['listings_id'] ."\">
								<button type=\"submit\" class=\"btn btn-success\" data-toggle=\"modal\" data-target=\"#myModal\">Edit</button>
							</form> 
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
	
	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Edit Listing <?php echo $_GET["listings_id"]; ?></h4>
				</div>
				<form action="modules/editListings/editListingsProc.php" method="post" onsubmit="return confirm('Are you sure you want to delete this listing?')">
					<div class="modal-body">
						<div class="form-group">
							<label>Starting Address</label>
							<input type="text" class="form-control" placeholder="Starting Location" name="startingAddress" required autofocus>
						</div>

						<div class="form-group" id="test">
							<label>Ending Address</label>
							<input type="text" class="form-control" placeholder="Destination" name="destinationAddress" required autofocus>
						</div>
						
						<div class="form-group">
							<input type="radio" name="isRequest" value="1" onClick="disablePassengers()" checked> Looking for a Ride 
							<input type="radio" name="isRequest" value="0" onClick="enablePassengers()"> Hosting a Ride 			
						</div>
						
						<div class="form-group">
							<label>Number of Passengers</label>
							<input id="passengersTextBox" type="text" class="form-control" placeholder="Number of Passengers" name="passengers" required autofocus disabled>
						</div>						
						
						<div class="form-group">
							<label>Date of Departure</label>
							<div class='input-group date' id='datetimepicker1'>								
								<input type='text' class="form-control" name="dateTime" placeholder="Desired Departure Date" data-format="YYYY-MM-DD hh:mm:ss"/>
								<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>
						</div>

						<script type="text/javascript">
							$(function () {
								$('#datetimepicker1').datetimepicker();
							});
						</script>

						
						<input type="hidden" name="listings_id" value="">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" type="submit">Save changes</button>
					</div>
				</form> 
			</div>
		</div>
	</div>

</div> <!-- /container -->
