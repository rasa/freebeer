<?php

// $CVSHeader: _freebeer/www/demo/lib.qsort.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

require_once './_demo.php';

echo html_header_demo('JavaScript QSort Class', '../lib/qsort.js');

?>
<script type="text/javascript" language="JavaScript" src="../lib/qsort.js"></script>

<script type="text/javascript" language="JavaScript">
<!-- // <![CDATA[
var x = new Array();
for (var i = 0; i <= 10; ++i) {
	x[i] = Math.floor(65535 * Math.random());
}

document.write('Unsorted: ');

document.write(x.toString());

document.write('<br />Sorted:   ');

qsort(x); // , 0, x.length - 1);

document.write(x.toString());

//document.write('\nlength=' + x.length + "<br />\n");
// ]]> -->
</script>

<br />
<br />

<address>
$CVSHeader: _freebeer/www/demo/lib.qsort.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $
</address>

</body>
</html>
