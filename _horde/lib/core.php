<?php
/**
 * Horde Application Framework core services file.
 *
 * This file sets up any necessary include path variables and includes
 * the minimum required Horde libraries.
 *
 * $Horde: horde/lib/core.php,v 1.21 2004/02/14 02:40:35 chuck Exp $
 *
 * Copyright 1999-2004 Chuck Hagenbuch <chuck@horde.org>
 *
 * See the enclosed file COPYING for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 */

/* Turn PHP stuff off that can really screw things up. */
ini_set('magic_quotes_sybase', 0);
ini_set('magic_quotes_runtime', 0);

// Define the include prefix for all Horde libaries. This should be
// empty if you have the libraries installed in your include path,
// either through the PEAR installer or otherwise. If you are using a
// tarball distribution, or a custom version of Horde, it should
// probably set to dirname(__FILE__).
//
// This path MUST end in a trailing slash, or be entirely empty.
//
// Explicitly check for prior definitions so as to allow prepend_file
// customizations.
if (!defined('HORDE_LIBS')) {
    define('HORDE_LIBS', '');
}

// Horde core classes.
include_once HORDE_LIBS . 'Horde.php';
include_once HORDE_LIBS . 'Horde/Registry.php';
include_once HORDE_LIBS . 'Horde/String.php';
include_once HORDE_LIBS . 'Horde/Notification.php';
include_once HORDE_LIBS . 'Horde/Auth.php';
include_once HORDE_LIBS . 'Horde/Browser.php';
include_once HORDE_LIBS . 'Horde/Perms.php';

// Browser detection object.
$browser = &Browser::singleton();
