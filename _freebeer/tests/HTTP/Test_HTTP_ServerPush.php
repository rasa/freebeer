<?php

// $CVSHeader: _freebeer/tests/HTTP/Test_HTTP_ServerPush.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/HTTP/ServerPush.php';

class _Test_HTTP_ServerPush extends fbTestCase {

	function _Test_HTTP_ServerPush($name) {
        parent::__construct($name);
	}

	function setUp() {
	}

	function tearDown() {
	}
}

?>
<?php /*
	// \todo Implement test_push_1 in Test_HTTP_ServerPush.php
	function test_push_1() {
//		$o =& new ServerPush();
//		$rv = $o->push();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
