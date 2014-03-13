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
						echo "<td> <button type=\"button\" class=\"btn btn-success\">Edit</button> </td>";
						echo "<td> <button type=\"button\" class=\"btn btn-danger\">Delete</button> </td>";
						echo "</tr>";
					}
				}
				echo "</table>";
			}
			mysqli_close($con);
		?>
		
		<!-- Button to trigger modal -->
		<a href="#myModal" role="button" class="btn" data-toggle="modal">Launch demo modal</a>
		 
		<!-- Modal -->
		<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabel">Modal header</h3>
		  </div>
		  <div class="modal-body">
			<p>One fine body…</p>
		  </div>
		  <div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			<button class="btn btn-primary">Save changes</button>
		  </div>
		</div>
</div> <!-- /container -->
