<?php

// $CVSHeader: _freebeer/tests/Horde/Test_Horde_Horde.php,v 1.3 2004/03/07 17:51:30 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/Horde/Horde.php';

class _Test_Horde_Horde extends fbTestCase {

	function _Test_Horde_Horde($name) {
        parent::__construct($name);
	}

	function setUp() {
	}

	function tearDown() {
	}

}

# make PHPUnit_GUI_SetupDecorator() happy
class _Horde_Test_Horde_Horde extends _Test_Horde_Horde {
}

?><?php /*
	// \todo Implement test_isavailable_1 in Test_Horde_Horde.php
	function test_isavailable_1() {
//		$o =& new Horde();
//		$rv = $o->isavailable();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
