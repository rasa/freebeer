<?php

// $CVSHeader: _freebeer/www/demo/GeoIP.Free.Binary.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

require_once './_demo.php';

require_once FREEBEER_BASE . '/lib/GeoIP/Free/Binary.php';

echo html_header_demo('fbGeoIP_Free_Binary Class');

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
	$rv = fbGeoIP_Free_Binary::getCountryIDByIP($ip);
	printf("%-20s: '%s'\n", $ip, $rv);
}

echo "\n";

$hosts = array(
	'localhost',
	'grapevineproject.org',
	'blacksapphire.com',
);

foreach ($hosts as $host) {
	$rv = fbGeoIP_Free_Binary::getCountryIDByHostName($host);
	printf("%-20s: '%s'\n", $host, $rv);
}

?>
<address>
$CVSHeader: _freebeer/www/demo/GeoIP.Free.Binary.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $
</address>

</body>
</html>
