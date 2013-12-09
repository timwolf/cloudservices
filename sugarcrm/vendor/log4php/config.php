<?php

if (empty($GLOBALS['logger_file_name'])) {
    $logger_file_name = "sugarcrm.log";
} else {
    $logger_file_name = $GLOBALS['logger_file_name'];
}

function directoryEndsWith($findPath, $dir) {
	$rootDir = $dir;
    $pos = strpos($dir, $findPath);
	if ($pos !== false) { 
		$rootDir = substr($dir, 0, $pos+strlen($findPath));
	}
	return $rootDir; 
}

$_sugarcrm_root_dir = directoryEndsWith("/sugarcrm/vendor/", dirname(__FILE__)) . '../'; 
// echo  $_sugarcrm_root_dir . 'log/sugarcrm.log' . "\n---\n"; 

return array(
	'rootLogger' => array(
		'appenders' => array('default'),
	),
	'appenders' => array(
	    'default' => array(
	        'class' => 'LoggerAppenderFile',
	        'layout' => array(
	            'class' => 'LoggerLayoutPattern',
				'params' => array(
					    'conversionPattern' => '%date [%level] %message%newline',
			    )   
	         ),
	        'params' => array(
	            'file' => $_sugarcrm_root_dir . 'log/' . $logger_file_name,
	            'append' => true
	         )
	     )  
     )
);
