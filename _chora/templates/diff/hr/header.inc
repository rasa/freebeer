<br />
<table border="0" cellpadding="2" cellspacing="0" class="checkout" width="100%">
<tr>
 <td class="diff-log">
  <b><?php echo sprintf(_("Last Log Message for rev %s:"), $r2) ?></b><br /><br />
  <?php echo $log_print ?>
 </td>
</tr>
</table>
<br />
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr class="hr-diff-back">
 <th width="50%"><a href="<?php echo Chora::url('co', $where, array('r' => $r1)) ?>"><?php echo sprintf(_("version %s"), $r1) ?></a></th>
 <th width="50%"><a href="<?php echo Chora::url('co', $where, array('r' => $r2)) ?>"><?php echo sprintf(_("version %s"), $r2) ?></a></th>
</tr>
