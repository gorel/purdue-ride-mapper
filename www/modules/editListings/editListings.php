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
                                    <h4>Contact Tech Site</h4>
                                </div>
                                <div class = "modal-body">
                               
                                    <div class = "form-group">
                                       
                                        <label for = "contact-name" class = "col-lg-2 control-label">Name:</label>
                                        <div class = "col-lg-10">
                                           
                                            <input type = "text" class = "form-control" id = "contact-name" placeholder = "Full Name">
                                           
                                        </div>
                                       
                                    </div>
                                   
                                    <div class = "form-group">
                                       
                                        <label for = "contact-email" class = "col-lg-2 control-label">Email:</label>
                                        <div class = "col-lg-10">
                                           
                                            <input type = "email" class = "form-control" id = "contact-email" placeholder = "you@example.com">
                                           
                                        </div>
                                       
                                    </div>
                                   
                                    <div class = "form-group">
                                       
                                        <label for = "contact-msg" class = "col-lg-2 control-label">Message:</label>
                                        <div class = "col-lg-10">
                                           
                                            <textarea class = "form-control" rows = "8"></textarea>
                                           
                                        </div>
                                       
                                    </div>
                               
                                </div>
                                <div class = "modal-footer">
                            <a class = "btn btn-default" data-dismiss = "modal">Close</a>    
							<button class = "btn btn-primary" type = "submit">Send</button>
						</div>
					</form>
			</div>
		</div>
	</div>

</div> <!-- /container -->
