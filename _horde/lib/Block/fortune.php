<?php
/**
 * $Horde: horde/lib/Block/fortune.php,v 1.6 2003/10/29 03:38:43 chuck Exp $
 *
 * @package Horde_Block
 */
class Horde_Block_fortune extends Horde_Block {

    var $_app = 'horde';
    var $_type = 'fortune';

    /**
     * The title to go in this block.
     *
     * @return string   The title text.
     */
    function _title()
    {
        return _("Fortune");
    }

    /**
     * The content to go in this block.
     *
     * @return string   The content
     */
    function _content()
    {
        return nl2br(shell_exec($this->_params['fortune']));
    }

}
