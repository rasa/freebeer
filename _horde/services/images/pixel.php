<?php
/**
 * $Horde: horde/services/images/pixel.php,v 1.10 2004/01/05 22:45:47 slusarz Exp $
 *
 * Copyright 2002-2004 Chuck Hagenbuch <chuck@horde.org>
 *
 * See the enclosed file COPYING for license information (LGPL).  If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 */

define('HORDE_BASE', dirname(__FILE__) . '/../..');
require_once HORDE_BASE . '/lib/base.php';
require_once HORDE_LIBS . 'Horde/Image.php';

$gif = &Horde_Image::factory('gif', array('rgb' => Util::getFormData('c')));

header('Content-type: image/gif');
header('Expires: Wed, 21 Aug 1969 11:11:11 GMT');
header('Cache-Control: no-cache');
header('Cache-Control: must-revalidate');

echo $gif->makePixel();
