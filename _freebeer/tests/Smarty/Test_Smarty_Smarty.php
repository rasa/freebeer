<?php

// $CVSHeader: _freebeer/tests/Smarty/Test_Smarty_Smarty.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/Smarty/Smarty.php';

class _Test_Smarty_Smarty extends fbTestCase {

	function _Test_Smarty_Smarty($name) {
        parent::__construct($name);
	}

	function setUp() {
	}

	function tearDown() {
	}

	function isAvailable() {
			return fbSmarty::isAvailable();
	}

}

?><?php /*
	// \todo Implement test_append_1 in Test_Smarty_Smarty.php
	function test_append_1() {
//		$o =& new Smarty();
//		$rv = $o->append();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_append_by_ref_1 in Test_Smarty_Smarty.php
	function test_append_by_ref_1() {
//		$o =& new Smarty();
//		$rv = $o->append_by_ref();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_assign_1 in Test_Smarty_Smarty.php
	function test_assign_1() {
//		$o =& new Smarty();
//		$rv = $o->assign();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_assign_by_ref_1 in Test_Smarty_Smarty.php
	function test_assign_by_ref_1() {
//		$o =& new Smarty();
//		$rv = $o->assign_by_ref();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_clear_all_assign_1 in Test_Smarty_Smarty.php
	function test_clear_all_assign_1() {
//		$o =& new Smarty();
//		$rv = $o->clear_all_assign();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_clear_all_cache_1 in Test_Smarty_Smarty.php
	function test_clear_all_cache_1() {
//		$o =& new Smarty();
//		$rv = $o->clear_all_cache();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_clear_assign_1 in Test_Smarty_Smarty.php
	function test_clear_assign_1() {
//		$o =& new Smarty();
//		$rv = $o->clear_assign();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_clear_cache_1 in Test_Smarty_Smarty.php
	function test_clear_cache_1() {
//		$o =& new Smarty();
//		$rv = $o->clear_cache();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_clear_compiled_tpl_1 in Test_Smarty_Smarty.php
	function test_clear_compiled_tpl_1() {
//		$o =& new Smarty();
//		$rv = $o->clear_compiled_tpl();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_clear_config_1 in Test_Smarty_Smarty.php
	function test_clear_config_1() {
//		$o =& new Smarty();
//		$rv = $o->clear_config();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_config_load_1 in Test_Smarty_Smarty.php
	function test_config_load_1() {
//		$o =& new Smarty();
//		$rv = $o->config_load();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_display_1 in Test_Smarty_Smarty.php
	function test_display_1() {
//		$o =& new Smarty();
//		$rv = $o->display();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_fbsmarty_1 in Test_Smarty_Smarty.php
	function test_fbsmarty_1() {
//		$o =& new Smarty();
//		$rv = $o->fbsmarty();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_fetch_1 in Test_Smarty_Smarty.php
	function test_fetch_1() {
//		$o =& new Smarty();
//		$rv = $o->fetch();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_get_config_vars_1 in Test_Smarty_Smarty.php
	function test_get_config_vars_1() {
//		$o =& new Smarty();
//		$rv = $o->get_config_vars();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_get_registered_object_1 in Test_Smarty_Smarty.php
	function test_get_registered_object_1() {
//		$o =& new Smarty();
//		$rv = $o->get_registered_object();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_get_template_vars_1 in Test_Smarty_Smarty.php
	function test_get_template_vars_1() {
//		$o =& new Smarty();
//		$rv = $o->get_template_vars();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_is_cached_1 in Test_Smarty_Smarty.php
	function test_is_cached_1() {
//		$o =& new Smarty();
//		$rv = $o->is_cached();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_load_filter_1 in Test_Smarty_Smarty.php
	function test_load_filter_1() {
//		$o =& new Smarty();
//		$rv = $o->load_filter();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_quote_replace_1 in Test_Smarty_Smarty.php
	function test_quote_replace_1() {
//		$o =& new Smarty();
//		$rv = $o->quote_replace();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_register_block_1 in Test_Smarty_Smarty.php
	function test_register_block_1() {
//		$o =& new Smarty();
//		$rv = $o->register_block();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_register_compiler_function_1 in Test_Smarty_Smarty.php
	function test_register_compiler_function_1() {
//		$o =& new Smarty();
//		$rv = $o->register_compiler_function();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_register_function_1 in Test_Smarty_Smarty.php
	function test_register_function_1() {
//		$o =& new Smarty();
//		$rv = $o->register_function();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_register_modifier_1 in Test_Smarty_Smarty.php
	function test_register_modifier_1() {
//		$o =& new Smarty();
//		$rv = $o->register_modifier();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_register_object_1 in Test_Smarty_Smarty.php
	function test_register_object_1() {
//		$o =& new Smarty();
//		$rv = $o->register_object();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_register_outputfilter_1 in Test_Smarty_Smarty.php
	function test_register_outputfilter_1() {
//		$o =& new Smarty();
//		$rv = $o->register_outputfilter();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_register_postfilter_1 in Test_Smarty_Smarty.php
	function test_register_postfilter_1() {
//		$o =& new Smarty();
//		$rv = $o->register_postfilter();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_register_prefilter_1 in Test_Smarty_Smarty.php
	function test_register_prefilter_1() {
//		$o =& new Smarty();
//		$rv = $o->register_prefilter();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_register_resource_1 in Test_Smarty_Smarty.php
	function test_register_resource_1() {
//		$o =& new Smarty();
//		$rv = $o->register_resource();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_smarty_1 in Test_Smarty_Smarty.php
	function test_smarty_1() {
//		$o =& new Smarty();
//		$rv = $o->smarty();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_template_exists_1 in Test_Smarty_Smarty.php
	function test_template_exists_1() {
//		$o =& new Smarty();
//		$rv = $o->template_exists();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_trigger_error_1 in Test_Smarty_Smarty.php
	function test_trigger_error_1() {
//		$o =& new Smarty();
//		$rv = $o->trigger_error();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_unregister_block_1 in Test_Smarty_Smarty.php
	function test_unregister_block_1() {
//		$o =& new Smarty();
//		$rv = $o->unregister_block();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_unregister_compiler_function_1 in Test_Smarty_Smarty.php
	function test_unregister_compiler_function_1() {
//		$o =& new Smarty();
//		$rv = $o->unregister_compiler_function();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_unregister_function_1 in Test_Smarty_Smarty.php
	function test_unregister_function_1() {
//		$o =& new Smarty();
//		$rv = $o->unregister_function();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_unregister_modifier_1 in Test_Smarty_Smarty.php
	function test_unregister_modifier_1() {
//		$o =& new Smarty();
//		$rv = $o->unregister_modifier();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_unregister_object_1 in Test_Smarty_Smarty.php
	function test_unregister_object_1() {
//		$o =& new Smarty();
//		$rv = $o->unregister_object();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_unregister_outputfilter_1 in Test_Smarty_Smarty.php
	function test_unregister_outputfilter_1() {
//		$o =& new Smarty();
//		$rv = $o->unregister_outputfilter();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_unregister_postfilter_1 in Test_Smarty_Smarty.php
	function test_unregister_postfilter_1() {
//		$o =& new Smarty();
//		$rv = $o->unregister_postfilter();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_unregister_prefilter_1 in Test_Smarty_Smarty.php
	function test_unregister_prefilter_1() {
//		$o =& new Smarty();
//		$rv = $o->unregister_prefilter();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
<?php /*
	// \todo Implement test_unregister_resource_1 in Test_Smarty_Smarty.php
	function test_unregister_resource_1() {
//		$o =& new Smarty();
//		$rv = $o->unregister_resource();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
