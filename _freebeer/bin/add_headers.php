#!/bin/sh
exec php $0 ${1+"$@"}
<?php

//if (php_sapi_name() != 'cli') {
//	die "Use CLI version of PHP";
//}

error_reporting(2047);
@ini_set('html_errors', false);
@ini_set('track_errors', true);
@ob_implicit_flush(true);
@set_time_limit(0);

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(__FILE__)));

//if (phpversion() < '5.0') {
//	require_once FREEBEER_BASE . '/lib/Backport.php'; // scandir
//}

//require_once FREEBEER_BASE . '/lib/System.php';
require_once FREEBEER_BASE . '/lib/File.php'; // scandir

$exts = array(
	'cmd'		=> array(
		'prior'		=> '@echo off',
		'start'		=> 3,
		'prefix'	=> ':: ',
		'suffix'		=> '',
	),
	'htm'		=> array(
		'prior'		=> '',
		'start'		=> 3,
		'prefix'	=> '<!-- ',
		'suffix'		=> ' -->',
	),
	'ini'		=> array(
		'prior'		=> '',
		'start'		=> 1,
		'prefix'	=> '# ',
		'suffix'		=> '',
	),
	'js'		=> array(
		'prior'		=> '',
		'start'		=> 1,
		'prefix'	=> '// ',
		'suffix'		=> '',
	),
	'php'		=> array(
		'prior'		=> '<?php',
		'start'		=> 3,
		'prefix'	=> '// ',
		'suffix'		=> '',
	),
	'pl'		=> array(
		'prior'		=> '#!/usr/bin/perl',
		'start'		=> 3,
		'prefix'	=> '# ',
		'suffix'		=> '',
	),
	'po'		=> array(
		'prior'		=> '',
		'start'		=> 2,
		'prefix'	=> '# ',
		'suffix'		=> '',
	),
	'sh'		=> array(
		'prior'		=> '#!/bin/sh',
		'start'		=> 3,
		'prefix'	=> '# ',
		'suffix'		=> '',
	),
	'sql'		=> array(
		'prior'		=> '',
		'start'		=> 1,
		'prefix'	=> '-- ',
		'suffix'		=> '',
	),
	'txt'		=> array(
		'prior'		=> '',
		'start'		=> 1,
		'prefix'	=> '',
		'suffix'		=> '',
	),
);

$exts['bat'] = $exts['cmd'];
$exts['html'] = $exts['htm'];
$exts['pot'] = $exts['po'];

$search1 = array(
	'|^(\S+)\s*\$CVSHeader[^\$]*|i'	=> '$CVSHeader: _freebeer/bin/add_headers.php,v 1.1.1.1 2004/01/18 00:12:03 ross Exp $',
	'|^\s*$|'	=> '',
	'|^(\S+)\s*Copyright.*Ross\s+Smith|i'	=> 'Copyright (c) 2002-2004, Ross Smith.  All rights reserved.',
	'|^(\S+)\s*Licensed\s+under\s+the\s+BSD|i'	=> 'Licensed under the BSD or LGPL License. See license.txt for details.',
);

$files = fbFile::scandir(FREEBEER_BASE, 0, true);

foreach($files as $file) {
	$basename = basename($file);
	$base = $basename;
	$ext = '';
	if (preg_match('/(.+)\.([^\.]+)/', $basename, $matches)) {
			$base = $matches[1];
			$ext = $matches[2];
	}

	$found = false;
	foreach ($exts as $extregex => $extrules) {
		if (preg_match('/' . $extregex . '/', $ext, $matches)) {
				$found = true;
				break;
		}
	}

	if (!$found) {
			continue;
	}

	if (preg_match('~cvs2cl.pl~', $file)) {
		continue;
	}

	if (preg_match('~robots\.txt~', $file)) {
		continue;
	}

	if (preg_match('~/doc/~', $file)) {
		continue;
	}

	if (preg_match('~/etc/~', $file)) {
		continue;
	}

	if (preg_match('~/opt/~', $file)) {
		continue;
	}

	if (preg_match('~/wip/~', $file)) {
		continue;
	}

	if (preg_match('~/jsunit.net/~', $file)) {
		continue;
	}

	printf("\r%s%s", $file, str_repeat(' ', 80 - strlen($file)));

	$lines = file($file);
	if ($lines === false) {
		die(sprintf("Can't open '%s': %s", $file, $php_errormsg));
	}
	$file_tmp = $file . '.tmp';
	$file_bak = $file . '.bak';

	$fp = fopen($file_tmp, 'wb');
	if (!$fp) {
		die(sprintf("Can't create '%s': %s", $file_tmp, $php_errormsg));
	}

	$search_keys = array_keys($search1);
	
	$line_no = -1;
	for ($i = 0; $i < count($lines); ++$i) {
		if (preg_match($search_keys[0], $lines[$i])) {
			$line_no = $i;
			break;
		}
	}

	$start = $extrules['start'] - 1;
	$prefix = $extrules['prefix'];
	$suffix = $extrules['suffix'];

	$search = $search1;	
	foreach ($search as $regex => $replace) {
		if ($replace) {
			$search[$regex] = $prefix . $replace . $suffix;
		}
		$search[$regex] = $search[$regex] .= "\n"; 
	}

	$rewrite = false;

	do {
		if ($line_no == -1) {
			for ($i = 0; $i < count($lines); ++$i) {
				if ($i == $start) {
					$rewrite = true;
					foreach ($search as $regex => $replace) {
						fwrite($fp, $replace);
					}
					fwrite($fp, "\n");
				} 
				fwrite($fp, $lines[$i]);
			}
			break;
		}
	
		$replaces = array_values($search);
		
		for ($i = 0; $i < count($lines); ++$i) {
			if ($i == $line_no) {
					$rewrite = true;
					foreach ($search as $regex => $replace) {
						fwrite($fp, $replace);
					}
					fwrite($fp, "\n");
					$i += count($search);
					continue;
			}
			fwrite($fp, $lines[$i]);
		}

	} while (false);
		
	fclose($fp);
	
	if (!$rewrite) {
		@unlink($file_tmp);
		continue;
	}
	
	printf("\r%s rewritten\n", $file);
	
/*
	if (!@rename($file, $file_bak)) {
		die(sprintf("Can't rename '%s' to '%s': %s", $file, $file_bak, $php_errormsg));
	}
	if (!@rename($file_tmp, $file)) {
		die(sprintf("Can't rename '%s' to '%s': %s", $file_tmp, $file, $php_errormsg));
	}
*/
}

printf("Command completed successfully\n");

exit(0);

?>
