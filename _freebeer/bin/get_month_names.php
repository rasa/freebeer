#!/usr/bin/php
<?php

// $CVSHeader: _freebeer/bin/get_month_names.php,v 1.2 2004/03/07 17:51:14 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

error_reporting(2047);
@ini_set('html_errors', false);
@ini_set('track_errors', true);
@ob_implicit_flush(true);
@set_time_limit(0);

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(__FILE__)));

require_once FREEBEER_BASE . '/lib/DateTime.php';
require_once FREEBEER_BASE . '/lib/Locale.php';
require_once FREEBEER_BASE . '/lib/ISO639.php';
require_once FREEBEER_BASE . '/lib/ISO639/Alpha3.php';
require_once FREEBEER_BASE . '/lib/ISO639/Map.php';
require_once FREEBEER_BASE . '/lib/ISO3166.php';
require_once FREEBEER_BASE . '/lib/ISO639/ISO3166_Map.php';
require_once FREEBEER_BASE . '/lib/ISO3166/ISO639_Map.php';

$vars = array(
	'LANG',
	'LC_ALL',
	'LC_COLLATE',
	'LC_CTYPE',
	'LC_MONETARY',
	'LC_NUMERIC',
	'LC_TIME',
);

foreach ($vars as $var) {
//	echo "getenv('$var')=",getenv($var),"\n";
}

function resetlocale() {
	fbLocale::setlocale(LC_ALL, 'C') ||
	fbLocale::setlocale(LC_ALL, '') ||
	fbLocale::setlocale(LC_ALL, 'usa') ||
	fbLocale::setlocale(LC_ALL, 'us') ||
	fbLocale::setlocale(LC_ALL, 'en_US');
}

resetlocale();

$default_locale = fbLocale::getDefaultLocale();
//echo "default_locale=$default_locale\n";

$date = time();

$supported_locales = array();

$long_month_names_hash = array();
$long_weekday_names_hash = array();

// echo "setlocale('two letter code')\n\n";

$id2_language_hash = fbISO639::getIDToNameHash();
foreach ($id2_language_hash as $id2 => $language) {
	$locale = strtolower($id2);
	while (true) {
		$rv = fbLocale::setlocale(LC_ALL, $locale);
		if ($rv) {
			break;
		}

		$iso3166 = fbISO639_ISO3166_Map::getCountryID($id2);
		$locale = strtolower($id2) . '_' . strtoupper($iso3166);
		$rv = fbLocale::setlocale(LC_ALL, $locale);
		if ($rv) {
			break;
		}

		$locale = strtolower($id2) . '_' . strtoupper($id2);
		$rv = fbLocale::setlocale(LC_ALL, $locale);
		if ($rv) {
			break;
		}

		continue 2;
	}

	$id3 = fbISO639_Map::getID3($id2);

	if (strpos($rv, $language) === false) {
		$rv .= ' ??';
	}

//	$language .= " ($id3)";

	$long_month_names = join(',', fbDateTime::getLongMonthNames());
	$long_weekday_names = join(',', fbDateTime::getLongWeekdayNames());

	$long_month_names_hash[$long_month_names]		[$locale]	['id2']			= $id2;
	$long_weekday_names_hash[$long_weekday_names]	[$locale]	['id2']		= $id2;
	$long_month_names_hash[$long_month_names]		[$locale]	['id3']			= $id3;
	$long_weekday_names_hash[$long_weekday_names]	[$locale]	['id3']		= $id3;
	$long_month_names_hash[$long_month_names]		[$locale]	['language']		= $language;
	$long_weekday_names_hash[$long_weekday_names]	[$locale]	['language']	= $language;

/*
	$long_month_names_hash[$long_month_names]['id2']			= $id2;
	$long_weekday_names_hash[$long_weekday_names]['id2']		= $id2;
	$long_month_names_hash[$long_month_names]['id3']			= $id3;
	$long_weekday_names_hash[$long_weekday_names]['id3']		= $id3;
	$long_month_names_hash[$long_month_names]['language']		= $language;
	$long_weekday_names_hash[$long_weekday_names]['language']	= $language;
*/

//	printf("%-3s\t%-40s\t", $id2, $language);
//	printf("%-40s\t", $rv);
/*
	printf("%-12s\t%-10s\t%-10s\t%-10s",
			strftime("%A", $date),
			strftime("%a", $date),
			strftime("%B", $date),
			strftime("%b", $date),
			strftime("%c", $date));
*/
//	echo "\n";

	$supported_locales[$rv] = $id2 . ' ';
	resetlocale();
}

// echo "\nsetlocale('three letter code')\n\n";

$id3_language_hash = fbISO639_Alpha3::getIDToNameHash();
foreach ($id3_language_hash as $id3 => $language) {
	$rv = fbLocale::setlocale(LC_ALL, strtolower($id3));

	if (!$rv) {
		continue;
	}

	if (isset($supported_locales[$rv])) {
		$supported_locales[$rv] .= $id3 . ' ';
		continue;
	}

	if (strpos($rv, $language) === false) {
		$rv .= ' ??';
	}

	$id2 = fbISO639_Map::getID2($id3);
//	$language .= " ($id2)";

	$long_month_names = join(',', fbDateTime::getLongMonthNames());
	$long_weekday_names = join(',', fbDateTime::getLongWeekdayNames());

	$locale = $id3;
	
	$long_month_names_hash[$long_month_names]		[$locale]	['id2']			= $id2;
	$long_weekday_names_hash[$long_weekday_names]	[$locale]	['id2']		= $id2;
	$long_month_names_hash[$long_month_names]		[$locale]	['id3']			= $id3;
	$long_weekday_names_hash[$long_weekday_names]	[$locale]	['id3']		= $id3;
	$long_month_names_hash[$long_month_names]		[$locale]	['language']		= $language;
	$long_weekday_names_hash[$long_weekday_names]	[$locale]	['language']	= $language;

/*
	$long_month_names_hash[$long_month_names]['id2']			= $id2;
	$long_weekday_names_hash[$long_weekday_names]['id2']		= $id2;
	$long_month_names_hash[$long_month_names]['id3']			= $id3;
	$long_weekday_names_hash[$long_weekday_names]['id3']		= $id3;
	$long_month_names_hash[$long_month_names]['language']		= $language;
	$long_weekday_names_hash[$long_weekday_names]['language']	= $language;
*/

//	printf("%-3s\t%-40s\t", $id3, $language);
//	printf("%-40s\t", $rv);
/*
	printf("%-12s\t%-10s\t%-10s\t%-10s",
			strftime("%A", $date),
			strftime("%a", $date),
			strftime("%B", $date),
			strftime("%b", $date),
			strftime("%c", $date));
*/
//	if (isset($supported_locales[$rv])) {
//		echo $supported_locales[$rv];
//	}
//	echo "\n";

	@$supported_locales[$rv] .= $id3 . ' ';

	resetlocale();
}

// echo "\nsetlocale('language name')\n\n";

$language_id3_hash = fbISO639_Alpha3::getNameToIDHash();
foreach ($language_id3_hash as $language => $id3) {
	$rv = fbLocale::setlocale(LC_ALL, $language);

	if (!$rv) {
		continue;
	}

	if (isset($supported_locales[$rv])) {
//		$supported_locales[$rv] .= $id3 . ' ';
		continue;
	}

	if (strpos($rv, $language) === false) {
		$rv .= ' ??';
	}

	$id2 = fbISO639_Map::getID2($id3);
	$ids = $id3 . ' ' . $id2;

	$long_month_names = join(',', fbDateTime::getLongMonthNames());
	$long_weekday_names = join(',', fbDateTime::getLongWeekdayNames());

	$locale = $language;
	
	$long_month_names_hash[$long_month_names]		[$locale]	['id2']			= $id2;
	$long_weekday_names_hash[$long_weekday_names]	[$locale]	['id2']		= $id2;
	$long_month_names_hash[$long_month_names]		[$locale]	['id3']			= $id3;
	$long_weekday_names_hash[$long_weekday_names]	[$locale]	['id3']		= $id3;
	$long_month_names_hash[$long_month_names]		[$locale]	['language']		= $language;
	$long_weekday_names_hash[$long_weekday_names]	[$locale]	['language']	= $language;
/*
	$long_month_names_hash[$long_month_names]['id2']			= $id2;
	$long_weekday_names_hash[$long_weekday_names]['id2']		= $id2;
	$long_month_names_hash[$long_month_names]['id3']			= $id3;
	$long_weekday_names_hash[$long_weekday_names]['id3']		= $id3;
	$long_month_names_hash[$long_month_names]['language']		= $language;
	$long_weekday_names_hash[$long_weekday_names]['language']	= $language;
*/

//	printf("%-40s%-10s\t", $language, $ids);
//	printf("%-40s\t", $rv);
/*
	printf("%-12s\t%-10s\t%-10s\t%-10s",
			strftime("%A", $date),
			strftime("%a", $date),
			strftime("%B", $date),
			strftime("%b", $date),
			strftime("%c", $date));
*/
//	if (isset($supported_locales[$rv])) {
//		echo $supported_locales[$rv];
//	}
//	echo "\n";

//	@$supported_locales[$rv] .= ' ' . $language;

	resetlocale();
}

$a = $long_month_names_hash;
ksort($a);

foreach($a as $key => $value) {
	$t = var_export($value, true);
	printf("'%s'	=> %s,\n", $key, $t);
}

print_r($long_month_names_hash);

$a = $long_weekday_names_hash;
ksort($a);

print_r($a);

?>
