<?php

// $CVSHeader: _freebeer/tests/Payment/Test_Payment_Constants.php,v 1.1 2004/03/07 19:16:21 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/Payment/Constants.php';

class _Test_Payment_Constants extends fbTestCase {

	function _Test_Payment_Constants($name) {
        parent::__construct($name);
	}

	function setUp() {
	}

	function tearDown() {
	}

}

# make PHPUnit_GUI_SetupDecorator() happy
class _Payment_Test_Payment_Constants extends _Test_Payment_Constants {
}

?><?php /*
	// \todo Implement test_creditcardtypes_1 in Test_Payment_Constants.php
	function test_creditcardtypes_1() {
//		$o =& new Constants();
//		$rv = $o->creditcardtypes();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_methodtypes_1 in Test_Payment_Constants.php
	function test_methodtypes_1() {
//		$o =& new Constants();
//		$rv = $o->methodtypes();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_responsetypes_1 in Test_Payment_Constants.php
	function test_responsetypes_1() {
//		$o =& new Constants();
//		$rv = $o->responsetypes();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_statustypes_1 in Test_Payment_Constants.php
	function test_statustypes_1() {
//		$o =& new Constants();
//		$rv = $o->statustypes();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_transactiontypes_1 in Test_Payment_Constants.php
	function test_transactiontypes_1() {
//		$o =& new Constants();
//		$rv = $o->transactiontypes();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
