<?php

// $CVSHeader: _freebeer/www/demo/Binary_Search.Array.php,v 1.2 2004/03/07 17:51:33 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once './_demo.php';

require_once FREEBEER_BASE . '/lib/BinarySearch/Array.php';

echo html_header_demo('fbBinarySearch_Array Class (Binary Search an Array)');

echo "<pre>";

if (!isset($_REQUEST['elements'])) {
	$_REQUEST['elements'] = 10;
}

if (!isset($_REQUEST['attempts'])) {
	$_REQUEST['attempts'] = 10;
}

$elements = $_REQUEST['elements'];
$attempts = $_REQUEST['attempts'];

$a = array();
for ($i = 0; $i < $elements; ++$i) {
	$a[$i] = sprintf('%010.10d', mt_rand() % 2000000000);
}

sort($a);

// echo 'a=';	print_r($a);

function compare($a, $b) {
	if ($a > $b) {
		return 1;
	}
	if ($a < $b) {
		return -1;
	}

	return 0;
}

?>
<form method="post" name='frm_binary_search_array'>
<table>

<tr>
<td>
Elements:
</td>
<td>
<input type="text" name="elements" value="<?php echo $_REQUEST['elements']; ?>" size="10" maxlength="8" />
</td>
</tr>

<tr>
<td>
Attempts:
</td>
<td>
<input type="text" name="attempts" value="<?php echo $_REQUEST['attempts']; ?>" size="10" maxlength="8" />
</td>
</tr>

<tr>
<td>
&nbsp;
</td>
<td>
<input type="submit" value="Submit" name="btn_submit" />
</td>
</tr>

</table>
</form>
<?php

for ($i = 0; $i < $attempts; ++$i) {
	$n = rand(0, $elements - 1);
	$search_term = $a[$n];
	printf("Searching for '%s' (element   %s)\n", $search_term, $n);

	$rv = fbBinarySearch_Array::search($search_term, $a, false);
	printf("Found         '%s' at element %s\n", $search_term, $rv);
	assert('$a[$rv] == $search_term');
}

?>
<address>
$CVSHeader: _freebeer/www/demo/Binary_Search.Array.php,v 1.2 2004/03/07 17:51:33 ross Exp $
</address>

</body>
</html>

