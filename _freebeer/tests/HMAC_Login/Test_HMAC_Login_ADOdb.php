<?php

// $CVSHeader: _freebeer/tests/HMAC_Login/Test_HMAC_Login_ADOdb.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/tests/Test_HMAC_Login.php';

require_once FREEBEER_BASE . '/lib/HMAC_Login/ADOdb.php';

class _Test_HMAC_Login_ADOdb extends _Test_HMAC_Login {

	function _Test_HMAC_Login_ADOdb($name) {
        parent::__construct($name);
	}

	function setUp() {
		parent::setUp();
	}

	function tearDown() {
		parent::tearDown();
	}

	function &newObject() {
		$hmac_login = &new fbHMAC_Login_ADOdb();
		return $hmac_login;
	}

}

?>
<?php /*
	// \todo Implement test_checkaddress_1 in Test_HMAC_Login_ADOdb.php
	function test_checkaddress_1() {
//		$o =& new ADOdb();
//		$rv = $o->checkaddress();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_checkreferer_1 in Test_HMAC_Login_ADOdb.php
	function test_checkreferer_1() {
//		$o =& new ADOdb();
//		$rv = $o->checkreferer();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_checkuseragent_1 in Test_HMAC_Login_ADOdb.php
	function test_checkuseragent_1() {
//		$o =& new ADOdb();
//		$rv = $o->checkuseragent();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_close_1 in Test_HMAC_Login_ADOdb.php
	function test_close_1() {
//		$o =& new ADOdb();
//		$rv = $o->close();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_connect_1 in Test_HMAC_Login_ADOdb.php
	function test_connect_1() {
//		$o =& new ADOdb();
//		$rv = $o->connect();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_deletepercentage_1 in Test_HMAC_Login_ADOdb.php
	function test_deletepercentage_1() {
//		$o =& new ADOdb();
//		$rv = $o->deletepercentage();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_deleteunused_1 in Test_HMAC_Login_ADOdb.php
	function test_deleteunused_1() {
//		$o =& new ADOdb();
//		$rv = $o->deleteunused();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_deleteused_1 in Test_HMAC_Login_ADOdb.php
	function test_deleteused_1() {
//		$o =& new ADOdb();
//		$rv = $o->deleteused();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getchallenge_1 in Test_HMAC_Login_ADOdb.php
	function test_getchallenge_1() {
//		$o =& new ADOdb();
//		$rv = $o->getchallenge();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getlasterrno_1 in Test_HMAC_Login_ADOdb.php
	function test_getlasterrno_1() {
//		$o =& new ADOdb();
//		$rv = $o->getlasterrno();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getlasterror_1 in Test_HMAC_Login_ADOdb.php
	function test_getlasterror_1() {
//		$o =& new ADOdb();
//		$rv = $o->getlasterror();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getpassword_1 in Test_HMAC_Login_ADOdb.php
	function test_getpassword_1() {
//		$o =& new ADOdb();
//		$rv = $o->getpassword();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_setchallengetable_1 in Test_HMAC_Login_ADOdb.php
	function test_setchallengetable_1() {
//		$o =& new ADOdb();
//		$rv = $o->setchallengetable();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_setloginfield_1 in Test_HMAC_Login_ADOdb.php
	function test_setloginfield_1() {
//		$o =& new ADOdb();
//		$rv = $o->setloginfield();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_setlogintable_1 in Test_HMAC_Login_ADOdb.php
	function test_setlogintable_1() {
//		$o =& new ADOdb();
//		$rv = $o->setlogintable();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_setmaxattempts_1 in Test_HMAC_Login_ADOdb.php
	function test_setmaxattempts_1() {
//		$o =& new ADOdb();
//		$rv = $o->setmaxattempts();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_setpasswordfield_1 in Test_HMAC_Login_ADOdb.php
	function test_setpasswordfield_1() {
//		$o =& new ADOdb();
//		$rv = $o->setpasswordfield();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_settimeout_1 in Test_HMAC_Login_ADOdb.php
	function test_settimeout_1() {
//		$o =& new ADOdb();
//		$rv = $o->settimeout();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_validate_1 in Test_HMAC_Login_ADOdb.php
	function test_validate_1() {
//		$o =& new ADOdb();
//		$rv = $o->validate();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
