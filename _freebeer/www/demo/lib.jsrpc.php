<?php

// $CVSHeader: _freebeer/www/demo/lib.jsrpc.php,v 1.2 2004/03/07 17:51:34 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once './_demo.php';

echo html_header_demo('JavaScript RPC (Remote Procedure Call)', './jsrpc/jsrpc2.php');

// \todo classify this logic

?>

<script type="text/javascript" language="JavaScript">
<!-- // <![CDATA[
function serverGetTime() {
  var iframe = document.getElementById("COMM");
  if (!iframe) {
    return true;
  }
  var answ = document.getElementById("ANSW");
  if (!answ) {
    return true;
  }
  iframe.src = "jsrpc/jsrpc2.php";
  return false;
}

function handleServerResponse(message) {
  var answ = document.getElementById("ANSW");
  if (answ) {
    answ.innerHTML = 'jsrpc2.php returned "' + message + '"';
  }
  var iframe = document.getElementById("COMM");
  if (iframe) {
    iframe.src = "about:blank";
  }
}
// ]]> -->
</script>

<iframe id="COMM" style="border: none; width: 0px; height: 0px;"></iframe>

Click 
<a href="<?php echo $_SERVER['PHP_SELF']; ?>" onclick="return serverGetTime();"
>here</a> to execute 'jsrpc2.php' as a remote procedure call.

<div id="ANSW">
&nbsp;
<!--
'jsrpc2.php' has not been executed since this page was displayed.
-->
</div>

<?php

	echo 'This page was last displayed at ',strftime('%c');
	
?>
<p>
See:
<br />
<a target='_BLANK' href='http://dynarch.com/mishoo/rpc.epl'>Remote Procedure Call in JavaScript</a>
<br />
<a target='_BLANK' href='http://www.oreillynet.com/pub/a/javascript/2002/02/08/iframe.html'>Remote Scripting with IFRAME</a>
<br />
<a target='_BLANK' href='http://www.ashleyit.com/rs/'>Remote Scripting - Ashley IT</a>
</p>

<address>
$CVSHeader: _freebeer/www/demo/lib.jsrpc.php,v 1.2 2004/03/07 17:51:34 ross Exp $
</address>

</body>
</html>
