<tr class="<?php echo $attic ? 'attic' : "item$dirrow" ?>">
<?php if (!Chora::isRestricted($fileName)): ?>
 <td nowrap="nowrap">
   <a href="<?php echo $url ?>">
<?php if ($attic) : ?>
   <?php echo Horde::img('deleted.gif', _("Deleted File"), 'width="16" height="16"') ?>&nbsp;
<?php else: ?>
   <img src="<?php echo $icon ?>" border="0" width="16" height="16" alt="<?php echo _("File") ?>" />&nbsp;
<?php endif; ?>
   <?php echo $name ?></a>
 </td>
 <td>
   &nbsp;<b><a href="<?php echo Chora::url('co', $fileName, array('r' => $head)) ?>"><?php echo $head ?></a></b>
 </td>
<?php else: ?>
 <td nowrap="nowrap">
<?php if ($attic) : ?>
   <?php echo Horde::img('deleted.gif', _("Deleted File"), 'width="16" height="16"') ?>&nbsp;
<?php else: ?>
   <img src="<?php echo $icon ?>" border="0" width="16" height="16" alt="<?php echo _("File") ?>" />&nbsp;
<?php endif; ?>
   <?php echo $name ?>
 </td>
 <td>
   &nbsp;<b><?php echo $head ?></b>
 </td>
<?php endif; ?>
 <td>
   &nbsp;<?php echo $author ?>
 </td>
 <td nowrap="nowrap">
   &nbsp;
   <i><?php echo $readableDate ?></i>
 </td>
 <td nowrap="nowrap">
   &nbsp;
   <?php if (!empty($log)) : ?>
   <?php echo htmlspecialchars($shortLog) ?>
   <?php endif; ?>
 </td>
</tr>
