<table border="0" cellpadding="2" cellspacing="1" class="control" width="100%">
<tr>
  <td class="item<?php echo $i++ % 2 ?>">
    <?php printf(_("Revision <b>%s</b>, <i>%s</i> (%s ago) by %s"), $r, $commitDate, $readableDate, $author) ?>
  </td>
</tr>

<?php if (!empty($branchName)): ?>
<tr>
  <td class="item<?php echo $i++ % 2 ?>">
    <?php echo _("Branch:") ?> <b><a href="<?php echo Chora::url('cvs', $where, array('onb' => $branchRev)) ?>"><?php echo $branchName ?></a></b>
  </td>
</tr>
<?php endif; ?>

<?php if (!empty($commitTags)): ?>
<tr>
  <td class="item<?php echo $i++ % 2 ?>">
    <?php echo _("CVS Tags:") ?> <b><?php echo $commitTags ?></b>
  </td>
</tr>
<?php endif; ?>

<?php if (!empty($branchPoints)): ?>
<tr>
  <td class="item<?php echo $i++ % 2 ?>">
    <?php echo _("Branch Point for:") ?> <b><?php echo $branchPoints ?></b>
  </td>
</tr>
<?php endif; ?>

<?php if (!empty($prevRevision)): ?>
<tr>
  <td class="item<?php echo $i++ % 2 ?>">
    <?php echo sprintf(_("Changed since <b>%s</b>"), $prevRevision) ?></b>: <?php echo $changedLines ?>
  </td>
</tr>
<?php endif; ?>

<tr valign="top">
  <td class="control">
    <b><?php echo _("Log:") ?></b>
 </td>
</tr>
<tr valign="top">
  <td class="text">
    <?php echo $log_print ?>
 </td>
</tr>
</table>

<br />

<table border="0" cellpadding="2" cellspacing="0" class="checkout" width="100%">
<tr><td class="checkout">

<?php
if ($pretty->getType() == 'text/plain') {
    echo '<pre>' . htmlspecialchars($pretty->render()) . '</pre>';
} else {
    echo $pretty->render();
}
?>

</td></tr></table>
