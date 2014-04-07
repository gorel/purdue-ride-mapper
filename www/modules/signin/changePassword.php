<!-- Bootstrap core CSS -->
<link href="/css/bootstrap.css" rel="stylesheet">

<script type="text/javascript" src="/js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="/js/moment.min.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>



<?php

//TODO CHANGE BACK
$conn = new mysqli("collegecarpool.us", "root", "collegecarpool", "purdue_test");


if ($conn->connect_errno)
{
        echo json_encode(array('retval' => 'ERR'));
        die;
}

$conn->stmt_init();

$link=$_GET['link'];

$query = "SELECT email,link FROM password_reset WHERE link like \"$link\"";
$stmt = $conn->prepare($query);
$stmt->bind_result($email,$link);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows <= 0) {
  die("Password reset link does not exist");
}
$stmt->fetch();

echo "<p id=\"email\" hidden=true>$email</p>";

?>


<div class="container">
	<form class="form-horizontal">
	<fieldset>

		<!-- Form Name -->
		<p id="title"></>

		<!-- Password input-->
		<div class="control-group">
	  		<label class="control-label" for="password">Password</label>
	  		<div class="controls">
	    			<input id="password" name="password" type="password" placeholder="" class="input-xlarge">
  	  		</div>
		</div>

		<!-- Password input-->
		<div class="control-group">
			<label class="control-label" for="confirm_password">Confirm Password</label>
  			<div class="controls">
    				<input id="confirm_password" name="confirm_password" type="password" placeholder="" class="input-xlarge">
  			</div>
		</div>

		<!-- Button -->
		<div class="control-group">
  			<label class="control-label" for="submitPassword"></label>
  			<div class="controls">
    				<button type="button" id="submitPassword" name="submitPassword" onClick="validatePass()" class="btn btn-primary">Submit</button>
  			</div>
		</div>

	</fieldset>
	</form>

</div>


<script type="text/javascript">

var title = document.getElementById('title');
var val = document.getElementById('email');
title.innerHTML = "<h3><b>Password reset for " + val.innerHTML + "</b></h3><hr>";

function validatePass()
{
	var pass = document.getElementById('password');
	var cpass = document.getElementById('confirm_password');
	var email = document.getElementById('txtEmail');

	if ((pass.value != cpass.value) || pass.value.trim() == "" || cpass.value.trim() =="") {
		alert("Passwords must match and must not be empty");
		return;
	}

        $.ajax ({
        type: "POST",
        url: "/modules/signin/changePasswordProc.php", 
        dataType: 'json',
        data: {"password" : pass.value, "email" : txtEmail.value}, 
        success: function(data) {

        if (data.retval == "ERR") {
                alert("Database error: Could not delete the user");
		return;
        }
		alert("Password was successfuly changed");
		window.location = "/index.php";
          }
        });
}


</script>


