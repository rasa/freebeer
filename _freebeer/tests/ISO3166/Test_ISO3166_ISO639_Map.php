<?php

// $CVSHeader: _freebeer/tests/ISO3166/Test_ISO3166_ISO639_Map.php,v 1.3 2004/03/07 17:51:31 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/ISO3166/ISO639_Map.php';

class _Test_ISO3166_ISO639_Map extends fbTestCase {

	function _Test_ISO3166_ISO639_Map($name) {
        parent::__construct($name);
	}

	function setUp() {
	}

	function tearDown() {
	}

}

# make PHPUnit_GUI_SetupDecorator() happy
class _ISO3166_Test_ISO3166_ISO639_Map extends _Test_ISO3166_ISO639_Map {
}

?>
<?php /*
	// \todo Implement test_getcountryidtolanguageidhash_1 in Test_ISO3166_ISO639_Map.php
	function test_getcountryidtolanguageidhash_1() {
//		$o =& new ISO639_Map();
//		$rv = $o->getcountryidtolanguageidhash();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getlanguageid_1 in Test_ISO3166_ISO639_Map.php
	function test_getlanguageid_1() {
//		$o =& new ISO639_Map();
//		$rv = $o->getlanguageid();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
	
*/ ?>
