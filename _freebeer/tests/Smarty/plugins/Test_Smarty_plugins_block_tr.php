<?php

// $CVSHeader: _freebeer/tests/Smarty/plugins/Test_Smarty_plugins_block_tr.php,v 1.2 2004/03/07 17:51:32 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/Smarty/plugins/block.tr.php';

class _Test_Smarty_plugins_block_tr extends fbTestCase {

	function _Test_Smarty_plugins_block_tr($name) {
        parent::__construct($name);
	}

	function setUp() {
	}

	function tearDown() {
	}

}

# make PHPUnit_GUI_SetupDecorator() happy
class _Smarty_plugins_Test_Smarty_plugins_block_tr extends _Test_Smarty_plugins_block_tr {
}

?>
