<?php

// $CVSHeader: _freebeer/lib/Smarty/Smarty.php,v 1.2 2004/03/07 17:51:23 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

/*!
	\file Smarty/Smarty.php
	\brief Smarty template engine support class
*/

/*

todo:

How about:

fbSystem::require('/opt/smarty', 'Smarty.class.php');

*/

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(dirname(__FILE__))));

if (!class_exists('Smarty') && is_file(FREEBEER_BASE . '/opt/smarty/Smarty.class.php')) {
	include_once FREEBEER_BASE . '/opt/smarty/Smarty.class.php';
} else {
	class Smarty {
	}
}

/*!
	\class fbSmarty
	\brief Smarty template engine support class
*/
class fbSmarty extends Smarty {
	/*!
		\static
	*/
	function isAvailable() {
		return method_exists('Smarty', 'Smarty'); 
	}

	/*!
		The Mozilla editor handles <% and %> nicely, it choked on <? and ?>
		Many people have complained about using { and }
	*/
    var $left_delimiter		=  '<%';

	/*!
		The right delimiter
	*/
    var $right_delimiter	=  '%>';

	/*!
		\todo Get debugging flag from fbDebug::debugging();
	*/
	function fbSmarty() {
		parent::Smarty();

		$base = FREEBEER_BASE . '/';

		$dir = $base . 'var/cache/smarty/cache';
		if (@is_dir($dir)) {
			$this->cache_dir	= $dir;
		}
		$dir = $base . 'var/cache/smarty/templates_c';
		if (@is_dir($dir)) {
			$this->compile_dir	= $dir;
		}
		$dir = $base . 'etc/smarty';
		if (@is_dir($dir)) {
			$this->config_dir	= $dir;
		}
		$dir = $base . 'html';
		if (@is_dir($dir)) {
			$this->template_dir	= $dir;
			$this->debug_tpl	= $dir . '/debug.html';
		}

		$dir = FREEBEER_BASE . '/lib/Smarty/plugins';

		if (@is_dir($dir)) {
			array_unshift($this->plugins_dir, $dir);
		}

		if ($dh = @opendir($dir)) { 
			while (($file = readdir($dh)) !== false) { 
				if (preg_match('/(pre|post|output)filter\.([A-Za-z0-9_]+)\.php$/i', $file, $matches)) {
					$this->load_filter($matches[1], $matches[2]);
				}
			}
			closedir($dh);
		}

		// otherwise, only root can delete templates_c/*
    	$this->_file_perms	= 0666;
    	$this->_dir_perms	= 0777;

		// \todo add debug check
		$this->debugging		= false;
		$this->force_compile	= $this->debugging;
	}
}

?>
