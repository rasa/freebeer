<?php

// $CVSHeader: _freebeer/www/demo/Binary_Search.File.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

require_once './_demo.php';

require_once FREEBEER_BASE . '/lib/BinarySearch/File.php';

echo html_header_demo('fbBinarySearch_File Class (Binary Search a Sorted, Fixed Length Record File)');

@ini_set('max_execution_time', 0);

echo "<pre>\n";

$file = FREEBEER_BASE . '/etc/geo/geo-ips.txt';

$ips = array(
	'0.0.0.0'			=> '000.000.000.000 000.255.255.255 US',
	'10.0.0.1'			=> '010.000.000.000 010.255.255.255 I0',
	'10.255.255.255'	=> '010.000.000.000 010.255.255.255 I0',
	'127.0.0.1'			=> '127.000.000.000 127.255.255.255 L0',
	'127.0.0.2'			=> '127.000.000.000 127.255.255.255 L0',
	'127.255.255.255'	=> '127.000.000.000 127.255.255.255 L0',
	'172.16.0.0'		=> '172.016.000.000 172.031.255.255 I0',
	'172.31.255.255'	=> '172.016.000.000 172.031.255.255 I0',
	'192.168.0.0'		=> '192.168.000.000 192.168.255.255 I0',
	'192.168.0.1'		=> '192.168.000.000 192.168.255.255 I0',
	'192.168.255.255'	=> '192.168.000.000 192.168.255.255 I0',
	'217.194.16.0'		=> '217.194.016.000 217.194.031.255 EU',
	'217.194.16.1'		=> '217.194.016.000 217.194.031.255 EU',
	'255.0.0.0'			=> '221.097.211.000 255.255.255.255 US',
	'255.255.255.254'	=> '221.097.211.000 255.255.255.255 US',
	'255.255.255.255'	=> '255.255.255.255 255.255.255.255 --',
	'9.20.0.0'			=> '009.020.000.000 009.020.127.255 GB',
);

function _formatIP4address($ip, $format = '%03d.%03d.%03d.%03d') {
	$l = ip2long($ip);
	if ($l == -1 && $ip != '255.255.255.255') {
		return false;
	}

	$tuple1 = $l >> 24;
	if ($tuple1 < 0) {
		$tuple1 += 256;
	}

	$rv = sprintf($format, $tuple1, ($l >> 16) & 0xff, ($l >> 8)  & 0xff, $l & 0xff);
//printf("l=%s ip=%s rv=%s\n", $l, $ip, $rv);
	return $rv;
}

foreach($ips as $ip => $expected_result) {
	$ipf = _formatIP4address($ip);
	$bsf = &new fbBinarySearch_File($file);
	$found = $bsf->search($ipf);
	$found = trim($found);
	printf("%-20s: %s\n", $ip, $found);
}

?>

<address>
$CVSHeader: _freebeer/www/demo/Binary_Search.File.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $
</address>

</body>
</html>
