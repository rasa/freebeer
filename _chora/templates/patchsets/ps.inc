<a name="ps<?php echo $id ?>" />
<table width="100%" cellspacing="0" cellpadding="3" class="diff-back">
<tr class="diff-back"><td width="100%"> 
<table width="100%" cellspacing="0" cellpadding="4" class="diff-back">

<tr class="item"><td align="left"><span class="title"><?php printf(_("PatchSet %s</span> by %s"), $id, $author) ?></td><td align="right">
<?php echo $allDiffsLink; ?></a>
</td></tr>

<tr valign="top" class="diff-back">
<td width="35%">
<?php echo $commitDate ?> <i>(<?php echo sprintf(_("%s ago"), $readableDate) ?>)</i><br />
<?php if (!empty($patchset['branch'])): ?>
<?php echo _("Branch:") ?> <b><?php echo htmlspecialchars($patchset['branch']) ?></b>
<?php endif; ?>
<?php if (!empty($patchset['tags'])): ?>
<?php echo _("CVS Tags:") ?> <b><?php echo htmlspecialchars($patchset['tags']) ?></b>
<?php endif; ?>

<br />
<?php if (count($files)): ?><br /><b><?php echo _("Files Changed:") ?></b><table border="0" width="100%" cellpadding="2" cellspacing="0">
<?php $i = 0; foreach ($files as $file): ?>
<tr><td class="item<?php echo $i++ % 2 ?>"><?php echo $file['file'] . ': ' . $file['from'] . ' -> ' . $file['to'] . ' ' . $file['diff'] ?></td></tr>
<?php endforeach; ?>
</table>
<?php endif; ?>

</td><td class="diff-log" width="65%">
<?php echo $logMessage ?>
</td></tr>
</table>
</td></tr>
</table>
