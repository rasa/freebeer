<?php

// $CVSHeader: _freebeer/lib/Config/Container/XML.php,v 1.2 2004/03/07 17:51:18 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(dirname(dirname(__FILE__)))));

require_once FREEBEER_BASE . '/lib/Pear/Pear.php';

require_once 'Config/Container/XML.php';	// /opt/Pear/Config/Container/XML.php

class fbConfig_Container_XML extends Config_Container_XML {
    function fbConfig_Container_XML($options = array()) {
    	$this->Config_Container_XML($options);
    }

	var $_in_comment = false;

	var $comment = '';

	var $_whitespace = '';

	function _addComment($comment) {
//$this->_dump('addComment', $comment);
//$comment = htmlspecialchars($comment);

		$container =& new Config_Container('comment', null, $comment, null);
		$this->containers[] =& $container;

		$count = count($this->containers);
		$container =& $this->containers[$count-1];
		$currentSection =& $this->containers[$count-2];
		$currentSection->addItem($container);
		array_pop($this->containers);
		$this->in_comment = false;
		$this->comment = null;
		$this->cdata = null;
		return null;
	}

	function _dump($label, $v) {
		$l = strlen($v);
		$rv = $label . ": '" . $v . '\' (';
		for ($i = 0; $i < $l; $i++) {
			$rv .= sprintf('%02x ', ord($v{$i}));
		}
		$rv = htmlspecialchars($rv);
		$rv .= ")<br />\n";
		echo $rv;
	}

	function defaultHandler($xp, $data) {
//$this->_dump('defaultHandler', $data);
		// see http://myfluffydog.com/programming/php/scripts/regexp.php
 		if (preg_match('{<!--([!-\xff\s]*)-->}m', $data, $matches)) {
			$comment = $matches[1];
			return $this->_addComment($comment);
		}
 		if (preg_match('/<!--([!-\xff\s]*)/m', $data, $matches)) {
			$this->_in_comment = true;
			$this->comment = $matches[1];
//$this->_dump('$this->comment=',$this->comment);
			return null;
		}

 		if (preg_match('/<!--\s*/', $data)) {
			$this->_in_comment = true;
			return null;
		}

 		if (preg_match('/([!-\xff\s]*)-->/', $data, $matches)) {
			$this->comment .= $matches[1];
			return $this->_addComment($this->comment);
		}

 		if (preg_match('/\s*-->/', $data)) {
			return $this->_addComment($this->comment);
		}

		if ($this->_in_comment) {
			$this->_comment .= $data;
			return null;
		}

		if (method_exists('Config_Container_XML', 'defaultHandler')) {
			return parent::defaultHandler($xp, $data);
		}
		return null;
	}

    function cdataHandler($xp, $cdata) {
//$this->_dump('cdataHandler', $cdata);
		if (!$this->cdata) {
	        $this->cdata = $cdata;
	        return null;
		}

		$nls = preg_match_all('/\s*([\r\n])\s*/m', $this->cdata, $matches);
		$nls += preg_match_all('/\s*([\r\n])\s*/m', $cdata, $matches);

		for ($i = 1; $i < $nls; ++$i) {
			$container =& new Config_Container('blank', null, null, null);
			$this->containers[] =& $container;
			$count = count($this->containers);
			$container =& $this->containers[$count-1];
			$currentSection =& $this->containers[$count-2];
			$currentSection->addItem($container);
			array_pop($this->containers);
			$this->cdata = null;
			return null;
		}

        $this->cdata .= $cdata;
		return null;

    }

/*
    function startHandler($xp, $elem, &$attribs) {
//$this->_dump('startHandler', $elem);
		parent::startHandler($xp, $elem, &$attribs);
    }

    function endHandler($xp, $elem) {
//$this->_dump('endHandler', $elem);
		parent::endHandler($xp, $elem);
	}
*/

}

?>
