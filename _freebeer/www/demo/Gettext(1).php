<?php

// $CVSHeader: _freebeer/www/demo/Gettext(1).php,v 1.2 2004/03/07 17:51:33 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once './_demo.php';

require_once FREEBEER_BASE . '/lib/ISO639.php';
require_once FREEBEER_BASE . '/lib/ISO639/Alpha3.php';
require_once FREEBEER_BASE . '/lib/ISO639/Map.php';
require_once FREEBEER_BASE . '/lib/ISO3166.php';

require_once FREEBEER_BASE . '/lib/Locale.php';

require_once FREEBEER_BASE . '/lib/DateTime.php';
require_once FREEBEER_BASE . '/lib/Gettext.php';

require_once FREEBEER_BASE . '/lib/Debug.php';

//fbDebug::setLevel(FB_DEBUG_ALL);

echo html_header_demo('fbGettext Class (gettext() Emulation)');

echo "<pre>\n";

//echo 'setlocale(0, 0)=', setlocale(0, 0),"\n";

$default_locale = fbLocale::getLocale();
echo "fbLocale::getLocale()=$default_locale\n";

$default_locale_name = fbLocale::getDefaultLocale();
echo "fbLocale::getLocaleName()=$default_locale_name\n";

$rv = bindtextdomain('freebeer', FREEBEER_BASE . '/lib/locale');

printf("bindtextdomain('freebeer', '%s')=%s\n", FREEBEER_BASE . '/lib/locale', $rv);

$rv = textdomain('freebeer');

echo 'textdomain(\'freebeer\')=', $rv,"\n";

print "<table border='1'>
<tr>
<th>
ISO Code
</th>
<th>
_('24 hours')
</th>
<th>
Long Date
</th>
<th>
Short Date
</th>
<th>
Date/Time
</th>
<th>
Number
</th>
<th>
Money
</th>
<th>
Long Month Names
</th>
<th>
Short Month Names
</th>
<th>
Long Weekday Names
</th>
<th>
Short Weekday Names
</th>
<th>
Locale
</th>
<th>
Charset
</th>
<th>
Code Page
</th>
</tr>
";

$date = mktime(13, 14, 15, 2, 1, 2003);

//$locales = &fbLocaleWindows::mimeToWindowsLocaleMap();

$locales = fbLocale::getAvailableLocales();
$locales[$default_locale] = $default_locale_name;
$locales['en_US'] = 'English.United States';
$locales['en_GB'] = 'English.United Kingdom';
ksort($locales);

$long_month_names_hash = array();
$long_weekday_names_hash = array();

foreach ($locales as $locale => $language) {
	$rv		= fbLocale::pushLocale(LC_ALL, $locale);
//echo "fbLocale::pushLocale(LC_ALL, $locale) returned '$rv'\n";

	$name	= $locale;

	$language_id = substr($locale, 0, 2);
	$language = fbISO639::getLanguageName($language_id);
	$country_id = substr($locale, 3, 2);
	$country = fbISO3166::getCountryName($country_id);

	$id3 = fbISO639_Map::getID3($language_id);
	$language3 = fbISO639_Alpha3::getLanguageName($id3);

	$name .= " ($language";

	if ($language3 != $language) {
		$name .= ' [' . $language3 . ']';
	}

	$name .= "/$country/$id3)";

	$string	= '<i>Unavailable</i>';
	$long_date	= '';
	$short_date	= '';
	$datetime	= '';
	$number		= '';
	$money		= '';
	$charset	= '';
	$codepage	= '';
	$locale_name	= fbLocale::getLocale();
	$long_month_names		= '&nbsp;';
	$short_month_names		= '&nbsp;';
	$long_weekday_names		= '&nbsp;';
	$short_weekday_names	= '&nbsp;';

	if ($locale == $default_locale || $locale_name != $default_locale_name) {
		$string	= _('24 hours');
		$long_date	= fbDateTime::getLongDate($date);
		$short_date	= fbDateTime::getShortDate($date);
		$datetime = strftime('%c', $date);
		$amount	= -1234567.89;
		$number	= fbLocale::numberFormat($amount, 2);
		if (function_exists('money_format')) {
			$money	= money_format('%i', $amount) . "<br />\n" .
				money_format('%n', $amount);
		}

		$charset		= fbLocale::getCharset();
		$codepage		= fbLocale::getCodepage();

		$long_month_names = join(',', fbDateTime::getLongMonthNames());
		$short_month_names = join(',', fbDateTime::getShortMonthNames());
		$long_weekday_names = join(',', fbDateTime::getLongWeekdayNames());
		$short_weekday_names = join(',', fbDateTime::getShortWeekdayNames());

		$long_month_names_hash[$long_month_names][$locale] = $locale_name;
		$long_weekday_names_hash[$long_weekday_names][$locale] = $locale_name;
	} else {
//		$locale_name	= '&nbsp;';
	}

	print
"<tr>
<td>
$name
<br />
locale=$locale
<br />
rv=$rv
<br />
locale_name=$locale_name
&nbsp;
</td>
<td>
$string
&nbsp;
</td>
<td>
$long_date
&nbsp;
</td>
<td>
$short_date
&nbsp;
</td>
<td>
$datetime
&nbsp;
</td>
<td>
$number
&nbsp;
</td>
<td>
$money
&nbsp;
</td>
<td>
$long_month_names
&nbsp;
</td>
<td>
$short_month_names
&nbsp;
</td>
<td>
$long_weekday_names
&nbsp;
</td>
<td>
$short_weekday_names
&nbsp;
</td>
<td>
$locale_name
&nbsp;
</td>
<td>
$charset
&nbsp;
</td>
<td>
$codepage
&nbsp;
</td>
</tr>
";

	fbLocale::popLocale(LC_ALL);

/////////////////////////
//break;
/////////////////////////
}
echo "</table>\n";

/*
echo 'fbLocale::getLongMonthNames()=';
print_r(fbLocale::getLongMonthNames());

echo 'fbLocale::getShortMonthNames()=';
print_r(fbLocale::getShortMonthNames());

echo 'fbLocale::getLongWeekdayNames()=';
print_r(fbLocale::getLongWeekdayNames());

echo 'fbLocale::getShortWeekdayNames()=';
print_r(fbLocale::getShortWeekdayNames());

*/

$languages = fbLocale::parseAcceptLanguages();

echo 'fbLocale::parseAcceptLanguages=';
print_r($languages);

$locale = $languages[0];

/*
$windows_locale = fbLocaleWindows::getWindowsLocale($locale);

echo 'fbLocaleWindows::getWindowsLocale(',$locale,')=',$windows_locale,"\n";

$iso_locale = fbLocaleWindows::getISOLocale(LC_ALL, $windows_locale);
echo "fbLocaleWindows::getISOLocale(LC_ALL, $windows_locale)=$iso_locale\n";
*/
/*
// required by Windows
putenv('LANG=' . $locale);
echo "setlocale(LC_ALL, '$locale')=", setlocale(LC_ALL, $locale),"\n";

echo 'setlocale(0, 0)=', setlocale(0, 0),"\n";

echo 'strftime(\'%A %e %B %Y\', mktime (0, 0, 0, 12, 22, 1978))=',strftime('%A %e %B %Y', mktime (0, 0, 0, 12, 22, 1978)),"\n";

echo 'number_format(1234567.89, 2)=',number_format(1234567.89, 2),"\n";

// required by Windows
putenv('LANG=' . $locale);
echo "setlocale(LC_ALL, '$locale')=",setlocale(LC_ALL, $locale),"\n";

echo 'setlocale(0, 0)=', setlocale(0, 0),"\n";

echo 'strftime(\'%A %e %B %Y\', mktime (0, 0, 0, 12, 22, 1978))=',strftime('%A %e %B %Y', mktime (0, 0, 0, 12, 22, 1978)),"\n";

echo 'gettext("24 hours")=',gettext("24 hours"),"\n";

echo 'gettext(\'24 hours\')=',gettext('24 hours'),"\n";

// _() as in alias for gettext()
echo '_("24 hours")=',_("24 hours"),"\n";

echo '_(\'24 hours\')=',_('24 hours'),"\n";

//echo 'fbLocale::parseAcceptLanguages=';
//print_r(fbLocale::parseAcceptLanguages());

*/

// echo 'fbLocale::parseAcceptLanguages=';
// print_r(fbLocale::parseAcceptLanguages());

// echo "\n";

echo 'fbLocale::getNearestLocales(fbLocale::parseAcceptLanguages())=';
print_r(fbLocale::getNearestLocales(fbLocale::parseAcceptLanguages()));
print "\n";

echo 'fbLocale::getAvailableLocales=';
print_r(fbLocale::getAvailableLocales());

echo '$long_month_names_hash =';
print_r($long_month_names_hash);

echo '$long_weekday_names_hash =';
print_r($long_weekday_names_hash);

/*
$locales = fbLocaleWindows::mimeToWindowsLocaleMap();
foreach ($locales as $iso_locale => $country_name) {
	$nearest_locale = fbLocale::getNearestLocale($iso_locale);
	$nearest_country_name = '';
	printf("%-10s %-30s %-10s %-30s\n", $iso_locale, $country_name,
$nearest_locale, $nearest_country_name);
}
*/

/*
setlocale(LC_ALL, 'usa');
setlocale(LC_ALL, 'us');
setlocale(LC_ALL, $default_locale);
$date = time();
$default_strftime = strftime("%A\t%a\t%B\t%b\t%c", $date);

echo '$default_strftime=',$default_strftime,"\n";

$locales = &fbISO639_Alpha3::getIDToNameHash();
foreach ($locales as $locale => $language) {
	$rv = setlocale(LC_ALL, strtolower($locale));

	$t = strftime("%A\t%a\t%B\t%b\t%c", $date);
	if ($t != $default_strftime) {
		printf("%-4s\t%-60s\t%-12s\t%-10s\t%-10s\t%-10s\t%s\n",
			$locale, $language . " ($rv)",
			strftime("%A", $date),
			strftime("%a", $date),
			strftime("%B", $date),
			strftime("%b", $date),
			strftime("%c", $date));
	}
	setlocale(LC_ALL, 'usa');
	setlocale(LC_ALL, 'us');
	setlocale(LC_ALL, $default_locale);
}

echo "\n\n";

$locales = &fbISO639_Alpha3::getNameToIDHash();
foreach ($locales as $language => $locale) {
	$rv = setlocale(LC_ALL, $language);

	$t = strftime("%A\t%a\t%B\t%b\t%c", $date);
	if ($t != $default_strftime) {
		printf("%-4s\t%-60s\t%-12s\t%-10s\t%-10s\t%-10s\t%s\n",
			$locale, $language . " ($rv)",
			strftime("%A", $date),
			strftime("%a", $date),
			strftime("%B", $date),
			strftime("%b", $date),
			strftime("%c", $date));
	}
	setlocale(LC_ALL, 'usa');
	setlocale(LC_ALL, 'us');
	setlocale(LC_ALL, $default_locale);
}
*/

?>
</pre>
<address>
$CVSHeader: _freebeer/www/demo/Gettext(1).php,v 1.2 2004/03/07 17:51:33 ross Exp $
</address>

</body>
</html>
