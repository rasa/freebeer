<?php

// $CVSHeader: _freebeer/lib/GeoIP/Free.php,v 1.2 2004/03/07 17:51:19 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

/*!
	\file GeoIP/Free.php
	\brief Abstract class for Geo IP Free database
*/

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(dirname(__FILE__))));

/*!
	\class fbGeoIP_Free
	\brief Abstract class for Geo IP Free database

	\abstract	
	\static
*/
class fbGeoIP_Free {
	/*!
		\abstract
		\static
	*/
	function _formatIP4address($ip, $format) {
	}

	/*!
		\abstract
		\static
	*/
	function getCountryIdByIP($ip) {
	}

	/*!
		\static
	*/
	function getCountryIdByHostName($name) {
		$ip = gethostbyname($name);
		if ($ip == $name) {
			return false;
		}
		// PHP5 construct
		return self::getCountryIdByIP($ip);
	}

	/*!
		\static
	*/
	function getCountryNameByIP($ip) {
		include_once FREEBEER_BASE . '/lib/ISO3166.php';

		// PHP5 construct
		$cc = self::getCountryIdByIP($ip);
		$rv = fbISO3166::getCountryName($cc);
		return $rv ? $rv : $cc;
	}

	/*!
		\static
	*/
	function getCountryNameByHostName($name) {
		include_once FREEBEER_BASE . '/lib/ISO3166.php';

		$cc = fbGeoIP_Free::getCountryIdByHostName($name);
		$rv = fbISO3166::getCountryName($cc);
		return $rv ? $rv : $cc;
	}

}

?>
