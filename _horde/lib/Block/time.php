<?php
/**
 * $Horde: horde/lib/Block/time.php,v 1.9 2004/01/24 20:55:31 chuck Exp $
 *
 * @package Horde_Block
 */
class Horde_Block_time extends Horde_Block {

    var $_app = 'horde';
    var $_type = 'time';

    function getParams()
    {
        return $params = array('time' => array('type' => 'enum',
                                               'name' => _("Time format"),
                                               'default' => '24-hour',
                                               'values' => array('24-hour' => '24 Hour Format',
                                                                 '12-hour' => '12 Hour Format')));
    }

    /**
     * The title to go in this block.
     *
     * @return string   The title text.
     */
    function _title()
    {
        return _("Current Time");
    }

    /**
     * The content to go in this block.
     *
     * @return string   The content
     */
    function _content()
    {
        // Set the timezone variable, if available.
        NLS::setTimeZone();

        $html = '<table width="100%" height="100%"><tr><td style="font-family:verdana;font-size:18px;" align="center" valign="center">';
        $html .= strftime('%A, %B %d, %Y ');
        if ($this->_params['time'] == '24-hour') {
            $html .= strftime('%H:%M');
        } else {
            $html .= strftime('%I:%M %p');
        }
        $html .= '</td></tr></table>';

        return $html;
    }

}
