<table border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
  <th class="control" align="right"><?php echo _("Author") ?>&nbsp;&nbsp;</th>
  <th align="left" class="control"><?php echo _("Commits") ?></th>
</tr>
<?php

$most = max($author_stats);
$factor = $most / 400;
$i = 0;
foreach ($author_stats as $author => $commits) {
    $width = (int)($commits / $factor);
    $i = ($i + 1) % 2;
?>
<tr>
  <td class="item<?php echo $i ?>" align="right" valign="top"><?php echo $author ?>&nbsp;&nbsp;</td>
  <td class="item<?php echo $i ?>" valign="top"><?php echo Horde::img('pixel.php?c=#bbcbff', '', 'style="border:1px solid black;" width="' . $width . '" height="10"', $registry->getParam('webroot', 'horde') . '/services/images') . '&nbsp;&nbsp;(' . $commits . ')</td>' ?>
</tr>

<?php } ?>

</table>
