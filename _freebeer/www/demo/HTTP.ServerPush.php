<?php

// $CVSHeader: _freebeer/www/demo/HTTP.ServerPush.php,v 1.3 2004/03/08 04:29:18 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

// \todo fix wget's continuously reporting "End of file while parsing headers."
if (preg_match('/wget/i', $_SERVER['HTTP_USER_AGENT'])) {
	echo "<html><body></body></html>";
	exit(0);
}

require_once './_demo.php';

require_once FREEBEER_BASE . '/lib/HTTP/ServerPush.php';

$html_header = html_header_demo('fbHTTP_ServerPush Class');

function page_function($push_mode, $seconds = null, $url = null, $loop = 0) {
	$rv = '';
	if ($push_mode == FB_HTTP_SERVER_PUSH) {
		$rv .= "Content-type: text/html\n\n";
	}

	global $html_header;
	
	$spaces = ''; #str_repeat('IESUCKS!', 10000);
	$time = strftime('%c');
	$refresh_method = $push_mode == FB_HTTP_SERVER_PUSH ? 'server push method' : 'HTTP Refresh command';
	$a = $_SERVER;
	ksort($a);
	$s = var_export($a,true);

	$rv .= $html_header;
	$CVSHeader = '$CVSHeader: _freebeer/www/demo/HTTP.ServerPush.php,v 1.3 2004/03/08 04:29:18 ross Exp $';
	$rv .= "The time is $time
<br />
<br />
<i>Refresh <!-- $loop of {$_REQUEST['loops']} --> every {$_REQUEST['seconds']} seconds using $refresh_method</i>
<pre>
<form>
Seconds: <input type='text' name='seconds' value='{$_REQUEST['seconds']}' />
<!-- 
Loops:   <input type='text' name='loops' value='{$_REQUEST['loops']}' />
-->
<input type='submit' value='Submit' />
</form>
$s<!-- $spaces --></pre>
See
<br />
<a target='_blank' href='http://wp.netscape.com/assist/net_sites/pushpull.html'>An Exploration of Dynamic Documents</a>
</p>

<address>
$CVSHeader
</address>
</body>
</html>
";

	return $rv;
}

@$_REQUEST['seconds']	= $_REQUEST['seconds']	? $_REQUEST['seconds']	: 10;
@$_REQUEST['loops']		= $_REQUEST['loops']	? $_REQUEST['loops']	: 10;
#@$_REQUEST['loop']		= $_REQUEST['loop']		? $_REQUEST['loop']		: 0;

#++$_REQUEST['loop'];

#$url = $_SERVER['SCRIPT_NAME'] . "?seconds={$_REQUEST['seconds']}&loops={$_REQUEST['loops']}&loop={$_REQUEST['loop']}";
$url = $_SERVER['SCRIPT_NAME'] . "?seconds={$_REQUEST['seconds']}&loops={$_REQUEST['loops']}";

fbHTTP_ServerPush::push('page_function', $_REQUEST['seconds'], $url, $_REQUEST['loops']);

?>
