<?php
include_once("sugar_api.php");

$GLOBALS['config']['apiUrl'] = 'http://localhost:8888/cloud/sugarcrm';
$method = "POST";
$uri = '/hello/recipients/12345';

$data= array(

	"filter" => array(
		array('last_name' => array('$starts' => 'Aaron') ),		
	),
											
	"fields"	=> "first_name,last_name",
	// "order_by"	=> "last_name:ASC,first_name:DESC",
	"order_by"	=> "first_name:ASC",
	"max_num" 	=> 15,
	"offset" 	=> 0
);


printf("%s\n",indent(json_encode($data)));

// print_r($data);
//printf("\n\n\nREST CALL: %s  %s %s\n", $method, $uri, json_encode($data)); //e sugar_api_* exit;
//exit;
$response = callResource($uri, $method, $data);

print_r($response['data']);
exit;

$code = $response["code"];
printf("\n\nHTTP Status Code: $code\n");
if ($code == 200) {
	//print_r($response["data"]);
} else {
	print_r($response["response_headers"]);
	printf("----- FAILED ------\n");
	exit;
}





/**
 * Indents a flat JSON string to make it more human-readable.
 *
 * @param string $json The original JSON string to process.
 *
 * @return string Indented version of the original JSON string.
 */
function indent($json) {

    $result      = '';
    $pos         = 0;
    $strLen      = strlen($json);
    $indentStr   = '  ';
    $newLine     = "\n";
    $prevChar    = '';
    $outOfQuotes = true;

    for ($i=0; $i<=$strLen; $i++) {

        // Grab the next character in the string.
        $char = substr($json, $i, 1);

        // Are we inside a quoted string?
        if ($char == '"' && $prevChar != '\\') {
            $outOfQuotes = !$outOfQuotes;
        
        // If this character is the end of an element, 
        // output a new line and indent the next line.
        } else if(($char == '}' || $char == ']') && $outOfQuotes) {
            $result .= $newLine;
            $pos --;
            for ($j=0; $j<$pos; $j++) {
                $result .= $indentStr;
            }
        }
        
        // Add the character to the result string.
        $result .= $char;

        // If the last character was the beginning of an element, 
        // output a new line and indent the next line.
        if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
            $result .= $newLine;
            if ($char == '{' || $char == '[') {
                $pos ++;
            }
            
            for ($j = 0; $j < $pos; $j++) {
                $result .= $indentStr;
            }
        }
        
        $prevChar = $char;
    }

    return $result;
}
?>