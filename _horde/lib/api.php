<?php
/**
 * Horde external API interface.
 *
 * This file defines Horde's external API interface. Other
 * applications can interact with Horde through this API.
 *
 * $Horde: horde/lib/api.php,v 1.27 2004/02/14 02:40:35 chuck Exp $
 *
 * @package Horde
 */

$_services['admin_list'] = array(true);

/**
 * General API for deleting Horde_Links
 */
$_services['deleteLink'] = array(
    'link' => '%application%/services/links/delete.php?' .
    'link_data=|link_data|' . 
    ini_get('arg_separator.output') . 'return_url=|url|'
);

$_services['listApps'] = array(
    'args' => array('filter' => 'array'),
    'type' => 'array'
);

$_services['listAPIs'] = array(
    'args' => array(),
    'type' => 'array'
);

$_services['block'] = array(
    'args' => array('type' => 'string', 'params' => 'array'),
    'type' => 'array'
);

$_services['defineBlock'] = array(
    'args' => array('type' => 'string'),
    'type' => 'string'
);

$_services['blocks'] = array(
    'args' => array(),
    'type' => 'array'
);


function &_horde_block($block, $params = array())
{
    @define('HORDE_BASE', dirname(__FILE__) . '/..');
    require_once HORDE_BASE . '/lib/base.php';

    if (is_a(($blockClass = _horde_defineBlock($block)), 'PEAR_Error')) {
        return $blockClass;
    }

    return $ret = &new $blockClass($params);
}

function _horde_defineBlock($block)
{
    $blockClass = 'Horde_Block_' . $block;
    include_once HORDE_BASE . '/lib/Block/' . $block . '.php';
    if (class_exists($blockClass)) {
        return $blockClass;
    } else {
        return PEAR::raiseError(sprintf(_("%s not found."), $blockClass));
    }
}

function _horde_blocks()
{
    global $registry;
    return isset($registry->applets) ? $registry->applets : array();
}

function _horde_admin_list()
{
    return array('users' => array(
                     'link' => '%application%/admin/user.php',
                     'name' => _("Users"),
                     'icon' => 'user.gif'),
                 'groups' => array(
                     'link' => '%application%/admin/groups.php',
                     'name' => _("Groups"),
                     'icon' => 'group.gif'),
                 'perms' => array(
                     'link' => '%application%/admin/perms/index.php',
                     'name' => _("Permissions"),
                     'icon' => 'perms.gif'),
                 'phpshell' => array(
                     'link' => '%application%/admin/phpshell.php',
                     'name' => _("PHP Shell"),
                     'icon' => 'shell.gif'),
                 'sqlshell' => array(
                     'link' => '%application%/admin/sqlshell.php',
                     'name' => _("SQL Shell"),
                     'icon' => 'sql.gif'),
                 'cmdshell' => array(
                     'link' => '%application%/admin/cmdshell.php',
                     'name' => _("Command Shell"),
                     'icon' => 'shell.gif'),
                 );
}

function _horde_listApps($filter = null)
{
    return $GLOBALS['registry']->listApps($filter);
}

function _horde_listAPIs()
{
    return $GLOBALS['registry']->listAPIs();
}
