<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr><td class="item">
<table width="100%" border="0" cellspacing="1" cellpadding="2">
 <tr class="item">
  <th class="<?php echo $acts['sbt'] == VC_SORT_NAME ? 'selected' : '' ?>" align="left">
    <a class="widget" href="<?php echo $url['name'] ?>"><?php echo Horde::img($acts['ord'] ? 'za.gif' :
'az.gif', _("Sort Order"), 'width="9" height="9"', $registry->getParam('graphics', 'horde')) ?></a>&nbsp;
    <a class="widget" href="<?php echo $url["name"] ?>"><?php echo _("File") ?></a>
  </th>
  <th class="<?php echo $acts['sbt'] == VC_SORT_REV ? 'selected' : '' ?>" align="left" nowrap="nowrap">
    <a class="widget" href="<?php echo $url['rev'] ?>"><?php echo Horde::img($acts['ord'] ? 'za.gif' :
'az.gif', _("Sort Order"), 'width="9" height="9"', $registry->getParam('graphics', 'horde')) ?></a>&nbsp;
    <a class="widget" href="<?php echo $url["rev"] ?>"><?php echo _("Rev") ?></a>
  </th>
  <th class="<?php echo $acts['sbt'] == VC_SORT_AUTHOR ? 'selected' : '' ?>" align="left" nowrap="nowrap">
    <a class="widget" href="<?php echo $url['author'] ?>"><?php echo Horde::img($acts['ord'] ? 'za.gif' :
'az.gif', _("Sort Order"), 'width="9" height="9"', $registry->getParam('graphics', 'horde')) ?></a>&nbsp;
    <a class="widget" href="<?php echo $url['author'] ?>"><?php echo _("Author") ?></a>
  </th>
  <th class="<?php echo $acts['sbt'] == VC_SORT_AGE ? 'selected' : '' ?>" align="left" nowrap="nowrap">
    <a class="widget" href="<?php echo $url['age'] ?>"><?php echo Horde::img($acts['ord'] ? 'za.gif' :
'az.gif', _("Sort Order"), 'width="9" height="9"', $registry->getParam('graphics', 'horde')) ?></a>&nbsp;
    <a class="widget" href="<?php echo $url['age'] ?>"><?php echo _("Date") ?></a>
  </th>
  <th align="left"><a class="widget"><?php echo _("Last Log") ?></a></th>
 </tr>
