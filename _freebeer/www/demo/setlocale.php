<?php

// $CVSHeader: _freebeer/www/demo/setlocale.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

require_once './_demo.php';

require_once FREEBEER_BASE . '/lib/Locale.php';
require_once FREEBEER_BASE . '/lib/ISO639.php';
require_once FREEBEER_BASE . '/lib/ISO639/Alpha3.php';
require_once FREEBEER_BASE . '/lib/ISO639/Map.php';
require_once FREEBEER_BASE . '/lib/ISO3166.php';
require_once FREEBEER_BASE . '/lib/ISO639/ISO3166_Map.php';
require_once FREEBEER_BASE . '/lib/ISO3166/ISO639_Map.php';

echo html_header_demo('fbLocale Class');

// en_US.iso885915

/*
$h = fbISO3166::getIDToNameHash();

$cc2lang = fbISO639_ISO3166_Map::getCountryIDToLanguageIDHash();

$cc2lang = array_flip($cc2lang);

echo "<pre>\n";

foreach ($h as $country_id => $country_name) {
	$lang = @$cc2lang[$country_id];
	printf("\t\t'%s'\t=> '%s',\t// '%s'\t=> '%s'\n", 
		$country_id, 
		$lang, 
		$country_name,
		fbISO639::getLanguageName($lang)
	);
}
exit;
*/

echo "<pre>\n";

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
	echo "getenv('$var')=",getenv($var),"\n";
}

function resetlocale() {
	setlocale(LC_ALL, 'C');
	setlocale(LC_ALL, '');
	//setlocale(LC_ALL, 'usa');
	//setlocale(LC_ALL, 'us');
	//setlocale(LC_ALL, 'en_US');
}

resetlocale();

$default_locale = setlocale(LC_ALL, 0);
echo "default_locale=$default_locale\n";

$date = time();

$supported_locales = array();

echo "setlocale('two letter code')\n\n";

$id2_language_hash = fbISO639::getIDToNameHash();
foreach ($id2_language_hash as $id2 => $language) {
	$locale = strtolower($id2);
	while (true) {
		$rv = setlocale(LC_ALL, $locale);
		if ($rv) {
			break;
		}

		$iso3166 = fbISO639_ISO3166_Map::getCountryID($id2);
		$locale = strtolower($id2) . '_' . strtoupper($iso3166);
		$rv = setlocale(LC_ALL, $locale);
		if ($rv) {
			break;
		}

		$locale = strtolower($id2) . '_' . strtoupper($id2);
		$rv = setlocale(LC_ALL, $locale);
		if ($rv) {
			break;
		}

		continue 2;
	}

$a = fbLocale::_parseLocale($rv);
//print_r($a);

	$id3 = fbISO639_Map::getID3($id2);

	if (strpos($rv, $language) === false) {
		$rv .= ' ??';
	}

	$language .= " ($id3)";

	printf("%-3s\t%-40s\t", $id2, $language);
	printf("%-40s\t", $rv);

	printf("%-12s\t%-10s\t%-10s\t%-10s",
			strftime("%A", $date),
			strftime("%a", $date),
			strftime("%B", $date),
			strftime("%b", $date),
			strftime("%c", $date));

	echo "\n";

	$supported_locales[$rv] = $id2 . ' ';
	resetlocale();
}

echo "\nsetlocale('three letter code')\n\n";

$id3_language_hash = fbISO639_Alpha3::getIDToNameHash();
foreach ($id3_language_hash as $id3 => $language) {
	$rv = setlocale(LC_ALL, strtolower($id3));

	if (!$rv) {
		continue;
	}

$a = fbLocale::_parseLocale($rv);
//print_r($a);

	if (isset($supported_locales[$rv])) {
		$supported_locales[$rv] .= $id3 . ' ';
		continue;
	}

	if (strpos($rv, $language) === false) {
		$rv .= ' ??';
	}

	$id2 = fbISO639_Map::getID2($id3);
	$language .= " ($id2)";

	printf("%-3s\t%-40s\t", $id3, $language);
	printf("%-40s\t", $rv);

	printf("%-12s\t%-10s\t%-10s\t%-10s",
			strftime("%A", $date),
			strftime("%a", $date),
			strftime("%B", $date),
			strftime("%b", $date),
			strftime("%c", $date));

//	if (isset($supported_locales[$rv])) {
//		echo $supported_locales[$rv];
//	}
	echo "\n";

	@$supported_locales[$rv] .= $id3 . ' ';

	resetlocale();
}

echo "\nsetlocale('language name')\n\n";

$language_id3_hash = fbISO639_Alpha3::getNameToIDHash();
foreach ($language_id3_hash as $language => $id3) {
	$rv = setlocale(LC_ALL, $language);

	if (!$rv) {
		continue;
	}

$a = fbLocale::_parseLocale($rv);
//print_r($a);

	if (isset($supported_locales[$rv])) {
//		$supported_locales[$rv] .= $id3 . ' ';
		continue;
	}

	if (strpos($rv, $language) === false) {
		$rv .= ' ??';
	}

	$id2 = fbISO639_Map::getID2($id3);
	$ids = $id3 . ' ' . $id2;

	printf("%-40s%-10s\t", $language, $ids);
	printf("%-40s\t", $rv);

	printf("%-12s\t%-10s\t%-10s\t%-10s",
			strftime("%A", $date),
			strftime("%a", $date),
			strftime("%B", $date),
			strftime("%b", $date),
			strftime("%c", $date));

//	if (isset($supported_locales[$rv])) {
//		echo $supported_locales[$rv];
//	}
	echo "\n";

//	@$supported_locales[$rv] .= ' ' . $language;

	resetlocale();
}

?>
</pre>
<address>
$CVSHeader: _freebeer/www/demo/setlocale.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $
</address>

</body>
</html>
