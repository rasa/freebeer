<?php

// $CVSHeader: _freebeer/lib/Random/Rand.php,v 1.2 2004/03/07 17:51:23 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

/*!
	\file Random/Rand.php
	\brief 	RNG using rand() function
*/

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(dirname(__FILE__))));

require_once FREEBEER_BASE . '/lib/Random.php';

/*!
	\class fbRandom_Rand
	\brief 	RNG using rand() function
*/
class fbRandom_Rand extends fbRandom {
	/*!
		Constructor
	*/
	function fbRandom_Rand($seed = null) {
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
			return rand(0, 255);
		}

		assert('$modulo > 0');
		assert('$modulo <= 255');

		$modulo2 = $this->_modulo_map[$modulo];

		$tries = 100;
		while (--$tries > 0) {
			$rv = rand(0, $modulo2);
			if ($rv < $modulo) {
				return $rv;
			}
		}

		trigger_error('fbRandom_Rand::_getRandomByte(): failed to find a valid value after 100 tries');
	}

	/*!
		\param $length \c int length of string
		\return \c string String of random characters of length $length
		\static
	*/
	function _getRandomString($length) {
		assert('$length >= 0');

		static $bytes = null;

		if ($bytes === null) {
			// get number of significant bytes returned by getrandmax()
			// on my system, it's 1 as getrandmax() returns 65535 which is 2 ^ 16 - 1
			$bytes = (int) (floor((floor(log((double) getrandmax()) / log(2.0) + 0.1)) / 8.0));
			assert('$bytes >= 1');
		}

		$rv = '';
		$len = 0;

		if ($bytes == 1) {
			while ($len++ < $length) {
				$rv .= chr(rand() & 0xff);
			}
			return $rv;
		}
		
		while ($len < $length) {
			$r = rand();

			for ($i = 0; $i < $bytes; ++$i) {
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

	/**
		\param $seed \c int
		\return \c bool \c true if seedable, otherwise false
	*/
	function setSeed($seed = null) {
		if (is_null($seed)) {
			$seed = $this->getRandomSeed();
		}
		$seed = (int) $seed;

		if ($seed < 0) {
				$seed = abs($seed);
		}
		if ($seed > getrandmax()) {
			$seed %= getrandmax();
		}

		srand($seed);

		return true;
	}

}

?>
