<?php

// $CVSHeader: _freebeer/www/demo/lib.crypt.php,v 1.2 2004/03/07 17:51:34 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once './_demo.php';

require_once FREEBEER_BASE . '/lib/Random.php';

echo html_header_demo('JavaScript Crypt Class', '../lib/crypt.js');

$rng = fbRandom::getInstance();

$salt = $rng->nextSalt();

if (!isset($_REQUEST['t_crypt_in'])) {
	$_REQUEST['t_crypt_in'] = 'enter text to encrypt here';
}

if (!@$_REQUEST['t_crypt_salt_in']) {
	$_REQUEST['t_crypt_salt_in'] = $salt;
}

?>
<script type="text/javascript" language="JavaScript" src="../lib/crypt.js"></script>
<script type="text/javascript" language="JavaScript">
<!-- // <![CDATA[
function btn_crypt_onclick(f) {
	var crypted = crypt(f.t_crypt_in.value, f.t_crypt_salt_in.value);
	f.t_crypt_out.value			= crypted;
	f.t_crypt_salt_out.value	= crypted.substr(0, 2);
	return false;
}
// ]]> -->
</script>

<form method="post" name='frm_crypt'>
<table>
<tr>
<td>
Input:
</td>
<td>
<input type="text" name="t_crypt_in" value="<?php echo $_REQUEST['t_crypt_in']; ?>" size="10" maxlength="8" />
</td>
</tr>

<tr>
<td>
Salt:
</td>
<td>
<input type="text" name="t_crypt_salt_in" value="<?php echo $_REQUEST['t_crypt_salt_in']; ?>" size="2" maxlength="2" />
</td>
</tr>


<tr>
<td>
&nbsp;
</td>
<td>
<input type="submit" value="Calculate (PHP)" />
</td>
</tr>

<tr>
<td>
Output (PHP):
</td>
<td>
<?php
$crypt = crypt($_REQUEST['t_crypt_in'], $_REQUEST['t_crypt_salt_in']);
echo $crypt;
?>
</td>
</tr>

<tr>
<td>
Salt (PHP):
</td>
<td>
<?php
echo substr($crypt, 0, 2);
?>
</td>
</tr>

<tr>
<td>
&nbsp;
</td>
<td>
<input type="button" value="Calculate (JavaScript)" name="btn_crypt" onclick="btn_crypt_onclick(this.form);" />
</td>
</tr>
<tr>
<td>
Output:
</td>
<td>
<input type="text" name="t_crypt_out" size="20" maxlength="20" />
</td>
</tr>
<tr>

<td>
Salt:
</td>
<td>
<input type="text" name="t_crypt_salt_out" size="2" maxlength="2" />
</td>
</tr>

</table>
</form>

<script type="text/javascript" language="javascript">
<!-- // <![CDATA[
btn_crypt_onclick(document.frm_crypt);
// ]]> -->
</script>

<p>
See
<br />
<a target='_blank' href='http://locutus.kingwoodcable.com/jfd/crypt.html'>Java Implementation Of Crypt</a>
</p>

<address>
$CVSHeader: _freebeer/www/demo/lib.crypt.php,v 1.2 2004/03/07 17:51:34 ross Exp $
</address>

</body>
</html>
