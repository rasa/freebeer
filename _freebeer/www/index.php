<?php

// $CVSHeader: _freebeer/www/index.php,v 1.2 2004/03/07 17:51:33 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

error_reporting(2047);

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(__FILE__)));

require_once './_header.php';

$www_root = getWebRoot();
$doc_root = getDocRoot();
$root_dir = $doc_root . $www_root;

echo html_header_home();

//echo "<pre>";
//echo 'www_root=',$www_root,"\n";
//echo 'doc_root=',$doc_root,"\n";
//echo 'root_dir=',$root_dir,"\n";

$exclude = array(
	'^\.\.?$',
	'^cgi-bin$',
	'^css$',
	'^CVS$',
	'^img$',
	'^lib$',
	'^opt$',
);

$dh = opendir($root_dir);
$files = array();
while ($file = readdir($dh)) {
	$path = $root_dir . '/' . $file;
	if (!is_dir($path)) {
		continue;
	}
	foreach ($exclude as $pattern) {
		if (preg_match("/$pattern/", $file)) {
			continue 2;
		}
	}
	$files[] = $file;
}
closedir($dh);

sort($files);

$body_text = '';

$map = array(
	'demo'			=> 'Demos',
	'doc'			=> 'Developer Documentation (Preliminary)',
	'doxygen'		=> 'PHP Library API Documentation (via doxygen)',
	'phpxref'		=> 'Source Code Cross Reference (via phpxref)',
	'tests'			=> 'PHP Unit Tests',
);

$links = array();

foreach ($files as $file) {
	$text = isset($map[$file]) ? $map[$file] : $file;
	$links[$text] = $www_root . '/' . $file;
}

if (@is_dir($root_dir . '/opt/jsunit.net')) {
	$https = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 's' : '';
	$prefix = 'http' . $https . '://' . $_SERVER['HTTP_HOST'] . $root_dir;
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
$CVSHeader: _freebeer/www/index.php,v 1.2 2004/03/07 17:51:33 ross Exp $
</address>
</body>
</html>
