<?php

// $CVSHeader: _freebeer/lib/HTTP/PathInfo.php,v 1.1.1.1 2004/01/18 00:12:04 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

/*!
	\file HTTP/PathInfo.php
	\brief PATH_INFO related functions
*/

/*!
	\class fbHTTP_PathInfo
	\brief PATH_INFO related functions

	\static
*/
class fbHTTP_PathInfo {
	/*!
	*/
	function redirect() {
		global $_SERVER, $_SESSION; // < 4.1.0
		
		if (empty($_SERVER['PATH_INFO'])) {
			return;
		}
		
		$a = parse_url($_SERVER['REQUEST_URI']);

		$url = 'http' . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 's' : '') . '://';

		$url .= $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
		$q = false;
		if (!empty($a['query'])) {
			$url .= '?' . $a['query'];
			$q = true;
		}

		session_start();

		$_SESSION['PATH_INFO'] = $_SERVER['PATH_INFO'];
		header('HTTP/1.0 301 Moved Permanently');
		header('Location: ' . $url);
		exit(0);
	}

	/// \todo research using inside ob_handler() like fbHTTPS
	function fixRelativeURL($url) {
		global $_SERVER;	// < 4.1.0

		if (!isset($_SERVER['PATH_INFO']) || !$_SERVER['PATH_INFO']) {
			return $url;
		}

		$a = parse_url($url);	
		if (isset($a['scheme']) && $a['scheme']) {	
			return $url;
		}
		if (isset($a['path']) && substr($a['path'], 0, 1) == '/') {
			return $url;
		}

		$uri = $_SERVER['REQUEST_URI'];

		$q = strpos($uri, '?');
		if ($q === false) {
			$q = strlen($uri);
		}

		$n = strpos($uri, $_SERVER['PATH_INFO'], $q - strlen($_SERVER['PATH_INFO']));
		if ($n === false) {
			trigger_error(sprintf('Can\'t find PATH_INFO (\'%s\') in REQUEST_URI (\'%s\')',
				$_SERVER['PATH_INFO'], $_SERVER['REQUEST_URI']), E_USER_NOTICE);
			return $_SERVER['REQUEST_URI'];
		}

		$uri = substr($_SERVER['REQUEST_URI'], 0, $n);
		$dir = dirname($uri);
		if ($dir != '/') {
			$dir .= '/';
		}
		return $dir . $url;
	}
}

?>
