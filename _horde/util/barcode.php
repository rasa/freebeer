<?php
/**
 * $Horde: horde/util/barcode.php,v 1.6 2004/01/05 22:45:44 slusarz Exp $
 *
 * Copyright 2002-2004 Chuck Hagenbuch <chuck@horde.org>
 *
 * See the enclosed file COPYING for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 */

define('HORDE_BASE', dirname(__FILE__) . '/..');
require_once HORDE_BASE . '/lib/base.php';
require_once HORDE_LIBS . 'Horde/Image.php';

header('Pragma: public');

$image = &Horde_Image::factory(Util::getFormData('type', 'png'));
$image->headers();
echo $image->makeBarcode(Util::getFormData('barcode', 'test'));
