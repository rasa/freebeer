<?php

// $CVSHeader: _freebeer/lib/TicketAgent.php,v 1.2 2004/03/07 17:51:17 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

/*!
	\file TicketAgent.php
	\brief Disallows submitting the same HTML form data more than once.
*/

/*!
	\class fbTicket
	\brief Ticket used by fbTicketAgent class
*/
class fbTicket {
	/*!
		Constructor
		
		\param $name \c string
	*/
	function fbTicket($name) {
		global $_SERVER; // < 4.1.0

		$this->_count			= 0;
		$this->_ip				= isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
		$this->_name			= $name;
		$this->_time			= time();
		$this->_previous_time	= 0;
	}

	/*!
	*/
	var $_count;

	/*!
	*/
	var $_ip;

	/*!
	*/
	var $_name;

	/*!
	*/
	var $_time;
	
	/*!
	*/
	var $_previous_time;
}

/*!
	\class fbTicketAgent
	\brief Disallows submitting the same HTML form data more than once.

	The fbTicketAgent class is used to disallow resubmitting the same form
	(either by clicking the submit button more than once, 
	or clicking the Back button in the browser, and resubmitting the page again).
	
	\static
*/
class fbTicketAgent {
	/*!
		\private
		\static
	*/
	function &_init() {
		static $_init = null;

		return $_init;
	}

	/*!
		\private
		\static
	*/
	function &_ticket_name() {
		static $_ticket_name = null;

		return $_ticket_name;
	}

	/*!
		\private
		\static
	*/
	function &_session_field_name() {
		static $_session_field_name = 'fbTicket';
		
		return $_session_field_name;
	}

	/*!
		\private
		\static
	*/
	function _isInitialized() {
		if (!fbTicketAgent::_init()) {
			trigger_error("You must call fbTicketAgent::issueTicket() before calling any other fbTicketAgent functions", E_USER_FATAL);
			exit(1);
		}
		
		return true;
	}
	
	/*!
		\static
	*/
	function issueTicket($ticket_name = null, $session_field_name = null) {
//		global $_SESSION; 	// < 4.1.0	// fails in 4.3.4
//		isset($_SESSION) || $_SESSION = &$GLOBALS['HTTP_SESSION_VARS'];	// < 4.1.0
//		global $_SERVER; 	// < 4.1.0

		$_init = &fbTicketAgent::_init();

		$_init = true;
		
		$_ticket_name = &fbTicketAgent::_ticket_name();

		$_ticket_name = !is_null($ticket_name) ? $ticket_name : $_SERVER['PHP_SELF'];

		$_session_field_name = &fbTicketAgent::_session_field_name();
		
		if (!is_null($session_field_name)) {
			$_session_field_name = $session_field_name;
		}

		if (!isset($_SESSION[$_session_field_name])) {
			$ticket = new fbTicket($_ticket_name);
		} else {
			$ticket = unserialize($_SESSION[$_session_field_name]);
			if ($ticket->_name != $_ticket_name) {
				$ticket = new fbTicket($_ticket_name);
			} else {
				$ticket->_previous_time = $ticket->_time;
				$ticket->_time = time();
			}
		}

		$_SESSION[$_session_field_name] = serialize($ticket);
		
		return true;
	}
	
	/*!
		\static
	*/
	function isTicketValid() {
//		global $_SESSION; 	// < 4.1.0	// fails in 4.3.4
//		isset($_SESSION) || $_SESSION = &$GLOBALS['HTTP_SESSION_VARS'];	// < 4.1.0

		fbTicketAgent::_isInitialized();
		
		$_ticket_name = &fbTicketAgent::_ticket_name();
		$_session_field_name = &fbTicketAgent::_session_field_name();

		$ticket = unserialize($_SESSION[$_session_field_name]);
		if ($ticket->_name != $_ticket_name) {
			return true;
		}

		return $ticket->_count < 1;
	}
	
	/*!
		\static
	*/
	function invalidateTicket() {
//		global $_SESSION; 	// < 4.1.0	// fails in 4.3.4
//		isset($_SESSION) || $_SESSION = &$GLOBALS['HTTP_SESSION_VARS'];	// < 4.1.0

		fbTicketAgent::_isInitialized();
		$_session_field_name = &fbTicketAgent::_session_field_name();
		$ticket = unserialize($_SESSION[$_session_field_name]);
		++$ticket->_count;
		$_SESSION[$_session_field_name] = serialize($ticket);

		return true;
	}

	/*!
		\static
	*/
	function getTicketName() {
//		global $_SESSION; 	// < 4.1.0	// fails in 4.3.4
//		isset($_SESSION) || $_SESSION = &$GLOBALS['HTTP_SESSION_VARS'];	// < 4.1.0

		fbTicketAgent::_isInitialized();
		$_session_field_name = &fbTicketAgent::_session_field_name();
		$ticket = unserialize($_SESSION[$_session_field_name]);
		return $ticket->_name;
	}
	
	/*!
		\static
	*/
	function getTicketCount() {
//		global $_SESSION; 	// < 4.1.0	// fails in 4.3.4
//		isset($_SESSION) || $_SESSION = &$GLOBALS['HTTP_SESSION_VARS'];	// < 4.1.0

		fbTicketAgent::_isInitialized();
		$_session_field_name = &fbTicketAgent::_session_field_name();
		$ticket = unserialize($_SESSION[$_session_field_name]);
		return $ticket->_count;
	}
	
	/*!
		\static
	*/
	function getTicketIPAddress() {
//		global $_SESSION; 	// < 4.1.0	// fails in 4.3.4
//		isset($_SESSION) || $_SESSION = &$GLOBALS['HTTP_SESSION_VARS'];	// < 4.1.0

		fbTicketAgent::_isInitialized();
		$_session_field_name = &fbTicketAgent::_session_field_name();
		$ticket = unserialize($_SESSION[$_session_field_name]);
		return $ticket->_ip;
	}
	
	/*!
		\static
	*/
	function getTicketTime() {
//		global $_SESSION; 	// < 4.1.0	// fails in 4.3.4
//		isset($_SESSION) || $_SESSION = &$GLOBALS['HTTP_SESSION_VARS'];	// < 4.1.0

		fbTicketAgent::_isInitialized();
		$_session_field_name = &fbTicketAgent::_session_field_name();
		$ticket = unserialize($_SESSION[$_session_field_name]);
		return $ticket->_previous_time;
	}
}

?>
