<?php
/**
 * $Horde: horde/admin/perms/index.php,v 1.7 2004/02/23 08:27:57 slusarz Exp $
 *
 * Copyright 1999, 2000, 2001 Chuck Hagenbuch <chuck@horde.org>
 *
 * See the enclosed file COPYING for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 */

define('HORDE_BASE', dirname(__FILE__) . '/../..');
require_once HORDE_BASE . '/lib/base.php';
require_once HORDE_LIBS . 'Horde/Menu.php';

if (!Auth::isAdmin()) {
    Horde::authenticationFailureRedirect();
}

$form = null;
$cid = Util::getFormData('cid');

$title = _("Permissions Administration");
require HORDE_TEMPLATES . '/common-header.inc';
require HORDE_TEMPLATES . '/admin/common-header.inc';
$notification->notify(array('listeners' => 'status'));

require_once HORDE_LIBS . 'Horde/Perms/UI.php';
$ui = &new Perms_UI($perms);
$ui->renderTree($cid);

require HORDE_TEMPLATES . '/common-footer.inc';
