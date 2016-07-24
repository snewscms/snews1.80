<?php

// CONTACT FORM
function public_contact() {
	if (!isset($_POST['contactform'])) {
		$_SESSION[_SITE.'time'] = time();
		echo '
			<div class="contactbox">
				<h2>'.l('contact').'</h2>
				<p>'.l('required').'</p>
				<form method="post" action="'._SITE.'" id="post" accept-charset="UTF-8">
					<p>
						<label for="name">* ',l('name'),'</label>:<br />
						<input type="text" name="name" id="name" maxlength="100" class="text" value="" />
					</p>
					<p>
						<label for="email">* ',l('email'),'</label>:<br />
						<input type="text" name="email" id="email" maxlength="320" class="text" value="" />
					</p>
					<p>
						<label for="weblink">',l('url'),'</label>:<br />
						<input type="text" name="weblink" id="weblink"  maxlength="160" class="text" value="" />
					</p>
					<p>
						<label for="message">* ',l('message'),'</label>:<br />
						<textarea name="message" rows="5" cols="5" id="message"></textarea>
					</p>
					',mathCaptcha(),'
					<p>
						<input type="hidden" name="ip" id="ip" value="',$_SERVER['REMOTE_ADDR'],'" />
						<input type="hidden" name="time" id="time" value="',time(),'" />
						<input type="submit" name="contactform" id="contactform" class="button" value="',l('submit'),'" />
					</p>
				</form>
			</div>';
	} else
	if (isset($_SESSION[_SITE.'time'])) {
		$count = $magic = 0;
		if (get_magic_quotes_gpc()) {$magic = 1;}
		foreach ($_POST as $k => $v) {
			if ($count === 8 ) {die;}
			if ($magic) {$k = stripslashes($v);}
			else {$$k = $v;}
			++$count;
		}
		$to = s('website_email');
		$subject = s('contact_subject');
		$name = (isset($name[0]) && ! isset($name[300]) ) ? trim($name) : null;
		$name = ! preg_match('/[\\n\\r]/', $name) ? $name : die;
		$mail = (isset($email[6]) && ! isset($email[320]) ) ? trim($email) : null;
		$mail = ! preg_match('/[\\n\\r]/', $mail) ? $mail : die;
		$url = (isset($weblink[4]) && ! isset($weblink[160]) ) ? trim($weblink) : null;
		$url = ( strpos($url, '?') === false && ! preg_match('/[\\n\\r]/', $url)) ? $url : null;
		$message = (isset($message[10]) && ! isset($message[6000]) ) ? strip_tags($message) : null;
		$time = (isset($_SESSION[_SITE.'time']) && $_SESSION[_SITE.'time'] === (int)$time && (time() - $time) > 10) ? $time : null;
		if ( isset($ip) && $ip === $_SERVER['REMOTE_ADDR'] && $time
			&& $name && $mail && $message && checkMathCaptcha())
		{
			unset($_SESSION[_SITE.'time']);
			$send_array = array(
				'to' => $to,
				'name' => $name,
				'email' => $mail,
				'message' => $message,
				'ip' => $ip,
				'url' => $url,
				'subject' => $subject);
			send_email($send_array);
		}
		else {
			echo notification(1, l('contact_not_sent'), 'contact');
		}
	}
}
?>