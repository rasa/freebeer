<?php

// $CVSHeader: _freebeer/tests/BinarySearch/Test_BinarySearch_Array.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/BinarySearch/Array.php';

function fbBinarySearch_Array_compare($a, $b, $len = null) {
	if ($a > $b) {
		return 1;
	}
	if ($a < $b) {
		return -1;
	}

	return 0;
}

class _Test_BinarySearch_Array extends fbTestCase {

    function _Test_BinarySearch_Array($name) {
        parent::__construct($name);
    }

	function setUp() {
		$this->_elements = 10;
		$this->_attempts = 10;
	}

	function tearDown() {
	}

	function &_getArray() {
		static $a;

		if (!$a) {
			$a = array();
			for ($i = 0; $i < $this->_elements; ++$i) {
				$a[$i] = str_pad(mt_rand(), 32, '0', STR_PAD_LEFT);
			}
		}

		return $a;
	}

	function test_search_Sorted() {
		$a = &$this->_getArray();

		sort($a);

		for ($i = 0; $i < $this->_attempts; ++$i) {
			$n = mt_rand(0, $this->_elements - 1);
			$search_term = $a[$n];
			$rv = fbBinarySearch_Array::search($search_term, $a);
			$this->assertEquals($search_term, $a[$rv]);
		}
	}

	function test_search_Unsorted() {
		$a = &$this->_getArray();

		for ($i = 0; $i < $this->_attempts; ++$i) {
			$n = mt_rand(0, $this->_elements - 1);
			$search_term = $a[$n];
			$rv = fbBinarySearch_Array::search($search_term, $a, true);
			$this->assertEquals($search_term, $a[$rv]);
		}
	}

	function test_search_SortedCompare() {
		$a = &$this->_getArray();

		sort($a);

		for ($i = 0; $i < $this->_attempts; ++$i) {
			$n = mt_rand(0, $this->_elements - 1);
			$search_term = $a[$n];
			$rv = fbBinarySearch_Array::search($search_term, $a, false, 'fbBinarySearch_Array_compare');
			$this->assertEquals($search_term, $a[$rv]);
		}
	}

	function test_search_UnsortedCompare() {
		$a = &$this->_getArray();

		for ($i = 0; $i < $this->_attempts; ++$i) {
			$n = mt_rand(0, $this->_elements - 1);
			$search_term = $a[$n];
			$rv = fbBinarySearch_Array::search($search_term, $a, true, 'fbBinarySearch_Array_compare');
			$this->assertEquals($search_term, $a[$rv]);
		}
	}

}

?>
