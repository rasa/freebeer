/*

$CVSHeader

Requires: JavaScript 1.3
Source: http://www.vidwest.com/crypt/

*/

/* Arcfour Encryption, also using MD5/SHA-1 - see Schneier pp397-8,436-45
 *
 * C = H(K)^H(P) + RC4(H(K+H(P)), P) // where H = MD5 or SHA1, defined below
 *     ~1~~~~~~~       ~2~~~~~~~
 *
 * 1. Unique MAC even when encrypting same plaintext (P) with different key (K).
 *    H(P) is retrieved with correct K to verifiably decrypt ciphertext (C).
 * 2. Randomized en/decryption key allows safer re-use of user's K.
 *
 * Possible attack? Knowing C AND P yields H(K), AND H(P') from C', assuming
 * K' == K. The key and P' remain hidden. Also, avoid K == P...
 *
 * MAIN WEAKNESSES: YOU; YOUR PASSWORD; YOUR COMPUTER, without good disk-wiping.
 *
 * Please send bug reports to d@vidwest.net - version 0.1 - 21-Sep-2002
 *
 * Copyright (c) 2002 d@vidwest.net - USE THIS FREE SOURCE AT YOUR OWN RISK.
 */

function RC4(key, msg) { // args and return value are 8bit strings
 for (var i=0, n=key.length, S=[], K=[]; i<256; i++) {
  S[i] = i; K[i] = key.charCodeAt(i % n);
 }
 for (var i=j=0; i<256; i++) { // initialize S(ubstitution)-box with key
  j = (j + S[i] + K[i]) & 255; var x = S[i]; S[i] = S[j]; S[j] = x;
 }
 for (var i=j=l=0, n=msg.length, s=''; l<n; l++) { // en/decrypt msg...
  i = (i + 1) & 255; j = S[i] & 255; var x = S[i]; S[i] = S[j]; S[j] = x;
  s += String.fromCharCode(msg.charCodeAt(l) ^ S[(S[i] + S[j]) & 255]);
 }
 return s;
}

function RC4encrypt(key, msg, hfn) { // [API] optional hfn is MD5s or SHA1s
 if (!hfn) return stob64(RC4(key, msg));
 var md = hfn(msg); // md randomizes key, verifies decrypted msg
 return stob64(sxor(hfn(key),md)+RC4(hfn(key+''+md),msg));
}

function RC4decrypt(key, msg, hfn) { // [API] optional hfn is MD5s or SHA1s
 if (!hfn) return RC4(key, b64tos(msg));
 var m = b64tos(msg), kd = hfn(key), md = sxor(kd,m.substring(0,kd.length));
 msg = RC4(hfn(key+''+md), m.substring(kd.length));
 if (hfn(msg) != md) alert('Warning: decrypted/original message mis-match.');
 return msg;
}

/*
 * RFC1321: derived from RSA Data Security, Inc.'s MD5 Message Digest Algorithm
 *
 * Copyright (c) 2002 d@vidwest.net - USE THIS FREE SOURCE AT YOUR OWN RISK.
 */

function MD5 (s) { return s2h(MD5s(s)); } // [API] standard 32 char hexstring

function MD5s(s) { return li2s(MD5Transforms(s2li(MD5pad(s)))); } // 8bit string

function MD5test(warn) { // best called at startup
 if (MD5('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789') ==
     'd174ab98d277d9f5a5611c2c9f419d9f') return true; // more or less
 if (warn)
  alert('MD5 ERROR:\nScripts on this page might not run properly in this\n' +
        'web browser. Maybe you should try a newer version?.');
 return false;
}

function FF(a,b,c,d,x,s,ac) { return rotl(a+((b&c)|((~b)&d))+x+ac,s)+b; }
function GG(a,b,c,d,x,s,ac) { return rotl(a+((b&d)|(c&(~d)))+x+ac,s)+b; }
function HH(a,b,c,d,x,s,ac) { return rotl(a+(b^c^d)         +x+ac,s)+b; }
function II(a,b,c,d,x,s,ac) { return rotl(a+(c^(b|(~d)))    +x+ac,s)+b; }

function MD5Transforms(x) {
 var st = [0x67452301, 0xefcdab89, 0x98badcfe, 0x10325476];
 for(i = 0, n = x.length, m = 0xffffffff; i < n; i += 16) {
  var a = st[0], b = st[1], c = st[2], d = st[3];
  a = FF(a,b,c,d,x[i+ 0], 7,0xd76aa478); d = FF(d,a,b,c,x[i+ 1],12,0xe8c7b756);
  c = FF(c,d,a,b,x[i+ 2],17,0x242070db); b = FF(b,c,d,a,x[i+ 3],22,0xc1bdceee);
  a = FF(a,b,c,d,x[i+ 4], 7,0xf57c0faf); d = FF(d,a,b,c,x[i+ 5],12,0x4787c62a);
  c = FF(c,d,a,b,x[i+ 6],17,0xa8304613); b = FF(b,c,d,a,x[i+ 7],22,0xfd469501);
  a = FF(a,b,c,d,x[i+ 8], 7,0x698098d8); d = FF(d,a,b,c,x[i+ 9],12,0x8b44f7af);
  c = FF(c,d,a,b,x[i+10],17,0xffff5bb1); b = FF(b,c,d,a,x[i+11],22,0x895cd7be);
  a = FF(a,b,c,d,x[i+12], 7,0x6b901122); d = FF(d,a,b,c,x[i+13],12,0xfd987193);
  c = FF(c,d,a,b,x[i+14],17,0xa679438e); b = FF(b,c,d,a,x[i+15],22,0x49b40821);
  a = GG(a,b,c,d,x[i+ 1], 5,0xf61e2562); d = GG(d,a,b,c,x[i+ 6], 9,0xc040b340);
  c = GG(c,d,a,b,x[i+11],14,0x265e5a51); b = GG(b,c,d,a,x[i+ 0],20,0xe9b6c7aa);
  a = GG(a,b,c,d,x[i+ 5], 5,0xd62f105d); d = GG(d,a,b,c,x[i+10], 9,0x02441453);
  c = GG(c,d,a,b,x[i+15],14,0xd8a1e681); b = GG(b,c,d,a,x[i+ 4],20,0xe7d3fbc8);
  a = GG(a,b,c,d,x[i+ 9], 5,0x21e1cde6); d = GG(d,a,b,c,x[i+14], 9,0xc33707d6);
  c = GG(c,d,a,b,x[i+ 3],14,0xf4d50d87); b = GG(b,c,d,a,x[i+ 8],20,0x455a14ed);
  a = GG(a,b,c,d,x[i+13], 5,0xa9e3e905); d = GG(d,a,b,c,x[i+ 2], 9,0xfcefa3f8);
  c = GG(c,d,a,b,x[i+ 7],14,0x676f02d9); b = GG(b,c,d,a,x[i+12],20,0x8d2a4c8a);
  a = HH(a,b,c,d,x[i+ 5], 4,0xfffa3942); d = HH(d,a,b,c,x[i+ 8],11,0x8771f681);
  c = HH(c,d,a,b,x[i+11],16,0x6d9d6122); b = HH(b,c,d,a,x[i+14],23,0xfde5380c);
  a = HH(a,b,c,d,x[i+ 1], 4,0xa4beea44); d = HH(d,a,b,c,x[i+ 4],11,0x4bdecfa9);
  c = HH(c,d,a,b,x[i+ 7],16,0xf6bb4b60); b = HH(b,c,d,a,x[i+10],23,0xbebfbc70);
  a = HH(a,b,c,d,x[i+13], 4,0x289b7ec6); d = HH(d,a,b,c,x[i+ 0],11,0xeaa127fa);
  c = HH(c,d,a,b,x[i+ 3],16,0xd4ef3085); b = HH(b,c,d,a,x[i+ 6],23,0x04881d05);
  a = HH(a,b,c,d,x[i+ 9], 4,0xd9d4d039); d = HH(d,a,b,c,x[i+12],11,0xe6db99e5);
  c = HH(c,d,a,b,x[i+15],16,0x1fa27cf8); b = HH(b,c,d,a,x[i+ 2],23,0xc4ac5665);
  a = II(a,b,c,d,x[i+ 0], 6,0xf4292244); d = II(d,a,b,c,x[i+ 7],10,0x432aff97);
  c = II(c,d,a,b,x[i+14],15,0xab9423a7); b = II(b,c,d,a,x[i+ 5],21,0xfc93a039);
  a = II(a,b,c,d,x[i+12], 6,0x655b59c3); d = II(d,a,b,c,x[i+ 3],10,0x8f0ccc92);
  c = II(c,d,a,b,x[i+10],15,0xffeff47d); b = II(b,c,d,a,x[i+ 1],21,0x85845dd1);
  a = II(a,b,c,d,x[i+ 8], 6,0x6fa87e4f); d = II(d,a,b,c,x[i+15],10,0xfe2ce6e0);
  c = II(c,d,a,b,x[i+ 6],15,0xa3014314); b = II(b,c,d,a,x[i+13],21,0x4e0811a1);
  a = II(a,b,c,d,x[i+ 4], 6,0xf7537e82); d = II(d,a,b,c,x[i+11],10,0xbd3af235);
  c = II(c,d,a,b,x[i+ 2],15,0x2ad7d2bb); b = II(b,c,d,a,x[i+ 9],21,0xeb86d391);
  st[0] = (st[0] + a) & m; st[1] = (st[1] + b) & m;
  st[2] = (st[2] + c) & m; st[3] = (st[3] + d) & m;
 }
 return st; // ('& m' above to prevent int overflow)
}

function MD5pad(s) { // pad and append string length in bits, max 2**32-1
 var n = s.length;
 return s + li2s([0x80,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]).substring(0,
  64-((n+8)&63)) + li2s([n<<3,0]);
} // note sha1 is the same, but with big-endian ints ...+ li2s([0,n<<3]

/*
 * MD5 encryption functions - slow! - CFB mode - see Schneier pp351,209
 */

// version 0.2

function MD5encrypt(key, msg) { // [API]
 var iv = s = sxor(MD5s(key), MD5s(msg));
 for (var i = 0, n = msg.length; i < n; i +=16) {
  iv = sxor(MD5s(key + '' + iv), msg.substring(i, i + 16)); s += '' + iv;
 }
 return stob64(s);
}

function MD5decrypt(key, msg) { // [API]
 var m = b64tos(msg), iv = mac = m.substring(0,16), s = '', m = m.substring(16);
 for (var i = 0, n = m.length; i < n; i +=16) {
  var b = m.substring(i, i + 16); s += sxor(MD5s(key + '' + iv), b); iv = b;
 }
 if (MD5s(s) != sxor(MD5s(key), mac))
  alert('Warning: decrypted/original message mis-match.');
 return s;
}

/*
 * FIPS PUB 180-1: Secure Hash Standard, SHA-1 - see Schneier pp442-5
 *
 * Copyright (c) 2002 d@vidwest.net - USE THIS FREE SOURCE AT YOUR OWN RISK.
 */

function SHA1 (s) { return s2h(SHA1s(s)); } // [API] standard 40 char hexstring

function SHA1s(s) { return bi2s(SHA1Transforms(s2bi(SHA1pad(s)))); } // 8bit str

function SHA1test(warn) { // best called at startup
 if (SHA1('abcdbcdecdefdefgefghfghighijhijkijkljklmklmnlmnomnopnopq') ==
     '84983e441c3bd26ebaae4aa1f95129e5e54670f1') return true; // more or less
 if (warn)
  alert('SHA1 ERROR:\nScripts on this page might not run properly in this\n' +
        'web browser. Maybe you should try a newer version?.');
 return false;
}

function SHA1Transforms(m) {
 var h = [0x67452301, 0xefcdab89, 0x98badcfe, 0x10325476, 0xc3d2e1f0];
 for (var i = 0, n = m.length, x=0xffffffff, w = []; i < n; i += 16) {
  var a = h[0], b = h[1], c = h[2], d = h[3], e = h[4];
  for (var t = 0; t < 80; t++) {
   w[t] = t < 16 ? m[i+t] : rotl(w[t-3]^w[t-8]^w[t-14]^w[t-16], 1);
   var temp = rotl(a, 5) + e + w[t] +
   (t<20 ? ((b&c)|(~b&d))      + 0x5a827999 : t<40 ? (b^c^d) + 0x6ed9eba1 :
    t<60 ? ((b&c)|(b&d)|(c&d)) + 0x8f1bbcdc :        (b^c^d) + 0xca62c1d6);
   e = d; d = c; c = rotl(b, 30); b = a; a = temp;
  }
  h[0] = (h[0] + a) & x; h[1] = (h[1] + b) & x; h[2] = (h[2] + c) & x;
  h[3] = (h[3] + d) & x; h[4] = (h[4] + e) & x;
 }
 return h; // ('& 0xffffffff' above to prevent int overflow)
}

function SHA1pad(s) { // pad and append string length in bits, max 2**32-1
 var n = s.length;
 return s + bi2s([0x80000000,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]).substring(0,
  64-((n+8)&63)) + bi2s([0,n<<3]);
} // note md5 is the same, but with little-endian ints...

/*
 * SHA-1 encryption functions - slow! - CFB mode - see Schneier pp351,209
 */

// version 0.2

function SHA1encrypt(key, msg) { // [API]
 var iv = s = sxor(SHA1s(key), SHA1s(msg));
 for (var i = 0, n = msg.length; i < n; i +=20) {
  iv = sxor(SHA1s(key + '' + iv), msg.substring(i, i + 20)); s += '' + iv;
 }
 return stob64(s);
}

function SHA1decrypt(key, msg) { // [API]
 var m = b64tos(msg), iv = mac = m.substring(0,20), s = '', m = m.substring(20);
 for (var i = 0, n = m.length; i < n; i +=20) {
  var b = m.substring(i, i + 20); s += sxor(SHA1s(key + '' + iv), b); iv = b;
 }
 if (SHA1s(s) != sxor(SHA1s(key), mac))
  alert('Warning: decrypted/original message mis-match.');
 return s;
}

// Pick-and-Mix Conversions and Common Functions...

function stob64(s) { // [binary mode: no lf->crlf's - see rfc2045]
 var B = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
 for (var i = c = 0, n = s.length, b = ''; ; ) {
  var x = s.charCodeAt(i++), y = s.charCodeAt(i++), z = s.charCodeAt(i++);
  if (i > n) break;
  b += B.charAt(63&(x>>2)) + B.charAt(63&((x<<4)+(y>>4)))+
       B.charAt(63&((y<<2)+(z>>6))) + B.charAt(63&z);
  if (++c > 18) { b += '\n'; c = 0; }
 }
 if      (i - n == 1)
  b += B.charAt(63&(x>>2))+B.charAt(63&((x<<4)+(y>>4)))+B.charAt(63&(y<<2))+'=';
 else if (i - n == 2)
  b += B.charAt(63&(x>>2))+B.charAt(63&(x<<4))+'==';
 return b;
} // used by all en/decrypt functions

function b64tos(b) {
 var B = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
 for (var i = 0, n = b.length, s = '', a = []; i < n; ) {
  for (var j = 0; j < 4 && i < n; i++) {
   var c = B.indexOf(b.charAt(i));
   if (c != -1) a[j++] = c;
  }
  if (j < 4) break;
  s += String.fromCharCode(
   255&((a[0]<<2)+(a[1]>>4)), 255&((a[1]<<4)+(a[2]>>2)), 255&(a[2]<<6)+(a[3]));
 }
 if (j % 4 > 1) s += String.fromCharCode(255&((a[0]<<2)+(a[1]>>4)));
 if (j % 4 > 2) s += String.fromCharCode(255&((a[1]<<4)+(a[2]>>2)));
 return s;
} // used by all en/decrypt functions

function sxor(a,b,v) { // xor strings a^b, repeating the shorter if v(igenere)
 var l = a.length, m = b.length, n = (l>m&&v)||(l<m&&!v) ? l : m;
 for (var i = 0, s = ''; i < n; i++)
  s += String.fromCharCode(a.charCodeAt(i%l) ^ b.charCodeAt(i%m));
 return s; // 8bit
} // used by all en/decrypt functions, except RC4()

function rotl(x,n) { return (x<<n)|(x>>>(32-n)); } // used by MD5/SHA-1

function s2h(s) { // 8bit string to hexstring
 for (var i = 0, n = s.length, x = '0123456789abcdef', h = ''; i < n; i++) {
  var c = s.charCodeAt(i); h += x.charAt((c & 255) >> 4) + x.charAt(c & 15);
 }
 return h;
} // used by MD5/SHA-1

function h2s(h) { // hexstring to 8bit string
 for (var i = 0, n = h.length, x = '0123456789abcdef', s = ''; i < n; i+=2) {
  s+=String.fromCharCode((x.indexOf(h.charAt(i))<<4)+x.indexOf(h.charAt(i+1)));
 }
 return s;
} // unused

function li2s(a) { // little-endian 32bit int array to 8bit string
 for(var i = 0, n = a.length, m = 255, s = ''; i < n; i++)
  s+=String.fromCharCode(a[i]&m, (a[i]>>>8)&m, (a[i]>>>16)&m, (a[i]>>>24)&m);
//s+=String.fromCharCode((a[i]>>>24)&m, (a[i]>>>16)&m, (a[i]>>>8)&m, a[i]&m);
 return s;
} // used by MD5

function s2li(s) { // 8bit string to little-endian 32bit int array
 for (var i = 0, n = s.length, a = []; i < n; i++)
  a[i >>> 2] |= (s.charCodeAt(i) & 255) << ((i & 3) << 3);
//a[i >>> 2] |= (s.charCodeAt(i) & 255) << ((3 - (i & 3)) << 3);
 return a;
} // used by MD5

function bi2s(a) { // array of big-endian 32bit ints to 8bit string
 for(var i = 0, n = a.length, m = 255, s = ''; i < n; i++)
  s+=String.fromCharCode((a[i]>>>24)&m, (a[i]>>>16)&m, (a[i]>>>8)&m, a[i]&m);
 return s;
} // used by SHA-1

function s2bi(s) { // 8bit string to big-endian 32bit int array
 for (var i = 0, n = s.length, a = []; i < n; i++)
  a[i >>> 2] |= (s.charCodeAt(i) & 255) << ((3 - (i & 3)) << 3);
 return a;
} // used by SHA-1
