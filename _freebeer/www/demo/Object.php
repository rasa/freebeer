<?php

// $CVSHeader: _freebeer/www/demo/Object.php,v 1.2 2004/03/07 17:51:33 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

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
$CVSHeader: _freebeer/www/demo/Object.php,v 1.2 2004/03/07 17:51:33 ross Exp $
</address>

</body>
</html>
