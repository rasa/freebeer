<?php
/**
 * $Horde: horde/services/prefs.php,v 1.6 2004/03/06 22:09:50 mdjukic Exp $
 *
 * Copyright 1999-2004 Charles J. Hagenbuch <chuck@horde.org>
 * Copyright 1999-2004 Jon Parise <jon@horde.org>
 *
 * See the enclosed file COPYING for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 */

@define('HORDE_BASE', dirname(__FILE__) . '/..');
require_once dirname(__FILE__) . '/../lib/core.php';
require_once HORDE_LIBS . 'Horde/Prefs/UI.php';

$registry = &Registry::singleton();

/* Figure out which application we're setting preferences for. */
$app = Util::getFormData('app', 'horde');
$appbase = $registry->getParam('fileroot', $app);

/* Load $app's base environment. */
require_once $appbase . '/lib/base.php';

/* Load $app's preferences, if any. */
if (file_exists($appbase . '/config/prefs.php')) {
    require $appbase . '/config/prefs.php';
}

/* Load custom preference handlers for $app, if present. */
if (file_exists($appbase . '/lib/prefs.php')) {
    require_once $appbase . '/lib/prefs.php';
}

/* See if we have a preferences group set. */
$group = Util::getFormData('group');

if (Prefs_UI::handleForm($group)) {
    require $appbase . '/config/prefs.php';
}

$title = _("User Options");
require $registry->getParam('templates', $app) . '/common-header.inc';
if ($app == 'horde') {
    require $appbase . '/services/portal/navbar.php';
} else {
    if (isset($menu) && is_a($menu, 'Menu')) {
        /* App has a defined menu object and can return a menu
         * array. */
        $menu = $menu->getMenu();

        /* Use the default menu template to output this menu array. */
        require $registry->getParam('templates', 'horde') . '/menu/menu.inc';
    } else {
        /* App has no menu object so is probably using a menu
         * template. */
        call_user_func(array($app, 'menu'));
    }
}

/* Show the UI. */
Prefs_UI::generateUI($group);

require $registry->getParam('templates', 'horde') . '/common-footer.inc';
