<?php
/**
 * $Horde: horde/admin/perms/edit.php,v 1.24 2004/02/26 13:02:04 eraserhd Exp $
 *
 * Copyright 1999, 2000, 2001 Chuck Hagenbuch <chuck@horde.org>
 *
 * See the enclosed file COPYING for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 */

define('HORDE_BASE', dirname(__FILE__) . '/../..');
require_once HORDE_BASE . '/lib/base.php';
require_once HORDE_LIBS . 'Horde/Menu.php';
require_once HORDE_LIBS . 'Horde/Group.php';
require_once HORDE_LIBS . 'Horde/Tree.php';
require_once HORDE_LIBS . 'Horde/Variables.php';

/* Form libraries. */
require_once HORDE_LIBS . 'Horde/Form.php';
require_once HORDE_LIBS . 'Horde/Form/Renderer.php';

if (!Auth::isAdmin()) {
    Horde::authenticationFailureRedirect();
}

$groups = &Group::singleton();
$auth = &Auth::singleton($conf['auth']['driver']);

/* Set up the form variables. */
$vars = &Variables::getDefaultVariables();
$cid = $vars->get('cid');
$category = $vars->get('category');
$permission = &$perms->getPermissionById($cid);

/* See if we need to (and are supposed to) autocreate the
 * permission. */
if ($category !== null) {
    $permission = &$perms->getPermission($category);
    if (is_a($permission, 'PEAR_Error') && Util::getFormData('autocreate')) {
        $parent = $vars->get('parent');
        $permission = &$perms->newPermission($category);
        $result = $perms->addPermission($permission, $parent);
        if (!is_a($result, 'PEAR_Error')) {
            $form = 'edit.inc';
        }
    }
} else {
    $permission = &$perms->getPermissionById($cid);
}

/* If the permission fetched is an error return to the permissions
 * list. */
if (is_a($permission, 'PEAR_Error')) {
    $notification->push(_("Attempt to edit a non-existent permission."), 'horde.error');
    $url = Horde::applicationUrl('admin/perms/index.php', true);
    header('Location: ' . $url);
    exit;
}

$form = &Horde_Form::singleton('', $vars);

$form->setButtons(_("Update"), true);
$form->addHidden('', 'cid', 'text', false);

/* Set up the columns for the permissions matrix. */
$cols = Perms::getPermsArray();

/* Default permissions. */
$perm_val = $permission->getDefaultPermissions();

/* Define a single matrix row for default perms. */
$matrix = array();
$matrix[0] = Perms::integerToArray($perm_val);
$form->setSection('default', Horde::img('perms.gif') . ' ' . _("Default Permissions"), false);
$form->addVariable(_("Default permissions"), 'default', 'matrix', false, false, null, array($cols, array(0 => ''), $matrix));

/* Guest permissions. */
$perm_val = $permission->getGuestPermissions();

/* Define a single matrix row for guest perms. */
$matrix = array();
$matrix[0] = Perms::integerToArray($perm_val);
$form->setSection('guest', Horde::img('guest.gif') . ' ' . _("Guest Permissions"), false);
$form->addVariable(_("Guest permissions"), 'guest', 'matrix', false, false, null, array($cols, array(0 => ''), $matrix));

/* Object creator permissions. */
$perm_val = $permission->getCreatorPermissions();

/* Define a single matrix row for creator perms. */
$matrix = array();
$matrix[0] = Perms::integerToArray($perm_val);
$form->setSection('creator', Horde::img('user.gif') . ' ' . _("Creator Permissions"), false);
$form->addVariable(_("Object creator permissions"), 'creator', 'matrix', false, false, null, array($cols, array(0 => ''), $matrix));

/* Users permissions. */
$perm_val = $permission->getUserPermissions();
$form->setSection('users', Horde::img('user.gif') . ' ' . _("Users"), false);
if ($auth->hasCapability('list')) {
    /* The auth driver has list capabilities so set up an array which
     * the matrix field type will recognise to set up an enum box for
     * adding new users to the permissions matrix. */
    $new_users = array();
    $user_list = $auth->listUsers();
    foreach ($user_list as $user) {
        if (!isset($perm_val[$user])) {
            $new_users[$user] = $user;
        }
    }
} else {
    /* No list capabilities, setting to true so that the matrix field
     * type will offer a text input box for adding new users. */
    $new_users = true;
}

/* Set up the matrix array, breaking up each permission integer into
 * an array.  The keys of this array will be the row headers. */
$rows = array();
$matrix = array();
foreach ($perm_val as $u_id => $u_perms) {
    $rows[$u_id] = $u_id;
    $matrix[$u_id] = Perms::integerToArray($u_perms);
}
$form->addVariable(_("User permissions"), 'u', 'matrix', false, false, null, array($cols, $rows, $matrix, $new_users));

/* Groups permissions. */
$perm_val = $permission->getGroupPermissions();
$form->setSection('groups', Horde::img('group.gif') . ' ' . _("Groups"), false);
$group_list = $groups->listGroups();
if (!empty($group_list)) {
    /* There is an available list of groups so set up an array which
     * the matrix field type will recognise to set up an enum box for
     * adding new groups to the permissions matrix. */
    $new_groups = array();
    foreach ($group_list as $groupId => $group) {
        if (!isset($perm_val[$groupId])) {
            $new_groups[$groupId] = $group;
        }
    }
} else {
    /* Do not offer a text box to add new groups. */
    $new_groups = false;
}

/* Set up the matrix array, break up each permission integer into an
 * array. The keys of this array will be the row headers. */
$rows = array();
$matrix = array();
foreach ($perm_val as $g_id => $g_perms) {
    $rows[$g_id] = isset($group_list[$g_id]) ? $group_list[$g_id] : $g_id;
    $matrix[$g_id] = Perms::integerToArray($g_perms);
}
$form->addVariable(_("Group permissions"), 'g', 'matrix', false, false, null, array($cols, $rows, $matrix, $new_groups));

/* Set form title. */
$form->setTitle(Horde::img('perms.gif') . ' ' . sprintf(_("Edit permissions for '%s'"), $perms->getTitle($permission->getName())));

if ($form->validate($vars)) {
    $form->getInfo($vars, $info);

    /* Collapse the array for default/guest/creator. */
    $info['default'] = isset($info['default'][0]) ? $info['default'][0] : null;
    $info['guest']   = isset($info['guest'][0]) ? $info['guest'][0] : null;
    $info['creator'] = isset($info['creator'][0]) ? $info['creator'][0] : null;

    /* Update and save the permissions. */
    $permission->updatePermissions($info);
    $permission->save();
    $notification->push(sprintf(_("Updated '%s'."), $permission->getShortName()), 'horde.success');
    $url = Horde::applicationUrl('admin/perms/edit.php', true);
    $url = Util::addParameter($url, 'cid', $permission->getId());
    header('Location: ' . $url);
    exit;
}

$title = _("Permissions Administration");
require HORDE_TEMPLATES . '/common-header.inc';
require HORDE_TEMPLATES . '/admin/common-header.inc';
$notification->notify(array('listeners' => 'status'));

/* Render the form. */
$renderer = &new Horde_Form_Renderer();
$form->renderActive($renderer, $vars, 'edit.php', 'post');

echo '<br />';

require_once HORDE_LIBS . 'Horde/Perms/UI.php';
$ui = &new Perms_UI($perms);
$ui->renderTree($cid);

require HORDE_TEMPLATES . '/common-footer.inc';
