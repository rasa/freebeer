<?php
/**
 * $Horde: chora/cvs.php,v 1.172 2004/01/22 01:00:18 jan Exp $
 *
 * Copyright 1999-2004 Anil Madhavapeddy <anil@recoil.org>
 * Copyright 1999-2004 Charles Hagenbuch <chuck@horde.org>
 *
 * See the enclosed file COPYING for license information (GPL).  If you
 * did not receive this file, see http://www.fsf.org/copyleft/gpl.html.
 */

@define('CHORA_BASE', dirname(__FILE__));
require_once CHORA_BASE . '/lib/base.php';
require_once HORDE_BASE . '/config/mime_drivers.php';
require_once HORDE_LIBS . 'Horde/MIME/Magic.php';
require_once HORDE_LIBS . 'Horde/MIME/Viewer.php';
require_once CHORA_BASE . '/config/mime_drivers.php';

if ($atdir) {
    Chora::checkError($dir = $VC->queryDir($where));

    $atticFlags = (boolean)$acts['sa'];
    Chora::checkError($dir->browseDir(true, $atticFlags));
    $dir->applySort($acts['sbt'], $acts['ord']);
    Chora::checkError($dirList = &$dir->queryDirList());
    Chora::checkError($fileList = $dir->queryFileList($atticFlags));

    /* Decide what title to display. */
    if ($where == '') {
        $title = $conf['options']['introTitle'];
    } else {
        $title = sprintf(_("Source Directory of /%s"), Text::htmlallSpaces($where));
    }

    if (is_a($VC, 'VC_svn')) {
        // Showing deleted files is not supported in svn.
        $extraLink = '';
    } else {
        if ($acts['sa']) {
            $extraLink='<a class="widget" href="' . Chora::url('cvs', $where . '/', array('sa' => 0)) . '">' . _("Hide Deleted Files") . '</a>';
        } else {
            $extraLink='<a class="widget" href="' . Chora::url('cvs', $where . '/', array('sa' => 1)) . '">' . _("Show Deleted Files") . '</a>';
        }
    }

    require CHORA_TEMPLATES . '/common-header.inc';
    Chora::menu();
    require CHORA_TEMPLATES . '/headerbar.inc';

    foreach (array('age', 'rev', 'name', 'author') as $u) {
        $umap = array('age' => VC_SORT_AGE, 'rev' => VC_SORT_REV,
                      'name' => VC_SORT_NAME, 'author' => VC_SORT_AUTHOR);
        $arg = array('sbt' => $umap[$u]);
        if ($acts['sbt'] == $umap[$u]) {
            $arg['ord'] = !$acts['ord'];
        }
        $url[$u] = Chora::url('cvs', $where . '/', $arg);
    }

    /* Print out the directory header. */
    $printAllCols = sizeof($fileList);
    require CHORA_TEMPLATES . '/directory/header.inc';

    /* Unless we're at the top, display the 'back' bar. */
    $dirrow = 1;
    if ($where != '') {
        $dirrow = ++$dirrow % 2;
        $url = Chora::url('cvs', preg_replace('|[^/]+$|', '', $where));
        require CHORA_TEMPLATES . '/directory/back.inc';
    }

    /* Display all the directories first. */
    foreach ($dirList as $currentDir) {
        if ($conf['hide_restricted'] && Chora::isRestricted($currentDir)) {
            continue;
        }
        $dirrow = ++$dirrow % 2;
        $url = Chora::url('cvs', "$where/$currentDir/");
        $currDir = Text::htmlAllSpaces($currentDir);
        require CHORA_TEMPLATES . '/directory/dir.inc';
    }

    /* Display all of the files in this directory */
    foreach ($fileList as $currFile) {
        if ($conf['hide_restricted'] && Chora::isRestricted($currFile->queryName())) {
            continue;
        }
        $dirrow = ++$dirrow % 2;
        $lg = $currFile->queryLastLog();
        if (is_a($lg, 'PEAR_Error')) {
            continue;
        }
        $realname = $currFile->queryName();
        $mimeType = MIME_Magic::filenameToMIME($realname);

        $icon = MIME_Viewer::getIcon($mimeType);

        $aid = $lg->queryAuthor();
        $author = Chora::showAuthorName($aid);
        $head = $currFile->queryHead();
        $date = $lg->queryDate();
        $log  = $lg->queryLog();
        $attic = $currFile->isAtticFile();
        $fileName = $where . ($attic ? DIRECTORY_SEPARATOR . 'Attic' : '') . DIRECTORY_SEPARATOR . $realname;
        $name = Text::htmlAllSpaces($realname);
        $url = Chora::url('cvs', $fileName);
        $readableDate = VC_File::readableTime($date);
        if ($log) {
            $shortLog = str_replace("\n" , ' ',
                trim(substr($log, 0, $conf['options']['shortLogLength'] - 1)));
            if (strlen($log) > 80) {
                $shortLog .= "...";
            }
        }
        require CHORA_TEMPLATES.'/directory/file.inc';
    }
    /* Display the options control panel at the bottom */
    $formwhere = $scriptName . '/' . $where;

    require CHORA_TEMPLATES . '/directory/footer.inc';
    require $registry->getParam('templates', 'horde') . '/common-footer.inc';

} elseif ($VC->isFile($fullname)) {
    $fl = &$VC->getFileObject($where);
    $title = sprintf(_("Source Log for %s"), Text::htmlAllSpaces($where));
    $upwhere = preg_replace('|[^/]+$|', '', $where);
    $onb = Util::getFormData('onb', 0);
    $r1 = Util::getFormData('r1', 0);

    $isBranch = isset($onb) && isset($fl->branches[$onb]) ? $fl->branches[$onb] : '';
    $extraLink = Chora::getFileViews();

    require CHORA_TEMPLATES . '/common-header.inc';
    Chora::menu();
    require CHORA_TEMPLATES . '/headerbar.inc';

    $mimeType = MIME_Magic::filenameToMIME($fullname);
    $defaultTextPlain = ($mimeType == 'text/plain');

    foreach ($fl->logs as $lg) {
        $rev = $lg->rev;

        /* Are we sticking only to one branch ? */
        if ($onb && VC_Revision::valid($onb)) {

            /* If so, if we are on the branch itself, let it through */
            if (substr($rev, 0, strlen($onb)) != $onb) {

                /* We are not on the branch, see if we are on a trunk
                 * branch below the branch */
                $baseRev = VC_Revision::strip($onb, 1);

                /* Check we are at the same level of branching or less */
                if (substr_count($rev, '.') <= substr_count($baseRev, '.')) {
                    /* If we are at the same level, and the revision is
                     * less, then let the revision through, since it was
                     * committed before the branch actually took place
                     */
                    if (VC_Revision::cmp($rev, $baseRev) > 0) {
                        continue;
                    }
                } else {
                    continue;
                }
            }
        }

        $textURL = Chora::url('co', $where, array('r' => $rev));
        $commitDate = strftime('%c', $lg->date);
        $readableDate = VC_File::readableTime($lg->date, true);

        $aid = $lg->queryAuthor();
        $author = Chora::showAuthorName($aid, true);

        if (!empty($lg->tags)) {
            $commitTags = implode(', ', $lg->tags);
        } else {
            $commitTags = '';
        }

        $branchPointsArr = array();
        foreach ($lg->querySymbolicBranches() as $symb => $bra) {
            $branchPointsArr[] = '<a href="' . Chora::url('cvs', $where, array('onb' => $bra)) . '">'. $symb . '</a>';
        }

        /* Calculate the current branch name and revision. */
        $branchPoints = implode(' , ', $branchPointsArr);
        $branchRev = VC_Revision::strip($rev, 1);
        if (@isset($fl->branches[$branchRev])) {
            $branchName = $fl->branches[$branchRev];
        } else {
            $branchName = '';
        }

        if ($prevRevision = $fl->queryPreviousRevision($lg->queryRevision())) {
            $changedLines = $lg->queryChangedLines();
            $coloredDiffURL = Chora::url('diff', $where, array('r1' => $prevRevision, 'r2' => $rev, 'ty' => 'h'));
            $longDiffURL = Chora::url('diff', $where, array('r1' => $prevRevision, 'r2' => $rev, 'ty' => 'h', 'num' => 10));
            $uniDiffURL = Chora::url('diff', $where, array('r1' => $prevRevision, 'r2' => $rev, 'ty' => 'u'));
            $nowsDiffURL = Chora::url('diff', $where, array('ws' => 0, 'r1' => $prevRevision, 'r2' => $rev, 'ty' => 'u'));
        }

        $manyRevisions = !($fl->queryRevision() === '1.1');
        if ($manyRevisions) {
            $selCvsURL = Chora::url('cvs', $where, array('r1' => $rev, 'onb' => $onb));
            if (!empty($r1)) {
                $selColoredDiffURL = Chora::url('diff', $where, array('r1' => $r1, 'r2' => $rev, 'ty' => 'h'));
                $selLongDiffURL = Chora::url('diff', $where, array('r1' => $r1, 'r2' => $rev, 'ty' => 'h', 'num' => 10));
                $selUniDiffURL = Chora::url('diff', $where, array('r1' => $r1, 'r2' => $rev, 'ty' => 'u'));
                $selNowsDiffURL = Chora::url('diff', $where, array('ws' => 0, 'r1' => $r1, 'r2' => $rev, 'ty' => 'u'));
            }
        }

        $logMessage = Chora::formatLogMessage($lg->log);

        if ($r1 === $rev) {
            $bgclass = 'diff-selected';
        } else {
            $bgclass = 'control';
        }

        require CHORA_TEMPLATES . '/log/rev.inc';
    }

    $first = end($fl->logs);
    $diffValueLeft  = $first->rev;
    $diffValueRight = $fl->queryRevision();

    $sel = '';
    foreach ($fl->symrev as $sm => $rv) {
        $sel .= '<option value="' . $rv . '">' . $sm . '</option>';
    }

    $selAllBranches = '';
    foreach ($fl->branches as $num => $sym) {
        $selAllBranches .= '<option value="' . $num . '">' . $sym;
    }

    require CHORA_TEMPLATES . '/log/request.inc';
    require $registry->getParam('templates', 'horde') . '/common-footer.inc';
} else {
    Chora::fatal('404 Not Found', "$where: no such file or directory");
}
