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

		<!-- Custom styles for this template -->
		<link href="css/justified-nav.css" rel="stylesheet">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
		<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
		<script src="js/gmaps.js"></script>
	</head>

	<body>		
		<script type="text/javascript">
			function hideAll(sender) 
			{				
				document.getElementById('about').parentNode.className = "inactive";
				document.getElementById('contact').parentNode.className = "inactive";
				document.getElementById('findARide').parentNode.className = "inactive";
				document.getElementById('listARide').parentNode.className = "inactive";
				document.getElementById('login').parentNode.className = "inactive";
				document.getElementById('home').parentNode.className = "inactive";
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
						<li><a href="#" id="listARide" onclick="hideAll(this);">Create a Ride</a></li>
						<li><a href="#" id="login" onclick="hideAll(this);">Log In</a></li>
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
						
						<p><a class="btn btn-lg btn-success" id="register" href="#" role="button" onclick="hideAll(this);">Register Now!</a></p>
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
					$("#login").click(function()
					{
						$( "#content" ).load( "modules/signin/signin.php" );
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
					$("#register").click(function()
					{
						$( "#content" ).load( "modules/register/register.php" );
					});
				</script>
				

				<!-- Site footer -->
				<div class="footer">
					<p>&copy; College Carpool 2014</p>
				</div>
			</div>
		</div> <!-- /container -->
	</body>
</html>
