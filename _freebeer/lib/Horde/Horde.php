<?php

// $CVSHeader: _freebeer/lib/Horde/Horde.php,v 1.2 2004/03/07 17:51:21 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

/*!
	\file Horde/Horde.php
	\brief Horde support functions
*/

defined('FREEBEER_BASE') || 
 define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') : dirname(dirname(dirname(__FILE__))));

if (!defined('HORDE_BASE')) {
	if (@is_dir(FREEBEER_BASE . '/opt/horde')) {
		define('HORDE_BASE', FREEBEER_BASE . '/opt/horde');
	} elseif (is_dir('/usr/share/horde2')) {
		// Debian
		define('HORDE_BASE', '/usr/share/horde2');
	}
}

/*!
	\class fbHorde
	\brief Horde support functions

	\static
*/
class fbHorde {
	/*!
		\return \c bool
		\static
	*/
	function isAvailable() {
		return defined('HORDE_BASE');
	}

}

?>
