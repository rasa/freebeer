<?php

// $CVSHeader: _freebeer/lib/Random.php,v 1.2 2004/03/07 17:51:17 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

/*!
	\file Random.php
	\brief Random Number Generator (RNG) abstract class
*/
defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(__FILE__)));

require_once FREEBEER_BASE . '/lib/System.php'; // tempDirectory()

/*!	
	\class fbRandom
	\brief Random Number Generator (RNG) abstract class
	
	Random Number Generator (RNG) abstract class

*/
class fbRandom {
	/*!
		Return if this random class is available or not
		
		\return \c bool \c true if this random class is available, otherwise \c false
		\static
	*/
	function isAvailable() {
		return true;
	}

	/*!
		Return if this random class blocks or not
		
		\return \c bool \c true if this random class blocks, otherwise \c false
		\static
	*/
	function isBlocking() {
		return false;
	}

	/*!
		Return if this random class is seedable or not
		
		\return \c bool \c true if this random class is seedable, otherwise \c false
		\static
	*/
	function isSeedable() {
		return false;
	}

	/*!
		\static
	*/
	function &getInstance($blocking = null, $seed = null) {
        static $instances;

        if (!isset($instances)) {
            $instances = array();
        }

        $signature = serialize(array($blocking, $seed));
        if (!array_key_exists($signature, $instances)) {
			do {
				if (is_null($seed)) {
					if (preg_match('/^win/i', PHP_OS)) {
						include_once FREEBEER_BASE . '/lib/Random/Win32.php';
						if (fbRandom_Win32::isAvailable()) {
							$classname = 'fbRandom_Win32';
							break;
						}
					} else {
						if ($blocking) {
							include_once FREEBEER_BASE . '/lib/Random/DevRandom.php';
							if (fbRandom_DevRandom::isAvailable()) {
								$classname = 'fbRandom_DevRandom';
								break;
							}
						}

						include_once FREEBEER_BASE . '/lib/Random/DevUrandom.php';
						if (fbRandom_DevUrandom::isAvailable()) {
							$classname = 'fbRandom_DevUrandom';
							break;
						}
					}
				}

				include_once FREEBEER_BASE . '/lib/Random/MT_Rand.php';
				$classname = 'fbRandom_MT_Rand';
			} while (false);

            $instances[$signature] = new $classname($seed);

			if (!is_null($seed)) {
				$this = &$instances[$signature];
				$this->setSeed($seed);
			}

        }

        return $instances[$signature];
	}

	/*!
		Return a string of entropy
		
		\return \c string 
		\static
	*/
	function getEntropy() {
		/// \todo would implode('~', ...) be quicker?
		$entropy = 
			microtime() . '~' .
			@getmypid() . '~' . 
			@lcg_value() . '~' .
			@disk_free_space(fbSystem::tempDirectory());

		if (function_exists('getrusage')) {
			$entropy .= '~' . serialize(@getrusage());
		}

		if (function_exists('memory_get_usage')) {
			$entropy .= '~' . serialize(@memory_get_usage());
		}

		if (function_exists('posix_times')) {
			$entropy .= '~' . serialize(@posix_times());
		}

		global $_SERVER; // < 4.1.0

		if (isset($_SERVER)) {
			$entropy .= '~' . serialize($_SERVER);
		}

		return $entropy;
	}

	/*!
		\private
	*/
	var $_modulo_map = array(
//		  0   1   2   3   4   5   6   7   8   9  10  11  12  13  14  15
		  0,  0,  1,  3,  3,  7,  7,  7,  7, 15, 15, 15, 15, 15, 15, 15,
		 31, 31, 31, 31, 31, 31, 31, 31, 31, 31, 31, 31, 31, 31, 31, 31,
		 63, 63, 63, 63, 63, 63, 63, 63, 63, 63, 63, 63, 63, 63, 63, 63,
		 63, 63, 63, 63, 63, 63, 63, 63, 63, 63, 63, 63, 63, 63, 63, 63,
		127,127,127,127,127,127,127,127,127,127,127,127,127,127,127,127,
		127,127,127,127,127,127,127,127,127,127,127,127,127,127,127,127,
		127,127,127,127,127,127,127,127,127,127,127,127,127,127,127,127,
		127,127,127,127,127,127,127,127,127,127,127,127,127,127,127,127,
		255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,
		255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,
		255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,
		255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,
		255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,
		255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,
		255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,
255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,255,
	);

	/*!
		\param $modulo \c int 0 thru 255
		\return \c int if $modulo == 0, return 0 thru 255, otherwise return 0 thru $modulo - 1
		\static
	*/
	function _getRandomByte($modulo = 0) {
		trigger_error('fbRandom::_getRandomByte() has not been overridden in subclass');
	}

	/*!
		\param $length \c int length of string
		\return \c string String of random characters of length $length
		\static
	*/
	function _getRandomString($length) {
		trigger_error('fbRandom::_getRandomString() has not been overridden in subclass');
	}

	/*!
		\param $length \c int
		\return \c string
	*/
	function nextBase64String($length = 1) {
		static $base64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';

		assert('$length >= 0');

		$bytes = $this->_getRandomString($length);

		$rv = '';

		for ($i = 0; $i < $length; ++$i) {
			$rv .= $base64[ord($bytes{$i}) & 0x3f];
		}

		return $rv;
	}


	/*!
		URL compatible Base64 encoding

		This is how PHP encodes the session hash when
		session.hash_bits_per_character is set to '6' in php.ini
		(I'm not sure if the sequence is the same though)
		
		\param $length \c int
		\return \c string
	*/
	function nextURL64String($length = 1) {
		static $url64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-,';

		assert('$length >= 0');

		$bytes = $this->_getRandomString($length);

		$rv = '';

		for ($i = 0; $i < $length; ++$i) {
			$rv .= $url64[ord($bytes{$i}) & 0x3f];
		}

		return $rv;
	}

	/*!
		\param $length \c int
		\return \c string
	*/
	function nextBase95String($length = 1) {
		assert('$length >= 0');

		$rv = '';

		for ($i = 0; $i < $length; ++$i) {
			$rv .= chr($this->_getRandomByte(95) + 32);
		}

		return $rv;
	}

	/*!
		\return \c bool
	*/
	function nextBoolean() {
		return (boolean) ($this->_getRandomByte() & 1);
	}

	/*!
		\return \c double >= 0.0 and < 1.0
	*/
	function nextDouble() {
		static $multipliers = null;
		static $divisor		= null;

		if ($multipliers === null) {
			$multipliers = array();
			$divisor = 0.0;

			for ($i = 0; $i < 8; ++$i) {
				/*
					multipliers:
						2 ^  0:                          1
						2 ^  8:                        256
						2 ^ 16:                     65 536
						2 ^ 24:                 16 777 216
						2 ^ 32:              4 294 967 296
						2 ^ 40:          1 099 511 627 776
						2 ^ 48:        281 474 976 710 656
						2 ^ 56:     72 057 594 037 927 936

					divisor: 	18 519 084 246 547 628 288
				*/
				$multipliers[$i] = floor(pow(2.0, (double) ($i * 8)));
				$divisor += $multipliers[$i] * 256.0;
			}
		}

		$rv = 0.0;

		$bytes = $this->_getRandomString(8);

		for ($i = 0; $i < 8; ++$i) {
			$rv += $multipliers[$i] * (double) ord($bytes{$i});
		}

		$rv /= $divisor;
		if ($rv < 0.0) {
			$rv = -$rv;
		}
		if ($rv >= 1.0) {
			$rv -= 1.0;
		}

		assert('$rv >= 0.0');
		assert('$rv < 1.0');

		return $rv;
	}

	/*!
		\see http://www.taygeta.com/random/gaussian.html
		
		\return \c double
	*/
	function nextGaussian() {
		static $have_y2 = false;
		static $y2;

		if ($have_y2) {
			$have_y2 = false;

			return $y2;
		}

		do {
			$x1 = 2.0 * $this->nextDouble() - 1.0;
			$x2 = 2.0 * $this->nextDouble() - 1.0;
			$w = $x1 * $x1 + $x2 * $x2;
		} while ($w >= 1.0 || $w == 0.0);

		$w = sqrt((-2.0 * log($w)) / $w);
		$y2 = $x2 * $w;
		$have_y2 = true;

		return $x1 * $w; // y1
	}

	/*!
		\param $min \c int
		\param $max \c int
		\return \c int
	*/
	function nextInt($min = null, $max = null) {
		if ($min === null && $max === null) {
			$s = $this->_getRandomString(4);

			$rv = ord($s{0}) | (ord($s{1}) << 8) | (ord($s{2}) << 16) | (ord($s{3}) << 24);

			assert('is_int($rv)');

			return $rv;
		}

		if ($max === null) {
			$max = $min;
			$min = 0;
		}

		$min = (int) $min;
		$max = (int) $max;

		assert('$min <= $max');

		$diff = $max - $min;

		// the difference is 8 bits or less, use getRandomByte()
		if ($diff > 0 && $diff <= 256) {
			$rv = $this->_getRandomByte($diff % 256) + $min;

			assert('is_int($rv)');

			return $rv;
		}

		// $min and $max are the same
		if ($diff == 0) {
			return $min;
		}

		$tries = 100;

		// the difference is 31 bits
		if ($diff < 0) {

			while (--$tries > 0) {
				$s = $this->_getRandomString(4);

				$rv = ord($s{0}) | (ord($s{1}) << 8) | (ord($s{2}) << 16) | (ord($s{3}) << 24);

				assert('is_int($rv)');

				if ($rv >= $min && $rv <= $max) {
					return $rv;
				}
			}

			trigger_error('fbRandom::nextInt(): failed to find a valid value');
			return false;
		}

		// the difference is 9 to 30 bits
		// \todo Can't this be done with integer math?
		$modulo = (int) pow(2.0, floor(log((double) $diff) / log(2.0)));
		if ($modulo < $diff) {
			$modulo *= 2;
		}

		// per Bruce Schneier's "Practical Cryptography" page 183:
		// "The proper way to select a random number in an arbitrary range is to use a trial-and-error approach.
		// To generate a random value 0,...4, we first generate a random value in the range 0,...,7, which we can
		// do since 8 is a power of 2.  If the result is 5 or larger, we throw it away and choose a new random
		// number in the range 0,...,7.  We keep doing this until the result is in the desired range."
		while (--$tries > 0) {
			$s = $this->_getRandomString(4);

			$rv = ord($s{0}) | (ord($s{1}) << 8) | (ord($s{2}) << 16) | (ord($s{3}) << 24);

			assert('is_int($rv)');

			$rv %= $modulo;

			assert('is_int($rv)');

//printf("rv=%x, min=%x\n", $rv, $min);

			if ($rv < $min) {
				$rv += $min;
			}

//			assert('is_int($rv)');

			if ($rv >= $min && $rv <= $max) {
				return $rv;
			}
		}

		trigger_error('fbRandom::nextInt(): failed to find a valid value after 100 tries');

		return false;
	}

	/*!
		\return \c string
	*/
	function nextSalt() {
		static $salt_chars =
			'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789./';

		return
			$salt_chars[$this->_getRandomByte(64)] .
			$salt_chars[$this->_getRandomByte(64)];
	}

	/*!
		\todo should the characters 128-159 be considered "printable"?

		32-126, 160-255, 95 + 96 characters = 191 + 33 = 224 + 32 = 256

		\param $length \c int
		\return \c string
	*/
	function nextPrintableString($length = 1) {
		assert('$length >= 0');

		$rv = '';

		for ($i = 0; $i < $length; ++$i) {
			$c = $this->_getRandomByte(191);

			if ($c > 94) {
				$c += 65;
			} else {
				$c += 32;
			}

			$rv .= chr($c);
		}

		return $rv;
	}

	/*!
		\param $length \c int
		\return \c string a random string of length \c $length without ASCII NULs (0x00)
	*/
	function nextString($length = 1) {
		assert('$length >= 0');

		$rv = '';

		for ($i = 0; $i < $length; ++$i) {
			$rv .= chr($this->_getRandomByte(255) + 1);
		}

		return $rv;
	}

	/*!
		\param $length \c int
		\return \c string a random string of length \c $length with ASCII NULs (0x00)
	*/
	function nextBytes($length = 1) {
		assert('$length >= 0');

		return $this->_getRandomString($length);
	}

	/*!
		\private
	*/
	var $_seed = null;
	
	/*!
		\return \c int the last seed passed to setSeed(), otherwise \c null
	*/
	function getSeed() {
		return $this->$_seed;
	}

	/*!
		\param $seed \c mixed an integer of the new seed, or \c null to generate a new random seed
		\return \c bool \c true if this RNG is seedable, otherwise \c false
	*/
	function setSeed($seed = null) {
		return false;
	}

	/*!
		\return \c int a new random seed
	*/
	function getRandomSeed() {
		return hexdec(substr(md5($this->getEntropy()), -8));
	}

	/*!
		\return \c bool \c true if this RNG is seedable, otherwise \c false
	*/
	function setRandomSeed() {
		return $this->setSeed($this->getRandomSeed());
	}

}

?>
