<?php

// $CVSHeader: _freebeer/www/_header.php,v 1.3 2004/03/07 19:16:21 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(__FILE__)));

class fbWeb {
	function getDocRoot() {
		static $doc_root = null;

		if (is_null($doc_root)) {
			if (isset($_SERVER['DOCUMENT_ROOT'])) {
				$doc_root = $_SERVER['DOCUMENT_ROOT'];
			} else {
				assert('$_SERVER["PATH_TRANSLATED"]');
				assert('$_SERVER["SCRIPT_NAME"]');
				$a 	= dirname($_SERVER['SCRIPT_NAME']);
				$b	= dirname($_SERVER['PATH_TRANSLATED']);
				if (substr($b, -strlen($a)) == $a) {
					$doc_root = substr($b, 0, strlen($b) - strlen($a));
				}
			}	
		}

		return $doc_root;
	}

	function getWebRoot() {
		static $web_root = null;

		if (is_null($web_root)) {
	//		assert('$_SERVER["SCRIPT_NAME"]');
	//		$web_root = dirname($_SERVER['SCRIPT_NAME']);
			$web_root = dirname(__FILE__);
			if (strpos($web_root, fbWeb::getDocRoot()) === 0) {
				$web_root = substr($web_root, strlen(fbWeb::getDocRoot()));
			}
		}

		return $web_root;
	}
}

?>
