<?php

// $CVSHeader: _freebeer/www/demo/HTML.LockFormFields.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

require_once './_demo.php';

require_once FREEBEER_BASE . '/lib/HTML/LockFormFields.php';

echo html_header_demo('fbHTML_LockFormFields Class (Lock Selected Form Fields)');

$msg = 'Values in v1 and v2 are locked. Values in v3 and v4 can be modified.';

$locked_fields = array(
	'textfield1',
	'textfield2',
);

if (isset($_REQUEST['set'])) {
    $_REQUEST = fbHTML_LockFormFields::set($_REQUEST, $locked_fields);
}
elseif (isset($_REQUEST['check'])) {
    if (fbHTML_LockFormFields::check($_REQUEST, $locked_fields)) {
        $msg = 'Values in v1 and v2 have not been modified.<br>';
    }
    else {
        $msg = '<b>WARNING: v1 and/or v2 have been modified.</b><br>';
    }

//	$msg .= 'hash=' . fbHTML_LockFormFields::_generateHash($_REQUEST, $locked_fields);
//	$msg .= "<br />\n";

}
else {
	$_REQUEST['textfield1'] = 'Value1';
	$_REQUEST['textfield2'] = 'Value2';
	$_REQUEST['textfield3'] = 'Value3';
	$_REQUEST['textfield4'] = 'Value4';
}

?>

<p>This script shows how to protect selected fields in an HTML form.
</p>
<p>
First, enter some values in the following fields and click "set" to set initial values.
</p>
<p>
Then, click "check" to verify that fields v1 and v2 have not been modified.
</p>
<p>
Then, modify any of the fields and click "check" again.
</p>
<form method="post" name="test" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
<p>v1
<input type="text" name="textfield1" value="<?php echo $_REQUEST['textfield1']; ?>" />
<br>
v2
<input type="text" name="textfield2" value="<?php echo $_REQUEST['textfield2']; ?>" />
<br>
v3
<input type="text" name="textfield3" value="<?php echo $_REQUEST['textfield3']; ?>" />
<br>
v4
<input type="text" name="textfield4" value="<?php echo $_REQUEST['textfield4']; ?>" />
</p>
<p>
<input type="submit" name="set" value="set" />
<input type="submit" name="check" value="check" />
<input type="reset" name="reset" value="reset" />
</p>
<p>
<?php echo $msg; ?>
</p>
<p> Digest value:
<?php

echo fbHTML_LockFormFields::getHash();
echo fbHTML_LockFormFields::getHashField();

?>
</p>
</form>

See
<br />
<a target='_blank' href='http://www.zend.com/codex.php?id=626&single=1'>Protect values (GET/POST/COOKIE) set by PHP</a>
</p>

<address>
$CVSHeader: _freebeer/www/demo/HTML.LockFormFields.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $
</address>

</body>
</html>
