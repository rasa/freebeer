// $CVSHeader: _freebeer/www/lib/Base64.js,v 1.3 2004/03/08 04:29:18 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

function Base64() {
}

Base64.encode_map = [
	'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P',
	'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd', 'e', 'f',
	'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v',
	'w', 'x', 'y', 'z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '+', '/', 
];

Base64.decode_map = [
//	00 01 02 03 04 05 06 07 08 09 10 11 12 13 14 15
	-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1, 	//   0- 15
	-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1, 	//  16- 31
	-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,62,-1,-1,-1, 63,	//  32- 47
	52,53,54,55,56,57,58,59,60,61,-1,-1,-1,-1,-1,-1,	//  48- 63
	-1, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9,10,11,12,13,14,	//  64- 79
	15,16,17,18,19,20,21,22,23,24,25,-1,-1,-1,-1,-1,	//  80- 95
	-1,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,	//  96-111
	41,42,43,44,45,46,47,48,49,50,51,-1,-1,-1,-1,-1,	// 112-127
	-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,	// 
	-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,	// 
	-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,	// 
	-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,	// 
	-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,	// 
	-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,	// 
	-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,	// 
	-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,	// 
];

/*!
	\param data string of data to encode
	\return base64 encoded string
*/
Base64.encode = function(data) {
	var len	= data.length;
	var i	= 0;
	var rv	= '';

	while (len > 2) {
		c0 = data.charCodeAt(i++);
		c1 = data.charCodeAt(i++);
		c2 = data.charCodeAt(i++);

		rv +=	Base64.encode_map[c0 >>> 2] +
				Base64.encode_map[((c0 &  3) << 4) + (c1 >>> 4)] +
				Base64.encode_map[((c1 & 15) << 2) + (c2 >>> 6)] +
				Base64.encode_map[  c2 & 63];

		len -= 3;
	}

	if (len != 0) {
		c0 = data.charCodeAt(i++);
		rv += Base64.encode_map[c0 >>> 2];
		if (len > 1) {
			c1 = data.charCodeAt(i++);
			rv +=	Base64.encode_map[((c0 & 3) << 4) + (c1 >>> 4)] +
					Base64.encode_map[((c1 & 15) << 2)] + '=';
		} else {
			rv += Base64.encode_map[(c0 & 3) << 4] + '==';
		}
	}

	return rv;
}

/*!
	\param data string of base64 encoded data to decode
	\return decoded string
*/
Base64.decode = function(data) {
	var n = data.length;
	var rv = '';
	
	for (var i = 0; i < n; ) {
		var a = [];
		for (var j = 0; j < 4 && i < n; i++) {
			var c = data.charCodeAt(i);
			if (c == ' ') {
				c = '+';
			}
			var d = Base64.decode_map[c];
			if (d != -1) {
				a[j++] = d;
			}
		}
		if (j < 4) {
			break;
		}
		rv += String.fromCharCode(
			255 & ((a[0] << 2) + (a[1] >> 4)),
			255 & ((a[1] << 4) + (a[2] >> 2)),
			255 & ((a[2] << 6) + (a[3]     ))
		);
	}
	if (j % 4 > 1) {
		rv += String.fromCharCode(255 & ((a[0] << 2) + (a[1] >> 4)));
	}
	if (j % 4 > 2) {
		rv += String.fromCharCode(255 & ((a[1] << 4) + (a[2] >> 2)));
	}

	return rv;
}
