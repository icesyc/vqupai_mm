<?php
class Captcha {

	public $backColor =  0xffffff;
	public $foreColor = 0x2040A0;
	public $width = 144;
	public $height = 50;
	public $padding = 5;
	public $offset = -2;
	public $fontFile;

	public function __construct(){
		!session_id() && session_start();
	}

	public function verify($code){
		if(empty($_SESSION['vcode'])) return false;
		return $_SESSION['vcode'] == $code;
	}

	public function getCode(){
		$char = 'abcdefghijklmnopqrstuvwxyz';
		$_SESSION['vcode'] = substr(str_shuffle($char), 0, 4);
		return $_SESSION['vcode'];
	}

	public function image(){
		$code = $this->getCode();

		$image = imagecreatetruecolor($this->width,$this->height);

		$backColor = imagecolorallocate($image,
				(int)($this->backColor % 0x1000000 / 0x10000),
				(int)($this->backColor % 0x10000 / 0x100),
				$this->backColor % 0x100);
		imagefilledrectangle($image,0,0,$this->width,$this->height,$backColor);
		imagecolordeallocate($image,$backColor);

		$foreColor = imagecolorallocate($image,
				(int)($this->foreColor % 0x1000000 / 0x10000),
				(int)($this->foreColor % 0x10000 / 0x100),
				$this->foreColor % 0x100);

		if($this->fontFile === null)
			$this->fontFile = dirname(__FILE__) . '/SpicyRice.ttf';

		$length = strlen($code);
		$box = imagettfbbox(30,0,$this->fontFile,$code);
		$w = $box[4] - $box[0] + $this->offset * ($length - 1);
		$h = $box[1] - $box[5];
		$scale = min(($this->width - $this->padding * 2) / $w,($this->height - $this->padding * 2) / $h);
		$x = 10;
		$y = round($this->height * 27 / 40);
		for($i = 0; $i < $length; ++$i)
		{
			$fontSize = (int)(rand(26,32) * $scale * 0.8);
			$angle = rand(-10,10);
			$letter = $code[$i];
			$box = imagettftext($image,$fontSize,$angle,$x,$y,$foreColor,$this->fontFile,$letter);
			$x = $box[2] + $this->offset;
		}

		imagecolordeallocate($image,$foreColor);

		//wave
		$grade = 5;
		for($i=0;$i<$this->width;$i+=2){
            imagecopyresampled($image, $image, $i-2, sin($i/10)*$grade,$i,0,2,$this->height,2,$this->height);
        }

		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Content-Transfer-Encoding: binary');
		header("Content-Type: image/png");
		imagepng($image);
		imagedestroy($image);
	}
}