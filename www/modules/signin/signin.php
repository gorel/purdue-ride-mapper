<!-- Bootstrap core CSS -->
<link href="css/bootstrap.css" rel="stylesheet">

<!-- Custom styles for this template -->
<link href="signin.css" rel="stylesheet">

<hr class="featurette-divider">
<script>
	var emailValid = false;
	var passwordValid = false;
	
	function registerRedirect()
	{
		location.replace("register.php");
	}
	
	function forgotPassword()
	{
		location.replace("forgotPassword.html");
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
			<form class="form-signin" role="form">
				<h2 class="form-signin-heading">Please Sign In</h2>
				
				<div class="form-group has-error">
					<input type="text" class="form-control" placeholder="Email" onkeyup="validateEmail(this)" required autofocus>
				</div>
				
				<div class="form-group has-error">
					<input type="password" class="form-control" placeholder="Password" onkeyup="validatePassword(this)" required>
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
					<button class="btn btn-lg btn-primary btn-block" onclick="forgotPassword" type="text" id="forgotPassword">Forgot Password?</button>
				</div>
			</form>
		</div> <!-- col-md-4 -->
	</div> <!-- row -->
</div> <!-- /container -->
