<?php
/**
 * $Horde: horde/admin/setup/config.php,v 1.8 2004/02/26 13:02:04 eraserhd Exp $
 *
 * Copyright 1999-2004 Charles J. Hagenbuch <chuck@horde.org>
 * Copyright 1999-2004 Jon Parise <jon@horde.org>
 *
 * See the enclosed file COPYING for license information (LGPL).  If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 */

define('HORDE_BASE', dirname(__FILE__) . '/../..');
require_once HORDE_BASE . '/lib/base.php';
require_once HORDE_LIBS . 'Horde/Menu.php';
require_once HORDE_LIBS . 'Horde/Form.php';
require_once HORDE_LIBS . 'Horde/Form/Action.php';
require_once HORDE_LIBS . 'Horde/Form/Renderer.php';
require_once HORDE_LIBS . 'Horde/Config.php';
require_once HORDE_LIBS . 'Horde/Variables.php';

if (!Auth::isAdmin()) {
    Horde::fatal('Forbidden.', __FILE__, __LINE__);
}

if (!Util::extensionExists('domxml')) {
    Horde::fatal(PEAR::raiseError('You need the domxml PHP extension to use the configuration tool.'), __FILE__, __LINE__);
}

$app = Util::getFormData('app');
$appname = $registry->getParam('name', $app);
$title = sprintf(_("%s Configuration"), $appname);

if ($app === null &&
    in_array($app, $registry->listApps(array('inactive', 'hidden', 'notoolbar', 'active', 'admin')))) {
    $notification->push(_("Invalid application."), 'horde.error');
    $url = Horde::applicationUrl('admin/setup/index.php', true);
    header('Location: ' . $url);
    exit;
}

$vars = Variables::getDefaultVariables();
$form = &new ConfigForm($vars, $app);
$form->setTitle($title);
$form->setButtons(sprintf(_("Generate %s Configuration"), $appname));

$php = '';
if ($form->validate($vars)) {
    $config = &new Horde_Config($app);
    $php = $config->generatePHPConfig($vars);
    $path = $registry->getParam('fileroot', $app) . '/config';
    if ($fp = @fopen($path . '/conf.php', 'w')) {
        /* Can write, so output to file. */
        fwrite($fp, String::convertCharset($php, NLS::getCharset(), 'iso-8859-1'));
        fclose($fp);
        $notification->push(sprintf(_("Successfully wrote %s"), $path . '/conf.php'), 'horde.success');
    } else {
        /* Can not write. */
        $notification->push(sprintf(_("Writing not possible. You can either use the available upgrade script or copy manually the code below to %s."), $path . '/conf.php'), 'horde.warning');
        /* Save to session. */
        $_SESSION['_config'][$app] = $php;
    }
}

/* Render the configuration form. */
require_once HORDE_LIBS . 'Horde/UI/VarRenderer.php';
$renderer = &new Horde_Form_Renderer();
$renderer->setAttrColumnWidth('50%');
$form = Util::bufferOutput(array($form, 'renderActive'), $renderer, $vars, 'config.php', 'post');


/* Set up the template. */
require_once HORDE_LIBS . 'Horde/Template.php';
$template = &new Horde_Template();
$menu = &new Menu(true, true, true);
$template->set('menu', $menu->getMenu());
$template->set('notify', Util::bufferOutput(array($notification, 'notify')));
$template->set('php', htmlspecialchars($php), true);
$template->set('form', $form);

require HORDE_TEMPLATES . '/common-header.inc';
echo $template->fetch(HORDE_TEMPLATES . '/admin/setup/config.html');
require HORDE_TEMPLATES . '/common-footer.inc';
