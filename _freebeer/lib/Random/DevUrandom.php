<?php

// $CVSHeader: _freebeer/lib/Random/DevUrandom.php,v 1.1.1.1 2004/01/18 00:12:05 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

/*!
	\file Random/DevUrandom.php
	\brief 	Non-blocking RNG for Unix/Linux based systems supporting /dev/urandom
*/

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(dirname(__FILE__))));

require_once FREEBEER_BASE . '/lib/Random/DevRandom.php';

/*!
	\class fbRandom_DevUrandom
	\brief 	Non-blocking RNG for Unix/Linux based systems supporting /dev/urandom
*/
class fbRandom_DevUrandom extends fbRandom_DevRandom {
	/*!
	*/
	var $_file = '/dev/urandom';

	/*!
		Constructor
	*/
	function fbRandom_DevUrandom($seed = null) {
		parent::fbRandom_DevRandom($seed);
	}

	/*!
		\return \c bool
		\static
	*/
	function isAvailable() {
		static $rv = null;
		
		while (is_null($rv)) {
			if (!@is_dir('/dev')) {
				$rv = false;
				break;
			}
			$fp = @fopen('/dev/urandom', 'r');
			if (!$fp) {
				$rv = false;
				break;
			}
			@fclose($fp);
			$rv = true;
		}
		
		return $rv;
	}

}

?>
