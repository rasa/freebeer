<?php

// $CVSHeader: _freebeer/www/lib/tests/index.php,v 1.3 2004/03/08 04:29:18 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

error_reporting(2047);

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') : 
 	dirname(dirname(dirname(dirname(__FILE__)))));

// no longer required, right?
// require_once FREEBEER_BASE . '/lib/Pear/Pear.php';

require_once FREEBEER_BASE . '/lib/File.php'; // scandir()

require_once FREEBEER_BASE . '/www/fbWeb.php';

$www_root = fbWeb::getWebRoot();

$rootdir = $www_root . '/lib/tests/';

$files = fbFile::scandir('.', null, true);

$files2 = array();
$files3 = array();
foreach ($files as $file) {
	$file = substr($file, 2);
	/// \todo change to DIRECTORY_SEPARATOR, or remove
	$file = strtr($file, "\\", '/');
	if (!preg_match('/\.(php|htm|html)$/i', $file)) {
		continue;
	}
	if (preg_match('/^index\.php$/i', $file)) {
		continue;
	}
	if (preg_match('/^_.*\.php$/i', $file)) {
		continue;
	}
	$path = $rootdir . $file;
	$files2[$path] = $path;
	$dirname = dirname($file);
	$files3[$dirname][$file] = $path;

} // foreach ($files as $file)

$suites = '';	
$main = '';

$dirs = array_keys($files3);
foreach ($dirs as $dir) {
	$dir2 = strtr($dir, '\\/@.', '____');
	if ($dir2 == '' || $dir2 == '_') {
		$dir2 = 'root';
	}
	$suite_name = $dir2 . '_TestSuite()';
	
	$suite = '';
	foreach ($files3[$dir] as $file => $path) {
		if ($suite) {
			$suite .= "\n";
		}
		$suite .= "\t\tnewsuite.addTestPage(\"$path\");";
	}
	
	if (!$suite) {
		continue;
	}
	
	$suite = <<<EOD

	function {$suite_name} {
		var newsuite = new top.jsUnitTestSuite();
$suite
		return newsuite;
	}
	
EOD;

	if ($main) {
		$main .= "\n";
	}
	$main .= "\t\tnewsuite.addTestSuite($suite_name);";

	$suites .= $suite;

} // foreach ($dirs as $dir)

$main = <<<EOD

	function suite() {
		var newsuite = new top.jsUnitTestSuite();
$main		
		return newsuite;
	}
EOD;

$js_html = $suites . $main . "\n\n";

$file_names = array_keys($files2);
$body_html = "<ol>\n<li>" . join("\n<li>", $file_names) . "\n</ol>\n";
$body_html .= "<p>The JsUnit JavaScript code to execute this test suite is:</p> <pre>" . $js_html;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<html>
  <head>
    <title>Freebeer JavaScript Library Test Suite</title>
    <link rel="stylesheet" type="text/css" href="<?php echo $www_root ?>/opt/jsunit.net/css/jsUnitStyle.css">
<script language="JavaScript" type="text/javascript" src="<?php echo $www_root ?>/opt/jsunit.net/app/jsUnitCore.js"></script>
<script language="JavaScript" type="text/javascript">
<!-- // <![CDATA[
<?php echo $js_html ?>
// ]]> -->
</script>
  </head>

  <body>
    <h1>Freebeer JavaScript Library Test Suite</h1>

    <p>This page contains a suite of tests for testing the
    Freebeer JavaScript Library.</p>

	<p>The following files will be tested:</p>

<?php echo $body_html ?>

  </body>
</html>
