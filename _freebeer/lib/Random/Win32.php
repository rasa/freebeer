<?php

// $CVSHeader: _freebeer/lib/Random/Win32.php,v 1.1.1.1 2004/01/18 00:12:05 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

/*!
	\file Random/Win32.php
	\brief 	RNG using Window's CryptGenRandom() function
	
	This function currently does not work with any released PHP version.
*/

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(dirname(__FILE__))));

require_once FREEBEER_BASE . '/lib/Random.php';

// the win32 extension appears to be highly unstable so let's just use the MT class for new

require_once FREEBEER_BASE . '/lib/Random/MT_Rand.php';

if (!isset($argv[0]) || basename($argv[0]) != basename(__FILE__)) {
	/*!
		\class fbRandom_Win32
		\brief 	RNG using Window's CryptGenRandom() function
	*/
	class fbRandom_Win32 extends fbRandom_MT_Rand {
	}

	return;
}

/*!
	\class fbRandom_Win32_WIP
	\brief 	RNG using Window's CryptGenRandom() function
*/
class fbRandom_Win32_WIP extends fbRandom {
	/*!
		\private
	*/
	var $_provider = null;

	/*!
		\private
	*/
	var $_win32 = null;

	/*!
		Constructor
		
		\param $seed \c int
	*/
	function fbRandom_Win32($seed = null) {
		if (!extension_loaded('Win32 API')) {
			trigger_error('The Win32 API extension (php_w32api.dll) is not loaded');
			return false;
		}
		$win32 = &new win32;
		assert('!is_null($win32)');

		$win32->registerfunction('long CryptAcquireContext (long &provider, long zero, long zero2, long prov_rsa_full, long crypt_verifycontext) From advapi32.dll');
		$win32->registerfunction('long CryptGenRandom (long provider, long size, string &output) From advapi32.dll');
		$win32->registerfunction('long CryptReleaseContext (long provider, long zero) From advapi32.dll');

		// \todo get from MSDN
		static $PROV_RSA_FULL = 1;
		static $CRYPT_VERIFYCONTEXT = 0xf0000000;

		$this->_provider = 0;

		$rv = (bool) $win32->CryptAcquireContext($this->_provider, 0, 0, $PROV_RSA_FULL, $CRYPT_VERIFYCONTEXT);

		if (!$rv) {
			assert('!is_null($this->_provider)');
			$this->_win32 = $win32;
		}

		register_shutdown_function(
			create_function('' , '$this = fbRandom_Win32->getInstance(); $this->__destruct(); return true;' )
		);
	}

	/*!
		return \c void
	*/
	function __destruct() {
		if (!$this->_win32 && !$this->_provider) {
			return;
		}

		$this->_win32->CryptReleaseContext($this->_provider, 0);
		
		$this->_provider = 0;
		$this->_win32 = null;
	}
	
	/*!
		\static
	*/
	function isAvailable() {
		static $rv = null;
		
		if (is_null($rv)) {
			if (!extension_loaded('Win32 API')) {
				$rv = false;
			} else {
				$h = &new fbRandom_Win32();
				$rv = $h->_provider && $h->_win32;
				$h->__destruct();
			}
		}
		
		return $rv;
	}

	/*!
		\param $modulo \c int 0 thru 255
		\return \c int 0 to 255
		\static
	*/
	function _getRandomByte($modulo = 0) {
		if (!$this->_win32 && !$this->_provider) {
			return false;
		}

		$output = "\0";

		$win32 = $this->_win32;
		
		if ($modulo == 0) {
			if (!$win32->CryptGenRandom($this->_provider, 1, $output)) {
				return false;
			}
			return ord($output);
		}

		assert('$modulo > 0');
		assert('$modulo <= 255');

		$modulo2 = $this->_modulo_map[$modulo];

		$tries = 100;
		while (--$tries > 0) {
			if (!$win32->CryptGenRandom($this->_provider, 1, $output)) {
				return false;
			}

			$rv = ord($output) % $modulo2;
			if ($rv < $modulo) {
				return $rv;
			}
		}

		trigger_error('fbRandom_Win32::_getRandomByte(): failed to find a valid value after 100 tries');
	}

	/*!
		\param $length \c int length of string
		\return \c string String of random characters of length $length
		\static
	*/
	function _getRandomString($length) {
		if (!$this->_win32 && !$this->_provider) {
			return false;
		}

		assert('$length >= 0');

		$win32 = $this->_win32;

		$output = str_repeat("\0", $length);
		
		if (!$win32->CryptGenRandom($this->_provider, $length, $output)) {
			return false;
		}

		return $output;
	}

}

/// \todo test win32 code and remove once it's working
if (isset($argv[0]) && basename($argv[0]) == basename(__FILE__)) {
	error_reporting(2047);
	@set_time_limit(0);
	@ob_implicit_flush(true);
	@ini_set('html_errors', false);
	
	if (!extension_loaded('Win32 API')) {
		trigger_error('The Win32 API extension (php_w32api.dll) is not loaded');
		return false;
	}

	$win32 = new win32;
	assert('!is_null($win32)');

/*
	// works in 5.0.0b1, 4.3.4
	$win32->registerfunction('long GetTickCount() from kernel32.dll');
	$rv = $win32->GetTickCount();
	echo "GetTickCount()=$rv\n";

	// doesn't work in 5.0.0b1, 4.3.4
//	$win32->registerfunction("long sndPlaySound (string a, int b) From winmm.dll");
//	$rv = $win32->sndPlaySound("ding.wav", 0);
//	echo "sndPlaySound('ding.wav', 0)=$rv\n";

	// doesn't work in 5.0.0b1, works in 4.3.4
	$win32->registerfunction("long GetUserName (string &a, int &b) From advapi32.dll");
	$len = 255;                   // set the length your variable should have
	$name = str_repeat("\0", $len); // prepare an empty string
	$rv = $win32->GetUserName($name, $len);
	echo "GetUserName()=$rv\n";
	$name = substr($name, 0, $len - 1);
	echo "name='$name' len=$len\n";
*/
	// 5.0b2 doesn't have php_w32api.dll
	// doesn't work in 5.0.0b1, 4.3.4
	$win32->registerfunction("long CryptAcquireContext(int &phProv, string pszContainer, string pszProvider, int dwProvType, int dwFlags) From advapi32.dll");
//	$win32->registerfunction('long CryptGenRandom(long hProv, long dwLen, string &pbBuffer) From advapi32.dll');
//	$win32->registerfunction('long CryptReleaseContext(long hProv, long dwFlags) From advapi32.dll');

	$PROV_RSA_FULL = 1;
	$CRYPT_VERIFYCONTEXT = 0xf0000000;

	$hProv = str_repeat("\0", 256);
	$hProv = 0;
	$szContainer = "\0";
	$szProvider = "\0";
	$rv = (bool) $win32->CryptAcquireContext($hProv, "ross\0", "Microsoft Base Cryptographic Provider v1.0\0", $PROV_RSA_FULL, 0);
	printf("rv=%s hProv=%s\n", $rv, $hProv);
/*
	if ($hProv) {
		register_shutdown_function(
			create_function('' , 'fbRandom_Win32::__destruct(); return true;' )
		);
	}
*/
}

?>
