<?php

// $CVSHeader: _freebeer/tests/ISO639/Test_ISO639_CharsetMap.php,v 1.3 2004/03/07 17:51:31 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/ISO639/CharsetMap.php';

class _Test_ISO639_CharsetMap extends fbTestCase {

	function _Test_ISO639_CharsetMap($name) {
        parent::__construct($name);
	}

	function setUp() {
	}

	function tearDown() {
	}

}

# make PHPUnit_GUI_SetupDecorator() happy
class _ISO639_Test_ISO639_CharsetMap extends _Test_ISO639_CharsetMap {
}

?>
<?php /*
	// \todo Implement test_getcharset_1 in Test_ISO639_CharsetMap.php
	function test_getcharset_1() {
//		$o =& new CharsetMap();
//		$rv = $o->getcharset();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getidtocharsethash_1 in Test_ISO639_CharsetMap.php
	function test_getidtocharsethash_1() {
//		$o =& new CharsetMap();
//		$rv = $o->getidtocharsethash();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
	
*/ ?>
