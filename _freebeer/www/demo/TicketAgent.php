<?php

// $CVSHeader: _freebeer/www/demo/TicketAgent.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

session_cache_limiter('private, must-revalidate');

session_start();

require_once './_demo.php';

if (phpversion() <= '4.1.0') {
	include_once FREEBEER_BASE . '/lib/Backport.php'; // $_SESSION
}

require_once FREEBEER_BASE . '/lib/TicketAgent.php';

echo html_header_demo('fbTicketAgent Class (To stop duplicate form submissions)', null, null, false);

echo <<<EOD
	The fbTicketAgent class is used to disallow resubmitting the same form
	<br />
	(either by clicking the submit button more than once, 
	or clicking the Back button in the browser, and resubmitting the page again).
	<br />
	<br />
EOD;

if (isset($_REQUEST['btnDelete'])) {
	unset($_SESSION['fbTicket']);
	echo "Ticket was deleted<br />";
} else {
	fbTicketAgent::issueTicket();

	if (isset($_REQUEST['text'])) {
		if (!fbTicketAgent::isTicketValid()) {
			echo "Ticket is not valid<br />";
		} else {
			echo "Ticket is valid<br />";
		}

		if (isset($_REQUEST['btnCancel'])) {
			echo "Ticket was not submitted as the form submission was cancelled<br />";
		} else {
			fbTicketAgent::invalidateTicket();
			echo "Ticket has been invalidated<br />";
		}

	}
}

?>
<form method="post" name="frmTicketAgentDemo">
Text Field:
<input type="text" name="text" value="<?php echo @$_REQUEST['text']; ?>" />
<br />
<br />
<input type="submit" name="btnSubmit" value="Submit" />
&nbsp;
Click this button to submit the form.
<br />
<br />
<input type="image" name="imgSubmit" src="/img/php-small-trans-light.gif" />
&nbsp;
Click anywhere on this image to submit the form.
<br />
<br />
Click <a href="#" onclick="return frmTicketAgentDemo.btnSubmit.click();" >here</a>
to submit the form via JavaScript (formName.submitButtonName.click()).
<br />
<br />
Click <a href="#" onclick="return frmTicketAgentDemo.submit();" >here</a>
to submit the form via JavaScript (formName.submit()).
<br />
<br />

<input type="submit" name="btnCancel" value="Cancel" /> 
&nbsp;
Click this button to cancel data entry.

<br />
<br />

<input type="reset" name="btnReset" value="Reset" /> 
&nbsp;
Click this button to reset the form values to their default values.
<br />
<br />

<input type="submit" name="btnDelete" value="Delete" /> 
&nbsp;
Click this button to delete the ticket and start over.

<br />
<br />

Click <a href="<?php echo $_SERVER['PHP_SELF']; ?>">here</a> to restart.
<br />
<br />

</form>
<?php

echo "<pre>";

echo "The current time is:     ", strftime('%c'), "\n";

if (!isset($_REQUEST['btnDelete'])) {
	printf("You have attempted to submit this form %s time(s)", fbTicketAgent::getTicketCount());
	echo "<br />";
	if (fbTicketAgent::getTicketTime()) {
		echo "You received or last used this ticket at ";
		echo strftime("%c", fbTicketAgent::getTicketTime());
		echo " from ";
		echo fbTicketAgent::getTicketIPAddress();
		echo "\n";
	}
	
	echo "The ticket's name is '", fbTicketAgent::getTicketName(), "'\n";
	echo "\n";
	
	echo "The raw ticket is=";
	@print_r(@unserialize(@$_SESSION[fbTicketAgent::_session_field_name()]));
	echo "\n";
}

echo "_REQUEST=";
print_r($_REQUEST);
echo "\n";
echo "_SESSION=";
print_r(@$_SESSION);
echo "\n";
echo "HTTP_SESSION_VARS=";
print_r(@$HTTP_SESSION_VARS);
echo "\n";
echo "_SERVER=";
print_r(@$_SERVER);
?>

</pre>
<address>
$CVSHeader: _freebeer/www/demo/TicketAgent.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $
</address>
</body>
</html>
