<?php

// $CVSHeader: _freebeer/tests/GeoIP/Test_GeoIP_Free.php,v 1.3 2004/03/07 17:51:28 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/GeoIP/Free.php';

class _Test_GeoIP_Free extends fbTestCase {

	function _Test_GeoIP_Free($name) {
        parent::__construct($name);
	}

	function setUp() {
		$this->ips = array();
		$this->hosts = array();
	}

	function tearDown() {
	}

}

# make PHPUnit_GUI_SetupDecorator() happy
class _GeoIP_Test_GeoIP_Free extends _Test_GeoIP_Free {
}

?><?php /*
	// \todo Implement test_getcountryidbyhostname_1 in Test_GeoIP_Free.php
	function test_getcountryidbyhostname_1() {
//		$o =& new Free();
//		$rv = $o->getcountryidbyhostname();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getcountryidbyip_1 in Test_GeoIP_Free.php
	function test_getcountryidbyip_1() {
//		$o =& new Free();
//		$rv = $o->getcountryidbyip();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getcountrynamebyhostname_1 in Test_GeoIP_Free.php
	function test_getcountrynamebyhostname_1() {
//		$o =& new Free();
//		$rv = $o->getcountrynamebyhostname();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getcountrynamebyip_1 in Test_GeoIP_Free.php
	function test_getcountrynamebyip_1() {
//		$o =& new Free();
//		$rv = $o->getcountrynamebyip();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
