#!/usr/bin/php
<?php

// $CVSHeader: _freebeer/bin/make_tests.php,v 1.1.1.1 2004/01/18 00:12:03 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

error_reporting(2047);
@ini_set('html_errors', false);
@ini_set('track_errors', true);
@ob_implicit_flush(true);
@set_time_limit(0);

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(__FILE__)));

// should not be required:
//require_once FREEBEER_BASE . '/lib/Pear/Pear.php';
require_once FREEBEER_BASE . '/lib/File.php';	// scandir()

$file_dir = FREEBEER_BASE . '/lib';
$test_dir = FREEBEER_BASE . '/tests';

$files = fbFile::scandir($file_dir, 0, true);

$test_files = array();

foreach($files as $file) {
	if (!preg_match('/\.php$/i', $file)) {
		continue;
	}
	if (preg_match('/\/tests\//i', $file)) {
		continue;
	}
	$file = strtr($file, "\\", '/');	
	$test_files[$file] = $file;
}

//print_r($test_files);
//exit;

foreach($test_files as $file => $dummy) {
    include_once $file;

	$dirname = dirname($file);
	$basename = basename($file);

//echo "dirname=$dirname\n";
//echo "basename=$basename\n";
	
	$after_lib_name = '';
	$dir = $dirname;
	while (true) {
		$base = basename($dir);
		if ($base == 'lib') {
			break;
		}

//echo "base=$base\n";
		
		if ($after_lib_name) {
			$after_lib_name = $base . '/' . $after_lib_name;
		} else {
			$after_lib_name = $base;
		}

//echo "after_lib_name=$after_lib_name\n";

		$dir = dirname($dir);
	}
	
//echo "after_lib_name=$after_lib_name\n";

//	$base = basename($file, '.php'); // fails in <= 4.0.6
	if (preg_match('/(.*)\.php$/i', $basename, $matches)) {
		$base = $matches[1];
	} else {
		echo "Skipping $basename\n";
		continue;
	}

//	$base = basename($file); // , '.php');
	if ($after_lib_name) {
		$test_prefix = strtr($after_lib_name, '/', '_') . '_' . $base;
	} else {
		$test_prefix = $base;
	}

	$test_prefix = strtr($test_prefix, '-', '_');
	$test_prefix = strtr($test_prefix, '.', '_');
	
//echo "test_prefix=$test_prefix\n";

	$data = file($file);
	$data = join("", $data);
	if (preg_match('/\s*class\s*(\w+)\s*/im', $data, $matches)) {
		$classname = $matches[1];
	} else {
		$classname = 'fb' . $test_prefix;
	}

//echo "classname=$classname\n";
	
	$testname = 'Test_' . $test_prefix;

//echo "testname=$testname\n";

	if ($after_lib_name) {
		$test = $test_dir . '/' . $after_lib_name . '/' . $testname . '.php';
	} else {
		$test = $test_dir . '/' . $testname . '.php';
	}

//echo "test=$test\n";

	if (!@is_dir($test_dir . '/' . $after_lib_name)) {
		fbFile::mkdirs($test_dir . '/' . $after_lib_name, 0777, 000);
	}
	
	$exists = is_file($test);
	if ($exists) {
		$filesize = filesize($test);
	}

	$fp = fopen($test, 'a+b');
	if (!$fp) {
		die("Can't open $test: $php_errormsg");
	}

	if ($after_lib_name) {
		$after_lib_name .= '/';
	}
	
	if (!$exists) {
		$data = <<<EOD
<?php

// \$CVSHeader\$

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/{$after_lib_name}{$basename}';

class _{$testname} extends fbTestCase {

	function _{$testname}(\$name) {
        parent::__construct(\$name);
	}

	function setUp() {
	}

	function tearDown() {
	}

}

?>
EOD;
		fwrite($fp, $data);

	}

	fseek($fp, 0);
	$data = fread($fp, 1000000);
	fseek($fp, 0, SEEK_END);

	$methods = get_class_methods($classname);

	if (!$methods) {
		echo "No methods found for $base\n";
		fclose($fp);
		continue;
	}

	$methods_to_add = array();
	foreach($methods as $method) {
		if (preg_match('/^_/', $method)) {
			continue;
		}

		if (!preg_match("/function\s+test_{$method}/im", $data)) {
			$methods_to_add[$method] = $method;
		}
	}

	ksort($methods_to_add);

	foreach($methods_to_add as $method => $dummy) {
		$data = <<<EOD
<?php /*
	// \\todo Implement test_{$method}_1 in $testname.php
	function test_{$method}_1() {
//		\$o =& new {$base}();
//		\$rv = \$o->{$method}();
//		\$expected = 0;
//		\$this->assertEquals(\$expected, \$rv);
	}
*/ ?>

EOD;
		fwrite($fp, $data);
	}

	fclose($fp);
}

?>
