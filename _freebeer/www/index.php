<?php

// $CVSHeader: _freebeer/www/index.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

error_reporting(2047);

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(__FILE__)));

require_once './_header.php';

echo html_header_home();

$dir = '/';

$document_root = FREEBEER_BASE . '/www';

$dh = opendir($document_root);
$files = array();
while ($file = readdir($dh)) {
	if (!is_dir($file)) {
		continue;
	}
	if (
		preg_match('/^\.\.?$/', $file) ||
		preg_match('/^cgi-bin$/', $file) ||
		preg_match('/^css$/', $file) ||
		preg_match('/^img$/', $file) ||
		preg_match('/^lib$/', $file) ||
		preg_match('/^opt$/', $file)) {
		continue;
	}
	$files[] = $file;
}
closedir($dh);

sort($files);

$body_text = '';

$map = array(
	'demo'		=> 'Demos',
	'doxygen'	=> 'PHP Library API Documentation (via doxygen)',
	'phpxref'		=> 'Source Code Cross Reference (via phpxref)',
	'tests'			=> 'PHP Unit Tests',
);

$links = array();

foreach ($files as $file) {
	$text = isset($map[$file]) ? $map[$file] : $file;
	$links[$text] = $dir . $file;
}

if (@is_dir($document_root . '/opt/jsunit.net')) {
	$https = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 's' : '';
	$prefix = 'http' . $https . '://' . $_SERVER['HTTP_HOST'];
	$url = $prefix . '/opt/jsunit.net/testRunner.html?testPage=' . $prefix . '/lib/tests/index.php';
	$links['JavaScript Unit Tests'] = $url;
}

ksort($links);

foreach ($links as $text => $url) {
	$body_text .= sprintf("<a href=\"%s\">%s</a><br />\n", $url, $text);
}

echo "<br />";

echo $body_text;

?>
<br />
<address>
$CVSHeader: _freebeer/www/index.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $
</address>
</body>
</html>
