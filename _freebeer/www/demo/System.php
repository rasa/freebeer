<?php

// $CVSHeader: _freebeer/www/demo/System.php,v 1.3 2004/03/07 19:16:21 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once './_demo.php';

require_once FREEBEER_BASE . '/lib/System.php';

echo html_header_demo('fbSystem Class', FREEBEER_BASE . '/lib/System.php');

echo "
<pre>
";

echo 'platform=',			fbSystem::platform(),"\n";
echo 'isCLI=',				fbSystem::isCLI(),"\n";
echo 'isApache=',			fbSystem::isApache(),"\n";
echo 'directorySeparator=',	fbSystem::directorySeparator(),"\n";
$nl = fbSystem::lineSeparator();
echo 'lineSeparator=';
for ($i = 0; $i < strlen($nl); ++$i) {
	printf("%d: %d ", $i + 1, ord($nl{$i}));
}
echo "\n";
echo 'pathSeparator=',		fbSystem::pathSeparator(),"\n";
echo 'tempDirectory=',		fbSystem::tempDirectory(),"\n";
echo 'extensionSuffix=',	fbSystem::extensionSuffix(),"\n";
echo 'hostname=',			fbSystem::hostname(),"\n";
echo 'username=',			fbSystem::username(),"\n";

echo 'include_path=',		ini_get('include_path'),"\n";
fbSystem::appendIncludePath('appendme');
echo 'include_path=',		ini_get('include_path'),"\n";
fbSystem::prependIncludePath('prependme');
echo 'include_path=',		ini_get('include_path'),"\n";

echo 'loadExtension(\'standard\')=',fbSystem::loadExtension('standard'),"\n";
echo 'loadExtension(\'curl\')=',fbSystem::loadExtension('curl'),"\n";
echo 'loadExtension(\'failme\')=',fbSystem::loadExtension('failme'),"\n";
echo 'getLastError()=', fbSystem::getLastError(), "\n";

?>
</pre>
<address>
$CVSHeader: _freebeer/www/demo/System.php,v 1.3 2004/03/07 19:16:21 ross Exp $
</address>

</body>
</html>
