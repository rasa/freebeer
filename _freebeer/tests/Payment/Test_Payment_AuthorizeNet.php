<?php

// $CVSHeader: _freebeer/tests/Payment/Test_Payment_AuthorizeNet.php,v 1.1 2004/03/07 19:16:21 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/Payment/AuthorizeNet.php';

class _Test_Payment_AuthorizeNet extends fbTestCase {

	function _Test_Payment_AuthorizeNet($name) {
        parent::__construct($name);
	}

	function setUp() {
	}

	function tearDown() {
	}

}

# make PHPUnit_GUI_SetupDecorator() happy
class _Payment_Test_Payment_AuthorizeNet extends _Test_Payment_AuthorizeNet {
}

?><?php /*
	// \todo Implement test_addfields_1 in Test_Payment_AuthorizeNet.php
	function test_addfields_1() {
//		$o =& new AuthorizeNet();
//		$rv = $o->addfields();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_fbpayment_1 in Test_Payment_AuthorizeNet.php
	function test_fbpayment_1() {
//		$o =& new AuthorizeNet();
//		$rv = $o->fbpayment();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_fbpayment_authorizenet_1 in Test_Payment_AuthorizeNet.php
	function test_fbpayment_authorizenet_1() {
//		$o =& new AuthorizeNet();
//		$rv = $o->fbpayment_authorizenet();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getauthorization_1 in Test_Payment_AuthorizeNet.php
	function test_getauthorization_1() {
//		$o =& new AuthorizeNet();
//		$rv = $o->getauthorization();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getavsaddress_1 in Test_Payment_AuthorizeNet.php
	function test_getavsaddress_1() {
//		$o =& new AuthorizeNet();
//		$rv = $o->getavsaddress();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getavszip_1 in Test_Payment_AuthorizeNet.php
	function test_getavszip_1() {
//		$o =& new AuthorizeNet();
//		$rv = $o->getavszip();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getcreditcardtype_1 in Test_Payment_AuthorizeNet.php
	function test_getcreditcardtype_1() {
//		$o =& new AuthorizeNet();
//		$rv = $o->getcreditcardtype();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getfields_1 in Test_Payment_AuthorizeNet.php
	function test_getfields_1() {
//		$o =& new AuthorizeNet();
//		$rv = $o->getfields();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getpath_1 in Test_Payment_AuthorizeNet.php
	function test_getpath_1() {
//		$o =& new AuthorizeNet();
//		$rv = $o->getpath();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getport_1 in Test_Payment_AuthorizeNet.php
	function test_getport_1() {
//		$o =& new AuthorizeNet();
//		$rv = $o->getport();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getresponse_1 in Test_Payment_AuthorizeNet.php
	function test_getresponse_1() {
//		$o =& new AuthorizeNet();
//		$rv = $o->getresponse();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getresultcode_1 in Test_Payment_AuthorizeNet.php
	function test_getresultcode_1() {
//		$o =& new AuthorizeNet();
//		$rv = $o->getresultcode();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getresultmessage_1 in Test_Payment_AuthorizeNet.php
	function test_getresultmessage_1() {
//		$o =& new AuthorizeNet();
//		$rv = $o->getresultmessage();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getserver_1 in Test_Payment_AuthorizeNet.php
	function test_getserver_1() {
//		$o =& new AuthorizeNet();
//		$rv = $o->getserver();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_gettransactionid_1 in Test_Payment_AuthorizeNet.php
	function test_gettransactionid_1() {
//		$o =& new AuthorizeNet();
//		$rv = $o->gettransactionid();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_isapproved_1 in Test_Payment_AuthorizeNet.php
	function test_isapproved_1() {
//		$o =& new AuthorizeNet();
//		$rv = $o->isapproved();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_isdeclined_1 in Test_Payment_AuthorizeNet.php
	function test_isdeclined_1() {
//		$o =& new AuthorizeNet();
//		$rv = $o->isdeclined();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_printresults_1 in Test_Payment_AuthorizeNet.php
	function test_printresults_1() {
//		$o =& new AuthorizeNet();
//		$rv = $o->printresults();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_resultmessages_1 in Test_Payment_AuthorizeNet.php
	function test_resultmessages_1() {
//		$o =& new AuthorizeNet();
//		$rv = $o->resultmessages();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_setfields_1 in Test_Payment_AuthorizeNet.php
	function test_setfields_1() {
//		$o =& new AuthorizeNet();
//		$rv = $o->setfields();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_setpath_1 in Test_Payment_AuthorizeNet.php
	function test_setpath_1() {
//		$o =& new AuthorizeNet();
//		$rv = $o->setpath();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_setport_1 in Test_Payment_AuthorizeNet.php
	function test_setport_1() {
//		$o =& new AuthorizeNet();
//		$rv = $o->setport();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_setrequireavs_1 in Test_Payment_AuthorizeNet.php
	function test_setrequireavs_1() {
//		$o =& new AuthorizeNet();
//		$rv = $o->setrequireavs();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_setserver_1 in Test_Payment_AuthorizeNet.php
	function test_setserver_1() {
//		$o =& new AuthorizeNet();
//		$rv = $o->setserver();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_settestcardtype_1 in Test_Payment_AuthorizeNet.php
	function test_settestcardtype_1() {
//		$o =& new AuthorizeNet();
//		$rv = $o->settestcardtype();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_settestmethodtype_1 in Test_Payment_AuthorizeNet.php
	function test_settestmethodtype_1() {
//		$o =& new AuthorizeNet();
//		$rv = $o->settestmethodtype();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_settestmode_1 in Test_Payment_AuthorizeNet.php
	function test_settestmode_1() {
//		$o =& new AuthorizeNet();
//		$rv = $o->settestmode();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_settestresponsetype_1 in Test_Payment_AuthorizeNet.php
	function test_settestresponsetype_1() {
//		$o =& new AuthorizeNet();
//		$rv = $o->settestresponsetype();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_settesttransactiontype_1 in Test_Payment_AuthorizeNet.php
	function test_settesttransactiontype_1() {
//		$o =& new AuthorizeNet();
//		$rv = $o->settesttransactiontype();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_submit_1 in Test_Payment_AuthorizeNet.php
	function test_submit_1() {
//		$o =& new AuthorizeNet();
//		$rv = $o->submit();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
