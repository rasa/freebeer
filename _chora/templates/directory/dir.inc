<tr class="item<?php echo $dirrow ?>">
 <td>
<?php if (!Chora::isRestricted("$where/$currentDir")): ?>
   <a href="<?php echo $url ?>">
   <?php echo Horde::img('folder.gif', _("Directory"), 'width="16" height="16"') ?>&nbsp;<?php echo $currDir ?>/</a>
<?php else: ?>
   <?php echo Horde::img('folder.gif', _("Directory"), 'width="16" height="16"') ?>&nbsp;<?php echo $currDir ?>/
<?php endif; ?>
 </td>
 <td> &nbsp; </td>
 <td> &nbsp; </td>
 <td> &nbsp; </td>
 <td> &nbsp; </td>
</tr>
