<?php

// $CVSHeader: _freebeer/www/demo/HTTP.PathInfo(2).php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

require_once './_demo.php';

require_once FREEBEER_BASE . '/lib/HTTP/PathInfo.php';

echo html_header_demo('HTTP/PathInfo class tester', null, null, false);

/// \todo move to separate file

function _url($url) {
	return sprintf("<a href=\"%s\">%s</a><br />\n", $url, $url);
}

function url($_url) {
	$url = fbHTTP_PathInfo::fixRelativeURL($_url);
	return _url($url);
}

echo "<pre>";

echo "<html>\n<head>\n</head>\n<body>\n";
echo "Absolute:";

echo url('/index.php');
echo "Relative (broken):";
echo _url('index.php');
echo "Relative (fixed):";
echo url('index.php');

$a = $_SERVER;
ksort($a);
print_r($a);

echo date('r');

echo "</body>\n</html>\n";

?>
