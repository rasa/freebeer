<?php

// $CVSHeader: _freebeer/lib/Random/GMP.php,v 1.1.1.1 2004/01/18 00:12:05 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

/*!
	\file Random/GMP.php
	\brief 	RNG using gmp_random() function
*/

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(dirname(__FILE__))));

require_once FREEBEER_BASE . '/lib/Random.php';

/*!
	\class fbRandom_GMP
	\brief 	RNG using gmp_random() function
*/
class fbRandom_GMP extends fbRandom {
	function isAvailable() {
		return extension_loaded('gmp');
	}

	/*!
		\param $modulo \c int 0 thru 255
		\return \c int 0 to 255
		\static
	*/
	function _getRandomByte($modulo = 0) {
		if ($modulo == 0) {
			$a = unpack('C*', pack('L', gmp_random(1)));	// untested
			return ord($a[1]);
		}

		assert('$modulo > 0');
		assert('$modulo <= 255');

		$modulo2 = $this->_modulo_map[$modulo];
		$tries = 100;
		while (--$tries > 0) {
			$a = unpack('C*', pack('L2', gmp_random(2)));	// untested
			for ($i = 1; $i < 9; ++$i) {
				$rv = $a[$i] & $modulo2;
				if ($rv < $modulo) {
					return $rv;
				}
			}
		}

		trigger_error('fbRandom_GMP::_getRandomByte(): failed to find a valid value after 100 tries');
	}

	/*!
		\param $length \c int length of string
		\return \c string String of random characters of length $length
		\static
	*/
	function _getRandomString($length) {
		assert('$length >= 0');

		$rv = '';

		$len = 0;
		while ($len < $length) {
			$a = unpack('C*', pack('L2', gmp_random(2)));	// untested
			for ($i = 1; $i < 9; ++$i) {
				$rv .= chr($a[$i] & 0xff);
				if (++$len == $length) {
					return $rv;
				}
			}
		}

		return $rv;
	}

}

?>
