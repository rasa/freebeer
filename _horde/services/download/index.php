<?php
/**
 * $Horde: horde/services/download/index.php,v 1.6 2004/01/01 15:17:01 jan Exp $
 *
 * Copyright 2002-2004 Michael Slusarz <slusarz@bigworm.colorado.edu>
 *
 * See the enclosed file COPYING for license information (LGPL).  If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 */

include_once '../../lib/base.php';

if (!($module = Util::getFormData('module')) ||
    !file_exists($registry->getParam('fileroot', $module))) {
    Horde::fatal('Do not call this script directly.', __FILE__, __LINE__);
}
include $registry->getParam('fileroot', $module) . '/view.php';
