<?php
/**
 * $Horde: chora/stats.php,v 1.22 2004/01/17 22:57:25 jan Exp $
 *
 * Copyright 2000-2004 Anil Madhavapeddy <anil@recoil.org>
 *
 * See the enclosed file COPYING for license information (GPL).  If you
 * did not receive this file, see http://www.fsf.org/copyleft/gpl.html.
 */

define('CHORA_BASE', dirname(__FILE__));
require_once CHORA_BASE . '/lib/base.php';

/* Spawn the file object. */
$fl = &$VC->getFileObject($where);

$extraLink = Chora::getFileViews();

$author_stats = array();
foreach ($fl->logs as $lg) {
    @$author_stats[$lg->queryAuthor()]++;
}
arsort($author_stats);

// Show the page.
$title = sprintf(_("Statistics for %s"), Text::htmlallspaces($where));
require CHORA_TEMPLATES . '/common-header.inc';
Chora::menu();
require CHORA_TEMPLATES . '/headerbar.inc';
require CHORA_TEMPLATES . '/stats/stats.inc';
require $registry->getParam('templates', 'horde') . '/common-footer.inc';
