<?php

// $CVSHeader: _freebeer/www/_source.php,v 1.2 2004/03/07 17:51:33 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

error_reporting(2047);

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') : 
 	dirname(dirname(__FILE__)));

require_once FREEBEER_BASE . '/lib/HTTP.php';

require_once './_header.php';

// required for Opera 7.x
fbHTTP::sendNoCacheHeaders();

$file = isset($_REQUEST['file']) ? $_REQUEST['file'] : false;

if (strpos($file, fbWeb::getDocRoot()) !== 0) {
	exit;
}

if (!preg_match('/\.(php|js)$/i', $file)) {
	exit;
}

highlight_file($file);

?>
