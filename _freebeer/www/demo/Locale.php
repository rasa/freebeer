<?php

// $CVSHeader: _freebeer/www/demo/Locale.php,v 1.2 2004/03/07 17:51:33 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once './_demo.php';

echo html_header_demo('fbLocale Class');

echo "<pre>\n";

require_once FREEBEER_BASE . '/lib/Locale.php';

//exit;

$iso_codes = array (
	'it_IT' => 'Italian',
	'fr_FR' => 'French',
	'es_ES' => 'Spanish',
	'nl_NL' => 'Dutch',
	'en_US' => 'English',
	'pt_PT' => 'Portuguese',
);

echo "fbLocale::getLocale(LC_ALL)=",fbLocale::getLocale(LC_ALL),"\n";

foreach ($iso_codes as $iso_code => $language) {
//	putenv('LANG=' . $iso_code);
	fbLocale::setLocale(LC_ALL, $iso_code);
/*	
	// \todo convert to one function!
	putenv('LANG=' . $iso_code);
	if (preg_match('/^win/i', PHP_OS)) {
		$rv = setlocale(LC_ALL, $language);
	} else {
		$rv = setlocale(LC_ALL, $iso_code);
	}
*/
	printf("%-12s: %s\n", $language, strftime("%A %a %B %b %c\n"));

	fbLocale::setLocale(LC_ALL, 'en_US');
}
?>

</pre>
<address>
$CVSHeader: _freebeer/www/demo/Locale.php,v 1.2 2004/03/07 17:51:33 ross Exp $
</address>

</body>
</html>
