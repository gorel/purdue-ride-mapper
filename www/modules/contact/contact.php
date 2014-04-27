<?php 
session_start();
?>
<hr class="featurette-divider">
<div class="modal fade" id="modalReply" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Reply to ticket</h4>
      </div>
      <div class="modal-body">
      	<input type="text" hidden="true" id="replyID">
      	<form id="replyForm" onSubmit="" method="POST">
		<b>Type reply</b>
		<input type='textarea' class='form-control' name='message' id='replyMessage' ><br>
                <label class="err" id="errWarnMsg" hidden='true'></label>
                <label class="ok" id="okWarnMsg" hidden='true'></label>
	</form>
      </div>
      <div class="modal-footer">
      	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" onClick="replyTicket()">Reply Ticket</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
	function replyModal(ticket_id)
	{
		var tid = document.getElementById('replyID');
		tid.value = ticket_id;
		$('#modalReply').modal('show');
	}
	function replyTicket()
	{
		var ticket_id = document.getElementById('replyID').value;
		var ticket_answer = document.getElementById('replyMessage').value;
		$.ajax ({
			type:"POST",
			url: "/modules/contact/replyTicketProc.php",
			datatype: "json",
			data:
			{
				"ticket_id" : ticket_id,
				"ticket_answer" : ticket_answer
			},
			success: function(data)
			{

				console.log("success");

				if(data.success == "SUCCESS")
				{
					console.log("Reply added successfully");
				}
				else if(data.success == "FAILURE2")
				{
					console.log("Error updating");
				}
				else if(data.success == "FAILURE1")
				{
					console.log("Error getting to MySQL");
				}
				else 
				{
					console.log(data.success);
				}
				//$('#modalReply').hide();
			}
		});
	}
	function deleteTicket(ticket_id)
	{
		$.ajax ({
			type:"POST",
			url: "/modules/contact/deleteTicketProc.php",
			dataType: "json",
			data: 
			{
				"ticket_id" : ticket_id
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

				if(data.success == "SUCCESS")
				{
					console.log("Delete successful");
					alert("The ticket was sucessfully deleted.");
					var header = document.getElementById(ticket_id + '_Header');
					var body1 = document.getElementById(ticket_id + '_Body1');
					var body2 = document.getElementById(ticket_id + '_Body2');
					header.parentNode.removeChild(header);
					body1.parentNode.removeChild(body1);
					body2.parentNode.removeChild(body2);

				}
			}
		});
	}
	function createTicket(ticket_id)
	{
		$.ajax ({
			type:"POST",
			url: "/modules/contact/createTicket.php",
			dataType: "json",
			data: 
			{
				// set variable here
				"ticket_id" : ticket_id
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

				if(data.success == "SUCCESS")
				{
					// stuff happen here

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
									echo '<tr id="' . $row['ticket_id'] . '_Header">';
									echo "<th> Category </th>";
									echo "<th> Date </th>";
									echo "<th> </th>";
									echo "<th> </th>";
									echo '<tr id="' . $row['ticket_id']. '_Body1">';
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
									echo '<td> <button type="button" onclick="replyModal('. $row['ticket_id'] .')">Reply</button> </td>';
									echo '<td> <button type="button" onclick="deleteTicket('. $row['ticket_id'] .')">Delete</button> </td>';
									echo "</tr>";
									echo '<tr id="' . $row['ticket_id'] . '_Body2">';
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
