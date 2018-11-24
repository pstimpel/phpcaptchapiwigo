<?php
class PhpCaptchaConfig {
	
	public static function getPresets() {
		
		$valid=array();
		$valid['stringlength']=6;
		$valid['charstouse']='abcdefghkmnpqrtuvwxyz23456789';
		$valid['strictlowercase']=1;
		$valid['bgcolor']="000000";
		$valid['textcolor']="ffffff";
		$valid['linecolor']="323232";
		$valid['sizewidth']=200;
		$valid['sizeheight']=50;
		$valid['fontsize']=25;
		$valid['numberoflines']=6;
		$valid['thicknessoflines']=2;
		$valid['allowad']=1;
		return $valid;
		
	}
	
	public static function readConfig() {
		
		$config = PhpCaptchaConfig::getPresets();
		
		if(file_exists(__DIR__ . "/config.php")) {
			
			require_once __DIR__ . "/config.php";
			
			$config['stringlength']=$stringlength;
			$config['charstouse']=$charstouse;
			if($strictlowercase==true) {
				$strictlowercase=1;
			} else {
				$strictlowercase=0;
			}
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
			
		}
		
		return $config;
		
		
	}
	
}