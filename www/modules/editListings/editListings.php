<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>		
<script type="text/javascript" src="js/moment.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="http://code.jquery.com/mobile/1.4.1/jquery.mobile-1.4.1.min.js"></script>
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
			<th> StartingAddress </th>
			<th> EndingAddress </th>
			<th> Request? </th>
			<th> Passengers </th>
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
					echo "<td> <button class=\"btn btn-success\" data-toggle=\"modal\" data-target=\"#myModal\">Edit</button> </td>";
					echo "<td> 
							<form action=\"modules/editListings/editListingsProc.php\" method=\"post\" onsubmit=\"return confirm('You want to delete this listing?')\">
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
			<h4 class="modal-title" id="myModalLabel">Edit Listing</h4>
		  </div>
		  <div class="modal-body">
			...
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary">Save changes</button>
		  </div>
		</div>
	  </div>
	</div>

</div> <!-- /container -->
