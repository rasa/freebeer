<?php

// $CVSHeader: _freebeer/lib/DateTime.php,v 1.2 2004/03/07 17:51:17 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

/*!
	\file DateTime.php
	\brief Date and Time related functions
*/

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(__FILE__)));

require_once FREEBEER_BASE . '/lib/Locale.php';

/*!
	\class fbDateTime

	\brief Date and Time related functions

	\todo Rename fbDateTime to fbDateUtils?
		
	\static
*/
class fbDateTime {

	/*
	 Unsupported on Windows 2000 SP4, so we emulate:
	 
	 %C: century number (the year divided by 100 and truncated to an integer, range 00 to 99)
	 %D: same as %m/%d/%y
	 %e: day of the month as a decimal number, a single digit is preceded by a space (range ' 1' to '31')
	 %g: like %G, but without the century.
	 %G: The 4-digit year corresponding to the ISO week number (see %V). 
	     This has the same format and value as %Y, except that if the ISO week number belongs to the previous 
	     or next year, that year is used instead.
	 %h: same as %b
	 %n: newline character
	 %r: time in a.m. and p.m. notation
	 %R: time in 24 hour notation
	 %t: tab character
	 %T: current time, equal to %H:%M:%S
	 %u: weekday as a decimal number [1,7], with 1 representing Monday
	 %V: The ISO 8601:1988 week number of the current year as a decimal number, range 01 to 53, 
	     where week 1 is the first week that has at least 4 days in the current year, and with 
	     Monday as the first day of the week. (Use %G or %g for the year component that corresponds
	     to the week number for the specified timestamp.)
	*/

	/*!
		\static
	*/
	function strftime($format, $date = null) {
		static $map = null;
		
		if (is_null($map)) {
			$map = array(
				'%D'	=> '%m/%d/%y',
				'%h'	=> '%b',
				'%n'	=> "\n",
				'%r'	=> '%I:%M:%S %p',
				'%R'	=> '%X',
				'%t'	=> "\t",
				'%T'	=> '%H:%M:%S',
			);
		}
		
		static $nomap = null;
		
		if (is_null($nomap)) {
			$nomap = array(
				'%C'	=> '',
				'%e'	=> '',
				'%g'	=> '',
				'%G'	=> '',
				'%u'	=> '',
				'%V'	=> '',
			);
		}
		
		static $nomapfixes = null;

		static $unsupported = 0;

		if (is_null($date)) {
			$date = time();
		}

		if (is_null($nomapfixes)) {
			foreach($map as $fmt => $fix) {
				if (!strftime($fmt, $date)) {
					++$unsupported;
				}
			}

			$nomapfixes = array();
			foreach($nomap as $fmt => $fix) {
				if (!strftime($fmt, $date)) {
					$nomapfixes[] = $fmt;
					++$unsupported;
				}
			}
		}

		if ($unsupported) {
			$fixmap = $map;
			foreach ($nomapfixes as $fmt) {
				switch ($fmt) {
 					case '%C':
						$fixmap[$fmt] = sprintf('%02d', date('Y', $date) / 100);
						break;

					case '%e':
						$fixmap[$fmt] = sprintf('%2d', date('j', $date));	// strftime('%d')
						break;

					case '%G':
						$y = (int) strftime('%Y', $date);
						if (date('W', $date) == 1 && date('m', $date) == 12) {
							++$y;
						} else {
							if (date('W', $date) == 53 && date('m', $date) == 1) {
								--$y;
							}
						}
						$fixmap[$fmt] = sprintf('%04d', $y);
						break;

					case '%g':
						$y = (int) strftime('%y', $date);
						if (date('W', $date) == 1 && date('m', $date) == 12) {
							++$y;
						} else {
							if (date('W', $date) == 53 && date('m', $date) == 1) {
								--$y;
							}
						}
						$fixmap[$fmt] = sprintf('%02d', $y);
						break;

					case '%u':
						$u = (int) date('w', $date);
						if ($u == 0) {
							$u = 7;
						}
						$fixmap[$fmt] = (string) $u;
						break;

					case '%V':
						$fixmap[$fmt] = (string) date('W', $date);
						break;
				}
			}

			$format = str_replace(array_keys($fixmap), array_values($fixmap), $format);
		}

		return strftime($format, $date);
	}

	/*
		day
			'd'	=> "day of the month as a decimal number (range 01 to 31)",
			'e'	=> "day of the month as a decimal number, a single digit is preceded by a space (range ' 1' to '31')",

		month
			'b'	=> "abbreviated month name according to the current locale",
			'B'	=> "full month name according to the current locale",
			'h'	=> "same as %b",
			'm'	=> "month as a decimal number (range 01 to 12)",

		year
			'y'	=> "year as a decimal number without a century (range 00 to 99)",
			'Y'	=> "year as a decimal number including the century",

		other
			'a'	=> "abbreviated weekday name according to the current locale",
			'A'	=> "full weekday name according to the current locale",
			'c'	=> "preferred date and time representation for the current locale",
			'C'	=> "century number (the year divided by 100 and truncated to an integer, range 00 to 99)",
			'D'	=> "same as %m/%d/%y",
			'g'	=> "like %G, but without the century.",
			'G'	=> "The 4-digit year corresponding to the ISO week number (see %V). This has the same format and value as %Y, except that if the ISO week number belongs to the previous or next year, that year is used instead.",
			'H'	=> "hour as a decimal number using a 24-hour clock (range 00 to 23)",
			'I'	=> "hour as a decimal number using a 12-hour clock (range 01 to 12)",
			'j'	=> "day of the year as a decimal number (range 001 to 366)",
			'M'	=> "minute as a decimal number",
			'n'	=> "newline character",
			'p'	=> "either `am' or `pm' according to the given time value, or the corresponding strings for the current locale",
			'r'	=> "time in a.m. and p.m. notation",
			'R'	=> "time in 24 hour notation",
			'S'	=> "second as a decimal number",
			't'	=> "tab character",
			'T'	=> "current time, equal to %H:%M:%S",
			'u'	=> "weekday as a decimal number [1,7], with 1 representing Monday",
			'U'	=> "week number of the current year as a decimal number, starting with the first Sunday as the first day of the first week",
			'V'	=> "The ISO 8601:1988 week number of the current year as a decimal number, range 01 to 53, where week 1 is the first week that has at least 4 days in the current year, and with Monday as the first day of the week. (Use %G or %g for the year component that corresponds to the week number for the specified timestamp.)",
			'W'	=> "week number of the current year as a decimal number, starting with the first Monday as the first day of the first week",
			'w'	=> "day of the week as a decimal, Sunday being 0",
			'x'	=> "preferred date representation for the current locale without the time",
			'X'	=> "preferred time representation for the current locale without the date",
			'Z'	=> "time zone or name or abbreviation",
			'%' => "a literal `%' character",

		day
			'd'	=> "day of the month as a decimal number (range 01 to 31)",
			'e'	=> "day of the month as a decimal number, a single digit is preceded by a space (range ' 1' to '31')",

		month
			'b'	=> "abbreviated month name according to the current locale",
			'B'	=> "full month name according to the current locale",
			'h'	=> "same as %b",
			'm'	=> "month as a decimal number (range 01 to 12)",

		year
			'y'	=> "year as a decimal number without a century (range 00 to 99)",
			'Y'	=> "year as a decimal number including the century",

	*/

	/*!
		\todo add sensing of leading zeros?

		%B %d %Y
		\static
	*/
	function getLongDate($date, $format = array('d', 'B', 'Y'), $locale = null) {
		assert('is_array($format)');
		assert('count($format) == 3');
		
		$date_order = fbDateTime::getDateOrder($locale);

		$rv = '';
		foreach($date_order as $date_part) {
			if ($rv) {
				$rv .= ' ';
			}

			switch ($date_part) {
				case 'd':
					$rv .= fbDateTime::strftime('%' . $format[0], $date);
					break;
				case 'm':
					$rv .= fbDateTime::strftime('%' . $format[1], $date);
					break;
				case 'y':
					$rv .= fbDateTime::strftime('%' . $format[2], $date);
					break;
			}
		}

		return $rv;
	}

	/*!
		\todo add sensing of leading zeros?

		%b %d %y
		\static
	*/
	function getShortDate($date, $format = array('d', 'b', 'y'), $locale = null) {
		assert('is_array($format)');
		assert('count($format) == 3');

		$date_order = fbDateTime::getDateOrder($locale);

		$rv = '';
		foreach($date_order as $date_part) {
			if ($rv) {
				$rv .= ' ';
			}

			switch ($date_part) {
				case 'd':
					$rv .= fbDateTime::strftime('%' . $format[0], $date);
					break;
				case 'm':
					$rv .= fbDateTime::strftime('%' . $format[1], $date);
					break;
				case 'y':
					$rv .= fbDateTime::strftime('%' . $format[2], $date);
					break;
			}
		}

		return $rv;
	}

	/*!
		\static
	*/
	function getDateOrder($locale = null) {
		static $locale_cache = array();

		if (is_null($locale)) {
			$locale = fbLocale::getLocale(LC_TIME);
		}

		if (!isset($locale_cache[$locale])) {
			$date = mktime(0, 0, 0, 4, 3, 2001);
			$s = strftime('%c', $date);
			$d = strpos($s, '3');
			$m = strpos($s, '4');
			$y = strpos($s, '2001');
			$rv = array();
			if ($d < $m && $d < $y) {
				$rv = ($m < $y) ? array('d', 'm', 'y') : array('d', 'y', 'm');
			}
			if ($m < $d && $m < $y) {
				$rv = ($d < $y) ? array('m', 'd', 'y') : array('m', 'y', 'd');
			}
			if ($y < $d && $y < $m) {
				$rv = ($d < $m) ? array('y', 'd', 'm') : array('y', 'm', 'd');
			}
			assert('count($rv)');
			$locale_cache[$locale] = $rv;
		}

		return $locale_cache[$locale];
	}

	/*!
		\static
	*/
	function _getDateNames($format, $locale = null) {
		static $rv = array();

		$get_locale = is_null($locale);

		if ($get_locale) {
			$locale = fbLocale::getLocale(LC_TIME);
		}

		if (!isset($rv[$locale])) {
			if (!$get_locale) {
				fbLocale::pushLocale(LC_TIME, $locale);
			}

			$t = array();

			for ($i = 0; $i < 12; ++$i) {
				$date = mktime(0, 0, 0, 1 + $i, 1, 2003);
				$t['B'][] = strftime('%B', $date);
				$t['b'][] = strftime('%b', $date);
			}
			for ($i = 0; $i < 7; ++$i) {
				$date = mktime(0, 0, 0, 11, 9 + $i, 2003);	// 9-Nov-2003 was a Sunday
				$t['A'][] = strftime('%A', $date);
				$t['a'][] = strftime('%a', $date);
			}

			$rv[$locale] = $t;

			if (!$get_locale) {
				fbLocale::popLocale(LC_TIME);
			}
		}

		return $rv[$locale][$format];
	}

	/*!
		'B'	=> "full month name according to the current locale"
		
		\static
	*/
	function getLongMonthNames($locale = null) {
		return fbDateTime::_getDateNames('B', $locale);
	}

	/*!
		'b'	=> "abbreviated month name according to the current locale"

		\static
	*/
	function getShortMonthNames($locale = null) {
		return fbDateTime::_getDateNames('b', $locale);
	}

	/*!
		'A'	=> "full weekday name according to the current locale"
		
		\static
	*/
	function getLongWeekdayNames($locale = null) {
		return fbDateTime::_getDateNames('A', $locale);
	}

	/*!
		'a'	=> "abbreviated weekday name according to the current locale"
		
		\static
	*/
	function getShortWeekdayNames($locale = null) {
		return fbDateTime::_getDateNames('a', $locale);
	}

}

?>
