<?php

// $CVSHeader: _freebeer/tests/HTTP/Test_HTTP_Status.php,v 1.3 2004/03/07 17:51:30 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

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

# make PHPUnit_GUI_SetupDecorator() happy
class _HTTP_Test_HTTP_Status extends _Test_HTTP_Status {
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
