<!-- Bootstrap core CSS -->
<link href="css/bootstrap.css" rel="stylesheet">
 
<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="js/moment.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>

<hr class="featurette-divider">
<script>
	var emailValid = false;
	var passwordValid = false;

	function registerRedirect()
	{
		location.replace("modules/register/register.php");
	}

	/**
	* showPwModal()
	*
	* bring up the modal to submit email for resetting password
	*/
	function showPwModal()
	{
		$('#modalPwReset').modal('show');
	}

	/**
	* submitEmail
	*
	* process the email to inform user how to reset the password
	*/
	function submitEmail()
	{
		var elm = document.getElementById('txtEmail');
		var patt = /[A-Za-z0-9]+@([A-Za-z0-9]+\.[A-Za-z0-9])+/i

		if (txtEmail.value.trim() == "") 
		{
			alert("Please enter an email address");
			return;
		}

		if (! patt.test(txtEmail.value)) 
		{
			alert("Please enter a valid email address!");
			return;
		}

		$.ajax ({ 
			type: "POST",
        	    	url: "/modules/signin/forgotPassword.php", 
                    	dataType: 'json',
                    	data: {"email" : txtEmail.value.trim()}, 
		    	success: function(data) {

        	    		if (data.retval == "ERR") 
				{
                			alert("Error: No such email / database connection failed");
        	        		$('#modalPwReset').modal('hide');
					return;
        	    		}

		    		alert("An email has been sent to your account, please check for details");
        	    		$('#modalPwReset').modal('hide');
           		}	
    		}); 
		

	}

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

	function validateForm()
	{
		var button =  document.getElementById('loginButton');
		if(emailValid && passwordValid)
		{
			button.disabled = false;
		}
		else
		{
			button.disabled = true;
		}
	}
</script>

<div class="container" >
	<div class="row">

		<div class="col-md-4">
		</div>

		<div class="col-md-4">
			<form class="form-signin" action="/modules/signin/signinProc.php" method="post" role="form">
				<h2 class="form-signin-heading">Please Sign In</h2>

				<div class="form-group has-error">
					<input type="text" class="form-control" name="email" placeholder="Email" onkeyup="validateEmail(this)" required autofocus>
				</div>

				<div class="form-group has-error">
					<input type="password" class="form-control" name="pass" placeholder="Password" onkeyup="validatePassword(this)" required>
				</div>

				<div class="form-group">
					<label class="checkbox">
						<input type="checkbox" value="remember-me">Remember me
					</label>
				</div>

				<div class="form-group">
					<button class="btn btn-lg btn-primary btn-block" type="submit" id="loginButton" disabled>Sign in</button>
				</div>

				<div class="form-group">
					<button class="btn btn-lg btn-primary btn-block" onclick="registerRedirect()" type = "button" id="registerButton">Register</button>
				</div>

				<div class="form-group">
				</div>
			</form>

			<button class="btn btn-lg btn-primary btn-block" onclick="showPwModal()" type="text" id="pwModalButton">Forgot Password?</button>
		</div> <!-- col-md-4 -->
	</div> <!-- row -->
</div> <!-- /container -->


<!-- Password reset modal -->

<div class="modal fade" id="modalPwReset" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Send Reset Password Link</h4>
      </div>
      <div class="modal-body">
        <b>Email</b>      <input type="text" class="form-control" name="email" id="txtEmail"><br>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onClick="submitEmail()">Submit</button>
      </div>
    </div>
  </div>
</div>

