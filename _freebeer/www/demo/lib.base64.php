<?php

// $CVSHeader: _freebeer/www/demo/lib.base64.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

require_once './_demo.php';

echo html_header_demo('JavaScript Base64 Class', '../lib/Base64.js');

if (!isset($_REQUEST['t_input'])) {
	$_REQUEST['t_input'] = 'enter text to base64 encode here';
}

if (!isset($_REQUEST['t_base64encode'])) {
	$_REQUEST['t_base64encode'] = '';
}

if (!isset($_REQUEST['t_base64decode'])) {
	$_REQUEST['t_base64decode'] = '';
}

?>
<script type="text/javascript" language="javascript1.2" src="/lib/Base64.js">
</script>
<script type="text/javascript" language="javascript1.2">
<!-- // <![CDATA[
function btn_base64_onclick(f) {
	f.t_base64encode.value = Base64.encode(f.t_input.value);
	f.t_base64decode.value = Base64.decode(f.t_base64encode.value);
	return false;
}

// ]]> -->
</script>

<form method="post" name='frm_base64'>
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
Base64 Encode (PHP):
</td>
<td>
<?php
$base64_encoded = base64_encode($_REQUEST['t_input']);
echo $base64_encoded;
?>
</td>
</tr>
<tr>
<td>
Base64 Decode (PHP):
</td>
<td>
<?php
echo base64_decode($base64_encoded);
?>
</td>
</tr>

<tr>
<td>
&nbsp;
</td>
<td>
<input type="button" value="Calculate (JavaScript)" name='btn_base64' onclick="btn_base64_onclick(this.form);" />
</td>
</tr>
<tr>
<tr>
<td>
Base64 Encode:
</td>
<td>
<input type="text" name="t_base64encode" value="<?php echo $_REQUEST['t_base64encode']; ?>" size="80" />
</td>
</tr>
<tr>
<td>
Base64 Decode:
</td>
<td>
<input type="text" name="t_base64decode" value="<?php echo $_REQUEST['t_base64decode']; ?>" size="80" />
</td>
</tr>

</table>
</form>

<script type="text/javascript" language="javascript1.2">
<!-- // <![CDATA[
btn_base64_onclick(document.frm_base64);
// ]]> -->
</script>

<address>
$CVSHeader: _freebeer/www/demo/lib.base64.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $
</address>

</body>
</html>
