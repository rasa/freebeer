<tr><td class="header"><?php echo $link_definition['description']; if ($this->_link_definitions[$link_type]['readonly']) { echo _(" (Read only)"); } ?></td></tr>
<?php
    foreach ($this->listLinks($link_data) as $id => $link):  ?>
<tr><td class="item"><?php 
    $this->display($link_type, $link) ?></td></tr>
<?php endforeach; ?>
