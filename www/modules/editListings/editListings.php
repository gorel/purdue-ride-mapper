<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.1/jquery.mobile-1.4.1.min.css">
<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="http://code.jquery.com/mobile/1.4.1/jquery.mobile-1.4.1.min.js"></script>
<hr class="featurette-divider">
<div class="container" >
	<div class="row">
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
		
<div data-role="page">
  <div data-role="header">
    <h1>Welcome To My Homepage</h1>
  </div>

  <div data-role="main" class="ui-content">
    <a href="#myPopupDialog" data-rel="popup" data-position-to="window" data-transition="fade" class="ui-btn ui-corner-all ui-shadow ui-btn-inline">Open Dialog Popup</a>

    <div data-role="popup" id="myPopupDialog">
      <div data-role="header">
        <h1>Header Text</h1>
      </div>

      <div data-role="main" class="ui-content">
        <h2>Welcome to my Popup Dialog!</h2>
        <p>jQuery Mobile is FUN!</p>
        <a href="#" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b ui-icon-back ui-btn-icon-left" data-rel="back">Go Back</a>
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

		</div>
	</div> <!-- row -->
</div> <!-- /container -->
