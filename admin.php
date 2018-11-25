<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

global $template;

$template->set_filenames(
	array(
		'plugin_admin_content' => dirname(__FILE__).'/template/admin.tpl'
	)
);

require_once __DIR__ . '/phpcaptchaconfig.php';

class PHPCaptcha_Admin {
	
	private $presets;
	private $config;
	private $thispath;
	private $webroot;
	
	public function __construct() {
		global $template;
		
		$this->thispath = PHPWG_PLUGINS_PATH . 'phpcaptchapiwigo/';
		$this->webroot = get_absolute_root_url() . 'plugins/phpcaptchapiwigo/';
		
		//read presets
		$this->presets = PhpCaptchaConfig::getPresets();
		
		if ( isset( $_POST['submit'] ) ) {
			
			$this->saveFormData($this->presets, $_POST);

		}

		$this->config = PhpCaptchaConfig::readConfig();
		
		//push them to template
		$this->setTemplateVars(
			$this->config,
			$this->presets,
			$this->thispath,
			$this->webroot);
		
		// Assign the template contents to ADMIN_CONTENT
		$template->assign_var_from_handle('ADMIN_CONTENT', 'plugin_admin_content');
	
		
		
		
		
	}
	
	function saveFormData($valid, $input) {
		
		$sourceIsForm = true;
		
		$valid['stringlength'] = $this->sanitize_integer($valid['stringlength'], $input['stringlength'],
			'Number of characters', 'stringlength');
		
		$valid['charstouse'] = $this->sanitize_charstouse($valid['charstouse'], $input['charstouse'],
			'Characters allowed', 'charstouse', 10, $sourceIsForm );
		
		if(!isset($input['strictlowercase'])) {
			$valid['strictlowercase']=false;
		} else {
			$valid['strictlowercase'] = $this->sanitize_boolean( $valid['strictlowercase'], $input['strictlowercase'],
				'Strict to lower case', 'strictlowercase', $sourceIsForm );
		}

		//bgcolor
		$valid['bgcolor'] = $this->sanitize_color($valid['bgcolor'], $input['bgcolor'],
			'Background color', 'background_color');
		
		//textcolor
		$valid['textcolor'] = $this->sanitize_color($valid['textcolor'], $input['textcolor'],
			'Text color','text_color');
		
		//linecolor
		$valid['linecolor'] = $this->sanitize_color($valid['linecolor'], $input['linecolor'],
			'Line color', 'line_color');
		
		$valid['sizewidth'] = $this->sanitize_integer($valid['sizewidth'], $input['sizewidth'],
			'Image width', 'sizewidth');
		
		$valid['sizeheight'] = $this->sanitize_integer($valid['sizeheight'], $input['sizeheight'],
			'Image height', 'sizeheight');
		
		$valid['fontsize'] = $this->sanitize_integer($valid['fontsize'], $input['fontsize'],
			'Font size', 'fontsize');
		
		$valid['numberoflines'] = $this->sanitize_integer($valid['numberoflines'], $input['numberoflines'],
			'Number of lines', 'numberoflines');
		
		$valid['thicknessoflines'] = $this->sanitize_integer($valid['thicknessoflines'], $input['thicknessoflines'],
			'Thickness of lines', 'thicknessoflines');
		
		if(!isset($input['allowad'])) {
			$valid['allowad']=false;
		} else {
			$valid['allowad'] = $this->sanitize_boolean($valid['allowad'], $input['allowad'],
				'Allow small advertisement below Captcha image', 'allowad', $sourceIsForm);
		}
		
		if(!isset($input['guestonly'])) {
			$valid['guestonly']=false;
		} else {
			$valid['guestonly'] = $this->sanitize_boolean($valid['guestonly'], $input['guestonly'],
				'Only not logged-in users see Captchas', 'guestonly', $sourceIsForm);
		}
		
		if(!isset($input['picture'])) {
			$valid['picture']=false;
		} else {
			$valid['picture'] = $this->sanitize_boolean($valid['picture'], $input['picture'],
				'Secure picture pages', 'picture', $sourceIsForm);
		}
		
		if(!isset($input['category'])) {
			$valid['category']=false;
		} else {
			$valid['category'] = $this->sanitize_boolean($valid['category'], $input['category'],
				'Secure category pages', 'category', $sourceIsForm);
		}
		
		if(!isset($input['register'])) {
			$valid['register']=false;
		} else {
			$valid['register'] = $this->sanitize_boolean($valid['register'], $input['register'],
				'Secure registration form', 'register', $sourceIsForm);
		}
		
		
		
		
		
		//write setting into file for db-less access
		$file = __DIR__ ."/config.php";
		
		$current='';
		$current .= "<?php\n";
		$current .= "//do not edit this file, gets overwritten by admin actions\n";
		$current .= "//created ".date("Y-m-d H:i:s O")."\n";
		$current .= '$stringlength='.$valid['stringlength'].";\n";
		$current .= '$charstouse=\''.$valid['charstouse']."';\n";
		$current .= '$strictlowercase='.($valid['strictlowercase'] == true ? "true":"false").";\n";
		$current .= '$bgcolor=\''.$valid['bgcolor']."';\n";
		$current .= '$textcolor=\''.$valid['textcolor']."';\n";
		$current .= '$linecolor=\''.$valid['linecolor']."';\n";
		$current .= '$sizewidth='.$valid['sizewidth'].";\n";
		$current .= '$sizeheight='.$valid['sizeheight'].";\n";
		$current .= '$fontsize='.$valid['fontsize'].";\n";
		$current .= '$numberoflines='.$valid['numberoflines'].";\n";
		$current .= '$thicknessoflines='.$valid['thicknessoflines'].";\n";
		$current .= '$allowad='.($valid['allowad'] == true ? "true":"false").";\n";
		$current .= '$guestonly='.($valid['guestonly'] == true ? "true":"false").";\n";
		$current .= '$picture='.($valid['picture'] == true ? "true":"false").";\n";
		$current .= '$category='.($valid['category'] == true ? "true":"false").";\n";
		$current .= '$register='.($valid['register'] == true ? "true":"false").";\n";
		$current .= "\n\n\n//END OF FILE\n";
		
		file_put_contents($file, $current);
		
		
		
	}
	
	
	
	
	private function setTemplateVars($settings, $presets, $thispath, $webroot) {
		global $template;

		$template->assign('captcha', array(
			"settings" => $settings,
			"presets" => $presets,
			"thispath" => $thispath,
			"webroot" => $webroot
		));
		
		
	}
	
	private function sanitize_color($valid, $input, $setting_title, $setting_errorid) {
		global $page;
		$validreturn = (isset($input) && !empty($input))
			? $input : $valid;
		if ( !empty($validreturn) && !preg_match( '/^[a-f0-9]{6}$/i', $validreturn ) ) {
			$page['errors'][] = sprintf(l10n('Please enter a valid hex value for %s, (RRGGBB, a-f, 0-9)'), l10n
				($setting_title));
			return $valid;
		}
		return $validreturn;
	}
	
	private function sanitize_integer($valid, $input, $setting_title, $setting_errorid) {
		global $page;
		$validreturn = (isset($input) && !empty($input))
			? $input : $valid;
		if ( !empty($validreturn) && preg_match( '/^[0-9]*$/i', $validreturn )==0 ) {
			$page['errors'][] = sprintf(l10n('Please enter a valid integer value for %s'),l10n($setting_title));
			return $valid;
		}
		return $validreturn;
	}
	
	private function sanitize_charstouse($valid, $input, $setting_title, $setting_errorid, $minlength, $sourceIfForm) {
		global $page;
		if($sourceIfForm) {
			if(strlen($input) < $minlength) {
				$page['errors'][] = sprintf(l10n('Please enter a valid value for %s, at least %d chars long'), l10n($setting_title),
					$minlength);
				return $valid;
			}
			if ( !preg_match( '/^[a-zA-Z0-9]/i', $input )) {
				$page['errors'][] = sprintf(l10n('Please enter a valid value for %s, at least %d chars long'), l10n($setting_title),
					$minlength);
				return $valid;
			}
			return $input;
		} else {
			return $valid;
		}
	}
	
	private function sanitize_boolean($valid, $input, $setting_title, $setting_errorid, $sourceIsForm) {
		if($sourceIsForm) {
			if(isset($input) && $input == "1") {
				$validreturn = true;
			} else {
				$validreturn = false;
			}
		} else {
			$validreturn = $valid;
		}
		return $validreturn;
	}
	
}

$phpcaptcha_admin = new PHPCaptcha_Admin();
