<?php
/**
 * $Horde: horde/services/portal/navbar.php,v 2.9 2004/02/14 01:36:28 chuck Exp $
 *
 * Copyright 2002-2004 Charles J. Hagenbuch, <chuck@horde.org>
 *
 * See the enclosed file COPYING for license information (LGPL).  If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 */

require_once HORDE_LIBS . 'Horde/Menu.php';
require_once HORDE_LIBS . 'Horde/Help.php';

require HORDE_TEMPLATES . '/navbar/menu.inc';
$notification->notify(array('listeners' => 'status'));

/* Include the JavaScript for the help system. */
Help::javascript();
