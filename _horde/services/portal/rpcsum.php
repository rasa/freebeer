<?php
/**
 * $Horde: horde/services/portal/rpcsum.php,v 2.19 2004/02/23 01:52:55 jon Exp $
 *
 * Copyright 2001-2004 Jan Schneider <jan@horde.org>
 *
 * See the enclosed file COPYING for license information (LGPL).  If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 */

function _returnToPrefs()
{
    $url = Horde::applicationUrl('services/prefs.php', true);
    header('Location: ' . Util::addParameter($url, 'group', 'display'));
    exit;
}

define('RPC_EDIT',   1);
define('RPC_SAVE',   2);
define('RPC_DELETE', 3);
define('HORDE_BASE', dirname(__FILE__) . '/../..');
require_once HORDE_BASE . '/lib/base.php';
require_once HORDE_LIBS . 'Horde/RPC.php';

if (!Auth::isAuthenticated()) {
    Horde::authenticationFailureRedirect();
}

$rpc_servers = @unserialize($prefs->getValue('remote_summaries'));
if (!is_array($rpc_servers)) {
    $rpc_servers = array();
}

$actionID = Util::getFormData('actionID');

// Handle clients without javascript.
if (is_null($actionID)) {
    if (Util::getPost('edit')) {
        $actionID = RPC_EDIT;
    } elseif (Util::getPost('save')) {
        $actionID = RPC_SAVE;
    } elseif (Util::getPost('delete')) {
        $actionID = RPC_DELETE;
    } elseif (Util::getPost('back')) {
        _returnToPrefs();
    }
}

/* Run through the action handlers */
switch ($actionID) {
 case RPC_SAVE:
    if (($to_edit = Util::getFormData('edit_server')) == null) {
        $to_edit = count($rpc_servers);
        $rpc_servers[] = array();
    }
    $rpc_servers[$to_edit]['url']    = Util::getFormData('url');
    $rpc_servers[$to_edit]['user']   = Util::getFormData('user');
    $rpc_servers[$to_edit]['passwd'] = Util::getFormData('passwd');
    $prefs->setValue('remote_summaries', serialize($rpc_servers));
    $prefs->store();
    $notification->push(sprintf(_("The server \"%s\" has been saved."), $rpc_servers[$to_edit]['url']), 'horde.success');
    break;

 case RPC_DELETE:
    $to_delete = Util::getFormData('server');
    if ($to_delete != -1) {
        $deleted_server = $rpc_servers[$to_delete]['url'];
        $server_list = array();
        for ($i = 0; $i < count($rpc_servers); $i++) {
            if ($i == $to_delete) {
                continue;
            }
            $server_list[] = $rpc_servers[$i];
        }
        $prefs->setValue('remote_summaries', serialize($server_list));
        $chosenColumns = explode(';', $prefs->getValue('show_summaries'));
        if ($chosenColumns != array('')) {
            $newColumns = array();
            foreach ($chosenColumns as $chosenColumn) {
                $chosenColumn = explode(',', $chosenColumn);
                $remote = explode('|', $chosenColumn[0]);
                if (count($remote) != 3 || $remote[2] == $deleted_server) {
                    $newColumns[] = implode(',', $chosenColumn);
                }
            }
            $prefs->setValue('show_summaries', implode(';', $newColumns));
        }
        $prefs->store();
        $rpc_servers = $server_list;
        $notification->push(sprintf(_("The server \"%s\" has been deleted."), $deleted_server), 'horde.success');
    } else {
        $notification->push(_("You must select an server to be deleted."), 'horde.warning');
    }
    break;

}

$title = _("Remote Servers");
require HORDE_TEMPLATES . '/common-header.inc';
require HORDE_BASE . '/services/portal/navbar.php';
require HORDE_TEMPLATES . '/rpcsum/javascript.inc';
require HORDE_TEMPLATES . '/rpcsum/manage.inc';
require HORDE_TEMPLATES . '/common-footer.inc';
