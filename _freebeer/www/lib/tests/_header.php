<?php

// $CVSHeader: _freebeer/www/lib/tests/_header.php,v 1.3 2004/03/08 04:29:18 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') : 
 	dirname(dirname(dirname(dirname(__FILE__)))));

require_once FREEBEER_BASE . '/lib/HTTP.php';

require_once FREEBEER_BASE . '/www/fbWeb.php';

$www_root = fbWeb::getWebRoot();
$doc_root = fbWeb::getDocRoot();
//$root_dir = $doc_root . $www_root;

if (isset($test_name)) {
	$test_name = $www_root . $test_name;
}

fbHTTP::sendNoCacheHeaders();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<html>
<!--
$CVSHeader: _freebeer/www/lib/tests/_header.php,v 1.3 2004/03/08 04:29:18 ross Exp $

Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
Licensed under the BSD or LGPL License. See doc/license.txt for details.
-->
  <head>
    <title><?php echo $test_name ?>Test Suite</title>
    <link rel="stylesheet" type="text/css" href="<?php echo $www_root ?>/opt/jsunit.net/css/jsUnitStyle.css">
<script language="JavaScript" type="text/javascript" src="<?php echo $www_root ?>/opt/jsunit.net/app/jsUnitCore.js"></script>
<?php

if (isset($test_name) && !is_file($doc_root . $test_name)) {
	$file = $doc_root . $test_name;
	echo <<<EOD
</head>
<body>
Skipping test of '$file' as the file does not exist.
</body>
</html>
EOD;
	exit;
}

?>
