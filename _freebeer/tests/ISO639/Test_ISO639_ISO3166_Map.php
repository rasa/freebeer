<?php

// $CVSHeader: _freebeer/tests/ISO639/Test_ISO639_ISO3166_Map.php,v 1.3 2004/03/07 17:51:31 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/ISO639/ISO3166_Map.php';

class _Test_ISO639_ISO3166_Map extends fbTestCase {

	function _Test_ISO639_ISO3166_Map($name) {
        parent::__construct($name);
	}

	function setUp() {
	}

	function tearDown() {
	}

}

# make PHPUnit_GUI_SetupDecorator() happy
class _ISO639_Test_ISO639_ISO3166_Map extends _Test_ISO639_ISO3166_Map {
}

?>
<?php /*
	// \todo Implement test_getcountryid_1 in Test_ISO639_ISO3166_Map.php
	function test_getcountryid_1() {
//		$o =& new ISO3166_Map();
//		$rv = $o->getcountryid();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getlanguageidtocountryidhash_1 in Test_ISO639_ISO3166_Map.php
	function test_getlanguageidtocountryidhash_1() {
//		$o =& new ISO3166_Map();
//		$rv = $o->getlanguageidtocountryidhash();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
	
*/ ?>
