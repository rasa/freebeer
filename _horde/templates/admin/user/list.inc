<table border="0" cellspacing="0" cellpadding="1" width="100%">
<tr>
  <td align="left" class="header">
    <b><?php echo _("Users in the system:") ?></b>
  </td>
</tr>
<tr><td class="control"><table border="0" width="100%" cellpadding="1" cellspacing="0">
<?php $_i = 0; $remove = $auth->hasCapability('remove'); foreach ($users as $user): ?>
<tr class="item<?php echo $_i++%2 ?>">
<?php if ($remove): ?>
  <td width="1%"><?php echo Horde::link(Horde::applicationUrl('admin/user.php?form=remove_f&user_name=' . $user), _("Delete")) . Horde::img('delete.gif', _("Delete")) ?></a></td>
<?php endif; ?>
  <td width="1%"><?php echo Horde::link(Horde::applicationUrl('admin/user.php?form=update_f&user_name=' . $user . '&do_update=1'), _("Update")) .  Horde::img('edit.gif', _("Update")) ?></a></td>
  <td align="left"><?php echo $user ?></td>
</tr>
<?php endforeach; ?>
</table></td></tr>
</table>
