<?php

// $CVSHeader: _freebeer/tests/Test_System.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/System.php';

class _Test_System extends fbTestCase {

    function _Test_System($name) {
        parent::__construct($name);
    }

	function setUp() {
	}

	function tearDown() {
	}

	function test_init() {
		global $_SERVER; // < 4.1.0
		
		fbSystem::_init();
		if (!fbSystem::isCLI()) {
			$this->assertTrue(!empty($_SERVER['SCRIPT_FILENAME']), "\$_SERVER['SCRIPT_FILENAME'] is empty");
			$this->assertTrue(!empty($_SERVER['REQUEST_URI']), "\$_SERVER['REQUEST_URI'] is empty");
			$t = ini_get('session.save_path');
			if ($t) {
				$this->assertTrue(@is_dir($t), "session.save_path '$t' isn't a valid directory");
			}
			$t = ini_get('upload_tmp_dir');
			if ($t) {
				$this->assertTrue(@is_dir($t), "upload_tmp_dir '$t' isn't a valid directory");
			}
		}
	}

	function test_getPhpinfo() {
		$array = array(
			INFO_GENERAL,
			INFO_CREDITS,
			INFO_CONFIGURATION,
			INFO_MODULES,
			INFO_ENVIRONMENT,
			INFO_VARIABLES,
			INFO_LICENSE,
			INFO_ALL,
		);

		foreach ($array as $info) {
			$rv = fbSystem::getPhpinfo($info);
			$this->assertTrue(!empty($rv));
		}
	}

	function test_getEnvVar() {
		$rv = fbSystem::getEnvVar('PATH');
		$this->assertTrue(!empty($rv));
	}
	
	function test_getEnvVar_2() {
		$rv = fbSystem::getEnvVar('path');
		$this->assertTrue(!empty($rv));
	}
	
	function test_getEnvVar_3() {
		$rv = fbSystem::getEnvVar('pAtH', true);
		$this->assertFalse($rv);
	}

	function test_getEnvVar_4() {
		$rv = fbSystem::getEnvVar(' ');
		$this->assertFalse($rv);
	}

	function test_getServerVar() {
		$rv = fbSystem::getServerVar('PATH');
		$this->assertTrue(!empty($rv));
	}

	function test_getServerVar_2() {
		$rv = fbSystem::getServerVar('path');
		$this->assertTrue(!empty($rv));
	}

	function test_getServerVar_3() {
		$rv = fbSystem::getServerVar('pAtH', true);
		$this->assertFalse($rv);
	}

	function test_getServerVar_4() {
		$rv = fbSystem::getServerVar(' ');
		$this->assertFalse($rv);
	}

	function test_platform() {
		$rv = fbSystem::platform();
		$this->assertTrue(!empty($rv));
	}

	function test_isCLI() {
		$rv = fbSystem::isCLI();
		$this->assertTrue(is_bool($rv));
	}

	function test_isApache() {
		$rv = fbSystem::isApache();
		$this->assertTrue(is_bool($rv));
	}

	function test_directorySeparator() {
		$rv = fbSystem::directorySeparator();
		$this->assertTrue(!empty($rv));
	}

	function test_lineSeparator() {
		$rv = fbSystem::lineSeparator();
		$this->assertTrue(!empty($rv));
	}

	function test_pathSeparator() {
		$rv = fbSystem::pathSeparator();
		$this->assertTrue(!empty($rv));
	}

	function test_hostname() {
		$rv = fbSystem::hostname();
		$this->assertTrue(!empty($rv));
	}

	function test_username() {
		$rv = fbSystem::username();
		if (fbSystem::isCLI()) {
			$this->assertTrue(!empty($rv));
		}
	}

	function test_tempDirectory() {
		$rv = fbSystem::tempDirectory();
		$this->assertTrue(!empty($rv));
		$this->assertTrue(@is_dir($rv), "Not a directory: '$rv'");
		$this->assertTrue(@is_writeable($rv), "Not a writeable directory: '$rv'");
	}

	function test_getLastError() {
		fbSystem::setLastError('Error 1');
		$rv = fbSystem::getLastError();
		$this->assertTrue(!empty($rv));
	}

//	function test_setLastError() {
//	}

	function test_extensionSuffix() {
		$rv = fbSystem::extensionSuffix();
		$this->assertTrue(!empty($rv));
	}

	function test_loadExtension() {
		$rv = fbSystem::loadExtension('curl');
		$this->assertTrue(is_bool($rv));
	}

	function test_isExtensionLoaded() {
		$rv = fbSystem::isExtensionLoaded('standard');
		$this->assertTrue($rv, "standard extension isn't loaded?");
	}

	function test_appendIncludePath() {
		$old_include_path = ini_get('include_path');
		$path = FREEBEER_BASE . '/lib/tests';
		$rv = fbSystem::appendIncludePath($path);
		$include_path = ini_get('include_path');
		$this->assertTrue(strpos($include_path, $path) > 0);
		ini_set('include_path', $old_include_path);
	}

	function test_prependIncludePath() {
		$old_include_path = ini_get('include_path');
		$path = FREEBEER_BASE . '/lib/tests';
		$rv = fbSystem::prependIncludePath($path);
		$include_path = ini_get('include_path');
		$this->assertTrue(strpos($include_path, $path) === 0);
		ini_set('include_path', $old_include_path);
	}

	function test_putenv_1() {
		$rv = fbSystem::putEnv('PHPRC', 'C:\PHP');
		$this->assertTrue($rv, "rv='$rv'");

		$rv = fbSystem::putEnv('PHP_TZ', 'PST8PDT');
		$this->assertTrue($rv, "rv='$rv'");
	}

}

?>
<?php /*
	// \todo Implement test_includefile_1 in Test_System.php
	function test_includefile_1() {
//		$o =& new System();
//		$rv = $o->includefile();
//		$expected = 0;
//		$this->assertEquals($expected, $rv);
	}
*/ ?>
