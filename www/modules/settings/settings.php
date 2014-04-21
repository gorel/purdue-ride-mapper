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
  <link href="/css/custom.css" rel="stylesheet">

  <style>
    .err { color:#FF0000; font-weight:bold; }
    .ok  { color:#397D02; font-weight:bold; }
  </style>

  <script>

  // preferences need to reflect latest account information

  $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
   if (e.currentTarget.id == "tab_pref")
   {
     fillPref();
   }
  });
  

  function initFields()
  {
    hideAllPwSettingsMsg();
    hideAllAccntSettingsMsg();
    resetPasswordTab();
    resetAccountTab();
    fillAccnt();
    hideAllPrefMsg();
    $('#modalSettings').modal('show');
  }

  /**
   * Account Information
   */
  function fillAccnt()
  {
    $('#txtAltEmailSettings').val($('#setting_alt_email').text());
    $('#txtPhoneSettings').val($('#setting_phone').text());
  }

  function resetAccountTab()
  {
    hideAllAccntSettingsMsg();
    $('#txtAltEmailSettings').val("");
    $('#txtPhoneSettings').val("");
  }
  
  function hideAllAccntSettingsMsg()
  {
    $('#progressSaveAccnt').hide();
    $('#errAltEmail').hide();
    $('#errPhone').hide();
    $('#okAccnt').hide();
    $('#errAccnt').hide();
  }

  function disableAllAccntSettingsCntl()
  {
    $('#txtAltEmailSettings').prop('disabled', true);
    $('#txtPhoneSettings').prop('disabled', true);
    $('#btnSaveAccntSettingsClose').prop('disabled', true);
    $('#btnSaveAccntSettings').prop('disabled', true);
  }

  function enableAllAccntSettingsCntl()
  {
    $('#txtAltEmailSettings').prop('disabled', false);
    $('#txtPhoneSettings').prop('disabled', false);
    $('#btnSaveAccntSettingsClose').prop('disabled', false);
    $('#btnSaveAccntSettings').prop('disabled', false);
  }

  function saveAccnt()
  {
    hideAllAccntSettingsMsg();

    var alt_email = $.trim($('#txtAltEmailSettings').val());
    var phone = $.trim($('#txtPhoneSettings').val());

    if (alt_email.length > 0) {
    
      if (! validateAltMail(alt_email))
      {
        $('#errAltEmail').text("Please enter a valid email address");
        $('#errAltEmail').show();
        $('#txtAltEmailSettings').focus();
        return;
      }
    }  

    if (phone.length > 0)
    {
      if (! validatePhone(phone))
      {
        $('#errPhone').text("Please enter a valid 10 digit phone number");
        $('#errPhone').show();
        $('#txtPhoneSettings').focus();
        return;
      }
    }

    $.ajax({
      type: "POST",
      url: "/modules/settings/saveAccnt.php",
      dataType: "json",
      beforeSend: function() {
        disableAllAccntSettingsCntl();
        $('#btnSaveAccntSettings').text("Saving Account Information");
        $('#progressSaveAccnt').show();

      },
      complete: function() {
        enableAllAccntSettingsCntl();
        $('#btnSaveAccntSettings').text("Save Account Information");
        $('#progressSaveAccnt').hide();
        console.log("Complete");

      },
      data: {"alt_email" : alt_email, "phone" : phone, "uid" : window.uid},
      success: function(data) {
        if (data.retval == "OK")
        {
          $('#okAccnt').text("Updated account information successfully");
          $('#okAccnt').show();

          $('#setting_alt_email').text(alt_email);
          $('#setting_phone').text(phone);
          
          if (alt_email == "")
            $('setting_alt_email').text("0");

          if (phone == "")
            $('setting_phone').text("0");
        }
        else
        {
          $('#errAccnt').text("Updated account information successfully");
          $('#errAccnt').show();

        }
      } 
    });
  }

  /**
   * Change Password functions
   */

  function resetPasswordTab()
  {
    hideAllPwSettingsMsg();
    $('#txtCurrPwSettings').val("");
    $('#txtNewPwSettings').val("");
    $('#txtRetypeNewPwSettings').val("");
    $('#progressChangePw').hide();
  }

  function hideAllPwSettingsMsg()
  {
    $('#errCurrPw').hide();
    $('#errNewPw').hide();
    $('#errRetypeNewPw').hide();
    $('#okNewPw').hide();
  }

  function disableAllPwSettingsCntl()
  {
    $('#txtCurrPwSettings').prop('disabled', true);
    $('#txtNewPwSettings').prop('disabled', true);
    $('#txtRetypeNewPwSettings').prop('disabled', true);
    $('#btnChangePwSettings').prop('disabled', true);
    $('#btnChangePwSettingsClose').prop('disabled', true);
  }

  function enableAllPwSettingsCntl()
  {
    $('#txtCurrPwSettings').prop('disabled', false);
    $('#txtNewPwSettings').prop('disabled', false);
    $('#txtRetypeNewPwSettings').prop('disabled', false);
    $('#btnChangePwSettings').prop('disabled', false);
    $('#btnChangePwSettingsClose').prop('disabled', false);
  }

  function changePass()
  {
    hideAllPwSettingsMsg();

    // trim white spaces

    $('#txtCurrPwSettings').val($.trim($('#txtCurrPwSettings').val()));
    $('#txtNewPwSettings').val($.trim($('#txtNewPwSettings').val()));
    $('#txtRetypeNewPwSettings').val($.trim($('#txtRetypeNewPwSettings').val()));

    var currPw = $('#txtCurrPwSettings').val();
    var newPw = $('#txtNewPwSettings').val();
    var retypeNewPw = $('#txtRetypeNewPwSettings').val();

    if (currPw.length == 0)
    {
      $('#errCurrPw').text("Please enter your current password");
      $('#errCurrPw').show();
      $('#txtCurrPwSettings').focus();
      return;
    }

    if (newPw.length == 0)
    {
      $('#errNewPw').text("Please enter your new password");
      $('#errNewPw').show();
      $('#txtNewPwSettings').focus();
      return;
    }

    if (newPw.length < 6)
    {
      $('#errNewPw').text("Please enter a minimum of 6 characters");
      $('#errNewPw').show();
      $('#txtNewPwSettings').focus();
      return;
    }

    if (retypeNewPw.length == 0)
    {
      $('#errRetypeNewPw').text("Please retype your new password");
      $('#errRetypeNewPw').show();
      $('#txtRetypeNewPwSettings').focus();
      return;

    }

    if (newPw != retypeNewPw)
    {
      $('#errRetypeNewPw').text("New password must match");
      $('#errRetypeNewPw').show();
      $('#txtRetypeNewPwSettings').focus();
      return;
    }

    $.ajax ({
      type: "POST",
      url: "/modules/settings/settingsPassword.php",
      dataType: "json",
      beforeSend: function() {
        disableAllPwSettingsCntl();
        $('#btnChangePwSettings').text("Changing Password...");
        $('#progressChangePw').show();

      },
      complete: function() {
        enableAllPwSettingsCntl();
        $('#btnChangePwSettings').text("Change Password");
        $('#progressChangePw').hide();

      },
      data: {"currPw" : currPw, "newPw" : newPw, "uid" : window.uid},
      success: function(data) {
        if (data.status == "AUTH_FAILED")
        {
	  $('#errCurrPw').text("Wrong Password. Please retype your current password");
          $('#errCurrPw').show(5,function() {$('#txtCurrPwSettings').focus()});
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
    $('#progressSavePref').hide();
  }

  function enableAllPrefCntl()
  {
      $('#btnSavePrefSettings').prop('disabled', false);
      $('#btnSavePrefSettingsClose').prop('disabled', false);
  }

  function disableAllPrefCntl()
  {
      $('#btnSavePrefSettings').prop('disabled', true);
      $('#btnSavePrefSettingsClose').prop('disabled', true);
  }

  function fillPref()
  {
    // show latest account information

    $('#pref_req_phone').text($('#setting_phone').text());
    $('#pref_req_alt_email').text($('#setting_alt_email').text());
    $('#pref_req_reg_email').text($('#setting_reg_email').text());

    $('#pref_list_phone').text($('#setting_phone').text());
    $('#pref_list_alt_email').text($('#setting_alt_email').text());
    $('#pref_list_reg_email').text($('#setting_reg_email').text());

    // check the boxes 

    if ($('#setting_alt_email').text() != "")
    {
      $('#cbListAltEmail').prop('disabled', false);
      $('#cbSendAltEmail').prop('disabled', false);

      if ($('#setting_list_alt_email').text() == 1)
        $('#cbListAltEmail').prop('checked', true);

      if ($('#setting_send_alt_email').text() == 1)
        $('#cbSendAltEmail').prop('checked', true);

    }
    else
    {
        $('#cbListAltEmail').prop('disabled', true);
        $('#cbListAltEmail').prop('checked', false);

        $('#cbSendAltEmail').prop('disabled', true);
        $('#cbSendAltEmail').prop('checked', false);

    }

    if ($('#setting_phone').text() != "")
    {
      $('#cbSendPhone').prop('disabled', false);
      $('#cbListPhone').prop('disabled', false);

      if ($('#setting_send_phone').text() == 1)
        $('#cbSendPhone').prop('checked', true);

    
      if ($('#setting_list_phone').text() == 1)
        $('#cbListPhone').prop('checked', true);
   
    }
    else
    {
      $('#cbSendPhone').prop('disabled', true);
      $('#cbSendPhone').prop('checked', false);

      $('#cbListPhone').prop('disabled', true);
      $('#cbListPhone').prop('checked', false);
    }

    // Setting for listing registered mail cannot be changed

    if ($('#setting_list_reg_email').text() == 1)
      $('#cbListRegEmail').prop('checked', true);
        
        
  }

  function savePref()
  {
    hideAllPrefMsg();

    var send_phone = 0;
    var send_alt_email = 0;
    var list_phone = 0;
    var list_alt_email = 0;
    var list_reg_email = 0;

    if ($('#cbSendPhone').prop('checked'))
    {
      send_phone = 1; 
    }

    if ($('#cbSendAltEmail').prop('checked'))
    {
      send_alt_email = 1;
    }

    if ($('#cbListPhone').prop('checked'))
    {
      list_phone = 1;
    }

    if ($('#cbListAltEmail').prop('checked'))
    {
      list_alt_email = 1;
    }
    
    if ($('#cbListRegEmail').prop('checked'))
    {
      list_reg_email = 1;
    }
    
    $.ajax ({
      type: "POST",
      url: "/modules/settings/savePref.php",
      dataType: "json",
      beforeSend: function() {
        disableAllPrefCntl();
        $('#btnSavePrefSettings').text("Saving Preferences...");
        $('#progressSavePref').show();

      },
      complete: function() {  
        enableAllPrefCntl();
        $('#btnSavePrefSettings').text("Save Preferences");
        $('#progressSavePref').hide();
          
      },
      data: {"send_phone" : send_phone , "send_alt_email" : send_alt_email , "list_phone" : list_phone , "list_alt_email" : list_alt_email , "list_reg_email" : list_reg_email, "uid" : window.uid },
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

            $('#setting_send_phone').text(send_phone);
            $('#setting_list_phone').text(list_phone);
            $('#setting_send_alt_email').text(send_alt_email);
            $('#setting_list_alt_email').text(list_alt_email);
            $('#setting_list_reg_email').text(list_reg_email);
          }  
      } 
    });
  }
  </script>

<!-- </head> -->


<hr class="featurette-divider">

<?php
			
  if (!isset($_SESSION['user']))
  {
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
           "first_name, last_name, email, alt_email, phone " .
           "FROM users where user_id = $uid";

  $stmt = $conn->stmt_init();
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $stmt->bind_result($fname, $lname, $email, $alt_email, $phone);
  $stmt->fetch();
  $stmt->close();


  $query = "SELECT " .
           "send_phone, send_alt_email, list_phone, list_reg_email, list_alt_email " .
           "FROM user_settings WHERE user_id=?";

  $stmt = $conn->prepare($query);
  $stmt->bind_param('d', $uid);
  $stmt->execute();
  $stmt->bind_result($send_phone, $send_alt_email, $list_phone, $list_reg_email, $list_alt_email);
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
	          <li class='active tab'><a id='tab_accnt' href='#account' data-toggle='tab'>Account</a></li>
	          <li class='tab'><a id='tab_pref' href='#preferences' data-toggle='tab'>Preferences</a></li>
	          <li class='tab'><a id='tab_pw' href='#changepw' data-toggle='tab'>Change Password</a></li>
	        </ul>

                <div id='tabcontent' class='tab-content'>
  
                  <!----------------------Account-------------------->                  
 
                  <div class='tab-pane active' id='account'>

                      <form class='form-horizontal'>
                        <div class= 'row form-group'>
                        </div>

                        <div class='row form-group'>
                          <div class='col-sm-4'>
                            <label class='control-label' for='txtFNameSettings'>First Name</label>
                          </div>
                          <div class='col-sm-6'>
                            <input class='form-control' id='txtFNameSettings' value=$fname disabled>
                          </div>
                        </div> <!-- row -->

                        <div class='row form-group'>
                          <div class='col-sm-4'>
                            <label for='txtLNameSettings'>Last Name</label>
                          </div>
                          <div class='col-sm-6'>
                            <input class='form-control' id='txtLNameSettings' value=$lname disabled>
                          </div>
                        </div> <!-- row -->

                        <div class='row form-group'>
                          <div class='col-sm-4'>
                            <label for='txtRegEmailSettings'>Registered Email</label>
                          </div>
                          <div class='col-sm-6'>
                            <input class='form-control' id='txtRegEmailSettings' value=$email disabled>
                          </div>
                        </div> <!--row -->

                        <div class='row form-group'>
                          <div class='col-sm-offset-2'>
  		            Please <a href=''>contact us</a> if you need to change the above details
                          </div>
                        </div> <!--row-->

                        <div class='row form-group'>
                          <div class='col-sm-4'>
                            <label for='txtAltEmailSettings'>Alternate Email</label>
                          </div>
                          <div class='col-sm-6'>
                            <input class='form-control' id='txtAltEmailSettings' value=$alt_email>
                            <div class='err' id='errAltEmail'></div>
                          </div>
                        </div> <!--row -->

                        <div class='row form-group'>
                          <div class='col-sm-4'>
                            <label for='txtPhoneSettings'>Phone</label>
                          </div>
                          <div class='col-sm-6'>
                            <input class='form-control' id='txtPhoneSettings' value=$phone>
                            <div class='err' id='errPhone'></div>
                            <div class='ok' id='okAccnt'></div>
                            <div class='err' id='errAccnt'></div>
                          </div>
                        </div> <!--row -->
                        
                        <div class='modal-footer'> 
                            <button type='button' class='btn btn-default' id='btnSaveAccntSettingsClose' data-dismiss='modal'>Close</button>
                            <button class='btn btn-primary ' id='btnSaveAccntSettings' type='submit' onclick='saveAccnt(); return false;'>Save Account Information</button>
                            <img id='progressSaveAccnt' src='/images/load.gif'/>
                        </div>

                      </form> 
                  </div> <!-- account pane -->
                    

                  <!----------------------Preferences-------------------->                  

                  <div class='tab-pane' id='preferences'>
                    <form class='form-horizontal'>
                        <div class= 'row form-group'>
                        </div>

                        <div class='row form-group'>
                          <div class='col-sm-12'>
                            <label class='control-label'>When I send a message in a listing </label>
                          </div>

                          <div class='col-sm-12'>

                            <div class='col-sm-offset-1'>
                              I want the following contact information to be sent:
                            </div>

                            <!-- individual settings -->
                            <div class='col-sm-offset-2'> 
                              <div class='col-sm-12'>
                                <input id='cbSendPhone'type='checkbox'> Phone: </input>
                                <label id='pref_req_phone'></label>
                              </div>
                              <div class='col-sm-12'>
                                <input type='checkbox' disabled checked> Registered Email: </input>
                                <label id='pref_req_reg_email'></label>
                              </div>
                              <div class='col-sm-12'>
                                <input id='cbSendAltEmail' type='checkbox'> Alternate Email: </input>
                                <label id='pref_req_alt_email'></label>
                              </div>
                            </div> <!-- individual settings -->

                          </div> <!-- settings group -->
                        </div> <!-- row -->


                        <div class='row form-group'>
                          <div class='col-sm-12'>
                            <label class='control-label'>When I create a ride listing </label>
                          </div>
                          
                          
			   <div class='col-sm-12'>

                            <div class='col-sm-offset-1'>
                              I want the following contact information to be listed:
                            </div>

                            <!-- individual settings -->
                            <div class='col-sm-offset-2'> 
                              <div class='col-sm-12'>
                                <input id='cbListPhone' type='checkbox'> Phone: </input>
                                <label id='pref_list_phone'></label>
                              </div>
                              <div class='col-sm-12'>
                                <input id='cbListRegEmail' type='checkbox'> Registered Email: </input>
                                <label id='pref_list_reg_email'></label>
                              </div>
                              <div class='col-sm-12'>
                                <input id='cbListAltEmail'type='checkbox'> Alternate Email: </input>
                                <label id='pref_list_alt_email'></label>
                                <label id='errPref' class='err'></label>
                                <label id='okPref' class='ok'></label>
                              </div>
                            </div> <!-- individual settings -->
                           </div> <!-- settings group -->
                        </div> <!-- row -->

                      <div class='modal-footer'> 
                          <button type='button' class='btn btn-default' id='btnSavePrefSettingsClose' data-dismiss='modal'>Close</button>
                          <button class='btn btn-primary ' id='btnSavePrefSettings' type='submit' onclick='savePref(); return false;'>Save Preferences</button>
                          <img id='progressSavePref' src='/images/load.gif'/>
                      </div>

                    </form>

                  </div> <!-- perferences -->
                  

                  <!----------------------Change Password-------------------->                  

                  <div class='tab-pane' id='changepw'>
                    <form class='form-horizontal'>
                        <div class= 'row form-group'>
                        </div>

                        <div class='row form-group'>
                          <div class='col-sm-4'>
                            <label class='control-label' for='txtCurrPwSettings'>Current Password</label>
                          </div>
                          <div class='col-sm-6'>
                            <input class='form-control' type='password' id='txtCurrPwSettings'>
                            <div class='err' id='errCurrPw'></div>
                          </div>
                        </div> <!-- row -->

                        <div class='row form-group'>
                          <div class='col-sm-4'>
                            <label for='txtNewPwSettings'>New Password</label>
                          </div>
                          <div class='col-sm-6'>
                            <input class='form-control' type='password' id='txtNewPwSettings'>
                            <div class='err' id='errNewPw'></div>
                          </div>
                        </div> <!-- row -->

                        <div class='row form-group'>
                          <div class='col-sm-4'>
                            <label for='txtRetypeNewPwSettings'>Retype New Password</label>
                          </div>
                          <div class='col-sm-6'>
                            <input class='form-control' type='password' id='txtRetypeNewPwSettings'>
                            <div class='err' id='errRetypeNewPw'></div>
                            <div class='ok' id='okNewPw'></div>
                          </div>
                        </div> <!--row -->

                        <div class='modal-footer'> 
                            <button type='button' class='btn btn-default' id='btnChangePwSettingsClose' data-dismiss='modal'>Close</button>
                            <button class='btn btn-primary ' type='submit' id='btnChangePwSettings' onclick='changePass(); return false;'>Change Password</button>
                            <img id='progressChangePw' src='/images/load.gif'/>
                        </div>
 
                    </form>
                  </div> <!-- changepw -->
               
          </div> <!-- Modal body -->

    </div>
  </div>

  <!-- Setttings for user -->

  <label id='setting_alt_email' hidden='true'>$alt_email</label>
  <label id='setting_reg_email' hidden='true'>$email</label>
  <label id='setting_phone' hidden='true'>$phone</label>

  <label id='setting_send_alt_email' hidden='true'>$send_alt_email</label>
  <label id='setting_send_phone' hidden='true'>$send_phone</label>

  <label id='setting_list_alt_email' hidden='true'>$list_alt_email</label>
  <label id='setting_list_reg_email' hidden='true'>$list_reg_email</label>
  <label id='setting_list_phone' hidden='true'>$list_phone</label>


</div>"


?>

