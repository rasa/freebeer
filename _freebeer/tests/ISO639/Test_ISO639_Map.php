<?php

// $CVSHeader: _freebeer/tests/ISO639/Test_ISO639_Map.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/ISO639/Map.php';

class _Test_ISO639_Map extends fbTestCase {

	function _Test_ISO639_Map($name) {
        parent::__construct($name);
	}

	function setUp() {
	}

	function tearDown() {
	}

	function test_getid3toid2hash_1() {
		$rv = fbISO639_Map::getid3toid2hash();
		$this->assertTrue(count($rv) > 0);
	}

	function test_getid2toid3hash_1() {
		$rv = fbISO639_Map::getid2toid3hash();
		$this->assertTrue(count($rv) > 0);
	}

	function test_getid2_1() {
		$h = fbISO639_Map::getid3toid2hash();
		foreach ($h as $id3 => $id2) {
			$rv = fbISO639_Map::getId2($id3);
			$this->assertEquals($id2, $rv, "id2=$id2");
		}
	}

	function test_getid2_lc() {
		$h = fbISO639_Map::getid3toid2hash();
		foreach ($h as $id3 => $id2) {
			$id3 = strtolower($id3);
			$rv = fbISO639_Map::getId2($id3);
			$this->assertEquals($id2, $rv, "id2=$id2");
		}
	}

	function test_getid2_uc() {
		$h = fbISO639_Map::getid3toid2hash();
		foreach ($h as $id3 => $id2) {
			$id3 = strtoupper($id3);
			$rv = fbISO639_Map::getId2($id3);
			$this->assertEquals($id2, $rv, "id2=$id2");
		}
	}

	function test_getid3_1() {
		$h = fbISO639_Map::getid2toid3hash();
		foreach ($h as $id2 => $id3) {
			$rv = fbISO639_Map::getId3($id2);
			$this->assertEquals($id3, $rv, "id3=$id3");
		}
	}

	function test_getid3_lc() {
		$h = fbISO639_Map::getid2toid3hash();
		foreach ($h as $id2 => $id3) {
			$id2 = strtolower($id2);
			$rv = fbISO639_Map::getId3($id2);
			$this->assertEquals($id3, $rv, "id3=$id3");
		}
	}

	function test_getid3_uc() {
		$h = fbISO639_Map::getid2toid3hash();
		foreach ($h as $id2 => $id3) {
			$id2 = strtoupper($id2);
			$rv = fbISO639_Map::getId3($id2);
			$this->assertEquals($id3, $rv, "id3=$id3");
		}
	}

}

?>
