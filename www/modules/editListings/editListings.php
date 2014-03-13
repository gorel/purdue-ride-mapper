<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="http://code.jquery.com/mobile/1.4.1/jquery.mobile-1.4.1.min.js"></script>
<hr class="featurette-divider">
<div class="container" >
	<div data-role="page">
	  <div data-role="main" class="ui-content">
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


    <a href="#myPopupDialog" data-rel="popup" data-position-to="window" data-transition="fade" class="btn btn-danger">Open Dialog Popup</a>

    <div data-role="popup" id="myPopupDialog">
      <div data-role="header">
        <h1>Header Text</h1>
      </div>

      <div data-role="main" class="ui-content">
        <h2>Welcome to my Popup Dialog!</h2>
        <p>jQuery Mobile is FUN!</p>
        <a href="#" class="btn btn-danger" data-rel="back">Go Back</a>
      </div>

      <div data-role="footer">
        <h1>Footer Text</h1>
      </div>
    </div>
  </div>

  <div data-role="footer">
    <h1>Footer Text</h1>
  </div>
</div> 
</div> <!-- /container -->
