<hr class="featurette-divider">
<div id="contact" class="contact_page">
	<div class="container">
		<div class="col-md-4">
		</div>
		<div class="col-md-4">
			<form enctype="application/x-www-form-urlencoded" class="form-horizontal" action="/modules/contact/contactProc.php" method="POST"><div class="padded">
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
				<tr>
				<th> Email </th>
				<th> Date </th>
				<th> Reply </th>
				<th> Delete </th>
				</tr>
				<tr>
				<td> Estevan </td>
				<td> Today! </td>
				<td> button1 </td>
				<td> button2 </td>
				</tr>
				<tr>
				<td colspan="4">MSGGGGGG</td>
				</tr>
				<tr>
				<td> Just do it </td>
				<td> Yesterday! </td>
				<td> M1 </td>
				<td> M2 </td>
				</tr>
				<tr>
				<td colspan="4"> MSGGGGG11111122223333</td>
				</tr>
				<?php
					session_start();
					$user_id = $_SESSION['user'];

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

								// check if user exists
								$query = "SELECT ticket_message FROM tickets WHERE user_id like $user_id";
								$stmt = $conn->prepare($query);
								$stmt->bind_param('s', $email);
								$stmt->execute();
								$stmt->store_result();

								if ($stmt->num_rows > 0)
								{
								    echo  "User already exists";
								    die;
								}
								else
								{

								}
								$stmt->close();

							}
							else
							{

							// check if user exists
							$query = "SELECT ticket_id, ticket_date,  ticket_message, ticket_answer FROM tickets";
							$stmt = $conn->prepare($query);
							$stmt->execute();
							$stmt->store_result();

							$stmt->bind_result('dsss', $ticket_id, $ticket_date, $ticket_message, $ticket_answer);
							if ($stmt->num_rows > 0)
							{
								while($stmt->fetch())
								{
									echo "<tr>";
									echo "<td> " , $ticket_id , "</td>";
									echo "<td> " , $ticket_date , "</td>";
									echo "<td> Button1 </td>";
									echo "<td> Button2 </td>";
									echo "</tr>";
									echo "<tr>";
									echo "<td colspan=\"4\"> " , $ticket_message , "</td>";
									echo "</tr>";
									if($ticket_answer != '')
									{
										echo "<tr>";
										echo "<td colspan=\"4\"> " , $ticket_answer , "</td>";
										echo "</tr>";
									}
								}
							    echo  "User already exists";
							    die;
							}
							else
							{
								echo "There is no ticket!";
							}
							$stmt->close();
							}
						}
					}
				?>
			</table>
	</div>
</div>
