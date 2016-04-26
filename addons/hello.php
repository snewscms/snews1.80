<?php

/* SIMPLE EXAMPLE HOW TO USE ADDON */

// LANG
$l['hello_title'] = 'Hello World';
$l['hello_desc'] = 'This is simple addon to show how this works';
$l['wyname'] = 'What is your name';
$l['focus'] = isset($l['focus']) ? $l['focus'].',hello' : ',hello';


// FUNCTION CALLED BY ADMIN
function admin_hello() {
	echo '<h2>Create your code here</h2>';
	echo '<p>You can see public function <a href="'._SITE.'hello'.'">here</a></p>';
}

// PUBLIC FUNCTION
function public_hello() {
	if ($_POST) {
		if ($_POST['name']) {echo '<h1>Hello '.clean($_POST['name']).'</h1>';}
	} else {
		echo '<h2>Hello world</h2>';
		echo html_input('form', '', 'post', '', '', '', '', '', '', '', '', '', 'post', _SITE.'hello', '')."\r\n";
		echo html_input('text', 'name', 'name', '', '* '.l('wyname'), 'text', '', '', '', '', '', '', '', '', '')."\r\n";
		echo html_input('hidden', 'addon', 'addon', 'hi', '', '', '', '', '', '', '', '', '', '', '');
		echo html_input('submit', 'submit', 'submit', l('submit'), '', 'button', '', '', '', '', '', '', '', '', '')."\r\n";
	}
	
}


// http://localhost/snews1.80/hello/
// http://localhost/snews1.80/?action=admin_hello

?>