<?php

// $CVSHeader: _freebeer/lib/Mhash.php,v 1.2 2004/03/07 17:51:17 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

/*!
	\file Mhash.php
	\brief Emulation of mhash extension functions
*/

/*!
	\class fbMhash
	\brief Emulation of mhash extension functions

	Emulation layer for mhash functions if the mhash extension is not loaded

	\see http://www.php.net/manual/en/function.mhash.php
	\see http://www.ietf.org/rfc/rfc2104.txt

	\static
*/
class fbMhash {
	/*!
		\todo create a object that can process data in chunks a la perl's Digest-HMAC

		\param $hash \c string
		\param $data \c string
		\param $key \c string
		\return \c string
		\static
	*/
	function mhashhex($hash, $data, $key = '') {
		static $mhash_loaded = null;

		if ($mhash_loaded === null) {
			$mhash_loaded = extension_loaded('mhash');
		}

		if ($mhash_loaded) {
			if ($key) {
				return bin2hex(mhash($hash, $data, $key));
			} else {
				return bin2hex(mhash($hash, $data));
			}
		}

		switch ($hash) {
			case MHASH_MD5:

				if (strlen($key) == 0) {
					return md5($data);
				}

				// Source: http://www.php.net/manual/en/function.mhash.php

				// RFC 2104 HMAC implementation for php. Hacked by Lance Rushing
				$b = 64; // byte length for md5
				if (strlen($key) > $b) {
					$key = pack('H*', md5($key));
				}
				$key  = str_pad($key, $b, "\x00");
				$ipad = str_pad('',   $b, "\x36");
				$opad = str_pad('',   $b, "\x5c");
				$k_ipad = $key ^ $ipad ;
				$k_opad = $key ^ $opad;

				return md5($k_opad . pack('H*', md5($k_ipad . $data)));

			case MHASH_SHA1:
				if (!function_exists('sha1')) {
					trigger_error(sprintf('Unsupported mhash type: %s', $hash), E_USER_WARNING);
					return false;
				}

				if (strlen($key) == 0) {
					return sha1($data);
				}

				$b = 64; // byte length for sha1
				// see /usr/src/mhash-0.8.17/lib/mhash.c
				if (strlen($key) > $b) {
					$key = pack('H*', sha1($key));
				}
				$key  = str_pad($key, $b, "\x00");
				$ipad = str_pad('',   $b, "\x36");
				$opad = str_pad('',   $b, "\x5c");
				$k_ipad = $key ^ $ipad ;
				$k_opad = $key ^ $opad;

				return sha1($k_opad . pack('H*', sha1($k_ipad . $data)));

			default:
				trigger_error(sprintf('Unsupported mhash type: %s', $hash), E_USER_WARNING);
				return false;
		}
	}
}

if (!function_exists('sha1')) {
	include_once FREEBEER_BASE . '/Backport/SHA1.php';

	function sha1($str) {
		$sha1 = &new fbSHA1();
		return $sha1->sha1($str);
	}
}

if (!function_exists('mhash')) {

	defined('MHASH_MD5') || define('MHASH_MD5', 0);

	if (function_exists('sha1')) {
		defined('MHASH_SHA1') || define('MHASH_SHA1', 1);
	}

	// \todo support additional mhash functions:

	// define('MHASH_CRC32',	0);
	// define('MHASH_CRC32B',	9);
	// define('MHASH_GOST',		8);
	// define('MHASH_HAVAL128',	13);
	// define('MHASH_HAVAL160',	12);
	// define('MHASH_HAVAL192',	11);
	// define('MHASH_HAVAL224',	10);
	// define('MHASH_HAVAL256',	3);
	// define('MHASH_MD4',		16);
	// define('MHASH_RIPEMD160',5);
	// define('MHASH_SHA256',	17);
	// define('MHASH_TIGER',	7);
	// define('MHASH_TIGER128',	14);
	// define('MHASH_TIGER160',	15);

	/*!
		\param $hash \c string
		\param $data \c string
		\param $key \c string
		\return \c string
		\static
	*/
	function mhash($hash, $data, $key = '') {
		$rv = fbMhash::mhashhex($hash, $data, $key);

		return $rv ? pack('H*', $rv) : $rv;
	}

	/*!
		\return \c int
		\static
	*/
	function mhash_count() {
		static $count = 0;

		if (!$count) {
			$count = 1;

			if (function_exists('sha1')) {
				++$count;
			}
		}

		return $count;
	}

	/*!
		\param $hash \c string
		\return \c void
	
		\static
	*/
	function mhash_get_hash_name($hash) {
		static $hash_name = null;

		if ($hash_name === null) {
			$hash_name = array();
			$hash_name[0] = 'MD5';

			if (function_exists('sha1')) {
				$hash_name[1] = 'SHA1';
			}
		}

	// \todo support additional mhash functions:

	//		0	=> 'CRC32',		# 4
	//		1	=> 'MD5',		# 16
	//		2	=> 'SHA1',		# 20
	//		3	=> 'HAVAL256',	# 32
	//		4	=> '',			# 0
	//		5	=> 'RIPEMD160',	# 20
	//		6	=> '',			# 0
	//		7	=> 'TIGER',		# 24
	//		8	=> 'GOST',		# 32
	//		9	=> 'CRC32B',	# 4
	//		10	=> 'HAVAL224',	# 28
	//		11	=> 'HAVAL192',	# 24
	//		12	=> 'HAVAL160',	# 20
	//		13	=> 'HAVAL128',	# 16
	//		14	=> 'TIGER128',	# 16
	//		15	=> 'TIGER160',	# 20
	//		16	=> 'MD4',		# 16
	//		17	=> 'SHA256',	# 32
	//		18	=> 'ADLER32',	# 4

		return isset($hash_name[$hash]) ? $hash_name[$hash] : false;
	}

	/*!
		\param $hash \c string
		\return \c int
		\static
	*/
	function mhash_get_block_size ($hash) {
		static $hash_size = null;

		if ($hash_size === null) {
			$hash_size = array();
			$hash_size[0] = 16;

			if (function_exists('sha1')) {
				$hash_size[1] = 20;
			}
		}

	// \todo support additional mhash functions:

	//		0	=> 4,	# CRC32
	//		1	=> 16,	# MD5		(supported)
	//		2	=> 20,	# SHA1		(supported)
	//		3	=> 32,	# HAVAL256
	//		4	=> 0,	#
	//		5	=> 20,	# RIPEMD160
	//		6	=> 0,	#
	//		7	=> 24,	# TIGER
	//		8	=> 32,	# GOST
	//		9	=> 4,	# CRC32B
	//		10	=> 28,	# HAVAL224
	//		11	=> 24,	# HAVAL192
	//		12	=> 20,	# HAVAL160
	//		13	=> 16,	# HAVAL128
	//		14	=> 16,	# TIGER128
	//		15	=> 20,	# TIGER160
	//		16	=> 16,	# MD4
	//		17	=> 32,	# SHA256
	//		18	=> 4,	# ADLER32

		return isset($hash_size[$hash]) ? $hash_size[$hash] : false;
	}

	/*!
		\param $hash \c string
		\param $password \c string
		\param $salt \c string
		\param $bytes \c int
		\return \c string
		\static
	*/
	function mhash_keygen_s2k($hash, $password, $salt, $bytes) {
		switch ($hash) {
			case MHASH_MD5:
				// per http://www.php.net/manual/en/function.mhash-keygen-s2k.php
				return substr(pack('H*', md5($salt . $password)), 0, $bytes);

			case MHASH_SHA1:
				if (!function_exists('sha1')) {
					trigger_error(sprintf('Unsupported mhash type: %s', $hash), E_USER_WARNING);
					return false;
				}

				return substr(pack('H*', sha1($salt . $password)), 0, $bytes);

			default:
				trigger_error(sprintf('Unsupported mhash type: %s', $hash), E_USER_WARNING);
				return false;

		}
	}

} // if (!function_exists('mhash'))

?>
