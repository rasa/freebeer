<?php

// $CVSHeader: _freebeer/tests/Test_Backport.php,v 1.2 2004/03/07 17:51:26 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

/*!


\todo add pre 4.1.0 functions

\todo test array_key_exists
\todo test array_reduce
\todo test array_search
\todo test array_sum
\todo test array_unique
\todo test call_user_func_array
\todo test constant
\todo test expm1
\todo test fmod
\todo test fprintf
\todo test hypot
\todo test is_null
\todo test is_scalar
\todo test log1p
\todo test php_uname
\todo test strncasecmp
\todo test vprintf
\todo test vsprintf

*/

require_once FREEBEER_BASE . '/tests/_init.php';

// sha1 code returns incorrect results in PHP 4.1.0, but the code is fine, so 4.1.0 is therefore broken
$skip_sha1 = !function_exists('mhash') && phpversion() == '4.1.0';

require_once FREEBEER_BASE . '/lib/Backport.php';

require_once FREEBEER_BASE . '/lib/System.php';	// tempDirectory()

class fbBackport1 {
}

class fbBackport2 extends fbBackport1 {
}

class fbBackport3 extends fbBackport2 {
}

class _Test_Backport extends fbTestCase {

    function _Test_Backport($name) {
        parent::__construct($name);
    }

	function setUp() {
	}

	function tearDown() {
	}

	function _getTempName() {
		return tempnam(fbSystem::tempDirectory(), 'fbt');
	}
	
	function test_array_change_key_case() {
		$a = array(
			'A' => 'A',
			'b' => 'b',
			'C' => 'C',
			'd' => 'd',
		);

		$rv = array_change_key_case($a);
		$expected = array(
			'a' => 'A',
			'b' => 'b',
			'c' => 'C',
			'd' => 'd',
		);

		$this->assertEquals($expected, $rv);
	}

	function test_array_change_key_case_upper() {
		$a = array(
			'A' => 'A',
			'b' => 'b',
			'C' => 'C',
			'd' => 'd',
		);

		$rv = array_change_key_case($a, CASE_UPPER);
		$expected = array(
			'A' => 'A',
			'B' => 'b',
			'C' => 'C',
			'D' => 'd',
		);

		$this->assertEquals($expected, $rv);
	}

	function test_array_chunk() {
		$a = array('a', 'b', 'c', 'd', 'e');
		$rv = array_chunk($a, 2);
		$expected = array(
			0 => array(
				0 => 'a',
				1 => 'b',
			),
			1 => array(
				0 => 'c',
				1 => 'd',
			),
			2 => array(
				0 => 'e',
			),
		);
		$this->assertEquals($expected, $rv);
	}

	function test_array_combine() {
		$a = array('green', 'red', 'yellow');
		$b = array('avocado', 'apple', 'banana');
		$rv = array_combine($a, $b);
		$expected = array('green' => 'avocado', 'red' => 'apple', 'yellow' => 'banana');
		$this->assertEquals($expected, $rv);
	}

	function test_array_chunk_true() {
		$a = array('a', 'b', 'c', 'd', 'e');
		$rv = array_chunk($a, 2, TRUE);
		$expected = array(
			0 => array(
				0 => 'a',
				1 => 'b',
			),
			1 => array(
				2 => 'c',
				3 => 'd',
			),
			2 => array(
				4 => 'e',
			),
		);
		$this->assertEquals($expected, $rv);
	}

	function test_array_diff_assoc() {
		$a = array('a' => 'green', 'b' => 'brown', 'c' => 'blue', 'red');
		$b = array('a' => 'green', 'yellow', 'red');
		$rv = array_diff_assoc($a, $b);
		$expected = array('b' => 'brown', 'c' => 'blue', 0 => 'red');
		$this->assertEquals($expected, $rv);
	}

	function test_array_fill() {
		$rv = array_fill(5, 6, 'banana');
		$expected = array(
			5 => 'banana',
			6 => 'banana',
			7 => 'banana',
			8 => 'banana',
			9 => 'banana',
			10 => 'banana',
		);
		$this->assertEquals($expected, $rv);
	}

	function test_file_get_contents() {
		$file = $this->_getTempName();
		$data = 'aline\r\nanother line\ranother line\n';
		$rv = file_put_contents($file, $data);
		$this->assertEquals(strlen($data), $rv, 'length mismatch');
		$rv = file_get_contents($file);
		$this->assertEquals($data, $rv, 'data mismatch');
		unlink($file);
	}

	function test_file_put_contents() {
		$file = $this->_getTempName();
		$data = 'aline\r\nanother line\ranother line\n';
		$rv = file_put_contents($file, $data);
		$this->assertEquals(strlen($data), $rv, 'length mismatch');
		$rv = file_get_contents($file);
		$this->assertEquals($data, $rv, 'data mismatch');
		unlink($file);
	}

	function test_floatval() {
		$data = array(
			'122.34343The'	=> 122.34343,
			'1E02'			=> 1E02,
			'+1E02'			=> +1E02,
			'-1E02'			=> -1E02,
			'1E+02'			=> 1E+02,
			'1E-02'			=> 1E-02,
			'+1E+02'		=> +1E+02,
			'-1E-02'		=> -1E-02,
			'+1E-02'		=> +1E-02,
			'-1E+02'		=> -1E+02,
		);
		foreach ($data as $input => $expected) {
			$this->assertEquals($expected, floatval($input), "input='$input'");
		}
	}

	function test_is_finite_1() {
		$finite = 1;
		$infinite = log(0);
		$rv = is_finite($finite);
		$this->assertTrue($rv);
		$rv = is_finite(-$finite);
		$this->assertTrue($rv);
		$rv = is_finite($infinite);
		$this->assertFalse($rv);
		$rv = is_finite(-$infinite);
		$this->assertFalse($rv);
	}

	function test_is_infinite_1() {
		$finite = 1;
		$infinite = log(0);
		$rv = is_infinite($infinite);
		$this->assertTrue($rv);
		$rv = is_infinite(-$infinite);
		$this->assertTrue($rv);
		$rv = is_infinite($finite);
		$this->assertFalse($rv);
		$rv = is_infinite(-$finite);
		$this->assertFalse($rv);
	}

	function test_md5_file() {
		$file = $this->_getTempName();
		file_put_contents($file, 'A');
		$rv = md5_file($file);
		$expected = md5('A');
		$this->assertEquals($expected, $rv);
		unlink($file);
	}

	function test_sha1() {
		global $skip_sha1;
		
		if ($skip_sha1) {
			return;
		}

		$data = 'A';
		$rv = sha1($data);
		$expected = '6dcd4ce23d88e2ee9568ba546c007c63d9131c1b';
		$this->assertEquals($expected, $rv);
	}

	function test_sha1_file() {
		global $skip_sha1;
		
		if ($skip_sha1) {
			return;
		}

		$file = $this->_getTempName();
		$data = 'A';
		file_put_contents($file, $data);
		$rv = sha1_file($file);
		$expected = '6dcd4ce23d88e2ee9568ba546c007c63d9131c1b';
		$this->assertEquals($expected, $rv);
		unlink($file);
	}

	function test_stripos() {
		$findme    = 'a';
		$mystring1 = 'xyz';
		$mystring2 = 'ABC';

		$rv = stripos($mystring1, $findme);
		$this->assertTrue(false === $rv,
			"expected='false' actual='$rv'\n");
	}

	function test_stripos_2() {
		$findme    = 'a';
		$mystring1 = 'xyz';
		$mystring2 = 'ABC';

		$rv = stripos($mystring2, $findme);
		$this->assertTrue(0 === $rv,
			"expected='0' actual='$rv'\n");
	}

	function test_strripos() {
		$haystack = 'ababcd';
		$needle   = 'aB';

		$rv = strripos($haystack, $needle);
		$this->assertEquals(2, $rv);
	}

	function test_str_ireplace() {
		$rv = str_ireplace('%body%', 'black', '<body text=%BODY%>');
		$expected = '<body text=black>';
		$this->assertEquals($expected, $rv);
	}

	function test_str_split() {
		$str = 'Hello Friend';
		$rv = str_split($str);
		$expected = array(
			0 => 'H',
			1 => 'e',
			2 => 'l',
			3 => 'l',
			4 => 'o',
			5 => ' ',
			6 => 'F',
			7 => 'r',
			8 => 'i',
			9 => 'e',
			10 => 'n',
			11 => 'd',
		);
		$this->assertEquals($expected, $rv);
	}

	function test_str_split_3() {
		$str = 'Hello Friend';
		$rv = str_split($str, 3);
		$expected = array(
			0 => 'Hel',
			1 => 'lo ',
			2 => 'Fri',
			3 => 'end',
		);
		$this->assertEquals($expected, $rv);
	}

	function test_is_a() {
		$a = new fbBackport1();
		$b = new fbBackport2();
		$c = new fbBackport3();
		$this->assertEquals(true, is_a($a, 'fbBackport1'), "is_a(\$a, 'fbBackport1')");
		$this->assertEquals(true, is_a($b, 'fbBackport2'), "is_a(\$b, 'fbBackport2')");
		$this->assertEquals(true, is_a($b, 'fbBackport1'), "is_a(\$b, 'fbBackport1')");
		$this->assertEquals(true, is_a($c, 'fbBackport3'), "is_a(\$c, 'fbBackport3')");
		$this->assertEquals(true, is_a($c, 'fbBackport2'), "is_a(\$c, 'fbBackport2')");
		$this->assertEquals(true, is_a($c, 'fbBackport1'), "is_a(\$c, 'fbBackport1')");
	}

	function test_is_nan() {
		$this->assertTrue(is_nan(acos(1.01)));
		$this->assertFalse(is_nan(0));
	}

	function test_str_rot13() {
		$rv = str_rot13('abcde');
		$expected = 'nopqr';
		$this->assertEquals($expected, $rv);
	}

	function test_var_export_int() {
		$v = 1;
		$rv = var_export($v, true);
		$expected = '1';
		$this->assertEquals($expected, $rv);
	}

	function test_var_export_string() {
		$v = 'string';
		$rv = var_export($v, true);
		// >= PHP 4.2.0
		$expected = "'string'";
		// < PHP 4.2.0
		$expected2 = "string";
		$this->assertTrue($rv == $expected || $rv == $expected2,
			"rv='$rv' expected='$expected' expected2='$expected2'\n");
	}

	function test_var_export_array() {
		$v = array(1,2);
		$rv = var_export($v, true);
		// >= PHP 4.2.0
		$expected = "array (\n  0 => 1,\n  1 => 2,\n)";
		// < PHP 4.2.0
		$expected2 = "Array\n(\n    [0] => 1\n    [1] => 2\n)\n";
		$this->assertTrue($rv == $expected || $rv == $expected2,
			"rv='$rv'\n expected2='$expected2'\n\\t='\n\t'");
	}

	function test_DIRECTORY_SEPARATOR_1() {
		$this->assertTrue(defined('DIRECTORY_SEPARATOR'));
	}

	function test_PATH_SEPARATOR_1() {
		$this->assertTrue(defined('PATH_SEPARATOR'));
	}

	function test_PHP_SHLIB_SUFFIX_1() {
		$this->assertTrue(defined('PHP_SHLIB_SUFFIX'));
	}

}

?>
