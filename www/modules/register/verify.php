<?php

require '/var/dbcredentials.php';
require '../../lib/hash.php';

/**
 *
 * Verify user id and registration token to activate account
 *
 * @author	Timothy Thong <tthong@purdue.edu>
 * @version	1.0
 *
 */

// do sainty checks

$token    = $_GET["token"];
$uid      = $_GET["id"];

if (preg_match("/[^[a-z0-9]+/", $token))
{
        die;
}

if (preg_match("/[^0-9]+/", $uid))
{
        die;
}

$dbName   = 'purdue_test';

// connect to local db

$conn =  new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_errno) 
{
    echo  $conn->connect_errno . " " . $conn->connect_error;
    die;
}

$stmt = $conn->stmt_init();

$query = "SELECT token FROM unverified_user_tokens WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('d', $uid);
$stmt->execute();
$stmt->store_result();

if (!$stmt->num_rows ) 
{
        echo "Activation link does not exist!";
        die();
}

// compare token from URL and database

$stmt->bind_result($db_token);
$stmt->fetch();
$stmt->close();

if ($db_token == $token) 
{
        $update = "UPDATE users set enabled=1, verified=1 WHERE user_id = ?";
        $stmt = $conn->prepare($update);
        $stmt->bind_param('d', $uid);
        $stmt->execute();
        $stmt->close();

        $delete = "DELETE FROM unverified_user_tokens WHERE user_id = ?";
        $stmt = $conn->prepare($delete);
        $stmt->bind_param('d', $uid);
        $stmt->execute();
        $stmt->close();
}

?>

<!-- Bootstrap core CSS -->

<html>
<style>
.calign { text-align:center };
</style>
<head>
  <link href="/css/bootstrap.css" rel="stylesheet">

  <script type="text/javascript" src="/js/jquery-1.11.0.min.js"></script>
  <script type="text/javascript" src="/js/moment.min.js"></script>
  <script type="text/javascript" src="/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="/js/validation.js"></script>
  <script>

  var time_start = new Date().getTime() / 1000;
  var time_end = time_start + 5;

  /**
   * countdown
   *
   * dynamically change redirecting time
   */
  function countdown()
  {
    time_elapsed = Math.ceil(time_end - time_start);
     if (time_elapsed >= 0)
     {
       console.log("time_elapsed");
       text = "You will be redirected to collegecarpool.us in..." + time_elapsed + " "; 
       $('#msg').text(text);
       $('#msg').append("<img src='/images/load.gif'>"); 
       time_start = new Date().getTime() / 1000;
     } 
     else
       window.location = "/index.php";
  }
  setInterval(function() {countdown(); }, 1000);
  </script>

  </head>
  <body>
    <div class="col-sm-4 col-sm-offset-4">
      <div class="calign">
        Thank you for activating your account!<br>
      </div>
      <div id="msg"class="calign"</div>
      </div>
</div>
<body>
</html>


