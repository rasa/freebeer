<?php

// $CVSHeader: _freebeer/lib/Mbstring.php,v 1.1.1.1 2004/01/18 00:12:04 ross Exp $

// Copyright (c) 2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

/*!
	\file Mbstring.php
	\brief mbstring extension emulation (incomplete)
*/

// \todo add some multibyte strings to test case

// Get part of string (PHP 4 >= 4.0.6)
// string mb_substr ( string str, int start [, int length [, string encoding]] )

if (!function_exists('mb_substr')) {
	function mb_substr($str, $start, $length = null, $encoding = null) {
		// \todo deal with $encoding parameter

		static $x7f = "\x7F";

		$strlen = strlen($str);
		for ($i = 0; $start > 0; --$start) {
			if ($i >= $strlen) {
				break;
			}
			if ($str[$i] <= $x7f) {
				++$i;
			} else {
				while ($str[$i] > $x7f) {
					++$i;
				}
			}
		}
		
		if (is_null($length)) {
			return substr($str, $i);
		}

		if ($length == 0) {
			return '';
		}

		for ($j = $i; $length > 0; --$length) {
			if ($j >= $strlen) {
				break;
			}
			if ($str[$j] <= $x7f) {
				++$j;
			} else {
				while ($str[$j] > $x7f) {
					++$j;
				}
			}
		}

		return substr($str, $i, $j - $i);
	}
}

?>
