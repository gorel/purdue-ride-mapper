<?php session_start();

$user_id = $_SESSION['user'];

if (!isset($user_id))
  die();

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
?>

<script type="text/javascript" src="/js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="/js/moment.min.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>

<!-- Bootstrap core CSS -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/justified-nav.css" rel="stylesheet">

<script>
  function doSearch()
  {
    var term = $('#txtSearch').val();
    var page = $('#txtPage').val();
    var by = $('#txtBy').val();
    //TODO term validaiton to prevent sql injection

    $.ajax ({
      type: "POST",
      url: "/modules/manageUsers/manageUsersSearch.php",
      dataType: 'json',
      data: {"by" : by, "term" : term ,"page" : page},
      beforeSend: function() {
          $('#tableusr').empty();

      },
      complete: function() {
        console.log("Complete");
      },
      success: function(data) {

	if (data.retval == "ERR") {
		alert("Database error: Could not delete the user");
        }
     
        for (var i = 0; i < data.results.length; i++) {
          var usr = jQuery.parseJSON(data.results[i]);

          var verified_text = usr.verified ? "Yes" : "No";
          var enabled_text  = usr.enabled  ? "Yes" : "No";
          var is_admin_text = usr.is_admin ? "Yes" : "No";

          var markup = "<tr id=usr_" + usr.user_id + ">"             +
                         "<td id=" + usr.user_id + "_uid>"           + usr.user_id    + "</td>" +
                         "<td id=" + usr.user_id + "_email>"         + usr.email      + "</td>" +
                         "<td id=" + usr.user_id + "_fname>"         + usr.first_name + "</td>" +
                         "<td id=" + usr.user_id + "_lname>"         + usr.last_name  + "</td>" +
                         "<td id=" + usr.user_id + "_verified_text>" + verified_text  + "</td>" +
                         "<td id=" + usr.user_id + "_enabled_text>"  + enabled_text   + "</td>" +
                         "<td id=" + usr.user_id + "_is_admin_text>" + is_admin_text  + "</td>" +

                         "<td><button class='btn btn-success btn-small' onclick='editUser(" + usr.user_id + ")'>"  +
                                 "Edit</button>"    + "     " +
                             "<button class='btn btn-warning' onclick='warnUserModal(" + usr.user_id + ")'>" +
                                 "Send Warning</button>" + "     " +
		             "<button class='btn btn-danger' onclick='updateDelId(" + usr.user_id + ")'>" +
                                 "Delete</button>"          +     "      " +
                         "</td>" +
                       "</tr>";

          $("#tableusr").append(markup);
        }
      }
    });
  }

</script>

<hr class="featurette-divider">

	<div class="container" >

<!--          <form action="/modules/manageUsers/manageUsersSearch.php" method="POST"> -->
            <input type="text" class="form-control" name="term" id="txtSearch" value="a">
            <input type="text" class="form-control" name="by" id="txtBy" value="first_name">
            <input type="text" class="form-control" name="page" id="txtPage" value="0">
            <button class="form-control btn-primary" id="btnSearch" onclick="doSearch()">Search</button>
<!--            <button class="form-control btn-primary" id="btnSearch" onclick="doSearch()">Search</button> -->
	<?php

	        echo "<div class='table-responsive'>";
		echo   "<table class='table table-hover table-striped table-condensed'>
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
			  </thead>";
		echo      "<tbody id='tableusr'>";
		echo      "</tbody>";
		echo   "</table>";
                echo "</div>";

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

        if (data.retval == "ERR") 
        {
	  alert("Database error: Could not delete the user");
	}
	$('#modalDeleteUser').modal('hide');
        doSearch();
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

        if (data.retval == "ERR") 
        {
	  alert("Database error: Could not update user's details");
	}
	$('#modalEditUser').modal('hide');
        doSearch();

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
	var from_uid = <?php echo $_SESSION['user']; ?>;
	var to_uid = document.getElementById('toWarnId').value;
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
		complete: function() {
			console.log("complete");
		},
		data: {"message" : message, "from_uid" : from_uid, "to_uid" : to_uid},
		success: function(data) {
			console.log("send mail success");
			$('#modalWarnUser').modal('hide');
			if(data.success === "TRUE")
			{
				alert("User has been warned.");
				document.getElementById('warnMessage').value = "";
			}
			else
			{
				alert("Message failed to send.");
			}
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
        <h4 class="modal-title" id="myModalLabel">Warn User for Behavior</h4>
      </div>
      <div class="modal-body">
      	<input type="text" hidden="true" id="toWarnId">
      	<form id="warnForm" onSubmit="JavaScript:warnUser(); return false;" method="POST">
		<b>Input Your Warning Message (What did the user do?)</b>
		<input type='textarea' class='form-control' name='message' id='warnMessage' ><br>
	</form>
      </div>
      <div class="modal-footer">
      	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" onClick="warnUser()">Send Warning</button>
      </div>
    </div>
  </div>
</div>


