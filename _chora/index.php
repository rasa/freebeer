<?php
/**
 * $Horde: chora/index.php,v 1.22 2004/01/01 15:14:00 jan Exp $
 *
 * Copyright 1999-2004 Anil Madhavapeddy <anil@recoil.org>
 *
 * See the enclosed file COPYING for license information (GPL).  If you
 * did not receive this file, see http://www.fsf.org/copyleft/gpl.html.
 */

define('CHORA_BASE', dirname(__FILE__));
$chora_configured = (@is_readable(CHORA_BASE . '/config/conf.php') &&
                     @is_readable(CHORA_BASE . '/config/sourceroots.php') &&
                     @is_readable(CHORA_BASE . '/config/mime_drivers.php') &&
                     @is_readable(CHORA_BASE . '/config/prefs.php') &&
                     @is_readable(CHORA_BASE . '/config/html.php'));

if (!$chora_configured) {
    /* Chora isn't configured. */
    define('HORDE_LIBS', '');
    require CHORA_BASE . '/../lib/Test.php';
    Horde_Test::configFilesMissing('Chora', CHORA_BASE,
        array('conf.php', 'html.php', 'prefs.php', 'mime_drivers.php'),
        array('sourceroots.php' => 'This file defines all of the source repositories that you wish Chora to display.'));
}

require CHORA_BASE . '/cvs.php';
