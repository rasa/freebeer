<?php
/**
 * Chora Base Class.
 *
 * $Horde: chora/lib/Chora.php,v 1.59 2004/02/11 22:17:10 chuck Exp $
 *
 * @author Anil Madhavapeddy <avsm@horde.org>
 * @version $Revision$
 * @package Chora
 */
class Chora {

    /**
     * Initialize global variables and objects.
     */
    function init()
    {
        global $acts, $defaultActs, $conf, $where, $atdir,
            $fullname, $prefs, $sourceroots, $sourceroot, $scriptName;

        /**
         * Variables we wish to propagate across web pages
         *  sbt = Sort By Type (name, age, author, etc)
         *  ha  = Hide Attic Files
         *  ord = Sort order
         *
         * Obviously, defaults go into $defaultActs :)
         * TODO: defaults of 1 will not get propagated correctly - avsm
         * XXX: Rewrite this propagation code, since it sucks - avsm
         */
        $defaultActs = array('sbt' => constant($conf['options']['defaultsort']),
                             'sa'  => 0,
                             'ord' => VC_SORT_ASCENDING,
                             'ws'  => 1);

        /* Use the last sourceroot used as the default value if the user
         * has that preference. */
        $remember_last_file = $prefs->getValue('remember_last_file');
        if ($remember_last_file) {
            $last_file = $prefs->getValue('last_file') ? $prefs->getValue('last_file') : null;
            $last_sourceroot = $prefs->getValue('last_sourceroot') ? $prefs->getValue('last_sourceroot') : null;
        }

        if ($remember_last_file && !empty($last_sourceroot) &&
            is_array(@$sourceroots[$last_sourceroot])) {
            $defaultActs['rt'] = $last_sourceroot;
        } else {
            foreach ($sourceroots as $key => $val) {
                if (isset($val['default']) || !isset($defaultActs['rt'])) {
                    $defaultActs['rt'] = $key;
                }
            }
        }

        /* See if any have been passed as GET variables, and if so,
         * assign them into the acts array. */
        $acts = array();
        foreach ($defaultActs as $key => $default) {
            $acts[$key] = Util::getFormData($key, $default);
        }

        if (!isset($sourceroots[$acts['rt']])) {
            Chora::fatal(404, 'Malformed URL');
        }

        $sourcerootopts = $sourceroots[$acts['rt']];
        $sourceroot = $sourcerootopts['location'];

        $GLOBALS['VC'] = &VC::factory($sourcerootopts['type'], 
                                      array('sourceroot' => $sourceroot,
                                            'paths' => $conf['paths']));

        $conf['paths']['sourceroot'] = $sourcerootopts['location'];
        $conf['paths']['cvsusers'] = "$sourceroot/". @$sourcerootopts['cvsusers'];
        $conf['paths']['introText'] = CHORA_BASE . '/config/' . @$sourcerootopts['intro'];
        $conf['options']['introTitle'] = @$sourcerootopts['title'];
        $conf['options']['sourceRootName'] = $sourcerootopts['name'];

        $where = Util::getFormData('f', '');

        /* Override $where with PATH_INFO if appropriate. */
        if ($conf['options']['use_path_info'] && isset($_SERVER['PATH_INFO'])) {
            $where = $_SERVER['PATH_INFO'];
        }

        if ($where == '') {
            $where = '/';
        }

        $atdir = (substr($where, -1) == '/');

        /* Location relative to the SOURCEROOT. */
        $where = preg_replace('|^/|', '', $where);
        $where = preg_replace('|\.\.|', '', $where);
        $where = preg_replace('|/$|', '', $where);

        /* Location of this script (e.g. /chora/cvs.php) */
        $scriptName = preg_replace('|^/?|', '/', $_SERVER['PHP_SELF']);
        $scriptName = preg_replace('|/$|', '', $scriptName);

        /* Store last file/repository viewed, and set 'where' to
         * last_file if necessary. */
        if ($remember_last_file) {
            if (!isset($_SESSION['chora']['login'])) {
                $_SESSION['chora']['login'] = 0;
            }

            /* We store last_sourceroot and last_file only when we have
             * already displayed at least one page. */
            if (!empty($_SESSION['chora']['login'])) {
                $prefs->setValue('last_sourceroot', $acts['rt']);
                $prefs->setValue('last_file', $where);
            } else {
                /* We are displaying the first page. */
                if ($last_file && !$where) {
                    $where = $last_file;
                }
                $_SESSION['chora']['login'] = 1;
            }
        }

        $fullname = "$sourceroot/$where";

        if ($sourcerootopts['type'] == 'cvs' && !@is_dir($sourceroot)) {
            Chora::fatal(_("SourceRoot not found! This could be a misconfiguration by the server administrator, or the server could be having temporary problems. Please try again later."));
        }
    }

    function whereMenu()
    {
        global $where, $atdir;

        $bar = '';
        $wherePath = '';

        $dirs = explode('/', $where);
        $last = count($dirs) - 1;
        $i = 0;
        foreach ($dirs as $dir) {
            if (!$atdir && $i++ == $last) {
                $wherePath .= "/$dir";
            } else {
                $wherePath .= "/$dir/";
            }
            $wherePath = str_replace('//', '/', $wherePath);
            if (!empty($dir) && ($dir != 'Attic')) {
                $bar .= '/ <a href="' . Chora::url('cvs', $wherePath) . '">'. Text::htmlallspaces($dir) . '</a>';
            }
        }
        return $bar;
    }

    /**
     * Output an error page.
     *
     * @param string $msg  The verbose error message to be displayed.
     */
    function fatal($msg)
    {
        global $registry, $conf, $notification, $browser, $prefs;

        /* Don't store the bad file in the user's preferences. */
        $prefs->setValue('last_file', '');

        include CHORA_TEMPLATES . '/common-header.inc';
        $notification->push($msg, 'horde.error');
        Chora::menu();
        include $registry->getParam('templates', 'horde') . '/common-footer.inc';
        exit;
    }

    /**
     * Given a return object from a VC:: call, make sure
     * that it's not a PEAR_Error object.
     * @param e Return object from a VC:: call.
     */
    function checkError($e)
    {
        if (is_a($e, 'PEAR_Error')) {
            Chora::fatal($e->getMessage());
        }
    }

    /**
     * Return an array with the names of any of the variables we need
     * to keep, that are different from the defaults.
     *
     * @return array  Names/vals of differing variables.
     */
    function differingVars()
    {
        global $acts, $defaultActs;
        reset($acts);
        $ret = array();
        foreach ($acts as $key => $val) {
            if ($val != $defaultActs[$key]) {
                $ret[$key] = $val;
            }
        }
        return $ret;
    }

    /**
     * Generate a series of hidden input variables based on the GET
     * parameters which are different from the defaults.
     *
     * @param array $except  Array of exceptions to never output.
     *
     * @return string  A set of input tags with the different variables.
     */
    function generateHiddens($except = array())
    {
        global $acts;
        $toOut = Chora::differingVars();
        $ret = Util::formInput() . "\n";
        while (list($key, $val) = each($toOut)) {
            if (is_array($except) && !in_array($key, $except)) {
                $ret .= "<input type=\"hidden\" name=\"$key\" value=\"$val\" />\n";
            }
        }
        return $ret;
    }

    /**
     * Convert a commit-name into whatever the user wants.
     *
     * @param string $name  CVS account name.
     * @return string  The transformed name.
     */
    function showAuthorName($name, $fullname = false)
    {
        global $VC;

        $users = $VC->getUsers($GLOBALS['conf']['paths']['cvsusers']);
        if (is_array($users) && isset($users[$name])) {
            return '<a href="mailto:' . $users[$name]['mail'] . '">' .
                ($fullname ? $users[$name]['name'] : $name) .
                '</a>' . ($fullname ? " <i>($name)</i>" : '');
        } else {
            return $name;
        }
    }

    /**
     * Generate a URL that links into Chora.
     *
     * @param string $script  Name of the Chora script to link into
     * @param string $uri     Any PATH_INFO portion that should be included
     * @param array  $args    Key/value pair of any GET parameters to append
     * @param string $anchor  Anchor entity name
     *
     * @return string  The URL, with session information if necessary.
     */
    function url($script, $uri = '', $args = array(), $anchor = '')
    {
        global $conf;

        $arglist = array_merge(Chora::differingVars(), $args);
        $script = $script . '.php';

        if ($conf['options']['use_path_info']) {
            if (substr($uri, 0, 1) != '/') {
                $script .= '/';
            }
            $script .= $uri;
        } else {
            $arglist['f'] = $uri;
        }

        $url = Horde::applicationUrl($script);

        foreach ($arglist as $key => $val) {
            if (!empty($val) || $val === 0) {
                $url = Util::addParameter($url, $key, $val);
            }
        }

        if (!empty($anchor)) {
            $url .= "#$anchor";
        }

        return $url;
    }

    /**
     * Generate a list of repositories available from this installation
     * of Chora.
     * @return XHTML code representing links to the repositories
     */
    function repositories()
    {
        global $sourceroot, $sourceroots, $defaultActs;

        $arr = array();
        foreach ($sourceroots as $key => $val) {
            if ($sourceroot != $val['location']) {
                $arg = array('rt' => (($defaultActs['rt'] == $key) ? '' : $key));
                $arr[] = '<b><a href="' . Chora::url('cvs', '', $arg) . '">' .
                         $val['name'] . '</a></b>';
            }
        }

        if (sizeof($arr)) {
            return _("Other Repositories") . ': ' . implode(' , ', $arr);
        } else {
            return '';
        }
    }

    /**
     * Pretty-print the checked out copy, using the
     * Horde::Mime::Viewer package.
     *
     * @param string $mime_type File extension of the checked out file
     * @param resource fp File pointer to the head of the checked out copy
     * @return object The MIME_Viewer object which can be rendered or
     *                false on failure
     */
    function &pretty($mime_type, $fp)
    {
        $lns = '';
        while ($ln = fread($fp, 8192)) {
            $lns .= $ln;
        }

        $mime = &new MIME_Part($mime_type, $lns);
        return MIME_Viewer::factory($mime);
    }

    /**
     * Check if the given item is restricted from being shown.
     * @return boolean whether or not the item is allowed to be displayed
     **/
    function isRestricted($item)
    {
        global $conf, $sourceroots, $sourceroot;
        static $restricted;

        if (!isset($restricted)) {
            $restricted = array();
            if (isset($conf['restrictions']) && is_array($conf['restrictions'])) {
                $restricted = $conf['restrictions'];
            }

            foreach ($sourceroots as $key => $val) {
                if ($sourceroot == $val['location']) {
                    if (isset($val['restrictions']) && is_array($val['restrictions'])) {
                        $restricted = array_merge($restricted, $val['restrictions']);
                        break;
                    }
                }
            }
        }

        if (!empty($restricted) && is_array($restricted) && count($restricted)) {
            for ($i = 0; $i < count($restricted); $i++) {
                if (preg_match('|' . str_replace('|', '\|', $restricted[$i]) . '|', $item)) {
                    return true;
                }
            }
        }

        return false;
    }

    function menu()
    {
        global $conf, $registry, $notification;
        require_once HORDE_LIBS . 'Horde/Menu.php';
        require CHORA_TEMPLATES . '/menu/menu.inc';

        $notification->notify(array('listeners' => 'status'));

        /* Include the JavaScript for the help system. */
        Help::javascript();
    }

    function getFileViews()
    {
        global $where;

        $views = array();
        $current = $_SERVER['PHP_SELF'];
        if (!empty($_SERVER['PATH_INFO'])) {
            $current = str_replace($_SERVER['PATH_INFO'], '', $current);
        }
        $current = str_replace('.php', '', basename($current));

        $views[] = $current == 'cvs' ? '<i>' . _("Logs") . '</i>' : Horde::link(Chora::url('cvs', $where), _("Logs"), 'widget') . _("Logs") . '</a>';
        // Subversion supports patchsets natively
        if (!empty($GLOBALS['conf']['paths']['cvsps']) || is_a($GLOBALS['VC'], 'VC_svn')) {
            $views[] = $current == 'patchsets' ? '<i>' . _("Patchsets") . '</i>' : Horde::link(Chora::url('patchsets', $where), _("Patchsets"), 'widget') . _("Patchsets") . '</a>';
        }
        if (!is_a($GLOBALS['VC'], 'VC_svn')) {
            $views[] = $current == 'history' ? '<i>' . _("Branches") . '</i>' : Horde::link(Chora::url('history', $where), _("Branches"), 'widget') . _("Branches") . '</a>';
        }
        if (!empty($GLOBALS['conf']['paths']['cvsgraph']) && !is_a($GLOBALS['VC'], 'VC_svn')) {
            $views[] = $current == 'cvsgraph' ? '<i>' . _("Graph") . '</i>' : Horde::link(Chora::url('cvsgraph', $where), _("Graph"), 'widget') . _("Graph") . '</a>';
        }
        $views[] = $current == 'stats' ? '<i>' . _("Statistics") . '</i>' : Horde::link(Chora::url('stats', $where), _("Statistics"), 'widget') . _("Statistics") . '</a>';

        return _("View:") . ' ' . implode(' | ', $views);
    }

    function formatLogMessage($log)
    {
        global $conf;

        $log = Text::toHTML($log, TEXT_HTML_MICRO, NLS::getCharset(), null);

        if (!empty($conf['tickets']['regexp']) && !empty($conf['tickets']['replacement'])) {
            $log = preg_replace($conf['tickets']['regexp'], $conf['tickets']['replacement'], $log);
        }

        return $log;
    }

}
