<?php

// $CVSHeader: _freebeer/tests/Test_Mbstring.php,v 1.2 2004/03/07 17:51:26 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/Mbstring.php';

class _Test_Mbstring extends fbTestCase {

	function _Test_Mbstring($name) {
        parent::__construct($name);
	}

	function setUp() {
	}

	function tearDown() {
	}

	function _test() {
		static $test = null;
		
		if (is_null($test)) {
			$test = !extension_loaded('mbstring');
			if (!$test) {
				trigger_error('Unable to fully test fbMbstring class as mbstring extension is loaded.', E_USER_NOTICE);
			}
		}

		return $test;
	}

	// \todo add some multibyte strings
	function test_mb_substr_1() {
		if (!$this->_test()) {
			return;
		}

		$a = array(
			'1' => array(
				'str' 		=> 'abc',
				'start'		=> 0,
				'length'	=> 2,
				'expected'	=> 'ab',
			),
			'2' => array(
				'str' 		=> 'abc',
				'start'		=> 1,
				'length'	=> 2,
				'expected'	=> 'bc',
			),
			'3' => array(
				'str' 		=> 'abc',
				'start'		=> 3,
				'length'	=> 2,
				'expected'	=> '',
			),
			'4' => array(
				'str' 		=> 'abc',
				'start'		=> 0,
				'length'	=> null,
				'expected'	=> 'abc',
			),
			'5' => array(
				'str' 		=> 'abc',
				'start'		=> 1,
				'length'	=> null,
				'expected'	=> 'bc',
			),
			'6' => array(
				'str' 		=> 'abc',
				'start'		=> 3,
				'length'	=> null,
				'expected'	=> '',
			),
		);
		foreach ($a as $key => $test) {
			extract($test);
			if (is_null($length)) {
				$rv = mb_substr($str, $start);
			} else {
				$rv = mb_substr($str, $start, $length);
			}
			$this->assertEquals($expected, $rv, "key='$key' gettype(expected)=" . gettype($expected) . ' gettype(rv)=' . gettype($rv));
		}
	}

}

?>
