<td style="background-color: <?php echo $bg ?>" nowrap="nowrap">
 <a name="rev<?php echo $rev ?>" />
 <a href="<?php echo Chora::url('cvs',$where, array('r'=>$rev), "rev$rev") ?>">
 <span class="title"><?php echo $rev ?></span></a> <?php echo sprintf(_("by %s"), $author) ?>
 <br />
 <i><?php echo $date ?></i>
<?php if (!empty($lines)): ?>
 <br /><?php echo sprintf(_("Changed: %s"), $lines) ?>
<?php endif ?>
</td>
