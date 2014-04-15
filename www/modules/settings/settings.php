<script type="text/javascript" src="/js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="/js/moment.min.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/validate.js"></script>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">

  <!-- Bootstrap core CSS -->
  <link href="/css/bootstrap.css" rel="stylesheet">
  <style>
    .err { color:#FF0000; font-weight:bold; }
    .ok  { color:#397D02; font-weight:bold; }
  </style>

  <script>

  $('#tabs a').click(function (e) {
    e.preventDefault()
    $(this).tab('show')
  })

  $(document).ready(function() {	
    hideAllPwMsg();
  });

  function initFields()
  {
	resetPasswordTab();
        $('#modalSettings').modal('show')
  }

  /**
   * Change Password functions
   */

  function resetPasswordTab()
  {
    hideAllPwMsg();
    $('#txtCurrPw').val("");
    $('#txtNewPw').val("");
    $('#txtRetypeNewPw').val("");
  }

  function hideAllPwMsg()
  {
    $('#errCurrPw').hide();
    $('#errNewPw').hide();
    $('#errRetypeNewPw').hide();
    $('#okNewPw').hide();
  }

  function disableAllCntl()
  {
    $('#txtCurrPw').prop('disabled', true);
    $('#txtNewPw').prop('disabled', true);
    $('#txtRetypeNewPw').prop('disabled', true);
    $('#btnChangePw').prop('disabled', true);
  }

  function enableAllCntl()
  {
    $('#txtCurrPw').prop('disabled', false);
    $('#txtNewPw').prop('disabled', false);
    $('#txtRetypeNewPw').prop('disabled', false);
    $('#btnChangePw').prop('disabled', false);
  }

  function changePass()
  {
    hideAllPwMsg();

    // trim white spaces

    $('#txtCurrPw').val($.trim($('#txtCurrPw').val()));
    $('#txtNewPw').val($.trim($('#txtNewPw').val()));
    $('#txtRetypeNewPw').val($.trim($('#txtRetypeNewPw').val()));

    var currPw = $('#txtCurrPw').val();
    var newPw = $('#txtNewPw').val();
    var retypeNewPw = $('#txtRetypeNewPw').val();

    if (currPw.length == 0)
    {
      $('#errCurrPw').text("Please enter your current password");
      $('#errCurrPw').show();
      $('#txtCurrPw').focus();
      return;
    }

    if (newPw.length == 0)
    {
      $('#errNewPw').text("Please enter your new password");
      $('#errNewPw').show();
      $('#txtNewPw').focus();
      return;
    }

    if (newPw.length < 6)
    {
      $('#errNewPw').text("Please enter a minimum of 6 characters");
      $('#errNewPw').show();
      $('#txtNewPw').focus();
      return;
    }

    if (retypeNewPw.length == 0)
    {
      $('#errRetypeNewPw').text("Please retype your new password");
      $('#errRetypeNewPw').show();
      $('#txtRetypeNewPw').focus();
      return;

    }

    if (newPw != retypeNewPw)
    {
      $('#errRetypeNewPw').text("New password must match");
      $('#errRetypeNewPw').show();
      $('#txtRetypeNewPw').focus();
      return;
    }

    $.ajax ({
      type: "POST",
      url: "/modules/settings/changePassword.php",
      dataType: "json",
      beforeSend: function() {
        disableAllCntl();

      },
      complete: function() {
        enableAllCntl();

      },
      data: {"currPw" : currPw, "newPw" : newPw, "uid" : window.uid},
      success: function(data) {
        if (data.status == "AUTH_FAILED")
        {
	  $('#errCurrPw').text("Wrong Password. Please retype your current password");
          $('#errCurrPw').show(5,function() {$('#txtCurrPw').focus()});
        }
        else {
     	  resetPasswordTab();
          $('#okNewPw').text("Password updated successfully");
          $('#okNewPw').show();
        }

      } 
    });
  }

  /**
   * Preferences functions
   */
   
  </script>

</head>


<hr class="featurette-divider">

<button class="btn btn-success btn-small" onclick="initFields()">CLICK</button>
<button class="btn btn-success btn-small" onclick="initFields()">CLICK</button>
<a href="" onclick="initFields()">TEST</a>


<?php
  session_start();
			
  if (!isset($_SESSION['user'])) 
  {
  // TODO: Need to find a better way to redirect
  /*
    echo "<script type='text/javascript'>
            $(\"#content\").load(\"/home.php\");
            document.getElementById('manageUsers').parentNode.className = \"inactive\";
            document.getElementById('home').parentNode.className = \"active\";
          </script>";
	*/
    echo "Not logged in";
    die();
  }

  $uid = $_SESSION['user'];
  echo "<script> window.uid=$uid;</script>";

  // TODO: CHANGE CREDENTIALS BACK
  $conn = new mysqli("collegecarpool.us","root","collegecarpool","purdue_test");

  if($conn->connect_errno)
  {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
  }


  $query = "SELECT " .
           "first_name, last_name, email " .
           "FROM users where user_id = $uid";

  $stmt = $conn->stmt_init();
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $stmt->bind_result($fname, $lname, $email);
  $stmt->fetch();

  echo "<!-- Modal to edit user settings -->

        <div class='modal fade' id='modalSettings' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
          <div class='modal-dialog'>
            <div class='modal-content'>
              <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                  <h4 class='modal-title' id='myModalLabel'>My Settings</h4>
              </div>
              <div class='modal-body'>
                <ul class='nav nav-tabs' id='tabs'>
	          <li class='active'><a href='#changepw' data-toggle='tab'>Change Password</a></li>
	          <li><a href='#account' data-toggle='tab'>Account Details</a></li>
	          <li><a href='#preferences' data-toggle='tab'>Preferences</a></li>
	        </ul>
	
	        <div id='tabcontent' class='tab-content'>
	          <div class='tab-pane' id='account'>
                    <form class ='form-horizontal'>
                      <label class='control-label' for ='txtFirstName'>First Name</label>
	              <input class='form-control' type='text' id='txtFirstName' disabled='true' value=$fname>
                      <label class='control-label' for ='txtLastName'>Last Name</label>
	              <input class='form-control' type='text' id='txtLastName' disabled='true' value=$lname>
                      <label class='control-label' for ='txtRegEmail'>Registered Email</label>
	              <input class='form-control' type='text' id='txtRegEmail' disabled='true' value=$email>
                    </form>
	          </div>

	          <div class='tab-pane' id='preferences'>
                    <div class='form-group'>
                      <label class='col-lg-8' for ='cbPrefEmail'>I want users to contact me via another email</label>
                    </div>
                  </div>

	          <div class='tab-pane active' id='changepw'>
                    <form class='form-horizontal'>
	              <label class='control-label' for ='txtCurrPw'>Current Password</label>
	              <input class='form-control' type='password' id='txtCurrPw'>
                      <div class='err' id='errCurrPw'></div>
	              <label class='control-label' for ='txtNewPw'>New Password</label>
	              <input class='form-control' type='password' id='txtNewPw'>
                      <div class='err' id='errNewPw'></div>
	              <label class='control-label' for ='txtRetypeNewPw'>Retype New Password</label>
	              <input class='form-control' type='password' id='txtRetypeNewPw'>
                      <div class='err' id='errRetypeNewPw'></div>
                      <div class='ok' id='okNewPw'></div>
	              <br>
                      <button class='btn btn-primary form-control' id='btnChangePw', onClick='changePass(); return false;'>Change Password</button>
                    </form>
                  </div>
              </div>


            </div>
      <div class='modal-footer'>
        <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
        <button type='button' class='btn btn-primary' onClick=''>Save changes</button>
      </div>
    </div>
  </div>
</div>"














?>

