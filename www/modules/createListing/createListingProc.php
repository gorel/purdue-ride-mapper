<?php
	
	function test_input($data)
	{
	   $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}
	
	function qualityCodeCheck($qualityCode)
	{
		if(strpos($qualityCode,'C') !== false)//If the quality code contains 'C' it is bad
		{
			return false;
		}
		if(strpos($qualityCode,'P1') !== false || strpos($qualityCode,'A5') !== false || strpos($qualityCode,'Z') !== false || strpos($qualityCode,'L1AAA') !== false)//If the quality code indicates an exact point or city, it's good
		{
			return true;
		}
		return false; //When in doubt, assume it's a bad code
	}
	
	function qualityTimeFormat($dateTime)
	{
		$re='((?:2|1)\\d{3}(?:-|\\/)(?:(?:0[1-9])|(?:1[0-2]))(?:-|\\/)(?:(?:0[1-9])|(?:[1-2][0-9])|(?:3[0-1]))(?:T|\\s)(?:(?:[0-1][0-9])|(?:2[0-3])):(?:[0-5][0-9]):(?:[0-5][0-9]))';//Time stamp regex
		if(preg_match('/'.$re.'/is',$dateTime) === 1) 		
		{
			return true;
		}
		else
		{
			return false;
		}
	}	
	
	function qualityTimeRange($dateTime)
	{
		$year = substr($dateTime,0,4);
		$month = substr($dateTime,5,2);
		$day = substr($dateTime,8,2);
		$currentyear = date("Y");
		$currentmonth = date("m");
		$currentday = date("d");
		if($year < $currentyear || $year > ($currentyear+1))//Last year or 2+ years from now = bad
		{
			return false;
		}
		if($year === $currentyear && $month < $currentmonth)//Previous months = bad
		{
			return false;
		}
		if($year === $currentyear && $month === $currentmonth && $day < $currentday)//Previous days = bad
		{
			return false;
		}
		
		return true;
	}

	
	$startingAddress = $destinationAddress = $passengers = $dateTime = $isRequest = $user_id = "";
	
	$startingAddress = test_input($_POST["startingAddress"]);
	$destinationAddress = test_input($_POST["destinationAddress"]);
	$passengers = test_input($_POST["passengers"]);
	$dateTime = test_input($_POST["dateTime"]);
	$isRequest = test_input($_POST["isRequest"]);
	$badInput = 0;
	
				
	session_start();
	$user_id = $_SESSION['user'];
	$con=mysqli_connect("localhost", "collegecarpool", "collegecarpool", "purdue_test");
	
	if($isRequest == 1)
	{
		$passengers = 0;
	}
	
	// Check connection
	if (mysqli_connect_errno())
	{
		echo json_encode(array('success' => "FAILURE1"));
	}
	elseif($startingAddress === "" || $destinationAddress === "" || $dateTime === "" || ($isRequest != 1 && ($passengers == 0 || $passengers === "")))
	{
		echo json_encode(array('success' => "FAILURE2"));
	}
	else
	{				
		//THIS IS WHERE THE MAPQUEST API IS CALLED
		//@author Evan Arnold <arnold44@purdue.edu>
		//Sets the start/end lats and longs to their real-world values, and 0.0 if there is bad input (like just a state or gibberish)
		//Start Location:
		$lookupStartingAddress = str_replace(' ', '%20', $startingAddress);
		$lookupDestinationAddress = str_replace(' ', '%20', $destinationAddress);
		
		//If address is Purdue, add better address.
		if(preg_match('/Purdue(\\s*)University/i',$startingAddress) === 1)
		{
			$lookupStartingAddress = str_replace(' ', '%20', "1275 Third Street West Lafayette Indiana 47906");
		}
		if(preg_match('/Purdue(\\s*)University/i',$destinationAddress) === 1)
		{
			$lookupDestinationAddress = str_replace(' ', '%20', "1275 Third Street West Lafayette Indiana 47906");
		}				
		
		$mapquestResult = file_get_contents("http://www.mapquestapi.com/geocoding/v1/address?&key=Fmjtd%7Cluur210znh%2Cb0%3Do5-90ys0a&location=" . $lookupStartingAddress ."");
		$parsedResult = json_decode($mapquestResult);
		//debug_to_console("test");
		//debug_to_console($parsedResult);
		//Only parse result if the input address is good.
		$addressQualityCode = $parsedResult->results[0]->locations[0]->geocodeQualityCode;
		if(qualityCodeCheck($addressQualityCode) === false)//If the quality code is bad
		{
			//GUYS WHAT DO WE DO IF IT IS BAD?			
			echo json_encode(array('success' => "FAILURE3"));
			$badInput = 1;
			$startLatitude = 0.0;
			$startLongitude = 0.0;
		}				
		else//If the quality of the input is good enough
		{
			$startLatitude = $parsedResult->results[0]->locations[0]->latLng->lat;//Add dat tab
			$startLongitude = $parsedResult->results[0]->locations[0]->latLng->lng;//Add dat tab
		}
		
		//EndLocation
		$mapquestResult2 = file_get_contents("http://www.mapquestapi.com/geocoding/v1/address?&key=Fmjtd%7Cluur210znh%2Cb0%3Do5-90ys0a&location=" . $lookupDestinationAddress ."");
		$parsedResult2 = json_decode($mapquestResult2);
		//Only parse result if the input address is good.
		$addressQualityCode2 = $parsedResult2->results[0]->locations[0]->geocodeQualityCode;
		if(qualityCodeCheck($addressQualityCode2) === false)//If the quality code is bad
		{
			//GUYS WHAT DO WE DO IF IT IS BAD?
			if($badInput != 1)
				echo json_encode(array('success' => "FAILURE4"));	
			$badInput = 1;
			$endLatitude = 0.0;
			$endLongitude = 0.0;
		}				
		else//If the quality of the input is good enough
		{
			$endLatitude = $parsedResult2->results[0]->locations[0]->latLng->lat;//Add dat tab
			$endLongitude = $parsedResult2->results[0]->locations[0]->latLng->lng;//Add dat tab
		}				
		//At this point the start and end Latitudes and Longitudes /should/ be correct.... if there was bad input they are 0.0.
		
		//Check for acceptable time....
		if(qualityTimeFormat($dateTime) === false)
		{
			//Bad!
			if($badInput != 1)
				echo json_encode(array('success' => "FAILURE5"));
			$badInput = 1;
		}
		else
		{
			if(qualityTimeRange($dateTime) === false)
			{
				//Bad!
				if($badInput != 1)
					echo json_encode(array('success' => "FAILURE6"));
				$badInput = 1;
			}
		}

		$sql="INSERT INTO listings (startingAddress, start_lat, start_long, endingAddress, end_lat, end_long, isRequest, passengers, dateOfDeparture, user_id)
		VALUES
		('$startingAddress','$startLatitude','$startLongitude','$destinationAddress','$endLatitude','$endLongitude','$isRequest','$passengers','$dateTime', '$user_id')";
		
		if($badInput === 0)//Only do this if it is good input
		{
			if (!mysqli_query($con,$sql))
			{
				mysqli_close($con);
				echo json_encode(array('success' => "FAILURE7"));
			}
			else
			{
				mysqli_close($con);
				echo json_encode(array('success' => "SUCCESS"));
			}		
		}
	}			
?>
