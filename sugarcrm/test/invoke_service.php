<?php
//error_reporting(E_ALL);
//error_reporting(E_STRICT);
     
include_once('cloud_api.php');
include_once('guid.php'); 

function base64_encode_file($filename) {
    if ($filename) {
        $bin = fread(fopen($filename, 'r'), filesize($filename));
        return base64_encode($bin); 
    }
}

// $GLOBALS['config']['apiUrl'] = 'http://campaigns.sugarcrmlabs.com/cloud/sugarcrm';  
$GLOBALS['config']['apiUrl'] = 'http://localhost:8888/cloud/sugarcrm'; 

$text = <<<TEXTMESSAGE
Hello  *|first_name|* *|last_name|*,

Thank you for your interest in our products. We have set up an appointment
to call you  on *|appointment_date|* at *|appointment_time|* to discuss your needs in more detail. Your 
representative is  *|representative_name|*.

Regards,
Fred
TEXTMESSAGE
;

$html = <<<HTMLMESSAGE
<html>
  <head></head>
  <body style="font-family:Arial; font-size:13; font-weight:normal; color:#333333; line-height:30px;">
    <p style="color:#990000">Hello *|first_name|* *|last_name|*,</p>
	<p style="color:#000099">
       Thank you for your interest in our products. We have set up an appointment for you with *|representative_name|* to meet with you right there
       in  *|city|*, *|state|*  on *|appointment_date|* at *|appointment_time|*. 
       <br /> 
       *|representative_first_name|* will be calling you shortly to confirm the location and time with you.
	   <br />      
	   <br /> 
       Regards,
	   Fred
	   <br />
    </p>
  </body>
</html>
HTMLMESSAGE
;   
 
$company_name = 'BakersField Electronics, Inc.'; 
$service_provider = 'Mandrill';
 
$merge_field_delimiters = array(
	'begin' => '*|',
	'end' 	=> '|*', 
);

$global_merge_data = array(
	array(
	    'name' => 'company_name',
	    'content' => $company_name
	),
	array(
	    'name' => 'service_provider',
	    'content' => $service_provider,
	), 
);

$recipient_merge_vars = array(
	'first_name',
	'last_name',
	'city',
	'state',
	'appointment_date',
	'appointment_time',
	'representative_name',
	'representative_first_name',
);

$recipients = array(
	array('email' => 'twolf@sugarcrm.com', 'name' => 'Captain Kangaroo',  'merge-data' => array('Captain','Kangaroo', 	'Chicago', 		'Illinois',		'10/24/2014',	'9:15 AM',	'Robert Blake',		'Robert')),  
	/*
	array('email' => 'abc@yahoo.com', 'name' => 'Doctor Do Little', 	'merge-data' => array('Doctor', 'Do Little', 	'Milwaukee', 	'Wisconsin',	'8/12/2014',	'8:10 AM',	'Peter Jennings',	'Peter')),
	array('email' => 'abc@yahoo.com', 'name' => 'Casper the Ghost', 	'merge-data' => array('Casper', 'Ghost', 		'Indianapolis', 'Indiana',		'7/24/2014',	'10:30 AM', 'Roger Rabbit', 	'Roger')),
	array('email' => 'abc@yahoo.com', 'name' => 'Curly Howard', 		'merge-data' => array('Curly', 	'Howard', 		'Minneapolis', 	'Minnesota',	'9/3/2014',		'10:25 AM', 'Clark Kent',		'Clark')),
	array('email' => 'abc@yahoo.com', 'name' => 'Moe Howard', 		'merge-data' => array('Moe', 	'Howard', 		'St. Paul', 	'Minnesota',	'11/16/2014',	'2:25 PM',	'Bruce Willis', 	'Bruce')),
	array(''email' => abc@yahoo.com', 'name' => 'Larry Fine', 		'merge-data' => array('Larry', 	'Fine', 		'Rochester', 	'Minnesota',	'12/25/2014',	'5:15 PM',	'David Banner', 	'David')), 
	*/
); 

$user = 'K456-5348955523';
$pass = '3440112776';
$from_name  = '';
$from_email = 'noreply@redherring.net';
$reply_to   = $from_email;
$account_id  = 'ebdf3380-37f3-3017-1dbb-5294fe857ca6';
$campaign_id = 'db8432e4-5151-f1ca-44c9-5294fe721518';
$unsubscribe_url = 'http://google.com';
$x_headers = array();
            
$cid = create_guid();
$images = array(
    array(
		'name' =>  $cid,   								  			  			 // CID
		'type' =>  'image/jpeg',          							  			 // image/png  image/jpeg  image/gif 
		'content' =>  base64_encode_file('/Users/twolfe/images/superman.jpg'),   // base64_encoded   
	),
);

$attachments = array(
    array(
		'name' =>  'Email.pdf',								          // FileName
		'type' =>  'text/plain',          							  //  
		'content' =>  base64_encode_file('/Users/twolfe/Email.pdf'),  // base64_encoded   
	),
); 
           
 
$subject    = 'This email was sent by *|service_provider|*';
$text_body  = 'This is a Text Message';

   //$text_body  = $text; 


$html_body  = '<p>Example HTML content</p><br /><br />Sponsored By *|company_name|*<br /><br />Hi *|first_name|* *|last_name|*,<br />';
$html_body .= '<img src="cid:' . $cid . '" /><br />';  
$html_body .= 'Your representative, *|representative_name|* will be reaching out to you in the next few days.<br /><br />';   
$html_body .= '*|representative_first_name|* is hoping that you can be available for the trade show preview we will be hosting on *|appointment_date|* at *|appointment_time|* <br /><br />';
$html_body .= 'Regards,<br />';      
$html_body .= 'Tony<br />';

   //$html_body = $html;

    
 
// $images=array();
// $attachments=array();

$post_data = array(
	'API-USER'		=> $user,
    'API-PASS'		=> $pass,
	'ACCOUNT-ID'	=> $account_id,
	'CAMPAIGN-ID'	=> $campaign_id,
	'MERGE-FIELD-DELIMETERS' => $merge_field_delimiters,
	'GLOBAL-MERGE-DATA'      => $global_merge_data, 
	'RECIPIENT-MERGE-VARS'   => $recipient_merge_vars,
	'RECIPIENTS'	=> $recipients,
	'X-HEADERS'		=> $x_headers,
	'FROM-NAME'		=> $from_name,
	'FROM-EMAIL'	=> $from_email,
	'REPLY-TO'		=> $reply_to, 
	'SUBJECT'		=> $subject,
	'HTML-BODY'		=> $html_body,
	'TEXT-BODY'		=> $text_body,
	'INLINE-IMAGES' => $images,
	'ATTACHMENTS' 	=> $attachments,         
);


$method = 'POST';
$uri = '/webmail/send';

// printf("%s\n",indent(json_encode($post_data)));

$response = callResource($uri, $method, $post_data);

print_r($response);
exit;     
 

 

/*
$code = $response['code'];
printf("\n\nHTTP Status Code: $code\n");
if ($code == 200) {
	//print_r($response['data']);
} else {
	print_r($response['response_headers']);
	printf("----- FAILED ------\n");
	exit;
}
*/




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
