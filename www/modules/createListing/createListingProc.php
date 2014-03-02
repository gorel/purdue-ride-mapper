<!DOCTYPE html>
<html>
	<head>
		<!-- Bootstrap core CSS -->
		<link href="css/bootstrap.css" rel="stylesheet">


		<!-- Custom styles for this template -->
		<link href="signin.css" rel="stylesheet">

		<!-- Custom scripts for the datatimepicker -->
		<link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />
		<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>		
		<script type="text/javascript" src="js/moment.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
	</head>
	<body>
		<h1 align="center">Processing your request...</h1>
		<h2 align="center">You will be redirected once your request has been processed.</h2>

		<?php
			
			function test_input($data)
			{
			   $data = trim($data);
			   $data = stripslashes($data);
			   $data = htmlspecialchars($data);
			   return $data;
			}

			function qualityCode($qualityCode)
			{
				if(strpos($qualityCode,'C') !== false)//If the quality code contains 'C' it is bad
				{
					return false;
				}
				if(strpos($qualityCode,'P1') !== false || strpos($qualityCode,'A5') !== false || strpos($qualityCode,'Z1') !== false)//If the quality code indicates an exact point or city, it's good
				{
					return true;
				}
				return false; //When in doubt, assume it's a bad code
			}
			
			$startingAddress = $destinationAddress = $passengers = $dateTime = $isRequest = "";
			
			$startingAddress = test_input($_POST["startingAddress"]);
			$destinationAddress = test_input($_POST["destinationAddress"]);
			$passengers = test_input($_POST["passengers"]);
			$dateTime = test_input($_POST["dateTime"]);
			$isRequest = test_input($_POST["isRequest"]);
			
			$con=mysqli_connect("localhost", "collegecarpool", "collegecarpool", "purdue_test");
			
			if($isRequest == 1)
			{
				$passengers = 0;
			}
			
			// Check connection
			if (mysqli_connect_errno())
			{
				echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}
			else
			{				
				//THIS IS WHERE THE MAPQUEST API IS CALLED
				//@author Evan Arnold <arnold4@purdue.edu>
				//Sets the start/end lats and longs to their real-world values, and 0.0 if there is bad input (like just a state or gibberish)
				//Start Location:
				$mapquestResult = file_get_contents('http://www.mapquestapi.com/geocoding/v1/address?&key=Fmjtd%7Cluur210znh%2Cb0%3Do5-90ys0a&location=$startingAddress');
				$parsedResult = json_decode($mapquestResult);
				//Only parse result if the input address is good.
				//$addressQualityCode = $parsedResult->results->locations[0]->geocodeQualityCode;
				//if(qualityCode($addressQualityCode) === false)//If the quality code is bad
				//{
				//	//GUYS WHAT DO WE DO IF IT IS BAD?
				//	$startLatitude = 5.0;
				//	$startLongitude = 5.0;
				//}				
				//else//If the quality of the input is good enough
				//{
				$startLatitude = $parsedResult->results[0]->providedLocation->location;//locations[0]->latLng->lat;//Add dat tab
				$startLongitude = $parsedResult->results[0]->locations[0]->latLng->lng;//Add dat tab
				//}

				//EndLocation
				$mapquestResult2 = file_get_contents('http://www.mapquestapi.com/geocoding/v1/address?&key=Fmjtd%7Cluur210znh%2Cb0%3Do5-90ys0a&location=$destinationAddress');
				$parsedResult2 = json_decode($mapquestResult2);
				//Only parse result if the input address is good.
				//$addressQualityCode2 = $parsedResult2->results->locations[0]->geocodeQualityCode;
				//if(qualityCode($addressQualityCode2) === false)//If the quality code is bad
				//{
				//	//GUYS WHAT DO WE DO IF IT IS BAD?
				//	$endLatitude = 5.0;
				//	$endLongitude = 5.0;
				//}				
				//else//If the quality of the input is good enough
				//{
				$endLatitude = $parsedResult2->results[0]->providedLocation->location;//locations[0]->latLng->lat;//Add dat tab
				$endLongitude = $parsedResult2->results[0]->locations[0]->latLng->lng;//Add dat tab
				//}


				//At this point the start and end Latitudes and Longitudes /should/ be correct.... if there was bad input they are 0.0. We need to handle this.//This has been temporarily removed



				$sql="INSERT INTO listings (startingAddress, start_lat, start_long, endingAddress, end_lat, end_long, isRequest, passengers, dateOfDeparture)
				VALUES
				('$startingAddress','$startLatitude','$startLongitude','$destinationAddress','$endLatitude','$endLongitude','$isRequest','$passengers','$dateTime')";

				if (!mysqli_query($con,$sql))
				{
					die('Error: ' . mysqli_error($con));
					mysqli_close($con);
				}
				else
				{
					mysqli_close($con);
					header('Location: ../../index.php');
					exit();
				}		
			}			
		?>
	</body>
</html>
