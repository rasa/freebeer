<a name="rev<?php echo $rev ?>" />
<table width="100%" cellspacing="0" cellpadding="3" class="item">
<tr>
  <td align="left" class="<?php echo $bgclass ?>">
    <a href="<?php echo $textURL ?>" class="title"><?php echo $rev ?></a> <?php echo sprintf(_("by %s"), $author) ?>
  </td>
  <td align="right" class="<?php echo $bgclass ?>">
    <a href="<?php echo Chora::url('annotate', $where, array('rev' => $rev)); ?>"><?php echo _("Annotate") ?></a>
  | <a href="<?php echo Chora::url('co', $where, array('r' => $rev)); ?>"><?php echo _("View") ?></a>
  | <a href="<?php echo Chora::url('co', $where, array('r' => $rev, 'p' => 1)); ?>"><?php echo _("Download") ?></a>
<?php if ($manyRevisions): ?>
<?php if ($r1 === $rev): ?>
| <b><a href="<?php echo Chora::url('cvs', $where) ?>"><?php echo _("Deselect") ?></a></b>
<?php else: ?>
| <a href="<?php echo $selCvsURL ?>"><?php echo _("Select for Diff") ?></a>
<?php endif; ?>
<?php endif; ?>
  </td>
</tr>
<tr valign="top">
  <td width="35%">
    <table border="0" width="100%" class="box" cellpadding="2" cellspacing="0">
<?php
echo '<tr><td>' . $commitDate . ' <i>(' . sprintf(_("%s ago"), $readableDate) . ')</i></td></tr>';
if (!empty($branchName)) {
    echo '<tr><td>' . _("Branch:") . ' <b>' . Horde::link(Chora::url('cvs', $where, array('onb' => $branchRev)), $branchName) . $branchName . '</a></b></td></tr>';
}
if (!empty($branchPoints)) {
    echo '<tr><td>' . _("Branch Point for:") . ' <b>' . $branchPoints . '</b></td></tr>';
}
if (!empty($commitTags)) {
    echo '<tr><td>' . _("Tags:") . ' <b>' . $commitTags . '</b></td></tr>';
}
if (!empty($prevRevision)) {
    echo '<tr><td>' . sprintf(_("Changed since <b>%s</b>"), $prevRevision) . '</b>: ' . $changedLines . '</td></tr>';
}
if (!empty($prevRevision)) { ?>
<tr><td>
<a href="<?php echo $uniDiffURL ?>"><?php echo sprintf(_("Diffs to version %s"), $prevRevision) ?></a>
(<a href="<?php echo $coloredDiffURL ?>"><?php echo _("Colored") ?></a> |
<a href="<?php echo $longDiffURL ?>"><?php echo _("Long") ?></a> |
<a href="<?php echo $nowsDiffURL ?>"><?php echo _("NoWhitespaceChanges") ?></a>)
</td></tr>
<?php }

if ($manyRevisions && !empty($r1) && ($r1 != $prevRevision) && ($r1 != $rev)) { ?>
<tr><td>
<a href="<?php echo $selUniDiffURL ?>"><?php echo sprintf(_("Diffs to <b>%s</b>"), $r1) ?></a>
(<a href="<?php echo $selColoredDiffURL ?>"><?php echo _("Colored") ?></a> |
<a href="<?php echo $selLongDiffURL ?>"><?php echo _("Long") ?></a> |
<a href="<?php echo $selNowsDiffURL ?>"><?php echo _("NoWhitespaceChanges") ?></a>)
</td></tr>
<?php } ?>
      </table>
    </td>
    <td width="65%" height="100%" valign="top">
      <table border="0" height="100%" width="100%" class="box" cellpadding="2" cellspacing="0"><tr><td class="text" valign="top">
        <?php echo $logMessage ?>
      </td></tr></table>
    </td>
  </tr>
  <tr>
    <td style="font-size:5px;">&nbsp;</td>
  </tr>
</table>
