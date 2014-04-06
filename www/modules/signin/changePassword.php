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
echo "<input type=\"text\" name=\"$email\" id=\"txtEmail\" hidden=\"true\" value=\"$email\">";

?>



<script>
$('#content').load("/modules/signin/signin.php");
$('#modalResetPw').modal('show');
</script>


<script>

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
<!-- Modal to change password -->

<div class="modal fade" id="modalResetPw" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Reset Password</h4>
      </div>
      <div class="modal-body">
        Password: <input type="Password" id="password" class="form-control">
        Confirm Password:<input type="Password" id="confirm_password" class="form-control">
	
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onClick="validatePass()">Submit</button>
      </div>
    </div>
  </div>
</div>




