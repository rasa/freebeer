</table>
<br />
<table border="0" cellspacing="0" cellpadding="0"> 
 <tr>
   <th class="hr-diff-back" colspan="3"><?php echo _("Legend:") ?></th>
 </tr>
 <tr>
   <td>
   <table border="0" cellspacing="0" cellpadding="2">
     <tr>
      <td align="center" class="hr-diff-remove"><?php echo sprintf(_("Removed in v.%s"), $r1) ?></td>
      <td class="hr-diff-grey">&nbsp;</td>
     </tr>
     <tr class="hr-diff-change">
      <td align="center" colspan="2"><?php echo _("changed lines") ?></td>
     </tr>
     <tr>
      <td class="hr-diff-grey">&nbsp;</td>
      <td align="center" class="hr-diff-add"><?php echo sprintf(_("Added in v.%s"), $r2) ?></td>
     </tr>
   </table>
  </td>
 </tr>
</table>
