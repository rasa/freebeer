<?php

// $CVSHeader: _freebeer/tests/Test_HTTP.php,v 1.1.1.1 2004/01/18 00:12:06 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/HTTP.php';

class _Test_HTTP extends fbTestCase {

	function _Test_HTTP($name) {
        parent::__construct($name);
	}

	function setUp() {
	}

	function tearDown() {
	}

	function test_getIPAddressType() {
		$ips = array(
			'0.0.0.0'		=> 'external',
			'10.0.0.1'		=> 'internal',
			'127.0.0.1'		=> 'loopback',
			'172.16.0.1'	=> 'internal',
			'192.168.0.1'	=> 'internal',
			'255.0.0.1'		=> 'external',
		);
		foreach ($ips as $ip => $expected) {
			$rv = fbHTTP::getIPAddressType($ip);
			$this->assertEquals($expected, $rv);
		}
	}

}

?>
<?php /*
	// \todo Implement test_getremoteaddress_1 in Test_HTTP.php
	function test_getremoteaddress_1() {
//		$o =& new fbHTTP();
//		$rv = $o->getremoteaddress();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getrequestvar_1 in Test_HTTP.php
	function test_getrequestvar_1() {
//		$o =& new fbHTTP();
//		$rv = $o->getrequestvar();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_redirect_1 in Test_HTTP.php
	function test_redirect_1() {
//		$o =& new fbHTTP();
//		$rv = $o->redirect();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_sendlastmodified_1 in Test_HTTP.php
	function test_sendlastmodified_1() {
//		$o =& new fbHTTP();
//		$rv = $o->sendlastmodified();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_sendnocacheheaders_1 in Test_HTTP.php
	function test_sendnocacheheaders_1() {
//		$o =& new fbHTTP();
//		$rv = $o->sendnocacheheaders();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_sendstatusheader_1 in Test_HTTP.php
	function test_sendstatusheader_1() {
//		$o =& new fbHTTP();
//		$rv = $o->sendstatusheader();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
