<?php
/**
* javascript obfuscator
* 
* @param mixed $js_code
*/
function js_obfuscator_with_php($js_code){
    /*ob_start();
    echo $js_code;
    $generatedoutput = ob_get_contents();
    ob_end_clean();*/
    $generatedoutput=$js_code;
    $generatedoutput = str_replace("\\\r\n", "\\n", $generatedoutput);
    $generatedoutput = str_replace("\\\n", "\\n", $generatedoutput);
    $generatedoutput = str_replace("\\\r", "\\n", $generatedoutput);
    $generatedoutput = str_replace("}\r\n", "};\r\n", $generatedoutput);
    $generatedoutput = str_replace("}\n", "};\n", $generatedoutput);
    $generatedoutput = str_replace("}\r", "};\r", $generatedoutput);
    require('../libs/packer.php-1.1/class.JavaScriptPacker.php');
    $myPacker = new JavaScriptPacker($generatedoutput, 62, true, false);
    $packed = $myPacker->pack();
    return ($packed);
}
?>