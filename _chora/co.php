<?php
/**
 * $Horde: chora/co.php,v 1.50 2004/02/14 02:40:30 chuck Exp $
 *
 * Copyright 2000-2004 Anil Madhavapeddy <anil@recoil.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/gpl.html.
 */

define('CHORA_BASE', dirname(__FILE__));
require_once CHORA_BASE . '/lib/base.php';
require_once HORDE_BASE . '/config/mime_drivers.php';
require_once HORDE_LIBS . 'Horde/MIME/Part.php';
require_once HORDE_LIBS . 'Horde/MIME/Magic.php';
require_once HORDE_LIBS . 'Horde/MIME/Viewer.php';

/* Should we pretty-print this output or not? */
$plain = Util::getFormData('p', 0);

/* Create the VC_File object and populate it. */
$file = &$VC->getFileObject($where);

/* Get the revision number. */
$r = Util::getFormData('r', 0);

/* If no revision is specified, default to HEAD.  If a revision is
 * specified, it's safe to cache for a long time. */
if ($r == 0) {
    $r = $file->queryRevision();
    header('Cache-Control: max-age=60, must-revalidate');
} else {
    header('Cache-Control: max-age=2419200');
}

/* Is this a valid revision being requested? */
if (!VC_Revision::valid($r)) {
    Chora::fatal('404 Not Found', "Revision Not Found: $r is not a valid RCS revision number");
}

/* Retrieve the actual checkout. */
$checkOut = $VC->getCheckout($file, $r);

/* Get the MIME type of the file, or at least our best guess at it. */
$mime_type = MIME_Magic::filenameToMIME($fullname);

/* Check error status, and either show error page, or the checkout
 * contents */
Chora::checkError($checkOut);

if (!$plain) {
    /* Pretty-print the checked out copy */
    $pretty = &Chora::pretty($mime_type, $checkOut);

    if (($pretty->getType() == 'text/html' || $pretty->getType() == 'text/plain')
        && $pretty->canDisplayInline()) {

        $title = sprintf(_("Checkout of %s (revision %s)"), basename($fullname), $r);
        $extraLink = sprintf('<a href="%s">%s</a> <b>|</b> <a href="%s">%s</a>',
                             Chora::url('annotate', $where, array('rev' => $r)), _("Annotate"),
                             Chora::url('co', $where, array('r' => $r, 'p' => 1)), _("Download"));

        /* Make sure this revision exists. */
        if (empty($file->logs[$r])) {
            Chora::fatal(sprintf(_("Revision %s for file %s not found."), $r, $file));
        }

        /* Get this revision's attributes in printable form. */
        $log = $file->logs[$r];
        $commitDate = strftime('%c', $log->date);
        $readableDate = VC_File::readableTime($log->date, true);

        $aid = $log->queryAuthor();
        $author = Chora::showAuthorName($aid, true);

        if (!empty($log->tags)) {
            $commitTags = implode(', ', $log->tags);
        } else {
            $commitTags = '';
        }

        $branchPointsArr = array();
        foreach ($log->querySymbolicBranches() as $symb => $bra) {
            $branchPointsArr[] = '<a href="' . Chora::url('cvs', $where, array('onb' => $bra)) . '">'. $symb . '</a>';
        }

        /* Calculate the current branch name and revision. */
        $branchPoints = implode(' , ', $branchPointsArr);
        $branchRev = VC_Revision::strip($r, 1);
        if (@isset($fl->branches[$branchRev])) {
            $branchName = $fl->branches[$branchRev];
        } else {
            $branchName = '';
        }

        if ($prevRevision = VC_Revision::prev($log->queryRevision())) {
            $changedLines = $log->queryChangedLines();
        }

        $log_print = Chora::formatLogMessage($log->queryLog());
        $i = 0;

        require CHORA_TEMPLATES . '/common-header.inc';
        Chora::menu();
        require CHORA_TEMPLATES . '/headerbar.inc';
        require CHORA_TEMPLATES . '/checkout/checkout.inc';
        require $registry->getParam('templates', 'horde') . '/common-footer.inc';
    } else {
        header('Content-Type: ' . $pretty->getType());
        echo $pretty->render();
    }
    exit;
} else {
    /* Download the file. */

    // Get data.
    $content = '';
    while ($line = fgets($checkOut)) {
        $content .= $line;
    }
    @fclose($checkOut);

    // Get name.
    $filename = $file->queryName();
    if ($browser->getBrowser() == 'opera') {
        $filename = strtr($filename, ' ', '_');
    }

    // Send headers.
    $browser->downloadHeaders($filename, $mime_type, false, strlen($content));

    // Send data.
    echo $content;
    exit;
}
