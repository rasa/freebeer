<?php

// $CVSHeader: _freebeer/lib/Pear/Pear.php,v 1.1.1.1 2004/01/18 00:12:04 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

/*!
	\file Pear/Pear.php
	\brief PEAR support class
*/

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(dirname(__FILE__))));

/*
Windows:

DEFAULT_INCLUDE_PATH	=> .;c:\php4\pear
PEAR_EXTENSION_DIR	=> c:\php4
PEAR_INSTALL_DIR	=> c:\php4\pear

Debian GNU/Linux:

DEFAULT_INCLUDE_PATH	=> .:/usr/share/php:/usr/share/pear
PEAR_EXTENSION_DIR	=> /usr/lib/php4/20020429
PEAR_INSTALL_DIR	=> /usr/share/php

*/

if (@is_dir(FREEBEER_BASE . '/opt/pear')) {
	require_once FREEBEER_BASE . '/lib/System.php';

	fbSystem::appendIncludePath(FREEBEER_BASE . '/opt/pear');
}

if (defined('PEAR_INSTALL_DIR') && @is_dir(PEAR_INSTALL_DIR)) {
	require_once FREEBEER_BASE . '/lib/System.php';

	fbSystem::appendIncludePath(PEAR_INSTALL_DIR);
}

/*!
	\class fbPear
	\brief PEAR support class
*/
class fbPear {
	/*!
		\return \c bool
		\static
	*/
	function isAvailable() {
		static $rv = null;
		
		if (is_null($rv)) {
				$rv = @is_dir(FREEBEER_BASE . '/opt/pear') ||
			(defined('PEAR_INSTALL_DIR') && @is_dir(PEAR_INSTALL_DIR));
		}
		
		return $rv;
	}

}

?>
