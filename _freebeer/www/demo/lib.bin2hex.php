<?php

// $CVSHeader: _freebeer/www/demo/lib.bin2hex.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

require_once './_demo.php';

echo html_header_demo('JavaScript Bin2Hex Class', '../lib/bin2hex.js');

if (!isset($_REQUEST['t_input'])) {
	$_REQUEST['t_input'] = 'enter text to bin2hex here';
}

if (!isset($_REQUEST['t_bin2hex'])) {
	$_REQUEST['t_bin2hex'] = '';
}

if (!isset($_REQUEST['t_hex2bin'])) {
	$_REQUEST['t_hex2bin'] = '';
}

?>
<script type="text/javascript" language="javascript1.2" src="../lib/bin2hex.js">
</script>
<script type="text/javascript" language="javascript1.2">
<!-- // <![CDATA[
function btn_bin2hex_onclick(f) {
	f.t_bin2hex.value = bin2hex(f.t_input.value);
	f.t_hex2bin.value = hex2bin(f.t_bin2hex.value);
	return false;
}
// ]]> -->
</script>

<form method="post" name='frm_bin2hex'>
<table>

<tr>
<td>
Input:
</td>
<td>
<input type="text" name="t_input" value="<?php echo $_REQUEST['t_input']; ?>" size="80" />
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
Bin2Hex (PHP):
</td>
<td>
<?php
$bin2hex = bin2hex($_REQUEST['t_input']);
echo $bin2hex;
?>
</td>
</tr>
<tr>
<td>
Hex2Bin (PHP):
</td>
<td>
<?php
echo pack('H*', $bin2hex);
?>
</td>
</tr>

<tr>
<td>
&nbsp;
</td>
<td>
<input type="button" value="Calculate (JavaScript)" name='btn_bin2hex' onclick="btn_bin2hex_onclick(this.form);" />
</td>
</tr>
<tr>

<tr>
<td>
Bin2Hex:
</td>
<td>
<input type="text" name="t_bin2hex" value="<?php echo $_REQUEST['t_bin2hex']; ?>" size="80" />
</td>
</tr>
<tr>
<td>
Hex2Bin:
</td>
<td>
<input type="text" name="t_hex2bin" value="<?php echo $_REQUEST['t_hex2bin']; ?>" size="80" />
</td>
</tr>

</table>
</form>

<script type="text/javascript" language="javascript1.2">
<!-- // <![CDATA[
btn_bin2hex_onclick(document.frm_bin2hex);
// ]]> -->
</script>

<address>
$CVSHeader: _freebeer/www/demo/lib.bin2hex.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $
</address>

</body>
</html>
