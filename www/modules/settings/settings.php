<!--
 Exclude these since index.php includes them

<script type="text/javascript" src="/js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="/js/moment.min.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">

  <link href="/css/bootstrap.css" rel="stylesheet"> 
-->

  <script type="text/javascript" src="/js/validation.js"></script>

  <style>
    .err { color:#FF0000; font-weight:bold; }
    .ok  { color:#397D02; font-weight:bold; }
  </style>

  <script>

  $('#tabs a').click(function (e) {
    e.preventDefault();
    $(this).tab('show');
  });
  

  function initFields()
  {
        hideAllPwMsg();
	resetPasswordTab();
        hideAllPrefMsg();
        fillPref();
        $('#modalSettings').modal('show');
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
  function hideAllPrefMsg()
  {
    $('#errPref').hide();
    $('#okPref').hide();
  }

  function enableAllPrefCntl()
  {
      $('#txtAltEmail').prop('disabled', false);
      $('#txtAltEmail').prop('disabled', false);
  }

  function disableAllPrefCntl()
  {
      $('#txtAltEmail').prop('disabled', true);
      $('#txtAltEmail').prop('disabled', true);
  }

  function fillPref()
  {
    var haveAltEmail = $('#default_have_alt_email').text();
    var altEmail = $('#default_alt_email').text();

    var txtAltEmail = $('#txtAltEmail');
    var cbAltEmail = $('#cbAltEmail');

    if (haveAltEmail == 1)
    {
      cbAltEmail.prop('checked', true);
      txtAltEmail.val(altEmail);
    }
    else
    {
      cbAltEmail.prop('checked', false);
      txtAltEmail.prop('disabled', true);
    }
  }

  function validatePref()
  {
    var txtAltEmail = $('#txtAltEmail');
    var cbAltEmail = $('#cbAltEmail');

    if (cbAltEmail.is(':checked'))
    {
      if (! validateAltMail(txtAltEmail.val()))
      {
        $('#errPref').text("Please enter a valid email address");
        $('#errPref').show();
        return false;
      }
    }

    return true;
  }

  function toggleAltEmail()
  {
    if ($('#cbAltEmail').prop('checked')) {
      $('#txtAltEmail').prop('disabled', false);
      $('#default_have_alt_email').text("1");
    } else {
      $('#txtAltEmail').prop('disabled', true);
      $('#default_have_alt_email').text("0");
    }
  }

  function savePref()
  {
    var txtAltEmail = $('#txtAltEmail');
    var cbAltEmail = $('#cbAltEmail');
    
    var defaultHaveAltEmail = $('#default_have_alt_email');
    var defaultAltEmail = $('#default_alt_email');

    hideAllPrefMsg();

    if (validatePref())
    {
      if ($('#cbAltEmail').is(':checked'))
        defaultAltEmail.text(txtAltEmail.val());
      else
        defaultAltEmail.text("");

      $.ajax ({
        type: "POST",
        url: "/modules/settings/savePref.php",
        dataType: "json",
        beforeSend: function() {
          disableAllPrefCntl();

        },
        data: {"have_alt_email" : defaultHaveAltEmail.text(), "alt_email" : defaultAltEmail.text(), "uid" : window.uid},
        success: function(data) {
          if (data.retval == "DB_ERR")
          {
            $('#errPref').text("An unexpected DB error occurred");
            $('#errPref').show();
          }
          else 
          {
            $('#okPref').text("Your preferences have been saved");
            $('#okPref').show();
  
          }  
          enableAllPrefCntl();

        } 
      });
    }
  }
  </script>

<!-- </head> -->


<hr class="featurette-divider">

<?php
			

  $uid = $_SESSION['user'];
  echo "<script> window.uid=$uid;</script>";

  // TODO: CHANGE CREDENTIALS BACK
  $conn = new mysqli("collegecarpool.us","root","collegecarpool","purdue_test");

  if($conn->connect_errno)
  {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
  }


  $query = "SELECT " .
           "first_name, last_name, email, have_alt_email, alt_email " .
           "FROM users where user_id = $uid";

  $stmt = $conn->stmt_init();
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $stmt->bind_result($fname, $lname, $email, $have_alt_email, $alt_email);
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
	          <li class='active'><a href='#account' data-toggle='tab'>Account Details</a></li>
	          <li><a href='#changepw' data-toggle='tab'>Change Password</a></li>
	          <li><a href='#preferences' data-toggle='tab'>Preferences</a></li>
	        </ul>
	
	        <div id='tabcontent' class='tab-content'>
	          <div class='tab-pane active' id='account'>
                    <form class ='form-horizontal'>
                      <label class='control-label' for ='txtFirstName'>First Name</label>
	              <input class='form-control' type='text' id='txtFirstName' disabled='true' value=$fname>
                      <label class='control-label' for ='txtLastName'>Last Name</label>
	              <input class='form-control' type='text' id='txtLastName' disabled='true' value=$lname>
                      <label class='control-label' for ='txtRegEmail'>Registered Email</label>
	              <input class='form-control' type='text' id='txtRegEmail' disabled='true' value=$email>
			<br>Please <a href=''>contact us</a> if you need to change the above details
                    </form>
	          </div>

	          <div class='tab-pane' id='preferences'>
                    <form class='form-horizontal'>
                      <input type='checkbox' id='cbAltEmail' onclick='toggleAltEmail()'>
			<label class='control-label' for='cbAltEmail'>I want to be contacted by users through an alternate email</label>
	                <input class='form-control' type='text' id='txtAltEmail'>
                        <label class='err' id='errPref' hidden='true'></label>
                        <label class='ok' id='okPref' hidden='true'></label>
			<label id='default_have_alt_email' hidden='true'>$have_alt_email</label>
			<label id='default_alt_email' hidden='true'>$alt_email</label>
                        <br>
                        <button class='btn btn-primary form-control' id='btnSavePref' onClick='savePref(); return false;'>Save Preferences</button>
                    </form>
                  </div>

	          <div class='tab-pane' id='changepw'>
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
      </div>
    </div>
  </div>
</div>"
?>

