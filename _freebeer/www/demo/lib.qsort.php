<?php

// $CVSHeader: _freebeer/www/demo/lib.qsort.php,v 1.2 2004/03/07 17:51:34 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

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
$CVSHeader: _freebeer/www/demo/lib.qsort.php,v 1.2 2004/03/07 17:51:34 ross Exp $
</address>

</body>
</html>
