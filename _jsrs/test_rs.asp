<!--#include file="jsrsServer.inc"--> 
<% jsrsDispatch( "testJS, testVB, envVar" ) %>

<script runat="server" language="javascript">

function testJS( str1, str2 ){
  // 2 vars coming in, return array
  return jsrsArrayToString(Array( str2.toLowerCase(), str1.toUpperCase(), 'javascript' ));
}

</script>

<script runat="server" language="vbscript">

function testVB( str1, str2 )
  testVB =  jsrsVBArrayToString( Array( UCase(str2), LCase(str1), "vbscript" ), "" )
end function

function envVar( varname )
  envVar = Request.ServerVariables(varname)
end function

</script>