<?php

// $CVSHeader: _freebeer/www/demo/HTTP.PathInfo(1).php,v 1.3 2004/03/08 04:29:18 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

// \todo fix wget's continuously reporting "End of file while parsing headers."
if (preg_match('/wget/i', $_SERVER['HTTP_USER_AGENT'])) {
	echo "<html><body></body></html>";
	exit(0);
}

require_once './_demo.php';

require_once FREEBEER_BASE . '/lib/HTTP/PathInfo.php';

fbHTTP_PathInfo::redirect();

session_start();

echo html_header_demo('HTTP/PathInfo class tester', null, null, false);

$url = $_SERVER['SCRIPT_NAME'];
echo "<a href=\"$url\">$url</a><br />\n";

$url = $_SERVER['REQUEST_URI'];

if (!isset($_SERVER['PATH_INFO'])) {
	$url .= '/PATH_INFO';
}

echo "<a href=\"$url\">$url</a><br />\n";

$url = $_SERVER['REQUEST_URI'];

if (!isset($_SERVER['PATH_INFO'])) {
	$url .= '/param1/value1/param2/value2';
}

echo "<a href=\"$url\">$url</a><br />\n";

echo "<a href=\"get.php\">get.php</a><br />\n";

echo "<pre>";
if (isset($_SESSION)) {
	echo '$_SESSION=';
	ksort($_SESSION);
	print_r($_SESSION);
}

echo '$_SERVER=';
ksort($_SERVER);
print_r($_SERVER);

?>
</pre>
<address>
$CVSHeader: _freebeer/www/demo/HTTP.PathInfo(1).php,v 1.3 2004/03/08 04:29:18 ross Exp $
</address>
</body>
</html>
