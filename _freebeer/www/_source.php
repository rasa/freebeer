<?php

// $CVSHeader: _freebeer/www/_source.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

error_reporting(2047);

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') : 
 	dirname(dirname(__FILE__)));

require_once FREEBEER_BASE . '/lib/HTTP.php';

// required for Opera 7.x
fbHTTP::sendNoCacheHeaders();

$file = $_REQUEST['file'];

if (!preg_match('/\.(php|js)$/i', $file)) {
	exit;
}

highlight_file($file);

?>
