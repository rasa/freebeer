<?php

// $CVSHeader: _freebeer/tests/Test_TicketAgent.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/TicketAgent.php';

class _Test_TicketAgent extends fbTestCase {

	function _Test_TicketAgent($name) {
        parent::__construct($name);
	}

	function setUp() {
	}

	function tearDown() {
	}

	function test_issueticket_1() {
		if (isset($_SESSION)) unset($_SESSION);
		$rv = fbTicketAgent::issueTicket();
		$this->assertTrue($rv);
	}

	function test_isticketvalid_1() {
		if (isset($_SESSION)) unset($_SESSION);
		fbTicketAgent::issueTicket();
		$this->assertTrue(fbTicketAgent::isTicketValid());
	}

	function test_invalidateticket_1() {
		if (isset($_SESSION)) unset($_SESSION);
		fbTicketAgent::issueTicket();
		fbTicketAgent::invalidateTicket();
		$this->assertFalse(fbTicketAgent::isTicketValid());
	}

	function test_getticketname_1() {
		if (isset($_SESSION)) unset($_SESSION);
		fbTicketAgent::issueTicket();
		$this->assertTrue(fbTicketAgent::getTicketName());
	}

	function test_getticketcount_1() {
		if (isset($_SESSION)) unset($_SESSION);
		fbTicketAgent::issueTicket();
		$this->assertEquals(1, fbTicketAgent::getTicketCount());
		fbTicketAgent::invalidateTicket();
		$this->assertEquals(2, fbTicketAgent::getTicketCount());
	}

	function test_getticketipaddress_1() {
		if (isset($_SESSION)) unset($_SESSION);
		fbTicketAgent::issueTicket();
		$this->assertTrue(!is_null(fbTicketAgent::getTicketIPAddress()));
	}

	function test_gettickettime_1() {
		if (isset($_SESSION)) unset($_SESSION);
		fbTicketAgent::issueTicket();
		$this->assertTrue(fbTicketAgent::getTicketTime());
	}

}

?>
