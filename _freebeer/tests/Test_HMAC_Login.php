<?php

// $CVSHeader: _freebeer/tests/Test_HMAC_Login.php,v 1.2 2004/03/07 17:51:26 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/HMAC_Login.php';

require_once FREEBEER_BASE . '/lib/Mhash.php';

class _Test_HMAC_Login extends fbTestCase {

    function __construct($name) {
        parent::__construct($name);
    }

    function _Test_HMAC_Login($name) {
        parent::__construct($name);
    }

	function setUp() {
		$this->host		= 'localhost';
		$this->user		= 'root';
		$this->password	= '';
		$this->database	= 'hmac_login';
		$this->driver	= 'mysql';
	}

	function tearDown() {
	}

	function &newObject() {
		$n = null;
		return $n;
	}

	function test_validate_safe_password() {
		$hmac_login = &$this->newObject();
		if (!$hmac_login) {
			return;
		}
		
		$rv = $hmac_login->connect($this->host, $this->user, $this->password, $this->database, $this->driver);
		$this->AssertTrue($rv, 'connect(): \'' . $hmac_login->getLastError() . '\'');

		if (!$rv) {
			return;
		}

		$challenge = $hmac_login->getChallenge();
		$this->AssertTrue($challenge, 'getChallenge(): \'' . $hmac_login->getLastError() . '\'');

		$login		= 'login';
		$password	= 'password';

		$response	= bin2hex(mhash(MHASH_MD5, $password, $challenge));

		$rv = $hmac_login->validate($challenge, $response, $login, '');
		$this->AssertTrue($rv, 'validate(): \'' . $hmac_login->getLastError() . '\'');
		
		$this->AssertEquals($hmac_login->getLastErrno(), FB_HMAC_LOGIN_ERROR_OK);
	}

	function test_validate_bad_password() {
		$hmac_login = &$this->newObject();
		if (!$hmac_login) {
			return;
		}

		$rv = $hmac_login->connect($this->host, $this->user, $this->password, $this->database);
		$this->AssertTrue($rv, 'connect(): \'' . $hmac_login->getLastError() . '\'');

		if (!$rv) {
			return;
		}

		$challenge = $hmac_login->getChallenge();
		$this->AssertTrue($challenge, 'getChallenge(): \'' . $hmac_login->getLastError() . '\'');

		$login		= 'login';
		$password	= 'bad_password';

		$response	= bin2hex(mhash(MHASH_MD5, $password, $challenge));

		$rv = $hmac_login->validate($challenge, $response, $login, '');
		$this->AssertFalse($rv, 'validate(): \'' . $hmac_login->getLastError() . '\'');

		$this->AssertEquals($hmac_login->getLastErrno(), FB_HMAC_LOGIN_ERROR_BAD_PASSWORD);
	}

	function test_validate_unsafe_password() {
		$hmac_login = &$this->newObject();
		if (!$hmac_login) {
			return;
		}

		$rv = $hmac_login->connect($this->host, $this->user, $this->password, $this->database);
		$this->AssertTrue($rv, 'connect(): \'' . $hmac_login->getLastError() . '\'');

		if (!$rv) {
			return;
		}

		$challenge = $hmac_login->getChallenge();
		$this->AssertTrue($challenge, 'getChallenge(): \'' . $hmac_login->getLastError() . '\'');

		$login		= 'login';
		$password	= 'password';

		$response	= bin2hex(mhash(MHASH_MD5, $password, $challenge));

		$rv = $hmac_login->validate($challenge, '', $login, $password);
		$this->AssertTrue($rv, 'validate(): \'' . $hmac_login->getLastError());

		$this->AssertEquals($hmac_login->getLastErrno(), FB_HMAC_LOGIN_ERROR_UNSAFE_PASSWORD);
	}


	function test_validate_unsafe_bad_password() {
		$hmac_login = &$this->newObject();
		if (!$hmac_login) {
			return;
		}

		$rv = $hmac_login->connect($this->host, $this->user, $this->password, $this->database);
		$this->AssertTrue($rv, 'connect(): \'' . $hmac_login->getLastError() . '\'');

		if (!$rv) {
			return;
		}

		$challenge = $hmac_login->getChallenge();
		$this->AssertTrue($challenge, 'getChallenge(): \'' . $hmac_login->getLastError() . '\'');

		$login		= 'login';
		$password	= 'bad_password';

		$response	= bin2hex(mhash(MHASH_MD5, $password, $challenge));

		$rv = $hmac_login->validate($challenge, '', $login, $password);
		$this->AssertFalse($rv, 'validate(): \'' . $hmac_login->getLastError());

		$this->AssertEquals($hmac_login->getLastErrno(), FB_HMAC_LOGIN_ERROR_UNSAFE_BAD_PASSWORD);
	}


}

?>
<?php /*
	// \todo Implement test_getlasterrno_1 in Test_HMAC_Login.php
	function test_getlasterrno_1() {
//		$o = &new fbHMAC_Login();
//		$rv = $o->getlasterrno();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getlasterror_1 in Test_HMAC_Login.php
	function test_getlasterror_1() {
//		$o = &new fbHMAC_Login();
//		$rv = $o->getlasterror();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_setmaxattempts_1 in Test_HMAC_Login.php
	function test_setmaxattempts_1() {
//		$o = &new fbHMAC_Login();
//		$rv = $o->setmaxattempts();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_setchallengetable_1 in Test_HMAC_Login.php
	function test_setchallengetable_1() {
//		$o = &new fbHMAC_Login();
//		$rv = $o->setchallengetable();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_setlogintable_1 in Test_HMAC_Login.php
	function test_setlogintable_1() {
//		$o = &new fbHMAC_Login();
//		$rv = $o->setlogintable();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_setloginfield_1 in Test_HMAC_Login.php
	function test_setloginfield_1() {
//		$o = &new fbHMAC_Login();
//		$rv = $o->setloginfield();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_setpasswordfield_1 in Test_HMAC_Login.php
	function test_setpasswordfield_1() {
//		$o = &new fbHMAC_Login();
//		$rv = $o->setpasswordfield();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_settimeout_1 in Test_HMAC_Login.php
	function test_settimeout_1() {
//		$o = &new fbHMAC_Login();
//		$rv = $o->settimeout();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_connect_1 in Test_HMAC_Login.php
	function test_connect_1() {
//		$o = &new fbHMAC_Login();
//		$rv = $o->connect();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_close_1 in Test_HMAC_Login.php
	function test_close_1() {
//		$o = &new fbHMAC_Login();
//		$rv = $o->close();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getchallenge_1 in Test_HMAC_Login.php
	function test_getchallenge_1() {
//		$o = &new fbHMAC_Login();
//		$rv = $o->getchallenge();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_validate_1 in Test_HMAC_Login.php
	function test_validate_1() {
//		$o = &new fbHMAC_Login();
//		$rv = $o->validate();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getpassword_1 in Test_HMAC_Login.php
	function test_getpassword_1() {
//		$o = &new fbHMAC_Login();
//		$rv = $o->getpassword();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_deleteunused_1 in Test_HMAC_Login.php
	function test_deleteunused_1() {
//		$o = &new fbHMAC_Login();
//		$rv = $o->deleteunused();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_deleteused_1 in Test_HMAC_Login.php
	function test_deleteused_1() {
//		$o = &new fbHMAC_Login();
//		$rv = $o->deleteused();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_deletepercentage_1 in Test_HMAC_Login.php
	function test_deletepercentage_1() {
//		$o = &new fbHMAC_Login();
//		$rv = $o->deletepercentage();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_checkaddress_1 in Test_HMAC_Login.php
	function test_checkaddress_1() {
//		$o = &new fbHMAC_Login();
//		$rv = $o->checkaddress();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_checkreferer_1 in Test_HMAC_Login.php
	function test_checkreferer_1() {
//		$o = &new fbHMAC_Login();
//		$rv = $o->checkreferer();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_checkuseragent_1 in Test_HMAC_Login.php
	function test_checkuseragent_1() {
//		$o = &new fbHMAC_Login();
//		$rv = $o->checkuseragent();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
