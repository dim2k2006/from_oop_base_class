<?php
session_start();
require('captcha_config.php');

/*
 *	Captcha Class
 *  @author Nickolas Whiting <nwhiting@hwsinet.com>
 *  @copyright Copyright (c) 2008, Nickolas Whiting
 *  @version 1.0.0
 *  @date August 19, 2008
 *
 *  This is a captcha class that uses math and is performed randomly throughout a website when a user inputs form data
 *  it currently supports the ability for adding, subtracting and multiplying whole numbers ( division will be added as
 *  it requries the checking if the two numbers divided will equal a whole number ).
 *
 *  This class works 2 ways it can be set to require validation or by default set to randomly require validation
 *  the way it is done randomly is by generating a number 1 - 1500 if the number falls between 1 and 250, 501 - 750, and 1001 - 1250
 *  it will not require captcha validation if the number is between 251 - 500, 751 - 1000, 1251 - 1500 captcha validation will be required
 *
 *  The second way is to force the validation, the way this is done is simply by calling force_validation() on the page a form is submited to
 *  and it will force a user to validate with captcha, usefull for logins, registrations etc.
 *
 *  Of Course there is a way to block the captcha from working on pages by calling disable_captcha() on the page that you want it disabled
 *
 *  The validation number is generated on the page the form is submitted on and redirected to the captcha validation page if captcha will
 *  be required, and allows the user to input the captcha if they get it wrong it keeps asking for a maximum number of times allowed once they
 *  exceed this number they are locked out of performing any task on the website, even viewing ( moraly wrong or not ) for a set time
 *
 *  All validation is done before anything is will be parsed on the page information is being submitted to :)
 *
 *  Known Bugs :
 *  Forms with uploaded data, the uploaded data will not be parsed...
 *  Forms that sent to a page that redirects the user after 
 *  submitting does not properly submit the form
 *
 */
class Captcha
{
	/*
	 * @param string Type of math used can be 'add', 'multiply' or 'subtract'
	 */

	public $integer_one = 0;

	public $integer_two = 0;

	private $max_try_count = 5;

	private $disable_validation = NULL;

	private $force_validation = NULL;

	private $require_validation = NULL;

	// PUBLIC VARS

	public $try_count = NULL;

	public $integer_total = NULL;

	public function __construct()
	{
		// Brute Force!!
		if($_COOKIE['captcha_fail'])
		{
			 die('You are unable to perform any actions at this time, due to failing to properly identify yourself. This will be lifted soon');
		}

		// SET try_count and integer totals
		if($this->try_count == NULL && !$_SESSION['captcha_count'])
		{
			$_SESSION['captcha_count'] = 0;
		}
		else
		{
			$this->try_count = $_SESSION['captcha_count'];
		}

		// Brute Force
		if($this->try_count >= $this->max_try_count)
		{
			setcookie('captcha_fail', '1', time() + (60 * 15));
			unset($_SESSION['captcha_total'], $_SESSION['integer_one'], $_SESSION['integer_two'], $_SESSION['captcha_count'], $_SESSION['validate_captcha'], $_SESSION['request_captcha']);
			die('You have execed the maximum number of attempts to properly identify yourself as a human, you will be unable to perform any actions for 15 minutes');
		}

		$this->generate_integer_one();

		$this->generate_integer_two();

		if($this->integer_total == NULL)
		{
			switch(MATH_TYPE)
			{
				case 'add':
					$this->integer_total = $this->integer_one + $this->integer_two;
				break;

				case 'multiply':
					$this->integer_total = $this->integer_one * $this->integer_two;
				break;

				case 'subtract':
					$this->integer_total = $this->integer_one - $this->integer_two;
				break;
			}

			
			$_SESSION['captcha_total'] = $this->integer_total;
			

		}

		$this->random_validation_gen();

	}

	public function __destruct()
	{
		// do nothing
	}

	private function generate_integer_one()
	{
		if(!$_POST['submitcaptcha'])
		{
			switch(MATH_TYPE)
			{
				case 'add':
				case 'multiply':
					$int = rand('1', '9');
				break;

				case 'subtract':
					$int = rand('10', '20');
				break;
			}
			$_SESSION['integer_one'] = $int;
		}
		else
		{
			$int = $_SESSION['integer_one'];
		}
		$this->integer_one = $int;
	}

	private function generate_integer_two()
	{
		if(!$_POST['submitcaptcha'])
		{
			switch(MATH_TYPE)
			{
				case 'add':
				case 'multiply':
					$int = rand('1', '9');
				break;

				case 'subtract':
					$int = rand('1', '9');
				break;
			}
			$_SESSION['integer_two'] = $int;
		}
		else
		{
			$int = $_SESSION['integer_two'];
		}

		$this->integer_two = $int;
	}

	// Generate Value for random check

	private function random_validation_gen()
	{
		$number = rand('1', '1500');
		if(count($_POST) != 0)
		{
			if($this->require_validation == NULL)
			{
				if($number >= 1 && $number <= 250 || $number >= 501 && $number <= 750 || $number >= 1001 && $number <= 1250 || isset($_SESSION['validate_captcha']) || $this->force_validation == TRUE)
				{
					$this->require_validation = TRUE;
					$_SESSION['validate_captcha'] = TRUE;
				}
				else
				{
					$this->require_validation =  FALSE;
				}
			}
		}
		else
		{
			// Since nothing has been submitted we dont need to validate anything
			$this->require_validation = FALSE;
		}
	}

	// Main Function!!
	// This will generate the captcha saving all post data from the form and if a captcha is correct automaticlly redirect a user or show
	// them a dreadfull error message! ! ! ! ! ! !

	public function generate_captcha()
	{
		$html .=  '<img align="absmiddle" src="inc/captcha_image.php" /> <input type="text" name="captcha" /> ';
		// this will take all post data stick it in a hidden element and then send it!
		foreach($_POST as $key => $val)
		{
			if($key == 'submitcaptcha' || $key == 'captcha')
			{
			}
			else
			{
				echo '
				<input type="hidden" name="'.$key.'" value="'.htmlentities(addslashes($val)).'" />
				';
			}
		}
		return $html;
	}

	public function exec()
	{
		$answer = (int) $_POST['captcha'];
		// VALIDATE CAPTCHa!!
		if($answer != $_SESSION['captcha_total'])
		{
			$html .= "<div class='errortxt'>Ответ <span>каптчи</span> неверен</div>";
		}
		else 
		{
			$html .= "";
		}
		return $html;
	}


	// PUBLIC FUNCTIONS

	// Set captcha to automatically be called on a page

	public function force_validation()
	{
		$this->force_validation = TRUE;
	}

	// Set captcha to never call itself on a page

	public function disable_validation()
	{
		$this->disable_validation = TRUE;
	}

}
?>