<% Response.Expires = 0 %>
<script runat=server language=javascript>

//
// jsrsServer.asp - javascript remote scripting server include
//
// Author:  Brent Ashley [jsrs@megahuge.com]
//
// see jsrsClient.js for version info
//
//  see license.txt for copyright and license info

function jsrsDispatch( validFuncs ){
  var func = jsrsBuildFunc( validFuncs );
  if (func != ""){
    var retval;
    try {
      retval = eval(func);
    }
    catch (e) {
      jsrsReturnError(e.description);
    }
    if (retval != null){
      jsrsReturn( retval + '' );
    } else {
      jsrsReturn( '' );
    } 
  } else {
    jsrsReturnError( 'function builds as empty string' );
  }
}

function jsrsReturn( payload ) {
  Response.Write (
      '<html><head></head><body onload="p=document.layers?parentLayer:window.parent;p.jsrsLoaded(\'' 
    + Request("C") + '\');">jsrsPayload:<br>' 
    + '<form name="jsrs_Form"><textarea name="jsrs_Payload" id="jsrs_Payload">'
    + jsrsEscape( payload ) + '</textarea></form></body></html>' );
    Response.End();
}

function jsrsEscape( str ){
  // escape ampersands so special chars aren't interpreted
  var tmp = str.replace( /&/g, "&amp;" );
  // escape slashes  with whacks so end tags don't interfere with return html
  return tmp.replace( /\//g, "\\/");
}

/////////////////////////////
//
// user functions


function jsrsReturnError( str ){

  // escape quotes
  cleanStr = str.replace( /\'/g, "\\'" );
  cleanStr = 'jsrsError: ' + cleanStr.replace( /\"/g, "\\\"" );
  Response.Write (
      '<html><head></head><body '
      + 'onload="p=document.layers?parentLayer:window.parent;p.jsrsError(\'' + Request("C") + "','" + escape(str) + '\');">'
      + cleanStr + '</body></html>' );
  Response.End();
}

function jsrsArrayToString( a, delim ){
  
  // user function to flatten 1-dim array to string for return to client
  var d = '~';
  if (delim != null) d = delim;
  return a.join(d);
}

</script>

<script runat=server language=vbscript>

Function jsrsBuildFunc( validFuncs )
  
  ' did this part in VBScript just for fun
  Dim func, i, parm

  func = ""
  If Request("F") <> "" Then
    func = Request("F") 
    
    ' make sure it's in the dispatch list
    If Instr( UCase(validFuncs), UCase(func) ) = 0 Then 
      jsrsReturnError func & " is not a valid function" 
    End If
    
    func = func & "("
    i = 0
    Do While Request("P" & i) <> ""
      parm = Request("P" & i) 
      parm = Mid( parm, 2, Len(parm) - 2 )
      func = func & """" & jsrsEvalEscape(parm) & ""","
      i = i + 1    
    Loop
    If Right(func,1) = "," Then func = Left(func,Len(func)-1)

    func = func & ")"
  End IF
  jsrsBuildFunc = func
  
End Function

Function jsrsEvalEscape( thing )
  Dim tmp
  tmp = Replace( thing, vbCRLF, "\n" )
  jsrsEvalEscape = Replace( tmp, """", "\""" )
End Function

Function jsrsVBArrayToString( a, delim )
  
  ' user function to flatten vb array out to string for return to client
  Dim d
  d = "~"
  
  If delim <> "" Then d = delim
  jsrsVBArrayToString = Join(a,d)
  
End Function

</script>
