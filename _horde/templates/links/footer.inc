<?php if ($registry->hasMethod($app . '/addLink') && !$this->_link_definitions[$link_type]['readonly']): ?>
<tr><td>
<a href="<?php echo Horde::url($registry->link($app . '/addLink', 
        array('url' => Horde::selfUrl(true, false, true), 
              'from_parameters' => serialize($link_data['from_params']), 
              'from_application' => $registry->getParam('provides'),
              'link_type' => $type . $reverse, 
             ))) ?>" class="widget"><?php echo sprintf(_("Add %s"), $link_definition['description']) ?></a>
</tr></td>
<?php endif; ?>
</table>
