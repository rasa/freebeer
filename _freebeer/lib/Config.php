<?php

// $CVSHeader: _freebeer/lib/Config.php,v 1.1.1.1 2004/01/18 00:12:04 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

/*!
	\file Config.php
	\brief Provides configuration system via file system
	
	\todo Read /etc/freebeer.xml, write to /var/etc/freebeer.php
*/

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(__FILE__)));

require_once FREEBEER_BASE . '/lib/Pear/Pear.php';

require_once 'Config.php';	// /opt/Pear/Config.php

		$GLOBALS['CONFIG_TYPES']['xml'] = array(
			FREEBEER_BASE . '/lib/Config/Container/XML.php',
			'fbConfig_Container_XML'
		);

defined('FB_CONFIG_DEFAULT_EXTENSION') ||
 define('FB_CONFIG_DEFAULT_EXTENSION', 'xml');

defined('FB_CONFIG_ETC_DIR') ||
 define('FB_CONFIG_ETC_DIR', FREEBEER_BASE . '/etc');

defined('FB_CONFIG_VAR_DIR') ||
 define('FB_CONFIG_VAR_DIR', FREEBEER_BASE . '/var/etc');

/*!
	\class fbConfig
	\brief Provides configuration system via file system
	
	\static
*/
class fbConfig {
	var $_etc_dir;

	var $_var_dir;
	
	var $_ext = FB_CONFIG_DEFAULT_EXTENSION;

	var $_file;

	var $_var_file;
	
	var $_conf = array();

	/*!
		Constructor
		\return \c void
	*/
	function fbConfig() {
		$this->_etc_dir = FB_CONFIG_ETC_DIR;
		$this->_var_dir =  FB_CONFIG_VAR_DIR;
		$this->setFile($this->_etc_dir . '/freebeer.' . $this->_ext);
	}

	/*!
		\return \c object
		\static
	*/
	function &getInstance() {
		static $instance = null;

        if (is_null($instance)) {
            $instance = &new fbConfig();
        }

		return $instance;
	}

	/*!
		\return \c string
	*/
	function getFile() {
		return $this->_file;
	}

	/*!
		\param $file \c string
		\return \c void
	*/
	function setFile($file) {
		$this->_file = (string) $file;
		$this->_var_file = $this->_var_dir . '/' . basename($file) . '_' . md5($file) . '.php';
 	}

	/*!
		\return \c bool
	*/
	function reset() {
		$this->_conf = array();
		return true;
	}
		
	/*!
		\return \c bool
		\static
	*/
	function read() {
		static $map = array(
			'php'		=> 'phparray',
			'xml'		=> 'xml',
			'ini'			=> 'inifile',
			'conf'		=> 'apache',
			'gconf'	=> 'genericconf',
		);

		clearstatcache();
		$file = $this->_file;
		if (!is_file($file)) {
		    trigger_error(sprintf('File not found: \'%s\'', $file), E_USER_WARNING);
			return false;
		}

		$mtime = filemtime($file);
		$var_mtime = is_file($this->_var_file) ? filemtime($this->_var_file) : 0;

		if ($var_mtime >= $mtime) {
			$file = $this->_var_file;
/*
	\todo read /var/etc/freebeer.php directly
		unset($conf);
		include $this->_var_file;
		assert('isset($conf)');
		assert("isset(\$conf['freebeer'])");
		$a = $conf['freebeer'];
		$this->_conf = array_merge($this->_conf, $a);
		return true;
*/
		}

		if (!preg_match('|.*\.([^.]+)$|', $file, $matches)) {
			$matches[1] = FB_CONFIG_DEFAULT_EXTENSION;
		}

		if (!isset($map[$matches[1]])) {
		    trigger_error(sprintf('Can\t determine file type for \'%s\'', $file), E_USER_WARNING);
		    return false;
		}
		$type = $map[$matches[1]];

		$conf = &new Config();
		$root = &$conf->parseConfig($file, $type);

$this->__writeAll($conf);
		
		if (PEAR::isError($root)) {
		    trigger_error(sprintf('Can\'t read \'%s\': %s', $file, $root->getMessage()), E_USER_WARNING);
		    return false;
		}

		$freebeer = &$root->searchPath(array('freebeer'));
		if (PEAR::isError($freebeer)) {
		    trigger_error(sprintf('Read error reading \'%s\': Can\'t find section \'%s\'', $file, 'freebeer'), E_USER_WARNING);
			return false;
		}

//echo "<pre>\n";
//print_r($freebeer);
//exit;

		$a = &$freebeer->toArray();

		if (!isset($a['freebeer'])) {
		    trigger_error(sprintf('Read error reading \'%s\': Can\'t find section \'%s\'', $this->_file, 'freebeer'), E_USER_WARNING);
			return false;
		}

		$a = $a['freebeer'];
		$this->_conf = array_merge($this->_conf, $a);

		return true;
	}
	
	function getSection(&$obj, $path = null) {
		if (!$path) {
				$path = get_class($obj);
				if (substr($path, 0, 2) == 'fb') {
					$path = substr($path, 2);
				}
				$path = array($path);
		}

		if (count($this->_conf) == 0) {
			$this->read();
		}

		$section = $path[count($path) - 1];
		$s = $this->_conf;
		if (!isset($s[$section])) {
		    trigger_error(sprintf('Read error reading \'%s\': Can\t find section \'%s\'', $this->_file, $section), E_USER_WARNING);
			return false;
		}

		if (!$obj) {
			return true;
		}

		foreach($s[$section] as $key => $value) {
			$func = 'set' . str_replace('_', '', $key);
			if (method_exists($obj, $func)) {
				$obj->$func($value);
			} else {
				$var = '_' . $key;
				$a = get_object_vars($obj);
				if (isset($a[$var])) {
					$obj->$var = $value;
				} else {
					trigger_error(sprintf('Unknown option \'%s\' in section \'%s\' in \'%s\'',
						$var, join(', ', $path), $this->_file));
				}
			}
		}
		
		return true;
	}

	function _setupWrite() {
		$GLOBALS['CONFIG_TYPES']['xml'] = array(
			FREEBEER_BASE . '/lib/Config/Container/XML.php',
			'fbConfig_Container_XML'
		);
	}
	

	function write() {
		$conf = &new Config_Container('section', 'freebeer');
		$cc = &$conf->createSection('https');

		$cc->createComment('fbHTTPS (' . basename(__FILE__) . ') configuration settings');
		$cc->createBlank();

		$cc->createComment('Convert <input src=\'/path/to/image.png\' /> type tags');
		$cc->createComment('Default: 0');

/*
		$cc->createDirective('convert_input_src', $this->_convert_input_src);
		$cc->createDirective('enabled', $this->_enabled);
		$cc->createDirective('http_host', $this->_http_host);
		$cc->createDirective('http_port', $this->_http_port);
		$cc->createDirective('http_path', $this->_http_path);
		$cc->createDirective('https_host', $this->_https_host);
		$cc->createDirective('https_port', $this->_https_port);
		$cc->createDirective('https_path', $this->_https_path);
*/

		$cc->createDirective('convert_input_src', true);
		$cc->createDirective('enabled', false);
		$cc->createDirective('http_host', 'http_host.com');
		$cc->createDirective('http_port', 8080);
		$cc->createDirective('http_path', '/http_path');
		$cc->createDirective('https_host', 'https_host.com');
		$cc->createDirective('https_port', 8443);
		$cc->createDirective('https_path', '/https_path');

		$cc = &$conf->createSection('adodb');

		$cc->createComment('ADOdb configuration settings');

		$cc->createBlank();
		$cc->createComment('Database driver name');
		$cc->createComment('Default: (none)');
		$cc->createDirective('driver', 'mysqlt');

		$cc->createBlank();
		$cc->createComment('Database host name');
		$cc->createComment('Default: localhost');
		$cc->createDirective('hostname', 'localhost');

		$cc->createBlank();
		$cc->createComment('Database user name');
		$cc->createComment('Default: root');
		$cc->createDirective('username', 'root');

		$cc->createBlank();
		$cc->createComment('Database password');
		$cc->createComment('Default: (none)');
		$cc->createDirective('password', '');

		$cc->createBlank();
		$cc->createComment('Initial database');
		$cc->createComment('Default: (none)');
		$cc->createDirective('database', '');

		$cc->createBlank();
		$cc->createComment('Session database');
		$cc->createComment('Default: (none)');
		$cc->createDirective('session_database', 'adodb_sessions');

		$cc->createBlank();
		$cc->createComment('Session table');
		$cc->createComment('Default: adodb_sessions');
		$cc->createDirective('session_table', 'adodb_sessions');

		$config = &new Config();
		$config->setRoot($conf);

$this->_setupWrite();
		$file = $this->_var_file;		
		$rv = $config->writeConfig($file, 'phparray');
		$umask = @umask(0);
		@chmod($file, 0660);
		@umask($umask);
		if (PEAR::isError($rv)) {
		    trigger_error(sprintf('Can\'t write to\'%s\': %s', $file, $rv->getMessage()), E_USER_WARNING);
			return false;			
		}

$this->__writeAll($config);
	}		

	function __writeAll($config) {
$this->_setupWrite();
		foreach($GLOBALS['CONFIG_TYPES'] as $key => $value) {
			$file = $this->_var_dir . '/freebeer2.' . $key;
			$rv = $config->writeConfig($file, $key);
			$umask = @umask(0);
			@chmod($file, 0660);
			@umask($umask);
			if (PEAR::isError($rv)) {
			    trigger_error(sprintf('Can\'t write to\'%s\': %s', $file, $rv->getMessage()), E_USER_WARNING);
				return false;			
			}
		}
	}
}

?>
