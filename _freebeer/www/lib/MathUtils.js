// $CVSHeader: _freebeer/www/lib/MathUtils.js,v 1.2 2004/03/08 04:29:18 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

function MathUtils() {
}

MathUtils.round = function(number, places) {
	if (typeof(places) == 'undefined') {
		places = 2;
	};
	return Math.round(number * Math.pow(10, places)) / Math.pow(10, places);
}
