<?php

// $CVSHeader: _freebeer/www/demo/lib.arcfour.php,v 1.2 2004/03/07 17:51:34 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

// See doc/license.txt or http://freebeer.sf.net/license.txt for details.

require_once './_demo.php';

echo html_header_demo('JavaScript Arcfour/MD5/SHA-1 Encryption', '../opt/vidwest.com/arcfour.js');

?>

<script type="text/javascript" language="javascript1.3" src="/opt/vidwest.com/arcfour.js">
</script>
<noscript>Sorry, this program needs a JavaScript 1.3 enabled browser!</noscript>

<script type="text/javascript" language="JavaScript">
<!-- // <![CDATA[
/* top-level functions  - testing... */
function initialize() {
	MD5test(1);
	SHA1test(1);
}

function digest(f) {
	if (f.h[0].checked) {
		f.k.value = MD5(f.t.value);
	} else {
		f.k.value = SHA1(f.t.value);
	}
}
function encrypt(f) {
	var key = f.k.value.substring(0,8);
	var msg = f.t.value;
	
	if      (f.a.selectedIndex == 0) f.t.value = RC4encrypt(key, msg);
	else if (f.a.selectedIndex == 1) f.t.value = RC4encrypt(key, msg, MD5s);
	else if (f.a.selectedIndex == 2) f.t.value = RC4encrypt(key, msg, SHA1s);
	else if (f.a.selectedIndex == 3) f.t.value = MD5encrypt(key, msg);
	else if (f.a.selectedIndex == 4) f.t.value = SHA1encrypt(key, msg);
}
function decrypt(f) {
	var key = f.k.value.substring(0,8);
	var msg = f.t.value;
	
	if      (f.a.selectedIndex == 0) f.t.value = RC4decrypt(key, msg);
	else if (f.a.selectedIndex == 1) f.t.value = RC4decrypt(key, msg, MD5s);
	else if (f.a.selectedIndex == 2) f.t.value = RC4decrypt(key, msg, SHA1s);
	else if (f.a.selectedIndex == 3) f.t.value = MD5decrypt(key, msg);
	else if (f.a.selectedIndex == 4) f.t.value = SHA1decrypt(key, msg);
}

initialize();

// ]]> -->
</script>

<form onsubmit="return(false)";>
Message:
<br />
<textarea name="t" cols="80" rows="20">testing...testing...</textarea>
<br />
Key:
<br />
<input name="k" maxlength="256" size="45" />
<input type="radio" name="h" value="md5" checked="checked" />MD5
<input type="radio" name="h" value="sha1" />SHA-1
<input type="button" value="Digest" onclick="digest(form)"><br>
Algorithm:
<br />
<select name="a">
	<option value="rc4" />Arcfour (Fastest)
	<option value="rc4md5" selected="selected"  />Arcfour, MD5 Message Authentication
	<option value="rc4sha1" />Arcfour, SHA-1 Message Authentication
	<option value="md5" />MD5 with Message Authentication
	<option value="sha1" />SHA-1 with Message Authentication (Slowest)
</select>
<input type="button" value="Encrypt" onclick="encrypt(form)" />
<input type="button" value="Decrypt" onclick="decrypt(form)" />
<input type="reset" />
</form>

<p>
This program is designed for low-to-medium security storage of sensitive
data. The (base-64 encoded) ciphertext can also be pasted into emails, where the
key has been previously agreed by sender and recipient(s).
</p>

<p>
For maximum efficiency, and for encrypting binary data, it's recommended to
encrypt uuencoded zipfiles (via WinZip's Shift+U, etc.) .
</p>

<p>
See
<br />
<a target='_blank' href='http://www.vidwest.com/crypt/'>JavaScript Arcfour/MD5/SHA-1 Encryption </a>
</p>

<address>
$CVSHeader: _freebeer/www/demo/lib.arcfour.php,v 1.2 2004/03/07 17:51:34 ross Exp $
</address>

</body>
</html>
