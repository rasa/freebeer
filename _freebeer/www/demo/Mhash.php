<?php

// $CVSHeader: _freebeer/www/demo/Mhash.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

require_once './_demo.php';

require_once FREEBEER_BASE . '/lib/Mhash.php';

echo html_header_demo('fbMhash Class (mhash() Emulation)');

$data = 'data';
$key = 'key';

echo "<pre>\n";

echo "mhash(MHASH_MD5, '$data', '$key')=", 
	 mhash(MHASH_MD5, $data, $key), "\n";

echo "bin2hex(mhash(MHASH_MD5, '$data', '$key'))=   ",
	 bin2hex(mhash(MHASH_MD5, $data, $key)), "\n";

if (class_exists('fbMhash')) {
	echo "fbMhash::mhashhex(MHASH_MD5, '$data', '$key')=",
		 fbMhash::mhashhex(MHASH_MD5, $data, $key), "\n";
}

if (defined('MHASH_SHA1')) {
	echo "mhash(MHASH_SHA1, '$data', '$key')=",
		 mhash(MHASH_SHA1, $data, $key), "\n";

	echo "bin2hex(mhash(MHASH_SHA1, '$data', '$key'))=   ",
		 bin2hex(mhash(MHASH_SHA1, $data, $key)), "\n";

	if (class_exists('fbMhash')) {
		echo "fbMhash::mhashhex(MHASH_SHA1, '$data', '$key')=",
			 fbMhash::mhashhex(MHASH_SHA1, $data, $key), "\n";
	}
}

$hash_map = array(
	0	=> 'MHASH_CRC32',
	9	=> 'MHASH_CRC32B',
	8	=> 'MHASH_GOST',
	13	=> 'MHASH_HAVAL128',
	12	=> 'MHASH_HAVAL160',
	11	=> 'MHASH_HAVAL192',
	10	=> 'MHASH_HAVAL224',
	3	=> 'MHASH_HAVAL256',
	16	=> 'MHASH_MD4',
	1	=> 'MHASH_MD5',
	5	=> 'MHASH_RIPEMD160',
	2	=> 'MHASH_SHA1',
	17	=> 'MHASH_SHA256',
	7	=> 'MHASH_TIGER',
	14	=> 'MHASH_TIGER128',
	15	=> 'MHASH_TIGER160',
);

$nr = mhash_count();

for ($i = 0; $i < $nr; $i++) {
    echo sprintf("The blocksize of %s is %d\n", 
        mhash_get_hash_name($i),
        mhash_get_block_size($i));
}

?>
</pre>
<address>
$CVSHeader: _freebeer/www/demo/Mhash.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $
</address>

</body>
</html>
