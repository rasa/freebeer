// $CVSHeader: _freebeer/www/lib/qsort.js,v 1.2 2004/03/07 17:51:35 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

/// \todo pass the compare function as an optional parameter to qsort

/*!
	\param a \c mixed
	\param b \c mixed
	\return \c int
*/
function cmp(a, b) {
	return (a > b) ? 1 : ((a < b) ? -1 : 0);
}

/*!
	\param v \c array
	\param lb \c int (optional)
	\param hb \c int (optional)
	\return \c void
*/
function qsort(v, lb, hb) {
	if (!lb) {
		lb = 0;
	}
	
	if (!hb) {
		hb = v.length - 1;
	}

	if (hb - lb == 1) {
		if (cmp(v[lb], v[hb]) > 0) {
//		if (v[lb] > v[hb]) {
			var t = v[lb];
			v[lb] = v[hb];
			v[hb] = t;
		}
		return;
	}

	var x = parseInt((lb + hb) / 2);
	var p = v[x];
	v[x] = v[lb];
	v[lb] = p;
	var ls = lb + 1;
	var hs = hb;

	do {
		while (ls <= hs && (cmp(v[ls], p) <= 0)) {
//		while (ls <= hs && v[ls] <= p) {
			++ls;
		}

		while (cmp(v[hs], p) > 0) {
//		while (v[hs] > p) {
			--hs;
		}

		if (ls < hs) {
			t = v[ls];
			v[ls] = v[hs];
			v[hs] = t;
		}
	} while (ls < hs);

	v[lb] = v[hs];
	v[hs] = p;

	if (lb < hs - 1) {
		qsort(v, lb, hs - 1);
	}

	if (hs + 1 < hb) {
		qsort(v, hs + 1, hb);
	}
}
