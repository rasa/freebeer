<?php

// $CVSHeader: _freebeer/lib/ADOdb/test_binary.php,v 1.2 2004/03/07 17:51:17 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

// test writing binary data to file
/*
$sql = "DELETE FROM sessions";
$conn->Execute($sql);
for ($i = 0; $i < 256; ++$i) {
	$q = $conn->quote(chr($i));
	echo "q=$q<br />\n";
	for ($j = 0; $j < strlen($q); ++$j) {
		printf("%d: %d (%s)<br />\n", $j + 1, ord($q{$j}), $q{$j});
	}
	$sql = "INSERT INTO sessions (sesskey, expiry, data, expireref) VALUES ('$i', 0, $q, '')";
	$rs = $conn->Execute($sql);
	ADODB_Session::_dumprs($rs);
}
*/

?>
