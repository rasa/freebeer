<?php

// $CVSHeader: _freebeer/tests/Test_File.php,v 1.1.1.1 2004/01/18 00:12:06 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/File.php';

class _Test_File extends fbTestCase {

	function _Test_File($name) {
        parent::__construct($name);
	}

	function setUp() {
	}

	function tearDown() {
	}

	function test_scandir_1() {
		$files = fbFile::scandir('.');
		$this->assertTrue(is_array($files), 'not an array');
		$this->assertTrue(count($files) > 0, 'array is empty');
//		$this->assertTrue($this->isSorted($files)), 'is not sorted');
	}

	function test_mkdirs_1() {
		@ini_set('track_errors', true);
		$php_errormsg = '';
		$dir = FREEBEER_BASE . '/var/tmp/test1/test2/test3';
		$rv = fbFile::mkdirs($dir);
		$this->assertTrue($rv, "mkdirs() failed");
		$this->assertTrue(@is_dir($dir), "mkdirs() failed to create '$dir': $php_errormsg");
		rmdir($dir);
		rmdir(dirname($dir));
		rmdir(dirname(dirname($dir)));
	}

}

?>
<?php /*
	// \todo Implement test_fbbinarysearch_file_1 in Test_File.php
	function test_fbbinarysearch_file_1() {
//		$o =& new File();
//		$rv = $o->fbbinarysearch_file();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getcache_1 in Test_File.php
	function test_getcache_1() {
//		$o =& new File();
//		$rv = $o->getcache();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getcomparefunction_1 in Test_File.php
	function test_getcomparefunction_1() {
//		$o =& new File();
//		$rv = $o->getcomparefunction();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getfile_1 in Test_File.php
	function test_getfile_1() {
//		$o =& new File();
//		$rv = $o->getfile();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getfilesize_1 in Test_File.php
	function test_getfilesize_1() {
//		$o =& new File();
//		$rv = $o->getfilesize();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getloadall_1 in Test_File.php
	function test_getloadall_1() {
//		$o =& new File();
//		$rv = $o->getloadall();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getreadlength_1 in Test_File.php
	function test_getreadlength_1() {
//		$o =& new File();
//		$rv = $o->getreadlength();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getrecordlength_1 in Test_File.php
	function test_getrecordlength_1() {
//		$o =& new File();
//		$rv = $o->getrecordlength();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_getrecordnumber_1 in Test_File.php
	function test_getrecordnumber_1() {
//		$o =& new File();
//		$rv = $o->getrecordnumber();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_search_1 in Test_File.php
	function test_search_1() {
//		$o =& new File();
//		$rv = $o->search();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_setcache_1 in Test_File.php
	function test_setcache_1() {
//		$o =& new File();
//		$rv = $o->setcache();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_setcomparefunction_1 in Test_File.php
	function test_setcomparefunction_1() {
//		$o =& new File();
//		$rv = $o->setcomparefunction();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_setfile_1 in Test_File.php
	function test_setfile_1() {
//		$o =& new File();
//		$rv = $o->setfile();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_setloadall_1 in Test_File.php
	function test_setloadall_1() {
//		$o =& new File();
//		$rv = $o->setloadall();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_setreadlength_1 in Test_File.php
	function test_setreadlength_1() {
//		$o =& new File();
//		$rv = $o->setreadlength();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_setrecordlength_1 in Test_File.php
	function test_setrecordlength_1() {
//		$o =& new File();
//		$rv = $o->setrecordlength();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
