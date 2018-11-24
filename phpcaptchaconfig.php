<?php
class PhpCaptchaConfig {
	const SESSIONPATH = __DIR__.'/sessions/';
	const CLEANSESSIONFILESAFTERSECONDS = 3600;

	public static function cleanSessionDir() {
		
		$path = PhpCaptchaConfig::SESSIONPATH;
		$seconds = PhpCaptchaConfig::CLEANSESSIONFILESAFTERSECONDS;
		$filter = '/\.session\.php$/i';
		if(is_dir($path)) {
			if ($handle = opendir($path)) {
				while (false !== ($file = readdir($handle))) {
					if ((time()-filectime($path.$file)) > $seconds) {
						if (preg_match($filter, $file)) {
							unlink($path.$file);
						}
					}
				}
			}
		}
	}
	
	public static function putSessionfile($hash, $challenge) {
		
		$sessionfilename = PhpCaptchaConfig::getSessionFilename($hash);
		
		$filename = PhpCaptchaConfig::SESSIONPATH . $sessionfilename;
		
		if(!is_dir(PhpCaptchaConfig::SESSIONPATH)) {
			mkdir(PhpCaptchaConfig::SESSIONPATH);

			$content = '';
			$content .= '<?php'."\n";
			$content .= '//do not touch'."\n";
			$content .= "\n\n\n";
			file_put_contents(PhpCaptchaConfig::SESSIONPATH.'index.php', $content);
			
		}
		
		if(!file_exists($filename)) {
			unlink($filename);
		}
		
		$content = '';
		$content .= '<?php'."\n";
		$content .= '//do not touch'."\n";
		$content .= '$captcha="'.$challenge.'";'."\n\n\n";
		file_put_contents($filename, $content);
		
	}
	
	public static function getChallengeFromFile($hash) {
		
		$sessionfilename = PhpCaptchaConfig::getSessionFilename($hash);
		
		$filename = PhpCaptchaConfig::SESSIONPATH . $sessionfilename;
		
		if(file_exists($filename)) {
			
			include $filename;
			
			return $captcha;
			
		}
		
		return 'invalidvalue'.rand(0,10000000);
		
	}

	public static function getSessionFilename($hash) {
		require_once __DIR__."/salt.php";
		global $PHPCAPTCHA_SALT;
		return md5($PHPCAPTCHA_SALT.$hash).".session.php";
	}
	
	public static function getPresets() {
		
		$valid=array();
		$valid['stringlength']=6;
		$valid['charstouse']='abcdefghkmnpqrtuvwxyz23456789';
		$valid['strictlowercase']=true;
		$valid['bgcolor']="000000";
		$valid['textcolor']="ffffff";
		$valid['linecolor']="323232";
		$valid['sizewidth']=200;
		$valid['sizeheight']=50;
		$valid['fontsize']=25;
		$valid['numberoflines']=6;
		$valid['thicknessoflines']=2;
		$valid['allowad']=true;
		$valid['guestonly']=false;
		$valid['picture']=true;
		$valid['category']=true;
		$valid['register']=true;
		return $valid;
		
	}
	
	public static function readConfig() {
		
		$config = PhpCaptchaConfig::getPresets();
		
		if(file_exists(__DIR__ . "/config.php")) {
			
			require_once __DIR__ . "/config.php";
			
			$config['stringlength']=$stringlength;
			$config['charstouse']=$charstouse;
			$config['strictlowercase']=$strictlowercase;
			$config['bgcolor']=$bgcolor;
			$config['textcolor']=$textcolor;
			$config['linecolor']=$linecolor;
			$config['sizewidth']=$sizewidth;
			$config['sizeheight']=$sizeheight;
			$config['fontsize']=$fontsize;
			$config['numberoflines']=$numberoflines;
			$config['thicknessoflines']=$thicknessoflines;
			$config['allowad']=$allowad;
			$config['guestonly']=$guestonly;
			$config['picture']=$picture;
			$config['category']=$category;
			$config['register']=$register;
			
			// Attention: whenever you add future stuff, make sure to check for isset , since config.php might not
			// carry the new vars
		}
		
		return $config;
		
		
	}

}