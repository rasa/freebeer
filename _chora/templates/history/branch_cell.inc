<td style="background-color: <?php echo $bg ?>" nowrap="nowrap" align="center">
<?php echo _("Branching to") ?>:<br />
<a href="<?php echo Chora::url('cvs', $where, array('onb' => $rev)); ?>">
 <span class="title"><?php echo $symname ?></span></a>
<br />
<i>(<?php echo sprintf(_("revision %s"), $rev) ?>)</i>
</td>
