<?php

// $CVSHeader: _freebeer/tests/HTTP/Test_HTTP_Status.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/HTTP/Status.php';

class _Test_HTTP_Status extends fbTestCase {

	function _Test_HTTP_Status($name) {
        parent::__construct($name);
	}

	function setUp() {
	}

	function tearDown() {
	}

}

?><?php /*
	// \todo Implement test_getstatuscodes_1 in Test_HTTP_Status.php
	function test_getstatuscodes_1() {
//		$o =& new Status();
//		$rv = $o->getstatuscodes();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getstatusname_1 in Test_HTTP_Status.php
	function test_getstatusname_1() {
//		$o =& new Status();
//		$rv = $o->getstatusname();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
