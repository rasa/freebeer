<?php

// $CVSHeader: _freebeer/www/demo/DateTime.php,v 1.2 2004/03/07 17:51:33 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once './_demo.php';

require_once FREEBEER_BASE . '/lib/DateTime.php';

echo html_header_demo('fbDateTime Class (Improved strftime())');

echo "<pre>\n";

$formats = array(
	'a'	=> "abbreviated weekday name according to the current locale",
	'A'	=> "full weekday name according to the current locale",
	'b'	=> "abbreviated month name according to the current locale",
	'B'	=> "full month name according to the current locale",
	'c'	=> "preferred date and time representation for the current locale",
	'C'	=> "century number (the year divided by 100 and truncated to an integer, range 00 to 99)",
	'd'	=> "day of the month as a decimal number (range 01 to 31)",
	'D'	=> "same as %m/%d/%y",
	'e'	=> "day of the month as a decimal number, a single digit is preceded by a space (range ' 1' to '31')",
	'g'	=> "like %G, but without the century.",
	'G'	=> "The 4-digit year corresponding to the ISO week number (see %V). This has the same format and value as %Y, except that if the ISO week number belongs to the previous or next year, that year is used instead.",
	'h'	=> "same as %b",
	'H'	=> "hour as a decimal number using a 24-hour clock (range 00 to 23)",
	'I'	=> "hour as a decimal number using a 12-hour clock (range 01 to 12)",
	'j'	=> "day of the year as a decimal number (range 001 to 366)",
	'm'	=> "month as a decimal number (range 01 to 12)",
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
	'y'	=> "year as a decimal number without a century (range 00 to 99)",
	'Y'	=> "year as a decimal number including the century",
	'Z'	=> "time zone or name or abbreviation",
	'%' => "a literal `%' character",
	'#c' => "Long date and time for the current locale",
	'#x' => "Long date for the current locale",
	'#d' => "Same as %d with leading zeros removed (if any)",
	'#H' => "Same as %H with leading zeros removed (if any)",
	'#I' => "Same as %I with leading zeros removed (if any)",
	'#j' => "Same as %j with leading zeros removed (if any)",
	'#M' => "Same as %M with leading zeros removed (if any)",
	'#S' => "Same as %S with leading zeros removed (if any)",
	'#U' => "Same as %U with leading zeros removed (if any)",
	'#w' => "Same as %w with leading zeros removed (if any)",
	'#W' => "Same as %W with leading zeros removed (if any)",
	'#y' => "Same as %y with leading zeros removed (if any)",
	'#Y' => "Same as %Y with leading zeros removed (if any)",
);

$date = mktime(1, 3, 4, 5, 6, 2007);

$supported = '';
$unsupported = '';
$windows = '';

foreach ($formats as $char => $description) {
	$s = strftime('%' . $char, $date);
	$result = sprintf("%3s: %-40s: %s\n", '%' . $char, "'" . $s . "'", $description);
	if ($s !== false && ($s != $char || $char == '%')) {
		if (strpos($char, '#') !== false) {
			$windows .= $result;
		} else {
			$supported .= $result;
		}
	} else {
		$unsupported .= $result;
	}
}

echo "strftime()\n\n";

echo "Supported\n\n";

echo $supported;

echo "\nSupported (Windows Specific)\n\n";

echo $windows;

echo "\nUnsupported\n\n";

echo $unsupported;

$date_formats = array(
	'a'	=> "Lowercase Ante meridiem and Post meridiem am or pm",
	'A'	=> "Uppercase Ante meridiem and Post meridiem AM or PM",
	'B'	=> "Swatch Internet time 000 through 999",
	'c'	=> "ISO 8601 date (added in PHP 5)",
	'd'	=> "Day of the month, 2 digits with leading zeros 01 to 31",
	'D'	=> "A textual representation of a day, three letters Mon through Sun",
	'F'	=> "A full textual representation of a month, such as January or March January through December",
	'g'	=> "12-hour format of an hour without leading zeros 1 through 12",
	'G'	=> "24-hour format of an hour without leading zeros 0 through 23",
	'h'	=> "12-hour format of an hour with leading zeros 01 through 12",
	'H'	=> "24-hour format of an hour with leading zeros 00 through 23",
	'i'	=> "Minutes with leading zeros 00 to 59",
	'I'	=> "(capital i) Whether or not the date is in daylights savings time 1 if Daylight Savings Time, 0 otherwise.",
	'j'	=> "Day of the month without leading zeros 1 to 31",
	'l'	=> "(lowercase 'L') A full textual representation of the day of the week Sunday through Saturday",
	'L'	=> "Whether it's a leap year 1 if it is a leap year, 0 otherwise.",
	'm'	=> "Numeric representation of a month, with leading zeros 01 through 12",
	'M'	=> "A short textual representation of a month, three letters Jan through Dec",
	'n'	=> "Numeric representation of a month, without leading zeros 1 through 12",
	'O'	=> "Difference to Greenwich time (GMT) in hours Example: +0200",
	'r'	=> "RFC 822 formatted date Example: Thu, 21 Dec 2000 16:01:07 +0200",
	's'	=> "Seconds, with leading zeros 00 through 59",
	'S'	=> "English ordinal suffix for the day of the month, 2 characters st, nd, rd or th. Works well with j",
	't'	=> "Number of days in the given month 28 through 31",
	'T'	=> "Timezone setting of this machine Examples: EST, MDT ...",
	'U'	=> "Seconds since the Unix Epoch (January 1 1970 00:00:00 GMT) See also time()",
	'w'	=> "Numeric representation of the day of the week 0 (for Sunday) through 6 (for Saturday)",
	'W'	=> "ISO-8601 week number of year, weeks starting on Monday (added in PHP 4.1.0) Example: 42 (the 42nd week in the year)",
	'Y'	=> "A full numeric representation of a year, 4 digits Examples: 1999 or 2003",
	'y'	=> "A two digit representation of a year Examples: 99 or 03",
	'z'	=> "The day of the year 0 through 366",
	'Z'	=> "Timezone offset in seconds. The offset for timezones west of UTC is always negative, and for those east of UTC is always positive. -43200 through 43200",
);

$supported = '';
$unsupported = '';

foreach ($date_formats as $char => $description) {
	$s = date($char, $date);
	$result = sprintf("%3s: %-40s: %s\n", $char, $s, $description);
	if ($s !== false && $s != $char) {
		$supported .= $result;
	} else {
		$unsupported .= $result;
	}
}

echo "\ndate()\n\n";

echo "Supported\n\n";

echo $supported;

echo "\nUnsupported\n\n";

echo $unsupported;

$supported = '';
$unsupported = '';
$windows = '';

foreach ($formats as $char => $description) {
	$s = fbDateTime::strftime('%' . $char, $date);
	$result = sprintf("%3s: %-40s: %s\n", '%' . $char, "'" . $s . "'", $description);
	if ($s !== false && ($s != $char || $char == '%')) {
		if (strpos($char, '#') !== false) {
			$windows .= $result;
		} else {
			$supported .= $result;
		}
	} else {
		$unsupported .= $result;
	}
}

echo "\nfbLocale::strftime()\n\n";

echo "Supported\n\n";

echo $supported;

echo "\nUnsupported\n\n";

echo $unsupported;

?>
</pre>
<address>
$CVSHeader: _freebeer/www/demo/DateTime.php,v 1.2 2004/03/07 17:51:33 ross Exp $
</address>

</body>
</html>
