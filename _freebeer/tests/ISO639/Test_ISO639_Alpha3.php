<?php

// $CVSHeader: _freebeer/tests/ISO639/Test_ISO639_Alpha3.php,v 1.3 2004/03/07 17:51:31 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/ISO639/Alpha3.php';

class _Test_ISO639_Alpha3 extends fbTestCase {

	function _Test_ISO639_Alpha3($name) {
        parent::__construct($name);
	}

	function setUp() {
	}

	function tearDown() {
	}

	function test_getidtonamehash_1() {
		$rv = fbISO639_Alpha3::getidtonamehash();
		$this->assertTrue(count($rv) > 0);
	}

	function test_getnametoidhash_1() {
		$rv = fbISO639_Alpha3::getnametoidhash();
		$this->assertTrue(count($rv) > 0);
	}

	function test_getLanguageId_1() {
		$h = fbISO639_Alpha3::getnametoidhash();
		foreach ($h as $name => $id) {
			$rv = fbISO639_Alpha3::getLanguageId($name);
			$this->assertEquals($id, $rv, "name=$name");
		}
	}

	function test_getLanguageId_lc() {
		$h = fbISO639_Alpha3::getnametoidhash();
		foreach ($h as $name => $id) {
			$name = strtolower($name);
			$rv = fbISO639_Alpha3::getLanguageId($name);
			$this->assertEquals($id, $rv, "name=$name");
		}
	}

	function test_getLanguageId_uc() {
		$h = fbISO639_Alpha3::getnametoidhash();
		foreach ($h as $name => $id) {
			$name = strtoupper($name);
			$rv = fbISO639_Alpha3::getLanguageId($name);
			$this->assertEquals($id, $rv, "name=$name");
		}
	}

	function test_getLanguageName_1() {
		$h = fbISO639_Alpha3::getidtonamehash();
		foreach ($h as $id => $name) {
			$rv = fbISO639_Alpha3::getLanguageName($id);
			$this->assertEquals($name, $rv, "id=$id");
		}
	}

	function test_getLanguageName_lc() {
		$h = fbISO639_Alpha3::getidtonamehash();
		foreach ($h as $id => $name) {
			$id = strtolower($id);
			$rv = fbISO639_Alpha3::getLanguageName($id);
			$this->assertEquals($name, $rv, "id=$id");
		}
	}

	function test_getLanguageName_uc() {
		$h = fbISO639_Alpha3::getidtonamehash();
		foreach ($h as $id => $name) {
			$id = strtoupper($id);
			$rv = fbISO639_Alpha3::getLanguageName($id);
			$this->assertEquals($name, $rv, "id=$id");
		}
	}

}

# make PHPUnit_GUI_SetupDecorator() happy
class _ISO639_Test_ISO639_Alpha3 extends _Test_ISO639_Alpha3 {
}

?>
