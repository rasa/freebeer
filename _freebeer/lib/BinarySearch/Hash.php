<?php

// $CVSHeader: _freebeer/lib/BinarySearch/Hash.php,v 1.2 2004/03/07 17:51:18 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

/*!
	\file BinarySearch/Hash.php
	\brief Perform a binary search on a hash (associative array).
*/

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(dirname(__FILE__))));

require_once FREEBEER_BASE . '/lib/BinarySearch.php';

/*!
	\class fbBinarySearch_Hash
	\brief Perform a binary search on a hash (associative array).

	The hash need not be sorted.

	\static
*/

class fbBinarySearch_Hash extends fbBinarySearch {
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
		$flipped_haystack = array_flip($haystack);

//echo 'flipped_haystack=';print_r($flipped_haystack);

		if ($sort_flag) {
			sort($haystack);
		}

//echo 'haystack=';print_r($haystack);

		$high = count($haystack) - 1;

		if ($high < 0) {
			return false;
		}

		$mid = 0;
		$low = 0;
//@printf("%3d: low=%5s high=%5s mid=%5s arr[mid]=%5s (%s)\n", __LINE__, $low, $high, $mid, $haystack[$mid], $flipped_haystack[$haystack[$mid]]);
		while ($high >= $low) {
			$mid = ($high + $low) >> 1;
			$t = @$compare_function($needle, $haystack[$mid], $len);
			if ($t < 0) {
				$high = $mid - 1;
			} elseif ($t > 0) {
				$low = $mid + 1;
			} else {
//@printf("%3d: low=%5s high=%5s mid=%5s arr[mid]=%5s (%s)\n", __LINE__, $low, $high, $mid, $haystack[$mid], $flipped_haystack[$haystack[$mid]]);
				return $flipped_haystack[$haystack[$mid]];
			}
//@printf("%3d: low=%5s high=%5s mid=%5s arr[mid]=%5s (%s)\n", __LINE__, $low, $high, $mid, $haystack[$mid], $flipped_haystack[$haystack[$mid]]);
		}
//@printf("%3d: low=%5s high=%5s mid=%5s arr[mid]=%5s (%s)\n", __LINE__, $low, $high, $mid, $haystack[$mid], $flipped_haystack[$haystack[$mid]]);
		return $flipped_haystack[$haystack[$mid]];
	}

}

?>
