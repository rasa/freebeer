<?php

// $CVSHeader: _freebeer/tests/HTTP/Test_HTTP_ServerPush.php,v 1.3 2004/03/07 17:51:30 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

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

# make PHPUnit_GUI_SetupDecorator() happy
class _HTTP_Test_HTTP_ServerPush extends _Test_HTTP_ServerPush {
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
