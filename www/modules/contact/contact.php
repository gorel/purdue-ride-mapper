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
		<div class="col-md-4">
			<table class='table table-triped'>
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
				<?php
					session_start();
					if(!$_SESSION['isAdmin'])
					{
						$cat   = $_POST["category"];
						$msg   = $_POST["text"];
						$user_id = $_SESSION['user_id'];

						$dbName   = 'purdue_test';

						// connect to local db

						$conn =  new mysqli($dbHost, $dbUser, $dbPass, $dbName);

						if ($conn->connect_errn)
						{
						    echo  $conn->connect_errno . " " . $conn->connect_error;
						    die;
						}
						$stmt = $conn->stmt_init();

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
				?>
			</table>
		</div>
	</div>
</div>
