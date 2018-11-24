<?php
/*
Version: 1.0.5
Plugin Name: PHP Captcha for Piwigo
Plugin URI: https://piwigo.org/ext/extension_view.php?eid=882
Author: pstimpel
Author URI: https://wp.peters-webcorner.de
Description: PHP Captcha for Piwigo without using any third party content, tracking save and GDPR save replacement for Google Recaptcha and co.
*/


// Chech whether we are indeed included by Piwigo.
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

// Define the path to our plugin.
define('PHPCAPTCHA_PATH', PHPWG_PLUGINS_PATH.basename(dirname(__FILE__)).'/');

require_once __DIR__ . '/phpcaptchaconfig.php';



add_event_handler('init', 'phpcaptcha_init');

if (defined('IN_ADMIN'))
{
	add_event_handler('get_admin_plugin_menu_links', 'phpcaptcha_admin_menu');
}
else
{
	add_event_handler('loc_end_section_init', 'phpcaptcha_document_init', EVENT_HANDLER_PRIORITY_NEUTRAL+30);
	add_event_handler('loc_begin_register', 'phpcaptcha_register_init', EVENT_HANDLER_PRIORITY_NEUTRAL+30);
}

//clean the seession directory
PhpCaptchaConfig::cleanSessionDir();


function phpcaptcha_init()
{
	
	load_language('plugin.lang', PHPCAPTCHA_PATH);
}


// modules
function phpcaptcha_document_init()
{
	global $pwg_loaded_plugins, $page, $phpcaptcha_config;
	
	$phpcaptcha_config = PhpCaptchaConfig::readConfig();
	
	if (!is_a_guest() && $phpcaptcha_config['guestonly']==true)
	{
		return;
	}
	
	if (script_basename() == 'picture' and $phpcaptcha_config['picture'])
	{
		include(PHPCAPTCHA_PATH . 'include/picture.inc.php');
	}
	else if (isset($page['section']))
	{
		if (
			script_basename() == 'index' &&
			$page['section'] == 'categories' && isset($page['category']) &&
			isset($pwg_loaded_plugins['Comments_on_Albums']) &&
			$phpcaptcha_config['category']
		)
		{
			include(PHPCAPTCHA_PATH . 'include/category.inc.php');
		}
	}
}
function phpcaptcha_register_init()
{
	global $pwg_loaded_plugins, $page, $phpcaptcha_config;
	
	$phpcaptcha_config = PhpCaptchaConfig::readConfig();
	
	
	if ($phpcaptcha_config['register'])
	{
		include(PHPCAPTCHA_PATH . 'include/register.inc.php');
	}
}


// Add an entry to the 'Plugins' menu.
function phpcaptcha_admin_menu($menu) {
	array_push(
		$menu,
		array(
			'NAME'  => 'PHP Captcha for Piwigo',
			'URL'   => get_admin_plugin_menu_link(dirname(__FILE__)).'/admin.php?page=plugin-phpcaptchapiwigo'
		)
	);
	return $menu;
}
