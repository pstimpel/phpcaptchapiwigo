<?php
/**
 * Copyright (c) 2018. Peters Webcorner, All rights reserved if not mentioned different!
 */

defined('PHPCAPTCHA_PATH') or die('Hacking attempt!');

$conf['template'] = 'registert';
include(PHPCAPTCHA_PATH.'include/common.inc.php');

add_event_handler('loc_end_register', 'add_phpcaptcha_register');
add_event_handler('register_user_check', 'check_phpcaptcha_register');

function add_phpcaptcha_register()
{
  global $template;
  $template->set_prefilter('register', 'prefilter_phpcaptcha_register');
}

function prefilter_phpcaptcha_register($content, $smarty)
{
 $search = '<label for="send_password_by_mail">';
	return str_replace($search, "{\$captcha.parsed_content}".$search."\n", $content);
	
	
}

function check_phpcaptcha_register($errors)
{
	global $phpcaptcha_config;
	
	$captchasession = PhpCaptchaConfig::getChallengeFromFile($_POST['captcha_hash']);

	$sessionvalue = $captchasession;
	$postvalue = $_POST['captcha_code'];
	if($phpcaptcha_config['strictlowercase']==true) {
		
		$sessionvalue = strtolower($captchasession);
		$postvalue = strtolower($_POST['captcha_code']);
		
	}
	
	if (strcmp($postvalue,$sessionvalue) != 0)
	{
		$errors[] = l10n('Invalid Captcha');
	}
	
  return $errors;
}