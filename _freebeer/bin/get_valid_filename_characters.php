#!/usr/bin/php
<?php

// $CVSHeader: _freebeer/bin/get_valid_filename_characters.php,v 1.1.1.1 2004/01/18 00:12:03 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

error_reporting(2047);
@ini_set('html_errors', false);
@ini_set('track_errors', true);
@ob_implicit_flush(true);
@set_time_limit(0);

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(__FILE__)));

//require_once FREEBEER_BASE . '/lib/File.php';	// scandir()

if (phpversion() < '5.0') {
	require_once FREEBEER_BASE . '/lib/Backport.php'; // scandir
}

for ($i = 32; $i <= 255; ++$i) {
	$c = chr($i);
	$f = '0' . $c . '0';
	$fp = @fopen($f, "w");
	if (!$fp) {
//		printf("%3d %x '%c' '%s'\n", $i, $i, $i, chr($i));
		continue;
	}
	fclose($fp);
	if (!is_file($f)) {
//		printf("%3d %x '%c' '%s'\n", $i, $i, $i, chr($i));
	}
	// @unlink($f);
}

$files = scandir('.');

$case_sensitive		= true;
$case_preserving	= true;

echo "// ",php_uname(),"\n";
echo "// ",date('r'),"\n";
echo '$invalid_chars = "';

for ($i = 32; $i <= 255; ++$i) {
	$c = chr($i);
	$uc = strtoupper($c);
	$f = '0' . $c . '0';
	if (!in_array($f, $files)) {
		if ($uc >= 'A' && $uc <= 'Z') {
			$case_sensitive = false;
			continue;
		}
		printf('\\%c', $i);
		continue;
	}
	@unlink($f);
}

for ($i = 32; $i <= 255; ++$i) {
	$c = chr($i);
	$uc = strtoupper($c);
	if ($uc >= 'A' && $uc <= 'Z') {
		continue;
	}
	$f = '0' . $c . '0';
	if (!in_array($f, $files)) {
		printf('\\%c', $i);
		continue;
	}
}

echo "\";\n";

echo '$case_sensitive = ';
echo $case_sensitive ? 'true' : 'false';
echo ";\n";

echo '$case_preserving = ';
echo $case_preserving ? 'true' : 'false';
echo ";\n";

?>
