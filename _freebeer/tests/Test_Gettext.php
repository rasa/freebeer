<?php

// $CVSHeader: _freebeer/tests/Test_Gettext.php,v 1.2 2004/03/07 17:51:26 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/Gettext.php';

class _Test_Gettext extends fbTestCase {

	function _Test_Gettext($name) {
        parent::__construct($name);
	}

	function setUp() {
	}

	function tearDown() {
	}
	
	function _test() {
		static $test = null;
		
		if (is_null($test)) {
			$test = !extension_loaded('gettext');
			if (!$test) {
				trigger_error('Unable to fully test fbGettext class as gettext extension is loaded.', E_USER_NOTICE);
			}
		}

		return $test;
	}

	function test_init_1() {
		if (!$this->_test()) {
			return;
		}
		
//		$o = &new fbGettext();
//		$rv = $o->init();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
	
}

?>
<?php /*
	// \todo Implement test_init_1 in Test_Gettext.php
	function test_init_1() {
//		$o = &new fbGettext();
//		$rv = $o->init();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_bindtextdomain_1 in Test_Gettext.php
	function test_bindtextdomain_1() {
//		$o = &new fbGettext();
//		$rv = $o->bindtextdomain();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_textdomain_1 in Test_Gettext.php
	function test_textdomain_1() {
//		$o = &new fbGettext();
//		$rv = $o->textdomain();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_gettext_1 in Test_Gettext.php
	function test_gettext_1() {
//		$o = &new fbGettext();
//		$rv = $o->gettext();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_bind_textdomain_codeset_1 in Test_Gettext.php
	function test_bind_textdomain_codeset_1() {
//		$o = &new fbGettext();
//		$rv = $o->bind_textdomain_codeset();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_dcgettext_1 in Test_Gettext.php
	function test_dcgettext_1() {
//		$o = &new fbGettext();
//		$rv = $o->dcgettext();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_dcngettext_1 in Test_Gettext.php
	function test_dcngettext_1() {
//		$o = &new fbGettext();
//		$rv = $o->dcngettext();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_dgettext_1 in Test_Gettext.php
	function test_dgettext_1() {
//		$o = &new fbGettext();
//		$rv = $o->dgettext();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_dngettext_1 in Test_Gettext.php
	function test_dngettext_1() {
//		$o = &new fbGettext();
//		$rv = $o->dngettext();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_ngettext_1 in Test_Gettext.php
	function test_ngettext_1() {
//		$o = &new fbGettext();
//		$rv = $o->ngettext();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
