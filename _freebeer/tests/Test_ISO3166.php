<?php

// $CVSHeader: _freebeer/tests/Test_ISO3166.php,v 1.1.1.1 2004/01/18 00:12:06 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/ISO3166.php';

class _Test_ISO3166 extends fbTestCase {

	function _Test_ISO3166($name) {
        parent::__construct($name);
	}

	function setUp() {
	}

	function tearDown() {
	}

	function test_getidtonamehash_1() {
		$rv = fbISO3166::getidtonamehash();
		$this->assertTrue(count($rv) > 0);
	}

	function test_getnametoidhash_1() {
		$rv = fbISO3166::getnametoidhash();
		$this->assertTrue(count($rv) > 0);
	}

	function test_getCountryId_1() {
		$h = fbISO3166::getnametoidhash();
		foreach ($h as $name => $id) {
			$rv = fbISO3166::getCountryId($name);
			$this->assertEquals($id, $rv, "name=$name");
		}
	}

	function test_getCountryId_lc() {
		$h = fbISO3166::getnametoidhash();
		foreach ($h as $name => $id) {
			$name = strtolower($name);
			$rv = fbISO3166::getCountryId($name);
			$this->assertEquals($id, $rv, "name=$name");
		}
	}

	function test_getCountryId_uc() {
		$h = fbISO3166::getnametoidhash();
		foreach ($h as $name => $id) {
			$name = strtoupper($name);
			$rv = fbISO3166::getCountryId($name);
			$this->assertEquals($id, $rv, "name=$name");
		}
	}

	function test_getCountryName_1() {
		$h = fbISO3166::getidtonamehash();
		foreach ($h as $id => $name) {
			$rv = fbISO3166::getCountryName($id);
			$this->assertEquals($name, $rv, "id=$id");
		}
	}

	function test_getCountryName_lc() {
		$h = fbISO3166::getidtonamehash();
		foreach ($h as $id => $name) {
			$id = strtolower($id);
			$rv = fbISO3166::getCountryName($id);
			$this->assertEquals($name, $rv, "id=$id");
		}
	}

	function test_getCountryName_uc() {
		$h = fbISO3166::getidtonamehash();
		foreach ($h as $id => $name) {
			$id = strtoupper($id);
			$rv = fbISO3166::getCountryName($id);
			$this->assertEquals($name, $rv, "id=$id");
		}
	}

}

?>
