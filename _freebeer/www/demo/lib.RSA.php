<?php

// $CVSHeader: _freebeer/www/demo/lib.RSA.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

require_once './_demo.php';

$title = 'JavaScript RSA Class (Public Key Cryptography)';

echo html_header_demo($title, array('../opt/ohdave.com/BigInt.js', '../opt/ohdave.com/RSA.js'));

?>
<!--
	RSA key parameters:

	p1 = 37751404861025150047
	p2 = 64845143588964943

	n = p1 * p2 =
	  = 2447995268898324993537772139997802321 (decimal)
	  = 01D7777C38863AEC21BA2D91EE0FAF51      (hex)

	phi(n) = phi(p1) * phi(p2) = (p1 - 1) * (p2 - 1)
	       = 2447995268898324955721522135383687332 (decimal)
	       = 01D7777C38863AEA14EBDE8CC7AC40A4      (hex)

	e = 23227 (decimal)
	  = 5abb  (hex)

	d = multiplicative inverse of e mod phi(n)
	  = a number such that 23227 * d =~ 1 (mod
	                                       2447995268898324955721522135383687332)
	  =~ 1435260669559451898523945771716323851 (decimal)
	  =~ 1146BD07F0B74C086DF00B37C602A0B       (hex)

-->

<!--
<script type="text/javascript" language="JavaScript" src="../opt/ohdave.com/util.js">
</script>
-->

<script type="text/javascript" language="JavaScript" src="../opt/ohdave.com/BigInt.js">
</script>

<script type="text/javascript" language="JavaScript" src="../opt/ohdave.com/RSA.js">
</script>

<script type="text/javascript" language="JavaScript">
<!-- // <![CDATA[

var key;

function bodyLoad() {
	key = new RSAKeyPair(
		"5ABB",
		"01146BD07F0B74C086DF00B37C602A0B",
		"01D7777C38863AEC21BA2D91EE0FAF51"
	);
}

function showPublicKey() {
	document.frm.txtMessages.value = "Public key:\n" +
		"m = " + biToHex(key.m) + "\n" +
		"e = " + biToHex(key.e);
}

function cmdEncryptClick() {
	with (document.frm) {
		txtMessages.value	= "Encrypting. Please wait...";
		txtCiphertext.value	= encryptedString(key, txtPlaintext.value);
		txtMessages.value	= "Done.";
	}
}

function cmdVerifyClick() {
	with (document.frm) {
		txtMessages.value		= "Decrypting. Please wait...";
		txtVerification.value	= decryptedString(key, txtCiphertext.value);
		txtMessages.value		= "Done.";
	}
}

bodyLoad();

// ]]> -->
</script>

<p>
Ok, so it's not the fastest thing around. But it can be <EM>done</EM>.
The example below uses a 31-hex-digit (124-bit) modulus with my
multiple-precision math library, all written in JavaScript. The
encryption exponent is small-ish, making for faster encryption.
(Presumably, decryption would be handled on the server, where things
aren't as slow.) A truly secure system would certainly need a larger
key, and would be even more painfully slow. Hey, it's the concept that
counts.
</p>
<form name="frm">
	Plaintext
	<br />
	<textarea rows='2' cols='40' name="txtPlaintext">testing...testing...</textarea>
	&nbsp;
	<input type='button' value="Encrypt Plaintext" name="cmdEncrypt"
	onclick="cmdEncryptClick()"
	/>
	<br />
	<br />
	Ciphertext
	<br />
	<textarea rows='2' cols='40' name="txtCiphertext"></textarea>
	&nbsp;
	<input type='button' value="Verify Ciphertext" name="cmdVerify"
	onclick="cmdVerifyClick()"
	/>
	<br />
	<br />
	Verification
	<br />
	<textarea rows='2' cols='40' name="txtVerification"></textarea>
	<br />
	<br />
	Messages
	<br />
	<textarea rows='3' cols='40' name="txtMessages"></textarea>
	&nbsp;
	<input type='button' value="Show Public Key" name="cmdShowPublicKey"
	onclick="showPublicKey()"
	/>
</form>

<p>
See
<br />
<a target='_blank' href='http://www.ohdave.com/rsa'>RSA In JavaScript - Dave Service Pack 2.h3)P</a>
</p>

<address>
$CVSHeader: _freebeer/www/demo/lib.RSA.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $
</address>

</body>

</html>
