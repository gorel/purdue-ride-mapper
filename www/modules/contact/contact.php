<hr class="featurette-divider">
<script type="text/javascript">
function deleteTicket(ticketID)
{
	$.ajax ({
		type:"POST",
		url: "/modules/contact/deleteTicketProc.php",
		dataType: "json",
		data: 
		{
			"ticketID" : ticketID
		},
		beforeSend: function()
		{

		},
		complete: function()
		{

		},
		success: function(data)
		{
			// the important stuff happens here
			console.log("success");

			if(data.sucess == "SUCCESS")
			{
				console.log("Delete successful");
				alart("The ticket was sucessfully deleted.");
				var header = document.getElementById(ticket_id + '_Header');
				var body1 = document.getElementById(ticket_id + '_Body1');
				var body2 = document.getElementById(ticket_id + '_Body2');
				header.parentNode.removeChild(header);
				body1.parentNode.removeChild(body1);
				body2.parentNode.removeChild(body2);

			}
		}
	})
}
</script>
<div id="contact" class="contact_page">
	<div class="container">
		<div class="col-md-4">
		</div>
		<div class="col-md-4">
			<form enctype="application/x-www-form-urlencoded" class="form-horizontal" action="/modules/contact/contactCreateTicketProc.php" method="POST"><div class="padded">
				<h2 class="form-signin-heading">Contact Us</h2>
				<div class="control-group"><label for="category" class="control-label required">Category</label>
					<div class="controls">
						<select name="category" id="category" class="form-control">
						<option value="0">Making a Listing</option>
						<option value="1">Finding a Listing</option>
						<option value="2">Other</option>
						</select>
					</div>
				</div>

				<div class="control-group"><label for="text" class="control-label required">Message</label>
					<div class="controls">
						<textarea name="text" id="text" rows="6" class="form-control" cols="80"></textarea>
					</div>
				</div>
				<br>
				<div class="form-actions">
					<button class="btn btn-lg btn-primary btn-block" id="sendButton">Send</button>
				</div>
			</form>
		</div>
		</div>
		<div class="col-md-12">
		<hr class="featurette-divider">
				<h2> Tickets </h2>
			<table class='table table-triped table-bordered'>
				<?php
					session_start();

					if(!isset($_SESSION['user']))
					{
					}
					else
					{
						$conn = mysqli_connect("collegecarpool.us", "root", "collegecarpool", "purdue_test");

						if(mysqli_connect_errno())
						{
							echo "Failed to connect to MySQL: " . mysqli_connect_error();
						}
						else
						{
							$stmt = $conn->stmt_init();
							if(!$_SESSION['isAdmin'])
							{
								$user_id = $_SESSION['user'];

								$query = "SELECT * FROM tickets WHERE user_id like $user_id";
								$result = mysqli_query($conn, $query);

								while($row = mysqli_fetch_array($result))
								{
									echo "<tr>";
									echo "<th> Category </th>";
									echo "<th> Date </th>";
									echo "<th> </th>";
									echo "<th> </th>";
									echo "<tr>";
									if($row['category']==0)
									{
										echo "<td> Making a List </td>";
									}
									else if($row['category']==1)
									{
										echo "<td> Finding a List </td>";
									}
									else if($row['category']==2)
									{
										echo "<td> Others </td>";
									}
									echo "<td> " , $row['ticket_date'] , "</td>";
									echo "<td> Reply </td>";
									echo "<td> Resolved </td>";
									echo "</tr>";
									echo "<tr>";
									echo "<td colspan=\"4\"> " , $row['ticket_message'] , "</td>";
									echo "</tr>";
									if($row['ticket_answer'] != '')
									{
										echo "<tr>";
										echo "<td colspan=\"4\"> " , $row['ticket_answer'] , "</td>";
										echo "</tr>";
									}
								}

							}
							else
							{

							// check if user exists
								$query = "SELECT * FROM tickets";
								$result = mysqli_query($conn, $query);

								while($row = mysqli_fetch_array($result))
								{
									echo '<tr id="' . $row['ticket_id'] . '_Header>';
									echo "<th> Category </th>";
									echo "<th> Date </th>";
									echo "<th> </th>";
									echo "<th> </th>";
									echo '<tr id="' . $row['ticket_id']. '_Body1>';
									if($row['category']==0)
									{
										echo "<td> Making a List </td>";
									}
									else if($row['category']==1)
									{
										echo "<td> Finding a List </td>";
									}
									else if($row['category']==2)
									{
										echo "<td> Others </td>";
									}
									echo "<td> " , $row['ticket_date'] , "</td>";
									echo "<td> Reply </td>";
									echo '<td> <button type="button" onclick="deleteTicket('. $row['ticket_id'] .')">Delete</button> </td>';
									echo "</tr>";
									echo "<tr id=\"". $row['ticket_id']."_Body2>";
									echo "<td colspan=\"4\"> " , $row['ticket_message'] , "</td>";
									echo "</tr>";
									if($row['ticket_answer'] != '')
									{
										echo "<tr>";
										echo "<td colspan=\"4\"> " , $row['ticket_answer'] , "</td>";
										echo "</tr>";
									}
								}

							}
						}
					}
				?>
			</table>
	</div>
</div>
