<?php

// $CVSHeader: _freebeer/tests/ADOdb/Test_ADOdb_ADOdb.php,v 1.3 2004/03/07 17:51:27 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/ADOdb/ADOdb.php';

class _Test_ADOdb_ADOdb extends fbTestCase {

	function _Test_ADOdb_ADOdb($name) {
        parent::__construct($name);
	}

	function setUp() {
	}

	function tearDown() {
	}

	function isAvailable() {
			return fbADOdb::isAvailable();
	}

}

# make PHPUnit_GUI_SetupDecorator() happy
class _ADOdb_Test_ADOdb_ADOdb extends _Test_ADOdb_ADOdb {
}

?><?php /*
	// \todo Implement test_getdrivers_1 in Test_ADOdb_ADOdb.php
	function test_getdrivers_1() {
//		$o =& new ADOdb();
//		$rv = $o->getdrivers();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_isavailable_1 in Test_ADOdb_ADOdb.php
	function test_isavailable_1() {
//		$o =& new ADOdb();
//		$rv = $o->isavailable();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
