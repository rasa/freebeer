<?php

// $CVSHeader: _freebeer/tests/Random/Test_Random_Win32.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/tests/Test_Random.php';

require_once FREEBEER_BASE . '/lib/Random/Win32.php';

class _Test_Random_Win32 extends _Test_Random {
	function _Test_Random_Win32($name) {
        parent::__construct($name);
	}

	function setUp() {
//		$this->tries = 1;
	}

	function tearDown() {
	}

	function isAvailable() {
		return fbRandom_Win32::isAvailable();
	}

	function enabled() {
		return false;	// remove to enable
		return $this->isAvailable();
	}

	function &newObject() {
		$rv = &new fbRandom_Win32();
		return $rv;
	}

}

?>
