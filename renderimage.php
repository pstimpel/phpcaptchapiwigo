<?php
require_once __DIR__ . '/phpcaptchaconfig.php';

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
        
        $result = '';
        
        if($phpcaptchaConfig['strictlowercase'] == true) {
            $characters = strtolower($phpcaptchaConfig['charstouse']);
        } else {
            $characters = $phpcaptchaConfig['charstouse'];
        }
        
        for($i=0;$i < $phpcaptchaConfig['stringlength']; $i++) {
            
            $selectedChar = rand(1, strlen($characters));
            
            $result = $result . substr($characters, $selectedChar - 1, 1);
            
        }
        
        
        return $result;
    }
    
    
    
    
    /**
     * @param $phpcaptchaConfig
     * @param $captchaChallenge
     */
    function renderImage($phpcaptchaConfig, $captchaChallenge) {
        
        header("Content-type: text/html");
        
        $spacedText = '';
        for($i=0; $i < strlen($captchaChallenge); $i++) {
            
            $spacedText = $spacedText . substr($captchaChallenge, $i, 1).' ';
            
        }
        $spacedText = substr($spacedText, 0, strlen($spacedText) - 1);
        
        $fontangle = "0";
        $font = dirname(__FILE__).'/fonts/Lato-Regular.ttf';
        
        $im = imagecreate($phpcaptchaConfig['sizewidth'], $phpcaptchaConfig['sizeheight']);
        
        $crgb = $this->convertRGBToArray($phpcaptchaConfig['bgcolor']);
        $bgcolor = imagecolorallocate($im,
            $crgb['r'],
            $crgb['g'],
            $crgb['b']);
        
        imagefilledrectangle($im, 0, 0,
            $phpcaptchaConfig['sizewidth'], $phpcaptchaConfig['sizeheight'], $bgcolor);
        
        
        $frgb = $this->convertRGBToArray($phpcaptchaConfig['textcolor']);
        $fontcolor = imagecolorallocate($im,
            $frgb['r'],
            $frgb['g'],
            $frgb['b']);
        
        $box = @imageTTFBbox($phpcaptchaConfig['fontsize'], $fontangle, $font, $spacedText);
        
        $textwidth = abs($box[4] - $box[0]);
        
        $textheight = abs($box[5] - $box[1]);
        
        $xcord = ($phpcaptchaConfig['sizewidth'] / 2) - ($textwidth / 2) - 2;
        
        $ycord = ($phpcaptchaConfig['sizeheight'] / 2) + ($textheight / 2);
        
        imagettftext($im, $phpcaptchaConfig['fontsize'], 0, $xcord, $ycord, $fontcolor, $font, $spacedText);
        
        if($phpcaptchaConfig['numberoflines']>0) {
            
            $lrgb = $this->convertRGBToArray($phpcaptchaConfig['linecolor']);
            $linecolor = imagecolorallocate($im,
                $lrgb['r'],
                $lrgb['g'],
                $lrgb['b']);
            
            for($i=1;$i<=$phpcaptchaConfig['numberoflines'];$i++) {
                
                $x1 = rand(0,$phpcaptchaConfig['sizewidth']);
                $y1 = rand(0,$phpcaptchaConfig['sizeheight']);
                
                $x2 = rand(0,$phpcaptchaConfig['sizewidth']);
                $y2 = rand(0,$phpcaptchaConfig['sizeheight']);
                
                for($j=0;$j<$phpcaptchaConfig['thicknessoflines'];$j++) {
                    
                    imageline($im, $x1, $y1 + $j,
                        $x2,
                        $y2 + $j,
                        $linecolor);
                    
                    
                }
            }
        }
                
        // output a table with slices of the image
        echo $this->slicedimage($im,mt_rand(2,5),mt_rand(2,5));
        imagedestroy($im);
    }

    // decode an image as Uniform Resource Identifier
    function data_uri($img, $mime) {  
        ob_start();
        imagepng($img);
        $ob_image = ob_get_clean();
        $base64   = base64_encode($ob_image); 
        return ('data:' . $mime . ';base64,' . $base64);
    }
    
    // cut the image into slices and
    // return a table with URI codes  
    function slicedimage($img, $nx, $ny){
        $dx = imagesx($img) / $nx;
        $dy = imagesy($img) / $ny;
        $html = '<body style=\'overflow:hidden; margin:0\'>'.
        $html = '<table cellspacing="0" cellpadding="0"><tbody>';
        for ($y=0; $y <= $ny-1; $y++){
            $html .= '<tr>';
            for ($x=0; $x <= $nx-1; $x++){
                $tmp_img = $this->newimage($dx,$dy,255);
                imagecopy($tmp_img,$img,0,0,$x*$dx,$y*$dy,$dx,$dy);
                $html .=  "<td><img src=\"".
                    $this->data_uri($tmp_img,'image/png').
                    "\" ></td>"; 
                imagedestroy($tmp_img);
            }   
            $html .= '</tr>';
        }
        $html .= '</thead></table>';
        $html .= '</body>';
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
    
    function convertRGBToArray($rgb) {
        $value = array('r'=>0, 'g'=>0, 'b'=>0);
        if (preg_match("/([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})/i", $rgb, $crgb)) {
            $value['r'] = hexdec($crgb[1]);
            $value['g'] = hexdec($crgb[2]);
            $value['b'] = hexdec($crgb[3]);
        }
        return $value;
    }
}

if(!isset($_GET['hash'])) {
    die();
}
$image = new renderimage($_GET['hash']);
