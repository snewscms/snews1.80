<?php
/*

Original website:

	http://www.stanford.edu/dept/its/communications/webservices/wiki/index.php/How_to_perform_error_handling_in_PHP
	Thanks for Sibas to share this us

*/

define("LOG_FILE", "errors.log");
function my_error_handler($errno, $errstr, $errfile, $errline ){
	$time = date("g:i a, j F Y");
	$adr = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	$newline = chr(10).chr(13).PHP_EOL;
	switch ($errno) {
    	case E_USER_ERROR: 
	      error_log("[E_USER_ERROR # $errno]: $errstr \n Fatal error on line $errline in file $errfile - $time - \n\n\t", '3', LOG_FILE);
    	  break;    
		case E_USER_WARNING:
	      error_log("[E_USER_WARNING # $errno]: $errstr \n in $errfile on line $errline - $time - \n\n\t", '3', LOG_FILE);
    	  break;    
	 	case E_USER_NOTICE:
	      error_log("[E_USER_NOTICE # $errno]: $errstr \n in $errfile on line $errline - $time - \n\n\t", '3', LOG_FILE);
    	  break;  
		case E_NOTICE:
	      error_log("$adr \n[E_NOTICE # $errno]:  $errstr \nin $errfile on line $errline - $time - \n\n\t", '3', LOG_FILE);
    	  break;
		case E_PARSE:
	      error_log("[E_PARSE # $errno]: $errstr \n in $errfile on line $errline - $time - \n\n\t", '3', LOG_FILE);
    	  break;  
		case E_WARNING:
	      error_log("[E_WARNING # $errno]: $errstr \n in $errfile on line $errline - $time - \n\n\t", '3', LOG_FILE);
    	  break;
		case E_CORE_ERROR:
	      error_log("[E_CORE_ERROR # $errno]: $errstr \n in $errfile on line $errline - $time - \n\n\t", '3', LOG_FILE);
    	  break;
		case E_CORE_WARNING:
    	  error_log("[E_CORE_WARNING # $errno]: $errstr \n in $errfile on line $errline - $time - \n\n\t", '3', LOG_FILE);
	      break;	
		case E_COMPILE_ERROR:
	      error_log("[E_COMPILE_ERROR # $errno]: $errstr \n in $errfile on line $errline - $time - \n\n\t", '3', LOG_FILE);
    	  break;	
		case E_COMPILE_WARNING:
	      error_log("[E_COMPILE_WARNING # $errno]: $errstr \n in $errfile on line $errline - $time - \n\n\t", '3', LOG_FILE);
    	  break;	
		case E_ALL:
	      error_log("[E_ALL # $errno]: $errstr \n in $errfile on line $errline - $time - \n\n\t", '3', LOG_FILE);
    	  break;	
		default:
	      error_log("Unknown error [#$errno]: $errstr n in $errfile on line $errline - $time - \n\n\t", '3', LOG_FILE);
    	  break;
  }  // Don't execute PHP's internal error handler set to TRUE
  return TRUE;
}
// Use set_error_handler() to tell PHP to use our method
$old_error_handler = set_error_handler("my_error_handler");

?>