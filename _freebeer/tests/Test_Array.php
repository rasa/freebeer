<?php

// $CVSHeader: _freebeer/tests/Test_Array.php,v 1.2 2004/03/07 17:51:26 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/Array.php';

class _Test_Array extends fbTestCase {

    function _Test_Array($name) {
        parent::__construct($name);
    }

	function test_getNestedKey_1() {
		$a	= 1;
		$this->assertTrue(fbArray::getNestedKey($a) === false);
	}

	function test_getNestedKey_2() {
		$b	= array(2 => 3);
		$this->assertEquals(2, fbArray::getNestedKey($b));
	}

	function test_getNestedKey_3() {
		$c	= array(4 => array(5 => 6));
		$this->assertEquals(5, fbArray::getNestedKey($c, 4));
	}

	function test_getNestedKey_4() {
		$c	= array(4 => array(5 => 6));
		$this->assertEquals(5, fbArray::getNestedKey($c, array(4)));
	}

	function test_getNestedKey_5() {
		$d	= array(7 => array(8 => array(9 => 10)));
		$this->assertEquals(9, fbArray::getNestedKey($d, array(7, 8)));
	}

	function test_getNestedKeyValue_1() {
		$a	= 1;
		$this->assertEquals(1, fbArray::getNestedKeyValue($a));
	}

	function test_getNestedKeyValue_2() {
		$b	= array(2 => 3);
		$this->assertEquals(3, fbArray::getNestedKeyValue($b, 2));
	}

	function test_getNestedKeyValue_3() {
		$b	= array(2 => 3);
		$this->assertEquals(3, fbArray::getNestedKeyValue($b, array(2)));
	}

	function test_getNestedKeyValue_4() {
		$c	= array(4 => array(5 => 6));
		$this->assertEquals(6, fbArray::getNestedKeyValue($c, array(4, 5)));
	}

	function test_getNestedKeyValue_5() {
		$d	= array(7 => array(8 => array(9 => 10)));
		$this->assertEquals(10, fbArray::getNestedKeyValue($d, array(7, 8, 9)));
	}

	function test_unshiftAssoc_1() {
		$expected = array('A' => '0', 'B' => '1', 'C' => '2');
		$a = $expected;
		array_shift($a);
		$rv = fbArray::unshiftAssoc($a, 'A', '0');
		$this->assertEquals(count($expected), $rv);
		$this->assertEquals($expected, $a);
//		$this->assertEquals(serialize($expected), serialize($a));
	}

}

?>
<?php /*
	// \todo Implement test_search_1 in Test_Array.php
	function test_search_1() {
//		$o =& new Array();
//		$rv = $o->search();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
