<?php
/**
 * $Horde: $
 *
 * Wrapper for CVSGraph
 *
 * Copyright 1999-2004 Anil Madhavapeddy <anil@recoil.org>
 * Copyright 1999-2004 Charles Hagenbuch <chuck@horde.org>
 *
 * See the enclosed file COPYING for license information (GPL).  If you
 * did not receive this file, see http://www.fsf.org/copyleft/gpl.html.
 */

@define('CHORA_BASE', dirname(__FILE__));
require_once CHORA_BASE . '/lib/base.php';

// Exit if cvsgraph isn't active or it's not supported.
if (empty($conf['paths']['cvsgraph']) || is_a($VC, 'VC_svn')) {
    header('Location: ' . Chora::url('cvs', $where));
    exit;
}

if (@is_file($fullname . ',v')) {
    $root = escapeShellCmd($VC->sourceroot());
    $file = escapeShellCmd($where . ',v');

    if (Util::getFormData('show_image')) {
        // Pipe out the actual image.
        $args = array('c' => $conf['paths']['cvsgraph_conf'],
                      'r' => $root);

        // Build up the argument string.
        $argstr = '';
        if (OS_WINDOWS) {
            foreach ($args as $key => $val) {
                $argstr .= "-$key \"$val\" ";
            }
        } else {
            foreach ($args as $key => $val) {
                $argstr .= "-$key '$val' ";
            }
        }

        header('Content-Type: image/png');
        passthru($conf['paths']['cvsgraph'] . ' ' . $argstr . ' ' . $file);
    } else {
        // Display the wrapper page for the image.
        $title = sprintf(_("CVS Graph for %s"), Text::htmlAllSpaces($where));
        $upwhere = preg_replace('|[^/]+$|', '', $where);
        $extraLink = Chora::getFileViews();

        require CHORA_TEMPLATES . '/common-header.inc';
        Chora::menu();
        require CHORA_TEMPLATES . '/headerbar.inc';

        $imgUrl = Chora::url('cvsgraph', $where, array('show_image' => 1));

        $args = array('c' => $conf['paths']['cvsgraph_conf'],
                      'M' => 'graphMap',
                      'r' => $root,
                      '0' => ini_get('arg_separator.output'),
                      '1' => Chora::url('cvs', $where, array('dummy' => 'true')),
                      '2' => Chora::url('diff', $where, array('dummy' =>'true')),
                      '3' => Chora::url('co', $where, array('dummy' => 'true')),
                      );

        // Build up the argument string.
        $argstr = '';
        if (OS_WINDOWS) {
            foreach ($args as $key => $val) {
                $argstr .= "-$key \"$val\" ";
            }
        } else {
            foreach ($args as $key => $val) {
                $argstr .= "-$key '$val' ";
            }
        }

        // Generate the imagemap.
        $map = shell_exec($conf['paths']['cvsgraph'] . ' ' . $argstr . ' -i ' . $file);

        require CHORA_TEMPLATES . '/cvsgraph/cvsgraph.inc';
        require $registry->getParam('templates', 'horde') . '/common-footer.inc';
    }
} else {
    Chora::fatal('404 Not Found', "$where: no such file or directory");
}
