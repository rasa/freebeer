<?php

// $CVSHeader: _freebeer/www/demo/GeoIP.Free.Ascii.php,v 1.3 2004/03/07 17:51:33 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once './_demo.php';

require_once FREEBEER_BASE . '/lib/GeoIP/Free/Ascii.php';

echo html_header_demo('fbGeoIP_Free_Ascii Class');

echo "<pre>\n";

$ips = array(
	'0.0.0.0',
	'10.0.0.1',
	'10.255.255.255',
	'127.0.0.1',
	'127.0.0.2',
	'127.255.255.255',
	'127.255.255.255',
	'172.16.0.0',
	'172.31.255.255',
	'192.168.0.0',
	'192.168.0.1',
	'192.168.255.255',
	'217.194.16.0',
	'217.194.16.1',
	'255.0.0.0',
	'255.255.255.254',
	'255.255.255.255',
	'9.20.0.0',
);

foreach ($ips as $ip) {
	$rv = fbGeoIP_Free_Ascii::getCountryIDByIP($ip);
	printf("%-20s: '%s'\n", $ip, $rv);
}

echo "\n";
flush();

$hosts = array(
	'localhost',
	'sf.net',
);

foreach ($hosts as $host) {
	$rv = fbGeoIP_Free_Ascii::getCountryIDByHostName($host);
	printf("%-20s: '%s'\n", $host, $rv);
}

?>
<address>
$CVSHeader: _freebeer/www/demo/GeoIP.Free.Ascii.php,v 1.3 2004/03/07 17:51:33 ross Exp $
</address>

</body>
</html>
