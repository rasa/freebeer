#!/usr/bin/php
<?php

// $CVSHeader: _freebeer/bin/backport_check.php,v 1.1.1.1 2004/01/18 00:12:03 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

error_reporting(2047);
@ini_set('html_errors', false);
@ini_set('track_errors', true);
@ob_implicit_flush(true);
@set_time_limit(0);

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(__FILE__)));

echo 'PHP_OS=',PHP_OS,"\n";

echo "preg_match('^(os\/2|win)/i', PHP_OS)=";
echo preg_match('/^(os\/2|win)/i', PHP_OS);
echo "\n";

echo 'php_uname()=',php_uname(),"\n";

// added in 4.0.6:

echo 'DIRECTORY_SEPARATOR=',DIRECTORY_SEPARATOR,"\n";

// added in 4.1.0:

echo '$_SERVER=';
print_r($_SERVER);

// added in 4.3.0:

echo 'PATH_SEPARATOR=',PATH_SEPARATOR,"\n";

echo 'PHP_SHLIB_SUFFIX=',PHP_SHLIB_SUFFIX,"\n";

?>
