<table border="0" width="100%" cellpadding="2" cellspacing="0">
<tr>
  <td class="menu"><table border="0" width="100%">
  <tr valign="middle">
    <td class="menuheader" align="left" width="100%" nowrap="nowrap">
      &nbsp;<b><?php echo isset($title) ? $title : _("Horde Administration") ?></b>&nbsp;&nbsp;
    </td>
<?php
echo Menu::createItem(Horde::applicationUrl('admin/setup/'), _("Configuration"), 'config.gif');
foreach ($registry->listApps() as $app) {
    if ($registry->hasMethod('admin_list', $app)) {
        $list = $registry->callByPackage($app, 'admin_list');
        if (is_a($list, 'PEAR_Error')) {
            continue;
        }

        foreach ($list as $vals) {
            if ($app != 'horde') {
                $name = $registry->getParam('name', $app);
                if (!empty($vals['name'])) {
                    $name .= ' ' . $vals['name'];
                }
            } else {
                $name = $vals['name'];
            }
            $img = isset($vals['icon']) ? $registry->getParam('graphics', $app) . '/' . $vals['icon'] : $registry->getParam('icon', $app);
            echo Menu::createItem(Horde::url($registry->applicationWebPath($vals['link'], $app)), $name, $img, '');
        }
    }
}
?>
  </tr>
  </table></td>
</tr>
</table>

<br />
