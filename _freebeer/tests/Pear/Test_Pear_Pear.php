<?php

// $CVSHeader: _freebeer/tests/Pear/Test_Pear_Pear.php,v 1.3 2004/03/07 17:51:31 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/Pear/Pear.php';

class _Test_Pear_Pear extends fbTestCase {

	function _Test_Pear_Pear($name) {
        parent::__construct($name);
	}

	function setUp() {
	}

	function tearDown() {
	}

}

# make PHPUnit_GUI_SetupDecorator() happy
class _Pear_Test_Pear_Pear extends _Test_Pear_Pear {
}

?><?php /*
	// \todo Implement test_isavailable_1 in Test_Pear_Pear.php
	function test_isavailable_1() {
//		$o =& new Pear();
//		$rv = $o->isavailable();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
