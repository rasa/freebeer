<table border="0" cellpadding="2" cellspacing="0" width="100%">
<tr>
    <td class="menu">

<table border="0" width="100%">
<tr>
<?php

/* Chora menu items */
echo Menu::createItem(Horde::applicationUrl('cvs.php'), $registry->getParam('name'), 'chora.gif', $registry->getParam('graphics', 'chora'));

if (($conf['prefs']['driver'] != '') && ($conf['prefs']['driver'] != 'none')) {
    echo Menu::createItem(Horde::url($registry->getParam('webroot', 'horde') . '/services/prefs.php?app=chora'), _("Options"), 'prefs.gif', $registry->getParam('graphics', 'horde'));
}

/* Additional site-specific menu items */
Menu::siteLinks();

/* Online Help */
if ($help_link = Help::listLink('chora')) {
    echo Menu::createItem($help_link, _("Help"), 'manual.gif', $registry->getParam('graphics', 'horde'));
}

/* Links to other Horde applications. */
Menu::appLinks();
?>
<td width="100%">&nbsp;</td><!-- spacer -->
</tr></table></td></tr></table>
<br />
