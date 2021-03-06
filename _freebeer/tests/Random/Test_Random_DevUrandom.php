<?php

// $CVSHeader: _freebeer/tests/Random/Test_Random_DevUrandom.php,v 1.3 2004/03/07 17:51:32 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/tests/Test_Random.php';

require_once FREEBEER_BASE . '/lib/Random/DevUrandom.php';

class _Test_Random_DevUrandom extends _Test_Random {
	function _Test_Random_DevUrandom($name) {
        parent::__construct($name);
	}

	function setUp() {
//		$this->tries = 1;
	}

	function tearDown() {
	}

	function isAvailable() {
		return fbRandom_DevUrandom::isAvailable();
	}

	function enabled() {
		return false;	// remove to enable
		return $this->isAvailable();
	}

	function &newObject() {
		$rv = &new fbRandom_DevUrandom();
		return $rv;
	}

}

# make PHPUnit_GUI_SetupDecorator() happy
class _Random_Test_Random_DevUrandom extends _Test_Random_DevUrandom {
}

?>
<?php /*
	// \todo Implement test_fbrandom_devrandom_1 in Test_Random_DevUrandom.php
	function test_fbrandom_devrandom_1() {
//		$o =& new DevUrandom();
//		$rv = $o->fbrandom_devrandom();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_fbrandom_devurandom_1 in Test_Random_DevUrandom.php
	function test_fbrandom_devurandom_1() {
//		$o =& new DevUrandom();
//		$rv = $o->fbrandom_devurandom();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getentropy_1 in Test_Random_DevUrandom.php
	function test_getentropy_1() {
//		$o =& new DevUrandom();
//		$rv = $o->getentropy();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getinstance_1 in Test_Random_DevUrandom.php
	function test_getinstance_1() {
//		$o =& new DevUrandom();
//		$rv = $o->getinstance();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getrandomseed_1 in Test_Random_DevUrandom.php
	function test_getrandomseed_1() {
//		$o =& new DevUrandom();
//		$rv = $o->getrandomseed();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getseed_1 in Test_Random_DevUrandom.php
	function test_getseed_1() {
//		$o =& new DevUrandom();
//		$rv = $o->getseed();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_isavailable_1 in Test_Random_DevUrandom.php
	function test_isavailable_1() {
//		$o =& new DevUrandom();
//		$rv = $o->isavailable();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_isblocking_1 in Test_Random_DevUrandom.php
	function test_isblocking_1() {
//		$o =& new DevUrandom();
//		$rv = $o->isblocking();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_isseedable_1 in Test_Random_DevUrandom.php
	function test_isseedable_1() {
//		$o =& new DevUrandom();
//		$rv = $o->isseedable();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_nextbase64string_1 in Test_Random_DevUrandom.php
	function test_nextbase64string_1() {
//		$o =& new DevUrandom();
//		$rv = $o->nextbase64string();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_nextbase95string_1 in Test_Random_DevUrandom.php
	function test_nextbase95string_1() {
//		$o =& new DevUrandom();
//		$rv = $o->nextbase95string();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_nextboolean_1 in Test_Random_DevUrandom.php
	function test_nextboolean_1() {
//		$o =& new DevUrandom();
//		$rv = $o->nextboolean();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_nextbytes_1 in Test_Random_DevUrandom.php
	function test_nextbytes_1() {
//		$o =& new DevUrandom();
//		$rv = $o->nextbytes();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_nextdouble_1 in Test_Random_DevUrandom.php
	function test_nextdouble_1() {
//		$o =& new DevUrandom();
//		$rv = $o->nextdouble();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_nextgaussian_1 in Test_Random_DevUrandom.php
	function test_nextgaussian_1() {
//		$o =& new DevUrandom();
//		$rv = $o->nextgaussian();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_nextint_1 in Test_Random_DevUrandom.php
	function test_nextint_1() {
//		$o =& new DevUrandom();
//		$rv = $o->nextint();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_nextprintablestring_1 in Test_Random_DevUrandom.php
	function test_nextprintablestring_1() {
//		$o =& new DevUrandom();
//		$rv = $o->nextprintablestring();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_nextsalt_1 in Test_Random_DevUrandom.php
	function test_nextsalt_1() {
//		$o =& new DevUrandom();
//		$rv = $o->nextsalt();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_nextstring_1 in Test_Random_DevUrandom.php
	function test_nextstring_1() {
//		$o =& new DevUrandom();
//		$rv = $o->nextstring();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_nexturl64string_1 in Test_Random_DevUrandom.php
	function test_nexturl64string_1() {
//		$o =& new DevUrandom();
//		$rv = $o->nexturl64string();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_setrandomseed_1 in Test_Random_DevUrandom.php
	function test_setrandomseed_1() {
//		$o =& new DevUrandom();
//		$rv = $o->setrandomseed();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_setseed_1 in Test_Random_DevUrandom.php
	function test_setseed_1() {
//		$o =& new DevUrandom();
//		$rv = $o->setseed();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
	
*/ ?>
