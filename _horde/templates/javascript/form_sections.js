/**
 * Horde Form Sections Javascript Class
 *
 * Provides the javascript class for handling tabbed sections in Horde Forms.
 *
 * Copyright 2003-2004 Marko Djukic <marko@oblo.com>
 *
 * See the enclosed file COPYING for license information (LGPL). If you did not
 * receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * $Horde: horde/templates/javascript/form_sections.js,v 1.4 2004/01/01 16:17:43 jan Exp $
 *
 * @author  Marko Djukic <marko@oblo.com>
 * @version $Revision$
 * @package Horde_Form
 */
function Horde_Form_Sections(instanceName, openSection)
{
    /* Set up this class instance for function calls from the page. */
    this._instanceName = instanceName;

    this.getThis = function()
    {
        return this;
    }

    this.toggle = function(sectionId)
    {
        /* Get the currently open section object. */
        openSectionId = this._getCookie(this._instanceName + '_open');
        if (document.getElementById('_section_' + openSectionId)) {
            document.getElementById('_section_' + openSectionId).style.display = 'none';
            document.getElementById('_tab_' + openSectionId).className = 'tab';
            document.getElementById('_tabLink_' + openSectionId).className = 'tab';
        }

        /* Get the newly opened section object. */
        if (document.getElementById('_section_' + sectionId)) {
            document.getElementById('_section_' + sectionId).style.display = 'block';
            document.getElementById('_tab_' + sectionId).className = 'tab-hi';
            document.getElementById('_tabLink_' + sectionId).className = 'tab-hi';
        }

        /* Store the newly opened section object name to cookies. */
        this._setCookie(this._instanceName + '_open', sectionId);
    }

    this._getCookie = function(name)
    {
        var dc = document.cookie;
        var prefix = name + '=';
        var begin = dc.indexOf('; ' + prefix);
        if (begin == -1) {
            begin = dc.indexOf(prefix);
            if (begin != 0) {
                return '';
            }
        } else {
            begin += 2;
        }
        var end = document.cookie.indexOf(";", begin);
        if (end == -1) {
            end = dc.length;
        }
        return unescape(dc.substring(begin + prefix.length, end));
    }

    this._setCookie = function(name, value)
    {
        var curCookie = name + '=' + escape(value);
        curCookie = curCookie + ';DOMAIN=<?php echo $GLOBALS['conf']['cookie']['domain']; ?>;PATH=<?php echo $GLOBALS['conf']['cookie']['path']; ?>;';
        document.cookie = curCookie;
    }

    this._setCookie(this._instanceName + '_open', openSection);
}
