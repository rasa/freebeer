// $CVSHeader: _freebeer/www/lib/MathUtils.js,v 1.1.1.1 2004/01/18 00:12:08 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

function MathUtils() {

	function round(number, places) {
		places = (!places ? 2 : places);
		return Math.round(number * Math.pow(10, places)) / Math.pow(10, places);
	}

}

new MathUtils();

MathUtils.prototype.round = round;
