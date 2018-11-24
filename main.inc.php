<?php
/*
Version: 1.0
Plugin Name: PHP Captcha for Piwigo
Plugin URI: // Here comes a link to the Piwigo extension gallery, after
           // publication of your plugin. For auto-updates of the plugin.
Author: pstimpel
Description: PHP Captcha for Piwigo without using any third party content, tracking save and GDPR save replacement for Google Recaptcha and co.
*/

// Chech whether we are indeed included by Piwigo.
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

// Define the path to our plugin.
define('PHPCAPTCHA_PATH', PHPWG_PLUGINS_PATH.basename(dirname(__FILE__)).'/');

add_event_handler('get_admin_plugin_menu_links', 'phpcaptcha_admin_menu');


// Add an entry to the 'Plugins' menu.
function phpcaptcha_admin_menu($menu) {
	array_push(
		$menu,
		array(
			'NAME'  => 'PHP Captcha for Piwigo',
			'URL'   => get_admin_plugin_menu_link(dirname(__FILE__)).'/admin.php'
		)
	);
	return $menu;
}
