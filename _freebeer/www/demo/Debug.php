<?php

// $CVSHeader: _freebeer/www/demo/Debug.php,v 1.2 2004/03/07 17:51:33 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once './_demo.php';

require_once FREEBEER_BASE . '/lib/Debug.php';

fbDebug::setLevel(FB_DEBUG_ALL);

echo html_header_demo('fbDebug demo');

echo "<pre>\n";

echo 'fbDebug::getLevel()=',fbDebug::getLevel(),"\n";

fbDebug::log('log');

assert('1 == 2');

print_r(debug_backtrace());

fbDebug::stackdump(); 

fbDebug::trace("Hey you!"); 

?>
</pre>
<address>
$CVSHeader: _freebeer/www/demo/Debug.php,v 1.2 2004/03/07 17:51:33 ross Exp $
</address>

</body>
</html>
