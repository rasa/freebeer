<?php

// $CVSHeader: _freebeer/lib/HTTP.php,v 1.1.1.1 2004/01/18 00:12:04 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

/*!
	\file HTTP.php
	\brief HTTP protocol related functions
*/

/*!
	\class fbHTTP
	\brief HTTP protocol related functions
	
	\todo 404 page to optionally email webmaster if 404 request has a referrer

	\static
*/
class fbHTTP {
	/*!
		per http://forums.devshed.com/archive/6/2002/07/2/38967 :

			Perl Forums

			Thread: complications in determining *real* IP

			Doucette REMOTE_ADDR gives you the IP, but a lot of the time these are proxies.
			I found out that I can (sometimes) get the 'real' IP from HTTP_X_FORWARDED_FOR and HTTP_CLIENT_IP.

			Here are some interesting facts:

			- Out of a sample of ~7000 hits on my server, HTTP_X_FORWARDED_FOR was available 14% of the time,
			HTTP_CLIENT_IP 2%, (and REMOTE_ADDR 100%.)

			- There were instances in which HTTP_X_FORWARDED_FOR existed and HTTP_CLIENT_IP did not, vice-versa,
			and also instances in which they both existed.

			- Sometimes HTTP_X_FORWARDED_FOR has more than one IP listed (seperated by commas like this:
			"111.111.111.111, 222.222.222.222".)
			When this happened, somtimes HTTP_CLIENT_IP existed, and sometimes not, there was no correlation.

			- Sometimes HTTP_X_FORWARDED_FOR has values in these formats:
			"unknown", "unknown, unknown", "111.111.111.111, unknown", and "unknown, 111.111.111.111", 
			which your script must check for.

			The only thing I know is, when only REMOTE_ADDR exists, I use that as the IP... and I'm assuming that
			when only REMOTE_ADDR and only one of HTTP_X_FORWARDED_FOR and HTTP_CLIENT_IP exist,
			I have to use the one that exists as the IP.

			LOTS OF QUESTIONS:

			1) When both HTTP_X_FORWARDED_FOR and HTTP_CLIENT_IP exist, which do I use?

			2) When only HTTP_X_FORWARDED_FOR exists, but it contains two IPs, which do I use?

			3) When both HTTP_X_FORWARDED_FOR and HTTP_CLIENT_IP exist, but HTTP_X_FORWARDED_FOR contains two IPs, 
			which do I use?

			4) Some IPs returned in HTTP_X_FORWARDED_FOR start with "192.".
			Isn't this an internal IP which should be ignored as they are shared by many users? 
			What other ranges of IPs are internal?

			While I'm on the topic:

			5) Does anyone know if HTTP_VIA or HTTP_FORWARDED can help me out?
			(They were respectively available 17% and 0.5% of the time in ~7000 hits to my server.)

			If anyone knows these variables in detail, please explain them to me!
			I can not find any detailed information on them.

		\return The remote IP address of the user, if available,
		otherwise \c false.
		\static
	*/
	function getRemoteAddress() {
		global $_SERVER; // < 4.1.0

		static $rv = null;
		
		while (is_null($rv)) {
			if (isset($_SERVER['HTTP_CLIENT_IP'])) {
				$ip = trim($_SERVER['HTTP_CLIENT_IP']);
				if (strcasecmp($ip, 'unknown')) {
					$ip2 = explode('.', $ip);
					$rv = $ip2[3] . '.' . $ip2[2] . '.' . $ip2[1] . '.' . $ip2[0];
					if (fbHTTP::getIPAddressType($rv) == 'external') {
						break;
					}
				}
			}

			if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$ips = trim($_SERVER['HTTP_X_FORWARDED_FOR']);
				while (preg_match('/([^, ]+)[, ]+(.*)/', $ips, $matches)) {
					$rv = trim($matches[1]);
					if (strcasecmp($ip, 'unknown')) {
						if (fbHTTP::getIPAddressType($rv) == 'external') {
							break 2;
						}
					}
					$ips = @$matches[2];
				}
			}

			if (isset($_SERVER['HTTP_FORWARDED'])) {
				$ips = trim($_SERVER['HTTP_FORWARDED']);
				while (preg_match('/([^, ]+)[, ]+(.*)/', $ips, $matches)) {
					$rv = trim($matches[1]);
					if (strcasecmp($ip, 'unknown')) {
						if (fbHTTP::getIPAddressType($rv) == 'external') {
							break 2;
						}
					}
					$ips = @$matches[2];
				}
			}

			if (isset($_SERVER['REMOTE_ADDR'])) {
				$rv = trim($_SERVER['REMOTE_ADDR']);
				break;
			}

			$rv = false;
			break;
		}
		
		return $rv;
	}

	/*!
		Get the type of IP address

		\todo use constants instead? FB_IP_ADDRESS_TYPE_LOOPBACK _INTERNAL _EXTERNAL _MULTICAST _RESERVED
		\return string 'loopback', 'internal', 'external', 'reserved', or false if address is invalid
		\static
	*/
	function getIPAddressType($ip) {
		$l = ip2long($ip);
		if ($l == -1 && $ip != '255.255.255.255') {
			return false;
		}

		$tuple1 = $l >> 24;
		if ($tuple1 < 0) {
			$tuple1 += 256;
		}

		if ($tuple1 == 127) {
			return 'loopback';
		}

		if ($tuple1 == 10) {
			return 'internal';
		}

		$tuple2 = ($l >> 16) & 0xff;

		if ($tuple1 == 172 && ($tuple2 >= 16 && $tuple2 <= 31)) {
			return 'internal';
		}

		if ($tuple1 == 192 && $tuple2 == 168) {
			return 'internal';
		}
		
		if ($tuple1 >= 224 && $tuple1 <= 239) {
			return 'reserved';
		}

		// \todo List *all* reserved addresses?
		
		return 'external';
	}
		
	/*!
		Redirect the client to the url \c $url
		Appends SID to url if \c session.use_trans_sid is \c true

		\return void (function exits, so it never returns)
		\static
	*/
	function redirect($url, $append_sid = true) {
		$url = str_replace('&amp;', '&', $url);

		if ($append_sid && (ini_get('session.use_trans_sid') || !session_id() || strstr($url, session_id()))) {
			$sid = session_name() . '=' . session_id();

			if (strstr($url, '?') && !strstr($url, $sid)) {
				$url .= '&';
			} else {
				$url .= '?';
			}
			$url .= $sid;
		}

		header('Location: ' . $url . "\n\n");
		exit();
	}

	/*!
		Usage:

		$varname = fbHTTP::getRequestVar('varname', 'default value');

		\param $key \c string
		\param $default mixed
		\return mixed $var if defined, otherwise $default
		\static
	*/
	function getRequestVar($key, $default = null) {
		global $_REQUEST; // < 4.1.0

		if (isset($_REQUEST[$key]) && !is_null($_REQUEST[$key])) {
			return $_REQUEST[$key];
		}

		return $default;
	}

	/*!
		\param $status_code \c int
		\return \c bool
		\static
	*/
	function sendStatusHeader($status_code) {
		include_once(FREEBEER_BASE . '/lib/HTTP/Status.php');
		
		$status_name = fbHTTP_Status::getStatusName($status_code);
		if (!$status_name) {
			return false;
		}
		
		$protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
		header($protocol . ' ' . $status_code . ' ' . $status_name);
		return true;
	}

	/*!
		\return \c bool
		\static
	*/
	function sendNoCacheHeaders() {
		header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');				// Oldest date, always expired
		if (headers_sent()) {
			return false;
		}
		header('Last-Modified: Tue, 19 Jan 2038 03:14:07 GMT');			// Newest date, always modified
		header('Cache-Control: no-store, no-cache, must-revalidate');	// HTTP/1.1
		header('Cache-Control: post-check=0, pre-check=0', false);		// HTTP/1.1
		header('Pragma: no-cache');										// HTTP/1.0
		return true;
	}

	/*!
		\param $time \c int
		\param $file \c string
		\return \c bool
		\static
	*/
	function sendLastModified($time = null, $file = null) {
		if (!$time) {
			if (!is_null($file)) {
				if (@is_file($file) && @is_readable($file)) {
					$time = filemtime($file);
				} else {
					if ((int) $file != 0) {
						$time = (int) $file;
					} else {
						$time = time();	
					}
				}
			} else {
				$time = time();
			}
		}

		header('Last-Modified: ' . gmdate('D, d M Y H:i:s \G\M\T', $time));
		if (headers_sent()) {
			return false;
		}
		return true;
	}

}

?>
