 <tr>
  <td align="right" class="item1">
   <a name="<?php echo $lineno ?>" href="#<?php echo $lineno ?>"><?php echo $lineno ?></a>
  </td>
  <td class="annotate-author">
   <?php echo $author ?>
  </td>
  <td class="annotate-rev">
   <a href="<?php echo Chora::url('co', $where, array('r'=>$rev)) ?>"><?php echo $rev ?></a>
  </td>
  <td class="item<?php echo $style ?>">
   <tt><?php echo (trim($line) != '') ? $line : '&nbsp'; ?></tt>
  </td>
 </tr>
