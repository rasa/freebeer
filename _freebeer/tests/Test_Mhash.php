<?php

// $CVSHeader: _freebeer/tests/Test_Mhash.php,v 1.3 2004/03/08 04:29:17 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/Mhash.php';

/*

See http://www.faqs.org/rfcs/rfc2202.html
    (RFC 2202 "Test Cases for HMAC-MD5 and HMAC-SHA-1" pp. 2-3)

2. Test Cases for HMAC-MD5

key =           0x0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b
key_len =       16
data =          "Hi There"
data_len =      8
digest =        0x9294727a3638bb1c13f48ef8158bfc9d

test_case =     2
key =           "Jefe"
key_len =       4
data =          "what do ya want for nothing?"
data_len =      28
digest =        0x750c783e6ab0b503eaa86e310a5db738

test_case =     3
key =           0xaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
key_len =       16
data =          0xdd repeated 50 times
data_len =      50
digest =        0x56be34521d144c88dbb8c733f0e8b3f6

test_case =     4
key =           0x0102030405060708090a0b0c0d0e0f10111213141516171819
key_len =       25
data =          0xcd repeated 50 times
data_len =      50
digest =        0x697eaf0aca3a3aea3a75164746ffaa79

test_case =     5
key =           0x0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c
key_len =       16
data =          "Test With Truncation"
data_len =      20
digest =        0x56461ef2342edc00f9bab995690efd4c
digest-96 =     0x56461ef2342edc00f9bab995

test_case =     6
key =           0xaa repeated 80 times
key_len =       80
data =          "Test Using Larger Than Block-Size Key - Hash Key First"
data_len =      54
digest =        0x6b1ab7fe4bd7bf8f0b62e6ce61b9d0cd

test_case =     7
key =           0xaa repeated 80 times
key_len =       80
data =          "Test Using Larger Than Block-Size Key and Larger Than One Block-Size Data"
data_len =      73
digest =        0x6f630fad67cda0ee1fb1f562db3aa53e

3. Test Cases for HMAC-SHA-1

test_case =     1
key =           0x0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b
key_len =       20
data =          "Hi There"
data_len =      8
digest =        0xb617318655057264e28bc0b6fb378c8ef146be00

test_case =     2
key =           "Jefe"
key_len =       4
data =          "what do ya want for nothing?"
data_len =      28
digest =        0xeffcdf6ae5eb2fa2d27416d5f184df9c259a7c79

test_case =     3
key =           0xaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
key_len =       20
data =          0xdd repeated 50 times
data_len =      50
digest =        0x125d7342b9ac11cd91a39af48aa17b4f63f175d3

test_case =     4
key =           0x0102030405060708090a0b0c0d0e0f10111213141516171819
key_len =       25
data =          0xcd repeated 50 times
data_len =      50
digest =        0x4c9007f4026250c6bc8414f9bf50c86c2d7235da

test_case =     5
key =           0x0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c
key_len =       20
data =          "Test With Truncation"
data_len =      20
digest =        0x4c1a03424b55e07fe7f27be1d58bb9324a9a5a04
digest-96 =     0x4c1a03424b55e07fe7f27be1

test_case =     6
key =           0xaa repeated 80 times
key_len =       80
data =          "Test Using Larger Than Block-Size Key - Hash Key First"
data_len =      54
digest =        0xaa4ae5e15272d00e95705637ce8a3b55ed402112

test_case =     7
key =           0xaa repeated 80 times
key_len =       80
data =          "Test Using Larger Than Block-Size Key and Larger Than One Block-Size Data"
data_len =      73
digest =        0xe8e99d0f45237d786d6bbaa7965c7808bbff1a91

*/

class _Test_MHash extends fbTestCase {

    function _Test_MHash($name) {
        parent::__construct($name);
    }

	function setUp() {
	}

	function tearDown() {
	}

	function _test() {
		static $test = null;
		
		if (is_null($test)) {
			$test = !extension_loaded('mhash');
			if (!$test) {
				trigger_error('Unable to fully test fbMhash class as mhash extension is loaded.', E_USER_NOTICE);
			}
		}

		return $test;
	}

	function _test_md5($key, $data, $digest) {
		if (!$this->_test()) {
			return;
		}

		$rv = mhash(MHASH_MD5, $data, $key);
		$rv = bin2hex($rv);
		$this->AssertEquals($digest, $rv);

//		$rv = fbMhash::mhashhex(MHASH_MD5, $data, $key);
//		$this->AssertEquals($digest, $rv);
	}

	function _test_sha1($key, $data, $digest) {
		static $skip_sha1 = null;

		if (!$this->_test()) {
			return;
		}
		
		if (is_null($skip_sha1)) {
			$skip_sha1 = !extension_loaded('mhash') && phpversion() == '4.1.0';
		}
		
		if ($skip_sha1) {
			trigger_error('fbSHA1 functions return incorrect results in PHP 4.1.0. Please upgrade PHP.', E_USER_WARNING);
			return;
		}

		$rv = mhash(MHASH_SHA1, $data, $key);
		$rv = bin2hex($rv);
		$this->AssertEquals($digest, $rv);

//		$rv = fbMhash::mhashhex(MHASH_SHA1, $data, $key);
//		$this->AssertEquals($digest, $rv);
	}

	function test_md5_1() {
		$key =           '0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b';
		$data =          "Hi There";
		$digest =        '9294727a3638bb1c13f48ef8158bfc9d';

		$key = pack('H*', $key);

		$this->_test_md5($key, $data, $digest);
	}

	function test_md5_2() {
		$key =           "Jefe";
		$data =          "what do ya want for nothing?";
		$digest =        '750c783e6ab0b503eaa86e310a5db738';

		$this->_test_md5($key, $data, $digest);
	}

	function test_md5_3() {
		$key =           'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
		$data =          str_repeat("\xdd", 50); // 0xdd repeated 50 times;
		$digest =        '56be34521d144c88dbb8c733f0e8b3f6';

		$key = pack('H*', $key);

		$this->_test_md5($key, $data, $digest);
	}

	function test_md5_4() {
		$key =           '0102030405060708090a0b0c0d0e0f10111213141516171819';
		$data =          str_repeat("\xcd", 50); // 0xcd repeated 50 times;
		$digest =        '697eaf0aca3a3aea3a75164746ffaa79';

		$key = pack('H*', $key);

		$this->_test_md5($key, $data, $digest);
	}

	function test_md5_5() {
		$key =           '0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c';
		$data =          "Test With Truncation";
		$digest =        '56461ef2342edc00f9bab995690efd4c';

		$key = pack('H*', $key);

		$this->_test_md5($key, $data, $digest);
	}

	function test_md5_6() {
		$key =           str_repeat("\xaa", 80); // 0xaa repeated 80 times;
		$data =          "Test Using Larger Than Block-Size Key - Hash Key First";
		$digest =        '6b1ab7fe4bd7bf8f0b62e6ce61b9d0cd';

		$this->_test_md5($key, $data, $digest);
	}

	function test_md5_7() {
		$key =           str_repeat("\xaa", 80); // 0xaa repeated 80 times;
		$data =          "Test Using Larger Than Block-Size Key and Larger Than One Block-Size Data";
		$digest =        '6f630fad67cda0ee1fb1f562db3aa53e';

		$this->_test_md5($key, $data, $digest);
	}

	function test_sha1_1() {
		$key =           '0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b';
		$data =          "Hi There";
		$digest =        'b617318655057264e28bc0b6fb378c8ef146be00';

		$key = pack('H*', $key);

		$this->_test_sha1($key, $data, $digest);
	}

	function test_sha1_2() {
		$key =           "Jefe";
		$data =          "what do ya want for nothing?";
		$digest =        'effcdf6ae5eb2fa2d27416d5f184df9c259a7c79';

		$this->_test_sha1($key, $data, $digest);
	}

	function test_sha1_3() {
		$key =           'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
		$data =          str_repeat("\xdd", 50); // 0xdd repeated 50 times;
		$digest =        '125d7342b9ac11cd91a39af48aa17b4f63f175d3';

		$key = pack('H*', $key);

		$this->_test_sha1($key, $data, $digest);
	}

	function test_sha1_4() {
		$key =           '0102030405060708090a0b0c0d0e0f10111213141516171819';
		$data =          str_repeat("\xcd", 50); // 0xcd repeated 50 times;
		$digest =        '4c9007f4026250c6bc8414f9bf50c86c2d7235da';

		$key = pack('H*', $key);

		$this->_test_sha1($key, $data, $digest);
	}

	function test_sha1_5() {
		$key =           '0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c0c';
		$data =          "Test With Truncation";
		$digest =        '4c1a03424b55e07fe7f27be1d58bb9324a9a5a04';

		$key = pack('H*', $key);

		$this->_test_sha1($key, $data, $digest);
	}

	function test_sha1_6() {
		$key =          str_repeat("\xaa", 80); // 0xaa repeated 80 times;
		$data =          "Test Using Larger Than Block-Size Key - Hash Key First";
		$digest =        'aa4ae5e15272d00e95705637ce8a3b55ed402112';

		$this->_test_sha1($key, $data, $digest);
	}

	function test_sha1_7() {
		$key  =          str_repeat("\xaa", 80); // 0xaa repeated 80 times;
		$data =          "Test Using Larger Than Block-Size Key and Larger Than One Block-Size Data";
		$digest =        'e8e99d0f45237d786d6bbaa7965c7808bbff1a91';

		$this->_test_sha1($key, $data, $digest);
	}

}

?>
<?php /*
	// \todo Implement test_mhashhex_1 in Test_Mhash.php
	function test_mhashhex_1() {
//		$o =& new fbMhash();
//		$rv = $o->mhashhex();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
