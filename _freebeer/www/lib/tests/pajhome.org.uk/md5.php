<?php

// $CVSHeader: _freebeer/www/lib/tests/pajhome.org.uk/md5.php,v 1.1.1.1 2004/01/18 00:12:08 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
 	dirname(dirname(dirname(dirname(dirname(__FILE__))))));

$test_name = '/opt/pajhome.org.uk/md5.js';

include_once FREEBEER_BASE . '/www/lib/tests/_header.php';

?>
<script language="JavaScript" type="text/javascript" src="<?php echo $test_name ?>"></script>
<script language="JavaScript" type="text/javascript" src="/lib/bin2hex.js"></script>
<script language="JavaScript" type="text/javascript">
<!-- // <![CDATA[

function test_hex_hmac_md5_1() {
	var key		= ''; // '0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b';
	var data	= "Hi There";
	var digest	= '9294727a3638bb1c13f48ef8158bfc9d';

	for (var i = 0; i < 16; ++i) {
		key += String.fromCharCode(0x0b);
	}

	var rv = hex_hmac_md5(key, data);

	assertEquals(digest, rv);
}

function test_hex_hmac_md5_2() {
	var key =           "Jefe";
	var data =          "what do ya want for nothing?";
	var digest =        '750c783e6ab0b503eaa86e310a5db738';

	var rv = hex_hmac_md5(key, data);

	assertEquals(digest, rv);
}

function test_hex_hmac_md5_3() {
	var key =           '';	// 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
	var data =          ''; // str_repeat("\xdd", 50); // 0xdd repeated 50 times;
	var digest =        '56be34521d144c88dbb8c733f0e8b3f6';

	for (var i = 0; i < 16; ++i) {
		key += String.fromCharCode(0xaa);
	}

	for (var i = 0; i < 50; ++i) {
		data += String.fromCharCode(0xdd);
	}

//	key		= repeat(String.fromCharCode(0xaa), 16);
//	data	= repeat(String.fromCharCode(0xdd), 50);

	var rv = hex_hmac_md5(key, data);

	assertEquals(digest, rv);
}

function test_hex_hmac_md5_4() {
	var key =           '0102030405060708090a0b0c0d0e0f10111213141516171819';
	var data =          ''; // str_repeat("\xcd", 50); // 0xcd repeated 50 times;
	var digest =        '697eaf0aca3a3aea3a75164746ffaa79';

	var key = hex2bin(key);

	for (var i = 0; i < 50; ++i) {
		data += String.fromCharCode(0xcd);
	}

//	data	= repeat(String.fromCharCode(0xcd), 50);

	var rv = hex_hmac_md5(key, data);

	assertEquals(digest, rv);
}

function test_hex_hmac_md5_5() {
	var key =           ''; // '0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c';
	var data =          "Test With Truncation";
	var digest =        '56461ef2342edc00f9bab995690efd4c';

	for (var i = 0; i < 16; ++i) {
		key += String.fromCharCode(0x0c);
	}

//	key	= repeat(String.fromCharCode(0x0c), 16);

	var rv = hex_hmac_md5(key, data);

	assertEquals(digest, rv);
}

function test_hex_hmac_md5_6() {
	var key =           ''; // str_repeat("\xaa", 80); // 0xaa repeated 80 times;
	var data =          "Test Using Larger Than Block-Size Key - Hash Key First";
	var digest =        '6b1ab7fe4bd7bf8f0b62e6ce61b9d0cd';

	for (var i = 0; i < 80; ++i) {
		key += String.fromCharCode(0xaa);
	}

//	key	= repeat(String.fromCharCode(0xaa), 80);

	var rv = hex_hmac_md5(key, data);

	assertEquals(digest, rv);
}

function test_hex_hmac_md5_7() {
	var key =           ''; // str_repeat("\xaa", 80); // 0xaa repeated 80 times;
	var data =          "Test Using Larger Than Block-Size Key and Larger Than One Block-Size Data";
	var digest =        '6f630fad67cda0ee1fb1f562db3aa53e';

	for (var i = 0; i < 80; ++i) {
		key += String.fromCharCode(0xaa);
	}

//	key	= repeat(String.fromCharCode(0xaa), 50);

	var rv = hex_hmac_md5(key, data);

	assertEquals(digest, rv);
}

// ]]> -->
</script>
<?php

include_once FREEBEER_BASE . '/www/lib/tests/_footer.php';

?>
