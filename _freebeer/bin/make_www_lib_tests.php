#!/usr/bin/php
<?php

// $CVSHeader: _freebeer/bin/make_www_lib_tests.php,v 1.1.1.1 2004/01/18 00:12:03 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

error_reporting(2047);
@ini_set('html_errors', false);
@ini_set('track_errors', true);
@ob_implicit_flush(true);
@set_time_limit(0);

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(__FILE__)));

require_once FREEBEER_BASE . '/lib/File.php';	// scandir()

$file_dir = FREEBEER_BASE . '/www/lib';
$test_dir = FREEBEER_BASE . '/www/lib/tests';

$files = fbFile::scandir($file_dir, null, true);

//print_r($files);

$test_files = array();

foreach($files as $file) {
	/// \todo use DIRECTORY_SEPARATOR instead of hardcoded /
	$file = strtr($file, '\\', '/');
	if (!preg_match('/\.js$/i', $file)) {
		continue;
	}
	if (preg_match('/jsunit\.net/i', $file)) {
		continue;
	}
	
	$test_files[$file] = substr($file, strlen(FREEBEER_BASE) + 4);
}

print_r($test_files);

foreach($test_files as $file => $www_file) {
	$dirname = dirname($file);
	$base = basename($file); // fails in <= 4.0.6
	if (preg_match('/([^\.]+)\./i', $base, $matches)) {
		$base = $matches[1];
	}
echo "dirname=$dirname base=$base\n";
continue;
	$testname = $base;
	$test = $test_dir . '/' . $testname . '.php';

	$exists = is_file($test);

	$fp = fopen($test, 'a+b');
	if (!$fp) {
		die("Can't open $test: $php_errormsg");
	}

	$data = '';
	
	if ($exists) {
		fseek($fp, 0);
		$data = fread($fp, 10000000);
		fseek($fp, 0, SEEK_END);
	}

	$lines = file($file);
	if (!$lines) {
		echo "No lines read for $file\n";
		fclose($fp);
		continue;
	}		

	$functions = array();
	foreach($lines as $line) {
		if (preg_match("/\s*function\s+([^(]+)\\s*\(/i", $line, $matches)) {
			if (preg_match('/\s+/', $matches[1])) {
				continue;
			}
			$functions[] = $matches[1];
		}
	}

	if (!$functions) {
		echo "No functions found for $file\n";
		fclose($fp);
		continue;
	}

	$functions_to_add = array();
	foreach($functions as $function) {
		if (preg_match('/^_/', $function)) {
			continue;
		}

		if (!preg_match("/function\s+test_{$function}/im", $data)) {
			$functions_to_add[$function] = $function;
		}

	}

	$js_test_code = '';
	
	foreach($functions_to_add as $function => $dummy) {
		$js_test_code .= <<<EOD

/*
	/// \\todo Implement test_{$function}_1 in $www_file
	function test_{$function}_1() {
		var arg1		= '';
		var expected	= '';
		var rv			= {$function}(arg1);
		assertEquals(expected, rv);
	}
*/

EOD;
	}
	
//	if ($new_data) {
//		fwrite($fp, $new_data);
//	}

	$depth = 1;
	
	$freebeer_base = 'dirname(dirname(dirname(dirname(__FILE__))))';
	for ($i = 1; $i < $depth; ++$i) {
		$freebeer_base = 'dirname(' . $freebeer_base . ')';
	}
	
	if (!$exists) {
		$data = <<<EOD
<?php

// \$CVSHeader\$

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	$freebeer_base);

\$test_name = '$www_file';

include_once FREEBEER_BASE . '/www/lib/tests/_header.php';

?>
<script language="JavaScript" type="text/javascript" src="<?php echo \$test_name ?>"></script>
<script language="JavaScript" type="text/javascript">
<!-- // <![CDATA[
$js_test_code
// ]]> -->
</script>
<?php

include_once FREEBEER_BASE . '/www/lib/tests/_footer.php';

?>
EOD;

		fwrite($fp, $data);

	} else {
		$js_test_code = "\n<!--\n" . $js_test_code . "\n-->\n";
		fwrite($fp, $js_test_code);
	}
	
	fclose($fp);
} // foreach($test_files as $file => $www_file)

?>
