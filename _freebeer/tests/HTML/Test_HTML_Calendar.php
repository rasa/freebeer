<?php

// $CVSHeader: _freebeer/tests/HTML/Test_HTML_Calendar.php,v 1.3 2004/03/07 17:51:29 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

// requires PEAR.php
// require_once FREEBEER_BASE . '/lib/HTML/Calendar.php';

class _Test_HTML_Calendar extends fbTestCase {

	function _Test_HTML_Calendar($name) {
        parent::__construct($name);
	}

	function setUp() {
	}

	function tearDown() {
	}

}

# make PHPUnit_GUI_SetupDecorator() happy
class _HTML_Test_HTML_Calendar extends _Test_HTML_Calendar {
}

?>
