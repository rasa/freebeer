<?php

// $CVSHeader: _freebeer/tests/index.php,v 1.2 2004/03/07 17:51:26 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

if (php_sapi_name() != 'cli') {
	echo "You should be using the CLI version of PHP\n";
}

error_reporting(2047);
@ini_set('html_errors', false);
@ini_set('track_errors', true);
@set_time_limit(0);
@ob_implicit_flush(true);

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') : 
	dirname(dirname(__FILE__)));

if (phpversion() < '5.0.2') {
	include_once FREEBEER_BASE . '/lib/Backport.php'; // E_STRICT, fprintf, STDERR
}

// add /opt/pear to path
require_once FREEBEER_BASE . '/lib/Pear/Pear.php';

include_once 'PHPUnit.php';
include_once 'PHPUnit/TestSuite.php';

class fbTest {
	/*!	
		Output errors to stderr

		\static
	*/
	function errorHandler($code, $error, $file, $line, $context) {
		static $error_type = array(
			// do not localize
			E_ERROR				=> 'error',
			E_WARNING			=> 'warning',
			E_PARSE				=> 'parse error',
			E_NOTICE			=> 'notice',
			E_CORE_ERROR		=> 'core error',
			E_CORE_WARNING		=> 'core warning',
			E_COMPILE_ERROR		=> 'compile error',
			E_COMPILE_WARNING	=> 'compile warning',
			E_USER_ERROR		=> 'user error',
			E_USER_WARNING		=> 'user warning',
			E_USER_NOTICE		=> 'user notice',
			E_STRICT			=> 'strict',
		);
		if (error_reporting() == 0) {
			return;
		}

		if ($code == E_STRICT) {
			return;
		}
		$type = isset($error_type[$code]) ? $error_type[$code] : 'unknown error type ' . $code;
		$s = sprintf("%s: %s in %s on line %s\n", ucfirst($type), $error, $file, $line);
		fwrite(STDERR, $s);
	}

	/*!
		Output the "uncatchable" errors
		(E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR and E_COMPILE_WARNING)
		to stderr

		\static
	*/
	function obErrorHandler(&$buffer) {
		static $error_type = array(
			// do not localize
			'Error'				=> E_ERROR,
			'Warning'			=> E_WARNING,
			'Parse error'		=> E_PARSE,
			'Notice'			=> E_NOTICE	,
			'Core error'		=> E_CORE_ERROR,
			'Core warning'		=> E_CORE_WARNING,
			'Compile error'		=> E_COMPILE_ERROR,
			'Compile warning'	=> E_COMPILE_WARNING,
			'User error'		=> E_USER_ERROR,
			'User warning'		=> E_USER_WARNING,
			'User notice'		=> E_USER_NOTICE,
			'Strict'			=> E_STRICT,
		);

		if (preg_match('/^([^:\n]*):\s*(.*)$/im', $buffer, $matches)) {
			if (isset($error_type[$matches[1]])) {
				$code 	= $error_type[$matches[1]];

				if ($code == E_STRICT) {
					return $buffer;
				}
				$error	= $matches[2];

				if (preg_match('/^(.*)in\s*(.*)\s*on line\s*(\d+)$/', $error, $matches)) {
					$error	= $matches[1];
					$file	= $matches[2];					$line	= $matches[3];
				} else {
					$file	= 'Unknown';
					$line	= 0;
				}

				fbTest::errorHandler($code, $error, $file, $line, array());
			}
		}

		return $buffer;
	}

	/*!
		\static
	*/
	function loadTests(&$suite, $dir, $files = null) {
		$dirs = array();
	
		if (is_null($files)) {
			/// \todo change to DIRECTORY_SEPARATOR, or remove
			$dir = strtr($dir, '\\', '/');
			$dh = opendir($dir);
			$files = array();
			while ($file = readdir($dh)) {
				$name = $dir . DIRECTORY_SEPARATOR . $file;
				if (@is_dir($name)) {
					if ($file != '.' && $file != '..') {
						$dirs[] = $name;
					}
					continue;
				}
				if (!preg_match('/test.*\.php$/i', $file)) {
					continue;
				}
				$files[] = $file;
			}
			closedir($dh);

			#$files = array($files[rand(0, count($files) - 1)]);
			#print $files[0]."\n";
		}

		if (!is_array($files)) {
			$files = array($files);
		}

		sort($files);

		foreach ($files as $file) {
			include_once $dir . '/' . $file;

			// 4.0.6 basename() work around
			if (preg_match('/(.*)\.php$/i', $file, $matches)) {
				$file = $matches[1];
			}

			$classname = '_' . basename($file); // , '.php');

			$suite->addTestSuite($classname);
		}

		foreach($dirs as $dir) {
			if (!fbTest::loadTests($suite, $dir, null)) {
					return false;
			}
		}

		return true;
	}
}

$dir = FREEBEER_BASE . '/tests';

$files = null;

/// \todo Can't this be $_SERVER?

if (!isset($argc) && isset($HTTP_SERVER_VARS['argc'])) {
	$argc = $HTTP_SERVER_VARS['argc'];
	$argv = $HTTP_SERVER_VARS['argv'];
}

if (isset($argc) && $argc > 1) {
	$files = $argv;
	array_shift($files); // remove argv[0]
}

$suite = &new PHPUnit_TestSuite();

set_error_handler(array('fbTest', 'errorHandler'));

ob_start(array('fbTest', 'obErrorHandler'));

$rv = fbTest::loadTests($suite, $dir, $files);

$tests = $suite->countTestCases();

$result = &PHPUnit::run($suite);

$s = $result->toString();
$a = explode("\n", $s);
$passed = array();
$failed = array();
for ($i = 0; $i < count($a); ++$i) {
	if (preg_match('/ passed$/', $a[$i])) {
		$passed[] = $a[$i];
	} else {
		$failed[] = $a[$i];
	}
}

ob_end_flush();

// in case the user is not running the phpcli version
if (!defined('STDIN')) {
	define('STDIN',  fopen('php://stdin', 'r'));
	define('STDOUT', fopen('php://stdout', 'r'));
	define('STDERR', fopen('php://stderr', 'r'));
	register_shutdown_function(
		create_function('' , 'fclose(STDIN); fclose(STDOUT); fclose(STDERR); return true;' )
	);
}

if (count($passed)) {
	fprintf(STDOUT, join("\n", $passed));
	fprintf(STDOUT, "\n");
}

if (count($failed)) {
	fprintf(STDERR, join("\n", $failed));
	fprintf(STDERR, "\n");
}

?>
