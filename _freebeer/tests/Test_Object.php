<?php

// $CVSHeader: _freebeer/tests/Test_Object.php,v 1.2 2004/03/07 17:51:26 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/Object.php';

class _Test_Object extends fbTestCase {

    function _Test_Object($name) {
        parent::__construct($name);
    }

	function setUp() {
	}

	function tearDown() {
	}

	function test_persist() {
		$o = &new fbObject();

		$o->an_int = 1;
		$o->a_string = 'a string';

		$file = $o->persist();
		$o2 = $o->unpersist($file);

		$os = serialize($o);
		$os2 = serialize($o2);

		$this->assertEquals($os, $os2);
	}

	function test_unpersist() {
		// tested above
	}

	function test_toString() {
		$o = &new fbObject();

		$o->an_int = 1;
		$o->a_string = "a string";

//		$rv = $o->toString();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}

	function test_toHtml() {
		$o = &new fbObject();

		$o->an_int = 1;
		$o->a_string = "a string";

//		$rv = $o->toHtml();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}

}

?>
