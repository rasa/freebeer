<?php
/**
 * $Horde: chora/annotate.php,v 1.43 2004/01/17 22:57:25 jan Exp $
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

/* Retrieve the desired revision from the GET variable. */
$rev = Util::getFormData('rev', '1.1');
if (!VC_Revision::valid($rev)) {
    Chora::fatal('404 Not Found', "Revision $rev not found");
}

$ann = &$VC->getAnnotateObject($fl);
Chora::checkError($lines = $ann->doAnnotate($rev));

$title = sprintf(_("Source Annotation of %s for version %s"), Text::htmlAllSpaces($where), $rev);
$extraLink = sprintf('<a href="%s">%s</a> <b>|</b> <a href="%s">%s</a>',
                     Chora::url('co', $where, array('r' => $rev)), _("View"),
                     Chora::url('co', $where, array('r' => $rev, 'p' => 1)), _("Download"));
require CHORA_TEMPLATES . '/common-header.inc';
Chora::menu();
require CHORA_TEMPLATES . '/headerbar.inc';
require CHORA_TEMPLATES . '/annotate/header.inc';

$author = '';
$style = 0;

foreach ($lines as $line) {
    $lineno = $line['lineno'];
    $prevAuthor = $author;
    $author = Chora::showAuthorName($line['author']);
    if ($prevAuthor != $author) {
        $style = (++$style % 3);
    }
    $rev = $line['rev'];
    $line = Text::htmlspaces($line['line']);
    include CHORA_TEMPLATES . '/annotate/line.inc';
}

require CHORA_TEMPLATES . '/annotate/footer.inc';
require $registry->getParam('templates', 'horde') . '/common-footer.inc';
