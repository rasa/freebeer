<?php

// $CVSHeader: _freebeer/lib/Random/MT_Rand.php,v 1.1.1.1 2004/01/18 00:12:05 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

/*!
	\file Random/MT_Rand.php
	\brief 	RNG using mt_rand() function
*/

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(dirname(__FILE__))));

require_once FREEBEER_BASE . '/lib/Random.php';

/*!
	\class fbRandom_MT_Rand
	\brief 	RNG using mt_rand() function
*/
class fbRandom_MT_Rand extends fbRandom {
	/*!
		Constructor
	*/
	function fbRandom_MT_Rand($seed = null) {
		if (!is_null($seed)) {
			$this->setSeed($seed);
		}
	}

	/*!
		\param $modulo \c int 0 thru 255
		\return \c int 0 to 255
		\static
	*/
	function _getRandomByte($modulo = 0) {
		if ($modulo == 0) {
			return mt_rand(0, 255);
		}

		assert('$modulo > 0');
		assert('$modulo <= 255');

		$modulo2 = $this->_modulo_map[$modulo];

		$tries = 100;
		while (--$tries > 0) {
			$rv = mt_rand(0, $modulo2);
			if ($rv < $modulo) {
				return $rv;
			}
		}

		trigger_error('fbRandom_MT_Rand::_getRandomByte(): failed to find a valid value after 100 tries');
	}

	/*!
		\param $length \c int length of string
		\return \c string String of random characters of length $length
		\static
	*/
	function _getRandomString($length) {
		assert('$length >= 0');

		static $mt_bytes = null;

		if ($mt_bytes === null) {
			// get number of significant bytes returned by mt_getrandmax()
			// on my system, it's 3 as mt_getrandmax() returns 2147483647 which is 2 ^ 31 - 1
			$mt_bytes = (int) (floor((floor(log((double) mt_getrandmax()) / log(2.0) + 0.1)) / 8.0));
			assert('$mt_bytes >= 1');
		}

		$rv = '';

		$len = 0;
		while ($len < $length) {
			$r = mt_rand();

			for ($i = 0; $i < $mt_bytes; ++$i) {
				$rv .= chr($r & 0xff);
				if (++$len == $length) {
					return $rv;
				}

				$r >>= 8;
			}
		}

		return $rv;
	}

	/*!
		Return if this random class is seedable or not
		
		\return \c bool \c true if this random class is seedable, otherwise \c false
		\static
	*/
	function isSeedable() {
		return true;
	}

	/*!
		\param $seed \c int
		\return \c bool \c true if seedable, otherwise false
		\static
	*/
	function setSeed($seed = null) {
		if (is_null($seed)) {
			$seed = $this->getRandomSeed();
		}
		$seed = (int) $seed;

		if ($seed > mt_getrandmax()) {
			$seed %= mt_getrandmax();
		}

		mt_srand($seed);

		return true;
	}

}

?>
