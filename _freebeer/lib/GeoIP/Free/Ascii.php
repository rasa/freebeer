<?php

// $CVSHeader: _freebeer/lib/GeoIP/Free/Ascii.php,v 1.2 2004/03/07 17:51:19 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

/*!
	\file GeoIP/Free/Ascii.php
	\brief Geo IP Free ASCII file database class
*/

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(dirname(dirname(__FILE__)))));

require_once FREEBEER_BASE . '/lib/BinarySearch/File.php';
require_once FREEBEER_BASE . '/lib/Debug.php';
// require_once FREEBEER_BASE . '/lib/ISO3166.php'; // only include when needed

/*!
	\class fbGeoIP_Free_Ascii
	\brief Geo IP Free ASCII file database class

	\todo make caching optional?
	\static
*/
class fbGeoIP_Free_Ascii {
	/*!
		\static
	*/
	function _formatIP4address($ip, $format = '%03d.%03d.%03d.%03d') {
		$l = ip2long($ip);
		if ($l == -1 && $ip != '255.255.255.255') {
			return false;
		}
		if ($l === false && $ip == '255.255.255.255') {
			return $ip;
		}
		
		$tuple1 = $l >> 24;
		if ($tuple1 < 0) {
			$tuple1 += 256;
		}

		$rv = sprintf($format, $tuple1, ($l >> 16) & 0xff, ($l >> 8)  & 0xff, $l & 0xff);
fbDebug::log(sprintf("l=%s ip=%s rv=%s\n", $l, $ip, $rv));
		return $rv;
	}

	/*!
		\static
	*/
	function getCountryIdByIP($ip) {
		static $cache = array();

		$ipf = fbGeoIP_Free_Ascii::_formatIP4address($ip);
		if (!$ipf) {
			return false;
		}
/*		
		if (isset($cache[$ipf])) {
			return $cache[$ipf];
		}
*/		
		// \todo make a parameter
		$file = FREEBEER_BASE . '/etc/geo/geo-ips.txt';
		if (!is_file($file)) {
			trigger_error(sprintf("File not found: '%s'", $file));
			return false;
		}
		$bsf = &new fbBinarySearch_File($file);
		$bsf->setReadLength(64); // 36 for unix, 37 for ms-dos/windows

		$found		= $bsf->search($ipf);
		$midval		= $bsf->getRecordNumber();
		$rec_len	= $bsf->getRecordLength();

		if ($found === false) {
			return false;
		}

fbDebug::log(sprintf("ip=%s found=%s midval=%s rec_len=%s\n\n", $ip, $found, $midval, $rec_len));
		$matches = preg_split('/\s+/', $found);
		if (count($matches) < 3) {
			trigger_error(sprintf('Read error reading \'%s\': File does not contain whitespace', $file));
			return false;
		}

		if ($ipf < $matches[0] || $ipf > $matches[1]) {
			trigger_error(sprintf('Unexpected result: \'%s\' (\'%s\') is not between \'%s\' and \'%s\'',
				$ip, $ipf, $matches[0], $matches[1]), E_USER_NOTICE);
			return false;
		}

//		$cache[$ipf] = trim($matches[2]);
	
		return trim($matches[2]);
	}

	/*!
		\static
	*/
	function getCountryIdByHostName($name) {
		$ip = gethostbyname($name);
		if ($ip == $name) {
			return false;
		}
		return fbGeoIP_Free_Ascii::getCountryIdByIP($ip);
	}

	/*!
		\static
	*/
	function getCountryNameByIP($ip) {
		include_once FREEBEER_BASE . '/lib/ISO3166.php';

		$cc = fbGeoIP_Free_Ascii::getCountryIdByIP($ip);
		$rv = fbISO3166::getCountryName($cc);
		return $rv ? $rv : $cc;
	}

	/*!
		\static
	*/
	function getCountryNameByHostName($name) {
		include_once FREEBEER_BASE . '/lib/ISO3166.php';

		$cc = fbGeoIP_Free_Ascii::getCountryIdByHostName($name);
		$rv = fbISO3166::getCountryName($cc);
		return $rv ? $rv : $cc;
	}

}

?>
