<?php

// $CVSHeader: _freebeer/www/_header.php,v 1.3 2004/03/07 19:16:21 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(__FILE__)));

function html_header_home($title = null, $included_files = null, $path = null, $no_cache = true) {
	$hash = array('Home' => '');

	if ($title) {
		$hash['Home'] = '/';
		$hash[$title] = '';
	}

	return html_header($hash, $included_files, $path, $no_cache);
}

function html_header_demo($title = null, $included_files = null, $path = null, $no_cache = true) {
	$hash = array('Home' => '/', 'Demos' => '/demo');

	if ($title) {
		$hash[$title] = '';
	}

	return html_header($hash, $included_files, $path, $no_cache);
}


function html_header($hash, $included_files = null, $path = null, $no_cache = true) {
	if (preg_match('/wget/i', $_SERVER['HTTP_USER_AGENT'])) {
		@ini_set('html_errors', false);
	}

	@ini_set('html_errors', false);
	
	include_once FREEBEER_BASE . '/lib/HTTP.php';

	// required for Opera 7.x
	fbHTTP::sendNoCacheHeaders();

	@ini_set('implicit_flush', true);
	@ini_set('max_execution_time', 60);

	$path2 = '';
/*
	if ($path == null) {
		$path = '';
	}
	if (!strpos($path, '://')) {
		$path2 = $path;
		$home_url = $path . '..';
		$demo_url = $path;
	} else {
		$path2 = '';
		$home_url = $path;
		$demo_url = $path;
	}
*/

	$page_title = '';
	$header = '';
	foreach ($hash as $label => $url) {
		if ($page_title) {
			$page_title .= ' &gt; ';
			$header .= ' &gt; ';
		}
		$page_title .= $label;
		$header .= $url ? sprintf("<a href='%s'>%s</a>", $url, $label) : $label;
	}

	$files = get_included_files();
#	$files[] = $_SERVER['SCRIPT_NAME'];

	if (!is_array($included_files)) {
		$included_files = array($included_files);
	}

	static $skip_files = array(
		'HTTP.php',
		'System.php',
		'_demo.php',
		'_header.php',
 	);

	$hfiles = '';
	foreach ($files as $file) {
		$bfile = basename($file);

		if (in_array($bfile, $skip_files)) {
			continue;
		}

		$encfile = urlencode($file);
		$hfiles .= "\n&nbsp;\n<a target='$file' href='/_source.php?file=$encfile'>$bfile</a>";
	}

	foreach ($included_files as $file) {
		$bfile = basename($file);

		$encfile = urlencode($file);
		$hfiles .= "\n&nbsp;\n<a target='$file' href='/{$path2}_source.php?file=$encfile'>$bfile</a>";
	}

	if (preg_match('/wget/i', $_SERVER['HTTP_USER_AGENT'])) {
		$header = '';
		$hfiles = '';
	}

	$html = <<<EOD
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--
Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
Licensed under the BSD or LGPL License. See doc/license.txt for details.
-->
<html lang='en-US' xmlns='http://www.w3.org/1999/xhtml'>
<head>
<title>$page_title</title>
<!-- meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' / -->
<meta name="MSSmartTagsPreventParsing" content="TRUE" /><!-- ! -->
<!--
    <meta name="robots" content="noindex, nofollow" />
    <meta name="googlebot" content="noarchive" />
    <link rel='stylesheet' href='example.css' type='text/css' />
    <style type='text/css'>
    @import 'example.css';
    </style>
    <link rel='icon' href='favicon.png' type='image/png' />
    -->
<script language='JavaScript' type='text/javascript'>
    <!-- // <![CDATA[
        // JavaScript code goes here
    // ]]> -->

</script>
</head>
<body>
<table width='100%' border='0'>
 <tr>
  <td align='left'>
  	$header
  </td>
  <td align='right'>
   $hfiles
  </td>
 </tr>
</table>
<hr />
EOD;

	return $html;
}

?>
