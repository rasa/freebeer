<?php

// $CVSHeader: _freebeer/lib/Random/DevRandom.php,v 1.1.1.1 2004/01/18 00:12:05 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

/*!
	\file Random/DevRandom.php
	\brief 	Blocking RNG for Unix/Linux based systems supporting /dev/random
*/

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(dirname(__FILE__))));

require_once FREEBEER_BASE . '/lib/Random.php';

/*!
	\class fbRandom_DevRandom
	\brief 	Blocking RNG for Unix/Linux based systems supporting /dev/random
*/
class fbRandom_DevRandom extends fbRandom {
	/*!
	*/
	var $_file = '/dev/random';

	/*!
	*/
	var $_fp = null;

	/*!
		Constructor
	*/
	function fbRandom_DevRandom($seed = null) {
		$trace_errors = ini_get('trace_errors') ? true : @ini_set('trace_errors', true);
		$php_errormsg = '';

		$this->_fp = @fopen($this->_file, 'r');

		if (!$this->_fp) {
			trigger_error(sprintf('Can\'t open %s: %s', $this->_file, $php_errormsg));
		}

		$trace_errors || @ini_set('trace_errors', $trace_errors);
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
			$fp = @fopen('/dev/random', 'r');
			if (!$fp) {
				$rv = false;
				break;
			}
			@fclose($fp);
			$rv = true;
		}
		
		return $rv;
	}

	/*!
		Return if this random class blocks or not
		
		\return \c bool \c true if this random class blocks, otherwise \c false
		\static
	*/
	function isBlocking() {
		return true;
	}

	/*!
		\param $modulo \c int 0 thru 255
		\return \c int 0 to 255
		\static
	*/
	function _getRandomByte($modulo = 0) {
		assert('$this->_fp');

		if ($modulo == 0) {
			return ord(@fread($this->_fp, 1));
		}

		assert('$modulo >= 0');
		assert('$modulo <= 255');

		$modulo2 = $this->_modulo_map[$modulo];

		$tries = 100;
		while (--$tries > 0) {
			$rv = ord(@fread($this->_fp, 1)) & $modulo2;
			if ($rv < $modulo) {
				return $rv;
			}
		}

		trigger_error('Failed to find a valid random number after 100 tries');
	}

	/*!
		\param $length \c int length of string
		\return \c string String of random characters of length $length
		\static
	*/
	function _getRandomString($length) {
		assert('$this->_fp');

		assert('$length >= 0');

		return @fread($this->_fp, $length);
	}

}

?>
