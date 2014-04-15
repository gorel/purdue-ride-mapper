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
		  function showModal()
		  {
				$('#basicModal').modal('show')
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
				if (document.getElementById("login")) {
					document.getElementById('login').parentNode.className = "inactive";
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
							session_start();
							if (!isset($_SESSION['user']))
							{
								echo '<li><a href="#" id="loginModal" onclick="showModal()">Log In Modal</a></li>';
								echo '<li><a href="#" id="login" onclick="hideAll(this);">Log In</a></li>';
							}
							else
							{
								echo '<li><a href="#" id="manageUsers" onclick="hideAll(this);">Manage Users</a></li>';
								echo '<li><a href="#" id="editListings" onclick="hideAll(this);">My Listings</a></li>';
								echo '<li><a href="#" id="listARide" onclick="hideAll(this);">Create a Ride</a></li>';
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
					$("#loginModal").click(function() {
						$(this).modal();
					});
					$("#editListings").click(function()
					{
						$( "#content" ).load( "modules/editListings/editListings.php" );
					});
					$("#manageUsers").click(function()
					{
						$( "#content" ).load( "modules/manageUsers/manageUsers.php" );
					});
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
		
		<div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&amp;times;</button>
					<h4 class="modal-title" id="myModalLabel">Modal title</h4>
					</div>
					<div class="modal-body">
						<h3>Modal Body</h3>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary">Save changes</button>
				</div>
			</div>
		  </div>
		</div>
	</body>
</html>
