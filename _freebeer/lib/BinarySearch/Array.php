<?php

// $CVSHeader: _freebeer/lib/BinarySearch/Array.php,v 1.1.1.1 2004/01/18 00:12:04 ross Exp $

// Copyright (c) 2002-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

/*!
	\file BinarySearch/Array.php
	\brief Perform a binary search on an array.
*/

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(dirname(__FILE__))));

require_once FREEBEER_BASE . '/lib/Debug.php';
require_once FREEBEER_BASE . '/lib/BinarySearch.php';

/*!
	\class fbBinarySearch_Array
	\brief Perform a binary search on an array.

	The array must already be sorted.	
	
	\static
*/
class fbBinarySearch_Array extends fbBinarySearch {
	/*!
		\todo add $fuzzy parameter
		\static
	*/
	function search($needle, $haystack, $sort_flag = null, $compare_function = null) {
		$len = strlen($needle);
		if (!$len) {
			return false;
		}

		if (!is_array($haystack)) {
			return false;
		}

		if (is_null($compare_function)) {
			$compare_function = 'strncmp';
		}

		if ($sort_flag) {
			sort($haystack);
		}

fbDebug::dump($haystack, '$haystack');

		$high = count($haystack) - 1;

		if ($high < 0) {
			return false;
		}

		$mid = 0;
		$low = 0;
fbDebug::log(sprintf("%3d: low=%5s high=%5s mid=%5s arr[mid]=%5s\n", __LINE__, $low, $high, $mid, $haystack[$mid]));
		while ($high >= $low) {
			$mid = ($high + $low) >> 1;
			$t = @$compare_function($needle, $haystack[$mid], $len);
			if ($t < 0) {
				$high = $mid - 1;
			} elseif ($t > 0) {
				$low = $mid + 1;
			} else {
fbDebug::log(sprintf("%3d: low=%5s high=%5s mid=%5s arr[mid]=%5s\n", __LINE__, $low, $high, $mid, $haystack[$mid]));
				return $mid;
			}
fbDebug::log(sprintf("%3d: low=%5s high=%5s mid=%5s arr[mid]=%5s\n", __LINE__, $low, $high, $mid, $haystack[$mid]));
		}
fbDebug::log(sprintf("%3d: low=%5s high=%5s mid=%5s arr[mid]=%5s\n", __LINE__, $low, $high, $mid, $haystack[$mid]));
		return $mid;
	}

}

?>
