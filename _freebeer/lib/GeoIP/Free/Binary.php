<?php

// $CVSHeader: _freebeer/lib/GeoIP/Free/Binary.php,v 1.2 2004/03/07 17:51:19 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

/*!
	\file GeoIP/Free/Binary.php
	\brief Geo IP Free binary file database class
*/

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(dirname(dirname(__FILE__)))));

require_once FREEBEER_BASE . '/lib/BinarySearch/File.php';
require_once FREEBEER_BASE . '/lib/Debug.php';
// require_once FREEBEER_BASE . '/lib/ISO3166.php'; // only include when needed

/*!
	\class fbGeoIP_Free_Binary
	\brief Geo IP Free binary file database class

	The only difference between this and fbGeoIP_Free is the format is '%c%c%c%c' and unpacking the
	result:
	
	\code
			$a = unpack('C*', $found);
			$found = sprintf("%03d.%03d.%03d.%03d\t%03d.%03d.%03d.%03d\t%c%c",
				$a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10]);
	\endcode
	
	\todo make caching optional?
	\static
*/
class fbGeoIP_Free_Binary {
	/*!
		\static
	*/
	function _formatIP4address($ip, $format = '%c%c%c%c') {
		$l = ip2long($ip);
		if ($l == -1 && $ip != '255.255.255.255') {
			return false;
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

		$ipf = fbGeoIP_Free_Binary::_formatIP4address($ip);
		if (!$ipf) {
			return false;
		}
/*		
		if (isset($cache[$ipf])) {
			return $cache[$ipf];
		}
*/		
		// \todo make a parameter
		$file = FREEBEER_BASE . '/etc/geo/geo-ips-binary.txt';
		if (!is_file($file)) {
			trigger_error(sprintf("File not found: '%s'", $file));
			return false;
		}
		$bsf = &new fbBinarySearch_File($file);
		$bsf->setRecordLength(10);

		$found		= $bsf->search($ipf);
		$midval		= $bsf->getRecordNumber();
		$rec_len	= $bsf->getRecordLength();

		if ($found === false) {
			return false;
		}

fbDebug::log(sprintf("ip=%s found=%s midval=%s rec_len=%s\n\n", $ip, $found, $midval, $rec_len));

		$a	= unpack('C*', $found);
		$cc = sprintf('%c%c', $a[9], $a[10]);

		$ips = sprintf('%d.%d.%d.%d', $a[1], $a[2], $a[3], $a[4]);
		$ipe = sprintf('%d.%d.%d.%d', $a[5], $a[6], $a[7], $a[8]);

		if ($ipf < substr($found, 0, 4) || $ipf > substr($found, 4, 4)) {
			trigger_error(sprintf('Unexpected result: \'%s\' is not between \'%s\' and \'%s\'',
				$ip, $ips, $ipe), E_USER_NOTICE);
			return false;
		}

//		$cache[$ipf] = $cc;

		return $cc;
	}

	/*!
		\static
	*/
	function getCountryIdByHostName($name) {
		$ip = gethostbyname($name);
		if ($ip == $name) {
			return false;
		}
		return fbGeoIP_Free_Binary::getCountryIdByIP($ip);
	}

	/*!
		\static
	*/
	function getCountryNameByIP($ip) {
		include_once FREEBEER_BASE . '/lib/ISO3166.php';

		$cc = fbGeoIP_Free_Binary::getCountryIdByIP($ip);
		$rv = fbISO3166::getCountryName($cc);
		return $rv ? $rv : $cc;
	}

	/*!
		\static
	*/
	function getCountryNameByHostName($name) {
		include_once FREEBEER_BASE . '/lib/ISO3166.php';

		$cc = fbGeoIP_Free_Binary::getCountryIdByHostName($name);
		$rv = fbISO3166::getCountryName($cc);
		return $rv ? $rv : $cc;
	}

}

?>
