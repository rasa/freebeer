<?php

// test writing binary data to file
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

?>
