<?php 
  require("jsrsServer.php.inc");
  jsrsDispatch( "test, envVar" );

  function test($str1,$str2){
  // 2 vars coming in, return array
  return jsrsArrayToString(array(strtolower($str2), strtoupper($str1), "php" ),"~");
  }

function envVar($varname) {
  $varname = strtoupper($varname);
  $keys = array_keys($GLOBALS);
  if ($varname == "ALL_HTTP"){
    $s = "";
    for($i=0; $i < count($keys); $i++ ){
     if(strstr($keys[$i],"HTTP")){
       if( !is_array($GLOBALS[$keys[$i]]) ) {
         $s .= $keys[$i] . "=" . $GLOBALS[$keys[$i]] . "\n";
       }  
     }  
    }  
    return $s;
  } else {
    return getenv($varname);
  }  
}
?>