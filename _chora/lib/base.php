<?php
/**
 * Chora base inclusion file.
 *
 * $Horde: chora/lib/base.php,v 1.98 2004/02/14 04:02:10 chuck Exp $
 *
 * This file brings in all of the dependencies that every Chora script
 * will need, and sets up objects that all scripts use.
 */

// Check for a prior definition of HORDE_BASE (perhaps by an
// auto_prepend_file definition for site customization).
if (!defined('HORDE_BASE')) {
    @define('HORDE_BASE', dirname(__FILE__) . '/../..');
}

// Load the Horde Framework core, and set up inclusion paths.
require_once HORDE_BASE . '/lib/core.php';

// Registry
$registry = &Registry::singleton();
if (is_a(($pushed = $registry->pushApp('chora', !defined('AUTH_HANDLER'))), 'PEAR_Error')) {
    if ($pushed->getCode() == 'permission_denied') {
        Horde::authenticationFailureRedirect(); 
    }
    Horde::fatal($pushed, __FILE__, __LINE__, false);
}
$conf = &$GLOBALS['conf'];
@define('CHORA_TEMPLATES', $registry->getParam('templates'));

// Notification system.
$notification = &Notification::singleton();
$notification->attach('status');

// Find the base file path of Chora.
@define('CHORA_BASE', dirname(__FILE__) . '/..');

// Horde base libraries.
require_once HORDE_LIBS . 'Horde/Text.php';
require_once HORDE_LIBS . 'Horde/Help.php';

// Chora libraries and config.
require_once CHORA_BASE . '/config/sourceroots.php';
require_once CHORA_BASE . '/lib/Chora.php';
require_once HORDE_LIBS . 'Horde/VC.php';

// Initialize objects, path, etc.
Chora::init();

if (Chora::isRestricted($where)) {
    Chora::fatal('403 Forbidden', "$where: Forbidden by server configuration");
}

/* Start compression, if requested. */
Horde::compressOutput();
