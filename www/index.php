<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>College Carpool</title>


		<!-- Bootstrap core CSS -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/bootstrap.css" rel="stylesheet">
		<link href="css/justified-nav.css" rel="stylesheet">
                <style>
                  .err { color:#FF0000; font-weight:bold; }
                  .ok  { color:#397D02; font-weight:bold; }
                </style>

		
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
		<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
		<script src="js/gmaps.js"></script>				
		<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
		<script type="text/javascript" src="js/moment.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
	</head>
	<body>

		<script type="text/javascript">
			function showSignInModal()
			{
				clrLoginCntl();
				$('#signInModal').modal('show');
			}
			
			function hideAll(sender)
			{
				if (document.getElementById("about")) {
					document.getElementById('about').parentNode.className = "inactive";
				}
				if (document.getElementById("contact")) {
					document.getElementById('contact').parentNode.className = "inactive";
				}
				if (document.getElementById("findARide")) {
					document.getElementById('findARide').parentNode.className = "inactive";
				}
				if (document.getElementById("listARide")) {
					document.getElementById('listARide').parentNode.className = "inactive";
				}
				if (document.getElementById("home")) {
					document.getElementById('home').parentNode.className = "inactive";
				}
				if (document.getElementById("manageUsers")) {
					document.getElementById('manageUsers').parentNode.className = "inactive";
				}
				if (document.getElementById("editListings")) {
					document.getElementById('editListings').parentNode.className = "inactive";
				}
				sender.parentNode.className = "active";
			}
		</script>

		<div class="container">
			<div id="#body">
				<div class="masthead">
					<div align=middle>
						<img src="images/LogoHiRes.png" align="middle" width="600" alt="College-Carpool-Banner">
					</div>
					<ul class="nav nav-justified">
						<li class="active"><a href="#" id="home" onClick="hideAll(this);">Home</a></li>
						<li><a href="#" id="about" onclick="hideAll(this);">About Us</a></li>
						<li><a href="#" id="contact" onclick="hideAll(this);">Contact Us</a></li>
						<li><a href="#" id="findARide" onclick="hideAll(this);">Find a Ride</a></li>
						<?php
							if (!isset($_SESSION['user']))
							{
								echo '<li><a href="#" id="loginModal" onclick="showSignInModal()">Log In</a></li>';
								//echo '<li><a href="#" id="login" onclick="hideAll(this);">Log In</a></li>';
							}
							else
							{
                                                                if (isset($_SESSION['isAdmin']))
                                                                {
								        echo '<li><a href="#" id="manageUsers" onclick="hideAll(this);">Manage Users</a></li>';
								        echo '<li><a href="#" id="editListings" onclick="hideAll(this);">All Listings</a></li>';

                                                                } 
                                                                else
                                                                {
								        echo '<li><a href="#" id="editListings" onclick="hideAll(this);">My Listings</a></li>';

                                                                }

								echo '<li><a href="#" id="listARide" onclick="hideAll(this);">Create a Ride</a></li>';
								echo '<li><a href="#" id="settings" onclick="">Settings</a></li>';
								echo '<li><a href="#" id="logout" onclick="location.href = \'modules/signin/signoutProc.php\';">Log Out</a></li>';
							}

							function logout()
							{
								session_destroy();
								echo "<script type='text/javascript'>alert('logged out'); location.reload();</script>";
							}
						?>

					</ul>
				</div>

				<div id="content">
					<hr class="featurette-divider">
					<!-- Jumbotron -->
					<div class="jumbotron">
						<h1>Welcome to College Carpool!</h1>
						<p class="lead">Many students who attend college live far away from home.
						Many of those students do not own vehicles on campus. When it comes time to take trips home or to other places,
						some students need rides or are looking for people to share with to help pay for gas. But sometimes finding people
						in a student population of 40,000 can be difficult or even impossible.
						College Carpool is here to make it easier!
						</p>

						<p><a class="btn btn-lg btn-success" id="register" href="#" role="button" onclick="showRegisterModal();">Register Now!</a></p>
					</div>

					<!-- Example row of columns -->
					<div class="row">
						<div class="col-lg-4">
							<h2>Find a Ride</h2>
							<p>
							Once you register or sign in, you will be able to see a list of all available rides.
							</p>
							<p><a class="btn btn-primary" id="findARideAlternative" href="#" role="button" onclick="hideAll(this);">Find a ride</a></p>
						</div>
						<div class="col-lg-4">
							<h2>Create a Ride</h2>
							<p>
							Once you sign in, you will be able to create your own rides.
							</p>
							<p><a class="btn btn-primary" id="listARideAlternative" href="#" role="button" onclick="hideAll(this);">List a ride</a></p>
						</div>
						<div class="col-lg-4">
							<h2>Questions?</h2>
							<p>
							Concerns? Not sure how this works? Found a bug? Feel free to email us!
							</p>
							<p><a class="btn btn-primary" id="contactAlternative" href="#" role="button" onclick="hideAll(this);">Contact us</a></p>
						</div>
					</div>
				</div>

				<script type="text/javascript">
					$('#txtEmail').on('input', function() {
						console.log("pop");
					});            
					$("#editListings").click(function()
					{
						$( "#content" ).load( "modules/editListings/editListings.php" );
					});
					$("#manageUsers").click(function()
					{
						$( "#content" ).load( "modules/manageUsers/manageUsers.php" );
					});
					$("#contact").click(function()
					{
						$( "#content" ).load( "modules/contact/contact.php" );
					});
					$("#contactAlternative").click(function()
					{
						$( "#content" ).load( "modules/contact/contact.php" );
					});
					$("#home").click(function()
					{
						$( "#content" ).load( "home.php" );
					});
					$("#listARide").click(function()
					{
						$( "#content" ).load( "modules/createListing/createListing.php" );
					});
					$("#findARideAlternative").click(function()
					{
						$( "#content" ).load( "modules/findListing/findListing.php" );
					});
					$("#listARideAlternative").click(function()
					{
						$( "#content" ).load( "modules/createListing/createListing.php" );
					});
					$("#findARide").click(function()
					{
						$( "#content" ).load( "modules/findListing/findListing.php" );
					});
					$("#about").click(function()
					{
						$( "#content" ).load( "modules/about/about.php" );
					});

                                        $("#settings").click(function()
					{
						initFields();
					});
				</script>

				<?php if ($_GET["page"] == "editListings") { ?>
					<script type='text/javascript'> 
						$(document).ready(function() {
							$("#editListings").click();
						});
					</script>
				<?php } ?>


				<!-- Site footer -->
				<div class="footer">
					<p>&copy; College Carpool 2014</p>
				</div>
			</div>
		</div> <!-- /container -->
	</body>
	
	
	<!-- SignIn Form Validation -->
	<script>
          var signInEmailValid = false;
	  var signInPasswordValid = false;

	  function showRegisterModal()
	  {
            $('#signInModal').modal('hide');
	    $('#registerModal').modal('show');
	  }

	  /**
           * showPwModal()
	   *
	   * bring up the modal to submit email for resetting password
	   */
	   function showPwModal()
	   {
	     $('#signInModal').modal('hide');
	     $('#modalPwReset').modal('show');			
	   }

	   /**
	    * submitEmail
	    *
	    * process the email to inform user how to reset the password
	    */
	    function submitEmail()
	    {
	      var elm = document.getElementById('txtForgotPwEmail');
	      var patt = /[A-Za-z0-9]+@([A-Za-z0-9]+\.[A-Za-z0-9])+/i

	      if (txtForgotPwEmail.value.trim() == "") 
	      {
	        alert("Please enter an email address");
		return;
	      }

	      if (! patt.test(txtForgotPwEmail.value)) 
	      {
		alert("Please enter a valid email address!");
		return;
	      }

              $.ajax ({ 
	        type: "POST",
		url: "/modules/signin/forgotPassword.php", 
		dataType: 'json',
		data: {"email" : txtForgotPwEmail.value.trim()}, 
		success: function(data) {

		  if (data.retval == "ERR") 
		  {
		      alert("Error: No such email / database connection failed");
		      $('#modalPwReset').modal('hide');
		      return;
		  }

		  alert("An email has been sent to your account, please check for details.");
		  $('#modalPwReset').modal('hide');
		}	
	      }); 
			

	    }
	    
            /**
             * signIn()
             *
             */
            function signIn()
            {
              var email = $('#txtEmail').val().trim(); 
              var password = $('#txtPassword').val().trim();

              $.ajax ({
                type: "POST",
                url: "/modules/signin/signinProc.php",
                dataType: 'json',
                data: {"email" : email, "password" : password },
                success: function(data) {
                  var errMsg = $('#errAuth');

                  if (data.retval == "AUTH_OK")
                  {
        	    window.location.replace("/");	
                    return;
                  } 
                  else if (data.retval == "AUTH_FAILED_PW") 
                  {
                    errMsg.text("Authentication failed. Please retype your password");
                       
                  } 
                  else if (data.retval == "AUTH_NO_USER") 
                  {
                    errMsg.text("Account does not exist. Please register :)");

                  } 
                  else if (data.retval == "AUTH_UNVERIFIED")
                  {
                    errMsg.text("Please activate your account through the link sent in your email");
                  }
                  else if (data.retval == "AUTH_BANNED")
                  {
                    errMsg.text("Your account has been banned. Please contact us for further information");
                  }
                  else  
                  { 
                    errMsg.text("An unexpected error occurred");  
                  }
                   
                  errMsg.show();
    
                }
              }); 
               
            }

            function clrLoginCntl()
            {
              $('#txtEmail').val("");
              $('#txtPassword').val("");
              $('#errAuth').hide();
            }

		function validateSignInEmail(sender)
		{
			var parent = sender.parentNode;
			var textBoxValue = sender.value;
			var atPos= textBoxValue.indexOf("@");
			var dotPos= textBoxValue.lastIndexOf(".");
			var edu = textBoxValue.split(".").pop();

			if (atPos < 1 || dotPos < atPos + 2 || dotPos + 2 >= textBoxValue.length || edu != "edu")
			{
				parent.className = "form-group has-error";
				signInEmailValid = false;
				validateSignInForm();
			}
			else
			{
				parent.className = "form-group has-success";
				signInEmailValid = true;
				validateSignInForm();
			}
		}

		function validateSignInPassword(sender)
		{
			var parent = sender.parentNode;
			var textBoxValue = sender.value;

			if(textBoxValue.length >= 6 && textBoxValue.length <= 24)
			{
				parent.className = "form-group has-success";
				signInPasswordValid = true;
				validateSignInForm();
			}
			else
			{
				parent.className = "form-group has-error";
				signInPasswordValid = false;
				validateSignInForm();
			}
		}

		function validateSignInForm()
		{
			var button =  document.getElementById('loginButton');
			if(signInEmailValid && signInPasswordValid)
			{
				button.disabled = false;
			}
			else
			{
				button.disabled = true;
			}
		}
	</script>
	
	<!-- Register Form Validation -->
	<script>
		var emailValid = false;
		var firstNameValid = false;
		var lastNameValid = false;
		var passwordValid = false;
		var passwordRepeatValid = false;
		var termsOfServiceValid = false;
		
		function validateEmail(sender) 
		{
			var parent = sender.parentNode;		
			var textBoxValue = sender.value;
			var atPos= textBoxValue.indexOf("@");
			var dotPos= textBoxValue.lastIndexOf(".");
			var edu = textBoxValue.split(".").pop();
			
			if (atPos < 1 || dotPos < atPos + 2 || dotPos + 2 >= textBoxValue.length || edu != "edu")
			{
				parent.className = "form-group has-error";
				emailValid = false;
				validateForm();
			}
			else
			{
				parent.className = "form-group has-success";
				emailValid = true;
				validateForm();
			}
		}
		
		function validateFirstName(sender)
		{
			var parent = sender.parentNode;		
			var textBoxValue = sender.value;
			
			if(textBoxValue.length != 0)
			{
				parent.className = "form-group has-success";
				firstNameValid = true;
				validateForm();
			}
			else
			{
				parent.className = "form-group has-error";
				firstNameValid = false;
				validateForm();
			}
		}
		
		function validateLastName(sender)
		{
			var parent = sender.parentNode;		
			var textBoxValue = sender.value;
			
			if(textBoxValue.length != 0)
			{
				parent.className = "form-group has-success";
				lastNameValid = true;
				validateForm();
			}
			else
			{
				parent.className = "form-group has-error";
				lastNameValid = false;
				validateForm();
			}
		}
		
		function validatePassword(sender)
		{
			var parent = sender.parentNode;		
			var textBoxValue = sender.value;
			
			if(textBoxValue.length >= 6 && textBoxValue.length <= 24)
			{
				parent.className = "form-group has-success";
				passwordValid = true;
				validateForm();
			}
			else
			{
				parent.className = "form-group has-error";
				passwordValid = false;
				validateForm();
			}
		}

		function validatePasswordMatcher(sender)
		{
			var parent = sender.parentNode;		
			var textBoxValue = sender.value;
			var textBoxValueTwo =  document.getElementById('password').value;
			
			if(textBoxValue == textBoxValueTwo)
			{
				parent.className = "form-group has-success";
				passwordRepeatValid = true;
				validateForm();
			}
			else
			{
				parent.className = "form-group has-error";
				passwordRepeatValid = false;
				validateForm();
			}
		}
		
		function validateTermsOfService(sender)
		{
			var parent = sender.parentNode.parentNode;		
			if(sender.checked)
			{
				parent.className = "form-group has-success";
				termsOfServiceValid = true;
				validateForm();
			}
			else
			{
				parent.className = "form-group has-error";
				termsOfServiceValid = false;
				validateForm();
			}
		}
		
		function validateForm()
		{
			var button =  document.getElementById('submitButton');
			
			if(termsOfServiceValid && passwordRepeatValid && firstNameValid && lastNameValid && emailValid && passwordValid)
			{
				button.disabled = false;
			}
			else
			{
				button.disabled = true;
			}
		}
	
	</script>

	<!-- Login Modal -->
	<div class="modal fade" id="signInModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h3>Please Sign In</h3>
					</div>
					<div class = "modal-body">
					<form id="loginform" >
						<div class="form-group">	
							<input type="text" class="form-control" name="email" id="txtEmail" placeholder="Email" required autofocus>
						</div>

						<div class="form-group">
							<input type="password" class="form-control" name="pass" id="txtPassword" placeholder="Password" required>
						</div>
						<label id="errAuth" class="err" hidden="true"></label>

						<div class="form-group">
							<label class="checkbox">
								<input type="checkbox" value="remember-me">Remember me
							</label>
						</div>

						<div class="form-group">
							<button class="btn btn-lg btn-primary btn-block" onclick="signIn(); return false;" id="loginButton" >Sign in</button>

						</div>

						<div class="form-group">
							<button class="btn btn-lg btn-primary btn-block" type="reset" onclick="showRegisterModal()" type = "text" id="registerButton">Register</button>
						</div>

						<div class="form-group">
						</div>
							
						<button class="btn btn-lg btn-primary btn-block" type="reset" onclick="showPwModal()" type="text" id="pwModalButton">Forgot Password?</button>
						</div>
					</form>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
					
			</div>
		</div>
	</div>
	
	<!-- Password reset modal -->
	<div class="modal fade" id="modalPwReset" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Send Reset Password Link</h4>
				</div>
				<div class="modal-body">
					<b>Email</b>      <input type="text" class="form-control" name="email" id="txtForgotPwEmail"><br>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" onClick="submitEmail()">Submit</button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- register modal -->	<!-- Password reset modal -->
	<div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form class="form-signin" action="/modules/register/registerProc.php" role="form" method="post" id="registerForm">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h3>Register</h3>
					</div>
					<div class = "modal-body">
						<div class="form-group has-error">
							<input type="text" class="form-control" name="fname" placeholder="First Name" onkeyup="validateFirstName(this);" required autofocus>
						</div>

						<div class="form-group has-error">
							<input type="text" class="form-control" name="lname" placeholder="Last Name" onkeyup="validateLastName(this);" required autofocus>
						</div>

						<div class="form-group has-error" id="test">
							<input type="text" class="form-control" name="email" placeholder="Email" onkeyup="validateEmail(this);" required autofocus>
						</div>
						
						<div class="form-group has-error">
							<input type="password" class="form-control" name="pass" placeholder="Password" id="password" onkeyup="validatePassword(this);" required autofocus>
						</div>
						
						<div class="form-group has-error">
							<input type="password" class="form-control" placeholder="Retype Password" onkeyup="validatePasswordMatcher(this);" required autofocus>
						</div>
						
						<div class="form-group has-error">
							<label class="checkbox">
								<input type="checkbox" value="agree" onclick="validateTermsOfService(this);">Agree to Terms of Service
							</label>		
						</div>	
						
						<button class="btn btn-lg btn-primary btn-block" type="submit" id="submitButton" disabled>Submit</button>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>

<?php include 'modules/settings/settings.php' ?>
</html>
