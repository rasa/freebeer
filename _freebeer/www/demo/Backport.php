<?php

// $CVSHeader: _freebeer/www/demo/Backport.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

require_once './_demo.php';

require_once FREEBEER_BASE . '/lib/Backport.php';

echo html_header_demo('fbBackport.php Demo');

echo "<pre>\n";

$a = array('green', 'red', 'yellow');
$b = array('avocado', 'apple', 'banana');
$rv = array_combine($a, $b);
print_r($rv);
//		$rv = array('green' => 'avocado', 'red' => 'apple', 'yellow' => 'banana');
$expected = array('green' => 'avocado', 'red' => 'apple', 'yellow' => 'banana');
assert($expected == $rv);

$a1 = array('a' => 'green', 'b' => 'brown', 'c' => 'blue', 'red');
$a2 = array('a' => 'green', 'yellow', 'red');
$result = array_diff_assoc($a1, $a2);
echo 'array_diff_assoc($a1, $a2)=';
print_r($result);

$expected = array(
    'b' => 'brown',
    'c' => 'blue',
    '0' => 'red',
);
echo $result == $expected ? 'ok' : '*FAILED*';
echo "\n\n";

$result = sha1('A');
echo 'sha1(A)=',$result,"\n";

$expected = '6dcd4ce23d88e2ee9568ba546c007c63d9131c1b';
echo $result == $expected ? 'ok' : '*FAILED*';
echo "\n\n";

printf("sha1_file(%s)=", basename(__FILE__));
echo sha1_file(__FILE__);
echo "\n\n";

printf("md5_file(%s)=", basename(__FILE__));
echo md5_file(__FILE__);
echo "\n\n";

$a = array(
	'A' => 'A',
	'b' => 'b',
	'C' => 'C',
	'd' => 'd',
);

echo 'array_change_key_case($a)=';
$a = array_change_key_case($a);
print_r($a);
echo "\n";

echo 'array_change_key_case($a, CASE_UPPER)=';
$a = array_change_key_case($a, CASE_UPPER);
print_r($a);
echo "\n";

$a = array_fill(5, 6, 'banana');
echo 'array_fill(5, 6, banana)=';
print_r($a);
echo "\n";

$input_array = array('a', 'b', 'c', 'd', 'e');
echo 'array_chunk($input_array, 2)=';
print_r(array_chunk($input_array, 2));
echo "\n";

echo 'array_chunk($input_array, 2, TRUE)=';
print_r(array_chunk($input_array, 2, TRUE));
echo "\n";

$v = '122.34343The';
echo "floatval('$v')=",floatval($v),"\n";

$v = '1E02';
echo "floatval('$v')=",floatval($v),"\n";

$v = '+1E02';
echo "floatval('$v')=",floatval($v),"\n";

$v = '-1E02';
echo "floatval('$v')=",floatval($v),"\n";

$v = '1E+02';
echo "floatval('$v')=",floatval($v),"\n";

$v = '1E-02';
echo "floatval('$v')=",floatval($v),"\n";

$v = '+1E+02';
echo "floatval('$v')=",floatval($v),"\n";

$v = '-1E-02';
echo "floatval('$v')=",floatval($v),"\n";

$v = '+1E-02';
echo "floatval('$v')=",floatval($v),"\n";

$v = '-1E+02';
echo "floatval('$v')=",floatval($v),"\n";

?>
</pre>
<address>
$CVSHeader: _freebeer/www/demo/Backport.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $
</address>
</body>
</html>

