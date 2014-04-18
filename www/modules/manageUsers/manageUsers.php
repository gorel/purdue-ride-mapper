<?php session_start() ?>
<script type="text/javascript" src="/js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="/js/moment.min.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>


<hr class="featurette-divider">

	<div class="container" >


		<form class="form-inline" role="form">
			<div class="form-group">
				<input id='keyword_field' type="text" class="form-control" placeholder="Search..." required autofocus>
				<input type="radio" name="sex" value="fname">First Name<input type="radio" name="lname" value="female">Last Name<input type="radio" name="email" value="E-Mail" required checked>E-mail
			</div>
			<button type="submit" class="btn" id="search" disabled>Search</button>
		</form>

	<?php
		$user_id = $_SESSION['user'];

		if (!$_SESSION['isAdmin'])
		{
			// TODO: Need to find a better way to redirect
			echo "<script type='text/javascript'>
				$(\"#content\").load(\"/home.php\");
				document.getElementById('manageUsers').parentNode.className = \"inactive\";
				document.getElementById('home').parentNode.className = \"active\";
			     </script>";
			die();
		}

		// TODO: CHANGE CREDENTIALS BACK
		$conn = new mysqli("collegecarpool.us","root","collegecarpool","purdue_test");

		if($conn->connect_errno)
		{
			die("Failed to connect to MySQL: " . mysqli_connect_error());

		}

		$query = "SELECT " .
		         "user_id, email, first_name, verified, enabled, is_admin, last_name " .
		         "FROM users";

		$stmt = $conn->stmt_init();
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$stmt->bind_result($uid, $email, $fname, $verified, $enabled, $is_admin, $lname);

		echo "<table class='table table-striped' id='tbl_usr'>
		        <thead>
			  <tr>
			    <th> User ID </th>
			    <th> Email </th>
			    <th> First Name </th>
			    <th> Last Name </th>
			    <th> Verified </th>
			    <th> Enabled </th>
			    <th> Admin </th>
			  </tr>
			<thead>";

		echo "</tbody>";
		while ($stmt->fetch())
		{
			$verified_text = "No";
			$enabled_text = "No";
			$is_admin_text = "No";

			if ($verified)
				$verified_text = "Yes";
			if($enabled)
				$enabled_text = "Yes";
			if($is_admin)
				$is_admin_text = "Yes";

			echo "<tr id=usr_$uid>
			        <td id=$uid"."_uid> $uid </td>
			        <td id=$uid"."_email> $email </td>
			        <td id=$uid"."_fname> $fname </td>
			        <td id=$uid"."_lname> $lname </td>
			        <td id=$uid"."_verified_text> $verified_text </td>
			        <td id=$uid"."_enabled_text> $enabled_text </td>
			        <td id=$uid"."_is_admin_text> $is_admin_text </td>
				<td> <button class=\"btn btn-success btn-small\"
					onclick=\"editUser($uid)\">Edit</button>
				 <button class=\"btn btn-danger\"
					onclick=\"updateDelId($uid)\">Delete</button>
				<button class=\"btn btn-danger\"
					onclick=\"warnUserModal($uid)\">Send Warning</button></td>
			      </tr>";
		}
		echo "</tbody>";
		echo "</table>";

		$stmt->close();

	?>
</div> <!-- /container -->

<!-- User Management Functions -->

<script type="text/javascript">


  // Delete user

  function updateDelId(uid)
  {
    var elm = document.getElementById('toDelId');
    elm.value = uid;

    $('#modalDeleteUser').modal('show');
  }

  function delUser()
  {
    var elm = document.getElementById('toDelId');

    $.ajax ({
	type: "POST",
	url: "/modules/manageUsers/delUserProc.php",
	dataType: 'json',
	data: {"uid" : elm.value},
	success: function(data) {

	if (data.retval == "ERR") {
		alert("Database error: Could not delete the user");
	}
	$('#modalDeleteUser').modal('hide');
	$("#content").load("/modules/manageUsers/manageUsers.php");
           }
	});
  }

  // Populate values in edit modal

  function editUser(uid)
  {
    var txtFname = document.getElementById('txtFname');
    var txtLname = document.getElementById('txtLname');
    var txtEmail = document.getElementById('txtEmail');
    var radAdmin = document.getElementById('radAdmin');
    var radNoAdmin = document.getElementById('radNoAdmin');
    var radDisabled = document.getElementById('radDisabled');
    var radEnabled = document.getElementById('radEnabled');
    var txtUid = document.getElementById('txtUid');

    if (document.getElementById(uid + "_enabled_text").innerHTML.trim() == "Yes")
	radEnabled.checked = true;
    else
	radDisabled.checked = true;

    if (document.getElementById(uid + "_is_admin_text").innerHTML.trim() == "Yes")
        radAdmin.checked = true;
    else
	radNoAdmin.checked = true;

    txtFname.value = document.getElementById(uid + "_fname").innerHTML.trim();
    txtLname.value = document.getElementById(uid + "_lname").innerHTML.trim();
    txtEmail.value = document.getElementById(uid + "_email").innerHTML.trim();

    txtUid.value = uid;

    $("#modalEditUser").modal();
  }

  // Validate user fields

  function validate()
  {
    var txtFname = document.getElementById('txtFname');
    var txtLname = document.getElementById('txtLname');
    var txtEmail = document.getElementById('txtEmail');
    var radAdmin = document.getElementById('radAdmin');
    var radNoAdmin = document.getElementById('radNoAdmin');
    var radEnabled = document.getElementById('radEnabled');
    var radDisabled = document.getElementById('radDisabled');

    if ((txtFname.value.trim() == "" || txtLname.value.trim() == "" || txtEmail.value.trim() == ""))
    {
       alert("All fields must not be empty!");
       return;
    }

    var patt = /[A-Za-z0-9]+@([A-Za-z0-9]+\.[A-Za-z0-9])+/i

    if (! patt.test(txtEmail.value))
    {
       alert("Please enter a valid email address!");
       return;
    }

    var fname = txtFname.value.trim();
    var lname = txtLname.value.trim();
    var email = txtEmail.value.trim();
    var uid = txtUid.value;

    var admin;
    var enabled;

    if (radAdmin.checked)
	admin = radAdmin.value;
    else
	admin = radNoAdmin.value;

    if (radEnabled.checked)
	enabled = radEnabled.value;
    else
	enabled = radDisabled.value;

    $.ajax ({
	type: "POST",
	url: "/modules/manageUsers/editUserProc.php",
	dataType: 'json',
	data: {"fname" : fname, "lname" : lname, "email" : email, "admin" : admin, "enabled" : enabled, "uid" : uid},
	success: function(data) {

	if (data.retval == "ERR") {
		alert("Database error: Could not update user's details");
	}
	$('#modalEditUser').modal('hide');
	$("#content").load("/modules/manageUsers/manageUsers.php");
           }
    });
}

//Send a warning email to a user
function warnUserModal(uid)
{
    var elm = document.getElementById('toWarnId');
    elm.value = uid;

    $('#modalWarnUser').modal('show');
}

function warnUser()
{
	var message = document.getElementById('warnMessage').value;
	var from_uid = <?php echo $_SESSION['user']; ?>
	var to_uid = elm;
	if (message.length === 0)
	{
		alert("Please provide a non-empty message.");
		return;
	}
	console.log(message);
	console.log(from_uid);
	$.ajax ({
		type: "POST",
		url: "/modules/manageUsers/manageUsersContactProc.php",
		dataType: "json",
		beforeSend: function() {
			console.log("before send");
		},
		complete: fuction() {
			console.log("complete");
		},
		data: {"message" : message, "from_uid" : from_uid, "to_uid" : to_uid},
		success: function(data) {
			console.log("success");
			console.log(data.success);
			console.log(data.rcpt);
			console.log(data.from);
			console.log(data.to);
			if(data.success === "TRUE")
			{
				alert("User has been warned.");
				document.getElementById('warnMessage').value = "";
			}
			else
			{
				alert("Message failed to send.");
			}
	});
}


</script>

<!-- Modal to edit user-->

<div class="modal fade" id="modalEditUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Edit User</h4>
      </div>
      <div class="modal-body">
	<form id="editForm" action="/modules/manageUsers/editUserProc.php" method="POST">
        <b>First Name</b> <input type="text" class="form-control" name="fname" id="txtFname"><br>
        <b>Last Name</b>  <input type="text" class="form-control" name="lname" id="txtLname"><br>
        <b>Email</b>      <input type="text" class="form-control" name="email" id="txtEmail"><br>
	<b>Admin</b><br>  <input type="radio" name="admin" id="radAdmin" value="1"> Yes
	                  <input type="radio" name="admin" id="radNoAdmin"value="0"> No<br>
	<b>Enabled</b><br><input type="radio" name="enabled" id="radEnabled" value="1"> Yes
	                  <input type="radio" name="enabled" id="radDisabled" value="0"> No<br>
			  <input type="text" hidden="true" id="txtUid">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onClick="validate()">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal to delete user-->

<div class="modal fade" id="modalDeleteUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Delete User</h4>
      </div>
      <div class="modal-body">
	Are you sure you want to delete this user?
	<input type="text" hidden="true" id="toDelId">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" onClick="delUser()">Yes</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal to send a warning message to a user-->

<div class="modal fade" id="modalWarnUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Message User</h4>
      </div>
      <div class="modal-body">
      	<form id="warnForm" action="/modules/manageUsers/warnUserProc.php" method="POST">
		<b>Warning Message (What did the user do?)</b>
		<input type='textarea' class='form-control' name='message' id='warnMessage'><br>
	</form>
      </div>
      <div class="modal-footer">
      	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" onClick="warnUser()">Send Warning</button>
      </div>
    </div>
  </div>
</div>


