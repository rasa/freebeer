<a name="diff"></a>
<br />
<table width="100%" cellspacing="0" cellpadding="4">
<tr><td class="control">
<?php echo _("This form allows you to request diffs between any two revisions of a file.  You may select a symbolic revision name using the selection box or you may type in a numeric name using the type-in text box.") ?>
</td></tr>
<tr class="item"><td>
<form method="get" action="<?php echo Chora::url('diff',$where) ?>">
<?php echo Chora::generateHiddens() ?>
<input type="hidden" name="f" value="<?php echo Util::getFormData('f') ?>" />
<table cellspacing="0" cellpadding="1" border="0">
<tr>
<td align="right">
<?php echo _("Retrieve diffs between:") ?>
</td>
<td>
<select name="r1">
  <option value="0" selected="selected"><?php echo _("Use Text Field") ?></option>
  <?php echo $sel ?>
</select>
<input type="text" size="12" name="tr1" value="<?php echo $diffValueLeft ?>" />
</td>
<td><?php echo _("Type:") ?></td>
<td>
<select name="ty">
 <option value="h" selected="selected"><?php echo _("Human Readable") ?></option>
 <option value="u"><?php echo _("Unified") ?></option>
 <option value="c"><?php echo _("Context") ?></option>
 <option value="s"><?php echo _("Side-by-Side") ?></option>
 <option value="e"><?php echo _("Ed Script") ?></option>
</select> &nbsp;
</td>
</tr>
<tr>
<td align="right"><?php echo _("and:") ?></td>
<td>
<select name="r2">
  <option value="0" selected="selected"><?php echo _("Use Text Field") ?></option>
  <?php echo $sel ?>
</select>
<input type="text" size="12" name="tr2" value="<?php echo $diffValueRight ?>" />
</td>
<td>&nbsp;</td>
<td><input class="button" type="submit" value="<?php echo _("Get Diffs") ?>" /></td>
</tr>
</table>
</form>
</td></tr>
<?php if (!empty($selAllBranches)): ?>
<tr class="item"><td>
<form method="get" action="<?php echo Chora::url('cvs', $where) ?>">
<?php echo Chora::generateHiddens() ?>
<table cellspacing="0" cellpadding="1" border="0">
<tr><td><?php echo _("View revisions on:") ?></td>
<td>
<select name="onb">
  <option value="0" <?php if (!isset($onb) || !$onb) echo ' selected="selected"' ?>><?php echo _("All Branches") ?></option>
  <?php echo $selAllBranches ?>
</select> &nbsp;
<input class="button" type="submit" value="<?php echo _("View Branch") ?>" />
</td></tr>
</table>
</form>
</td></tr>
<?php endif; ?>
</table>
