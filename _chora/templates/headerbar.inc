<table width="100%" cellspacing="1" cellpadding="3" border="0">

<tr>
  <td class="header"><?php echo $title ?></td>
</tr>

<tr>
  <td class="item">
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
      <tr>
        <td>
          <?php echo _("Location:") ?>
          <b>[ <a href="<?php echo Chora::url('cvs') ?>"><?php echo $conf['options']['sourceRootName'] ?></a> ]
          <?php echo Chora::whereMenu() ?></b>

<?php if (isset($onb) && $onb): ?>
          &nbsp; &nbsp; <i>(<?php echo _("Tracking Branch") ?> <b><?php echo $fl->branches[$onb] ?></b>)</i>
<?php 
endif;
if ($where == '' && sizeof($sourceroots) > 1): 
?>
          &nbsp; &nbsp;
          (<?php echo Chora::repositories() ?>)
<?php endif; ?>
        </td>
<?php if (!empty($extraLink)): ?>
        <td align="right"><?php echo $extraLink ?></td>
<?php endif; ?>
      </tr>
    </table>
  </td>
</tr>

<?php if ($where == '' && @is_file($conf['paths']['introText'])): ?>
<tr class="text">
  <td><?php @readfile($conf['paths']['introText']) ?></td>
</tr>
<?php endif; ?>

</table>
<br />
