<?php

// $CVSHeader: _freebeer/www/demo/exchanger/exchanger2.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

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
<script type="text/javascript" language="JavaScript">
<!-- // <![CDATA[
	var myNewData = "<?php echo $response ?>";
	window.parent.showReturnData();
	//many other data can be sent back to client this way.
// ]]> -->
</script>
</head>
<body>
<pre>
<script type="text/javascript" language="JavaScript">
<!-- // <![CDATA[
	document.write('myNewData=' + myNewData);
// ]]> -->
</script>
<?php echo 'response=',$response; ?>
</pre>
</body>
</html>
