<?php

// $CVSHeader: _freebeer/tests/Debug/Test_Debug_PHP_Constants.php,v 1.3 2004/03/07 17:51:28 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/Debug/PHP_Constants.php';

class _Test_Debug_PHP_Constants extends fbTestCase {

	function _Test_Debug_PHP_Constants($name) {
        parent::__construct($name);
	}

	function setUp() {
	}

	function tearDown() {
	}

}

# make PHPUnit_GUI_SetupDecorator() happy
class _Debug_Test_Debug_PHP_Constants extends _Test_Debug_PHP_Constants {
}

?>
