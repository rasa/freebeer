/*

$CVSHeader

Source: http://www.ohdave.com/rsa/RSA.js

*/

// RSA, a suite of routines for performing RSA public-key computations in
// JavaScript. Requires BigInt.js. Copyright 1998-2003 David Shapiro. You may
// use, re-use, abuse, copy, and modify this code to your liking, but please
// keep this header.
//
// Thanks!
// 
// Dave Shapiro
// dave@ohdave.com 

function RSAKeyPair(encryptionExponent, decryptionExponent, modulus)
{
	this.e = biFromHex(encryptionExponent);
	this.d = biFromHex(decryptionExponent);
	this.m = biFromHex(modulus);
	// We can do two bytes per digit, so
	// chunkSize = 2 * (number of digits in modulus - 1).
	// Note that biNumDigits actually returns the high index, not the
	// number of digits, so since it's zero-based, 1 has already been
	// subtracted. Poor naming convention, admittedly. I'll fix that
	// one of these days.
	this.chunkSize = 2 * biNumDigits(this.m);
}

function twoDigit(n)
{
	return (n < 10 ? "0" : "") + String(n);
}

function encryptedString(key, s)
	// Altered by Rob Saunders (rob@robsaunders.net). New routine pads the
	// string after it has been converted to an array. This fixes an
	// iincompatibility with Flash MX's Actionscript.
{
	var a = new Array();
	var sl = s.length;
	var i = 0;
	while (i < sl) {
		a[i] = s.charCodeAt(i);
		i++;
	}

	while ((a.length % key.chunkSize) != 0) {
		a[i++] = 0;
	}

	var al = a.length;
	var result = "";
	var i, j, k, block;
	for (i = 0; i < al; i += key.chunkSize) {
		block = new BigInt("");
		j = 0;
		for (k = i; k < i + key.chunkSize; ++j) {
			block.digits[j] = a[k++];
			block.digits[j] += a[k++] << 8;
		}
		var crypt = biPowMod(block, key.e, key.m);
		result += biToHex(crypt) + " ";
	}
	return result.substr(0, result.length - 1); // Remove last space.
}

function decryptedString(key, s)
{
	var blocks = s.split(" ");
	var result = "";
	var i, j, block;
	for (i = 0; i < blocks.length; ++i) {
		block = biPowMod(biFromHex(blocks[i]), key.d, key.m);
		for (j = 0; j <= biNumDigits(block); ++j) {
			result += String.fromCharCode(block.digits[j] & 255,
			                              block.digits[j] >> 8);
		}
	}
	return result;
}
