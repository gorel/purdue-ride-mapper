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

echo "<script> window.myid = $user_id; </script>";
?>

<script type="text/javascript" src="/js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="/js/moment.min.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>

<!-- Bootstrap core CSS -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/justified-nav.css" rel="stylesheet">
<link href="css/custom.css" rel="stylesheet">

<script>
</script>

<style>
</style>

<hr class="featurette-divider">

<div class="container col-sm-12">
<form>
  <div class="col-sm-3">
    <div class="btn-group">
        <button type="button" class="btn btn-default active" id="btnFirstName" onclick="changeSearchType(this)">First Name</button>
        <button type="button" class="btn btn-default" id="btnLastName" onclick="changeSearchType(this)">Last Name</button>
        <button type="button" class="btn btn-default" id="btnEmail" onclick="changeSearchType(this)">Email</button>
    </div>
  </div>

  <div class="col-sm-5">
    <div class="input-group">
      <input type="text" class="form-control" name="term" id="txtSearch" value="">
      <span class="input-group-btn">
        <button class="btn btn-primary" id="btnSearch", onclick ="doSearch(); return false;">Search</button>
      </span>
    </div>
  </div>

  <div id="progressSearch" class="col-sm-1">
    <img src="/images/bigload.gif"> 
  </div>
</form>
</div>

<div class="container">
  <label name="by" id="lblBy" hidden='true'>first_name</label>
  <label name="page" id="lblPage" hidden='true'>0</label>
</div>

<hr class="featurette-divider">
<div id="searchinfo" class="h4"></div>

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
	        <th> Warnings </th>
	        <th> Admin </th>
	        </tr>
	  </thead>";
echo     "<tbody id='tableusr'>";
echo     "</tbody>";
echo   "</table>";
 echo "</div>";

?>

</div> <!-- /container -->
<div id="pagination_controls"><?php echo $paginationCtrls; ?></div>
<!-- User Management Functions -->

<script type="text/javascript">
   $(document).ready(function () {
     $('#progressSearch').hide();
     doSearch();
   });
  
  // disable all controls

  function disableAllSearchCntl() 
  {
    $('#btnFirstName').prop('disabled', true);
    $('#btnLastName').prop('disabled', true);
    $('#btnEmail').prop('disabled', true);
    $('#btnSearch').prop('disabled', true);
    $('#txtSearch').prop('disabled', true);
  }

  // disable all controls

  function enableAllSearchCntl() 
  {
    $('#btnFirstName').prop('disabled', false);
    $('#btnLastName').prop('disabled', false);
    $('#btnEmail').prop('disabled', false);
    $('#btnSearch').prop('disabled', false);
    $('#txtSearch').prop('disabled', false);
  }

  // search by different field

  function changeSearchType(btn)
  {
    $('#btnFirstName').removeClass('active');
    $('#btnLastName').removeClass('active');
    $('#btnEmail').removeClass('active');
    
    if (btn.id == "btnFirstName")
    {
      $('#lblBy').text("first_name");  
      $('#btnFirstName').toggleClass('active');
    }
    else if (btn.id == "btnLastName")
    {
      $('#lblBy').text("last_name");  
      $('#btnLastName').toggleClass('active');

    }
    else if (btn.id == "btnEmail")
    {
      $('#lblBy').text("email");  
      $('#btnEmail').toggleClass('active');
    }
  }
  
  // search for users

  function doSearch()
  {
    var term = $('#txtSearch').val().trim();
    var page = $('#lblPage').text();
    var by = $('#lblBy').text();

    // Get rid of annoying autocomplete dropdown

    $('#txtSearch').blur();

    $.ajax ({
      type: "POST",
      url: "/modules/manageUsers/manageUsersSearch.php",
      dataType: 'json',
      data: {"by" : by, "term" : term ,"page" : page},
      beforeSend: function() {
        disableAllSearchCntl();
        $('#btnSearch').text("Searching...");
        $('#progressSearch').show();

      },
      complete: function() {
        enableAllSearchCntl();
        $('#btnSearch').text("Search");
        $('#progressSearch').hide();

      },
      success: function(data) {

	if (data.retval == "ERR") {
		alert("Database error: Could not delete the user");
        }
     
        $('#tableusr').empty();

        for (var i = 0; i < data.results.length; i++) {
          var usr = jQuery.parseJSON(data.results[i]);

          var verified_text = usr.verified ? "Yes" : "No";
          var enabled_text  = usr.enabled  ? "Yes" : "No";
          var is_admin_text = usr.is_admin ? "Yes" : "No";

          var markup = "<tr id=usr_" + usr.user_id + ">"                       +
                         "<td id=" + usr.user_id + "_uid>"                     + usr.user_id    + "</td>" +
                         "<td id=" + usr.user_id + "_email>"                   + usr.email      + "</td>" +
                         "<td id=" + usr.user_id + "_fname>"                   + usr.first_name + "</td>" +
                         "<td id=" + usr.user_id + "_lname>"                   + usr.last_name  + "</td>" +
                         "<td id=" + usr.user_id + "_verified_text>"           + verified_text  + "</td>" +
                         "<td id=" + usr.user_id + "_enabled_text>"            + enabled_text   + "</td>" +
                         "<td id=" + usr.user_id + "_warnings>"                + usr.warned     + "</td>" +
                         "<td id=" + usr.user_id + "_is_admin_text>"           + is_admin_text  + "</td>" +
                         "<td id=" + usr.user_id + "_phone hidden='true'>"     + usr.phone  + "</td>" +
                         "<td id=" + usr.user_id + "_alt_email hidden='true'>" + usr.alt_email  + "</td>" +

                         "<td><button class='btn btn-success btn-small' onclick='editUser(" + usr.user_id + ")'>"  +
                                 "Edit</button>"         + "     " +
                             "<button class='btn btn-warning' onclick='warnUserModal(" + usr.user_id + ")'> " +
                                 "Send Warning</button>" + "     " +
		             "<button class='btn btn-danger' onclick='updateDelId(" + usr.user_id + ")'>" +
                                 "Delete</button>"       + "     " +
                         "</td>" +
                       "</tr>";

          $("#tableusr").append(markup);
        }
        $("#searchinfo").text(data.num + " result(s) found");
      }
    });
  }

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
    hideAllEditErrMsg();

    var txtFname = $('#txtFname');
    var txtLname = $('#txtLname');
    var txtRegEmail = $('#txtRegEmail');
    var txtAltEmail = $('#txtAltEmail');
    var txtPhone = $('#txtPhone');
    var txtWarn = $('#txtWarning');
    var radAdmin = $('#radAdmin');
    var radNoAdmin = $('#radNoAdmin');
    var radDisabled = $('#radDisabled');
    var radEnabled = $('#radEnabled');
    var txtUid = $('#txtUid');

    // enabled
    if ($('#' + uid + '_enabled_text').text() == "Yes")
      radEnabled.prop('checked', true);
    else
      radDisabled.prop('checked', true);

    // admin
    if ($('#' + uid + '_is_admin_text').text() == "Yes")
      radAdmin.prop('checked', true);
    else
      radNoAdmin.prop('checked', true);

    // email, alt_email, phone, first_name, last_name, warned
    txtRegEmail.val( $('#' + uid  + '_email').text()     );
    txtAltEmail.val( $('#' + uid  + '_alt_email').text() );
    txtPhone.val(    $('#' + uid  + '_phone').text()     );
    txtFname.val(    $('#' + uid  + '_fname').text()     );
    txtLname.val(    $('#' + uid  + '_lname').text()     );
    txtWarn.val(     $('#' + uid  + '_warnings').text()  );

    txtUid.val(uid);

    $("#modalEditUser").modal();
  }

  function disableAllEditUsrCntl()
  {
    $('#txtFname').prop('disabled',  true);
    $('#txtLname').prop('disabled',  true);
    $('#txtRegEmail').prop('disabled',  true);
    $('#txtAltEmail').prop('disabled',  true);
    $('#txtPhone').prop('disabled',  true);
    $('#txtWarning').prop('disabled',  true);
    $('#radAdmin').prop('disabled',  true);
    $('#radNoAdmin').prop('disabled',  true);
    $('#radDisabled').prop('disabled',  true);
    $('#radEnabled').prop('disabled',  true);
    $('#btnEditUsrClose').prop('disabled', true);
    $('#btnEditUsrSubmit').prop('disabled', true);
  }

  function enableAllEditUsrCntl()
  {
    $('#txtFname').prop('disabled',  false);
    $('#txtLname').prop('disabled',  false);
    $('#txtRegEmail').prop('disabled',  false);
    $('#txtAltEmail').prop('disabled',  false);
    $('#txtPhone').prop('disabled',  false);
    $('#txtWarning').prop('disabled',  false);
    $('#radAdmin').prop('disabled',  false);
    $('#radNoAdmin').prop('disabled',  false);
    $('#radDisabled').prop('disabled',  false);
    $('#radEnabled').prop('disabled',  false);
    $('#btnEditUsrClose').prop('disabled', false);
    $('#btnEditUsrSubmit').prop('disabled', false);
  }
 

  // Validate user fields

  function validate()
  {
    hideAllEditErrMsg();

    var fname = $.trim($('#txtFname').val());
    var lname = $.trim($('#txtLname').val());
    var regEmail = $.trim($('#txtRegEmail').val());
    var altEmail = $.trim($('#txtAltEmail').val());
    var phone = $.trim($('#txtPhone').val());
    var warn = $.trim($('#txtWarning').val());

    var radAdmin = $('#radAdmin');
    var radNoAdmin = $('#radNoAdmin');
    var radDisabled = $('#radDisabled');
    var radEnabled = $('#radEnabled');
    var txtUid = $('#txtUid');

    uid = txtUid.val();

    if (fname == "")
    {
      $('#errFname').text("Please enter a first name");
      $('#errFname').show();
      $('#txtFname').focus();
      return;
    }

    if (lname == "")
    {
      $('#errLname').text("Please enter a last name");
      $('#errLname').show();
      $('#txtLname').focus();
      return;
    }

    if (! validateEduMail(regEmail))
    {
      $('#errRegEmail').text("Please enter a valid edu email");
      $('#errRegEmail').show();
      $('#txtRegEmail').focus();
      return;
    }

    if (altEmail.length > 0)
    {
      if (! validateAltMail(altEmail))
      {
        $('#errAltEmail').text("Please enter a valid email");
        $('#errAltEmail').show();
        $('#txtAltEmail').focus();
        return;
      }
    }

    if (phone.length > 0)
    {
      if (! validatePhone(phone))
      {
        $('#errPhone').text("Please enter a valid 10 digit phone number");
        $('#errPhone').show();
        $('#txtPhone').focus();
        return;
      }
    }

    if (warn.length == 0)
    {
      $('#errWarn').text("Please enter a number");
      $('#errWarn').show();
      $('#txtWarning').focus();
    }
    else
    {
      var patt = /^[0-9]+$/g;
      if (! patt.test(warn))
      {
        $('#errWarn').text("Please enter a valid number");
        $('#errWarn').show();
        $('#txtWarning').focus();
        return;
      }
    }

    var admin;
    var enabled;

    if (radAdmin.prop('checked'))
	admin = radAdmin.val();
    else
	admin = radNoAdmin.val();

    if (radEnabled.prop('checked'))
	enabled = radEnabled.val();
    else
	enabled = radDisabled.val();

    $.ajax ({
      type: "POST",
      url: "/modules/manageUsers/editUserProc.php",
      dataType: 'json',
      data: {"fname" : fname, "lname" : lname, "email" : regEmail, "alt_email" : altEmail, "phone" : phone, "admin" : admin, "enabled" : enabled, "warnings" : warn, "uid" : uid},
      beforeSend: function() {
        $('#progressEdit').show();
        $('#btnEditUsrSubmit').text("Saving Changes...");
        disableAllEditUsrCntl();
      },
      complete: function() {
        $('#progressEdit').hide();
        $('#btnEditUsrSubmit').text("Save Changes");
        enableAllEditUsrCntl();

      },
      success: function(data) {
	console.log("Returned");
        if (data.retval == "ERR") 
        {
	  console.log("Database error: Could not update user's details");
	}

        if (window.myid == uid)
        {
          $('#setting_alt_email').text(altEmail);
          $('#setting_phone').text(phone);

          if (altEmail == "")
          {
            $('#setting_send_alt_email').text("0");
            $('#setting_list_alt_emaill').text("0");
          }

          if (phone == "")
          {
            $('#setting_send_phone').text("0");
            $('#setting_list_phone').text("0");
          }
        }

	$('#modalEditUser').modal('hide');
        doSearch();

     }
   });
}

function hideAllEditErrMsg()
{
  $('#errFname').hide();
  $('#errLname').hide();
  $('#errRegEmail').hide();
  $('#errAltEmail').hide();
  $('#errPhone').hide();
  $('#errWarn').hide();
  $('#progressEdit').hide();
}

//Send a warning email to a user
function warnUserModal(uid)
{
    var elm = document.getElementById('toWarnId');
    elm.value = uid;

    $('#modalWarnUser').modal('show');
}

/**
 * hideAllWarnMsg
 *
 * Hide all warning message hints
 */
function hideAllWarnMsg()
{
  $('errWarnMsg').hide();
  $('okWarnMsg').hide();
}
/**
 * updateWarning
 *
 * update number of user warnings
 */
function updateWarning(uid)
{
        $.ajax ({
            type: "POST",
            url: "/modules/manageUsers/manageUsersUpdateWarning.php",
            datatype: "json",
            data: { "uid" : uid },
            complete: function() {
              doSearch();
            }
        });
}

function warnUser()
{
        hideAllWarnMsg();

	var message = document.getElementById('warnMessage').value;
	var from_uid = <?php echo $_SESSION['user']; ?>;
	var to_uid = document.getElementById('toWarnId').value;
	if (message.length === 0)
	{
		$('#errWarnMsg').text("Please provide a non-empty message.");
		$('#errWarnMsg').show();
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
                        // TODO Might need to implement a delay before hiding
			console.log("send mail success");
			if(data.success === "TRUE")
			{
                                $('#okWarnMsg').text("Warning message has been sent");
                                $('#okWarnMsg').show();
				document.getElementById('warnMessage').value = "";
                                updateWarning(to_uid);
			}
			else
			{
                                $('#errWarnMsg').text("Failed to send warning message");
                                $('#errWarnMsg').show();
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

	<form id="editForm" class="form-horizontal">

          <div class="row form-group">
            <label class="col-sm-4" for="txtFname">First Name</label>
            <div class="col-sm-8">
              <input class="form-control" name="fname" id="txtFname">
              <label id="errFname" class="err" hidden="true"></label>
            </div>
          </div>

          <div class="row form-group">
            <label class="col-sm-4" for="txtLname">Last Name</label>
            <div class="col-sm-8">
              <input class="form-control" name="lname" id="txtLname">
              <label id="errLname" class="err" hidden="true"></label>
            </div>
          </div>

          <div class="row form-group">
            <label class="col-sm-4" for="txtRegEmail">Registered Email</label>
            <div class="col-sm-8">
              <input class="form-control" name="regEmail" id="txtRegEmail">
              <label id="errRegEmail" class="err" hidden="true"></label>
            </div>
          </div>

          <div class="row form-group">
            <label class="col-sm-4" for="altEmail">Alternate Email</label>
            <div class="col-sm-8">
              <input class="form-control" name="altEmail" id="txtAltEmail">
              <label id="errAltEmail" class="err" hidden="true"></label>
            </div>
          </div>

          <div class="row form-group">
            <label class="col-sm-4" for="txtPhone">Phone</label>
            <div class="col-sm-8">
              <input class="form-control" name="phone" id="txtPhone">
              <label id="errPhone" class="err" hidden="true"></label>
            </div>
          </div>

          <div class="row form-group">
            <label class="col-sm-4">Admin</label>
            <div class="col-sm-8 input-group-sm">
	    <input type="radio"  name="admin" id="radAdmin" value="1"> Yes
	    <input type="radio"  name="admin" id="radNoAdmin"value="0"> No<br>
            </div>
          </div>

          <div class="row form-group">
            <label class="col-sm-4">Enabled</label>
            <div class="col-sm-8 input-group-sm">
	    <input type="radio" name="enabled" id="radEnabled" value="1"> Yes
	    <input type="radio" name="enabled" id="radDisabled" value="0"> No<br>
            </div>
          </div>

          <div class="row form-group">
            <label class="col-sm-4">Warnings</label>
            <div class="col-sm-2">
              <input class="form-control" name="warning" id="txtWarning">
            </div>
            <div class="row col-sm-12 col-sm-offset-4">
              <label id="errWarn" class="err" hidden="true"></label>
            </div>
          </div>

	  <input type="text" hidden="true" id="txtUid">

          <div class="modal-footer">
            <button type="button" id='btnEditUsrClose'  class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" id='btnEditUsrSubmit' class="btn btn-primary" onclick="validate(); return false;">Save changes</button>
            <img id='progressEdit' src='/images/load.gif'/>
          </div>
        </form>
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
                <label class="err" id="errWarnMsg" hidden='true'></label>
                <label class="ok" id="okWarnMsg" hidden='true'></label>
	</form>
      </div>
      <div class="modal-footer">
      	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" onClick="warnUser()">Send Warning</button>
      </div>
    </div>
  </div>
</div>


