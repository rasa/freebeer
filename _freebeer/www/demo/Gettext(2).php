<?php

// $CVSHeader: _freebeer/www/demo/Gettext(2).php,v 1.2 2004/03/07 17:51:33 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once './_demo.php';

require_once FREEBEER_BASE . '/lib/Gettext.php';

echo html_header_demo('fbGettext Class (gettext() Emulation)');

echo "<pre>\n";

bindtextdomain('freebeer', FREEBEER_BASE . '/lib/locale');
textdomain('freebeer');

$iso_codes = array (
	'it_IT' => 'Italian',
	'fr_FR' => 'French',
	'es_ES' => 'Spanish',
	'nl_NL' => 'Dutch',
	'en_US' => 'English',
	'pt_PT' => 'Portuguese',
);

foreach ($iso_codes as $iso_code => $language) {
	// \todo convert to one function!
	putenv('LANG=' . $iso_code);
	if (preg_match('/^win/i', PHP_OS)) {
		$rv = setlocale(LC_ALL, $language);
	} else {
		$rv = setlocale(LC_ALL, $iso_code);
	}
	printf("%-12s: %s\n", $language, _("24 hours"));
}
?>
</pre>
<address>
$CVSHeader: _freebeer/www/demo/Gettext(2).php,v 1.2 2004/03/07 17:51:33 ross Exp $
</address>

</body>
</html>
