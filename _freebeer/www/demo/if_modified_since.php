<?php

// $CVSHeader: _freebeer/www/demo/if_modified_since.php,v 1.2 2004/03/07 17:51:33 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once './_demo.php';

//$html_header = html_header_demo('HTTP If-Modified-Since Header tester', null, null, false);
/*
if (preg_match('/wget/i', $_SERVER['HTTP_USER_AGENT'])) {
	echo "</body></html>";
	exit(0);
}
*/

$if_modified_since = 0;

if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
	$protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';

	$if_modified_since = strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);

//	header($protocol . ' 304 Not Modified');
//	exit;
}

$expires = gmdate('D, d M Y H:i:s \G\M\T', gmdate(time() + 500));
$last_modified = gmdate('D, d M Y H:i:s \G\M\T', gmdate(time() - 500));

header('Expires: ' . $expires);
header('Last-Modified: ' . $last_modified);

header('Cache-Control: private');	// HTTP/1.1
header('Pragma: public');										// HTTP/1.0

//header('Cache-Control: no-store, no-cache, must-revalidate');	// HTTP/1.1
//header('Cache-Control: post-check=0, pre-check=0', false);		// HTTP/1.1
//header('Pragma: no-cache');										// HTTP/1.0

//print $html_header;

print "<pre>";

printf("if_modified_since=%s (%s)\n", $if_modified_since, gmdate('r', $if_modified_since));

echo gmdate('D, d M Y H:i:s \G\M\T', time() + 500),"\n";

echo "apache_request_headers()=";
$a = apache_request_headers(); 
ksort($a);
print_r($a);

echo "apache_response_headers()=";
$a = apache_response_headers(); 
ksort($a);
print_r($a);

echo "\$_SERVER=";
ksort($_SERVER);
print_r($_SERVER);

?>

<address>
$CVSHeader: _freebeer/www/demo/if_modified_since.php,v 1.2 2004/03/07 17:51:33 ross Exp $
</address>

</body>
</html>
