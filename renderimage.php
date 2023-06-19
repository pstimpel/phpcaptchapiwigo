<?php

require_once __DIR__ . '/phpcaptchaconfig.php';
require_once __DIR__ . '/gd-gradient-fill.php';
require_once __DIR__ . '/random_compat/lib/random.php';

class renderimage {

	private $hash;

	public function __construct($hash) {
		$this->hash = $hash;
		$preset = PhpCaptchaConfig::readConfig();
		$phrase = $this->getRandomString($preset);
		$this->setSession($phrase);
		$this->renderImage($preset, $phrase);
	}

	/**
	 * @param $phpcaptchaConfig
	 * @param $captchaChallenge
	 */
	function setSession($captchaChallenge) {
		PhpCaptchaConfig::putSessionfile($this->hash,$captchaChallenge);
	}

	/**
	 * @param $phpcaptchaConfig
	 *
	 * @return string
	 */
	function getRandomString($phpcaptchaConfig) {
		$s = '';
		//$len = mt_rand($phpcaptchaConfig['stringlength']-1,$phpcaptchaConfig['stringlength']+1);
		$len =$phpcaptchaConfig['stringlength'];
		for ($S = 0; $i <= $len-1; $i++) {
			$s .= $phpcaptchaConfig['charstouse'][random_int(0,strlen($phpcaptchaConfig['charstouse'])-1)];
		}
		return $s;
	}

	// fills a sting with spaces
	// e.g. 'abc' -> 'a b c'
	function explodestring ($s){
		return implode(' ',str_split($s));
	}

	// add random chars to image
	function pixelfuck($img, $chars, $shrpns=2, $size=5, $weight=3)	{
		$w = imagesx($img);
		$h = imagesy($img);
		$cc = strlen($chars);
		for($y=0;$y <$h ;$y+=$shrpns)
			for($x=0;$x <$w; $x+=$shrpns)
				imagestring($img,mt_rand(1,5),$x*$size,$y*$size,
					$chars[mt_rand(1,$cc)],
					//imagecolorat($img, $w-$x*$size-1,$h-$y*$size-1)
					imagecolorat($img, mt_rand(1,$w-1),mt_rand(1,$h-1)));
		return $img;
	}

	// get list of all available fonts
	function fontlist($path){
		return glob($path);
	}

	// select random font
	function randomfont($fonts){
		return $fonts[random_int(0,count($fonts))];
	}

	// add a string or char to image
	function addtext($img, $txt, $size, $x, $y, $font, $color, $angle){
		$fontcolor = $this->createcolor($img, $color);
		$box = @imageTTFBbox($size, $angle, $font, $txt);
		$textwidth = abs($box[4] - $box[0]);
		$textheight = abs($box[5] - $box[1]);
		$xcord = ($x - ($textwidth / 2));
		$ycord = ($y + ($textheight / 2));
		imagettftext($img, $size, $angle, $xcord, $ycord,
			$fontcolor, $font, $txt);
		return $img;
	}

	// add (more or less) horizontal string
	// with random font, angle, size and ...
	function addstring($img, $txt){
		$t = strlen($txt);
		for ($i=1; $i <= strlen($txt); $i++){
			$size = random_int($phpcaptchaConfig['fontsize'] * 0.5,
				$phpcaptchaConfig['fontsize'] * 1.5);
			$angle = random_int(-10,10);
			$font = $this->randomfont($fonts);
			$color = $phpcaptchaConfig['textcolor'];
			$char = $txt[$i];
			$fontcolor = $this->createcolor($img, $color);
			imagettftext($img, $size, $angle, $xcord, $ycord,
				$fontcolor, $font, $char);

			}
		return $img;
	}

	// blur an image
	function blur($img){
		$gaussian = array(array(1.0, 2.0, 1.0), array(2.0, 4.0, 2.0), array(1.0, 2.0, 1.0));
		imageconvolution($img, $gaussian, 16, 0);
	}

	/**
	 * @param $phpcaptchaConfig
	 * @param $captchaChallenge
	 */
	function renderImage($phpcaptchaConfig, $captchaChallenge) {
		//additional config
		$phpcaptchaConfig['slicesX'] = mt_rand(2,5);
		$phpcaptchaConfig['slicesY'] = mt_rand(2,5);
		$filltypes= array('horizontal','vertical','ellipse','ellipse2','circle','circle2','diamond','square');
		$phpcaptchaConfig['bgfill'] = $filltypes[mt_rand(0,count($filltypes)-1)];
		$phpcaptchaConfig['stringlengthmin'] = 5; //not jet used
		$phpcaptchaConfig['stringlengthmax'] = 8;

		header('Expires: Mon, 26 Jul 1990 05:00:00 GMT');
		header("Last-Modified: ".date("D, d M Y H:i:s")." GMT");
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', false);
		header('Pragma: no-cache');

		// get a list of available fonts
		$fonts =  $this->fontlist(__dir__.'/fonts/*.ttf');

		// create a background layer
		//$the_image = $this->newimage($phpcaptchaConfig['sizewidth'], $phpcaptchaConfig['sizeheight'],0);
		// fill background with color gradient
		//fill($the_image,$phpcaptchaConfig['bgfill'],$phpcaptchaConfig['bgcolor'],
		//	$this->complementory($phpcaptchaConfig['bgcolor']));

		$the_image = imagecreatetruecolor($phpcaptchaConfig['sizewidth'], $phpcaptchaConfig['sizeheight']);

		imagealphablending($the_image, true);
		imagesavealpha($the_image, true);

		// Make the background transparent
		$trans = imagecolorallocate($the_image, 1,2,3);
		imagecolortransparent($the_image, $trans);

		// add small random chars all over
		//$this->pixelfuck($the_image,$phpcaptchaConfig['charstouse']);

		// add the passphrase to the image
		$spacedText = $this->explodestring($captchaChallenge);
		$font = $this->randomfont($fonts);
		$font = __dir__.'/fonts/Lato-Regular.ttf';
		//$angle = random_int(-10,10);
		$angle=0;
		$this->addtext($the_image, $spacedText, $phpcaptchaConfig['fontsize'],($phpcaptchaConfig['sizewidth'] / 2) , ($phpcaptchaConfig['sizeheight'] / 2 - $phpcaptchaConfig['fontsize'] * 0.2 ),$font, $phpcaptchaConfig['textcolor'], $angle);

		// blur the image;
		//$this->blur($the_image);

		// output a table with slices of the image
		echo $this->slicedimage($the_image,$phpcaptchaConfig['slicesX'],$phpcaptchaConfig['slicesY']);

		imagedestroy($the_image);
	}

	// decode an image as mime codes
	function data_uri($img, $mime) {
		ob_start();
		imagepng($img);
		$ob_image = ob_get_clean();
		$base64   = base64_encode($ob_image);
		return ('data:' . $mime . ';base64,' . $base64);
	}

	// cut the image into slices and
	// return a table with mime codes
	function slicedimage($img, $nx, $ny){
		$dx = imagesx($img) / $nx;
		$dy = imagesy($img) / $ny;
		$tmp_img = $this->newimage($dx,$dy,127);
		$html = '<body style=\'overflow:hidden; margin:0; background-color:black;\'>'.
		$html =	'<table cellspacing="0" cellpadding="0"><tbody>';
		for ($y=0; $y <= $ny-1; $y++){
			$html .= '<tr>';
			for ($x=0; $x <= $nx-1; $x++){
				imagecopy($tmp_img,$img,0,0,$x*$dx,$y*$dy,$dx,$dy);
				$html .=  "<td><img src=\"".
					$this->data_uri($tmp_img,'image/png').
					"\" ></td>";
			}
			$html .= '</tr>';
		}
		$html .= '</thead></table>';
		$html .= '</body>';
		imagedestroy($tmp_img);
		return $html;
	}

	// create a new image
	function newimage($w,$h,$alpha) {
		if (function_exists('imagecreatetruecolor')) {
            $im = imagecreatetruecolor($w,$h);
        } elseif (function_exists('imagecreate')) {
            $im = imagecreate($w,$h);
        } else {
            die('Unable to create an image');
		}
		imagesavealpha($im, true);
		$color = imagecolorallocatealpha($im, 0, 0, 0, $alpha);
		imagefill($im, 0, 0, $color);
		return $im;
	}

	// found here: https://www.php.net/manual/de/function.imagecolorallocate.php
	function createcolor($pic,$color) {
		$crgb = hex2rgb($color);
		$color = imagecolorexact($pic,  $crgb[0], $crgb[1], $crgb[2]);
		if($color==-1) {
			 //color does not exist...
			 //test if we have used up palette
			 if(imagecolorstotal($pic)>=255) {
				  //palette used up; pick closest assigned color
				  $color = imagecolorclosest($pic,  $crgb[0], $crgb[1], $crgb[2]);
			 } else {
				  //palette NOT used up; assign new color
				  $color = imagecolorallocate($pic,  $crgb[0], $crgb[1], $crgb[2]);
			 }
		}
		return $color;
   	}

	 // convert dec to hex with padding
	function dechexpad($dec,$padnum){
		return substr('0000000000000000'.dechex($dec),-$padnum);
	}

	// return the complementory color
	// #000 -> #FFFFFF, #FFFFFF -> #000000, #112233 -> #EEDDCC
   	function complementory($color) {
		$crgb = hex2rgb($color);
		$ccrgb[0] = 255 - $crgb[0];
		$ccrgb[1] = 255 - $crgb[1];
		$ccrgb[2] = 255 - $crgb[2];
		return ('#' .
			$this->dechexpad($ccrgb[0],2).
			$this->dechexpad($ccrgb[1],2).
			$this->dechexpad($ccrgb[2],2));
	}

}
if(!isset($_GET['hash'])) {
	die();
}
$image = new renderimage($_GET['hash']);
