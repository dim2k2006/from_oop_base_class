<?php
session_start();
// This will create the image for the math problem that must be solved!!
require('captcha_config.php');
class CaptchaImage
{

	public function __construct()
	{

		// Create the image
		$im = imagecreatetruecolor(75, 30) or die_message('Error Creating Image > CaptchaImage');


		// Create some colors
		$white = imagecolorallocate($im, 255, 255, 255);
		$black = imagecolorallocate($im, 0, 0, 0);
		imagefilledrectangle($im, 0, 0, 399, 29, $white);

		// The text to draw
		switch(MATH_TYPE)
		{
			case 'add':
				$text = $_SESSION['integer_one'] . ' + ' . $_SESSION['integer_two'];
			break;

			case 'subtract':
				$text = $_SESSION['integer_one'] . ' - ' . $_SESSION['integer_two'];
			break;

			case 'multiply':
				$text = $_SESSION['integer_one'] . ' x ' . $_SESSION['integer_two'];
			break;
		}


		$font = strtolower('../fonts/tahoma.ttf');

		// Add the text
		imagettftext($im, 9, 0, 10, 20, $black, $font, $text);

		header("Content-type: image/gif");
		imagegif($im);
		imagedestroy($im);
	}
}

$imagecreate = new CaptchaImage();

?>