// $CVSHeader: _freebeer/www/lib/StrUtils.js,v 1.1.1.1 2004/01/18 00:12:08 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

function StrUtils() {
	/*!
	*/
	function escape(s, special_char, escape_char) {
		var rv = '';
		for (var i = 0; i < s.length; ++i) {
			var c = s.charAt(i);
			if (c == special_char) {
				rv += escape_char;
			}
			rv += c;
		}

		return rv;
	}

	/*!
	*/
	function isBlank(s) {
		if (s) {
			for (var i = 0; i < s.length; ++i) {
				if (s.charAt(i) > ' ') {
					return false;
				}
			}
		}
		return true;
	}

	/*!
	*/
	function isNull(s) {
		return (!s || s == null || s == '' || s == 0);
	}

	/*!
	*/
	function lpad(s, n, c) {
		if (!c) {
			c = ' ';
		}
		var rv = '' + s;
		n -= rv.length;
		while (n-- > 0) {
			rv = c + rv;
		}

		return rv;
	}

	/*!
	*/
	function ltrim(s, ws) {
		if (!s) {
			return '';
		}
		if (!ws) {
			ws = " \t\r\n";
		}
		for (var i = 0; i < s.length; ++i) {
			var c = s.charAt(i);
			if (ws.indexOf(c) == -1) {
				return s.substr(i, s.length);
			}
		}

		return '';
	}

	/*!
	*/
	function repeat(c, n) {
		var rv = '';

		while (n-- > 0) {
			rv += c;
		}

		return rv;
	}

	/*!
	*/
	function rpad(s, n, c) {
		if (!c) {
			c = ' ';
		}
		var rv = '' + s;
		n -= rv.length;
		while (n-- > 0) {
			rv += c;
		}

		return rv;
	}

	/*!
	*/
	function rtrim(s, ws) {
		if (!s) {
			return '';
		}
		if (!ws) {
			ws = ' \t\r\n';
		}
		for (var i = s.length - 1; i >= 0; --i) {
			var c = s.charAt(i);
			if (ws.indexOf(c) == -1) {
				return s.substr(0, i + 1);
			}
		}

		return '';
	}

	/*!
	*/
	function trim(s, ws) {
		return ltrim(rtrim(s, ws), ws);
	}
}

new StrUtils(); // fix Netscape 3 bug

StrUtils.prototype.escape	= StrUtils.escape;
StrUtils.prototype.isBlank	= StrUtils.isBlank;
StrUtils.prototype.isNull	= StrUtils.isNull;
StrUtils.prototype.lpad		= StrUtils.lpad;
StrUtils.prototype.ltrim	= StrUtils.ltrim;
StrUtils.prototype.repeat	= StrUtils.repeat;
StrUtils.prototype.rpad		= StrUtils.rpad;
StrUtils.prototype.rtrim	= StrUtils.rtrim;
StrUtils.prototype.trim		= StrUtils.trim;
