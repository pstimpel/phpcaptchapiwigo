<?php

defined('PHPCAPTCHA_PATH') or die('Hacking attempt!');

$conf['template'] = 'comment';
include(PHPCAPTCHA_PATH.'include/common.inc.php');

add_event_handler('loc_begin_index', 'add_phpcaptcha');
add_event_handler('user_comment_check', 'check_phpcaptcha', EVENT_HANDLER_PRIORITY_NEUTRAL, 2);

function add_phpcaptcha()
{
  global $template;
  $template->set_prefilter('comments_on_albums', 'prefilter_phpcaptcha_category');

}

function prefilter_phpcaptcha_category($content, $smarty)
{
  $search = '{$comment_add.CONTENT}</textarea></p>';
  return str_replace($search, $search."\n{\$captcha.parsed_content}", $content);
}

function check_phpcaptcha($action, $comment)
{
  global $conf, $page;

  $captchasession = PhpCaptchaConfig::getChallengeFromFile($_POST['captcha_hash']);
  
  if (strcmp($_POST['captcha_code'], $captchasession) != 0)
  {
	$page['errors'][] = l10n('Invalid Captcha');
    return 'reject';
  }

  return $action;
}