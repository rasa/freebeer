<?php

// $CVSHeader: _freebeer/tests/Test_Random.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/Random.php';

class _Test_Random extends fbTestCase {
	var $tries = 1;
	
    function __construct($name) {
        parent::__construct($name);
    }

	function _Test_Random($name) {
        parent::__construct($name);
	}

	function setUp() {
	}

	function tearDown() {
	}

	function isAvailable() {
		return false;
	}

	function enabled() {
		return $this->isAvailable();
	}

	function &newObject() {
		return null;
	}

	function test_isavailable_1() {
		$rv = $this->isAvailable();
		$this->assertTrue(is_bool($rv));
	}

	function test_setseed_1() {
		if (!$this->enabled()) {
			return;
		}

		$o = &$this->newObject();
		$o->setseed(0);
	}

	function test_getentropy_1() {
		if (!$this->enabled()) {
			return;
		}

		$o = &$this->newObject();
		$rv = $o->getentropy();
		$this->assertTrue(is_string($rv), 'Not a string');
		$this->assertTrue($rv > '', 'Empty string');
	}

	function test_nextbase64string_1() {
		if (!$this->enabled()) {
			return;
		}

		$o = &$this->newObject();
		$tries = $this->tries;
		while ($tries--) {
			$rv = $o->nextbase64string(10);
			$this->assertTrue(is_string($rv), 'Not a string');
			$this->assertEquals(10, strlen($rv));
		}
	}

	function test_nexturl64string_1() {
		if (!$this->enabled()) {
			return;
		}

		$o = &$this->newObject();
		$tries = $this->tries;
		while ($tries--) {
			$rv = $o->nexturl64string(10);
			$this->assertTrue(is_string($rv), 'Not a string');
			$this->assertEquals(10, strlen($rv));
		}
	}

	function test_nextbase95string_1() {
		if (!$this->enabled()) {
			return;
		}

		$o = &$this->newObject();
		$tries = $this->tries;
		while ($tries--) {
			$rv = $o->nextbase95string(10);
			$this->assertTrue(is_string($rv), 'Not a string');
			$this->assertEquals(10, strlen($rv));
		}
	}

	function test_nextboolean_1() {
		if (!$this->enabled()) {
			return;
		}

		$o = &$this->newObject();
		$tries = $this->tries;
		while ($tries--) {
			$rv = $o->nextboolean();
			$this->assertTrue(is_bool($rv), 'Not a boolean');
		}
	}

	function test_nextdouble_1() {
		if (!$this->enabled()) {
			return;
		}

		$o = &$this->newObject();
		$tries = $this->tries;
		while ($tries--) {
			$rv = $o->nextdouble();
			$this->assertTrue(is_float($rv), 'Not a float');
			$this->assertTrue($rv >= 0.0, "Less than 0.0: '$rv'");
			$this->assertTrue($rv < 1.0, "Greater than or equal to 1.0: '$rv'");
		}
	}

	function test_nextgaussian_1() {
		if (!$this->enabled()) {
			return;
		}

		$o = &$this->newObject();
		$tries = $this->tries;
		while ($tries--) {
			$rv = $o->nextgaussian();
			$this->assertTrue(is_float($rv), 'Not a float');
// \todo determine limits to gaussian function	
//			$this->assertTrue($rv >= -5.0, "Less than -5.0: '$rv'");
//			$this->assertTrue($rv <=  5.0, "Greater than 5.0: '$rv'");
		}
	}

	function test_nextint_1() {
		if (!$this->enabled()) {
			return;
		}

		$o = &$this->newObject();
		$tries = $this->tries;
		while ($tries--) {
			$rv = $o->nextint();
			$this->assertTrue(is_int($rv), 'Not an int');
		}
	}

	function test_nextint_2() {
		if (!$this->enabled()) {
			return;
		}

		$o = &$this->newObject();

		$high = 1;
		while ($high > 0 && $high <= 0xffffffff) {
			$tries = $this->tries;
			while ($tries--) {
				$limit = $high;
				if ($limit == 0) {
					continue;
				}
				$rv = $o->nextint($limit);
				$this->assertTrue(is_int($rv), 'Not an int');
				$this->assertTrue($rv >= 0, "Less than 0: rv='$rv' high=$limit");
				$this->assertTrue($rv < $limit, "Greater than or equal to $limit: rv='$rv' high=$limit");
			}
			
			$high <<= 1;
		}

		$high = 1;
		while ($high > 0 && $high <= 0xffffffff) {
			$tries = $this->tries;
			while ($tries--) {
				$limit = $high + 1;
				if ($limit == 0) {
					continue;
				}
				$rv = $o->nextint($limit);
				$this->assertTrue(is_int($rv), 'Not an int');
				$this->assertTrue($rv >= 0, "Less than 0: rv='$rv' high=$limit");
				$this->assertTrue($rv < $limit, "Greater than or equal to $limit: rv='$rv' high=$limit");
			}
			
			$high <<= 1;
		}

		$high = 1;
		while ($high > 0 && $high <= 0xffffffff) {
			$tries = $this->tries;
			while ($tries--) {
				$limit = $high - 1;
				if ($limit == 0) {
					continue;
				}
				$rv = $o->nextint($limit);
				$this->assertTrue(is_int($rv), 'Not an int');
				$this->assertTrue($rv >= 0, "Less than 0: rv='$rv' high=$limit");
				$this->assertTrue($rv < $limit, "Greater than or equal to $limit: rv='$rv' high=$limit");
			}
			
			$high <<= 1;
		}

	}

	function test_nextint_3() {
		if (!$this->enabled()) {
			return;
		}

		$o = &$this->newObject();

		$high = 1;
		while ($high > 0 && $high <= 0xffffffff) {
			$tries = $this->tries;
			while ($tries--) {
				$limit = $high;
				if ($limit == 0) {
					continue;
				}
				$rv = $o->nextint(0, $limit);
				$this->assertTrue(is_int($rv), 'Not an int');
				$this->assertTrue($rv >= 0, "Less than 0: rv='$rv' high=$limit");
				$this->assertTrue($rv < $limit, "Greater than or equal to $limit: rv='$rv' high=$limit");
			}
			
			$high <<= 1;
		}
		
		$high = 1;
		while ($high > 0 && $high <= 0xffffffff) {
			$tries = $this->tries;
			while ($tries--) {
				$limit = $high + 1;
				if ($limit == 0) {
					continue;
				}
				$rv = $o->nextint(0, $limit);
				$this->assertTrue(is_int($rv), 'Not an int');
				$this->assertTrue($rv >= 0, "Less than 0: rv='$rv' high=$limit");
				$this->assertTrue($rv < $limit, "Greater than or equal to $limit: rv='$rv' high=$limit");
			}
			
			$high <<= 1;
		}

		$high = 1;
		while ($high > 0 && $high <= 0xffffffff) {
			$tries = $this->tries;
			while ($tries--) {
				$limit = $high - 1;
				if ($limit == 0) {
					continue;
				}
				$rv = $o->nextint(0, $limit);
				$this->assertTrue(is_int($rv), 'Not an int');
				$this->assertTrue($rv >= 0, "Less than 0: rv='$rv' high=$limit");
				$this->assertTrue($rv < $limit, "Greater than or equal to $limit: rv='$rv' high=$limit");
			}
			
			$high <<= 1;
		}
		
	}

	function test_nextint_4() {
		if (!$this->enabled()) {
			return;
		}

		$o = &$this->newObject();

		$high = 1;
		while ($high > 0 && $high <= 0xffffffff) {
			$tries = $this->tries;
			while ($tries--) {
				$limit = $high;
				if ($limit == 0) {
					continue;
				}
				$rv = $o->nextint(-$limit, 0);
				$this->assertTrue(is_int($rv), 'Not an int');
				$this->assertTrue($rv >= -$limit, "Less than -$limit: rv='$rv' high=$limit");
				$this->assertTrue($rv < 0, "Greater than or equal to 0: rv='$rv' high=$limit");
			}
			
			$high <<= 1;
		}
		
		$high = 1;
		while ($high > 0 && $high <= 0xffffffff) {
			$tries = $this->tries;
			while ($tries--) {
				$limit = $high + 1;
				if ($limit == 0) {
					continue;
				}
				$rv = $o->nextint(-$limit, 0);
				$this->assertTrue(is_int($rv), 'Not an int');
				$this->assertTrue($rv >= -$limit, "Less than -$limit: rv='$rv' high=$limit");
				$this->assertTrue($rv < 0, "Greater than or equal to 0: rv='$rv' high=$limit");
			}
			
			$high <<= 1;
		}

		$high = 1;
		while ($high > 0 && $high <= 0xffffffff) {
			$tries = $this->tries;
			while ($tries--) {
				$limit = $high - 1;
				if ($limit == 0) {
					continue;
				}
				$rv = $o->nextint(-$limit, 0);
				$this->assertTrue(is_int($rv), 'Not an int');
				$this->assertTrue($rv >= -$limit, "Less than -$limit: rv='$rv' high=$limit");
				$this->assertTrue($rv < 0, "Greater than or equal to 0: rv='$rv' high=$limit");
			}
			
			$high <<= 1;
		}
		
	}

	function test_nextsalt_1() {
		if (!$this->enabled()) {
			return;
		}

		$o = &$this->newObject();

		$tries = $this->tries;
		while ($tries--) {
			$rv = $o->nextsalt();
			$this->assertTrue(is_string($rv), 'Not a string');
			$this->assertEquals(2, strlen($rv));
		}
	}

	function test_nextprintablestring_1() {
		if (!$this->enabled()) {
			return;
		}

		$o = &$this->newObject();

		$tries = $this->tries;
		while ($tries--) {
			$rv = $o->nextprintablestring(10);
			$this->assertTrue(is_string($rv), 'Not a string');
			$this->assertEquals(10, strlen($rv));
		}
	}

	function test_nextstring_1() {
		if (!$this->enabled()) {
			return;
		}

		$o = &$this->newObject();

		$tries = $this->tries;
		while ($tries--) {
			$rv = $o->nextstring(10);
			$this->assertTrue(is_string($rv), 'Not a string');
			$this->assertEquals(10, strlen($rv));
		}
	}

	function test_nextbytes_1() {
		if (!$this->enabled()) {
			return;
		}

		$o = &$this->newObject();

		$tries = $this->tries;
		while ($tries--) {
			$rv = $o->nextbytes(10);
			$this->assertTrue(is_string($rv), 'Not a string');
			$this->assertEquals(10, strlen($rv));
		}
	}

}

?>
<?php /*
	// \todo Implement test_getinstance_1 in Test_Random.php
	function test_getinstance_1() {
//		$o =& new Random();
//		$rv = $o->getinstance();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getrandomseed_1 in Test_Random.php
	function test_getrandomseed_1() {
//		$o =& new Random();
//		$rv = $o->getrandomseed();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getseed_1 in Test_Random.php
	function test_getseed_1() {
//		$o =& new Random();
//		$rv = $o->getseed();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_isblocking_1 in Test_Random.php
	function test_isblocking_1() {
//		$o =& new Random();
//		$rv = $o->isblocking();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_isseedable_1 in Test_Random.php
	function test_isseedable_1() {
//		$o =& new Random();
//		$rv = $o->isseedable();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_setrandomseed_1 in Test_Random.php
	function test_setrandomseed_1() {
//		$o =& new Random();
//		$rv = $o->setrandomseed();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
