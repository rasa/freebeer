<?php

// $CVSHeader: _freebeer/tests/Test_ISO639.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/ISO639.php';

class _Test_ISO639 extends fbTestCase {

	function _Test_ISO639($name) {
        parent::__construct($name);
	}

	function setUp() {
	}

	function tearDown() {
	}

	function test_getidtonamehash_1() {
		$rv = fbISO639::getidtonamehash();
		$this->assertTrue(count($rv) > 0);
	}

	function test_getnametoidhash_1() {
		$rv = fbISO639::getnametoidhash();
		$this->assertTrue(count($rv) > 0);
	}

	function test_getLanguageId_1() {
		$h = fbISO639::getnametoidhash();
		foreach ($h as $name => $id) {
			$rv = fbISO639::getLanguageId($name);
			$this->assertEquals($id, $rv, "name=$name");
		}
	}

	function test_getLanguageId_lc() {
		$h = fbISO639::getnametoidhash();
		foreach ($h as $name => $id) {
			$name = strtolower($name);
			$rv = fbISO639::getLanguageId($name);
			$this->assertEquals($id, $rv, "name=$name");
		}
	}

	function test_getLanguageId_uc() {
		$h = fbISO639::getnametoidhash();
		foreach ($h as $name => $id) {
			$name = strtoupper($name);
			$rv = fbISO639::getLanguageId($name);
			$this->assertEquals($id, $rv, "name=$name");
		}
	}

	function test_getLanguageName_1() {
		$h = fbISO639::getidtonamehash();
		foreach ($h as $id => $name) {
			$rv = fbISO639::getLanguageName($id);
			$this->assertEquals($name, $rv, "id=$id");
		}
	}

	function test_getLanguageName_lc() {
		$h = fbISO639::getidtonamehash();
		foreach ($h as $id => $name) {
			$id = strtolower($id);
			$rv = fbISO639::getLanguageName($id);
			$this->assertEquals($name, $rv, "id=$id");
		}
	}

	function test_getLanguageName_uc() {
		$h = fbISO639::getidtonamehash();
		foreach ($h as $id => $name) {
			$id = strtoupper($id);
			$rv = fbISO639::getLanguageName($id);
			$this->assertEquals($name, $rv, "id=$id");
		}
	}

	function test_getidtolocalizednamehash_1() {
		$rv = fbISO639::getidtolocalizednamehash();
		$this->assertTrue(count($rv) > 0);
	}

	function test_getlocalizedlanguagename_1() {
		$h = fbISO639::getidtolocalizednamehash();
		foreach ($h as $id => $name) {
			$id = strtoupper($id);
			$rv = fbISO639::getLocalizedLanguageName($id);
			$this->assertEquals($name, $rv, "id=$id");
		}
	}

}

?>
