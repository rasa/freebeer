<table border="0" cellpadding="2" cellspacing="0" width="100%"><tr><td class="menu">
<table border="0" width="100%">
<tr>

<?php
/* Horde navbar items. */
if (Auth::getAuth()) {
    if ($registry->getParam('status', 'horde') == 'active') {
        echo Menu::createItem(Horde::applicationUrl('services/portal/'), _("Home"), 'horde.gif');
    }

    if (Auth::isAdmin()) {
        echo Menu::createItem(Horde::applicationUrl('admin/'), _("Administration"), 'administration.gif');
        echo Menu::createItem(Horde::applicationUrl('admin/setup/'), _("Configuration"), 'administration.gif');
    }

    if (($conf['prefs']['driver'] != '') && ($conf['prefs']['driver'] != 'none')) {
        echo Menu::createItem(Horde::applicationUrl('services/prefs.php?app=horde'), _("Options"), 'prefs.gif');
    }
} else {
    echo Menu::createItem(Horde::url('login.php'), _("Log in"), 'login.gif');
}

/* Online Help. */
if ($help_link = Help::listLink('horde')) {
    echo Menu::createItem($help_link, _("Help"), 'manual.gif');
}
?>
<td width="100%">&nbsp;</td>
</tr></table></td></tr></table><br />
