<?php

// $CVSHeader: _freebeer/www/lib/tests/pajhome.org.uk/sha1.php,v 1.2 2004/03/07 17:51:36 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') : 
 	dirname(dirname(dirname(dirname(dirname(__FILE__))))));

$test_name = '/opt/pajhome.org.uk/sha1.js';

include_once FREEBEER_BASE . '/www/lib/tests/_header.php';

?>
<script language="JavaScript" type="text/javascript" src="<?php echo $test_name ?>"></script>
<script language="JavaScript" type="text/javascript" src="/lib/bin2hex.js"></script>
<script language="JavaScript" type="text/javascript">
<!-- // <![CDATA[

function test_hex_hmac_sha1_1() {
	var key =           ''; // '0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b';
	var data =          "Hi There";
	var digest =        'b617318655057264e28bc0b6fb378c8ef146be00';

	for (var i = 0; i < 20; ++i) {
		key += String.fromCharCode(0x0b);
	}

	var rv = hex_hmac_sha1(key, data);

	assertEquals(digest, rv);
}

function test_hex_hmac_sha1_2() {
	var key =           "Jefe";
	var data =          "what do ya want for nothing?";
	var digest =        'effcdf6ae5eb2fa2d27416d5f184df9c259a7c79';

	var rv = hex_hmac_sha1(key, data);

	assertEquals(digest, rv);
}

function test_hex_hmac_sha1_3() {
	var key =           ''; // 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
	var data =          ''; // str_repeat("\xdd", 50); // 0xdd repeated 50 times;
	var digest =        '125d7342b9ac11cd91a39af48aa17b4f63f175d3';

	for (var i = 0; i < 20; ++i) {
		key += String.fromCharCode(0xaa);
	}

	for (var i = 0; i < 50; ++i) {
		data += String.fromCharCode(0xdd);
	}

//	key		= repeat(String.fromCharCode(0xaa), 16);
//	data	= repeat(String.fromCharCode(0xdd), 50);

	var rv = hex_hmac_sha1(key, data);

	assertEquals(digest, rv);
}

function test_hex_hmac_sha1_4() {
	var key =           '0102030405060708090a0b0c0d0e0f10111213141516171819';
	var data =          ''; // str_repeat("\xcd", 50); // 0xcd repeated 50 times;
	var digest =        '4c9007f4026250c6bc8414f9bf50c86c2d7235da';

	var key = hex2bin(key);

	for (var i = 0; i < 50; ++i) {
		data += String.fromCharCode(0xcd);
	}

//	data	= repeat(String.fromCharCode(0xcd), 50);

	var rv = hex_hmac_sha1(key, data);

	assertEquals(digest, rv);
}

function test_hex_hmac_sha1_5() {
	var key =           ''; // '0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c';
	var data =          "Test With Truncation";
	var digest =        '4c1a03424b55e07fe7f27be1d58bb9324a9a5a04';

	for (var i = 0; i < 20; ++i) {
		key += String.fromCharCode(0x0c);
	}

//	key	= repeat(String.fromCharCode(0x0c), 16);

	var rv = hex_hmac_sha1(key, data);

	assertEquals(digest, rv);
}

function test_hex_hmac_sha1_6() {
	var key =           ''; // str_repeat("\xaa", 80); // 0xaa repeated 80 times;
	var data =          "Test Using Larger Than Block-Size Key - Hash Key First";
	var digest =        'aa4ae5e15272d00e95705637ce8a3b55ed402112';

	for (var i = 0; i < 80; ++i) {
		key += String.fromCharCode(0xaa);
	}

//	key	= repeat(String.fromCharCode(0xaa), 80);

	var rv = hex_hmac_sha1(key, data);

	assertEquals(digest, rv);
}

function test_hex_hmac_sha1_7() {
	var key  =          ''; // str_repeat("\xaa", 80); // 0xaa repeated 80 times;
	var data =          "Test Using Larger Than Block-Size Key and Larger Than One Block-Size Data";
	var digest =        'e8e99d0f45237d786d6bbaa7965c7808bbff1a91';

	for (var i = 0; i < 80; ++i) {
		key += String.fromCharCode(0xaa);
	}

//	key	= repeat(String.fromCharCode(0xaa), 50);

	var rv = hex_hmac_sha1(key, data);

	assertEquals(digest, rv);
}

// ]]> -->
</script>
<?php

include_once FREEBEER_BASE . '/www/lib/tests/_footer.php';

?>
