<?php
/**
 * $Horde: chora/diff.php,v 1.79 2004/01/17 22:57:25 jan Exp $
 *
 * Copyright 2000-2004 Anil Madhavapeddy <anil@recoil.org>
 *
 * See the enclosed file COPYING for license information (GPL).  If you
 * did not receive this file, see http://www.fsf.org/copyleft/gpl.html.
 */

define('CHORA_BASE', dirname(__FILE__));
require_once CHORA_BASE . '/lib/base.php';

/* Spawn the repository and file objects */
$fl = &$VC->getFileObject($where);

/* Initialise the form variables correctly.
 * If r1/r2 are empty, then use the corresponding text field instead */
$r1 = Util::getFormData('r1', 0);
$r2 = Util::getFormData('r2', 0);

if (!$r1) {
    $r1 = Util::getFormData('tr1');
}
if (!$r2) {
    $r2 = Util::getFormData('tr2');
}

/* If no context-size has been specified, default to 3. */
$num = Util::getFormData('num', 3);

/* If no type has been specified, then default to human readable. */
$ty = Util::getFormData('ty', 'h');

/* Unless otherwise specified, show whitespace differences. */
$ws = Util::getFormData('ws', 1);

/* Figure out what type of diff has been requested. */
switch ($ty) {
case 's':
    $type = 'column';
    break;

case 'c':
    $type = 'context';
    break;

case 'e':
    $type = 'ed';
    break;

case 'u':
case 'h':
default:
    $type = 'unified';
}

/* Ensure that we have valid revision numbers. */
if (!VC_Revision::valid($r1) || !VC_Revision::valid($r2)) {
    Chora::fatal(_("Malformed Query"));
}

/* Cache the output of the diff for a week - it can be longer, since
 * it should never change */
header('Cache-Control: max-age=604800');

/* Title to use for html output pages */
$title = sprintf(_("Diff for %s between version %s and %s"),
                 Text::htmlallspaces($where), $r1, $r2);

/* All is ok, proceed with the diff */
switch ($type) {
case 'column':
    /* We'll need to know the mime type to modify diffs based on the mime
       type. */
    require_once HORDE_LIBS . 'Horde/MIME/Magic.php';
    $mime_type = MIME_Magic::filenameToMIME($fullname);

    if ($browser->isViewable($mime_type)) {
        // The above are images that most web browsers can inline
        // We borrow a *large* part of this from the Human-Readable case
        $url1 = Chora::url('co', $where, array('r' => $r1));
        $url2 = Chora::url('co', $where, array('r' => $r2));
        $path = $fl->queryModulePath();

        // Get the log entry so we can display it
        $log = $fl->logs[$r2];
        $log_print = Chora::formatLogMessage($log->queryLog());

        // Start the html output, include menu bar and headers.
        require CHORA_TEMPLATES . '/common-header.inc';
        Chora::menu();
        require CHORA_TEMPLATES . '/headerbar.inc';

        // Create a table for the two revisions, display log, and
        // print a labeled bar for the revisions.
        require CHORA_TEMPLATES . '/diff/hr/header.inc';
        echo "<td><img src=\"$url1\"></td>";
        echo "<td><img src=\"$url2\"></td>";
        echo '</tr>';
        require $registry->getParam('templates', 'horde') . '/common-footer.inc';
    } else {
        header('Content-Type: text/plain');
        echo implode("\n", $VC->getDiff($fl, $r1, $r2, $type, $num, $ws));
    }
    break;

case 'context':
    header('Content-Type: text/plain');
    echo implode("\n", $VC->getDiff($fl, $r1, $r2, $type, $num, $ws));
    break;

case 'ed':
    header('Content-Type: text/plain');
    echo implode("\n", $VC->getDiff($fl, $r1, $r2, $type, $num, $ws));
    break;

case 'unified':
default:
    if ($ty != 'h') {
        /* Not Human-Readable format. */
        header('Content-Type: text/plain');
        echo implode("\n", $VC->getDiff($fl, $r1, $r2, $type, $num, $ws));
    } else {
        /* Human-Readable diff. */

        /* Output standard header information for the page. */
        $filename = preg_replace('/^.*\//', '', $where);
        $pathname = preg_replace('/[^\/]*$/', '', $where);

        $log = $fl->logs[$r2];
        $log_print = Chora::formatLogMessage($log->queryLog());

        require CHORA_TEMPLATES . '/common-header.inc';
        Chora::menu();
        require CHORA_TEMPLATES . '/headerbar.inc';
        require CHORA_TEMPLATES . '/diff/hr/header.inc';

        /* Retrieve the tree of changes. */
        $lns = VC_Diff::humanReadable($VC->getDiff($fl, $r1, $r2, 'unified', $num, $ws));
        /* TODO: check for errors here (PEAR_Error returned) - avsm */
        /* Is the diff empty? */
        if (!sizeof($lns)) {
            require CHORA_TEMPLATES . '/diff/hr/nochange.inc';
        } else {
            /* Iterate through every header block of changes */
            foreach ($lns as $header) {
                $lefthead = Text::htmlspaces(@$header['oldline']);
                $righthead = Text::htmlspaces(@$header['newline']);
                $headfunc = Text::htmlspaces(@$header['function']);
                require CHORA_TEMPLATES . '/diff/hr/row.inc';

                /* Each header block consists of a number of changes
                   (add, remove, change) */
                foreach ($header['contents'] as $change) {
                    switch ($change['type']) {
                    case 'add':
                        foreach ($change['lines'] as $line) {
                            $line = Text::htmlspaces($line);
                            require CHORA_TEMPLATES . '/diff/hr/add.inc';
                        }
                        break;

                    case 'remove':
                        foreach ($change['lines'] as $line) {
                            $line = Text::htmlspaces($line);
                            require CHORA_TEMPLATES . '/diff/hr/remove.inc';
                        }
                        break;

                    case 'empty':
                        $line = Text::htmlspaces($change['line']);
                        require CHORA_TEMPLATES . '/diff/hr/empty.inc';
                        break;

                    case 'change':
                        /* Pop the old/new stacks one by one, until both
                           are empty */
                        while (sizeof($change['old']) || sizeof($change['new'])) {
                            if ($left = array_shift($change['old'])) {
                                $left = Text::htmlspaces($left);
                            }
                            if ($right = array_shift($change['new'])) {
                                $right = Text::htmlspaces($right);
                            }
                            require CHORA_TEMPLATES . '/diff/hr/change.inc';
                        }
                        break;
                    }
                }
            }
        }

        /* Print legend. */
        require CHORA_TEMPLATES . '/diff/hr/footer.inc';
        require $registry->getParam('templates', 'horde') . '/common-footer.inc';
    }
}
