<?php

// $CVSHeader: _freebeer/www/demo/Gettext(3).php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

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
	fbLocale::setLocale(LC_ALL, $iso_code);
	printf("%-12s: %-s\t%-15s\t%-s\t%-15s\t%-s\n", $language, gettext("24 hours"), strftime("%A"), strftime("%a"), strftime("%B"), strftime("%b\t%c"));
	fbLocale::setLocale(LC_ALL, 'en_US');
}
?>
</pre>
<address>
$CVSHeader: _freebeer/www/demo/Gettext(3).php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $
</address>

</body>
</html>
