<?php
$vf = new formgeneration('valid');

$sd = new SaveData();

if(count($_POST)>0){
	$errors = $sd->save();
}

$captcha = new Captcha();

$html .= '<h1>Отвечаю.ru</h1>';

$html .= $errors;

$html .= "<form  action='' method='POST'>";

$html .= $vf->create_label('name', 'Имя: *')."\n";
$html .= $vf->create_text('name', 'required')."\n";

$html .= $vf->create_label('email', 'E-mail: *')."\n";
$html .= $vf->create_text('email', 'required')."\n";

$html .= $vf->create_label('text_message', 'Сообщение: *')."\n";
$html .= $vf->create_textarea('text_message', 'required', 10, 30)."\n";

$html .= '<div>';
$html .= $captcha->generate_captcha();
$html .= '</div>';

$html .= <<<EOD
<input class="form-input-button" type="reset" value="Очистить">
<input class="form-input-button send-button" type="submit" value="Отправить">
</form>
EOD;
