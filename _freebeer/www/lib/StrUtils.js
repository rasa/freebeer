// $CVSHeader: _freebeer/www/lib/StrUtils.js,v 1.3 2004/03/08 04:29:18 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

function StrUtils() {
}

StrUtils.escape = function(s, special_char, escape_char) {
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

StrUtils.isBlank = function(s) {
	if (s) {
		for (var i = 0; i < s.length; ++i) {
			if (s.charAt(i) > ' ') {
				return false;
			}
		}
	}
	return true;
}

StrUtils.isNull = function(s) {
	return (!s || s == null || s == '' || s == 0);
}

StrUtils.lpad = function lpad(s, n, c) {
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

StrUtils.ltrim = function(s, ws) {
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

StrUtils.repeat = function(c, n) {
	var rv = '';

	while (n-- > 0) {
		rv += c;
	}

	return rv;
}

StrUtils.rpad = function(s, n, c) {
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

StrUtils.rtrim = function(s, ws) {
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

StrUtils.trim = function(s, ws) {
	return StrUtils.ltrim(StrUtils.rtrim(s, ws), ws);
}
