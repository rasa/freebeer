<?php

// $CVSHeader: _freebeer/lib/HTTP/OutputBuffering.php,v 1.2 2004/03/07 17:51:20 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

/*!
	\file HTTP/OutputBuffering.php
	\brief HTTP Output buffering support class
*/

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(dirname(__FILE__))));

require_once FREEBEER_BASE . '/lib/System.php';

require_once FREEBEER_BASE . '/lib/Pear/Pear.php';

/*!
	\class fbHTTP_OutputBuffering
	\brief HTTP Output buffering support class

	\static
*/
class fbHTTP_OutputBuffering {
	/*!
		\static
	*/
	function init() {
		global $_SERVER; // < 4.1.0
		
		if (fbSystem::isCLI()) {
			// Don't turn on output buffering in CLI mode
			return true;
		}


		if (!isset($_SERVER['HTTP_ACCEPT_ENCODING']) || !$_SERVER['HTTP_ACCEPT_ENCODING']) {
			// The browser doesn't support compression
			return false;
		}
		
		if (preg_match('/(gzip|deflate)/i', $_SERVER['HTTP_ACCEPT_ENCODING'])) {
			// The browser doesn't support compression
			return false;
		}

		include_once 'Net/UserAgent/Detect.php';
		
		$useragent = &new Net_UserAgent_Detect();

		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' && $useragent->isBrowser('ie')) {
			// IE (at least up to 6.01) fails with output buffering over HTTPS
			return false;
		}

		ob_start('ob_gzhandler');
		
		return true;
	}
	
}

?>
