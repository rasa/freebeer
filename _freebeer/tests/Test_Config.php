<?php

// $CVSHeader: _freebeer/tests/Test_Config.php,v 1.3 2004/03/07 19:33:23 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/Config.php';

class _Test_Config extends fbTestCase {

	function _Test_Config($name) {
        parent::__construct($name);
	}

	function setUp() {
	}

	function tearDown() {
	}

	// \todo Implement test_fbconfig_1 in Test_Config.php
	function test_fbconfig_1() {
/*
		$o =& fbConfig::getInstance();
		$rv = $o->read();
		$rv = $o->getConf();
		print_r($rv);
		$rv = 0;
		$expected = 0;
		$this->assertEquals($expected, $rv);
*/
	}
}

?>
<?php /*
	// \todo Implement test_getfile_1 in Test_Config.php
	function test_getfile_1() {
//		$o =& fbConfig::getInstance();
//		$rv = $o->getfile();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getinstance_1 in Test_Config.php
	function test_getinstance_1() {
//		$o =& fbConfig::getInstance();
//		$rv = $o->getinstance();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getsection_1 in Test_Config.php
	function test_getsection_1() {
//		$o =& fbConfig::getInstance();
//		$rv = $o->getsection();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_read_1 in Test_Config.php
	function test_read_1() {
//		$o =& fbConfig::getInstance();
//		$rv = $o->read();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_reset_1 in Test_Config.php
	function test_reset_1() {
//		$o =& fbConfig::getInstance();
//		$rv = $o->reset();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_setfile_1 in Test_Config.php
	function test_setfile_1() {
//		$o =& fbConfig::getInstance();
//		$rv = $o->setfile();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_write_1 in Test_Config.php
	function test_write_1() {
//		$o =& fbConfig::getInstance();
//		$rv = $o->write();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
