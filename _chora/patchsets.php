<?php
/**
 * $Horde: chora/patchsets.php,v 1.12 2004/01/29 19:05:47 chuck Exp $
 *
 * Copyright 1999-2004 Anil Madhavapeddy <anil@recoil.org>
 * Copyright 1999-2004 Charles Hagenbuch <chuck@horde.org>
 *
 * See the enclosed file COPYING for license information (GPL).  If you
 * did not receive this file, see http://www.fsf.org/copyleft/gpl.html.
 */

@define('CHORA_BASE', dirname(__FILE__));
require_once CHORA_BASE . '/lib/base.php';

// Exit if cvsps isn't active or it's not a subversion repository.
if (empty($conf['paths']['cvsps']) && !is_a($VC, 'VC_svn')) {
    header('Location: ' . Chora::url('cvs', $where));
    exit;
}

if (@is_dir($fullname)) {
    Chora::fatal('No patchsets for directories yet.');
} elseif ($VC->isFile($fullname)) {
    $ps = $VC->getPatchsetObject($where);

    $title = sprintf(_("Patchsets for %s"), Text::htmlallspaces($where));
    $upwhere = preg_replace('|[^/]+$|', '', $where);
    $extraLink = Chora::getFileViews();

    require CHORA_TEMPLATES . '/common-header.inc';
    Chora::menu();
    require CHORA_TEMPLATES . '/headerbar.inc';

    foreach ($ps->_patchsets as $id => $patchset) {
        $commitDate = strftime('%c', $patchset['date']);
        $readableDate = VC_File::readableTime($patchset['date'], true);
        $author = Chora::showAuthorName($patchset['author'], true);
        if (is_a($VC, 'VC_svn')) {
            // The diff should be from the top of the source tree so
            // as to get all files.
            $topDir = substr($where, 0, strpos($where, '/', 1));

            // Subversion supports patchset diffs natively.
            $allDiffsLink = Horde::link(Chora::url('diff', $topDir, array('r1' => $id - 1, 'r2' => $id, 'ty' => 'u')),
                                        _("Diff All Files")) . _("Diff All Files") . '</a>';
        } else {
            // Not supported in any other VC systems yet.
            $allDiffsLink = '';
        }

        $files = array();
        $dir = dirname($where);
        foreach ($patchset['members'] as $member) {
            $file = array();
            $mywhere = is_a($VC, 'VC_svn') ? $member['file'] : $dir . DIRECTORY_SEPARATOR . $member['file'];
            $file['file'] = Horde::link(Chora::url('patchsets', $mywhere), $member['file']) . $member['file'] . '</a>';
            if ($member['from'] == 'INITIAL') {
                $file['from'] = '<i>' . _("New File") . '</i>';
                $file['diff'] = '';
            } else {
                $file['from'] = Horde::link(Chora::url('co', $mywhere, array('r' => $member['from'])), $member['from']) . $member['from'] . '</a>';
                $file['diff'] = Horde::link(Chora::url('diff', $mywhere, array('r1' => $member['from'], 'r2' => $member['to'], 'ty' => 'u')), _("Diff")) . '(' . _("Diff") . ')';
            }
            if (substr($member['to'], -6) == '(DEAD)') {
                $file['to'] = '<i>' . _("Deleted") . '</i>';
                $file['diff'] = '';
            } else {
                $file['to'] = Horde::link(Chora::url('co', $mywhere, array('r' => $member['to'])), $member['to']) . $member['to'] . '</a>';
            }

            $files[] = $file;
        }

        $logMessage = Chora::formatLogMessage($patchset['log']);
        require CHORA_TEMPLATES . '/patchsets/ps.inc';
    }

    require $registry->getParam('templates', 'horde') . '/common-footer.inc';
} else {
    Chora::fatal('404 Not Found', "$where: no such file or directory");
}
