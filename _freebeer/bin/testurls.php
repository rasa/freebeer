#!/usr/bin/php
<?php

// $CVSHeader: _freebeer/bin/testurls.php,v 1.1.1.1 2004/01/18 00:12:04 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

error_reporting(2047);
@ini_set('html_errors', false);
@ini_set('track_errors', true);
@ob_implicit_flush(true);
@set_time_limit(0);

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(__FILE__)));

if (!extension_loaded('curl')) {
	die(basename(__FILE__) . ' requires the curl extension');
}

if (isset($_ENV['PWD'])) {
	if (getcwd() != $_ENV['PWD']) {
		chdir($_ENV['PWD']);
	}
}

#$user = $_ENV['USER'];

#$domain =  $user . '.example.com';

if (isset($argv[1])) {
	$domain = trim($argv[1]);
}

$urls = file('test.urls');

$count = array(
	'pass' => 0,
	'fail'	=> 0,
);

foreach ($urls as $url) {
	$url = trim($url);

	if (!$url) {
		continue;
	}

	if ($url[0] == '#') {
		continue;
	}

	if ($domain) {
		$parts	= parse_url($url);
		$url	= $parts['scheme'] ? $parts['scheme'] : 'http';
		$url	.= '://' . $domain;
		$url	.= $parts['path'] ? $parts['path'] : '/';
		$url	.= $parts['query'] ? '?' . $parts['query'] : '';
		$url	.= $parts['fragment'] ? '#' . $parts['fragment'] : '';
	}
	
	$ch = curl_init ($url); 

	curl_setopt ($ch, CURLOPT_HEADER, 1); 
	curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1); 
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

	$rv = curl_exec ($ch);

	$errors = array();

	if (preg_match_all('/^HTTP\/1\.\d\s+4\d+.*$/m', $rv, $matches)) {
		$errors[] = $matches[0][0];
	}
	if (preg_match_all('/^HTTP\/1\.\d\s+5\d+.*$/m', $rv, $matches)) {
		$errors[] = $matches[0][0];
	}
	if (preg_match_all('/^.*Assertion Failed.*$/m', $rv, $matches)) {
		$errors[] = $matches[0][0];
	}
	if (preg_match_all('/^.*Fatal error.*$/m', $rv, $matches)) {
		$errors[] = $matches[0][0];
	}
	if (preg_match_all('/^.*\.php.*User Error:.*$/m', $rv, $matches)) {
		$errors[] = $matches[0][0];
	}
	if (preg_match_all('/^.*mercury error.*$/m', $rv, $matches)) {
		$errors[] = $matches[0][0];
	}
	if (preg_match_all('/^.*\.php.*Warning:.*$/m', $rv, $matches)) {
		$errors[] = $matches[0][0];
	}

#print $rv;

	if ($errors) {
		$count['fail']++;
		foreach($errors as $error) {
			echo $url, ":\t",$error,"\n";
		}
		print "\n";
	} else {
		$count['pass']++;
#		echo $url, ":\tOK\n";
	}
	
	curl_close ($ch); 
}

$total = $count['pass'] + $count['fail'];
$pct = $total ? (float) $count['pass'] * 100.0 / (float) $total : 0;
printf("%5d pass\n",	$count['pass']);
printf("%5d fail\n",	$count['fail']);
printf("%5d total\n",	$total);
printf("%5.2f%% passed\n", $pct);
?>
