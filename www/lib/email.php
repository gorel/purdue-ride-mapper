<?php

/**
 *
 * Send email using the local smtp server
 *
 * The following functions send emails to users for different purposes
 * (i.e registration, forgot password).
 *
 * @author   Timothy Thong <tthong@purdue.edu>
 * @version  1.0
 */ 

/**
 *
 * These global variables MUST NOT BE CHANGED. 
 * The $headers variable must be passed to
 * mail() for all outgoing mails
 *
 */
$orgEmail = "donotreply@collegecarpool.us";
$tmpEmail = "collegecarpool1869@gmail.com";
$orgName  = "College Carpool";

$headers  = "MIME-Version: 1.0" . "\r\n" .
            "Content-type: text/html; charset=iso-8859-1" . "\r\n" .
            "From: $orgName <$orgEmail>\r\n";

/**
 * 
 * Send a registration mail containing an activation link
 *
 * @param	string $rcpt   new user's email
 * @param	string $fname  user's first name           
 * @param	string $uid    user id
 * @param	string $token  generated token for activation
 *
 */
function sendRegMail($rcpt, $fname, $uid, $token)
{
        global $orgEmail,$orgName, $headers;

        $vlink = "http://collegecarpool.us/modules/register/verify.php?" .
                 "id=$uid&token=$token";

        $subject = "Welcome to College Carpool!";

        $msg = "<html><body>" .
                   "Dear $fname,<br><br>" .
                   "Thank you for joining Purdue Ride Mapper!<br><br>" .
                   "Please activate your account by &nbsp;" . 
                   "visiting the link below:<br><br>" .
                   "<a href=\"$vlink\">$vlink</a><br><br><br>" . 
                   "Purdue Ride Mapper Team" .
                   "</body></html>";

        mail($rcpt, $subject, $msg, $headers);
}

/**
 * 
 * Send a registration mail containing an activation link
 *
 * @param	string $rcpt   new user's email
 * @param	string $fname  user's first name           
 * @param	string $uid    user id
 * @param	string $token  generated token for activation
 *
 */
function sendPwResetMail($rcpt, $link)
{
        global $orgEmail,$orgName, $headers;

        $vlink = "http://collegecarpool.us/modules/signin/changePassword.php?" .
                 "link=$link";

        $subject = "College Carpool Password Reset";

        $msg = "<html><body>" .
                   "Dear user,<br><br>" .
                   "You have requested to reset your password<br><br>" .
                   "Please do so by visiting the linke below:<br><br>" . 
                   "<a href=\"$vlink\">$vlink</a><br><br><br>" . 
                   "Purdue Ride Mapper Team" .
                   "</body></html>";

        mail($rcpt, $subject, $msg, $headers);
}



/**
 * 
 * Send an email to administrators (Contact us)
 *
 * @param	string $from   user's email
 * @param	int    $cat    message category
 * @param	string $msg    message
 *
 */
function sendContactMail($from, $cat, $msg)
{
	global $tmpEmail, $orgName, $headers;

	if ($cat == 0)
		$sub = "Make a listing";
        else if ($cat == 1)
		$sub = "Find a listing";
	else if ($cat == 2)
		$sub = "Other";

        $content = "<html><body>" .
                   "From: $from<br><br>" .
                   $msg .
                   "</body></html>";

	mail($tmpEmail, $sub, $msg, $headers);
}

function sendUserMail($rcpt, $rcpt_name, $msg, $requester, $requester_name, $requester_contact)
{
        global $orgEmail, $orgName, $headers;

        $subject = "You have received a ride request!";
		
        $content = "<html><body>" .
                   "Hi $rcpt_name! <br><br> $requester_name has sent you " .
                   "the following message: <br><br> ---------- <br><br> $msg <br><br> ." .
                   "---------- <br><br> $requester_name has provided the following " .
                   "contact information for you to respond : <br><br> ". 
                   " $requester<br>$requester_contact <br><br>" . "</body></html>";
				   
        mail($rcpt, $subject, $content, $headers);
}

function sendWarningMail($rcpt, $msg, $requester)
{
	global $orgEmail, $orgName, $headers;
	
	$msg = "Here is a description of the offense:<br><br>".$msg.
		"<br><br>Continuing to act in this way may result in a permanent ban of your CollegeCarpool account.";
	
	$subject = "College Carpool Behavior Warning";
	
		$content = "<html><body>".
			"Admin ".$requester." has sent you a warning based on your behavior on CollegeCarpool.us<br><br>".$msg.
			"<br><br>For questions about this warning, reply to ".$requester."</body></html>";
	
	mail($rcpt, $subject, $content, $headers);
}

?>

