<?php

// $CVSHeader: _freebeer/tests/HTTP/Test_HTTP_PathInfo.php,v 1.3 2004/03/07 17:51:30 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/HTTP/PathInfo.php';

class _Test_HTTP_PathInfo extends fbTestCase {

	function _Test_HTTP_PathInfo($name) {
        parent::__construct($name);
	}

	function setUp() {
	}

	function tearDown() {
	}

}

# make PHPUnit_GUI_SetupDecorator() happy
class _HTTP_Test_HTTP_PathInfo extends _Test_HTTP_PathInfo {
}

?><?php /*
	// \todo Implement test_fixrelativeurl_1 in Test_HTTP_PathInfo.php
	function test_fixrelativeurl_1() {
//		$o =& new PathInfo();
//		$rv = $o->fixrelativeurl();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_redirect_1 in Test_HTTP_PathInfo.php
	function test_redirect_1() {
//		$o =& new PathInfo();
//		$rv = $o->redirect();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
