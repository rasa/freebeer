<?php

// $CVSHeader: _freebeer/tests/ADOdb/Test_ADOdb_ADOdb.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

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
