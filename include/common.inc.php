<?php

defined('PHPCAPTCHA_PATH') or die('Hacking attempt!');

global $template;

load_language('plugin.lang', PHPCAPTCHA_PATH);

$template->assign(array(
  'captcha' => $phpcaptcha_config,
  'PHPCAPTCHA_PATH' => get_absolute_root_url().PHPCAPTCHA_PATH,
  'captchahash' => md5(date('Y-m-d H:i:s').rand(0,1000000))
  ));
$template->set_filename('phpcaptchapiwigo', realpath(PHPCAPTCHA_PATH.'template/'.$conf['template'].'.tpl'));
$template->append('captcha', array('parsed_content' => $template->parse('phpcaptchapiwigo', true)), true);
