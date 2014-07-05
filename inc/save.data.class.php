<?php
class SaveData extends ValidateForm {
	public function save()
	{
		if(count($_POST)>0){
			foreach($_POST as $key => $value){
				$$key=$value;
			}
		}
		
		if(count($_POST)>0){
			$this->ValidField($name,'empty','Поле <span>Имя</span> обязательно к заполнению');
			$this->ValidField($email,'email','Поле <span>Email</span> обязательно к заполнению');
			$this->ValidField($text_message,'empty','Поле <span>Сообщение</span> обязательно к заполнению');
			
			$answer = (int) $_POST['captcha'];
			// VALIDATE CAPTCHa!!
			if($answer != $_SESSION['captcha_total'])
			{
				$captchaCheck = "<div class='errortxt'>Ответ <span>каптчи</span> неверен</div>";
			}
			else 
			{
				$captchaCheck = "";
			}
		}
		
		//return $this->ErrorString.$captchaCheck.$this->ErrSufix;
		
		
		if ($this->ErrorString == "" && $captchaCheck == "") {
			//return 'запись в файл';
			$name = str_replace(";", "",$name);
			$email = str_replace(";", "",$email);
			$text_message = str_replace(";", "",$text_message);
			
			$path = "./data/db.txt";
			$date = date("F");
			$date .= ' '.date("j");
			$date .= ', '.date("Y");
			$date .= ' в '.date("G");
			$date .= ':'.date("i");
				
			$ip = $_SERVER['REMOTE_ADDR'];
			$browser = $_SERVER['HTTP_USER_AGENT'];
			
			if (file_exists($path)) {
				$content = "\n".$name.';'.$email.';'.$text_message.';'.$date.';'.$ip.';'.$browser.';';
				$file = fopen($path, 'a+');
				fwrite($file, $content);
				fclose($file);
					
				setcookie("GuestBookName", $name, mktime(0, 0, 0, date("n"), date("j"), date("Y")+1));
				setcookie("GuestBookEmail", $email, mktime(0, 0, 0, date("n"), date("j"), date("Y")+1));
					
				$url = $_SERVER['PHP_SELF'].'#text';
				echo "<meta http-equiv='refresh' content='0;$url'>";
				exit;
			}
			
		} else {
			return $this->ErrorString.$captchaCheck.$this->ErrSufix;
		}
		
	}
}