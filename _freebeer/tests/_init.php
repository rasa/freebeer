<?php

// $CVSHeader: _freebeer/tests/_init.php,v 1.2 2004/03/07 17:51:26 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

error_reporting(2047);

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(__FILE__)));

require_once FREEBEER_BASE . '/lib/Pear/Pear.php';

require_once 'PHPUnit/TestCase.php';

class fbTestCase extends PHPUnit_TestCase {
	function __construct($name) {
        parent::PHPUnit_TestCase($name);
	}
	
    function fbTestCase($name) {
        parent::PHPUnit_TestCase($name);
    }

    function getContainer() {
    }

    function getExtraOptions() {
    }

	function setUp() {
	}

	function tearDown() {
	}
	
	function enabled() {
		return true;
	}
	
}

?>
