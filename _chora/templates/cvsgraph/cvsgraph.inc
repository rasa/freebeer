<?php echo $map ?>
<table width="100%" cellspacing="0" cellpadding="1" border="0">
<tr>
  <td style="background-color:white;">
    <img usemap="#graphMap" alt="<?php echo htmlspecialchars($title) ?>" src="<?php echo $imgUrl ?>" border="0" />
  </td>
</tr>
<tr>
  <td class="smallheader">
    <?php echo _("Click on the revisions and branches to display the file.") ?><br />
    <?php echo _("Click on the links in between revisions to get a diff between those revisions.") ?>
  </td>
</tr>
</table>
