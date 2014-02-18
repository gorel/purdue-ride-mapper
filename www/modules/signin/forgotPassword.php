<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>Password Recovery</title>

		<!-- Bootstrap core CSS -->
		<link href="css/bootstrap.css" rel="stylesheet">

		<!-- Custom styles for this template -->
		<link href="signin.css" rel="stylesheet">
	</head>
	<script>
	var emailValid = false;
	
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
	
	function submitClicked()
	{
		window.alert("Email has been sent to reset your password!");
	}
	</script>
	
	<body>
		<div class="container" >
			<div class="row">
			
				<div class="col-md-4">
				</div>				
				
				<div class="col-md-4">
					<form class="form-signin" role="form" id="registerForm">
						<h2 class="form-signin-heading">Type your email here</h2>
						<div class="form-group has-error" id="test">
							<input type="text" class="form-control" placeholder="Email" onkeyup="validateEmail(this);" required autofocus>
						</div>	
					<button class="btn btn-lg btn-primary btn-block" type="submit" id="submitButton" onlick="submitClicked()">Submit</button>						
					</form>
				</div> <!-- col-md-4 -->
				
			</div> <!-- row -->
		</div> <!-- /container -->
	</body>
</html>
