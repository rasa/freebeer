<?php

// $CVSHeader: _freebeer/www/demo/Object.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

require_once './_demo.php';

require_once FREEBEER_BASE . '/lib/Object.php';

echo html_header_demo('fbObject Class');

echo "<pre>\n";

$o = &new fbObject();

$o->an_int = 1;
$o->a_string = "a string";

$file = $o->persist();

$o2 = $o->unpersist($file);

echo 'toString=', $o2->toString();

echo "</pre>\n";

echo 'toHtml=', $o2->toHtml();

echo "\n<p />\n";

?>
</pre>
<address>
$CVSHeader: _freebeer/www/demo/Object.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $
</address>

</body>
</html>
