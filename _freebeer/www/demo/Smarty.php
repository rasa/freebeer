<?php

// $CVSHeader: _freebeer/www/demo/Smarty.php,v 1.2 2004/03/07 17:51:33 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once './_demo.php';

require_once FREEBEER_BASE . '/lib/Smarty/Smarty.php';

$html_header = html_header_demo('fbSmarty Class (Smarty Template System)');

$smarty = &new fbSmarty();

// this breaks lots of stuff:
//$smarty->caching = true;

$smarty->assign('html_header', $html_header);

$smarty->assign('time', strftime('%c'));

$checkbox_values = array('values value1', 'values value2', 'values value3');

$smarty->assign('checkbox_values', $checkbox_values);

$checkbox_output = array('output value1', 'output value2', 'output value3');

$smarty->assign('checkbox_output', $checkbox_output);

$checkbox_options = array(
	'options key1'	=> 'options value1',
	'options key2'	=> 'options value2',
	'options key3'	=> 'options value3',
);

$smarty->assign('checkbox_options', $checkbox_options);

$checkbox_checked = 'options value2';

$smarty->assign('checkbox_checked', $checkbox_checked);

$table_loop = array(1, 2, 3, 4, 5, 6, 7, 8, 9);

$smarty->assign('table_loop', $table_loop);

$template = 'smarty.html';

$smarty->display($template);

?>
