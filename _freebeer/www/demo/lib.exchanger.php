<?php

// $CVSHeader: _freebeer/www/demo/lib.exchanger.php,v 1.2 2004/03/07 17:51:34 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once './_demo.php';

echo html_header_demo('Exchanging information with a server without reloading your HTML page', '../opt/ibm.com/exchanger.js');

// \todo classify this logic

?>

<script type="text/javascript" language="JavaScript" src="../opt/ibm.com/exchanger.js"></script>
<script type="text/javascript" language="JavaScript">
<!-- // <![CDATA[

var theBuffer;

//call this function in page onload event handler
function initialize() {
	theBuffer = new exchanger("myframe");
}

//call this function when data needs to be sent to server
function sendDataToServer(data) {
	theBuffer.sendData("exchanger/exchanger2.php?" + data);
	return false;
}

//call this function to check what the server returns.
function showReturnData() {
	alert(theBuffer.retrieveData("myNewData"));
	return false;
}

initialize();

// ]]> -->
</script>

<iframe name="myframe" style="border: none; width:0; height:0">
</iframe>

<layer name="myframe" width="0" height="0" visibility="hide">
</layer>

<br />

Click 
<a href="#" onclick="return sendDataToServer('');"
>here</a> to execute 'exchanger2.php' as a remote procedure call.

<br />
<br />

Click 
<a href="#" onclick="return showReturnData();"
>here</a> to display results.

<br />
<br />
 
<div id="ANSW">
&nbsp;
<!--
'exchanger2.php' has not been executed since this page was displayed.
-->
</div>

<?php

	echo 'This page was last displayed at ',strftime('%c');
	
?>
<p>
See:
<br />
<a target='_BLANK' href='http://www-106.ibm.com/developerworks/web/library/wa-exrel/?dwzone=web'>Exchanging information with a server without reloading your HTML page</a>
<br />
<a target='_BLANK' href='http://www.oreillynet.com/pub/a/javascript/2002/02/08/iframe.html'>Remote Scripting with IFRAME</a>
<br />
<a target='_BLANK' href='http://www.ashleyit.com/rs/'>Remote Scripting - Ashley IT</a>
</p>

<address>
$CVSHeader: _freebeer/www/demo/lib.exchanger.php,v 1.2 2004/03/07 17:51:34 ross Exp $
</address>

</body>
</html>
