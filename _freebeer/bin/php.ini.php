#!/usr/bin/php
<?php

// $CVSHeader: _freebeer/bin/php.ini.php,v 1.2 2004/03/07 17:51:14 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

error_reporting(2047);
@ini_set('html_errors', false);
@ini_set('track_errors', true);
@ob_implicit_flush(true);
@set_time_limit(0);

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(__FILE__)));

define('PHP5', phpversion() >= '5.0');

if (!PHP5) {
	require_once FREEBEER_BASE . '/lib/Backport.php'; // scandir
}

if (getenv('PHPRC')) {
	$php_dir = strtolower(getenv('PHPRC'));
}

define('OS_WINDOWS', substr(PHP_OS, 0, 3) == 'WIN');

if (OS_WINDOWS) {
	if (!$php_dir) {
		$php_dir = "c:/php";
	}
	$php_dir = str_replace("\\\\", "/", $php_dir);
	$php_dir = str_replace("\\", "/", $php_dir);
} else {
	die(basename(__FILE__) . ' currently only runs on Windows');
}

if (!is_dir($php_dir)) {
	die(sprintf("Directory not found: '%s'", $php_dir));
}

// $php_ini = get_cfg_var('cfg_file_path') ? get_cfg_var('cfg_file_path') : 'php.ini';

$php_ini = $php_dir . '/php.ini';

$php_ini_tmp = $php_dir . '/php.ini.tmp';
$php_ini_bak = $php_dir . '/php.ini.bak';

$php_ini_recommended	= $php_dir . '/php.ini-recommended';
$php_ini_dist			= $php_dir . '/php.ini-dist';
$php_ini_optimized		= $php_dir . '/php.ini-optimized';

if (OS_WINDOWS) {
	$php_exe = $php_dir . '/php.exe';
	$cli_php_exe = $php_dir . '/cli/php.exe';
	$phpcli_exe = $php_dir . '/phpcli.exe';

	$extension_dir = $php_dir . '/extensions';

	if (phpversion() == '4.2.0') {
		copy($php_dir . '/sapi/php.exe', $php_exe);
		copy($php_dir . '/php-cli.exe', $phpcli_exe);
	}

	if (PHP5) {
		$extension_dir = $php_dir . '/ext';
		$php_exe = $php_dir . '/php.exe';
		$cli_php_exe = $php_dir . '/php-win.exe';
		$phpcli_exe = $php_dir . '/php-win.exe';
	}

	$windir = getenv('windir');
	if (!$windir) {
		// PHP 5.0 ucases all env vars
		$windir = getenv('WINDIR');
	}
	$pear_dir = $php_dir . '/pear';

	$save_path = strtolower($windir) . '/temp';
	$save_path = 'c:/temp';
	$save_path = str_replace("\\\\", "/", $save_path);
	$save_path = str_replace("\\", "/", $save_path);
}

if (!@is_dir($pear_dir)) {
	mkdir($pear_dir);
}

echo '$save_path=',$save_path,"\n";
$error_log = $save_path . '/phperror.log';
echo '$error_log=',$error_log,"\n";
//print_r($_SERVER);
//exit;

$excludes = array(
	'php_apd.dll'		=> 'Possibly buggy, research',
	'php_bcompiler.dll'	=> 'Possibly buggy, research',
	'php_blenc.dll'		=> 'PHP quits at startup',
	'php_ifx.dll'		=> 'Requires external isqlt09a.dll',
	'php_ingres.dll'	=> 'Requires external OIAPI.DLL',
	'php_maxdb.dll'		=> 'Requires external libSQLDBC_C.dll',
	'php_netools.dll'	=> 'Requires external lcrzo.dll',
	'php_notes.dll'		=> 'Requires external nNOTES.dll',
	'php_ntuser.dll'	=> 'Possibly buggy, research',
	'php_pdo_oci8.dll'	=> 'Invalid library',
	'php_phpdoc.dll'	=> 'Possibly buggy, research',
	'php_pspell.dll'	=> 'Requires external aspell-15.dll',
	'php_radius.dll'	=> 'Possibly buggy, research',
	'php_snmp.dll'		=> 'Reports: Cannot find module (IP-..): At line 0 in (none)',
	'php_sybase_ct.dll'	=> 'Requires external libct.dll',
	'php_threads.dll'	=> 'Possibly buggy, research',
	'php_tidy.dll'		=> 'Possibly buggy, research',
//	'php_lzf.dll'		=> 'Appears ok',
//	'php_oggvorbis.dll'	=> 'Appears ok',
//	'php_sqlite.dll'	=> 'Appears ok',
);

$builtin = array(
	'5.0.0'	=> array(
		'bcmath'	=> 1,
		'calendar'	=> 1,
		'com_dotnet'=> 1,
		'ctype'		=> 1,
		'dom'		=> 1,
		'ftp'		=> 1,
		'iconv'		=> 1, // new
		'libxml'	=> 1,
		'odbc'		=> 1,
		'pcre'		=> 1,
		'session'	=> 1,
		'SimpleXML'	=> 1, // case
		'SPL'		=> 1, // new
		'SQLite'	=> 1, // case
		'standard'	=> 1,
		'tokenizer'	=> 1,
		'wddx'		=> 1,
		'xml'		=> 1,
		'zlib'		=> 1,
	),
	'5.0.0b2'	=> array(
		'bcmath'	=> 1,
		'calendar'	=> 1,
		'com_dotnet'=> 1,	// new
		'ctype'		=> 1,
		'dom'		=> 1,
		'ftp'		=> 1,
		'libxml'	=> 1,
		'odbc'		=> 1,
		'pcre'		=> 1,
		'session'	=> 1,
		'simplexml'	=> 1,
		'sqlite'	=> 1,
		'standard'	=> 1,
		'tokenizer'	=> 1,
		'wddx'		=> 1,
		'xml'		=> 1,
		'zlib'		=> 1,
	),
	'5.0.0b1'	=> array(
		'bcmath'	=> 1,
		'calendar'	=> 1,
		'ctype'		=> 1,
		'dom'		=> 1,
		'ftp'		=> 1,
		'odbc'		=> 1,
		'pcre'		=> 1,
		'session'	=> 1,
		'simplexml'	=> 1,
		'sqlite'	=> 1,
		'standard'	=> 1,
		'tokenizer'	=> 1,
		'wddx'		=> 1,
		'xml'		=> 1,
		'zlib'		=> 1,
	),
	'4.3.0' => array(
		'bcmath'	=> 1,
		'calendar'	=> 1,
		'com'		=> 1,
		'ctype'		=> 1,
		'ftp'		=> 1,
		'mysql'		=> 1,
		'odbc'		=> 1,
		'overload'	=> 1,
		'pcre'		=> 1,
		'session'	=> 1,
		'standard'	=> 1,
		'tokenizer'	=> 1,
		'wddx'		=> 1,
		'xml'		=> 1,
		'zlib'		=> 1,
	),
	'4.2.0' => array(
		'bcmath'	=> 1,
		'calendar'	=> 1,
		'com'		=> 1,
		'ftp'		=> 1,
		'mysql'		=> 1,
		'odbc'		=> 1,
		'pcre'		=> 1,
		'session'	=> 1,
		'standard'	=> 1,
		'wddx'		=> 1,
		'xml'		=> 1,
	),
	'4.1.0' => array(
		'bcmath'	=> 1,
		'calendar'	=> 1,
		'com'		=> 1,
		'ftp'		=> 1,
		'mysql'		=> 1,
		'odbc'		=> 1,
		'pcre'		=> 1,
		'session'	=> 1,
		'standard'	=> 1,
		'variant'	=> 1,
		'wddx'		=> 1,
		'xml'		=> 1,
	),
	'4.0.5' => array(
		'Calendar'	=> 1,	// case is different
		'bcmath'	=> 1,
		'com'		=> 1,
		'ftp'		=> 1,
		'mysql'		=> 1,
		'odbc'		=> 1,
		'pcre'		=> 1,
		'session'	=> 1,
		'standard'	=> 1,
		'variant'	=> 1,
		'wddx'		=> 1,
		'xml'		=> 1,
	),
);

$builtin['5.0.4'] = $builtin['5.0.0'];
$builtin['5.0.3'] = $builtin['5.0.0'];
$builtin['5.0.2'] = $builtin['5.0.0'];
$builtin['5.0.1'] = $builtin['5.0.0'];

$builtin['5.0.0b3'] = $builtin['5.0.0b2'];

$builtin['4.3.9RC2'] = $builtin['4.3.0'];
$builtin['4.3.8'] = $builtin['4.3.0'];
$builtin['4.3.7'] = $builtin['4.3.0'];
$builtin['4.3.6'] = $builtin['4.3.0'];
$builtin['4.3.5'] = $builtin['4.3.0'];
$builtin['4.3.4'] = $builtin['4.3.0'];
$builtin['4.3.3'] = $builtin['4.3.0'];
$builtin['4.3.2'] = $builtin['4.3.0'];
$builtin['4.3.1'] = $builtin['4.3.0'];

$builtin['4.2.3'] = $builtin['4.2.0'];
$builtin['4.2.2'] = $builtin['4.2.0'];
$builtin['4.2.1'] = $builtin['4.2.0'];

$builtin['4.1.2'] = $builtin['4.1.0'];
$builtin['4.1.1'] = $builtin['4.1.0'];

$builtin['4.0.6'] = $builtin['4.0.5'];

if (!is_dir($extension_dir)) {
	die(sprintf("Directory not found: '%s'", $extension_dir));
}

if (!is_dir($save_path)) {
	die(sprintf("Directory not found: '%s'", $save_path));
}

if (!is_file($php_dir . '/dlls/libmcrypt.dll') && !is_file($extension_dir . '/libmcrypt.dll')) {
	$excludes['php_mcrypt.dll'] = "libmcrypt.dll not found in $php_dir/dlls or $extension_dir";
}

if (!is_file($phpcli_exe)) {
	if (is_file($cli_php_exe)) {
		printf("Copying '%s' to '%s'\n", $cli_php_exe, $phpcli_exe);
		copy($cli_php_exe, $phpcli_exe);
	} else {
		// 4.1.2
		printf("Copying '%s' to '%s'\n", $php_exe, $phpcli_exe);
		copy($php_exe, $phpcli_exe);
	}
}

$src = $php_dir . '/dlls/libmySQL.dll';
$dst = $extension_dir . '/libmySQL.dll';

if (is_file($src) && !is_file($dst)) {
	printf("Copying '%s' to '%s'\n", $src, $dst);
	copy($src, $dst);
}

$fpt = fopen($php_ini_tmp, 'w');

if (!$fpt) {
	die(sprintf("Can't create '%s': %s", $php_ini_tmp, $php_errormsg));
}

if (!is_file($php_ini)) {
	$src = '';
	if (is_file($php_ini_recommended)) {
		$src = $php_ini_recommended;
	} elseif (is_file($php_ini_optimized)) {
		$src = $php_ini_optimized;
	} elseif (is_file($php_ini_dist)) {
		$src = $php_ini_dist;
	}
	if (!$src) {
		die(sprintf("Can't find '%s', '%s', or '%s' to copy to '%s'",
			$php_ini_recommended, $php_ini_optimized, $php_ini_dist));
	}
	printf("Copying '%s' to '%s'\n", $src, $php_ini);

	if (!copy($src, $php_ini)) {
		die(sprintf("Can't copy '%s' to '%s': %s", $src, $php_ini, $php_errormsg));
	}
	fwrite($fpt, sprintf("; Generated for %s from %s via %s on %s\n\n", phpversion(), basename($src), basename(__FILE__), date('r')));
}

$dlls = scandir($extension_dir);
$extensions = array();
foreach ($dlls as $dll) {
	if (preg_match('/^php_\S*\.dll$/i', $dll)) {
		$extensions[$dll] = 0;
	}
}

if (isset($extensions['php_gd.dll']) && isset($extensions['php_gd2.dll']) ) {
	$excludes['php_gd.dll'] = 'Can\'t load both php_gd.dll & php_gd2.dll';
}

if (phpversion() >= '5.0.0') {
	if (isset($extensions['php_exif.dll'])) {
		$excludes['php_exif.dll']		= 'Requires php_mbstring.dll to be loaded first';
	}
	$excludes['php_iisfunc.dll']	= 'Segfaults PHP 5.0.1';
	$excludes['php_yaz.dll']		= 'Entry point not found';
	$excludes['php_imagick.dll']	= 'Entry point not found';
	$excludes['php_stats.dll']		= 'Invalid library (maybe not a PHP library)';
}

if (phpversion() == '4.3.1') {
	$excludes['php_fdf.dll'] = 'Segfaults PHP 4.3.1';
}

if (preg_match('/4.2.3|4.2.2|4.2.1/', phpversion())) {
	$excludes['php_overload.dll'] = 'Invalid library (maybe not a PHP library)';
}

if (preg_match('/4.1.1|4.0.6|4.0.5/', phpversion())) {
	$excludes['php_dotnet.dll'] = 'dotnet: Unable to initialize module';
}

if (phpversion() == '4.1.0') {
	$excludes['php_iisfunc.dll'] = 'Segfaults PHP 4.1.0';
}

if (phpversion() == '4.0.5') {
	$excludes['php_curl.dll'] = 'Requires external MSVCR70.dll';
	$excludes['php_openssl.dll'] = 'Requires external MSVCR70.dll';
}

if (isset($builtin[phpversion()])) {
	foreach($builtin[phpversion()] as $ext => $dummy) {
		$excludes['php_' . strtolower($ext) . '.dll'] = 'Built in to PHP ' . phpversion();
	}
} else {
	printf("No built in extensions defined for PHP %s\n", phpversion());
}

$fp = fopen($php_ini, 'r');

printf("Reading '%s'...\n", $php_ini);

if (!$fp) {
	die(sprintf("Can't open '%s': %s", $php_ini, $php_errormsg));
}

$options = array();

while (!feof($fp)) {
	$line = fgets($fp, 1024);
	if ($line === false) {
		if (feof($fp)) {
			break;
		}
		die(sprintf("Can't read '%s': %s", $php_ini, $php_errormsg));
	}

	if (preg_match('/^\s*allow_call_time_pass_reference\s*/i', $line)) {
		$options['allow_call_time_pass_reference'] = true;
		$line = "allow_call_time_pass_reference = On\n";
	}
	if (preg_match('/^\s*display_errors\s*/i', $line)) {
		$line = "display_errors = On\n";
	}
	if (preg_match('/^\s*error_log\s*/i', $line)) {
		$options['error_log'] = true;
	}
	if (preg_match('/^\s*error_reporting\s*/i', $line)) {
		$line = "error_reporting = E_ALL\n";
	}
	if (preg_match('/^\s*extension_dir\s*/i', $line)) {
		$line = "extension_dir = \"$extension_dir\"\n";
	}
	if (preg_match('/^\s*html_errors\s*/i', $line)) {
		$line = "html_errors = On\n";
	}
	if (preg_match('/^\s*implicit_flush\s*/i', $line)) {
		$line = "implicit_flush = On\n";
	}
	if (preg_match('/^\s*include_path\s*=\s*([^;]*)/i', $line, $matches)) {
		$options['include_path'] = true;
		$include_path = trim($matches[1]);
		if (strcasecmp($include_path, $pear_dir) === false) {
			if ($include_path) {
				$include_path .= ";";
			}
			$include_path .= $pear_dir;
		}
		if (substr($include_path, 0, 1) != '"') {
			$include_path = '"' . $include_path;
		}
		if (substr($include_path, -1, 1) != '"') {
			$include_path .= '"';
		}
		$line = "include_path = $include_path\n";
	}
	if (preg_match('/^\s*register_argc_argv\s*/i', $line)) {
		$line = "register_argc_argv = On\n";
	}

	if (preg_match('/^\s*safe_mode_allowed_env_vars\s*/i', $line, $matches)) {
		$line = "safe_mode_allowed_env_vars = PHP_,TZ,LANG\n";
	}

	if (preg_match('/^\s*session.save_path\s*/i', $line)) {
		$line = "session.save_path = \"$save_path\"\n";
	}
	if (preg_match('/^\s*track_errors\s*/i', $line)) {
		$line = "track_errors = On\n";
	}
	if (preg_match('/^\s*(;*)extension\s*=\s*(\S*)/i', $line, $matches)) {
		$ext = strtolower($matches[2]);
		$fullname = $extension_dir . '/' . $ext;
		if (in_array($ext, array_keys($excludes))) {
			$line = sprintf(";extension = %-20s\t; excluded: {$excludes[$ext]}\n", $ext);
		} elseif (!is_file($fullname)) {
			$line = sprintf(";extension = %-20s\t; not found in $extension_dir\n", $ext);
		} elseif ($extensions[$ext] > 0) {
			$line = sprintf(";extension = %-20s\t; already used\n", $ext);
		} else {
			if (strlen($matches[1]) > 0) {
				$line = sprintf("extension = %-20s\n", $ext);
			}
		}
		@$extensions[$ext]++;
	}

	$bytes = fputs($fpt, $line);
	if ($bytes != strlen($line)) {
		die(sprintf("Can't write to '%s': %s", $php_ini_tmp, $php_errormsg));
	}
}

$send_php = true;

if (!@$options['allow_call_time_pass_reference']) {
	if ($send_php) {
		fputs($fpt, "\n[PHP]\n");
		$send_php = false;
	}
	$line = sprintf("\nallow_call_time_pass_reference = On\n\n", $error_log);
	fputs($fpt, $line);
}

if (!@$options['error_log']) {
	if ($send_php) {
		fputs($fpt, "\n[PHP]\n");
		$send_php = false;
	}
	$line = sprintf("\nerror_log = \"%s\"\n\n", $error_log);
	fputs($fpt, $line);
}

if (!@$options['include_path']) {
	if ($send_php) {
		fputs($fpt, "\n[PHP]\n");
		$send_php = false;
	}
	$line = "include_path = \"$pear_dir\"\n\n";
	fputs($fpt, $line);
}

foreach($extensions as $filename => $count) {
	if ($count > 0) {
		continue;
	}
	if (in_array($filename, array_keys($excludes))) {
		$line = sprintf(";extension = %-20s\t; excluded: {$excludes[$filename]}\n", $filename);
	} else {
		$line = sprintf("extension = %-20s\t; not listed in original php.ini file\n", $filename);
	}
	if ($send_php) {
		fputs($fpt, "\n[PHP]\n");
		$send_php = false;
	}
	fputs($fpt, $line);
}

if (isset($excludes['php_exif.dll']) && isset($extensions['php_mbstring.dll'])) {
	$line = sprintf("\nextension = %-20s\t; %s\n", 'php_exif.dll', $excludes['php_exif.dll']);
	$bytes = fputs($fpt, $line);
	if ($bytes != strlen($line)) {
		die(sprintf("Can't write to '%s': %s", $php_ini_tmp, $php_errormsg));
	}

}

fclose($fp);
fclose($fpt);

@unlink($php_ini_bak);
printf("Updating '%s'...\n", $php_ini);

if (!@rename($php_ini, $php_ini_bak)) {
	die(sprintf("Can't rename '%s' to '%s': %s", $php_ini, $php_ini_bak, $php_errormsg));
}
if (!@rename($php_ini_tmp, $php_ini)) {
	die(sprintf("Can't rename '%s' to '%s': %s", $php_ini_tmp, $php_ini, $php_errormsg));
}
printf("Command completed successfully\n");

exit(0);

?>
