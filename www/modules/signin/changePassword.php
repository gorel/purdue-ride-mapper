<!-- Bootstrap core CSS -->
<link href="/css/bootstrap.css" rel="stylesheet">
<link href="/css/custom.css" rel="stylesheet">


<script type="text/javascript" src="/js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="/js/moment.min.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/validation.js"></script>


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
$stmt->fetch();

if ($stmt->num_rows <= 0) {
  die("Password reset link does not exist");
}

echo "<p id=\"email\" hidden=true>$email</p>";

?>

<!-- Form Name -->
<div class="container">
<p id="title"></>
</div>

<div class="container col-sm-4 col-sm-offset-4">
  <form class="form-horizontal" onSubmit="submitNewPw(); return false;">
    <div class="control-group">

      <!-- Password input-->
      <label class="control-label" for="password">Password</label>
      <input id="password" class="form-control" name="password" type="password" placeholder="" class="input-xlarge">

      <!-- Confirm Password input-->
      <label class="control-label" for="confirm_password">Confirm Password</label>
      <input id="confirm_password" class="form-control" name="confirm_password" type="password" placeholder="" class="input-xlarge">
      <div class="control-group"> 
        <label id='errNewPwMsg' class="err"></label>
        <label id='okNewPwMsg' class="ok"></label>
        <div id='progressNewPw' class="waiting">Updating password... <img src="/images/load.gif"></div>
      </div>
      <!-- Button -->
      <label class="control-label" for="submitPassword"></label>
      <button class="form-control btn btn-primary" id="submitPassword" name="submitPassword">Submit</button>
    </div>

  </form>
</div>


<script type="text/javascript">


$(document).ready(function () {
  var title = document.getElementById('title');
  var val = document.getElementById('email');
  title.innerHTML = "<h3><b>Password reset for " + val.innerHTML + "</b></h3><hr>";
  $('#progressNewPw').hide();
});

var time_start = 0;
var time_end = 0;

/**
 * submitNewPw
 * 
 * validate and submit new password
 */
function submitNewPw()
{
  hideAllNewPwMsg();

  var pass  = $('#password').val()
  var cpass = $('#confirm_password').val();
  var email = $('#email').text();

  if (! validatePass(pass)) {
    $('#errNewPwMsg').text("Password must be alphanumerical and at least 6 characters long");
    $('#errNewPwMsg').show();
    return;
  }

  if (pass != cpass)
  {
    $('#errNewPwMsg').text("Passwords do not match");
    $('#errNewPwMsg').show();
    return;
  }

  $.ajax ({
    type: "POST",
    url: "/modules/signin/changePasswordProc.php", 
    dataType: 'json',
    data: {"password" : pass, "email" : email}, 
    beforeSend: function() {
      $('#progressNewPw').show();
      disableAllNewPwCntl();

    },
    success: function(data) {
      if (data.retval == "ERR") {
        $('#errNewPwMsg').text("Database error");
        $('#errNewPwMsg').show();
        $('progressNewPw').hide();
        return;
      }

      $('#okNewPwMsg').text("Password updated successfully");
      $('#okNewPwMsg').show();
      $('#progressNewPw').show();
      time_start = new Date().getTime() / 1000;
      time_end = time_start + 5;
      setInterval(function() { countdown(); }, 1000);
    }

  });
}

/**
 * countdown
 *
 * dynamically change redirecting time
 */
function countdown()
{
 time_elapsed = Math.ceil(time_end - time_start);
 if (time_elapsed >= 0) 
 {
   console.log("time_elapsed");
   text = "You will be redirected to collegecarpool.us in..." + time_elapsed + " "; 
   $('#progressNewPw').text(text);
   $('#progressNewPw').append("<img src='/images/load.gif'>"); 
   time_start = new Date().getTime() / 1000;
 } 
 else
   window.location = "/index.php";
}

/**
 * hideAllNewPwMsg
 *
 * Hide all new password hints
 */
function hideAllNewPwMsg()
{
  $('#errNewPwMsg').hide();
  $('#okNewPwMsg').hide();
  $('#progressNewPw').hide();
}

/**
 * disableAllNewPwCntl
 *
 * Disable all controls for the page
 */
function disableAllNewPwCntl()
{
  $('#password').prop('disabled', true);
  $('#confirm_password').prop('disabled', true);
  $('#email').prop('disabled', true);
  $('#submitPassword').prop('disabled', true);
}
/**
 * enableAllNewPwCntl
 *
 * Enable all controls for page
 */
function enableAllNewPwCntl()
{
  $('#password').prop('disabled', false);
  $('#confirm_password').prop('disabled', false);
  $('#email').prop('disabled', false);
  $('#submitPassword').prop('disabled', false);
}


</script>


