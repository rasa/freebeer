<?php

// $CVSHeader: _freebeer/tests/BinarySearch/Test_BinarySearch_File.php,v 1.3 2004/03/07 17:51:27 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/BinarySearch/File.php';

require_once FREEBEER_BASE . '/lib/GeoIP/Free/Ascii.php';	// _formatIP4address();

class _Test_BinarySearch_File extends fbTestCase {

    function _Test_BinarySearch_File($name) {
        parent::__construct($name);
    }

	function setUp() {
		$this->_file = FREEBEER_BASE . '/etc/geo/geo-ips.txt';
	}

	function tearDown() {
	}

	function test_fbBinarySearch_File_1() {
		$ips = array(
			'0.0.0.0'			=> "000.000.000.000\t000.255.255.255\tUS",
			'10.0.0.1'			=> "010.000.000.000\t010.255.255.255\tI0",
			'10.255.255.255'	=> "010.000.000.000\t010.255.255.255\tI0",
			'127.0.0.1'			=> "127.000.000.000\t127.255.255.255\tL0",
			'127.0.0.2'			=> "127.000.000.000\t127.255.255.255\tL0",
			'127.255.255.255'	=> "127.000.000.000\t127.255.255.255\tL0",
			'172.16.0.0'		=> "172.016.000.000\t172.031.255.255\tI0",
			'172.31.255.255'	=> "172.016.000.000\t172.031.255.255\tI0",
			'192.168.0.0'		=> "192.168.000.000\t192.168.255.255\tI0",
			'192.168.0.1'		=> "192.168.000.000\t192.168.255.255\tI0",
			'192.168.255.255'	=> "192.168.000.000\t192.168.255.255\tI0",
			'255.0.0.0'			=> "221.097.211.000\t255.255.255.254\tUS",
			'255.255.255.254'	=> "221.097.211.000\t255.255.255.254\tUS",
			'255.255.255.255'	=> "255.255.255.255\t255.255.255.255\t--",
		);

		foreach($ips as $ip => $expected) {
			$ipf = fbGeoIP_Free_Ascii::_formatIP4address($ip);
			$bsf = &new fbBinarySearch_File($this->_file);
			$rv = $bsf->search($ipf);
			$rv = trim($rv);
			$this->assertEquals($expected, $rv, "ip='$ip' ipf='$ipf'");
		}
	}

}

# make PHPUnit_GUI_SetupDecorator() happy
class _BinarySearch_Test_BinarySearch_File extends _Test_BinarySearch_File {
}

?>
<?php /*
	// \todo Implement test_getcache_1 in Test_BinarySearch_File.php
	function test_getcache_1() {
//		$o =& new File();
//		$rv = $o->getcache();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getcomparefunction_1 in Test_BinarySearch_File.php
	function test_getcomparefunction_1() {
//		$o =& new File();
//		$rv = $o->getcomparefunction();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getfile_1 in Test_BinarySearch_File.php
	function test_getfile_1() {
//		$o =& new File();
//		$rv = $o->getfile();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getfilesize_1 in Test_BinarySearch_File.php
	function test_getfilesize_1() {
//		$o =& new File();
//		$rv = $o->getfilesize();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getloadall_1 in Test_BinarySearch_File.php
	function test_getloadall_1() {
//		$o =& new File();
//		$rv = $o->getloadall();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getreadlength_1 in Test_BinarySearch_File.php
	function test_getreadlength_1() {
//		$o =& new File();
//		$rv = $o->getreadlength();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getrecordlength_1 in Test_BinarySearch_File.php
	function test_getrecordlength_1() {
//		$o =& new File();
//		$rv = $o->getrecordlength();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getrecordnumber_1 in Test_BinarySearch_File.php
	function test_getrecordnumber_1() {
//		$o =& new File();
//		$rv = $o->getrecordnumber();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_search_1 in Test_BinarySearch_File.php
	function test_search_1() {
//		$o =& new File();
//		$rv = $o->search();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_setcache_1 in Test_BinarySearch_File.php
	function test_setcache_1() {
//		$o =& new File();
//		$rv = $o->setcache();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_setcomparefunction_1 in Test_BinarySearch_File.php
	function test_setcomparefunction_1() {
//		$o =& new File();
//		$rv = $o->setcomparefunction();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_setfile_1 in Test_BinarySearch_File.php
	function test_setfile_1() {
//		$o =& new File();
//		$rv = $o->setfile();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_setloadall_1 in Test_BinarySearch_File.php
	function test_setloadall_1() {
//		$o =& new File();
//		$rv = $o->setloadall();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_setreadlength_1 in Test_BinarySearch_File.php
	function test_setreadlength_1() {
//		$o =& new File();
//		$rv = $o->setreadlength();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_setrecordlength_1 in Test_BinarySearch_File.php
	function test_setrecordlength_1() {
//		$o =& new File();
//		$rv = $o->setrecordlength();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
