<?php

// $CVSHeader: _freebeer/www/demo/HTTP.PathInfo(1).php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

/// \todo move to separate file

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
$CVSHeader: _freebeer/www/demo/HTTP.PathInfo(1).php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $
</address>
</body>
</html>
