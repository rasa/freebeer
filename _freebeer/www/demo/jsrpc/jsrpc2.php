<?php

// $CVSHeader: _freebeer/www/demo/jsrpc/jsrpc2.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

// \todo classify this logic

header('Content-type: text/html');
header('Cache-Control: no-cache');
header('Pragma: no-cache');
header('Expires: 0');
			
$response = htmlspecialchars(strftime('%c'));

?>
<html>
<head>
</head>
<body onload="window.parent.handleServerResponse('<?php echo $response ?>');">
</body>
</html>
